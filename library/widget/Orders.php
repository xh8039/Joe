<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}
class JoeOrders_Widget extends Typecho_Widget implements Widget_Interface_Do
{
    /**
     * 全局选项
     *
     * @access protected
     * @var Widget_Options
     */
    protected $options;

    /**
     * 用户对象
     *
     * @access protected
     * @var Widget_User
     */
    protected $user;

    /**
     * 安全模块
     *
     * @var Widget_Security
     */
    protected $security;

    /**
     * 数据库对象
     *
     * @access protected
     * @var Typecho_Db
     */
    protected $db;

    /**
     * 搜索引擎列表
     *
     * @var array
     */
    protected $bots;
    /**
     * 当前页
     *
     * @access private
     * @var integer
     */
    private $_currentPage;

    /**
     * 生成分页的内容
     *
     * @access private
     * @var array
     */
    private $_pageRow = array();

    /**
     * 分页计算对象
     *
     * @access private
     * @var Typecho_Db_Query
     */
    private $_countSql;

    /**
     * 所有文章个数
     *
     * @access private
     * @var integer
     */
    private $_total = false;

    /**
     * 构造函数,初始化组件
     *
     * @access public
     * @param mixed $request request对象
     * @param mixed $response response对象
     * @param mixed $params 参数列表
     */
    public function __construct($request, $response, $params = NULL)
    {
        parent::__construct($request, $response, $params);

        /** 初始化数据库 */
        $this->db = Typecho_Db::get();

        /** 初始化常用组件 */
        $this->options = $this->widget('Widget_Options');
        $this->user = $this->widget('Widget_User');
        $this->security = $this->widget('Widget_Security');

        $this->bots = ['wxpay' => '微信', 'alipay' => '支付宝', 'qqpay' => 'QQ'];
    }
    public function select()
    {
        return $this->db->select()->from('table.joe_pay');
    }
    /**
     * 执行函数
     *
     * @access public
     * @return void
     */
    public function execute()
    {
        /** 初始化分页变量 */
        // $pageSize = RobotsPlusPlus_Util::getConfig()->pageSize;
        $pageSize = 20;

        $select = $this->select();

        $this->parameter->setDefault(array(
            'pageSize' => $pageSize,
        ), false);

        $this->_currentPage = $this->request->get('page', 1);

        $hasPushed = false;

        /** 构建基础查询 */
        $select = $this->select();

        if (!empty($this->request->type)) {
            $select->where('table.joe_pay.type = ?', $this->request->type);
        }
        if (isset($this->request->status) && is_numeric($this->request->status)) {
            $select->where('table.joe_pay.status = ?', $this->request->status);
        }
        // if (!empty($this->parameter->api_trade_no)) {
        // 	$select->where('table.joe_pay.api_trade_no = ?', $this->parameter->api_trade_no);
        // }
        // if (!empty($this->parameter->content_title)) {
        // 	$select->where('table.joe_pay.content_title = ?', $this->parameter->content_title);
        // }

        /** 过滤标题 */
        if (null != ($keywords = $this->request->keywords)) {
            $select->where(
                'trade_no LIKE ? OR api_trade_no LIKE ? OR content_title LIKE ?',
                '%' . $keywords . '%',
                '%' . $keywords . '%',
                '%' . $keywords . '%',
            );
        }

        /** 如果已经提前压入则直接返回 */
        if ($hasPushed) {
            return;
        }

        /** 仅输出文章 */
        $this->_countSql = clone $select;

        /** 提交查询 */
        $select->order('table.joe_pay.create_time', Typecho_Db::SORT_DESC)
            ->page($this->_currentPage, $this->parameter->pageSize);

        $this->db->fetchAll($select, array($this, 'push'));
    }
    /**
     * 将每行的值压入堆栈
     *
     * @access public
     * @param array $value 每行的值
     * @return array
     */
    public function push(array $value)
    {
        $value = $this->filter($value);
        return parent::push($value);
    }

    /**
     * 通用过滤器
     *
     * @access public
     * @param array $value 需要过滤的行数据
     * @return array
     * @throws Typecho_Widget_Exception
     */
    public function filter(array $value)
    {
        $value['typeName'] = array_key_exists($value['type'], $this->bots) ? $this->bots[$value['type']] : $value['type'];
        $value['user_id'] = is_numeric($value['user_id']) ? $value['user_id'] : '游客';
        $value['pay_price'] = isset($value['pay_price']) ? '<font color="green">' . $value['pay_price'] . '</font>' : '未支付';
        $value['admin_email'] = $value['admin_email'] ? '<font color="green">已通知</font>' : '未通知';
        $value['user_email'] = $value['user_email'] ? '<font color="green">已通知</font>' : '未通知';
        $value['status'] = $value['status'] ? '<font color="green">已支付</font>' : '未支付';
        // $value['theId'] = 'robots-log-' . $value['lid'];
        return $value;
    }

    /**
     * 输出分页
     *
     * @access public
     * @param string $prev 上一页文字
     * @param string $next 下一页文字
     * @param int $splitPage 分割范围
     * @param string $splitWord 分割字符
     * @param string $template 展现配置信息
     * @return void
     */
    public function pageNav()
    {
        $query = $this->request->makeUriByRequest('page={page}');
        /** 使用盒状分页 */
        $nav = new Typecho_Widget_Helper_PageNavigator_Box(
            false === $this->_total ? $this->_total = $this->size($this->_countSql) : $this->_total,
            $this->_currentPage,
            $this->parameter->pageSize,
            $query
        );
        $nav->render('&laquo;', '&raquo;');
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        if (false === $this->_total) {
            $this->_total = $this->size($this->_countSql);
        }

        return $this->_total;
    }

    public function size(Typecho_Db_Query $condition)
    {
        return $this->db->fetchObject($condition
            ->select(array('COUNT(DISTINCT table.joe_pay.id)' => 'num'))
            ->from('table.joe_pay')
            ->cleanAttribute('group'))->num;
    }

    public function deleteLogs()
    {
        $logs = $this->request->filter('int')->getArray('id');
        $deleteCount = 0;
        foreach ($logs as $log) {
            // 删除插件接口
            $this->pluginHandle()->deleteLogs($log, $this);

            $result = $this->db->query($this->db->delete('table.joe_pay')->where('table.joe_pay.id = ?', $log));
            if ($result) $deleteCount++;
        }
        /** 设置提示信息 */
        $this->widget('Widget_Notice')->set(
            $deleteCount > 0 ? _t('订单已经被删除') : _t('没有订单被删除'),
            $deleteCount > 0 ? 'success' : 'notice'
        );
    }


    public function action()
    {
        $this->security->protect();
        $this->on($this->request->is('do=delete'))->deleteLogs();
        $this->response->goBack();
    }
}

<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}
class JoeFriends_Widget extends Typecho\Widget implements Widget_Interface_Do
{
    /**
     * 全局选项
     *
     * @access protected
     * @var Widget\Options
     */
    protected $options;

    /**
     * 用户对象
     *
     * @access protected
     * @var Widget\User
     */
    protected $user;

    /**
     * 安全模块
     *
     * @var Widget\Security
     */
    protected $security;

    /**
     * 数据库对象
     *
     * @access protected
     * @var Typecho\Db
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
     * @var Typecho\Db\Query
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
        $this->db = Typecho\Db::get();

        /** 初始化常用组件 */
        // $this->options = $this->widget('Widget\Options');
        // $this->user = $this->widget('Widget\User');
        // $this->security = $this->widget('Widget\Security');
    }
    public function select()
    {
        return $this->db->select()->from('table.friends');
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
        $pageSize = 10;

        $select = $this->select();

        $this->parameter->setDefault(array(
            'pageSize' => $pageSize,
        ), false);

        $this->_currentPage = $this->request->get('page', 1);

        $hasPushed = false;

        /** 构建基础查询 */
        $select = $this->select();

        if (isset($this->request->status) && is_numeric($this->request->status)) {
            $select->where('status = ?', $this->request->status);
        }

        /** 过滤标题 */
        if (null != ($keywords = $this->request->keywords)) {
            $select->where(
                'title LIKE ? OR url LIKE ? OR description LIKE ?',
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
        $select->order('id', Typecho\Db::SORT_DESC)
            ->page($this->_currentPage, $this->parameter->pageSize);

        // var_dump($select);

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
     * @throws Typecho\Widget\Exception
     */
    public function filter(array $value)
    {
        $value['status'] = $value['status'] ? '已通过' : '<font color="red">待审核</font>';
        if (!empty($value['position'])) {
            $value['position'] = str_replace(['index_bottom', 'single'], ['首页', '单页'], $value['position']);
        }
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
        $nav = new Typecho\Widget\Helper\PageNavigator\Box(
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

    public function size(Typecho\Db\Query $condition)
    {
        return $this->db->fetchObject($condition
            ->select(array('COUNT(DISTINCT id)' => 'num'))
            ->from('table.friends')
            ->cleanAttribute('group'))->num;
    }


    public function action() {}
}

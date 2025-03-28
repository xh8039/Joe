<?php

use think\facade\DbLog;

if (!defined('__TYPECHO_ROOT_DIR__') || empty($_SERVER['HTTP_HOST'])) {
	http_response_code(404);
	exit;
}

if (PHP_VERSION < '8') {
	throw new Typecho\Exception(base64_decode('5oKo55qEIFBIUCDniYjmnKzov4fkvY7vvIzor7fkvb/nlKggVjguMCDlj4rku6XkuIrniYjmnKzov5DooYwgPGEgaHJlZj0iaHR0cDovL2Jsb2cuYnJpNi5jbi9hcmNoaXZlcy8xOC5odG1sIiB0YXJnZXQ9Il9ibGFuayI+Sm9l5YaN57ut5YmN57yY5Li76aKYPC9hPg=='));
}

// 记录脚本开始执行的时间戳
define('JOE_START_TIME', microtime(true));

// 记录初始内存使用量
define('JOE_START_MEMORY', memory_get_usage());

// 主题文件夹名称
define('THEME_NAME', basename(JOE_ROOT));

// 通过 siteUrl 获取域名
define('JOE_DOMAIN', parse_url(Helper::options()->siteUrl, PHP_URL_HOST));

header('Yihang-Typecho-Joe: true');

/* 继承方法函数 */
require_once JOE_ROOT . 'public/widget.php';

/* Composer 自动加载 */
require_once JOE_ROOT . 'public/autoload.php';

/* ThinkORM 数据库配置 */
require_once JOE_ROOT . 'public/database.php';

/* 公用函数 */
require_once JOE_ROOT . 'public/function.php';

/* 过滤内容函数 */
require_once JOE_ROOT . 'public/parse.php';

/* 主题内置开放API */
require_once JOE_ROOT . 'public/api.php';

/* 插件方法 */
require_once JOE_ROOT . 'public/factory.php';

/* 主题初始化 */
function themeInit($self)
{
	/** 首次启用安装主题 */
	joe\install();
	/* 强制用户要求填写邮箱 */
	Helper::options()->commentsRequireMail = true;
	/* 强制用户要求无需填写url */
	Helper::options()->commentsRequireURL = false;
	/* 强制用户开启评论回复 */
	Helper::options()->commentsThreaded = true;
	/* 强制回复楼层最高999层 */
	Helper::options()->commentsMaxNestingLevels = 999;

	$is_iframe = (isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] === 'iframe') || isset($_GET['iframe']) || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');

	if (Helper::options()->JForceBrowser == 'on' && (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false) && !$is_iframe) {
		// 我就不信这次腾讯会再给封了！！！
		$self->response->setStatus(200);
		$self->setThemeFile('module/browser.php');
	}

	$GLOBALS['session_save_path'] = session_save_path();

	if (!joe\is_session_started()) session_start();

	if (!isset($GLOBALS['JOE_USER'])) {
		$user = \Widget\User::alloc();
		$GLOBALS['JOE_USER'] = $user;
		if ($user->hasLogin()) {
			if (!defined('USER_ID')) define('USER_ID', $user->uid);
		} else {
			$cookiesid = Typecho\Cookie::get('joe_user_id');
			if ((!$cookiesid) || (!preg_match('/^[0-9a-z]{32}$/i', $cookiesid))) {
				$cookiesid = md5(uniqid(mt_rand(), 1) . time());
				Typecho\Cookie::set('joe_user_id', $cookiesid, 31536000 * 10); // 游客用户ID存储十年
			}
			if (!defined('USER_ID')) define('USER_ID', $cookiesid);
		}
	}

	$path_info = $self->request->getPathInfo();

	/* 主题开放API 路由规则 */
	if (str_starts_with($path_info, '/joe/api')) {
		$path_info_explode = explode('/', $path_info);
		$route = empty($path_info_explode[3]) ? $self->request->routeType : $path_info_explode[3];
		if ($route && !is_numeric($route)) {
			if (str_ends_with($route, '.json')) $route = substr($route, 0, -5);
			$method = think\helper\Str::camel($route);
			$method_exists = method_exists(joe\Api::class, $method);
			if (!$method_exists) $self->response->throwJson(['code' => 404, 'message' => '接口不存在']);
			$self->response->setStatus(200);
			joe\Api::$self = $self;
			joe\Api::$options = Helper::options();
			joe\Api::$user = $GLOBALS['JOE_USER'];
			$api = joe\Api::$method($self);
			if (is_array($api) || is_object($api)) {
				if (Helper::options()->JoeDeBug == 'on') {
					if (is_array($api)) {
						if (array_is_list($api)) {
							$api[array_key_last($api)]['fetchSql'] = DbLog::list();
						} else {
							$api['fetchSql'] = DbLog::list();
						}
					}
					if (is_object($api)) $api->fetchSql = DbLog::list();
				}
				$self->response->throwJson($api);
			}
			if (is_string($api)) $self->response->throwContent($api);
			if ($api === true) $self->response->throwContent('');
		} else {
			$self->response->throwJson(['code' => 404, 'message' => '未调用接口']);
		}
	}

	if (str_starts_with($path_info, '/goto') && Helper::options()->JPostLinkRedirect == 'on') {
		(function () use ($self) {
			$self->response->setStatus(200);
			$url = base64_decode($self->request->url);
			if (!preg_match('/^https?:\/\/[^\s]*/i', $url)) {
				$self->response->throwContent('<script>alert("链接非法，已返回");window.location.href="' . Helper::options()->siteUrl . '"</script>');
			}
			$self->setThemeFile('module/goto.php');
		})();
	}

	if (Helper::options()->JUser_Switch == 'on') {
		// 增加自定义登录页面
		if (str_starts_with($path_info, '/user/login')) {
			$self->response->setStatus(200);
			$self->setThemeFile('module/user/login.php');
		}
		// 增加自定义注册页面
		if (Helper::options()->allowRegister && str_starts_with($path_info, '/user/register')) {
			$self->response->setStatus(200);
			$self->setThemeFile('module/user/register.php');
		}
		// 增加用户找回密码页面
		if (Helper::options()->JUserRetrieve == 'on' && str_starts_with($path_info, '/user/retrieve')) {
			$self->response->setStatus(200);
			$self->setThemeFile('module/user/retrieve.php');
		}
	}

	/* 增加自定义SiteMap功能 */
	if (Helper::options()->JSiteMap && Helper::options()->JSiteMap !== 'off') {
		if (str_starts_with($self->request->getRequestUri(), '/sitemap.xml')) {
			$self->response->setStatus(200);
			$self->setThemeFile("module/sitemap.php");
		}
	}
}

/* 增加自定义字段 */
function themeFields($layout)
{
	$keywords = new \Typecho\Widget\Helper\Form\Element\Text(
		'keywords',
		NULL,
		NULL,
		'SEO关键词',
		'介绍：用于设置当前页SEO关键词 <br />
		 注意：多个关键词使用英文逗号进行隔开 <br />
		 例如：Typecho,Typecho主题,Typecho模板 <br />
		 其他：如果不填写此项，则默认取文章标签，不占用数据库字段空间'
	);
	$layout->addItem($keywords);

	$description = new \Typecho\Widget\Helper\Form\Element\Text(
		'description',
		NULL,
		NULL,
		'SEO描述语',
		'介绍：用于设置当前页SEO描述语 <br />
		 注意：SEO描述语不应当过长也不应当过少 <br />
		 其他：如果不填写此项，则默认截取文章片段，不占用数据库字段空间'
	);
	$layout->addItem($description);

	$thumb = new \Typecho\Widget\Helper\Form\Element\Text(
		'thumb',
		NULL,
		NULL,
		'自定义缩略图',
		'建议尽量将缩略图放到文章的图片中，减少数据库空间占用<br>
		填写时：将会显示填写的文章缩略图<br>
		 不填写时：<br>
			1、若文章有图片则取文章内图片<br>
			2、若文章无图片，并且外观设置里未填写·自定义缩略图·选项，则取模板自带图片<br>
			3、若文章无图片，并且外观设置里填写了·自定义缩略图·选项，则取自定义缩略图图片<br>
			4、不占用数据库字段空间<br>
		 注意：多个缩略图时使用 || 分割填写（仅在三图模式下生效）'
	);
	$layout->addItem($thumb);

	$abstract = new \Typecho\Widget\Helper\Form\Element\Text(
		'abstract',
		NULL,
		NULL,
		'自定义摘要',
		'填写时：将会显示填写的摘要 <br>
		 不填写时：默认取文章里的内容，不占用数据库字段空间'
	);
	$layout->addItem($abstract);

	$video = new \Typecho\Widget\Helper\Form\Element\Textarea(
		'video',
		NULL,
		NULL,
		'M3U8或MP4地址',
		'<b style="color:#666;">该功能已废弃使用，后续版本将删除，请使用主题文章编辑器中的视频列表模块</b>
		<script>
		const fieldsVideo = document.querySelector(\'[name="fields[video]"]\');
		if (!fieldsVideo.value) fieldsVideo.style.display = "none";
		</script>'
	);
	$layout->addItem($video);

	$max_image_height = new \Typecho\Widget\Helper\Form\Element\Text(
		'max_image_height',
		NULL,
		NULL,
		'PC端图片极限高度',
		'介绍：用于设置当前页的图片最高高度 <br />
		 例如：40vh、300px、unset <br />
		 注意：填写 unset 即可使用自动高度 <br />
		 其他：如果不填写此项，则默认为40vh，不占用数据库字段空间'
	);
	$layout->addItem($max_image_height);

	$mode = new \Typecho\Widget\Helper\Form\Element\Select(
		'mode',
		['' => '默认模式（不占数据）', 'default' => '一图模式', 'single' => '大图模式', 'multiple' => '三图模式', 'none' => '无图模式'],
		NULL,
		'文章显示方式',
		'介绍：用于设置当前文章在首页和搜索页的显示方式 <br />
		 注意：独立页面该功能不会生效'
	);
	$layout->addItem($mode);

	$hide = new \Typecho\Widget\Helper\Form\Element\Select(
		'hide',
		['' => '默认评论可见（不占数据）', 'comment' => '评论可见', 'pay' => '付费可见', 'login' => '登录可见'],
		NULL,
		'隐藏内容模式',
		'可将隐藏内容设置为评论可见、付费可见、登录可见'
	);
	$layout->addItem($hide);

	$price = new \Typecho\Widget\Helper\Form\Element\Text(
		'price',
		NULL,
		NULL,
		'隐藏内容付费金额',
		'说明：金额设置为 0 则是免费资源<br><b style="color:#666;">注意：付费可见功能需先在文章编辑器内添加隐藏内容模块，并在 [主题设置=>付费设置] 处配置好您的支付信息后可用，否则不生效</b>'
	);
	$price->setAttribute('style', 'display:none');
	$layout->addItem($price);

	$pay_box_position = new \Typecho\Widget\Helper\Form\Element\Select(
		'pay_box_position',
		['' => '默认位置（不占数据）', 'top' => '文章内容顶部', 'bottom' => '文章内容底部', 'none' => '不显示'],
		NULL,
		'付费阅读模块显示位置',
		'在文章页面中购买模块的显示位置'
	);
	$layout->addItem($pay_box_position);

	$pay_tag_background = new \Typecho\Widget\Helper\Form\Element\Select(
		'pay_tag_background',
		['' => '默认颜色（不占数据）', 'yellow' => '渐变黄', 'blue' => '渐变蓝', 'cyan' => '渐变青', 'green' => '渐变绿', 'purple' => '渐变紫', 'red' => '渐变红', 'pink' => '渐变粉', 'vip1' => '豪华VIP', 'vip2' => '轻奢VIP', 'none' => '不显示'],
		NULL,
		'付费阅读标签背景颜色',
		'<script>
			const payPriceInput = document.querySelector(\'input[name="fields[price]"]\').parentElement.parentElement.parentElement;
			const pay_box_position = document.querySelector(\'select[name="fields[pay_box_position]"]\').parentElement.parentElement.parentElement;
			const pay_tag_background = document.querySelector(\'select[name="fields[pay_tag_background]"]\').parentElement.parentElement.parentElement;
			if (document.querySelector(\'select[name="fields[hide]"]\').value === "pay") {
				pay_box_position.style.display = "table-row";
				pay_tag_background.style.display = "table-row";
				payPriceInput.style.display = "table-row";
			} else {
			 	pay_box_position.style.display = "none";
				pay_tag_background.style.display = "none";
				payPriceInput.style.display = "none";
			}
			document.querySelector(\'select[name="fields[hide]"]\').addEventListener("change", () => {
				if (document.querySelector(\'select[name="fields[hide]"]\').value === "pay") {
					pay_box_position.style.display = "table-row";
					pay_tag_background.style.display = "table-row";
					payPriceInput.style.display = "table-row";
				} else {
				 	pay_box_position.style.display = "none";
					pay_tag_background.style.display = "none";
					payPriceInput.style.display = "none";
				}
			});
		</script>'
	);
	$layout->addItem($pay_tag_background);

	$global_advert = new \Typecho\Widget\Helper\Form\Element\Select(
		'global_advert',
		['' => '默认显示（不占数据）', 'display' => '显示', 'hide' => '隐藏'],
		NULL,
		'是否显示全局广告',
	);
	$layout->addItem($global_advert);

	if (Helper::options()->JPost_Record_Detection == 'on') {
		$baidu_push = new \Typecho\Widget\Helper\Form\Element\Select(
			'baidu_push',
			['' => '默认未推送（不占数据）', '0' => '未推送', '1' => '已推送'],
			NULL,
			'百度收录推送状态',
		);
		$layout->addItem($baidu_push);
	}
}

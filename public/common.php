<?php

if (!defined('__TYPECHO_ROOT_DIR__') || empty($_SERVER['HTTP_HOST'])) {
	http_response_code(404);
	exit;
}

if (PHP_VERSION < base64_decode('Ny40')) {
	throw new Typecho_Exception(base64_decode('5oKo55qEUEhQ54mI5pys6L+H5L2O77yM6K+35L2/55SoVjcuNOWPiuS7peS4iueJiOacrOi/kOihjCA8YSBocmVmPSJodHRwOi8vYmxvZy5icmk2LmNuL2FyY2hpdmVzLzE4Lmh0bWwiIHRhcmdldD0iX2JsYW5rIj5Kb2Xlho3nu63liY3nvJjkuLvpopg8L2E+'));
}

// 记录脚本开始执行的时间戳
define('JOE_START_TIME', microtime(true));

// 记录初始内存使用量
define('JOE_START_MEMORY', memory_get_usage());

// 主题文件夹名称
define('THEME_NAME', basename(JOE_ROOT));

// 通过 siteUrl 获取域名
define('JOE_DOMAIN', parse_url(Helper::options()->siteUrl, PHP_URL_HOST));

header('Generator: YiHang');
header('Author: YiHang');

/* 继承方法函数 */
require_once(JOE_ROOT . 'public/widget.php');

/* Composer自动加载 */
require_once(JOE_ROOT . 'vendor/autoload.php');

/* 公用函数 */
require_once(JOE_ROOT . 'public/function.php');

/* 过滤内容函数 */
require_once(JOE_ROOT . 'public/parse.php');

/* 主题内置开放API */
require_once(JOE_ROOT . 'public/route.php');

/* 插件方法 */
require_once(JOE_ROOT . 'public/factory.php');

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

	/** 屏蔽垃圾机器人 */
	if (Helper::options()->JShieldScan != 'off') require_once JOE_ROOT . 'public/tencent_protect.php';

	if (Helper::options()->JPrevent == 'on' && (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false)) {
		// 我就不信这次腾讯会再给封了！！！
		require JOE_ROOT . 'module/jump.php';
		exit;
	}

	if (!joe\is_session_started()) session_start();

	if (!isset($GLOBALS['JOE_USER'])) {
		Typecho_Widget::widget('Widget_User')->to($user);
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

	$GLOBALS['theme_this'] = $self;

	/* 主题开放API 路由规则 */
	if (strpos($self->request->getPathInfo(), "/joe/api") === 0) {
		switch ($self->request->routeType) {
			case 'publish_list':
				_getPost($self);
				break;
			case 'baidu_record':
				_getRecord($self);
				break;
			case 'baidu_push':
				_pushRecord($self);
				break;
			case 'bing_push':
				_pushBing($self);
				break;
			case 'handle_views':
				_handleViews($self);
				break;
			case 'handle_agree':
				_handleAgree($self);
				break;
			case 'wallpaper_type':
				_getWallpaperType($self);
				break;
			case 'wallpaper_list':
				_getWallpaperList($self);
				break;
			case 'maccms_list':
				_getMaccmsList($self);
				break;
			case 'huya_list':
				_getHuyaList($self);
				break;
			case 'server_status':
				_getServerStatus($self);
				break;
			case 'comment_lately':
				_getCommentLately($self);
				break;
			case 'article_filing':
				_getArticleFiling($self);
				break;
				// 提交友链
			case 'friend_submit':
				_friendSubmit($self);
				break;
			case 'statistics':
				_getstatistics($self);
				break;
			case 'meting':
				_Meting($self);
				break;
			case 'pay_cashier_modal':
				_payCashierModal($self);
				break;
			case 'initiate_pay':
				_initiatePay($self);
			case 'user_rewards_modal':
				_userRewardsModal($self);
			case 'check_pay':
				_checkPay($self);
				break;
		};
	}

	if (strpos($self->request->getPathInfo(), '/goto') === 0 && Helper::options()->JPostLinkRedirect == 'on') {
		(function () use ($self) {
			$self->response->setStatus(200);
			$location = Helper::options()->siteUrl;
			$link = base64_decode($self->request->url);
			$cid = $self->request->cid;
			if (is_numeric($cid)) {
				$db = Typecho_Db::get();
				$post = $db->fetchRow($db->select('text')->from('table.contents')->where('cid = ?', $cid));
				if (!empty($post['text']) && strpos($post['text'], $link) !== false) $location = $link;
			}
			if ($location == Helper::options()->siteUrl) {
				echo '<script>alert("链接非法，已返回");window.location.href="' . $location . '"</script>';
			} else {
				require_once JOE_ROOT . '/module/goto.php';
			}
			$self->response->throwContent('');
		})();
	}

	if (Helper::options()->JUser_Switch == 'on') {
		// 增加自定义登录页面
		if (strpos($self->request->getRequestUri(), 'user/login') !== false) {
			$self->response->setStatus(200);
			$self->setThemeFile('user/login.php');
		}
		// 增加自定义注册页面
		if (Helper::options()->allowRegister) {
			if (strpos($self->request->getRequestUri(), 'user/register') !== false) {
				$self->response->setStatus(200);
				$self->setThemeFile('user/register.php');
			}
		}
		// 增加自定义登录注册页面API
		if (strpos($self->request->getRequestUri(), 'user/api') !== false) {
			$self->response->setStatus(200);
			$self->setThemeFile('user/api.php');
		}
		// 增加用户找回密码页面
		if (Helper::options()->JUser_Forget == 'on') {
			if (strpos($self->request->getRequestUri(), 'user/forget') !== false) {
				$self->response->setStatus(200);
				$self->setThemeFile('user/forget.php');
			}
		}
	}

	/* 增加自定义SiteMap功能 */
	if (Helper::options()->JSiteMap && Helper::options()->JSiteMap !== 'off') {
		if (strpos($self->request->getRequestUri(), 'sitemap.xml') !== false) {
			$self->response->setStatus(200);
			$self->setThemeFile("module/sitemap.php");
		}
	}
}

/* 增加自定义字段 */
function themeFields($layout)
{
	$price = new \Typecho\Widget\Helper\Form\Element\Text(
		'price',
		NULL,
		NULL,
		'隐藏内容付费金额',
		'设置为 0 则是免费资源，付费可见功能需要在 [主题设置=>付费设置] 处配置好您的支付信息后可用'
	);
	$price->setAttribute('style', 'display:none');
	$layout->addItem($price);



	$keywords = new \Typecho\Widget\Helper\Form\Element\Text(
		'keywords',
		NULL,
		NULL,
		'SEO关键词',
		'介绍：用于设置当前页SEO关键词 <br />
		 注意：多个关键词使用英文逗号进行隔开 <br />
		 例如：Typecho,Typecho主题,Typecho模板 <br />
		 其他：如果不填写此项，则默认取文章标签'
	);
	$layout->addItem($keywords);

	$description = new \Typecho\Widget\Helper\Form\Element\Text(
		'description',
		NULL,
		NULL,
		'SEO描述语',
		'介绍：用于设置当前页SEO描述语 <br />
		 注意：SEO描述语不应当过长也不应当过少 <br />
		 其他：如果不填写此项，则默认截取文章片段'
	);
	$layout->addItem($description);

	$thumb = new \Typecho\Widget\Helper\Form\Element\Text(
		'thumb',
		NULL,
		NULL,
		'自定义缩略图',
		'填写时：将会显示填写的文章缩略图 <br>
		 不填写时：<br>
			1、若文章有图片则取文章内图片 <br>
			2、若文章无图片，并且外观设置里未填写·自定义缩略图·选项，则取模板自带图片 <br>
			3、若文章无图片，并且外观设置里填写了·自定义缩略图·选项，则取自定义缩略图图片 <br>
		 注意：多个缩略图时使用 || 分割填写（仅在三图模式下生效）'
	);
	$layout->addItem($thumb);

	$abstract = new \Typecho\Widget\Helper\Form\Element\Text(
		'abstract',
		NULL,
		NULL,
		'自定义摘要',
		'填写时：将会显示填写的摘要 <br>
		 不填写时：默认取文章里的内容'
	);
	$layout->addItem($abstract);

	$video = new \Typecho\Widget\Helper\Form\Element\Textarea(
		'video',
		NULL,
		NULL,
		'M3U8或MP4地址',
		'填写后，文章会插入一个视频模板 <br>
		 格式：[视频名称--视频介绍](视频地址)。如果有多个，换行写即可 <br>
		 例如：<br>
		 [第01集--凭什么仙家就可以遨游天地，而我等凡人只能做这井底之蛙](https://txmov2.a.kwimgs.com/upic/2022/08/20/07/BMjAyMjA4MjAwNzA5MzJfMTg0NzU1MDY3M184MjI2NDMxMTgyOV8yXzM=_b_Bb964ab3fd8fad18a949ed715402c992b.mp4)<br>
		 [第02集--韩大哥，没有灵根......真的不能成为修仙者吗](https://alimov2.a.kwimgs.com/upic/2022/07/24/23/BMjAyMjA3MjQyMzU1MzdfMjYxMzE4ODhfODAwNTQ2NzczNDhfMl8z_b_B6e7adb80a3c3cad6f66d318c66c48b68.mp4)'
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
		 其他：如果不填写此项，则默认为40vh'
	);
	$layout->addItem($max_image_height);

	$hide = new \Typecho\Widget\Helper\Form\Element\Select(
		'hide',
		['' => '默认评论可见（不占数据）', 'comment' => '评论可见', 'pay' => '付费可见', 'login' => '登录可见'],
		NULL,
		'隐藏内容模式',
		'可将隐藏内容设置为评论可见、付费可见、登录可见'
	);
	$layout->addItem($hide);

	$mode = new \Typecho\Widget\Helper\Form\Element\Select(
		'mode',
		['' => '默认模式（不占数据）', 'default' => '一图模式', 'single' => '大图模式', 'multiple' => '三图模式', 'none' => '无图模式'],
		NULL,
		'文章显示方式',
		'介绍：用于设置当前文章在首页和搜索页的显示方式 <br />
		 注意：独立页面该功能不会生效'
	);
	$layout->addItem($mode);

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

	if (Helper::options()->JPost_Record_Detection == 'on') {
		$baidu_push = new \Typecho\Widget\Helper\Form\Element\Select(
			'baidu_push',
			['' => '默认未推送（不占数据）', 'no' => '未推送', 'yes' => '已推送'],
			NULL,
			'百度收录推送状态',
		);
		$layout->addItem($baidu_push);
	}

	$global_advert = new \Typecho\Widget\Helper\Form\Element\Select(
		'global_advert',
		['' => '默认显示（不占数据）', 'display' => '显示', 'hide' => '隐藏'],
		NULL,
		'是否显示全局广告',
	);
	$layout->addItem($global_advert);
}

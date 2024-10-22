<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

define('JOE_VERSION', '1.3271');
define('JOE_ROOT', dirname(__FILE__) . '/');
define('THEME_NAME', basename(__DIR__));
define('JOE_BASE_API', Helper::options()->rewrite == 0 ? Helper::options()->rootUrl . '/index.php/joe/api' : Helper::options()->rootUrl . '/joe/api');

/* Joe核心文件 */
require_once(__DIR__ . '/public/common.php');

function themeConfig($form)
{
	// 注册后台页面
	// Typecho_Plugin::factory('admin/friend.php')->register('FriendLinks', 'mytheme_page', 'My Custom Page');

	$_db = Typecho_Db::get();
	$_prefix = $_db->getPrefix();
	try {
		$adapter = $_db->getAdapterName();
		$joe_pay = $_prefix . "joe_pay";
		if ("Pdo_SQLite" === $adapter || "SQLite" === $adapter) {
			$_db->query("CREATE TABLE IF NOT EXISTS '$joe_pay' (
						id INTEGER PRIMARY KEY,
						trade_no TEXT NOT NULL,
						api_trade_no TEXT,
						name TEXT NOT NULL,
						content_title TEXT,
						content_cid INTEGER NOT NULL,
						type TEXT NOT NULL,
						money TEXT NOT NULL,
						ip TEXT,
						user_id TEXT NOT NULL,
						create_time TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
						update_time TEXT,
						pay_type TEXT,
						pay_price TEXT,
						admin_email INTEGER DEFAULT 0
						user_email INTEGER DEFAULT 0
						status INTEGER DEFAULT 0");
		}
		if ("Pdo_Mysql" === $adapter || "Mysql" === $adapter) {
			$_db->query("CREATE TABLE IF NOT EXISTS `$joe_pay` (
						`id` INT NOT NULL AUTO_INCREMENT,
						`trade_no` varchar(64) NOT NULL unique,
						`api_trade_no` varchar(64) DEFAULT NULL,
						`name` varchar(64) NOT NULL,
						`content_title` varchar(150) DEFAULT NULL,
						`content_cid` INT NOT NULL,
						`type` varchar(10) NOT NULL,
						`money` varchar(32) NOT NULL,
						`ip` varchar(32) DEFAULT NULL,
						`user_id` varchar(32) NOT NULL,
						`create_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
						`update_time` DATETIME DEFAULT NULL,
						`pay_type` varchar(10) DEFAULT NULL,
						`pay_price` varchar(32) DEFAULT NULL,
						`admin_email` BOOLEAN NOT NULL DEFAULT FALSE,
						`user_email` BOOLEAN NOT NULL DEFAULT FALSE,
						`status` BOOLEAN NOT NULL DEFAULT FALSE,
						PRIMARY KEY  (`id`)
					) DEFAULT CHARSET=utf8mb4;");
		}
		$table_contents = $_db->fetchRow($_db->select()->from('table.contents')->page(1, 1));
		$table_contents = empty($table_contents) ? [] : $table_contents;
		if (!array_key_exists('views', $table_contents)) {
			$_db->query('ALTER TABLE `' . $_prefix . 'contents` ADD `views` INT DEFAULT 0;');
		}
		if (!array_key_exists('agree', $table_contents)) {
			$_db->query('ALTER TABLE `' . $_prefix . 'contents` ADD `agree` INT DEFAULT 0;');
		}
	} catch (Exception $e) {
	}
?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/typecho/config/css/joe.config.css') ?>">
	<script src="<?= joe\cdn('jquery/3.6.0/jquery.min.js') ?>"></script>
	<script src="<?= joe\cdn('layer/3.5.1/layer.min.js') ?>"></script>
	<script>
		window.Joe = {
			title: `<?= trim(Helper::options()->title ?? '') ?>`,
			version: `<?= trim(JOE_VERSION) ?>`,
			logo: `<?= trim(Helper::options()->JLogo ?? '') ?>`,
			Favicon: `<?= trim(Helper::options()->JFavicon ?? '') ?>`
		}
	</script>
	<script src="<?= joe\theme_url('assets/typecho/config/js/joe.config.min.js') ?>"></script>
	<div class="joe_config">
		<div>
			<div class="joe_config__aside">
				<div class="logo">Joe再续前缘<?= JOE_VERSION ?></div>
				<ul class="tabs">
					<li class="item" data-current="joe_notice">最新公告</li>
					<li class="item" data-current="joe_global">全局设置</li>
					<li class="item" data-current="joe_safe">安全设置</li>
					<li class="item" data-current="joe_image">图片设置</li>
					<li class="item" data-current="joe_post">文章设置</li>
					<li class="item" data-current="joe_aside">侧栏设置</li>
					<li class="item" data-current="joe_index">首页设置</li>
					<li class="item" data-current="joe_decoration">特效设置</li>
					<li class="item" data-current="joe_user">登录设置</li>
					<li class="item" data-current="joe_music">音乐设置</li>
					<li class="item" data-current="joe_friend">友链设置</li>
					<li class="item" data-current="joe_comment">评论设置</li>
					<li class="item" data-current="joe_statistic">统计设置</li>
					<li class="item" data-current="joe_message">消息推送</li>
					<a class="item" data-current="joe_code" href="<?= Helper::options()->rootUrl . __TYPECHO_ADMIN_DIR__ ?>options-theme.php?joe_code=true">插入代码</a>
					<li class="item" data-current="joe_other">其他设置</li>
				</ul>
				<?php require_once('public/backup.php'); ?>
			</div>
		</div>
		<div class="joe_config__notice">请求数据中...</div>
	<?php

	// 全局设置
	require_once('options/global.php');

	// 安全设置
	require_once('options/safe.php');

	// 图片设置
	require_once('options/image.php');

	// 文章设置
	require_once('options/post.php');

	// 侧栏设置
	require_once('options/aside.php');

	// 首页设置
	require_once('options/index.php');

	// 特效设置
	require_once('options/decoration.php');

	// 登录设置
	require_once('options/user.php');

	// 音乐设置
	require_once('options/music.php');

	// 友链设置
	require_once('options/friend.php');

	// 评论设置
	require_once('options/comment.php');

	// 统计设置
	require_once('options/statistic.php');

	// 消息推送
	require_once('options/message.php');

	// 自定义代码
	require_once('options/code.php');

	// 其他设置
	require_once('options/other.php');
}

header('Generator: YiHang');
header('Author: YiHang');

if (!empty(Helper::options()->JCustomFunctionsCode)) {
	file_put_contents(JOE_ROOT . 'JCustomFunctionsCode.txt', Helper::options()->JCustomFunctionsCode);
	include_once JOE_ROOT . 'JCustomFunctionsCode.txt';
	unlink(JOE_ROOT . 'JCustomFunctionsCode.txt');
}

<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

define('JOE_VERSION', '1.22');
define('JOE_ROOT', dirname(__FILE__) . '/');

/* Joe核心文件 */
require_once("core/core.php");

function themeConfig($form)
{
	$_db = Typecho_Db::get();
	$_prefix = $_db->getPrefix();
	try {
		if (!array_key_exists('views', $_db->fetchRow($_db->select()->from('table.contents')->page(1, 1)))) {
			$_db->query('ALTER TABLE `' . $_prefix . 'contents` ADD `views` INT DEFAULT 0;');
		}
		if (!array_key_exists('agree', $_db->fetchRow($_db->select()->from('table.contents')->page(1, 1)))) {
			$_db->query('ALTER TABLE `' . $_prefix . 'contents` ADD `agree` INT DEFAULT 0;');
		}
	} catch (Exception $e) {
	}
?>
	<link rel="stylesheet" href="<?= Joe::themeUrl('typecho/config/css/joe.config.css') ?>">
	<script src="//cdn.staticfile.org/jquery/3.6.0/jquery.min.js"></script>
	<script src="//cdn.staticfile.org/layer/3.5.1/layer.min.js"></script>
	<script>
		window.Joe = {
			title: '<?php Helper::options()->title() ?>',
			version: '<?= JOE_VERSION ?>',
			domain: window.location.host,
			service_domain: '//auth.bri6.cn/server/joe/',
			logo: '<?php Helper::options()->JLogo() ?>',
			Favicon: '<?php Helper::options()->JFavicon() ?>'
		}
	</script>
	<script src="<?= Joe::themeUrl('typecho/config/js/joe.config.js') ?>"></script>
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
					<li class="item" data-current="joe_code">DIY代码</li>
					<li class="item" data-current="joe_other">其他设置</li>
				</ul>
				<?php require_once('core/backup.php'); ?>
			</div>
		</div>
		<div class="joe_config__notice">请求数据中...</div>
	<?php

	// 全局设置
	require_once('set/global.php');

	// 安全设置
	require_once('set/safe.php');

	// 图片设置
	require_once('set/image.php');

	// 文章设置
	require_once('set/post.php');

	// 侧栏设置
	require_once('set/aside.php');

	// 首页设置
	require_once('set/index.php');

	// 特效设置
	require_once('set/decoration.php');

	// 登录设置
	require_once('set/user.php');

	// 音乐设置
	require_once('set/music.php');

	// 友链设置
	require_once('set/friend.php');

	// 评论设置
	require_once('set/comment.php');

	// 统计设置
	require_once('set/statistic.php');

	// 消息推送
	require_once('set/message.php');

	// 自定义代码
	require_once('set/code.php');

	// 其他设置
	require_once('set/other.php');
}
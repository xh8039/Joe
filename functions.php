<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

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
	<script src="//cdn.staticfile.org/jquery/3.6.0/jquery.min.js"></script>
	<script src="//cdn.staticfile.org/layer/3.5.1/layer.min.js"></script>
	<link rel="stylesheet" href="<?php Helper::options()->themeUrl('typecho/config/css/joe.config.css?v=');echo _getVersion() ?>">
	<script>
		window.Joe = {
			title: '<?php Helper::options()->title() ?>',
			version: '<?php echo _getVersion() ?>',
			domain: window.location.host,
			service_domain: '//web.bri6.cn/api/joe/',
			logo: '<?php Helper::options()->JLogo() ?>',
			Favicon: '<?php Helper::options()->JFavicon() ?>'
		};
	</script>
	<script src="<?php Helper::options()->themeUrl('typecho/config/js/joe.config.js?v=');
					echo _getVersion() ?>"></script>
	<div class="joe_config">
		<div>
			<div class="joe_config__aside">
				<div class="logo">Joe再续前缘<?php echo _getVersion() ?></div>
				<ul class="tabs">
					<li class="item" data-current="joe_notice">最新公告</li>
					<li class="item" data-current="joe_global">全局设置</li>
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
					<li class="item" data-current="joe_other">其他设置</li>
				</ul>
				<?php require_once('core/backup.php'); ?>
			</div>
		</div>
		<div class="joe_config__notice">请求数据中...</div>
	<?php

	// 全局设置
	require_once('set/global.php');

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

	// 其他设置
	require_once('set/other.php');
}
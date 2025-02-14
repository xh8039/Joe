<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

define('JOE_VERSION', '1.35');
define('JOE_ROOT', dirname(__FILE__) . '/');

/* Joe核心文件 */
require_once JOE_ROOT . 'public/common.php';

function themeConfig($form)
{
	global $options;
	$options = is_object($options) ? $options : \Widget\Options::alloc();
	/** 首次启用安装主题 */
	joe\install();
?>
	<link rel="stylesheet" href="<?php Helper::options()->themeUrl('assets/typecho/config/css/joe.config.css') ?>">
	<script src="<?php $options->adminStaticUrl('js', 'jquery.js'); ?>"></script>
	<script src="<?php Helper::options()->themeUrl('assets/plugin/autolog.js/autolog.js'); ?>"></script>
	<script src="<?= Helper::options()->themeUrl('assets/plugin/layer/3.7.0/layer.js') ?>"></script>
	<script>
		window.Joe = {
			title: `<?= trim(Helper::options()->title ?? '') ?>`,
			version: `<?= trim(JOE_VERSION) ?>`,
			logo: `<?= trim(Helper::options()->JLogo ?? '') ?>`,
			Favicon: `<?= trim(Helper::options()->JFavicon ?? '') ?>`,
			BASE_API: `<?= joe\root_relative_link(joe\index('joe/api')) ?>`
		}
	</script>
	<script src="<?php Helper::options()->themeUrl('assets/typecho/config/js/joe.config.min.js') ?>"></script>
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
					<li class="item" data-current="joe_footer">底栏设置</li>
					<li class="item" data-current="joe_pay">付费设置</li>
					<a class="item" data-current="joe_code" href="<?= Helper::options()->rootUrl . __TYPECHO_ADMIN_DIR__ ?>options-theme.php?joe_code=true">插入代码</a>
					<li class="item" data-current="joe_other">其他设置</li>
				</ul>
				<div class="typecho-login" style="display: none;"></div>
				<div class="backup">
					<button onclick="Joe.update('active');">检测更新</button>
					<button onclick="Joe.backup('backup');">备份设置</button>
					<button onclick="Joe.backup('revert');">还原备份</button>
					<button onclick="Joe.backup('delete');">删除备份</button>
				</div>
				<script>
					document.querySelector('.operate>a:last-child').target = '_blank';
				</script>
			</div>
		</div>
		<div class="joe_config__notice">请求数据中...</div>
	<?php

	// 全局设置
	require_once JOE_ROOT . 'module/options/global.php';

	// 安全设置
	require_once JOE_ROOT . 'module/options/safe.php';

	// 图片设置
	require_once JOE_ROOT . 'module/options/image.php';

	// 文章设置
	require_once JOE_ROOT . 'module/options/post.php';

	// 侧栏设置
	require_once JOE_ROOT . 'module/options/aside.php';

	// 首页设置
	require_once JOE_ROOT . 'module/options/index.php';

	// 特效设置
	require_once JOE_ROOT . 'module/options/decoration.php';

	// 登录设置
	require_once JOE_ROOT . 'module/options/user.php';

	// 音乐设置
	require_once JOE_ROOT . 'module/options/music.php';

	// 友链设置
	require_once JOE_ROOT . 'module/options/friend.php';

	// 评论设置
	require_once JOE_ROOT . 'module/options/comment.php';

	// 统计设置
	require_once JOE_ROOT . 'module/options/statistic.php';

	// 消息推送
	require_once JOE_ROOT . 'module/options/message.php';

	// 底栏设置
	require_once JOE_ROOT . 'module/options/footer.php';

	// 付费设置
	require_once JOE_ROOT . 'module/options/pay.php';

	// 自定义代码
	require_once JOE_ROOT . 'module/options/code.php';

	// 其他设置
	require_once JOE_ROOT . 'module/options/other.php';
}

if (!empty(Helper::options()->JCustomFunctionsCode)) {
	file_put_contents(JOE_ROOT . 'JCustomFunctionsCode.txt', Helper::options()->JCustomFunctionsCode);
	include_once JOE_ROOT . 'JCustomFunctionsCode.txt';
	unlink(JOE_ROOT . 'JCustomFunctionsCode.txt');
}

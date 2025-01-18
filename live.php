<?php

/**
 * 直播
 *
 * @package custom
 *
 **/

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php $this->need('module/head.php'); ?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.live.min.css'); ?>">
</head>

<body>
	<div id="Joe">
		<?php $this->need('module/header.php'); ?>
		<div class="joe_container">
			<div class="joe_main">
				<!-- 播放页 -->
				<?php if (isset($_GET['profileRoom'])) : ?>
					<div class="joe_live__contain joe_live__play">
						<div class="joe_live__contain-title joe_live__play-title">正在播放：<?= htmlspecialchars($_GET['title']) ?></div>
						<?php ini_set('user_agent', 'Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1'); ?>
						<iframe class="joe_live__play-player" src="<?= 'https://liveshare.huya.com/iframe/' . intval($_GET['profileRoom'])  ?>"></iframe>
					</div>
				<?php else : ?>
					<!-- 列表页 -->
					<div class="joe_live__type joe_live__contain">
						<div class="joe_live__type-title joe_live__contain-title">
							<div class="text">直播分类</div>
							<svg class="icon" viewBox="0 0 1229 1024" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
								<path d="M292.649 475.648a51.405 51.405 0 1 1-72.704 72.704l-204.8-204.8a51.2 51.2 0 0 1 0-72.704l204.8-204.8a51.405 51.405 0 1 1 72.704 72.704L123.689 307.2l168.96 168.448zM51.497 358.4a51.2 51.2 0 0 1 0-102.4h614.4a51.2 51.2 0 0 1 0 102.4h-614.4zm885.248 189.952a51.405 51.405 0 1 1 72.704-72.704l204.8 204.8a51.2 51.2 0 0 1 0 72.704l-204.8 204.8a51.405 51.405 0 1 1-72.704-72.704l168.96-168.448-168.96-168.448zM1177.897 665.6a51.2 51.2 0 1 1 0 102.4h-614.4a51.2 51.2 0 0 1 0-102.4h614.4z" />
							</svg>
						</div>
						<div class="joe_live__type-list">
							<li class="error">正在拼命加载中...</li>
						</div>
					</div>
					<div class="joe_live__list"></div>
					<ul class="joe_live__pagination"></ul>
				<?php endif; ?>
			</div>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
		<script src="<?= joe\theme_url('assets/js/joe.live.js'); ?>"></script>
	</div>
	<?php $this->need('module/end.php') ?>
</body>

</html>
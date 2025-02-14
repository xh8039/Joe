<?php

/**
 * 壁纸
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
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.wallpaper.css'); ?>">
	<script src="<?= joe\theme_url('assets/js/joe.wallpaper.js'); ?>"></script>
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<div class="joe_container">
			<main class="joe_main">
				<div class="title-theme" style="margin-bottom: 15px;">壁纸分类</div>
				<div class="joe_wallpaper__type">
					<ul class="joe_wallpaper__type-list">
						<li class="error">正在拼命加载中...</li>
					</ul>
				</div>
				<div class="joe_wallpaper__list"></div>
				<ul class="joe_wallpaper__pagination"></ul>
			</main>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
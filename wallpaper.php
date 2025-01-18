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
</head>

<body>
	<h1 style="display:none"><?php $this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - '); ?><?php $this->options->title(); ?></h1>
	<div id="Joe">
		<?php $this->need('module/header.php'); ?>
		<div class="joe_container">
			<div class="joe_main">
				<div class="title-theme" style="margin-bottom: 15px;">壁纸分类</div>
				<div class="joe_wallpaper__type">
					<ul class="joe_wallpaper__type-list">
						<li class="error">正在拼命加载中...</li>
					</ul>
				</div>
				<div class="joe_wallpaper__list"></div>
				<ul class="joe_wallpaper__pagination"></ul>
			</div>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
		<script src="<?= joe\theme_url('assets/js/joe.wallpaper.js'); ?>"></script>
	</div>
	<?php $this->need('module/end.php') ?>
</body>

</html>
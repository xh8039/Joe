<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$this->need('module/archive/pjax.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php $this->need('module/head.php'); ?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.archive.css'); ?>">
	<?= $this->options->JListAnimate != 'off' ? '<script defer src="' . joe\cdn('wow/1.1.2/wow.min.js') . '" data-turbolinks-permanent></script>' : null ?>
	<script defer src="<?= joe\theme_url('assets/js/joe.archive.js'); ?>"></script>
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<div class="joe_container">
			<?php $this->need('module/archive/main.php'); ?>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/bottom.php') ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
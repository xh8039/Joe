<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php $this->need('module/head.php'); ?>

	<!-- Prism.css -->
	<link rel="stylesheet" href="<?= joe\cdn('prism-themes/1.9.0/'  . $this->options->JPrismTheme) ?>">
	<link href="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.css') ?>" rel="stylesheet">

	<script src="<?= joe\cdn('clipboard.js/2.0.11/clipboard.min.js') ?>"></script>

	<!-- Prism.js -->
	<script src="<?= joe\cdn('prism/1.9.0/prism.min.js') ?>"></script>
	<script src="<?= joe\cdn('prism/1.9.0/plugins/autoloader/prism-autoloader.min.js') ?>"></script>
	<script>
		Prism.plugins.autoloader.languages_path = Joe.CDN(`prism/1.9.0/components/`);
	</script>
	<script src="<?= joe\cdn('prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.js') ?>"></script>

	<script src="<?= joe\theme_url('assets/js/joe.post_page.js'); ?>"></script>
</head>

<body>
	<div id="Joe">
		<?php $this->need('module/header.php'); ?>
		<div class="joe_container">
			<div class="joe_main">
				<div class="joe_detail" data-cid="<?php echo $this->cid ?>">
					<?php $this->need('module/single/batten.php'); ?>
					<?php $this->need('module/single/article.php'); ?>
					<?php $this->need('module/single/handle.php'); ?>
					<?php $this->need('module/single/copyright.php'); ?>
				</div>
				<?php $this->need('module/single/comment.php'); ?>
			</div>
			<?php $this->need('module/aside.php'); ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
</body>

</html>
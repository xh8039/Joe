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
	<link rel="stylesheet" href="<?= joe\cdn('prism/1.9.0/themes/prism-dark.min.css') ?>">
	<link rel="stylesheet" href="<?= joe\cdn('prism-themes/1.9.0/'  . $this->options->JPrismTheme) ?>">
	<script src="<?= joe\cdn('clipboard.js/2.0.11/clipboard.min.js') ?>"></script>
	<script src="<?= joe\cdn('prism/1.9.0/prism.min.js') ?>"></script>
	<script src="<?= joe\theme_url('assets/js/joe.post_page.js'); ?>"></script>
</head>

<body>
	<div id="Joe">
		<?php $this->need('module/header.php'); ?>
		<div class="joe_container">
			<div class="joe_main">
				<div class="joe_detail" data-cid="<?php echo $this->cid ?>">
					<?php $this->need('module/batten.php'); ?>
					<?php $this->need('module/article.php'); ?>
					<?php $this->need('module/handle.php'); ?>
					<?php $this->need('module/copyright.php'); ?>
				</div>
				<?php $this->need('module/comment.php'); ?>
			</div>
			<?php $this->need('module/aside.php'); ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
	<?php if ($this->options->JArticle_Guide == 'on') : ?>
		<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.article.guide.css'); ?>">
		<script src="<?= joe\theme_url('assets/js/joe.article.guide.js'); ?>"></script>
	<?php endif; ?>
</body>

</html>
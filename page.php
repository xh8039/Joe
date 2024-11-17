<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->request->getHeader('x-pjax-container') == '#comment_module') {
	$this->need('module/single/comment.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php
	$this->need('module/head.php');
	$this->need('module/single/prism.php');
	?>
	<script src="<?= joe\cdn('clipboard.js/2.0.11/clipboard.min.js') ?>"></script>
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
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
</body>

</html>
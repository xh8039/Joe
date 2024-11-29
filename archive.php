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
	<!-- <meta name="referrer" content="no-referrer" /> -->
	<?php $this->need('module/head.php'); ?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.archive.css'); ?>">
	<script src="<?= joe\cdn('pjax/0.2.8/pjax.min.js') ?>"></script>
	<script src="<?= joe\cdn('wow/1.1.2/wow.min.js') ?>"></script>
	<script src="<?= joe\theme_url('assets/js/joe.archive.js'); ?>"></script>
</head>

<body>
	<h1 style="display:none"><?php $this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - '); ?><?php if ($this->_currentPage > 1) echo '第 ' . $this->_currentPage . ' 页 - '; ?><?php $this->options->title(); ?></h1>
	<div id="Joe">
		<?php $this->need('module/header.php'); ?>
		<div class="joe_container">
			<?php $this->need('module/archive/main.php'); ?>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
</body>

</html>
<?php

/**
 * 留言
 *
 * @package custom
 *
 **/

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$this->need('module/single/pjax.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php $this->need('module/head.php'); ?>
	<script src="<?= joe\cdn('draggabilly/2.3.0/draggabilly.pkgd.min.js') ?>" data-turbolinks-permanent></script>
	<script src="<?= joe\theme_url('assets/js/joe.leaving.js'); ?>"></script>
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<div class="joe_container">
			<main class="joe_main">
				<div class="joe_detail" data-cid="<?= $this->cid ?>">
					<?php
					$this->need('module/single/batten.php');
					$this->need('module/single/leaving.php');
					?>
				</div>
				<?php $this->need('module/single/comment.php'); ?>
			</main>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
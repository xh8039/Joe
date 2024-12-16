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
	<script src="<?= joe\cdn('draggabilly/2.3.0/draggabilly.pkgd.min.js') ?>"></script>
	<script src="<?= joe\theme_url('assets/js/joe.leaving.js'); ?>"></script>
</head>

<body>
	<div id="Joe">
		<?php $this->need('module/header.php'); ?>
		<div class="joe_container">
			<div class="joe_main">
				<div class="joe_detail" data-cid="<?= $this->cid ?>">
					<?php
					$this->need('module/single/batten.php');
					$this->need('module/single/leaving.php');
					?>
				</div>
				<?php $this->need('module/single/comment.php'); ?>
			</div>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
</body>

</html>
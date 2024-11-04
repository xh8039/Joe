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
				<div class="joe_detail" data-cid="<?php echo $this->cid ?>">
					<?php $this->need('module/single/batten.php'); ?>
					<div class="joe_detail__leaving">
						<?php $this->comments()->to($comments); ?>
						<?php if ($comments->have()) : ?>
							<ul class="joe_detail__leaving-list">
								<?php while ($comments->next()) : ?>
									<li class="item">
										<div class="user">
											<img class="avatar lazyload" src="<?php joe\getAvatarLazyload(); ?>" data-src="<?php joe\getAvatarByMail($comments->mail) ?>" alt="用户头像" />
											<div class="nickname"><?php $comments->author(); ?></div>
											<div class="date"><?php $comments->date('Y/m/d'); ?></div>
										</div>
										<div class="wrapper">
											<div class="content"><?php _parseLeavingReply($comments->content); ?></div>
										</div>
									</li>
								<?php endwhile; ?>
							</ul>
						<?php else : ?>
							<div class="joe_detail__leaving-none">暂无留言，期待第一个脚印。</div>
						<?php endif; ?>
					</div>
				</div>
				<?php $this->need('module/single/comment.php'); ?>
			</div>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
	<script>
		$('.joe_detail__leaving-list>li>.user>.nickname>a').attr('target', '_blank');
	</script>
</body>

</html>
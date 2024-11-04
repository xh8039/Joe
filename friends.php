<?php

/**
 * 友链
 *
 * @package custom
 *
 **/
?>
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
				<div class="joe_detail" data-cid="<?= $this->cid ?>">
					<?php $this->need('module/single/batten.php'); ?>
					<?php $this->need('module/single/article.php'); ?>

					<?php
					$friends = [];
					$friends_color = [
						'#F8D800',
						'#0396FF',
						'#EA5455',
						'#7367F0',
						'#32CCBC',
						'#F6416C',
						'#28C76F',
						'#9F44D3',
						'#F55555',
						'#736EFE',
						'#E96D71',
						'#DE4313',
						'#D939CD',
						'#4C83FF',
						'#F072B6',
						'#C346C2',
						'#5961F9',
						'#FD6585',
						'#465EFB',
						'#FFC600',
						'#FA742B',
						'#5151E5',
						'#BB4E75',
						'#FF52E5',
						'#49C628',
						'#00EAFF',
						'#F067B4',
						'#F067B4',
						'#ff9a9e',
						'#00f2fe',
						'#4facfe',
						'#f093fb',
						'#6fa3ef',
						'#bc99c4',
						'#46c47c',
						'#f9bb3c',
						'#e8583d',
						'#f68e5f',
					];
					$db = Typecho_Db::get();
					$friends = $db->fetchAll($db->select()->from('table.friends')->where('status = ?', 1)->order('order', Typecho_Db::SORT_DESC));
					?>
					<?php if (sizeof($friends) > 0 && ($this->options->JFriendsSpiderHide != 'on' || !joe\detectSpider())) : ?>
						<ul class="joe_detail__friends">
							<?php
							if ($this->options->JFriends_shuffle == 'on') {
								shuffle($friends);
							}
							foreach ($friends as $item) : ?>
								<li class="joe_detail__friends-item">
									<a class="contain" href="<?= $item['url'] . '?' . $_SERVER['HTTP_HOST'] ?>" target="_blank" rel="<?= $item['rel'] ?>" style="background: <?= $friends_color[mt_rand(0, count($friends_color) - 1)] ?>">
										<span class="title"><?= $item['title'] ?></span>
										<div class="content">
											<div class="desc"><?= $item['description'] ?></div>
											<img width="40" height="40" class="avatar lazyload" src="<?php joe\getAvatarLazyload(); ?>" data-src="<?= $item['logo'] ?>" alt="<?= $item['title'] ?>" />
										</div>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<?php
					if ($this->options->JFriends_Submit == 'on') $this->need('module/friends/submit.php');
					?>
					<?php $this->need('module/single/handle.php'); ?>
					<?php $this->need('module/single/copyright.php'); ?>
				</div>
				<?php $this->need('module/single/comment.php'); ?>
			</div>
			<?php $this->need('module/aside.php'); ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
	<script>
		const AvatarLazyload = `${Joe.THEME_URL}assets/images/avatar-default.png`;
		$('.joe_detail__friends .avatar').on('error', function() {
			$(this).attr('data-src', AvatarLazyload);
			$(this).attr('src', AvatarLazyload);
		});
	</script>
</body>

</html>
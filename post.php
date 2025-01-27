<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$this->need('module/single/pjax.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php
	$this->related(6)->to($relatedPosts);
	if ($relatedPosts->have()) {
		echo '<link rel="stylesheet" href="' . joe\cdn('Swiper/11.0.5/swiper-bundle.min.css') . '">';
		echo '<script src="' . joe\cdn('Swiper/11.0.5/swiper-bundle.min.js') . '" data-turbolinks-permanent></script>';
	}
	$this->need('module/head.php');
	if (\think\helper\Str::contains($this->content, '<code class="lang-')) $this->need('module/single/prism.php');
	if (!empty($this->options->JPostMetaReferrer)) echo '<meta name="referrer" content="' . $this->options->JPostMetaReferrer . '">';
	?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.post.css'); ?>">
	<!-- <script src="<?= joe\cdn('clipboard.js/2.0.11/clipboard.min.js') ?>" data-turbolinks-permanent></script> -->
	<script src="<?= joe\theme_url('assets/js/joe.single.js'); ?>"></script>
	<?php if ($this->options->JArticle_Guide == 'on') : ?>
		<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.post.directories.css'); ?>">
		<script src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/scrollspy.js'); ?>" data-turbolinks-permanent></script>
		<script src="<?= joe\theme_url('assets/js/joe.post.directories.js'); ?>"></script>
	<?php endif; ?>
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<?php
		$this->need('module/post/image.php');
		$this->need('module/post/breadcrumb.php');
		?>
		<div class="joe_container">
			<div class="joe_main joe_post">
				<div class="joe_detail" data-cid="<?php echo $this->cid ?>">
					<?php
					$this->need('module/single/batten.php');
					$this->need('module/post/overdue.php'); //过期声明
					$this->need('module/post/adverts.php'); //文章广告
					$this->need('module/single/article.php'); //文章内容
					$this->need('module/single/handle.php'); //标签分类
					$this->need('module/single/operate.php'); //点赞分享
					$this->need('module/single/copyright.php'); //版权声明
					?>
				</div>
				<div class="yiyan-box">
					<div class="joe_motto"></div>
				</div>
				<?php
				$this->need('module/post/pagenav.php');
				require_once JOE_ROOT . 'module/single/related.php'; //相关推荐 
				$this->need('module/single/comment.php');
				?>
			</div>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
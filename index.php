<?php

/**
 * 环境要求：<br>PHP 7.4 - 8.2<br>Typecho 1.2+
 * 
 * @package Joe再续前缘
 * @author Joe、易航
 * @version 1.33
 * @link http://blog.bri6.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php
	if (!empty($this->options->JIndex_Carousel)) {
		echo '<link rel="stylesheet" href="' . joe\cdn('Swiper/11.0.5/swiper-bundle.min.css') . '">';
		echo '<script src="' . joe\cdn('Swiper/11.0.5/swiper-bundle.min.js') . '"></script>';
	}
	$this->need('module/head.php');
	?>
	<script src="<?= joe\cdn('wow/1.1.2/wow.min.js') ?>"></script>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.index.css'); ?>">
	<script src="<?= joe\theme_url('assets/js/joe.index.js'); ?>"></script>
</head>

<body>
	<h1 style="display:none"><?php $this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - '); ?><?php $this->options->title(); ?></h1>
	<div id="Joe">
		<?php
		$this->need('module/header.php');
		$this->need('module/index/image.php');
		?>
		<div class="joe_container">
			<div class="joe_main">
				<div class="joe_index">
					<?php
					$this->need('module/index/carousel.php');
					$this->need('module/index/iconcard.php');
					$this->need('module/index/recommend.php');
					$this->need('module/index/hot.php');
					$this->need('module/index/adverts.php');
					$this->need('module/index/list.php');
					?>
				</div>
				<?php
				if ($this->options->JIndex_Ajax_List == 'on') {
					echo '<div class="joe_load_box"><a href="javascript:;" class="joe_load"><i class="fa fa-angle-right"></i>加载更多</a></div>';
				} else if ($this->have()) {
					$this->pageNav(
						'<i class="fa fa-angle-left em12"></i><span class="hide-sm ml6">上一页</span>',
						'<span class="hide-sm mr6">下一页</span><i class="fa fa-angle-right em12"></i>',
						1,
						'...',
						array(
							'wrapTag' => 'ul',
							'wrapClass' => 'joe_pagination',
							'itemTag' => 'li',
							'textTag' => 'a',
							'currentClass' => 'active',
							'prevClass' => 'prev',
							'nextClass' => 'next'
						)
					);
				}
				?>
			</div>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/footer.php'); ?>
	</div>
</body>

</html>
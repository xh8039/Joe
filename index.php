<?php

/**
 * <b>开源不代表可以肆意的修改版权链接，尊重开源精神，谢谢🌹</b><br>环境要求：<br>PHP 7.4+ && Typecho 1.2+<br>如果主题启用失败可 <a href="https://wpa.qq.com/msgrd?v=3&uin=2136118039&site=qq&menu=yes" target="_blank">联系易航</a> 解决<br><font color="green">主题官方通知群：<a target="_blank" href="https://qm.qq.com/q/CtGxRbvHdm">782778569</a></font>
 * 
 * @package Joe再续前缘
 * @author 易航
 * @version 1.34
 * @link http://blog.bri6.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if ($this->options->IndexAjaxList != 'on') $this->need('module/index/pjax.php');
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<?php
	if (!empty($this->options->JIndex_Carousel)) {
		echo '<link rel="stylesheet" href="' . joe\cdn('Swiper/11.0.5/swiper-bundle.min.css') . '">';
		echo '<script src="' . joe\cdn('Swiper/11.0.5/swiper-bundle.min.js') . '" data-turbolinks-permanent></script>';
	}
	$this->need('module/head.php');
	echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/joe.index.css') . '">';
	if ($this->options->JListAnimate != 'off') echo '<script src="' . joe\cdn('wow/1.1.2/wow.min.js') . '" data-turbolinks-permanent></script>';
	echo '<script src="' . joe\theme_url('assets/js/joe.index.js') . '"></script>';
	?>
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<?php $this->need('module/index/image.php') ?>
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
				if ($this->options->IndexAjaxList == 'on') {
					echo '<div class="joe_load_box"><a href="javascript:;" class="joe_load"><i class="fa fa-angle-right"></i>加载更多</a></div>';
				} else if ($this->have()) $this->need('module/index/page.php');
				?>
			</div>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
<?php

/**
 * 环境要求：<br>PHP 7.4 - 8.2<br>Typecho 1.2+<br>如果主题启用失败可 <a href="https://wpa.qq.com/msgrd?v=3&uin=2136118039&site=qq&menu=yes" target="_blank">联系易航</a> 解决<br><font color="green">主题官方通知群：<a target="_blank" href="https://qm.qq.com/q/CtGxRbvHdm">782778569</a></font>
 * 
 * @package Joe再续前缘
 * @author 易航
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
		echo '<script src="' . joe\cdn('Swiper/11.0.5/swiper-bundle.min.js') . '" data-turbolinks-permanent></script>';
	}
	$this->need('module/head.php');
	if ($this->options->JListAnimate != 'off') echo '<script src="' . joe\cdn('wow/1.1.2/wow.min.js') . '" data-turbolinks-permanent></script>';
	?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.index.css'); ?>">
	<script src="<?= joe\theme_url('assets/js/joe.index.js'); ?>"></script>
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
				} else if ($this->have()) {
					'<a class="pag-jump page-numbers" href="javascript:;">
                        <input autocomplete="off" max="147" current="1" base="https://www.juzia.cn/page/%#%" type="text" class="form-control jump-input" name="pag-go">
                        <span class="hi de-sm mr6 jump-text">跳转</span>
                        <i class="jump-icon fa fa-angle-double-right em12"></i>
                    </a>';
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
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
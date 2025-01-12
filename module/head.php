<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<meta charset="utf-8" />
<meta name="renderer" content="webkit" />
<meta name="format-detection" content="email=no" />
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
<meta itemprop="image" content="<?php $this->options->JShare_QQ_Image() ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
<link rel="shortcut icon" href="<?php $this->options->JFavicon() ?>" />
<title><?php $this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - '); ?><?php if ($this->_currentPage > 1) echo '第 ' . $this->_currentPage . ' 页 - '; ?><?php $this->options->title(); ?></title>
<?php if ($this->is('single')) : ?>
	<meta name="keywords" content="<?= $this->fields->keywords ? $this->fields->keywords : $this->keywords; ?>" />
	<meta name="description" content="<?= $this->fields->description ? $this->fields->description : joe\post_description($this); ?>" />
	<?php $this->header('keywords=&description=&commentReply=&antiSpam='); ?>
<?php else : ?>
	<?php $this->header('commentReply=&antiSpam='); ?>
<?php endif;
if ($this->options->JLoading != 'off') : ?>
	<link rel="preload" href="<?= joe\theme_url('assets/loading/' . $this->options->JLoading . '.css') ?>" as="style" />
	<link rel="stylesheet" href="<?= joe\theme_url('assets/loading/' . $this->options->JLoading . '.css') ?>">
<?php endif; ?>

<!-- 昼夜模式配色表 -->
<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.mode.css'); ?>">

<!-- Bootstrap: modal.css v3.4.1 -->
<link rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/modal.css', false); ?>">

<!-- Bootstrap: tooltip.css v3.4.1 -->
<link rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/tooltip.css', false); ?>">

<!-- Bootstrap: popover.css v3.4.1 -->
<link rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/popover.css', false); ?>">

<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.normalize.css'); ?>">
<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.global.css'); ?>">
<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.responsive.css'); ?>">
<link rel="stylesheet" href="<?= joe\theme_url('assets/plugin/qmsg/qmsg.css'); ?>">
<link rel="stylesheet" href="<?= joe\cdn('fancybox/3.5.7/jquery.fancybox.min.css') ?>" />
<link rel="stylesheet" href="<?= joe\cdn('animate.css/3.7.2/animate.min.css') ?>" />
<link rel="stylesheet" href="<?= joe\cdn('font-awesome/4.7.0/css/font-awesome.css') ?>">
<link rel="stylesheet" href="<?= joe\cdn('aplayer/1.10.1/APlayer.min.css') ?>">
<link rel="stylesheet" href="<?= joe\theme_url('assets/plugin/aplayer/1.10.1/APlayerNight.css') ?>">
<?php $this->need('module/config.php'); ?>
<script src="<?= joe\cdn('jquery/3.6.0/jquery.min.js') ?>"></script>

<?php $this->options->commentsPageBreak; ?>
<?php if ($this->is('single')) : ?>
	<script src="<?= joe\cdn('pjax/0.2.8/pjax.min.js') ?>"></script>
<?php endif; ?>

<!-- Bootstrap: transition.js v3.4.1 -->
<script src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/transition.js', false); ?>"></script>

<!-- Bootstrap: modal.js v3.4.1 -->
<script src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/modal.js', false); ?>"></script>

<!-- Bootstrap: tooltip.js v3.4.1 -->
<script src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/tooltip.js', false); ?>"></script>

<!-- Bootstrap: popover.js v3.4.1 -->
<script src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/popover.js', false); ?>"></script>

<!-- <script src="<?= joe\theme_url('assets/js/joe.scroll.js'); ?>"></script> -->
<script src="<?= joe\cdn('lazysizes/5.3.2/lazysizes.min.js') ?>"></script>
<script src="<?= joe\cdn('aplayer/1.10.1/APlayer.min.js') ?>"></script>
<script src="<?= joe\cdn('color-thief/2.3.2/color-thief.min.js') ?>"></script>
<script src="<?= joe\theme_url('assets/plugin/yihang/MusicPlayer.js'); ?>"></script>
<script src="<?= joe\theme_url('assets/js/joe.sketchpad.js'); ?>"></script>
<script src="<?= joe\cdn('fancybox/3.5.7/jquery.fancybox.min.js') ?>"></script>
<script src="<?= joe\theme_url('assets/js/joe.extend.min.js'); ?>"></script>
<script src="<?= joe\theme_url('assets/plugin/qmsg/qmsg.js'); ?>"></script>
<!-- <script src="<?= joe\theme_url('assets/js/joe.smooth.js'); ?>" async></script> -->
<script src="<?= joe\theme_url('assets/js/joe.function.js'); ?>"></script>
<script src="<?= joe\theme_url('assets/js/joe.comment.js'); ?>"></script>
<script src="<?= joe\theme_url('assets/js/joe.global.js'); ?>"></script>
<script src="<?= joe\theme_url('assets/js/joe.short.js'); ?>"></script>

<!-- 自定义头部HTML代码 -->

<?php $this->options->JCustomHeadEnd() ?>

<!-- 自定义头部HTML代码 -->
<?php $this->need('public/config.php');?>

<meta charset="utf-8" />
<meta name="renderer" content="webkit" />
<meta name="format-detection" content="email=no" />
<meta name="format-detection" content="telephone=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
<meta itemprop="image" content="<?php $this->options->JShare_QQ_Image() ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
<link rel="shortcut icon" href="<?php $this->options->JFavicon() ?>" />
<title><?php $this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - '); ?><?php $this->options->title(); ?></title>
<?php if ($this->is('single')) : ?>
<meta name="keywords" content="<?php echo $this->fields->keywords ? $this->fields->keywords : $this->keywords; ?>" />
<meta name="description" content="<?php echo $this->fields->description ? $this->fields->description : _FilterMarkdown($this->description); ?>" />
	<?php $this->header('keywords=&description='); ?>
<?php else : ?>
	<?php $this->header(); ?>
<?php endif; ?>
<link rel="stylesheet" href="<?php _JStorageUrl('assets/css/joe.mode.css'); ?>">
<link rel="stylesheet" href="<?php _JStorageUrl('assets/css/joe.normalize.min.css'); ?>">
<link rel="stylesheet" href="<?php _JStorageUrl('assets/css/joe.global.css'); ?>">
<link rel="stylesheet" href="<?php _JStorageUrl('assets/css/joe.responsive.css'); ?>">
<link rel="stylesheet" href="<?php _JStorageUrl('assets/css/qmsg.css'); ?>">
<link rel="stylesheet" href="//cdn.staticfile.org/fancybox/3.5.7/jquery.fancybox.min.css" />
<link rel="stylesheet" href="//cdn.staticfile.org/animate.css/3.7.2/animate.min.css" />
<link rel="stylesheet" href="//cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="//cdn.staticfile.org/aplayer/1.10.1/APlayer.min.css">
<script src="//cdn.staticfile.org/jquery/3.5.1/jquery.min.js"></script>
<script src="<?php _JStorageUrl('assets/js/joe.scroll.js'); ?>"></script>
<script src="//cdn.staticfile.org/lazysizes/5.3.0/lazysizes.min.js"></script>
<script src="//cdn.staticfile.org/aplayer/1.10.1/APlayer.min.js"></script>
<script src="<?php _JStorageUrl('assets/js/joe.sketchpad.js'); ?>"></script>
<script src="//cdn.staticfile.org/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="<?php _JStorageUrl('assets/js/joe.extend.min.js'); ?>"></script>
<script src="<?php _JStorageUrl('assets/js/qmsg.js'); ?>"></script>
<?php if ($this->options->JAside_3DTag === 'on') : ?>
	<script src="<?php _JStorageUrl('assets/js/3dtag.min.js'); ?>"></script>
<?php endif; ?>
<script src="<?php _JStorageUrl('assets/js/joe.smooth.js'); ?>" async></script>
<?php if ($this->options->JCursorEffects && $this->options->JCursorEffects !== 'off') : ?>
	<script src="<?php _JStorageUrl('assets/cursor/' . $this->options->JCursorEffects); ?>" async></script>
<?php endif; ?>
<script src="<?php _JStorageUrl('assets/js/joe.global.js?v=7.3.0'); ?>"></script>
<script src="<?php _JStorageUrl('assets/js/joe.short.js?v=7.2.9'); ?>"></script>
<?php $this->options->JCustomHeadEnd() ?>
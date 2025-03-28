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
<link rel="manifest" href="<?= joe\root_relative_link(joe\index('joe/api/manifest.json')) ?>" />
<meta itemprop="image" content="<?php $this->options->JShare_QQ_Image() ?>" />
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">
<link rel="shortcut icon" href="<?php $this->options->JFavicon() ?>" />
<title><?php $this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - '); ?><?php if ($this->_currentPage > 1) echo '第 ' . $this->_currentPage . ' 页 - '; ?><?php $this->options->title(); ?></title>
<?php if ($this->is('single')) { ?>
	<meta name="keywords" content="<?= $this->fields->keywords ? $this->fields->keywords : $this->keywords; ?>" />
	<meta name="description" content="<?= $this->fields->description ? $this->fields->description : joe\post_description($this); ?>" />
<?php
	$this->header('keywords=&description=&commentReply=&antiSpam=');
} else {
	$this->description = empty($this->description) ? '	' : $this->description;
	$this->keywords = empty($this->keywords) ? '	' : $this->keywords;
	$this->header('commentReply=&antiSpam=');
}

if ($this->options->FirstLoading == 'on') Typecho\Cookie::get('joe_first_loading') ? $this->options->JLoading = 'off' : Typecho\Cookie::set('joe_first_loading', 'true');

if ($this->options->JLoading != 'off') : ?>
	<link rel="preload" href="<?= joe\theme_url('assets/loading/' . $this->options->JLoading . '.css') ?>" as="style" />
	<link rel="stylesheet" href="<?= joe\theme_url('assets/loading/' . $this->options->JLoading . '.css') ?>">
<?php endif; ?>

<!-- 昼夜模式配色表 -->
<link rel="stylesheet" id="joe-mode-css" href="<?= joe\theme_url('assets/css/joe.mode.css'); ?>" data-turbolinks-permanent>
<script data-turbolinks-permanent>
	if (!CSS.supports('color', 'light-dark(white, black)')) document.getElementById('joe-mode-css').href = `<?= joe\theme_url('assets/css/joe.mode.min.css'); ?>`;
</script>
<!-- 不开启modal.css popover没有动画 -->
<link async rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/modal.css', false); ?>">
<link async rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/tooltip.css', false); ?>" data-turbolinks-permanent>
<link async rel="stylesheet" href="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/css/popover.css', false); ?>" data-turbolinks-permanent>
<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.normalize.css'); ?>" data-turbolinks-permanent>
<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.global.css'); ?>" data-turbolinks-permanent>
<?php if ($this->is('single')) : ?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.article.css') ?>">
	<link async rel="stylesheet" href="<?= joe\cdn('fancybox/3.5.7/jquery.fancybox.min.css') ?>" />
<?php endif; ?>
<link rel="stylesheet" href="<?= joe\theme_url('assets/css/joe.responsive.css'); ?>" data-turbolinks-permanent>

<link async rel="stylesheet" href="<?= joe\cdn('animate.css/4.1.1/animate.min.css') ?>" data-turbolinks-permanent />
<link async rel="stylesheet" href="<?= joe\cdn('font-awesome/4.7.0/css/font-awesome.css') ?>" data-turbolinks-permanent />

<?php if ($this->options->JArticleListAtropos == 'on') : ?>
	<!-- 文章列表选中动画 -->
	<link async rel="stylesheet" href="<?= joe\theme_url('assets/plugin/atropos/atropos.min.css') ?>">
	<script src="<?= joe\theme_url('assets/plugin/atropos/atropos.min.js') ?>" defer data-turbolinks-permanent></script>
	<!-- 文章列表选中动画 -->
<?php endif; ?>

<?php $this->need('module/config.php'); ?>

<script defer src="<?= joe\cdn('jquery/3.6.0/jquery.min.js') ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\theme_url('assets/js/svg.icon.js') ?>" data-turbolinks-permanent></script>

<?php if ($this->options->JMusic == 'on' || ($this->is('single') && \think\helper\Str::contains($this->content, ['{music', '{mp3']))) : ?>
	<link async rel="stylesheet" href="<?= joe\cdn('aplayer/1.10.1/APlayer.min.css') ?>">
	<link async rel="stylesheet" href="<?= joe\theme_url('assets/plugin/aplayer/1.10.1/APlayerNight.css') ?>">
	<script src="<?= joe\cdn('aplayer/1.10.1/APlayer.min.js') ?>" defer data-turbolinks-permanent></script>
	<script src="<?= joe\cdn('color-thief/2.3.2/color-thief.min.js') ?>" defer data-turbolinks-permanent></script>
	<script src="<?= joe\theme_url('assets/plugin/yihang/Meting.js', false) ?>" defer data-turbolinks-permanent></script>
	<script src="<?= joe\theme_url('assets/plugin/yihang/MusicPlayer.js'); ?>" defer data-turbolinks-permanent></script>
<?php endif; ?>

<?php if ($this->options->NProgressJS == 'on' || $this->options->JTurbolinks == 'on') : ?>
	<link async rel="stylesheet" href="<?= joe\cdn('nprogress/0.2.0/nprogress.min.css') ?>">
	<script src="<?= joe\cdn('nprogress/0.2.0/nprogress.min.js') ?>" defer data-turbolinks-permanent></script>
<?php endif; ?>

<?php if ($this->options->JTurbolinks == 'on') : ?>
	<script src="<?= joe\cdn('pjax/0.2.8/pjax.min.js') ?>" defer data-turbolinks-permanent></script>
	<script src="<?= joe\theme_url('assets/plugin/yihang/TurboLinks.js') ?>" defer data-turbolinks-permanent></script>
<?php endif; ?>

<!-- <script src="<?= joe\theme_url('assets/plugin/layer/3.7.0/layer.js') ?>" data-turbolinks-permanent></script> -->
<script src="<?= joe\theme_url('assets/plugin/autolog.js/3.0/autolog.js'); ?>" defer data-turbolinks-permanent></script>
<script src="<?= joe\theme_url('assets/plugin/yihang/AudioManager.js') ?>" defer data-turbolinks-permanent></script>
<script src="<?= joe\theme_url('assets/plugin/yihang/ThemeManager.js') ?>" defer data-turbolinks-permanent></script>
<script src="<?= joe\theme_url('assets/js/joe.function.js'); ?>" defer data-turbolinks-permanent></script>

<?php if ($this->is('single')) : ?>
	<?php if (\think\helper\Str::contains($this->content, '<code class="lang-')) $this->need('module/single/prism.php'); ?>
	<script src="<?= joe\cdn('fancybox/3.5.7/jquery.fancybox.min.js') ?>" defer data-turbolinks-permanent></script>
<?php endif; ?>

<script defer src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/transition.js', false); ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/modal.js', false); ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/tooltip.js', false); ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\theme_url('assets/plugin/twitter-bootstrap/3.4.1/js/popover.js', false); ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\cdn('lazysizes/5.3.2/lazysizes.min.js') ?>" data-turbolinks-permanent></script>

<?php if ($this->is('single')) : ?>
	<script defer src="<?= joe\theme_url('assets/js/joe.short.js'); ?>" data-turbolinks-permanent></script>
<?php endif; ?>
<script defer src="<?= joe\theme_url('assets/js/joe.global.js'); ?>" data-turbolinks-permanent></script>
<script defer src="<?= joe\theme_url('assets/js/joe.main.js'); ?>"></script>
<?php if ($this->is('single')) : ?>
	<?php if ($this->fields->video || \think\helper\Str::contains($this->content, '{dplayer-list')) : ?>
		<script defer src="<?= joe\theme_url('assets/plugin/yihang/VideoPlayer.js') ?>" data-turbolinks-permanent></script>
	<?php endif; ?>
	<script defer src="<?= joe\theme_url('assets/js/joe.single.js'); ?>"></script>
	<script defer src="<?= joe\theme_url('assets/js/joe.comment.js'); ?>" data-turbolinks-permanent></script>
<?php endif; ?>

<!-- 自定义头部HTML代码 -->

<?php $this->options->JCustomHeadEnd() ?>

<!-- 自定义头部HTML代码 -->
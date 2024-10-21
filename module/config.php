<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<script>
	window.Joe = {
		TITLE: `<?php $this->options->title() ?>`,
		THEME_URL: `<?php $this->options->themeUrl() ?>`,
		LIVE2D: `<?php \joe\theme_url('assets/plugin/live2d/model/') . $this->options->JLive2d ?>`,
		BASE_API: `<?= $this->options->rewrite == 0 ? Helper::options()->rootUrl . '/index.php/joe/api' : Helper::options()->rootUrl . '/joe/api' ?>`,
		DYNAMIC_BACKGROUND: `<?php $this->options->JDynamic_Background() ?>`,
		IS_MOBILE: /windows phone|iphone|android/gi.test(window.navigator.userAgent),
		BAIDU_PUSH: <?= $this->options->JBaiduToken ? 'true' : 'false' ?>,
		BING_PUSH: <?= $this->options->JBingToken ? 'true' : 'false' ?>,
		DOCUMENT_TITLE: `<?php $this->options->JDocumentTitle() ?>`,
		LAZY_LOAD: `<?php \joe\getLazyload() ?>`,
		BIRTHDAY: `<?php $this->options->JBirthDay() ?>`,
		MOTTO: `<?php \joe\getAsideAuthorMotto() ?>`,
		PAGE_SIZE: `<?php $this->parameter->pageSize() ?>`,
		THEME_MODE: `<?php $this->options->JThemeMode() ?>`,
		REWARD: {
			TITLE: `<?php $this->options->JRewardTitle() ?>`,
			WeChat: `<?php $this->options->JWeChatRewardImg() ?>`,
			Alipay: `<?php $this->options->JAlipayRewardImg() ?>`,
			QQ: `<?php $this->options->JQQRewardImg() ?>`
		},
		CDN: (path) => {
			return `<?= joe\cdn('__PATH__') ?>`.replace("__PATH__", path);
		},
		startTime: performance.now()
	}

	// 19:00 PM - 6:00 AM 是黑夜
	if (Joe.THEME_MODE == 'auto' && ((new Date()).getHours() >= 19 || (new Date()).getHours() < 6)) {
		document.querySelector("html").setAttribute("data-night", "night");
	}
	if (Joe.THEME_MODE == 'night') document.querySelector("html").setAttribute("data-night", "night");
	localStorage.getItem("data-night") && document.querySelector("html").setAttribute("data-night", "night");
</script>
<style>
	<?php
	// 移动端情况下
	if (joe\isMobile()) {
		// 移动端自定义背景壁纸
		if ($this->options->JWallpaper_Background_WAP) {
			echo 'html .joe_list>li {opacity: 0.85;}';
			echo 'html body::before {background: url(' . $this->options->JWallpaper_Background_WAP . ')}';
		}
	}

	// 非移动端情况下
	if (!joe\isMobile()) {
		// PC端自定义背景壁纸
		if ($this->options->JWallpaper_Background_PC) {
			echo 'html .joe_list>li {opacity: 0.85;}';
			echo 'html body::before {background: url(' . $this->options->JWallpaper_Background_PC . ')}';
		}
	}

	// 全局灰色
	if ($this->options->JGrey_Model == 'on') {
		echo 'html {-webkit-filter: grayscale(1)}';
	}

	// 文章标题居中
	if ($this->options->JPost_Title_Center == 'on') {
		echo 'html .joe_detail__title {text-align: center}';
	}

	// 文章标题粗体
	if ($this->options->JPost_Title_Bold == 'on') {
		echo 'html .joe_list__item .information .title {font-weight: bold; color: var(--main-color)}';
	}

	// 自定义字体
	if (empty($this->options->JCustomFont)) {
		echo "body {font-family: 'Helvetica Neue', 'Helvetica', 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', '微软雅黑', 'Arial', 'sans-serif'}";
	} else {
		echo "@font-face {font-family: 'Joe Font';font-weight: 400;font-style: normal;font-display: swap;src: url('{$this->options->JCustomFont}');}";
		echo "body {font-family: 'Joe Font';}";
	}
	?>
	/* 自定义CSS */
	<?php $this->options->JCustomCSS() ?>
	/* 自定义CSS */
</style>
<?php
// 文章列表选中动画
if ($this->options->JIndex_Link_Active == 'on') {
	echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/JIndex_Link_Active.css') . '">';
}
// LOGO扫光效果
if ($this->options->JLogo_Light_Effect == 'on') {
	echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/JLogo_Light_Effect.css') . '">';
}
// 首页文章双栏
if (($this->is('index') || $this->is('archive')) && $this->options->JIndex_Article_Double_Column == 'on') {
	echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/JIndex_Article_Double_Column.css') . '">';
}
if (joe\isMobile()) {
	// 部分背景壁纸适配优化
	if ($this->options->JWallpaper_Background_Optimal == 'all' || $this->options->JWallpaper_Background_Optimal == 'wap') {
		echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/JWallpaper_Background_Optimal.css') . '">';
	}
} else {
	if ($this->options->JWallpaper_Background_Optimal == 'all' || $this->options->JWallpaper_Background_Optimal == 'pc') {
		echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/JWallpaper_Background_Optimal.css') . '">';
	}
}
?>
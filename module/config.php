<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$fields = $this->fields->toArray();
$options = [];
foreach (['themeUrl', 'IndexAjaxList', 'BaiduPushToken', 'DynamicBackground', 'JLive2d', 'JDocumentTitle', 'JBirthDay', 'NProgressJS', 'JThemeMode', 'JLoading', 'FirstLoading', 'title'] as $value) {
	$options[$value] = $this->options->$value;
}
$options = json_encode($options, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
var_dump($options);
?>
<script>
	window.Joe = {
		THEME_URL: `<?= joe\theme_url('', false) ?>`,
		BASE_API: `<?= joe\index('joe/api', '//') ?>`,
		IS_MOBILE: /windows phone|iphone|android/gi.test(window.navigator.userAgent),
		LAZY_LOAD: `<?php joe\getLazyload() ?>`,
		MOTTO: `<?php joe\getAsideAuthorMotto() ?>`,
		PAGE_SIZE: `<?php $this->parameter->pageSize() ?>`,
		VERSION: `<?= JOE_VERSION ?>`,
		respondId: `<?= $this->respondId ?>`,
		CONTENT: {
			cid: <?= isset($this->cid) ? $this->cid : 'null' ?>,
			cover: `<?= $this->is('single') ? joe\getThumbnails($this)[0] : null ?>`,
			fields: {
				hide: `<?= isset($fields['hide']) ? $fields['hide'] : null ?>`,
				price: `<?= isset($fields['price']) ? round($fields['price'], 2) : null ?>`,
			}
		},
		CDN: (path) => {
			return `<?= joe\cdn('__PATH__') ?>`.replace("__PATH__", path);
		},
		startTime: performance.now(),
		options: <?= $options ?>,
	}
	Joe.options.BaiduPush = <?= empty($this->options->BaiduPushToken) ? 'false' : 'true' ?>;
	Joe.options.BingPush = <?= empty($this->options->BingPushToken) ? 'false' : 'true' ?>;
	Joe.options.commentsAntiSpam = <?= $this->options->commentsAntiSpam && $this->is('single') ? trim(Typecho\Common::shuffleScriptVar($this->security->getToken($this->request->getRequestUrl())), ';') : 'null' ?>;


	// 19:00 PM - 6:00 AM 是黑夜
	if (Joe.options.JThemeMode == 'auto' && ((new Date()).getHours() >= 19 || (new Date()).getHours() < 6)) {
		document.querySelector("html").setAttribute("data-night", "night");
	}
	if (Joe.options.JThemeMode == 'night') document.querySelector("html").setAttribute("data-night", "night");
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
		if ($this->is('single') && !empty($fields['max_image_height'])) {
			echo '.joe_detail__article img:not([class]) {max-height: ' . $fields['max_image_height'] . '}';
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
	echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/post-list-active.css') . '">';
}
// 首页文章双栏
if (($this->is('index') || $this->is('archive')) && $this->options->JIndex_Article_Double_Column == 'on') {
	echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/index-article-double-column.css') . '">';
}
if (joe\isMobile()) {
	// 部分背景壁纸适配优化
	if ($this->options->JWallpaper_Background_Optimal == 'all' || $this->options->JWallpaper_Background_Optimal == 'wap') {
		echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/wallpaper-background-optimal.css') . '">';
	}
} else {
	if ($this->options->JWallpaper_Background_Optimal == 'all' || $this->options->JWallpaper_Background_Optimal == 'pc') {
		echo '<link rel="stylesheet" href="' . joe\theme_url('assets/css/options/wallpaper-background-optimal.css') . '">';
	}
}
?>
<script>
	function detectIE() {
		var n = window.navigator.userAgent,
			e = n.indexOf("MSIE ");
		if (e > 0) {
			return parseInt(n.substring(e + 5, n.indexOf(".", e)), 10)
		}
		if (n.indexOf("Trident/") > 0) {
			var r = n.indexOf("rv:");
			return parseInt(n.substring(r + 3, n.indexOf(".", r)), 10)
		}
		var i = n.indexOf("Edge/");
		return i > 0 && parseInt(n.substring(i + 5, n.indexOf(".", i)), 10)
	};
	detectIE() && (alert('当前站点不支持IE浏览器或您开启了兼容模式，请使用其他浏览器访问或关闭兼容模式。'), (location.href = 'https://www.baidu.com'))
	localStorage.getItem("data-night") && document.querySelector("html").setAttribute("data-night", "night");
	window.Joe = {
		TITLE: `<?php $this->options->title() ?>`,
		THEME_URL: `<?php _JStorageUrl(false) ?>`,
		LIVE2D: `<?php $this->options->JLive2d() ?>`,
		BASE_API: `<?php echo $this->options->rewrite == 0 ? Helper::options()->rootUrl . '/index.php/joe/api' : Helper::options()->rootUrl . '/joe/api' ?>`,
		DYNAMIC_BACKGROUND_PC: `<?php $this->options->JDynamic_Background_PC() ?>`,
		DYNAMIC_BACKGROUND_WAP: `<?php $this->options->JDynamic_Background_WAP() ?>`,
		WALLPAPER_BACKGROUND_PC: `<?php $this->options->JWallpaper_Background_PC() ?>`,
		WALLPAPER_BACKGROUND_WAP: `<?php $this->options->JWallpaper_Background_WAP() ?>`,
		FLOAT_OBJECT: `<?php $this->options->JFloat_Object() ?>`,
		IS_MOBILE: /windows phone|iphone|android/gi.test(window.navigator.userAgent),
		BAIDU_PUSH: <?php echo $this->options->JBaiduToken ? 'true' : 'false' ?>,
		BING_PUSH: <?php echo $this->options->JBingToken ? 'true' : 'false' ?>,
		DOCUMENT_TITLE: `<?php $this->options->JDocumentTitle() ?>`,
		LAZY_LOAD: `<?php _getLazyload() ?>`,
		BIRTHDAY: `<?php $this->options->JBirthDay() ?>`,
		MOTTO: `<?php _getAsideAuthorMotto() ?>`,
		PAGE_SIZE: `<?php $this->parameter->pageSize() ?>`
	}
</script>
<?php
$fontUrl = $this->options->JCustomFont;
if (!$fontUrl) {
	$fontUrl = '';
}
if (strpos($fontUrl, 'woff2') !== false) $fontFormat = 'woff2';
elseif (strpos($fontUrl, 'woff') !== false) $fontFormat = 'woff';
elseif (strpos($fontUrl, 'ttf') !== false) $fontFormat = 'truetype';
elseif (strpos($fontUrl, 'eot') !== false) $fontFormat = 'embedded-opentype';
elseif (strpos($fontUrl, 'svg') !== false) $fontFormat = 'svg';
?>
<style>
	<?php
	if (_isMobile()) {
	?>.joe_index__hot-list .item>.item-body>.item-tags-category::-webkit-scrollbar {
		display: none;
	}

	<?php
	}
	if (!_isMobile()) {

		if ($this->options->JHeader_Blur != 'on') {
		?>
			html .joe_header {
			-webkit-backdrop-filter: none;
			backdrop-filter: none;
			background: var(--background);
			}
		<?php
		}
		
		if ($this->is('index')) {
			?>
			.joe_index__hot-list .item>.item-body>.item-tags-category {
				padding-bottom: 3px;
			}
			<?php
		}
	}
	if ($this->options->JGrey_Model == 'on') {
	?>
		html {
			-webkit-filter: grayscale(1);
		}
	<?php
	}

	if (
		($this->options->JWallpaper_Background_Optimal == 'all') ||
		(_isMobile() && $this->options->JWallpaper_Background_Optimal == 'wap') ||
		((!_isMobile()) && ($this->options->JWallpaper_Background_Optimal == 'pc'))
	) {
	?>html .joe_footer .joe_container>.item,
	html .joe_footer .joe_container a,
	html .joe_bread__bread .item,
	html .joe_bread__bread .item .link,
	html .text-muted,
	html .joe_index__title-title>.item,
	html .joe_index__title-notice>a {
		color: var(--classC);
	}

	html .joe_bread__bread>.item>.icon {
		fill: var(--classC);
	}

	html .text-muted>a {
		color: var(--classD);
	}

	<?php
	}
	?>@font-face {
		font-family: 'Joe Font';
		font-weight: 400;
		font-style: normal;
		font-display: swap;
		src: url('<?php echo $fontUrl ?>');
		<?php if ($fontFormat) : ?>src: url('<?php echo $fontUrl ?>') format('<?php echo $fontFormat ?>');
		<?php endif; ?>
	}

	body {
		<?php if ($fontUrl) : ?>font-family: 'Joe Font';
		<?php else : ?>font-family: 'Helvetica Neue', Helvetica, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', '微软雅黑', Arial, sans-serif;
		<?php endif; ?>
	}

	body::before {
		background: <?php
					if (_isMobile()) {
						echo $this->options->JWallpaper_Background_WAP ? "url(" . $this->options->JWallpaper_Background_WAP . ")" : "#f5f5f5";
					} else {
						echo $this->options->JWallpaper_Background_PC ? "url(" . $this->options->JWallpaper_Background_PC . ")" : "#f5f5f5";
					}
					?>;
		background-blend-mode: multiply;
		background-position: center 0;
		background-repeat: no-repeat;
		background-size: cover;
	}

	<?php $this->options->JCustomCSS() ?>
</style>
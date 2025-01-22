<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if ($this->request->getHeader('x-pjax') == 'true') return;

if ($this->options->JFooterMode == 'commercial') {
?>
	<footer class="footer">
		<div class="container-fluid container-footer">
			<?= base64_decode('PGEgaHJlZj0iaHR0cDovL2Jsb2cuYnJpNi5jbiIgcmVsPSJmcmllbmQiIHRhcmdldD0iX2JsYW5rIiBjbGFzcz0iaGlkZSI+5piT6Iiq5Y2a5a6iPC9hPg=='); ?>
			<ul class="list-inline">
				<li class="hidden-xs" style="max-width: 300px;">
					<p>
						<a class="footer-logo" href="<?php $this->options->siteUrl(); ?>" title="<?php $this->options->title() ?>">
							<img referrerpolicy="no-referrer" rel="noreferrer" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?php empty($this->options->JLogo) ? $this->options->themeUrl('assets/images/logo.png') : $this->options->JLogo(); ?>" alt="<?php $this->options->title() ?>" class="lazyload light" style="height: 40px;">
							<img referrerpolicy="no-referrer" rel="noreferrer" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?php $this->options->JDarkLogo(); ?>" alt="<?php $this->options->title() ?>" class="lazyload dark" style="height: 40px;">
						</a>
					</p>
					<div class="footer-muted em09"><?= $this->options->JFooterLeftText ?></div>
				</li>
				<li style="max-width: 550px;">
					<p class="fcode-links"><?= $this->options->JFooterCenter1 ?></p>
					<div class="footer-muted em09"><?= $this->options->JFooterCenter2 ?></div>
					<?php if ($this->options->JBirthDay) : ?>
						<div class="footer-muted em09">
							<span>已运行 <strong class="joe_run__day">00</strong> 天 <strong class="joe_run__hour">00</strong> 时 <strong class="joe_run__minute">00</strong> 分 <strong class="joe_run__second">00</strong> 秒</span>
						</div>
					<?php endif; ?>
					<?php if (!empty($this->options->baidu_statistics)) : ?>
						<style>
							#statistics>span>strong {
								color: var(--theme)
							}
						</style>
						<div class="footer-muted em09" id="statistics">
							<span>今日浏览量&nbsp;<strong>...</strong>丨</span><span>昨日访客&nbsp;<strong>...</strong>丨</span><span>本月访问量&nbsp;<strong>...</strong></span>
						</div>
					<?php endif; ?>
					<?php
					if (!empty($this->options->JFooterContactWechatImg) || !empty($this->options->JFooterContactQQ) || !empty($this->options->JFooterContactWeiBo) || !empty($this->options->JFooterContactEmail)) {
					?>
						<div class="footer-contact mt10">
							<?php
							if (!empty($this->options->JFooterContactWechatImg)) {
							?>
								<a class="toggle-radius hover-show nowave" href="javascript:;">
									<svg class="icon svg" aria-hidden="true">
										<use xlink:href="#icon-d-wechat"></use>
									</svg>
									<div class="hover-show-con footer-wechat-img">
										<img style="box-shadow: 0 5px 10px rgba(0,0,0,.2); border-radius:4px;" height="100" class="lazyload" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?= $this->options->JFooterContactWechatImg ?>" alt="扫一扫加微信 - <?= $this->options->title ?>">
									</div>
								</a>
							<?php
							}
							if (!empty($this->options->JFooterContactQQ)) {
							?>
								<a class="toggle-radius" data-toggle="tooltip" target="_blank" data-original-title="QQ联系" href="https://wpa.qq.com/msgrd?v=3&uin=<?= $this->options->JFooterContactQQ ?>&site=qq&menu=yes">
									<svg class="icon svg" aria-hidden="true" data-viewBox="-50 0 1100 1100" viewBox="-50 0 1100 1100">
										<use xlink:href="#icon-d-qq"></use>
									</svg>
								</a>
							<?php
							}
							if (!empty($this->options->JFooterContactWeiBo)) {
							?>
								<a class="toggle-radius" data-toggle="tooltip" target="_blank" data-original-title="微博" href="<?= $this->options->JFooterContactWeiBo ?>">
									<svg class="icon svg" aria-hidden="true">
										<use xlink:href="#icon-d-weibo"></use>
									</svg>
								</a>
							<?php
							}
							if (!empty($this->options->JFooterContactEmail)) {
							?>
								<a class="toggle-radius" data-toggle="tooltip" target="_blank" data-original-title="发邮件" href="mailto:<?= $this->options->JFooterContactEmail ?>">
									<svg class="icon svg" aria-hidden="true" data-viewBox="-20 80 1024 1024" viewBox="-20 80 1024 1024">
										<use xlink:href="#icon-d-email"></use>
									</svg>
								</a>
							<?php
							}
							?>
						</div>
					<?php
					}
					?>
				</li>
				<?php
				$JFooterMiniImg = joe\optionMulti($this->options->JFooterMiniImg);
				if (!empty($JFooterMiniImg)) {
					echo '<li>';
					foreach ($JFooterMiniImg as $key => $value) {
				?>
						<div class="footer-miniimg" data-toggle="tooltip" title="<?= $value[0] ?? '' ?>">
							<p>
								<img referrerpolicy="no-referrer" rel="noreferrer" class="lazyload" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?= $value[1] ?? '' ?>" alt="<?= $value[0] ?? '' ?> - <?= $this->options->title ?>">
							</p>
							<span class="opacity8 em09"><?= $value[0] ?? '' ?></span>
						</div>
				<?php
					}
					echo '</li>';
				}
				?>
			</ul>
			<?php
			if (!empty($this->options->JFcodeCustomizeCode)) {
				echo '<p class="footer-conter">' . $this->options->JFcodeCustomizeCode . '</p>';
			}
			?>
		</div>

	</footer>
	<?php
	if ($this->options->JFooter_Fish == 'on') {
		echo '<div id="footer_fish" style="background: var(--main-bg-color);"></div><script src="' . joe\theme_url('assets/plugin/FooterFish.js') . '"></script>';
	}
} else {
	?>
	<footer class="joe_footer">
		<div class="joe_container">
			<?= base64_decode('PGEgaHJlZj0iaHR0cDovL2Jsb2cuYnJpNi5jbiIgcmVsPSJmcmllbmQiIHRhcmdldD0iX2JsYW5rIiBjbGFzcz0iaGlkZSI+5piT6Iiq5Y2a5a6iPC9hPg=='); ?>
			<div class="item">
				<?php $this->options->JFooter_Left() ?>
			</div>
			<?php if ($this->options->JBirthDay) : ?>
				<div class="item">
					<span>已运行 <strong class="joe_run__day">00</strong> 天 <strong class="joe_run__hour">00</strong> 时 <strong class="joe_run__minute">00</strong> 分 <strong class="joe_run__second">00</strong> 秒</span>
				</div>
			<?php endif; ?>
			<div class="item">
				<?php $this->options->JFooter_Right() ?>
			</div>
			<?php
			if (!empty($this->options->baidu_statistics)) {
			?>
				<style>
					#statistics>span>strong {
						color: var(--theme)
					}
				</style>
				<div class="item" id="statistics">
					<span>今日浏览量&nbsp;<strong>...</strong>丨</span><span>昨日访客&nbsp;<strong>...</strong>丨</span><span>本月访问量&nbsp;<strong>...</strong></span>
				</div>
			<?php
			}
			?>
		</div>
		<?php
		if ($this->options->JFooter_Fish == 'on') {
			echo '<div id="footer_fish"></div><script src="' . joe\theme_url('assets/plugin/FooterFish.js') . '"></script>';
		}
		?>
	</footer>
<?php
}

$joe_action_bottom = 20;

if ($this->options->JMusic == 'on') {
?>
	<meting-js fixed="true" preload="metadata" mutex="true" volume="0.3" autotheme="true" api="<?= empty($this->options->JMusicApi) ? joe\index('joe/api', '//') . '?routeType=meting&server=:server&type=:type&id=:id&r=:r' : $this->options->JMusicApi ?>" storage="<?= $this->options->JMusicId ?>" order="<?= $this->options->JMusicOrder ?>" server="<?= $this->options->JMusicServer ?>" type="<?= $this->options->JMusicType ?>" dataId="<?= urlencode($this->options->JMusicId) ?>" <?= $this->options->JMusicPlay == 'on' ? 'autoplay="true"' : null ?>></meting-js>
<?php
}
?>

<div class="joe_action">
	<div class="joe_action_item scroll" data-toggle="tooltip" data-placement="left" data-original-title="返回顶部">
		<i class="fa fa-angle-up em12"></i>
	</div>
	<?php
	if ($this->options->JThemeModeSwitch == 'on') {
	?>
		<div class="joe_action_item mode" data-toggle="tooltip" data-placement="left" data-original-title="夜间模式">
			<i class="icon-1 fa fa-sun-o"></i>
			<i class="icon-2 fa fa-moon-o"></i>
		</div>
	<?php
	}
	?>
	<?php
	if ($this->is('post') && joe\isMobile() && $this->options->JArticle_Guide == 'on') {
	?>
		<div data-affix="true" class="joe_action_item posts-nav-box posts-nav-switcher" data-title="文章目录"><i class="fa fa-list-ul"></i></div>
	<?php
	}
	?>
	<?php if ($this->user->uid == $this->authorId) : ?>
		<?php if ($this->is('post')) : ?>
			<div class="joe_action_item" data-toggle="tooltip" data-placement="left" data-original-title="编辑文章">
				<a target="_blank" rel="noopener noreferrer" href="<?php $this->options->adminUrl(); ?>write-post.php?cid=<?php echo $this->cid; ?>"><i class="fa fa-cog fa-spin"></i></a>
			</div>
		<?php elseif ($this->is('page')) : ?>
			<div class="joe_action_item" data-toggle="tooltip" data-placement="left" data-original-title="编辑页面">
				<a target="_blank" rel="noopener noreferrer" href="<?php $this->options->adminUrl(); ?>write-page.php?cid=<?php echo $this->cid; ?>"><i class="fa fa-cog fa-spin"></i></a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php
// SSL安全认证
if ($this->options->JPendant_SSL == 'on') {
	$joe_action_bottom = $joe_action_bottom + 65;
?>
	<div id="cc-myssl-seal" style="width:65px;height:65px;z-index:9;position:fixed;right:0;bottom:0;cursor:pointer;">
		<div title="TrustAsia 安全签章" id="myssl_seal" style="text-align: center">
			<img src="<?= Joe\theme_url('assets/images/myssl-id.png') ?>" alt="SSL" style="width: 100%; height: 100%"></a>
		</div>
	</div>
<?php
}

if (!empty($this->options->JFooterTabbar) && joe\isMobile()) {
	$footer_tabbar = joe\optionMulti($this->options->JFooterTabbar);
} else {
	$footer_tabbar = false;
}
if (!empty($footer_tabbar)) {
?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/options/footer-tabbar.css') ?>">
	<div class="footer-tabbar">
		<?php
		$admin_dir_preg_quote = preg_quote(__TYPECHO_ADMIN_DIR__, '/');
		foreach ($footer_tabbar as $value) {
			$value[1] = $value[1] ?? '';
			if (preg_match("/{$admin_dir_preg_quote}[a-z,-]+\.php/i", $value[1], $match) && !$this->user->hasLogin()) $value[1] = joe\user_url('login', $match[0]);
		?>
			<a class="tabbar-item" title="<?= $value[0] ?? '' ?>" href="<?= $value[1] ?>" target="<?= $value[2] ?? '' ?>">
				<icon <?= empty($value[4]) ? '' : 'style="font-size:' . $value[4] . ';"' ?>>
					<svg class="icon svg" aria-hidden="true">
						<use xlink:href="<?= $value[3] ?? '' ?>"></use>
					</svg>
				</icon>
				<text><?= $value[0] ?? '' ?></text>
			</a>
		<?php
		}
		?>
	</div>
	<script>
		(function() {
			const height = document.querySelector('.footer-tabbar').clientHeight;

			if (document.querySelector('.joe_action')) {
				document.querySelector('.joe_action').style.bottom = (height + <?= $joe_action_bottom ?>) + 'px'
			}

			if (document.getElementById('cc-myssl-seal')) {
				document.getElementById('cc-myssl-seal').style.bottom = height + 'px';
			}

			document.querySelector('.joe_header__slideout').style.height = `calc(var(--vh, 1vh) * 100 - ${(height + document.querySelector('.joe_header').clientHeight)}px)`;

			var aplayerStyle = document.createElement('style');
			aplayerStyle.innerHTML = `html .aplayer.aplayer-fixed .aplayer-body{bottom: ${height}px} .aplayer.aplayer-fixed .aplayer-lrc{bottom: ${height + 10}px}`;
			$('head').append(aplayerStyle);

			document.querySelector('body').style.paddingBottom = height + 'px';
		})();
	</script>
<?php
}
?>
<style>
	html .joe_action {
		bottom: <?= $joe_action_bottom ?>px;
	}
</style>
<?php if ($this->options->JAside_3DTag == 'on') : ?>
	<script data-turbolinks-permanent src="<?= joe\theme_url('assets/plugin/3dtag/3dtag.min.js'); ?>"></script>
<?php endif; ?>
<?php if (!empty($this->options->NewYearLantern)) : ?>
	<script data-turbolinks-permanent src="<?= joe\theme_url('assets/plugin/yihang/china-lantern.min.js', ['text' => $this->options->NewYearLantern]); ?>"></script>
<?php endif; ?>
<?php if ($this->options->JCursorEffects && $this->options->JCursorEffects != 'off') : ?>
	<script data-turbolinks-permanent src="<?= joe\theme_url('assets/plugin/cursor/' . $this->options->JCursorEffects) ?>"></script>
<?php endif; ?>
<script data-turbolinks-permanent src="<?= joe\theme_url('assets/js/svg.icon.js') ?>"></script>

<!-- 自定义JavaScript -->
<script>
	<?php $this->options->JCustomScript() ?>
</script>
<!-- 自定义JavaScript -->

<!-- 自定义底部HTML代码 -->
<?php $this->options->JCustomBodyEnd() ?>
<!-- 自定义底部HTML代码 -->

<!-- 网站统计HTML代码 -->
<?php $this->options->JCustomTrackCode() ?>
<!-- 网站统计HTML代码 -->

<script>
	<?php
	$cookie = Typecho_Cookie::getPrefix();
	$notice = $cookie . '__typecho_notice';
	$type = $cookie . '__typecho_notice_type';

	if (isset($_COOKIE[$notice]) && isset($_COOKIE[$type]) && ($_COOKIE[$type] == 'success' || $_COOKIE[$type] == 'notice' || $_COOKIE[$type] == 'error')) { ?>
		autolog.log("<?php echo preg_replace('#\[\"(.*?)\"\]#', '$1', $_COOKIE[$notice]); ?>！", 'info');
	<?php }

	Typecho_Cookie::delete('__typecho_notice');
	Typecho_Cookie::delete('__typecho_notice_type');

	// 获取脚本结束执行的时间戳
	$end_time = microtime(true);
	// 计算脚本运行时间
	$execution_time = $end_time - JOE_START_TIME;
	$execution_time = number_format($execution_time, 2, '.', '');

	// 记录最终内存使用量
	$end_memory = memory_get_usage();
	// 计算内存消耗
	$memory_usage = $end_memory - JOE_START_MEMORY;
	// 将内存大小转换为 KB
	$memory_usage_kb = round($memory_usage / 1024, 2);
	?>
	console.log("%cTypecho Theme By Joe再续前缘", "color:#fff; background: linear-gradient(270deg, #986fee, #8695e6, #68b7dd, #18d7d3); padding: 8px 15px; border-radius: 0 15px 0 15px");
	window.addEventListener('load', () => {
		// 计算页面加载时间，并转换为秒
		const loadTime = ((performance.now() - Joe.startTime) / 1000).toFixed(2);
		console.log(`主题PHP脚本运行时间：<?= $execution_time ?> S`);
		console.log(`主题PHP脚本内存消耗：<?= $memory_usage_kb ?> KB`);
		console.log(`主题WEB资源加载耗时：${loadTime} S`);
	});
</script>

<?php $this->footer(); ?>
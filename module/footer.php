<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

?>
<div class="joe_action">
	<div class="joe_action_item scroll" data-toggle="tooltip" data-placement="left" title="返回顶部">
		<i class="fa fa-angle-up em12"></i>
	</div>
	<?php
	if ($this->options->JThemeModeSwitch == 'on') {
	?>
		<div class="joe_action_item mode toggle-theme" data-toggle="tooltip" data-placement="left" title="夜间模式">
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
			<div class="joe_action_item" data-toggle="tooltip" data-placement="left" title="编辑文章">
				<a target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl); ?>write-post.php?cid=<?php echo $this->cid; ?>"><i class="fa fa-cog fa-spin"></i></a>
			</div>
		<?php elseif ($this->is('page')) : ?>
			<div class="joe_action_item" data-toggle="tooltip" data-placement="left" title="编辑页面">
				<a target="_blank" href="<?= joe\root_relative_link($this->options->adminUrl); ?>write-page.php?cid=<?php echo $this->cid; ?>"><i class="fa fa-cog fa-spin"></i></a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
<?php

if ($this->request->getHeader('x-pjax') == 'true') return;

if ($this->options->JFooterMode == 'commercial') {
?>
	<footer class="footer">
		<div class="container-fluid container-footer">
			<?= base64_decode('PGEgaHJlZj0iaHR0cDovL2Jsb2cueWloYW5nLmluZm8iIHJlbD0iZnJpZW5kIiB0YXJnZXQ9Il9ibGFuayIgY2xhc3M9ImhpZGUiPuaYk+iIquWNmuWuojwvYT4='); ?>
			<ul class="list-inline <?= empty($this->options->baidu_statistics) ? null : 'flex' ?>">
				<li class="hidden-xs" style="max-width: 300px;">
					<p>
						<a class="footer-logo" href="/" title="<?php $this->options->title() ?>">
							<img referrerpolicy="no-referrer" rel="noreferrer" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?php empty($this->options->JLogo) ? $this->options->themeUrl('assets/images/logo.png') : $this->options->JLogo(); ?>" alt="<?php $this->options->title() ?>" class="lazyload light" style="height: 40px;">
							<img referrerpolicy="no-referrer" rel="noreferrer" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?php $this->options->JDarkLogo(); ?>" alt="<?php $this->options->title() ?>" class="lazyload dark" style="height: 40px;">
						</a>
					</p>
					<div class="footer-muted em09"><?= $this->options->JFooterLeftText ?></div>
				</li>
				<li style="max-width: 550px;">
					<p class="fcode-links"><?= $this->options->JFooterCenter1 ?></p>
					<div class="footer-muted em09 mb10"><?= $this->options->JFooterCenter2 ?></div>
					<?php
					if (empty($this->options->baidu_statistics) && $this->options->JOnLineCountThreshold && is_numeric($this->options->JOnLineCountThreshold)) {
					?>
						<div class="footer-muted em09 mb10">当前在线人数 <span class="online-users-count" style="color: var(--theme);"></span> 位</div>
					<?php
					}
					if (empty($this->options->baidu_statistics) && $this->options->JBirthDay) { ?>
						<div class="footer-muted em09 mb10">
							<span>已运行 <strong class="joe_run__day">00</strong> 天 <strong class="joe_run__hour">00</strong> 时 <strong class="joe_run__minute">00</strong> 分 <strong class="joe_run__second">00</strong> 秒</span>
						</div>
					<?php
					}
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
								<a class="toggle-radius" data-toggle="tooltip" target="_blank" title="QQ联系" href="https://wpa.qq.com/msgrd?v=3&uin=<?= $this->options->JFooterContactQQ ?>&site=qq&menu=yes">
									<svg class="icon svg" aria-hidden="true" data-viewBox="-50 0 1100 1100" viewBox="-50 0 1100 1100">
										<use xlink:href="#icon-d-qq"></use>
									</svg>
								</a>
							<?php
							}
							if (!empty($this->options->JFooterContactWeiBo)) {
							?>
								<a class="toggle-radius" data-toggle="tooltip" target="_blank" title="微博" href="<?= $this->options->JFooterContactWeiBo ?>">
									<svg class="icon svg" aria-hidden="true">
										<use xlink:href="#icon-d-weibo"></use>
									</svg>
								</a>
							<?php
							}
							if (!empty($this->options->JFooterContactEmail)) {
							?>
								<a class="toggle-radius" data-toggle="tooltip" target="_blank" title="发邮件" href="mailto:<?= $this->options->JFooterContactEmail ?>">
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
				if (!empty($this->options->baidu_statistics)) {
				?>
					<li style="max-width: 550px;">
						<?php
						if ($this->options->JOnLineCountThreshold && is_numeric($this->options->JOnLineCountThreshold)) {
						?>
							<div class="footer-muted em09 mb10">当前在线人数 <span class="online-users-count" style="color: var(--theme);"></span> 位</div>
						<?php
						}
						if ($this->options->JBirthDay) {
						?>
							<div class="footer-muted em09 mb10">
								本站已运行 <strong class="joe_run__day">00</strong> 天 <strong class="joe_run__hour">00</strong> 时 <strong class="joe_run__minute">00</strong> 分 <strong class="joe_run__second">00</strong> 秒
							</div>
						<?php
						}
						if (joe\isMobile()) {
						?>
							<div class="footer-muted em09" id="statistics">
								<span>今日浏览 <strong>...</strong> 次丨</span><span>昨日访客 <strong>...</strong> 位丨</span><span>本月访问 <strong>...</strong> 次</span>
							</div>
						<?php
						} else {
						?>
							<div class="footer-muted em09" id="statistics">
								<p>
									<span>今日均访 <strong>...</strong> 秒丨</span><span>昨日均访 <strong>...</strong> 秒丨</span><span>本月均访 <strong>...</strong> 秒</span>
								</p>
								<p>
									<span>今日访客 <strong>...</strong> 位丨</span><span>昨日访客 <strong>...</strong> 位丨</span><span>本月访客 <strong>...</strong> 位</span>
								</p>
								<p>
									<span>今日浏览 <strong>...</strong> 次丨</span><span>昨日浏览 <strong>...</strong> 次丨</span><span>本月浏览 <strong>...</strong> 次</span>
								</p>
							</div>
						<?php
						}
						?>
					</li>
					<?php
				}
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
		echo '<div id="footer_fish" style="background: var(--main-bg-color);"></div><script defer src="' . joe\theme_url('assets/plugin/FooterFish.js') . '"></script>';
	}
} else {
	?>
	<footer class="joe_footer">
		<div class="joe_container">
			<?= base64_decode('PGEgaHJlZj0iaHR0cDovL2Jsb2cueWloYW5nLmluZm8iIHJlbD0iZnJpZW5kIiB0YXJnZXQ9Il9ibGFuayIgY2xhc3M9ImhpZGUiPuaYk+iIquWNmuWuojwvYT4='); ?>
			<div class="item">
				<?php $this->options->JFooter_Left() ?>
			</div>
			<?php if ($this->options->JOnLineCountThreshold && is_numeric($this->options->JOnLineCountThreshold)) : ?>
				<div class="item">
					<span>当前在线 <span class="online-users-count" style="color: var(--theme);"></span> 人</span>
				</div>
			<?php endif; ?>
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
				<div class="item" id="statistics">
					<span>今日浏览量&nbsp;<strong>...</strong>丨</span><span>昨日访客&nbsp;<strong>...</strong>丨</span><span>本月访问量&nbsp;<strong>...</strong></span>
				</div>
			<?php
			}
			?>
		</div>
		<?php
		if ($this->options->JFooter_Fish == 'on') {
			echo '<div id="footer_fish"></div><script defer src="' . joe\theme_url('assets/plugin/FooterFish.js') . '"></script>';
		}
		?>
	</footer>
<?php
}

if ($this->options->JMusic == 'on') {
?>
	<meting-js fixed="true" preload="metadata" mutex="true" volume="0.7" autotheme="true" api="<?= empty($this->options->JMusicApi) ? joe\root_relative_link($this->options->index . '/joe/api/meting?server=:server&type=:type&id=:id') : $this->options->JMusicApi ?>" storage="<?= $this->options->JMusicId ?>" order="<?= $this->options->JMusicOrder ?>" server="<?= $this->options->JMusicServer ?>" type="<?= $this->options->JMusicType ?>" dataId="<?= urlencode($this->options->JMusicId) ?>" <?= $this->options->JMusicPlay == 'on' ? 'autoplay="true"' : null ?>></meting-js>
<?php
}

// SSL安全认证
if ($this->options->JPendant_SSL == 'on') {
?>
	<div id="cc-myssl-seal" style="width:65px;height:65px;z-index:9;position:fixed;right:0;bottom:0;cursor:pointer;">
		<div title="TrustAsia 安全签章" id="myssl_seal" style="text-align: center">
			<img src="<?= joe\theme_url('assets/images/myssl-id.png') ?>" alt="SSL" style="width: 100%; height: 100%"></a>
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
<?php
}
?>
<?php if ($this->options->JAside_3DTag == 'on') : ?>
	<script defer src="<?= joe\theme_url('assets/plugin/3dtag/3dtag.min.js', null); ?>"></script>
<?php endif; ?>
<?php if (!empty($this->options->JFestivalLantern)) : ?>
	<script defer src="<?= joe\theme_url('assets/plugin/yihang/china-lantern.min.js', ['text' => $this->options->JFestivalLantern]); ?>"></script>
<?php endif; ?>
<?php if ($this->options->JCursorEffects && $this->options->JCursorEffects != 'off') : ?>
	<script defer src="<?= joe\theme_url('assets/plugin/cursor/' . $this->options->JCursorEffects, null) ?>"></script>
<?php endif; ?>
<?php
if (\think\helper\Str::contains($this->options->JCustomFont, '||')) {
	$JCustomFont = joe\optionMulti($this->options->JCustomFont, '||', null, ['url', 'font']);
?>
	<link async rel="stylesheet" href="<?= $JCustomFont['url'] ?>" async>
	<style>
		html body {
			font-family: '<?= $JCustomFont['font'] ?>'
		}
	</style>
<?php
}
?>

<!-- 自定义底部HTML代码 -->
<?php $this->options->JCustomBodyEnd() ?>
<!-- 自定义底部HTML代码 -->

<!-- 自定义JavaScript -->
<script>
	<?php $this->options->JCustomScript() ?>
</script>
<!-- 自定义JavaScript -->

<!-- 网站统计HTML代码 -->
<?php $this->options->JCustomTrackCode() ?>
<!-- 网站统计HTML代码 -->

<script>
	<?php
	$cookie = Typecho\Cookie::getPrefix();
	$notice = $cookie . '__typecho_notice';
	$type = $cookie . '__typecho_notice_type';

	if (isset($_COOKIE[$notice]) && isset($_COOKIE[$type]) && ($_COOKIE[$type] == 'success' || $_COOKIE[$type] == 'notice' || $_COOKIE[$type] == 'error')) { ?>
		autolog.info("<?php echo preg_replace('#\[\"(.*?)\"\]#', '$1', $_COOKIE[$notice]); ?>！");
	<?php }

	Typecho\Cookie::delete('__typecho_notice');
	Typecho\Cookie::delete('__typecho_notice_type');
	?>
	console.log("%cTypecho Theme By Joe再续前缘", "color:#fff; background: linear-gradient(270deg, #986fee, #8695e6, #68b7dd, #18d7d3); padding: 8px 15px; border-radius: 0 15px 0 15px");
	window.addEventListener('load', () => {
		// 计算页面加载时间，并转换为秒
		const loadTime = ((performance.now() - Joe.startTime) / 1000).toFixed(2);
		console.log(`主题WEB资源加载耗时：${loadTime} S`);
	});
</script>

<?php Helper::options()->JoeDeBug == 'on' ? $this->need('module/trace.php') : null ?>

<?php $this->footer(); ?>
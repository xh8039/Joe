<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$joe_action_bottom = 20;

if ($this->options->JMusic == 'on') {
?>
	<meting-js id="global-meting-js" data-turbolinks-permanent fixed="true" preload="metadata" mutex="true" volume="0.3" autotheme="true" api="<?= empty($this->options->JMusicApi) ? joe\index('joe/api', '//') . '/joe/api?routeType=meting&server=:server&type=:type&id=:id&r=:r' : $this->options->JMusicApi ?>" storage="<?= $this->options->JMusicId ?>" order="<?= $this->options->JMusicOrder ?>" server="<?= $this->options->JMusicServer ?>" type="<?= $this->options->JMusicType ?>" dataId="<?= $this->options->JMusicId ?>" <?= $this->options->JMusicPlay == 'on' ? 'autoplay="true"' : null ?>></meting-js>
<?php
}
?>

<div class="joe_action" data-turbolinks-permanent id="data-turbolinks-permanent-joe-action">
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
	<style data-turbolinks-permanent>
		#cc-myssl-seal {
			width: 65px;
			height: 65px;
			z-index: 9;
			position: fixed;
			right: 0;
			bottom: 0;
			cursor: pointer;
		}
	</style>
	<div data-turbolinks-permanent id="cc-myssl-seal">
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
	<link data-turbolinks-permanent rel="stylesheet" href="<?= joe\theme_url('assets/css/options/footer-tabbar.css') ?>">
	<div data-turbolinks-permanent class="footer-tabbar">
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
	<script defer>
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
<style data-turbolinks-permanent>
	html .joe_action {
		bottom: <?= $joe_action_bottom ?>px;
	}
</style>
<?php if ($this->options->JAside_3DTag == 'on') : ?>
	<script data-turbolinks-permanent src="<?= joe\theme_url('assets/plugin/3dtag/3dtag.min.js'); ?>"></script>
<?php endif; ?>
<?php if (!empty($this->options->NewYearLantern)) : ?>
	<script data-turbolinks-permanent src="<?= joe\theme_url('assets/plugin/china-lantern.min.js', ['text' => $this->options->NewYearLantern]); ?>"></script>
<?php endif; ?>
<?php if ($this->options->JCursorEffects && $this->options->JCursorEffects != 'off') : ?>
	<script data-turbolinks-permanent src="<?= joe\theme_url('assets/plugin/cursor/' . $this->options->JCursorEffects) ?>" async></script>
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
	<?php if ($this->request->getHeader('X-Turbolinks') != 'true') {
		$cookie = Typecho_Cookie::getPrefix();
		$notice = $cookie . '__typecho_notice';
		$type = $cookie . '__typecho_notice_type';

		if (isset($_COOKIE[$notice]) && isset($_COOKIE[$type]) && ($_COOKIE[$type] == 'success' || $_COOKIE[$type] == 'notice' || $_COOKIE[$type] == 'error')) { ?>
			Qmsg.info("<?php echo preg_replace('#\[\"(.*?)\"\]#', '$1', $_COOKIE[$notice]); ?>！");
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
	<?php } ?>
</script>

<?php $this->footer(); ?>
<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$joe_action_bottom = 20;

if ($this->options->JMusic == 'on') {
?>
	<meting-js fixed="true" preload="metadata" mutex="true" volume="0.3" autotheme="true" storage="<?= $this->options->JMusicId ?>" order="<?= $this->options->JMusicOrder ?>" server="<?= $this->options->JMusicServer ?>" type="<?= $this->options->JMusicType ?>" id="<?= $this->options->JMusicId ?>" <?= $this->options->JMusicPlay == 'on' ? 'autoplay="true"' : null ?>></meting-js>
	<script>
		window.meting_api = `<?= empty($this->options->JMusicApi) ? '${Joe.BASE_API}/meting?server=:server&type=:type&id=:id&r=:r' : $this->options->JMusicApi ?>`
	</script>
	<script src="<?= joe\theme_url('assets/plugin/Meting.js'); ?>"></script>
<?php
}
?>

<footer class="joe_footer">
	<div class="joe_container">
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

<div class="joe_action">
	<div class="joe_action_item scroll" title="回到顶部">
		<i class="fa fa-angle-up em12"></i>
	</div>
	<div class="joe_action_item mode">
		<i class="icon-1 fa fa-sun-o" title="日间模式"></i>
		<i class="icon-2 fa fa-moon-o" title="夜间模式"></i>
	</div>
	<div class="joe_action_item full_screen">
		<i class="icon-1 fa fa-expand" title="全屏模式"></i>
		<i class="icon-2 fa fa-compress" title="关闭全屏" style="display: none;"></i>
	</div>
	<?php if ($this->user->uid == $this->authorId) : ?>
		<?php if ($this->is('post')) : ?>
			<div class="joe_action_item" title="编辑文章">
				<a target="_blank" rel="noopener noreferrer" href="<?php $this->options->adminUrl(); ?>write-post.php?cid=<?php echo $this->cid; ?>"><i class="fa fa-cog fa-spin"></i></a>
			</div>
		<?php elseif ($this->is('page')) : ?>
			<div class="joe_action_item" title="编辑页面">
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
	<style>
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
	<div id="cc-myssl-seal">
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
		foreach ($footer_tabbar as $value) {
			$value[1] = $value[1] ?? '';
			if (preg_match('/\/admin\/[a-z,-]+\.php/i', $value[1], $match) && !$this->user->hasLogin()) $value[1] = joe\user_url('login', $match[0]);
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
		const height = document.querySelector('.footer-tabbar').clientHeight;

		if (document.querySelector('.joe_action')) {
			document.querySelector('.joe_action').style.bottom = (height + <?= $joe_action_bottom ?>) + 'px'
		}

		if (document.getElementById('cc-myssl-seal')) {
			document.getElementById('cc-myssl-seal').style.bottom = height + 'px';
			// document.querySelector('.joe_action').style.bottom = (document.getElementById('cc-myssl-seal').clientHeight + height + 20) + 'px';
		}

		var aplayerStyle = document.createElement('style');
		aplayerStyle.innerHTML = `html .aplayer.aplayer-fixed .aplayer-body{bottom: ${height}px} .aplayer.aplayer-fixed .aplayer-lrc{bottom: ${height + 10}px}`;
		$('head').append(aplayerStyle);

		document.querySelector('body').style.paddingBottom = height + 'px';
	</script>
<?php
}
?>
<style>
	html .joe_action {
		bottom: <?= $joe_action_bottom ?>px;
	}
</style>
<script src="<?= joe\theme_url('assets/js/svg.icon.js') ?>"></script>
<script>
	<?php
	$cookie = Typecho_Cookie::getPrefix();
	$notice = $cookie . '__typecho_notice';
	$type = $cookie . '__typecho_notice_type';
	?>
	<?php if (isset($_COOKIE[$notice]) && isset($_COOKIE[$type]) && ($_COOKIE[$type] == 'success' || $_COOKIE[$type] == 'notice' || $_COOKIE[$type] == 'error')) : ?>
		Qmsg.info("<?php echo preg_replace('#\[\"(.*?)\"\]#', '$1', $_COOKIE[$notice]); ?>！")
	<?php endif; ?>
	<?php
	Typecho_Cookie::delete('__typecho_notice');
	Typecho_Cookie::delete('__typecho_notice_type');
	?>
	console.log("%cTheme By Joe再续前缘版", "color:#fff; background: linear-gradient(270deg, #986fee, #8695e6, #68b7dd, #18d7d3); padding: 8px 15px; border-radius: 0 15px 0 15px");

	window.addEventListener('load', () => {
		// 计算页面加载时间，并转换为秒
		const loadTime = ((performance.now() - Joe.startTime) / 1000).toFixed(2);
		console.log(`页面加载耗时：${loadTime} 秒`);
	});

	/* 自定义JavaScript */

	<?php $this->options->JCustomScript() ?>

	/* 自定义JavaScript */
</script>

<!-- 自定义底部HTML代码 -->

<?php $this->options->JCustomBodyEnd() ?>

<!-- 自定义底部HTML代码 -->

<!-- 网站统计HTML代码 -->

<?php $this->options->JCustomTrackCode() ?>

<!-- 网站统计HTML代码 -->

<?php $this->footer(); ?>
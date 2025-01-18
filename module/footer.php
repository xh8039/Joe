<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if ($this->is('index') && ($this->options->JFriendsSpiderHide != 'on' || !joe\detectSpider())) {
	$db = Typecho_Db::get();
	$friends = $db->fetchAll($db->select()->from('table.friends')->where('status = ?', 1)->where("FIND_IN_SET('index_bottom',position)")->order('order', Typecho_Db::SORT_DESC));
	if (sizeof($friends) > 0) : ?>
		<?php
		$friends_page = $db->fetchRow($db->select()->from('table.contents')->where('type = ?', 'page')->where('template = ?', 'friends.php')->where('status = ?', 'publish')->limit(1));
		if ($friends_page) {
			$friends_page_pathinfo = Typecho\Router::url('page', $friends_page);
			$friends_page_url = Typecho\Common::url($friends_page_pathinfo, $this->options->index);
		} else {
			$friends_page_url = null;
		}
		?>
		<div class="container fluid-widget">
			<div class="links-widget mb20">
				<div class="box-body notop">
					<div class="title-theme">友情链接<?= $friends_page_url ? '<div class="pull-right em09 mt3"><a href="' . $friends_page_url . '" class="muted-2-color"><i class="fa fa-angle-right fa-fw"></i>申请友链</a></div>' : null ?></div>
				</div>
				<div class="links-box links-style-simple zib-widget">
					<?php
					if ($this->options->JFriends_shuffle == 'on') shuffle($friends);
					$friends = array_values($friends);
					foreach ($friends as $key => $item) echo '<a rel="' . $item['rel'] . '" target="_blank" class="' . ($key ? 'icon-spot' : null) . '" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="' . ($item['description'] ?? '暂无简介') . '" referrer="unsafe-url" href="' . $item['url'] . '" data-original-title="' . $item['title'] . '">' . $item['title'] . '</a>';
					if ($friends_page_url) echo '<a class="icon-spot" href="' . $friends_page_url . '">查看更多</a>';
					?>
				</div>
			</div>
		</div>
	<?php endif;
}

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
					<div class="footer-contact mt10">
						<a class="toggle-radius hover-show nowave" href="javascript:;">
							<svg class="icon svg" aria-hidden="true">
								<use xlink:href="#icon-d-wechat"></use>
							</svg>
							<div class="hover-show-con footer-wechat-img">
								<img style="box-shadow: 0 5px 10px rgba(0,0,0,.2); border-radius:4px;" height="100" class="lazyload" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?= $this->options->JFooterContactWechatImg ?>" alt="扫一扫加微信 - <?= $this->options->title ?>">
							</div>
						</a>
						<a class="toggle-radius" data-toggle="tooltip" target="_blank" data-original-title="QQ联系" href="https://wpa.qq.com/msgrd?v=3&uin=<?= $this->options->JFooterContactQQ ?>&site=qq&menu=yes">
							<svg class="icon svg" aria-hidden="true" data-viewBox="-50 0 1100 1100" viewBox="-50 0 1100 1100">
								<use xlink:href="#icon-d-qq"></use>
							</svg>
						</a>
						<a class="toggle-radius" data-toggle="tooltip" target="_blank" data-original-title="微博" href="<?= $this->options->JFooterContactWeiBo ?>">
							<svg class="icon svg" aria-hidden="true">
								<use xlink:href="#icon-d-weibo"></use>
							</svg>
						</a>
						<a class="toggle-radius" data-toggle="tooltip" target="_blank" data-original-title="发邮件" href="mailto:<?= $this->options->JFooterContactEmail ?>">
							<svg class="icon svg" aria-hidden="true" data-viewBox="-20 80 1024 1024" viewBox="-20 80 1024 1024">
								<use xlink:href="#icon-d-email"></use>
							</svg>
						</a>
					</div>
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
?>
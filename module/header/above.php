<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<div class="joe_header__above">
	<div class="joe_container">
		<button type="button" class="joe_header__above-slideicon"><i class="em12 css-icon i-menu"><i></i></i></button>
		<a title="<?php $this->options->title(); ?>" class="joe_header__above-logo <?= $this->options->JLogo_Light_Effect == 'on' ? 'joe_scan_light' : null ?>" href="/">
			<img data-src="<?php empty($this->options->JLogo) ? $this->options->themeUrl('assets/images/logo.png') : $this->options->JLogo(); ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="<?php $this->options->title(); ?>" class="lazyload light" />
			<img data-src="<?php $this->options->JDarkLogo(); ?>" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="<?php $this->options->title(); ?>" class="lazyload dark" />
		</a>
		<?php
		if (joe\isPc()) {
			?>
		<nav class="joe_header__above-nav">
			<a class="item <?= $this->is('index') ? 'active' : '' ?>" href="/" title="首页">首页</a>
			<?php
			$this->widget('Widget_Contents_Page_List')->to($pages);
			$this->options->JNavMaxNum = isset($this->options->JNavMaxNum) ? $this->options->JNavMaxNum : 3;
			?>
			<?php if (count($pages->stack) <= $this->options->JNavMaxNum) : ?>
				<?php foreach ($pages->stack as $item) : ?>
					<a class="item <?php echo $this->is('page', $item['slug']) ? 'active' : '' ?>" href="<?= joe\root_relative_link($item['permalink']) ?>" title="<?php echo $item['title'] ?>"><?php echo $item['title'] ?></a>
				<?php endforeach; ?>
			<?php else : ?>
				<?php foreach (array_slice($pages->stack, 0, $this->options->JNavMaxNum) as $item) : ?>
					<a class="item <?php echo $this->is('page', $item['slug']) ? 'active' : '' ?>" href="<?= joe\root_relative_link($item['permalink']) ?>" title="<?php echo $item['title'] ?>"><?php echo $item['title'] ?></a>
				<?php endforeach; ?>
				<div class="joe_dropdown" trigger="hover" placement="60px" style="margin-right: 15px;">
					<div class="joe_dropdown__link">
						<a href="javascript:;" rel="nofollow">更多</a>
						<svg class="joe_dropdown__link-icon" width="14" height="14" style="color: var(--main);">
							<use xlink:href="#icon-joe-header-dropdown"></use>
						</svg>
					</div>
					<nav class="joe_dropdown__menu">
						<?php foreach (array_slice($pages->stack, $this->options->JNavMaxNum) as $item) : ?>
							<a class="<?php echo $this->is('page', $item['slug']) ? 'active' : '' ?>" href="<?= joe\root_relative_link($item['permalink']) ?>" title="<?php echo $item['title'] ?>"><?php echo $item['title'] ?></a>
						<?php endforeach; ?>
					</nav>
				</div>
			<?php endif; ?>
			<?php $JMoreNavs = joe\optionMulti($this->options->JMoreNavs); ?>
			<?php if (sizeof($JMoreNavs) > 0) : ?>
				<div class="joe_dropdown" trigger="hover" placement="60px">
					<div class="joe_dropdown__link">
						<a href="javascript:;" rel="nofollow">推荐</a>
						<svg class="joe_dropdown__link-icon" width="14" height="14" style="color: var(--main);">
							<use xlink:href="#icon-joe-header-dropdown"></use>
						</svg>
					</div>
					<nav class="joe_dropdown__menu">
						<?php foreach ($JMoreNavs as $item) : ?>
							<a href="<?= $item[1] ?>" target="_blank" rel="nofollow"><?= $item[0] ?></a>
						<?php endforeach; ?>
					</nav>
				</div>
			<?php endif; ?>
		</nav>
		<form class="joe_header__above-search" method="post" action="<?php $this->options->siteUrl(); ?>">
			<input maxlength="16" autocomplete="off" placeholder="请输入关键字..." name="s" value="<?php echo $this->is('search') ? $this->archiveTitle(' &raquo; ', '', '') : '' ?>" class="input" type="text" />
			<button type="submit" class="submit">Search</button>
			<span class="icon"></span>
			<nav class="result">
				<?php $this->widget('Widget_Contents_Hot@Search', 'action=search&pageSize=5')->to($item); ?>
				<?php $index = 1; ?>
				<?php while ($item->next()) : ?>
					<a href="<?php joe\root_relative_link($item->permalink); ?>" title="<?php $item->title(); ?>" class="item">
						<span class="sort"><?php echo $index; ?></span>
						<span class="text"><?php $item->title(); ?></span>
						<span class="views"><?php echo number_format($item->views); ?> 阅读</span>
					</a>
					<?php $index++; ?>
				<?php endwhile; ?>
			</nav>
		</form>
			<?php
		}
		?>
		<svg class="icon svg joe_header__above-searchicon" aria-hidden="true">
			<use xlink:href="#icon-search"></use>
		</svg>
	</div>
</div>
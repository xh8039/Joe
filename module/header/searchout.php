<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<div class="joe_header__searchout">
	<div class="joe_container">
		<div class="joe_header__searchout-inner">
			<form class="search" method="post" action="<?php $this->options->siteUrl(); ?>">
				<input maxlength="16" autocomplete="off" placeholder="请输入关键字..." name="s" value="<?php echo $this->is('search') ? $this->archiveTitle(' &raquo; ', '', '') : '' ?>" class="input" type="text" />
				<button type="submit" class="submit">
					<svg style="fill: #fff;" width="20" height="20"><use xlink:href="#icon-joe-searchout-search"></use></svg>
				</button>
			</form>
			<?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 20))->to($tags); ?>
			<?php if ($tags->have()) : ?>
				<div class="title">
					<svg class="icon" width="22" height="22"><use xlink:href="#icon-joe-searchout-cloud"></use></svg>标签搜索
				</div>
				<ul class="cloud">
					<?php $colors  = [
						'#F8D800',
						'#0396FF',
						'#EA5455',
						'#7367F0',
						'#32CCBC',
						'#F6416C',
						'#28C76F',
						'#9F44D3',
						'#F55555',
						'#736EFE',
						'#E96D71',
						'#DE4313',
						'#D939CD',
						'#4C83FF',
						'#F072B6',
						'#C346C2',
						'#5961F9',
						'#FD6585',
						'#465EFB',
						'#FFC600',
						'#FA742B',
						'#5151E5',
						'#BB4E75',
						'#FF52E5',
						'#49C628',
						'#00EAFF',
						'#F067B4',
						'#F067B4',
						'#ff9a9e',
						'#00f2fe',
						'#4facfe',
						'#f093fb',
						'#6fa3ef',
						'#bc99c4',
						'#46c47c',
						'#f9bb3c',
						'#e8583d',
						'#f68e5f',
					]; ?>
					<?php while ($tags->next()) : ?>
						<li class="item">
							<a style="background: <?php echo $colors[rand(0, count($colors) - 1)] ?>" href="<?= joe\root_relative_link($tags->permalink); ?>"><?php $tags->name(); ?></a>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</div>
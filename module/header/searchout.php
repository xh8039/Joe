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
					<svg style="fill: #fff;" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
						<path d="M1008.19 932.031L771.72 695.56a431.153 431.153 0 1 0-76.158 76.158l236.408 236.472a53.758 53.758 0 0 0 76.158 0 53.758 53.758 0 0 0 0-76.158zM107.807 431.185a323.637 323.637 0 0 1 323.316-323.381 323.7 323.7 0 0 1 323.381 323.38 323.637 323.637 0 0 1-323.38 323.317 323.637 323.637 0 0 1-323.317-323.316z"></path>
					</svg>
				</button>
			</form>
			<?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 20))->to($tags); ?>
			<?php if ($tags->have()) : ?>
				<div class="title">
					<svg class="icon" viewBox="0 0 1445 1024" xmlns="http://www.w3.org/2000/svg" width="22" height="22">
						<path d="M1055.021 277.865a348.762 348.762 0 0 1 348.401 348.341c0 178.96-136.132 327.68-311.778 346.172l-758.603 2.35A291.238 291.238 0 0 1 42.165 683.79a291.238 291.238 0 0 1 273.227-290.334 369.242 369.242 0 0 1 368.4-351.292 370.568 370.568 0 0 1 344.184 236.905c9.336-.783 18.19-1.205 27.045-1.205zM683.791 156.25a255.036 255.036 0 0 0-254.735 254.796V507h-95.955a177.032 177.032 0 0 0-176.79 176.791 177.032 177.032 0 0 0 176.79 176.85h721.98a234.677 234.677 0 0 0 234.316-234.435 234.616 234.616 0 0 0-234.316-234.255 234.616 234.616 0 0 0-234.315 234.315v18.07H706.56v-18.07A348.4 348.4 0 0 1 915.817 307.2a255.578 255.578 0 0 0-232.026-151.01z" />
					</svg>标签搜索
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
							<a style="background: <?php echo $colors[mt_rand(0, count($colors) - 1)] ?>" href="<?= joe\root_relative_link($tags->permalink); ?>"><?php $tags->name(); ?></a>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</div>
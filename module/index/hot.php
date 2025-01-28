<?php

/**
 * 热门文章模块
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if (Joe\isMobile()) $this->options->JIndex_Hot = $this->options->JIndex_Mobile_Hot;
if ((is_numeric($this->options->JIndex_Hot)) && ($this->options->JIndex_Hot >= 1)) : ?>
	<?php $this->widget('Widget_Contents_Hot@Index', 'pageSize=' . $this->options->JIndex_Hot)->to($item); ?>
	<div class="box-body notop">
		<div class="title-theme">热门文章</div>
	</div>
	<div class="joe_index__hot mb25">
		<ul class="joe_index__hot-list">
			<?php while ($item->next()) : ?>
				<li class="item">
					<a class="link" href="<?= joe\permalink($item->permalink); ?>" title="<?php $item->title(); ?>">
						<figure class="inner">
							<span class="views"><?= number_format($item->views); ?> ℃</span>
							<img referrerpolicy="no-referrer" rel="noreferrer" width="100%" height="120" class="image lazyload error-thumbnail" onerror="Joe.thumbnailError()" src="<?php joe\getLazyload(); ?>" data-src="<?= joe\getThumbnails($item)[0]; ?>" alt="<?php $item->title(); ?>" />
						</figure>
					</a>
					<div class="item-body">
						<h2 class="item-heading">
							<a title="<?php $item->title(); ?>" alt="<?php $item->title(); ?>" href="<?= joe\permalink($item->permalink); ?>"><?php $item->title(); ?></a>
						</h2>
						<div class="item-tags scroll-x no-scrollbar mb6">
							<?php
							$color_array = ['c-blue', 'c-yellow', 'c-green', 'c-cyan', 'c-blue-2', 'c-purple-2', 'c-yellow-2', 'c-purple', 'c-red-2', 'c-red'];
							foreach ($item->categories as $key => $value) {
								echo '<a class="but ' . $color_array[$key] . '" title="查看此分类更多文章" href="' . $value['url'] . '"><i class="fa fa-folder-open-o" aria-hidden="true"></i>' . $value['name'] . '</a>';
							}
							foreach ($item->tags as $key => $value) {
								echo '<a href="' . joe\permalink($value['permalink']) . '" title="查看此标签更多文章" class="but"># ' . $value['name'] . '</a>';
							}
							?>
						</div>
						<div class="item-meta muted-2-color flex jsb ac">
							<item class="meta-author flex ac">
								<a href="<?= joe\permalink($item->author->permalink) ?>"><span class="avatar-mini">
										<img alt="<?php $item->author() ?>的头像 - <?php $this->options->title() ?>" src="<?php joe\getAvatarLazyload(); ?>" data-src="<?php joe\getAvatarByMail($item->author->mail) ?>" class="avatar avatar-id-1 ls-is-cached lazyload"></span></a>
								<span title="<?= $item->date('Y-m-d H:i:s') ?>" class="ml6"><?= joe\dateWord($item->dateWord) ?></span>
							</item>
							<div class="meta-right">
								<item class="meta-comm">
									<svg class="icon" aria-hidden="true">
										<use xlink:href="#icon-comment"></use>
									</svg><?php $item->commentsNum('%d') ?>
								</item>
								<item class="meta-view">
									<svg class="icon" aria-hidden="true">
										<use xlink:href="#icon-view"></use>
									</svg><?= number_format($item->views); ?>
								</item>
								<item class="meta-like"><svg class="icon" aria-hidden="true">
										<use xlink:href="#icon-like"></use>
									</svg><?= number_format($item->agree) ?>
								</item>
							</div>
						</div>
					</div>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
<?php endif; ?>
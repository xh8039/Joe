<?php

/**
 * 推荐文章模块
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$recommend = [];
$recommend_text = joe\isMobile() ? $this->options->JIndex_Mobile_Recommend : $this->options->JIndex_Recommend;
if ($recommend_text) {
	$recommend = joe\optionMulti($recommend_text, '||', false);
}
if (!empty($recommend)) {
	if ($this->options->JIndex_Recommend_Style == 'simple') {
?>
		<div class="box-body notop">
			<div class="title-theme">推荐文章</div>
		</div>
		<div class="joe_index__banner-recommend">
			<?php
			foreach ($recommend as $cid) {
				$this->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid)->to($item);
				if (empty($item->permalink)) continue;
			?>
				<figure class="item">
					<a class="thumbnail" href="<?= joe\root_relative_link($item->permalink) ?>" title="<?php $item->title() ?>">
						<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="<?= joe\getLazyload(); ?>" data-src="<?= joe\getThumbnails($item)[0]; ?>" alt="<?php $item->title() ?>" />
					</a>
					<span class="type">推荐</span>
					<figcaption class="information">
						<div class="text"><?php $item->title() ?></div>
					</figcaption>
				</figure>
			<?php
			}
			?>
		</div>
	<?php
	}
	if ($this->options->JIndex_Recommend_Style == 'full') {
	?>
		<div class="box-body notop">
			<div class="title-theme">推荐文章</div>
		</div>
		<div class="joe_index__hot mb25">
			<ul class="joe_index__hot-list">
				<?php
				foreach ($recommend as $cid) {
					$this->widget('Widget_Contents_Post@' . $cid, 'cid=' . $cid)->to($item);
					if (empty($item->permalink)) continue;
				?>
					<li class="item">
						<a class="link" href="<?= joe\root_relative_link($item->permalink); ?>" title="<?php $item->title(); ?>">
							<figure class="inner">
								<span class="type">推荐</span>
								<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="120" class="image lazyload" src="<?= joe\getLazyload(); ?>" data-src="<?= joe\getThumbnails($item)[0]; ?>" alt="<?php $item->title(); ?>" />
							</figure>
						</a>
						<div class="item-body">
							<h2 class="item-heading">
								<a title="<?php $item->title(); ?>" alt="<?php $item->title(); ?>" href="<?= joe\root_relative_link($item->permalink); ?>"><?php $item->title(); ?></a>
							</h2>
							<div class="item-tags scroll-x no-scrollbar mb6">
								<?php
								$color_list = joe\zibll_color_list();
								foreach ($item->categories as $key => $value) {
									echo '<a class="but ' . $color_list[$key] . '" title="查看此分类更多文章" href="' . joe\root_relative_link($value['url']) . '"><i class="fa fa-folder-open-o" aria-hidden="true"></i>' . $value['name'] . '</a>';
								}
								foreach ($item->tags as $key => $value) {
									echo '<a href="' . joe\root_relative_link($value['permalink']) . '" title="查看此标签更多文章" class="but"># ' . $value['name'] . '</a>';
								}
								?>
							</div>
							<div class="item-meta muted-2-color flex jsb ac">
								<item class="meta-author flex ac">
									<a href="<?= joe\root_relative_link($item->author->permalink) ?>"><span class="avatar-mini">
											<img onerror="Joe.avatarError(this)" alt="<?php $item->author() ?>的头像 - <?php $this->options->title() ?>" src="<?= joe\getAvatarLazyload(); ?>" data-src="<?php joe\getAvatarByMail($item->author->mail) ?>" class="avatar avatar-id-<?= $this->user->uid ?> ls-is-cached lazyload"></span></a>
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
				<?php
				}
				?>
			</ul>
		</div>
<?php
	}
}

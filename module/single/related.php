<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<?php if ($relatedPosts->have()) : ?>
	<div class="joe_detail__related">
		<div class="joe_detail__related-title title-theme">相关推荐</div>
		<div class="joe_detail__related-content relates relates-thumb">
			<div class="swiper swiper-scroll">
				<div class="swiper-wrapper">
					<?php while ($relatedPosts->next()) : ?>
						<div class="swiper-slide mr10">
							<a href="<?= joe\root_relative_link($relatedPosts->permalink); ?>" title="<?php $relatedPosts->title(); ?>">
								<div class="graphic hover-zoom-img mb10 style-3" style="padding-bottom: 70%!important;">
									<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" class="fit-cover lazyload" data-src="<?= joe\getThumbnails($relatedPosts)[0]; ?>" src="<?= joe\getLazyload(); ?>" alt="<?php $relatedPosts->title(); ?>">
									<div class="abs-center left-bottom graphic-text text-ellipsis"><?php $relatedPosts->title(); ?></div>
									<div class="abs-center left-bottom graphic-text">
										<div class="em09 opacity8"><?php $relatedPosts->title(); ?></div>
										<div class="px12 opacity8 mt6">
											<item><?= joe\dateWord($relatedPosts->dateWord) ?></item>
											<item class="pull-right">
												<svg class="icon svg" aria-hidden="true">
													<use xlink:href="#icon-view"></use>
												</svg>
												<?= joe\getViews($relatedPosts) ?>
											</item>
										</div>
									</div>
								</div>
							</a>
						</div>
					<?php endwhile; ?>
				</div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>
		</div>
	</div>
<?php endif; ?>
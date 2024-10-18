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
							<a href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>">
								<div class="graphic hover-zoom-img mb10 style-3" style="padding-bottom: 70%!important;">
									<img referrerpolicy="no-referrer" rel="noreferrer" class="fit-cover lazyload" data-src="<?= joe\getThumbnails($relatedPosts)[0]; ?>" src="<?php joe\getLazyload(); ?>" alt="<?php $relatedPosts->title(); ?>">
									<div class="abs-center left-bottom graphic-text text-ellipsis"><?php $relatedPosts->title(); ?></div>
									<div class="abs-center left-bottom graphic-text">
										<div class="em09 opacity8"><?php $relatedPosts->title(); ?></div>
										<div class="px12 opacity8 mt6">
											<item><?php joe\dateWord($relatedPosts->dateWord) ?></item>
											<item class="pull-right">
												<svg class="icon svg" aria-hidden="true">
													<use xlink:href="#icon-view"></use>
												</svg>
												<?php joe\getViews($relatedPosts) ?>
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
	<script>
		$('.swiper-scroll').each(function(e) {
			if ($(this).hasClass('swiper-container-initialized')) return;
			var option = {};
			var _this = $(this);
			var _eq = 'swiper-scroll-eq-' + e;
			var slideClass = _this.attr('data-slideClass') || false;
			slideClass && (option.slideClass = slideClass);

			if (!_this.attr('scroll-nogroup')) {
				var c_w = _this.width();
				var i_w = _this.find('.swiper-slide').outerWidth(true);
				var slidesPerGroup = ~~(c_w / i_w);
				option.slidesPerGroup = slidesPerGroup || 1;
			}

			option.autoplay = _this.attr('data-autoplay') ? {
					delay: ~~_this.attr('data-interval') || 4000,
					disableOnInteraction: false,
				} :
				false;
			option.loop = _this.attr('data-loop');
			option.slidesPerView = 'auto';
			option.mousewheel = {
				forceToAxis: true,
			};
			option.freeMode = true;
			option.freeModeSticky = true;

			option.navigation = {
				nextEl: '.swiper-scroll.' + _eq + ' .swiper-button-next',
				prevEl: '.swiper-scroll.' + _eq + ' .swiper-button-prev',
			};

			// console.log(option)

			_this.addClass(_eq).attr('swiper-scroll-index', e);
			new Swiper('.swiper-scroll.' + _eq, option);
		});
	</script>
<?php endif; ?>
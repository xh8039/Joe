<?php

/**
 * 轮播图模块
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$carousel = [];
$carousel_text = $this->options->JIndex_Carousel;
$video = false;
if ($carousel_text) {
	$carousel_arr = explode("\r\n", $carousel_text);
	if (count($carousel_arr) > 0) {
		for ($i = 0; $i < count($carousel_arr); $i++) {
			if (is_numeric($carousel_arr[$i])) {
				$this->widget('Widget_Contents_Post@' . $carousel_arr[$i], 'cid=' . $carousel_arr[$i])->to($item);
				$img = trim(joe\getThumbnails($item)[0]);
				$url = joe\root_relative_link($item->permalink);
				$title = $item->title;
			} else {
				$img = trim(explode("||", $carousel_arr[$i])[0] ?? '');
				$url = explode("||", $carousel_arr[$i])[1] ?? '';
				$title = explode("||", $carousel_arr[$i])[2] ?? '';
			}
			if (pathinfo($img, PATHINFO_EXTENSION) == 'mp4') $video = true;
			$carousel[] = array("img" => $img, "url" => trim($url), "title" => trim($title));
		};
	}
}
if ($video) echo '<style>html .joe_index__banner>.swiper-container .item, html .joe_index__banner>.swiper-container {height: auto;}</style>';
?>
<?php if (count($carousel) > 0) : ?>
	<div class="joe_index__banner mb25">
		<div class="swiper swiper-container">
			<div class="swiper-wrapper">
				<?php foreach ($carousel as $item) : ?>
					<div class="swiper-slide">
						<a class="item" href="<?= $item['url'] ?>" rel="nofollow">
							<?php
							if (pathinfo($item['img'], PATHINFO_EXTENSION) == 'mp4') {
							?>
								<video src="<?= $item['img'] ?>" alt="<?= $item['title'] ?>" preload="none" autoplay muted loop></video>
							<?php
							} else {
							?>
								<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="thumbnail lazyload" src="<?= joe\getLazyload() ?>" data-src="<?= $item['img'] ?>" alt="<?= $item['title'] ?>" />
							<?php
							}
							?>
							<div class="title"><?= $item['title'] ?></div>
							<svg width="18" height="18" class="icon" aria-hidden="true">
								<use xlink:href="#icon-paper-aircraft"></use>
							</svg>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="swiper-pagination"></div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
	</div>
<?php endif; ?>
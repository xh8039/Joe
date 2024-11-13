<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$post_ad = joe\optionMulti($this->options->JPost_Ad);
if (!empty($post_ad) && !joe\detectSpider() && $this->fields->global_advert != 'hide') {
?>
	<style>
		.joe_detail__article {
			padding-top: 5px;
		}
	</style>
	<div class="joe_post__ad">
		<?php
		foreach ($post_ad as $advert) : ?>
			<a class="joe_post__ad-link" <?= empty($advert[1]) ? '' : 'href="' . $advert[1] . '" target="_blank"' ?> rel="nofollow">
				<img referrerpolicy="no-referrer" rel="noreferrer" width="100%" class="image lazyload" src="<?= joe\theme_url('assets/images/thumb/thumbnail-sm.svg', false) ?>" data-src="<?= $advert[0] ?>" alt="<?= empty($advert[2]) ? '' : $advert[2] ?>" title="<?= empty($advert[2]) ? '' : $advert[2] ?>" />
				<?= empty($advert[2]) ? '' : '<span class="icon">' . $advert[2] . '</span>' ?>
			</a>
		<?php endforeach; ?>
	</div>
<?php
}

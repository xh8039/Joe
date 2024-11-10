<?php

/**
 * 广告模块
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$index_ad = joe\optionMulti($this->options->JIndex_Ad);
if (!empty($index_ad) && !joe\detectSpider()) {
?>
	<div class="title-theme" style="margin-bottom: 10px;"><?= empty($this->options->JIndex_Ad_Title) ? '推广宣传' : $this->options->JIndex_Ad_Title ?></div>
	<div class="joe_index__ad">
		<?php foreach ($index_ad as $advert) : ?>
			<a class="joe_index__ad-link" <?= empty($advert[1]) ? '' : 'href="' . $advert[1] . '" target="_blank"' ?> rel="nofollow">
				<img referrerpolicy="no-referrer" rel="noreferrer" width="100%" class="image lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="<?= $advert[0] ?>" alt="<?= empty($advert[2]) ? '' : $advert[2] ?>" title="<?= empty($advert[2]) ? '' : $advert[2] ?>" />
				<?= empty($advert[2]) ? '' : '<span class="icon">' . $advert[2] . '</span>' ?>
			</a>
		<?php endforeach; ?>
	</div>
<?php
}
?>
<?php

/**
 * 文章列表
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if ($this->options->IndexAjaxList == 'on') {
?>
	<div class="box-body notop nobottom">
		<div class="title-theme">最新发布</div>
	</div>
	<div class="joe_index__title">
		<div class="joe_index__title-content">
			<div class="splitters-this-r">排序</div>
			<ul class="joe_index__title-title">
				<li class="item" data-type="created">最新<span>文章</span></li>
				<li class="item" data-type="views">热门<span>文章</span></li>
				<li class="item" data-type="commentsNum">评论<span>最多</span></li>
				<li class="item" data-type="agree">点赞<span>最多</span></li>
				<li class="line"></li>
			</ul>
		</div>
		<?php
		$index_notice_text = $this->options->JIndex_Notice;
		$index_notice = null;
		if ($index_notice_text) {
			$index_notice_arr = explode("||", $index_notice_text);
			if (count($index_notice_arr) === 2) $index_notice = array("text" => trim($index_notice_arr[0]), "url" => trim($index_notice_arr[1]));
		}
		?>
		<?php if ($index_notice && !joe\detectSpider()) : ?>
			<div class="joe_index__title-notice">
				<svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
					<use xlink:href="#icon-joe-index-notice"></use>
				</svg>
				<a href="<?= $index_notice['url'] ?>" target="_blank" rel="external nofollow"><?= $index_notice['text'] ?></a>
			</div>
		<?php endif; ?>
	</div>
	<div class="joe_index__list" data-wow="<?php $this->options->JListAnimate() ?>">
		<ul class="joe_list"></ul>
		<ul class="joe_list__loading">
			<?php
			for ($i = 0; $i <= $this->options->pageSize; $i++) {
			?>
				<li class="item">
					<div class="thumbnail"></div>
					<div class="information">
						<div class="title"></div>
						<p></p>
						<p></p>
						<p></p>
					</div>
				</li>
			<?php
			}
			?>
		</ul>
	</div>
<?php
} else if ($this->have()) {
?>
	<div class="box-body notop">
		<div class="title-theme">最新发布</div>
	</div>
	<div class="joe_index__list">
		<ul class="joe_list">
			<?php $this->need('module/archive/list.php') ?>
		</ul>
	</div>
<?php
}

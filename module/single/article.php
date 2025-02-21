<?php

/**
 * 文章模块
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if (joe\isPc() && $this->fields->max_image_height) {
	echo '<style>.joe_detail__article img:not([class]) {max-height: ' . $this->fields->max_image_height . '}</style>';
}
?>
<article class="joe_detail__article" data-nav="posts">
	<?php
	echo (empty($this->options->JArticleHeaderHTML) || joe\detectSpider()) ? null : _parseContent($this, $this->options->JArticleHeaderHTML);
	if ($this->hidden) {
		$this->need('module/single/password.php');
	} else {
		if ($this->fields->video) $this->need('module/single/video.php');
		echo _parseContent($this);
	}
	?>
</article>
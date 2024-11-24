<?php

/**
 * 文章模块
 */

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<article class="joe_detail__article" data-nav="posts">
	<?php
	echo (empty($this->options->JArticleHeaderHTML) || joe\detectSpider()) ? null : _parseContent($this, $this->options->JArticleHeaderHTML);
	if ($this->password) {
		$this->need('module/single/password.php');
	} else {
		if ($this->fields->video) $this->need('module/single/video.php');
		echo _parseContent($this);
	}
	?>
</article>
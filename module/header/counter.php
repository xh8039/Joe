<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!-- 顶部浏览进度条开始 -->
<style>
	#HeaderCounter {
		position: absolute;
		bottom: -2px;
		width: 0;
		height: 2.5px;
		z-index: 10;
		background-image: var(--back-line-right);
		border-radius: var(--main-radius);
		transition: width 0.45s;
	}
</style>
<div id="HeaderCounter"></div>
<!-- 顶部浏览进度条结束 -->
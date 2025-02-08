<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
if (is_numeric($this->options->JOverdue) && floor((time() - ($this->modified)) / 86400) >= $this->options->JOverdue) {
?>
	<div class="joe_detail__overdue">
		<div class="joe_detail__overdue-wrapper">
			<div class="title">
				<svg class="icon" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20">
					<use xlink:href="#icon-joe-article-overdue-notice"></use>
				</svg>
				<span class="text">温馨提示：</span>
			</div>
			<div class="content">
				本文最后更新于<?php echo date('Y年m月d日', $this->modified); ?>，已超过<?= floor((time() - ($this->modified)) / 86400); ?>天没有更新，若内容或图片失效，请留言反馈。
			</div>
		</div>
	</div>
<?php
}

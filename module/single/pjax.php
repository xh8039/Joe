<?php
if (is_string($this->request->getHeader('x-pjax-selectors'))) {
	if (strpos($this->request->getHeader('x-pjax-selectors'), '#comment_module>.comment-list') !== false) {
		$this->need('module/single/comment.php');
		exit;
	}
	if ($this->request->getHeader('x-pjax-selectors') == '["joe-hide"]') {
		if (preg_match('/{hide[^}]*}([\s\S]*?){\/hide}/', $this->content, $content_match)) {
			$content = joe\markdown_hide($content_match[0], $this, $this->user->hasLogin());
			$content = _parseContent($this, $this->user->hasLogin(), $content);
		} else {
			$content = '';
		}
		$content = '<script type="text/javascript">$(".pay-box").remove();</script>' . $content . joe\commentsAntiSpam($this->respondId);
		$content = '<div class="joe-hide-show">' . $content . '</div>';
		echo $content;
		exit;
	}
}
if ($this->is('post') && (joe\detectSpider() || joe\spider_referer()) && isset($_GET['scroll'])) {
	$this->response->setStatus(301);
	$url = str_ireplace('scroll=' . $_GET['scroll'], '', $this->request->getRequestUrl());
	$url = trim($url, '?');
	$url = str_replace(['?&', '&&'], ['?', '&'], $url);
	$this->response->redirect($url, true);
	exit;
}

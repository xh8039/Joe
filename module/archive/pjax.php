<?php
if (is_string($this->request->getHeader('x-ajax-selectors'))) {
	$selectors = json_decode($this->request->getHeader('x-ajax-selectors'));
	ob_start();
	$this->archiveTitle(array('category' => '分类 %s 下的文章', 'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'), '', ' - ');
	$title = ob_get_contents();
	ob_end_clean();
	if ($this->_currentPage > 1) $title .= '第 ' . $this->_currentPage . ' 页 - ';
	$title .= $this->options->title;
	if (in_array('title', $selectors)) echo '<title>' . $title . '</title>';
	if (in_array('h1', $selectors)) echo '<h1 style="display:none">' . $title . '</h1>';
	if (in_array('.joe_main', $selectors)) $this->need('module/archive/main.php');
	if (!in_array('#Joe', $selectors)) exit;
}

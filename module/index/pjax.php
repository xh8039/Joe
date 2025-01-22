<?php
if (is_string($this->request->getHeader('x-ajax-selectors'))) {
	$selectors = json_decode($this->request->getHeader('x-ajax-selectors'));
	if (in_array('.joe_index__list', $selectors)) $this->need('module/index/list.php');
	if (in_array('.joe_pagination', $selectors)) $this->need('module/index/page.php');
	exit;
}

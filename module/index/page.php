<?php
'<a class="pag-jump page-numbers" href="javascript:;">
	<input autocomplete="off" max="147" current="1" base="https://www.juzia.cn/page/%#%" type="text" class="form-control jump-input" name="pag-go">
	<span class="hi de-sm mr6 jump-text">跳转</span>
	<i class="jump-icon fa fa-angle-double-right em12"></i>
</a>';
$this->pageNav('<i class="fa fa-angle-left em12"></i><span class="hide-sm ml6">上一页</span>', '<span class="hide-sm mr6">下一页</span><i class="fa fa-angle-right em12"></i>', 1, '...', [
	'wrapTag' => 'ul',
	'wrapClass' => 'joe_pagination',
	'itemTag' => 'li',
	'textTag' => 'a',
	'currentClass' => 'active',
	'prevClass' => 'prev',
	'nextClass' => 'next'
]);

<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JForceBrowser = new \Typecho\Widget\Helper\Form\Element\Select(
	'JForceBrowser',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启QQ、微信防红拦截',
	'介绍：开启后，如果在QQ里打开网站，则会提示跳转浏览器打开'
);
$JForceBrowser->setAttribute('class', 'joe_content joe_safe');
$form->addInput($JForceBrowser->multiMode());

<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}

$JPrevent = new Typecho_Widget_Helper_Form_Element_Select(
	'JPrevent',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启QQ、微信防红拦截',
	'介绍：开启后，如果在QQ里打开网站，则会提示跳转浏览器打开'
);
$JPrevent->setAttribute('class', 'joe_content joe_safe');
$form->addInput($JPrevent->multiMode());

$JTencentProtect = new Typecho_Widget_Helper_Form_Element_Select(
	'JTencentProtect',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启反蜘蛛爬虫非法扫描',
	'介绍：开启后，可以一定程度上屏蔽各种自动扫描蜘蛛爬虫机器人非法扫描站点'
);
$JTencentProtect->setAttribute('class', 'joe_content joe_safe');
$form->addInput($JTencentProtect->multiMode());
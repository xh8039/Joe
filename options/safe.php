<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JPrevent = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPrevent',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启QQ、微信防红拦截',
	'介绍：开启后，如果在QQ里打开网站，则会提示跳转浏览器打开'
);
$JPrevent->setAttribute('class', 'joe_content joe_safe');
$form->addInput($JPrevent->multiMode());

$JShieldScan = new \Typecho\Widget\Helper\Form\Element\Select(
	'JShieldScan',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'屏蔽扫描',
	'介绍：用于屏蔽垃圾机器人扫描、腾讯电脑管家网址安全检测、国外用户（不会屏蔽各大搜索引擎蜘蛛）'
);
$JShieldScan->setAttribute('class', 'joe_content joe_safe');
$form->addInput($JShieldScan->multiMode());
<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}

$JUser_Switch = new Typecho_Widget_Helper_Form_Element_Select(
	'JUser_Switch',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启主题自带登录注册功能',
	'介绍：开启后博客将享有更优美的登录注册页面<br>
		 注意：启用后不可使用其他登录插件 以免产生冲突<br>
		 参考自：<a href="http://www.gmit.vip/412.html" target="_blank">故梦登录插件</a>'
);
$JUser_Switch->setAttribute('class', 'joe_content joe_user');
$form->addInput($JUser_Switch->multiMode());

$JUser_Forget = new Typecho_Widget_Helper_Form_Element_Select(
	'JUser_Forget',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'找回密码',
	'介绍：未配置邮箱无法发送验证码 访问地址：<a target="_blank" href="' . Typecho_Common::url('user/forget', Helper::options()->index) . '">' . Typecho_Common::url('user/forget', Helper::options()->index) . '</a>'
);
$JUser_Forget->setAttribute('class', 'joe_content joe_user');
$form->addInput($JUser_Forget->multiMode());
<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JUser_Switch = new Typecho_Widget_Helper_Form_Element_Select(
	'JUser_Switch',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启主题自带登录注册功能',
	'介绍：开启后博客将享有更优美的登录注册页面<br>
	注意：启用后不可使用其他登录插件 以免产生冲突'
);
$JUser_Switch->setAttribute('class', 'joe_content joe_user');
$form->addInput($JUser_Switch->multiMode());

$JUser_Register_Group = new Typecho_Widget_Helper_Form_Element_Select(
	'JUser_Register_Group',
	array('subscriber' => '关注者', 'contributor' => '贡献者（默认）', 'editor' => '编辑', 'administrator' => '管理员'),
	'contributor',
	'用户注册默认等级',
	'介绍：需要开启主题自带的登录注册功能方可生效，Typecho默认用户注册后的权限是关注者<br>
	关注者：只能修改自己的档案信息<br>
	贡献者：可撰写和管理自己的文章，上传和管理自己的文件<br>
	编辑：可管理所有的文章、分类、评论、标签、文件，但是不能修改设置<br>
	管理员：和你的权限一样
	'
);
$JUser_Register_Group->setAttribute('class', 'joe_content joe_user');
$form->addInput($JUser_Register_Group->multiMode());

$JUser_Forget = new Typecho_Widget_Helper_Form_Element_Select(
	'JUser_Forget',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'找回密码',
	'介绍：未配置邮箱无法发送验证码 访问地址：<a target="_blank" href="' . Typecho_Common::url('user/forget', Helper::options()->index) . '">' . Typecho_Common::url('user/forget', Helper::options()->index) . '</a>'
);
$JUser_Forget->setAttribute('class', 'joe_content joe_user');
$form->addInput($JUser_Forget->multiMode());

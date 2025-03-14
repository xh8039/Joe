<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JFriends = new \Typecho\Widget\Helper\Form\Element\Hidden(
	'JFriends',
	NULL,
	NULL,
	'友情链接',
	'<b>主题设置处友情链接已停止使用，请转到 控制台->管理->友链 处管理您的友情链接<br />
	注意：您需要先增加友链链接页面（新增独立页面-右侧模板选择友链），该项才会生效</b>'
);
$JFriends->setAttribute('class', 'joe_content joe_friend');
$form->addInput($JFriends);

$JFriends_Submit = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFriends_Submit',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启友情链接在线提交',
	'注意：需正确配置邮箱 否则收不到申请'
);
$JFriends_Submit->setAttribute('class', 'joe_content joe_friend');
$form->addInput($JFriends_Submit->multiMode());

$JFriends_shuffle = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFriends_shuffle',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启友情链接随机排序',
	NULL
);
$JFriends_shuffle->setAttribute('class', 'joe_content joe_friend');
$form->addInput($JFriends_shuffle->multiMode());

$JFriendsSpiderHide = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFriendsSpiderHide',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否对蜘蛛引擎隐藏友情链接'
);
$JFriendsSpiderHide->setAttribute('class', 'joe_content joe_friend');
$form->addInput($JFriendsSpiderHide->multiMode());

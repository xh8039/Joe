<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JCommentStatus = new \Typecho\Widget\Helper\Form\Element\Select(
	'JCommentStatus',
	array(
		'on' => '开启（默认）',
		'off' => '关闭'
	),
	'3',
	'开启或关闭全站评论',
	'介绍：用于一键开启关闭所有页面的评论 <br>
		 注意：此处的权重优先级最高 <br>
		 若关闭此项而文章内开启评论，评论依旧为关闭状态'
);
$JCommentStatus->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JCommentStatus->multiMode());

$JcommentAutoRefresh = new \Typecho\Widget\Helper\Form\Element\Text(
	'JcommentAutoRefresh',
	NULL,
	NULL,
	'评论区内容自动刷新',
	'介绍：填写后，在用户打开本标签页的情况下，每隔指定秒数自动同步评论区内容，或许评论区可以实时聊天了哦<br>
	示例：5'
);
$JcommentAutoRefresh->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JcommentAutoRefresh->multiMode());

$JcommentLogin = new \Typecho\Widget\Helper\Form\Element\Select(
	'JcommentLogin',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'是否启用登录评论',
	'介绍：开启后，游客将无法进行评论，必须登录账号后才能评论'
);
$JcommentLogin->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JcommentLogin->multiMode());

$JcommentDraw = new \Typecho\Widget\Helper\Form\Element\Select(
	'JcommentDraw',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'是否启用评论画图模式',
	'介绍：开启后，可以进行画图评论'
);
$JcommentDraw->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JcommentDraw->multiMode());

$JSensitiveWords = new \Typecho\Widget\Helper\Form\Element\Text(
	'JSensitiveWords',
	NULL,
	'你妈死了 || 傻逼 || 操你妈 || 射你妈一脸',
	'评论敏感词（非必填）',
	'介绍：用于设置评论敏感词汇，如果用户评论包含这些词汇，则将会把评论置为审核状态，支持正则表达式 <br />
	例如：/http[s]?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?/i || /[a-z0-9]{0,62}\.[a-z]{0,10}/i || /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i || 你妈死了 || 你妈炸了 || 我是你爹 || 你妈坟头冒烟（多个使用 || 分隔开）'
);
$JSensitiveWords->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JSensitiveWords);

$JLimitOneChinese = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLimitOneChinese',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启评论至少包含一个中文',
	'介绍：开启后如果评论内容未包含一个中文，则禁止评论 <br />
		 其他：用于屏蔽国外机器人刷的全英文垃圾广告信息'
);
$JLimitOneChinese->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JLimitOneChinese->multiMode());

$JTextLimit = new \Typecho\Widget\Helper\Form\Element\Text(
	'JTextLimit',
	NULL,
	NULL,
	'限制用户评论最大字符',
	'介绍：如果用户评论的内容超出字符限制，则禁止评论 <br />
		 其他：请输入数字格式，不填写则不限制'
);
$JTextLimit->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JTextLimit->multiMode());

$JOwOAssetsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
	'JOwOAssetsUrl',
	null,
	null,
	'自定义表情包资源URL',
	'介绍：将本主题所需要的CSS、JS等资源文件使用某个远程网址来提供，以便节省服务器宽带 提升小型服务器加载速度<br>
	例如：//storage.bri6.cn/typecho/usr/themes/Joe@1.33'
);
$JOwOAssetsUrl->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JOwOAssetsUrl);

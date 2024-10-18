<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}

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

$Jcomment_draw = new \Typecho\Widget\Helper\Form\Element\Select(
	'Jcomment_draw',
	array(
		'on' => '开启（默认）',
		'off' => '关闭',
	),
	'on',
	'是否启用评论画图模式',
	'介绍：开启后，可以进行画图评论'
);
$Jcomment_draw->setAttribute('class', 'joe_content joe_comment');
$form->addInput($Jcomment_draw->multiMode());

$JSensitiveWords = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JSensitiveWords',
	NULL,
	'你妈死了 || 傻逼 || 操你妈 || 射你妈一脸',
	'评论敏感词（非必填）',
	'介绍：用于设置评论敏感词汇，如果用户评论包含这些词汇，则将会把评论置为审核状态 <br />
		 例如：你妈死了 || 你妈炸了 || 我是你爹 || 你妈坟头冒烟 （多个使用 || 分隔开）'
);
$JSensitiveWords->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JSensitiveWords);

$JLimitOneChinese = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLimitOneChinese',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启评论至少包含一个中文',
	'介绍：开启后如果评论内容未包含一个中文，则将会把评论置为审核状态 <br />
		 其他：用于屏蔽国外机器人刷的全英文垃圾广告信息'
);
$JLimitOneChinese->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JLimitOneChinese->multiMode());

$JTextLimit = new \Typecho\Widget\Helper\Form\Element\Text(
	'JTextLimit',
	NULL,
	NULL,
	'限制用户评论最大字符',
	'介绍：如果用户评论的内容超出字符限制，则将会把评论置为审核状态 <br />
		 其他：请输入数字格式，不填写则不限制'
);
$JTextLimit->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JTextLimit->multiMode());
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
	'介绍：用于设置评论敏感词汇，如果用户评论包含这些词汇，则将会把评论置为审核状态，支持正则表达式，多个使用 || 分隔开 <br />
	例如：/http[s]?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?/i || /[a-z0-9]{0,62}\.[a-z]{2,10}/i || /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i || 你妈死了 || 你妈炸了 || 我是你爹 || 你妈坟头冒烟'
);
$JSensitiveWords->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JSensitiveWords);

$JSensitiveWordApi = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JSensitiveWordApi',
	NULL,
	NULL,
	'评论敏感词检测API（非必填）',
	'介绍：用于检测评论敏感词汇，如果用户评论包含这些词汇，则将会把评论置为审核状态，需要接口返回JSON格式的内容 <br />
	说明：第一行填写API相关信息，第二行可填写自定义请求头，如果不需要自定义请求头则无需填写第二行 <br />
	格式（第一行）：API地址 || 请求时的评论内容字段 || 返回内容是否违规字段 || 请求失败消息字段（若无留空即可） <br />
	格式（第二行）：请求头:请求值 || 请求头:请求值 <br />
	例如：<br />
	https://v2.xxapi.cn/api/detect || text || is_prohibited || error_message <br>
	Content-Type: application/x-www-form-urlencoded || Authorization: ZDjTRbBlgADFDHRRJWOBFGA'
);
$JSensitiveWordApi->setAttribute('class', 'joe_content joe_comment');
$form->addInput($JSensitiveWordApi);

$JLimitOneChinese = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLimitOneChinese',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'是否开启评论至少包含一个中文',
	'介绍：开启后如果评论内容未包含一个中文，则禁止评论（管理员除外） <br />
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

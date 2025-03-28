<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

if (joe\email_config()) {
	$mail = think\facade\Db::name('users')->where('group', 'administrator')->value('mail');
	$email = $mail ? $mail :  \Helper::options()->JCommentMailAccount;
	$JEmailTestText = '<a href="javascript:Joe.mailTest();">点击给 ' . $email . ' 发一封测试邮件</a>';
	$JEmailTest = new \Typecho\Widget\Helper\Form\Element\Hidden('', NULL, NULL, $JEmailTestText);
	$JEmailTest->setAttribute('class', 'joe_content joe_message joe_mail_test');
	$form->addInput($JEmailTest);
}

/* 评论发信 */
$JCommentMail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JCommentMail',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启评论回复邮件通知',
	'介绍：开启后评论内容将会进行邮箱通知，评论有新的回复，也会向用户发送邮件<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！'
);
$JCommentMail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JCommentMail->multiMode());

$JFriendEmail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFriendEmail',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启友链申请邮件通知',
	'介绍：开启后友链申请将会进行邮箱通知<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！'
);
$JFriendEmail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JFriendEmail->multiMode());

$JFriendsStatusEmail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFriendsStatusEmail',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启友情链接状态修改通知',
	'介绍：友链通过审核、禁用、删除时，对方友链邮箱正确的情况下会通过对方的邮箱进行通知<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！'
);
$JFriendsStatusEmail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JFriendsStatusEmail->multiMode());

$JPaymentOrderToAdminEmail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPaymentOrderToAdminEmail',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启新订单管理员邮件通知',
	'介绍：用户支付订单后 向管理员发送邮件<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！'
);
$JPaymentOrderToAdminEmail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JPaymentOrderToAdminEmail->multiMode());

$JPaymentOrderEmail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPaymentOrderEmail',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启新订单用户邮件通知',
	'介绍：用户支付订单后 向用户发送邮件<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！'
);
$JPaymentOrderEmail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JPaymentOrderEmail->multiMode());

$JCommentMailFromName = new \Typecho\Widget\Helper\Form\Element\Text(
	'JCommentMailFromName',
	NULL,
	NULL,
	'发件人昵称（非必填）',
	'介绍：不填写则使用网站名称'
);
$JCommentMailFromName->setAttribute('class', 'joe_content joe_message');
$form->addInput($JCommentMailFromName->multiMode());

if (empty(\Helper::options()->JMailApi)) {
	$JCommentMailHost = new \Typecho\Widget\Helper\Form\Element\Text(
		'JCommentMailHost',
		NULL,
		NULL,
		'邮箱服务器地址',
		'例如：smtp.qq.com'
	);
	$JCommentMailHost->setAttribute('class', 'joe_content joe_message');
	$form->addInput($JCommentMailHost->multiMode());

	$JCommentSMTPSecure = new \Typecho\Widget\Helper\Form\Element\Select(
		'JCommentSMTPSecure',
		['ssl' => 'SSL（默认）', 'tsl' => 'TSL'],
		'ssl',
		'加密方式',
		'介绍：用于选择登录鉴权加密方式'
	);
	$JCommentSMTPSecure->setAttribute('class', 'joe_content joe_message');
	$form->addInput($JCommentSMTPSecure->multiMode());

	$JCommentMailPort = new \Typecho\Widget\Helper\Form\Element\Text(
		'JCommentMailPort',
		NULL,
		NULL,
		'邮箱服务器端口号',
		'例如：465'
	);
	$JCommentMailPort->setAttribute('class', 'joe_content joe_message');
	$form->addInput($JCommentMailPort->multiMode());

	$JCommentMailAccount = new \Typecho\Widget\Helper\Form\Element\Text(
		'JCommentMailAccount',
		NULL,
		NULL,
		'发件人邮箱',
		'例如：123456@qq.com'
	);
	$JCommentMailAccount->setAttribute('class', 'joe_content joe_message');
	$form->addInput($JCommentMailAccount->multiMode());

	$JCommentMailPassword = new \Typecho\Widget\Helper\Form\Element\Text(
		'JCommentMailPassword',
		NULL,
		NULL,
		'邮箱授权码',
		'介绍：这里填写的是邮箱生成的授权码 <br>
		获取方式（以QQ邮箱为例）：QQ邮箱 > 设置 > 账户 > IMAP/SMTP服务 > 开启 <br>
		图文教程：<a href="https://ks3-cn-beijing.ksyuncs.com/wpsmail-miui/qq/QQ%20aouth.html" target="_blank">简单图文教程</a>丨<a href="http://blog.yihang.info/archives/452.html" target="_blank">详细图文教程</a>'
	);
	$JCommentMailPassword->setAttribute('class', 'joe_content joe_message');
	$form->addInput($JCommentMailPassword->multiMode());

	$JMailApiOptions = '';
} else {
	$JMailApi = joe\optionMulti(\Helper::options()->JMailApi, '||', null, ['url', 'title', 'name', 'content', 'email', 'code', '200', 'message']);
	$JMailApiOptions = '<br>
	<span style="color:#409eff">
	现在的配置<br>
	邮箱对接地址：' . $JMailApi['url'] . '<br>
	发送标题字段：' . $JMailApi['title'] . '<br>
	发件昵称字段：' . $JMailApi['name'] . '<br>
	发送内容字段：' . $JMailApi['content'] . '<br>
	收件邮箱字段：' . $JMailApi['email'] . '<br>
	响应成功字段：' . $JMailApi['code'] . '<br>
	响应成功内容：' . $JMailApi['200'] . '<br>
	响应失败内容字段：' . $JMailApi['message'] . '</span>
	';
}

$JMailApi = new \Typecho\Widget\Helper\Form\Element\Text(
	'JMailApi',
	NULL,
	NULL,
	'邮箱API对接发件（非必填）',
	'介绍：使用API接口发送邮件，可防止源站IP地址泄露，配置后优先使用本功能<br>
	格式：对接地址 || 标题字段 || 发件昵称字段 || 发送内容字段 || 收件邮箱字段 || 响应成功字段 || 响应成功内容 || 响应失败内容字段<br>
	例如：http://api.bri6.cn/api/email/index.php || title || name || content || email || code || 200 || message' . $JMailApiOptions
);
$JMailApi->setAttribute('class', 'joe_content joe_message');
$form->addInput($JMailApi->multiMode());

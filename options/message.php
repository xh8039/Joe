<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$db = \Typecho_Db::get();
$authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', 1));
if (empty($authoInfo['mail'])) {
	$email = \Helper::options()->JCommentMailAccount;
} else {
	$email = $authoInfo['mail'];
}

if (joe\email_config()) {
	$JEmailTestText = '
	<form action="?Joe_backup" method="post">
		<button type="submit" name="type" value="mailtest">点击给 ' . $email . ' 发一封测试邮件</button>
	</form>
	';
	if (isset($_POST['mod']) && $_POST['mod'] == 'mailtest') {
		$send_email = joe\send_email('邮件发送测试', null, '<p>这是一封测试邮件！<p>来自：<a target="_blank" href="' . Helper::options()->siteUrl . '">' . Helper::options()->siteUrl . '</a><p>', $email);
		$JEmailTestText = ($send_email === true ? '邮件发送成功！' : $send_email);
	}
	$JEmailTest = new \Typecho\Widget\Helper\Form\Element\Hidden('', NULL, NULL, $JEmailTestText);
	$JEmailTest->setAttribute('class', 'joe_content joe_message');
	$form->addInput($JEmailTest);
}

/* 评论发信 */
$JCommentMail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JCommentMail',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启评论回复邮件通知',
	'介绍：开启后评论内容将会进行邮箱通知，评论有新的回复，也会向用户发送邮件<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！<br />
	其他：下方例子以QQ邮箱为例，推荐使用QQ邮箱'
);
$JCommentMail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JCommentMail->multiMode());

$JFriendEmail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFriendEmail',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启友链申请邮件通知',
	'介绍：开启后友链申请将会进行邮箱通知<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！<br />
	其他：下方例子以QQ邮箱为例，推荐使用QQ邮箱'
);
$JFriendEmail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JFriendEmail->multiMode());

$JPaymentOrderToAdminEmail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPaymentOrderToAdminEmail',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启新订单管理员邮件通知',
	'介绍：用户支付订单后 向管理员发送邮件<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！<br />
	其他：下方例子以QQ邮箱为例，推荐使用QQ邮箱'
);
$JPaymentOrderToAdminEmail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JPaymentOrderToAdminEmail->multiMode());

$JPaymentOrderEmail = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPaymentOrderEmail',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否开启新订单用户邮件通知',
	'介绍：用户支付订单后 向用户发送邮件<br />
	注意：此项需要您完整无错的填写下方的邮箱设置！！！<br />
	其他：下方例子以QQ邮箱为例，推荐使用QQ邮箱'
);
$JPaymentOrderEmail->setAttribute('class', 'joe_content joe_message');
$form->addInput($JPaymentOrderEmail->multiMode());

$JMailApi = new \Typecho\Widget\Helper\Form\Element\Text(
	'JMailApi',
	NULL,
	NULL,
	'邮箱API对接发件',
	'介绍：使用API接口发送邮件，配置后优先使用本功能<br>
	格式：对接地址 || 标题字段 || 副标题字段 || 内容字段 || 发送邮箱字段 || 响应成功字段 || 响应成功内容 || 响应失败内容字段<br>
	例如：http://api.bri6.cn/api/email/index.php || title || subtitle || content || email || code || 200 || message'
);
$JMailApi->setAttribute('class', 'joe_content joe_message');
$form->addInput($JMailApi->multiMode());

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
	array('ssl' => 'ssl（默认）', 'tsl' => 'tsl'),
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

$JCommentMailFromName = new \Typecho\Widget\Helper\Form\Element\Text(
	'JCommentMailFromName',
	NULL,
	Helper::options()->title,
	'发件人昵称',
	'例如：帅气的象拔蚌'
);
$JCommentMailFromName->setAttribute('class', 'joe_content joe_message');
$form->addInput($JCommentMailFromName->multiMode());

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
		 获取方式（以QQ邮箱为例）：<br>
		 QQ邮箱 > 设置 > 账户 > IMAP/SMTP服务 > 开启 <br>
		 其他：这个可以百度一下开启教程，有图文教程'
);
$JCommentMailPassword->setAttribute('class', 'joe_content joe_message');
$form->addInput($JCommentMailPassword->multiMode());

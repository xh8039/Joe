<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JYiPayApi = new \Typecho\Widget\Helper\Form\Element\Text(
	'JYiPayApi',
	NULL,
	'http://ypay.bri6.cn/',
	'易支付/码支付API接口网址',
	'介绍：用于文章付费阅读功能 <br />
	例如：http://mpay.bri6.cn/ <br />
	易航易支付：<a href="http://ypay.bri6.cn?key=RbgwH8wQ4Vkx" target="_blank">http://ypay.bri6.cn</a> <br />
	易航码支付：<a href="http://mpay.bri6.cn?key=RbgwH8wQ4Vkx" target="_blank">http://mpay.bri6.cn</a>'
);
$JYiPayApi->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JYiPayApi);

$JYiPayID = new \Typecho\Widget\Helper\Form\Element\Text(
	'JYiPayID',
	NULL,
	NULL,
	'易支付/码支付商户号',
	'介绍：用于文章付费阅读功能 <br />
	例如：1001 <br />
	<a href="http://ypay.bri6.cn?key=RbgwH8wQ4Vkx" target="_blank">注册易支付商户号</a>丨<a href="http://mpay.bri6.cn?key=RbgwH8wQ4Vkx" target="_blank">注册码支付商户号</a>
	'
);
$JYiPayID->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JYiPayID);

$JYiPayKey = new \Typecho\Widget\Helper\Form\Element\Text(
	'JYiPayKey',
	NULL,
	NULL,
	'易支付/码支付商户秘钥',
	'介绍：用于文章付费阅读功能 <br />
	例如：VxyC70n46yBQAxhJHu0HTDP6sFh2NJYj <br />
	<a href="http://ypay.bri6.cn?key=RbgwH8wQ4Vkx" target="_blank">注册易支付商户密钥</a>丨<a href="http://mpay.bri6.cn?key=RbgwH8wQ4Vkx" target="_blank">注册码支付商户密钥</a>
	'
);
$JYiPayKey->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JYiPayKey);

$JYiPayMapi = new \Typecho\Widget\Helper\Form\Element\Select(
	'JYiPayMapi',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'易支付/码支付MAPI模式',
	'介绍：免跳转直接扫码支付（需要接口以及接入方式支持，开启后如果出现请求失败，请关闭）'
);
$JYiPayMapi->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JYiPayMapi);

$JYiPayMapiUrl = new \Typecho\Widget\Helper\Form\Element\Text(
	'JYiPayMapiUrl',
	NULL,
	NULL,
	'自定义MAPI接口支付URL（非必填）',
	'介绍：如果您的易支付接口的API接口地址不是/mapi.php结尾，则请在此处填写完整的API接口支付地址<br />
	例如：https://pay.xxxxxxx.cn/qrcode.php、https://pay.xxxxxxx.cn/pay/apisubmit'
);
$JYiPayMapiUrl->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JYiPayMapiUrl);

$JWeChatPay = new \Typecho\Widget\Helper\Form\Element\Select(
	'JWeChatPay',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'微信支付功能'
);
$JWeChatPay->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JWeChatPay);

$JAlipayPay = new \Typecho\Widget\Helper\Form\Element\Select(
	'JAlipayPay',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'支付宝支付功能'
);
$JAlipayPay->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JAlipayPay);

$JQQPay = new \Typecho\Widget\Helper\Form\Element\Select(
	'JQQPay',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'QQ支付功能'
);
$JQQPay->setAttribute('class', 'joe_content joe_pay');
$form->addInput($JQQPay);

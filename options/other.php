<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JMaccmsAPI = new \Typecho\Widget\Helper\Form\Element\Text(
	'JMaccmsAPI',
	NULL,
	NULL,
	'苹果CMS开放API',
	'介绍：请填写苹果CMS V10开放API，用于视频页面使用<br />
		 例如：http://blog.bri6.cn/api.php/provide/vod/ <br />
		 如果您搭建了苹果cms网站，那么用你自己的即可，如果没有，请去网上找API <br />
		 '
);
$JMaccmsAPI->setAttribute('class', 'joe_content joe_other');
$form->addInput($JMaccmsAPI);

$JCustomPlayer = new \Typecho\Widget\Helper\Form\Element\Text(
	'JCustomPlayer',
	NULL,
	NULL,
	'自定义视频播放器（非必填）',
	'介绍：用于修改主题自带的默认播放器 <br />
		 例如：http://blog.bri6.cn/player/?url= <br />
		 注意：主题自带的播放器可以解析MP4、WEBM、OGG、H265、HEVC、M3U8、MPD、FLV、磁力链接（BT种子）格式的视频'
);
$JCustomPlayer->setAttribute('class', 'joe_content joe_other');
$form->addInput($JCustomPlayer);

$JYiPayApi = new \Typecho\Widget\Helper\Form\Element\Text(
	'JYiPayApi',
	NULL,
	'http://ypay.bri6.cn/',
	'易支付API接口网址',
	'介绍：用于文章付费阅读功能 <br />
	例如：http://ypay.bri6.cn/'
);
$JYiPayApi->setAttribute('class', 'joe_content joe_other');
$form->addInput($JYiPayApi);

$JYiPayID = new \Typecho\Widget\Helper\Form\Element\Text(
	'JYiPayID',
	NULL,
	NULL,
	'易支付商户号',
	'介绍：用于文章付费阅读功能 <br />
	例如：1001'
);
$JYiPayID->setAttribute('class', 'joe_content joe_other');
$form->addInput($JYiPayID);

$JYiPayKey = new \Typecho\Widget\Helper\Form\Element\Text(
	'JYiPayKey',
	NULL,
	NULL,
	'易支付商户秘钥',
	'介绍：用于文章付费阅读功能 <br />
	例如：VxyC70n46yBQAxhJHu0HTDP6sFh2NJYj'
);
$JYiPayKey->setAttribute('class', 'joe_content joe_other');
$form->addInput($JYiPayKey);

// $JYiPayMapi = new \Typecho\Widget\Helper\Form\Element\Select(
// 	'JYiPayMapi',
// 	['off' => '关闭', 'no' => '开启'],
// 	'off',
// 	'易支付MAPI模式',
// 	'介绍：免跳转直接扫码支付（需要接口以及接入方式支持，开启后如果出现请求失败，请关闭）'
// );
// $JYiPayMapi->setAttribute('class', 'joe_content joe_other');
// $form->addInput($JYiPayMapi);

$JWeChatPay = new \Typecho\Widget\Helper\Form\Element\Select(
	'JWeChatPay',
	['off' => '关闭（默认）', 'no' => '开启'],
	'off',
	'微信支付功能',
	'介绍：用于文章付费阅读功能'
);
$JWeChatPay->setAttribute('class', 'joe_content joe_other');
$form->addInput($JWeChatPay);

$JAlipayPay = new \Typecho\Widget\Helper\Form\Element\Select(
	'JAlipayPay',
	['off' => '关闭（默认）', 'no' => '开启'],
	'off',
	'支付宝支付功能',
	'介绍：用于文章付费阅读功能'
);
$JAlipayPay->setAttribute('class', 'joe_content joe_other');
$form->addInput($JAlipayPay);

$JQQPay = new \Typecho\Widget\Helper\Form\Element\Select(
	'JQQPay',
	['off' => '关闭（默认）', 'no' => '开启'],
	'off',
	'QQ支付功能',
	'介绍：用于文章付费阅读功能'
);
$JQQPay->setAttribute('class', 'joe_content joe_other');
$form->addInput($JQQPay);

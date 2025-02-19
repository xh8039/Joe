<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}

$statistics_config = joe\baidu_statistic_config();
$update_access_token_url = $statistics_config ? '操作：<a href="http://openapi.baidu.com/oauth/2.0/token?grant_type=refresh_token&refresh_token=' . urlencode($statistics_config['refresh_token']) . '&client_id=' . urlencode($statistics_config['client_id']) . '&client_secret=' . urlencode($statistics_config['client_secret']) . '">一键更新access_token</a>（手动更新后请手动在主题设置处填写已更新的token）<br>' : NULL;
$baidu_statistics = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'baidu_statistics',
	NULL,
	NULL,
	'百度统计配置（非必填）',
	'介绍：用于展示站点的百度统计信息<br>
	格式：第一行填写：access_token，二：refresh_token，三：API Key，四：Secret Key<br>
	' . $update_access_token_url . '
	百度统计API文档：<a target="_blank" href="https://tongji.baidu.com/api/manual/Chapter2/openapi.html">tongji.baidu.com/api/manual/Chapter2/openapi.html</a>'
);
$baidu_statistics->setAttribute('class', 'joe_content joe_statistic');
$form->addInput($baidu_statistics);

$JSiteMap = new \Typecho\Widget\Helper\Form\Element\Select(
	'JSiteMap',
	array(
		'off' => '关闭（默认）',
		'100' => '显示最新 100 条链接',
		'200' => '显示最新 200 条链接',
		'300' => '显示最新 300 条链接',
		'400' => '显示最新 400 条链接',
		'500' => '显示最新 500 条链接',
		'600' => '显示最新 600 条链接',
		'700' => '显示最新 700 条链接',
		'800' => '显示最新 800 条链接',
		'900' => '显示最新 900 条链接',
		'1000' => '显示最新 1000 条链接',
	),
	'off',
	'是否开启主题自带SiteMap功能',
	'介绍：开启后博客将享有SiteMap功能 <br />
		 其他：链接为博客最新实时链接 <br />
		 好处：无需手动生成，无需频繁提交，提交一次即可 <br />
		 开启后SiteMap访问地址：<br />
		 http(s)://域名/sitemap.xml （开启了伪静态）<br />
		 http(s)://域名/index.php/sitemap.xml （未开启伪静态）
		 '
);
$JSiteMap->setAttribute('class', 'joe_content joe_statistic');
$form->addInput($JSiteMap->multiMode());

$JBTPanel = new \Typecho\Widget\Helper\Form\Element\Text(
	'JBTPanel',
	NULL,
	NULL,
	'宝塔面板地址',
	'介绍：用于统计页面获取服务器状态使用 <br>
		 例如：http://192.168.1.245:8888/ <br>
		 注意：结尾需要带有一个 / 字符！<br>
		 该功能需要去宝塔面板开启开放API，并添加白名单才可使用'
);
$JBTPanel->setAttribute('class', 'joe_content joe_statistic');
$form->addInput($JBTPanel->multiMode());

$JBTKey = new \Typecho\Widget\Helper\Form\Element\Text(
	'JBTKey',
	NULL,
	NULL,
	'宝塔开放接口密钥',
	'介绍：用于统计页面获取服务器状态使用 <br>
		 例如：thVLXFtUCCNzBShBweKTPBmw8296q8R8 <br>
		 该功能需要去宝塔面板开启开放API，并添加白名单才可使用'
);
$JBTKey->setAttribute('class', 'joe_content joe_statistic');
$form->addInput($JBTKey->multiMode());
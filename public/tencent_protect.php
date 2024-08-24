<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}
(function () {
	/**
	 * @Description：反腾讯网址安全检测系统
	 * @Author：易航
	 * @Link：http://blog.bri6.cn
	 */
	if (Helper::options()->JTencentProtect == 'off') return;
	$spider = [
		'Baiduspider', // 百度搜索爬虫
		'360Spider', // 360搜索爬虫
		'YisouSpider',
		'Sogou web spider',
		'Sogou inst spider',
		'Googlebot/', // 谷歌搜索爬虫
		'bingbot/', // 必应搜索爬虫
		'Bytespider'
	];
	foreach ($spider as $value) {
		if (strpos($_SERVER['HTTP_USER_AGENT'], $value) !== false) return;
	}
	if (!empty($_SERVER['HTTP_REFERER'])) {
		$refere = [
			'.tr.com',
			'.wsd.com',
			'.oa.com',
			'.cm.com',
			'/membercomprehensive/',
			'www.internalrequests.org'
		];
		foreach ($refere as $value) {
			if (strpos($_SERVER['HTTP_REFERER'], $value) !== false) $_SESSION['txprotectblock'] = true;
		}
	}
	//HEADER特征屏蔽
	if (!isset($_SERVER['HTTP_ACCEPT']) || empty($_SERVER['HTTP_USER_AGENT']) || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "manager") !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'ozilla') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla') === false || strpos($_SERVER['HTTP_USER_AGENT'], "Windows NT 6.1") !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || strpos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1") !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || strpos($_SERVER['HTTP_ACCEPT'], "vnd.wap.wml") !== false && strpos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1") !== false || isset($_COOKIE['ASPSESSIONIDQASBQDRC']) || strpos($_SERVER['HTTP_USER_AGENT'], "Alibaba.Security.Heimdall") !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'wechatdevtools/') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'libcurl/') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'python') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Go-http-client') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'HeadlessChrome') !== false || @$_SESSION['txprotectblock'] == true) {
		exit('Click to continue!' . date('Y-m-d'));
	}
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Coolpad Y82-520') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || strpos($_SERVER['HTTP_USER_AGENT'], 'Mac OS X 10_12_4') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp/') === false && $_SERVER['HTTP_ACCEPT'] == '*/*' || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en') !== false && strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh') === false || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'en-') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'zh') === false || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS 9_1') !== false && $_SERVER['HTTP_CONNECTION'] == 'close') {
		exit('您当前浏览器不支持或操作系统语言设置非中文，无法访问本站！');
	}
})();
<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
(function () {
	/**
	 * @Description：反腾讯网址安全检测系统
	 * @Author：易航
	 * @Link：http://blog.bri6.cn
	 */
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
		return;
	}
	$_SERVER['HTTP_USER_AGENT'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	$_SERVER['HTTP_ACCEPT'] = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';
	$_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$spider = [
		'Baiduspider', // 百度搜索爬虫
		'360Spider', // 360搜索爬虫
		'YisouSpider', // 神马搜索爬虫
		'Sogou web spider', // 搜狗搜索蜘蛛
		'Sogou inst spider', // 搜狗搜索蜘蛛
		'Googlebot', // 谷歌搜索爬虫
		'bingbot', // 必应搜索爬虫
		'Bytespider' // 今日头条的 ByteSpider
	];
	foreach ($spider as $value) {
		if (stripos($_SERVER['HTTP_USER_AGENT'], $value) !== false) return;
	}
	$refere = ['.tr.com', '.wsd.com', '.oa.com', '.cm.com', '/membercomprehensive/', 'www.internalrequests.org'];
	foreach ($refere as $value) {
		if (stripos($_SERVER['HTTP_REFERER'], $value) !== false) $_SESSION['txprotectblock'] = true;
	}
	//HEADER特征屏蔽
	if (!isset($_SERVER['HTTP_ACCEPT']) || empty($_SERVER['HTTP_USER_AGENT']) || stripos(strtolower($_SERVER['HTTP_USER_AGENT']), "manager") !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'ozilla') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'Mozilla') === false || stripos($_SERVER['HTTP_USER_AGENT'], "Windows NT 6.1") !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || stripos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1") !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || stripos($_SERVER['HTTP_ACCEPT'], "vnd.wap.wml") !== false && stripos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1") !== false || isset($_COOKIE['ASPSESSIONIDQASBQDRC']) || stripos($_SERVER['HTTP_USER_AGENT'], "Alibaba.Security.Heimdall") !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'wechatdevtools/') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'libcurl/') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'python') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'Go-http-client') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'HeadlessChrome') !== false || @$_SESSION['txprotectblock'] == true) {
		exit('Click to continue!' . date('Y-m-d'));
	}
	$_SERVER['HTTP_ACCEPT_LANGUAGE'] = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
	if (stripos($_SERVER['HTTP_USER_AGENT'], 'Coolpad Y82-520') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || stripos($_SERVER['HTTP_USER_AGENT'], 'Mac OS X 10_12_4') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp/') === false && $_SERVER['HTTP_ACCEPT'] == '*/*' || stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*' || stripos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en') !== false && stripos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh') === false || stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'en-') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'zh') === false || stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS 9_1') !== false && $_SERVER['HTTP_CONNECTION'] == 'close') {
		exit('您当前浏览器不支持或操作系统语言设置非中文，无法访问本站！');
	}
})();
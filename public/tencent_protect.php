<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
(function () {

	// 检查是否为 AJAX 请求
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
		return;
	}

	// 定义要检查的爬虫和 Referer
	$spiders = ['Baiduspider', '360Spider', 'YisouSpider', 'Sogou web spider', 'Sogou inst spider', 'Googlebot', 'bingbot', 'Bytespider'];

	$referers = ['.tr.com', '.wsd.com', '.oa.com', '.cm.com', '/membercomprehensive/', 'www.internalrequests.org'];

	if (!isset($_SERVER['HTTP_USER_AGENT'])) $_SERVER['HTTP_USER_AGENT'] = '';
	if (!isset($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER'] = '';
	if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) $_SERVER['HTTP_ACCEPT_LANGUAGE'] = '';

	// 检查 User-Agent
	foreach ($spiders as $spider) {
		if (stripos($_SERVER['HTTP_USER_AGENT'], $spider) !== false) {
			return;
		}
	}

	// 检查 Referer
	foreach ($referers as $referer) {
		if (stripos($_SERVER['HTTP_REFERER'], $referer) !== false) {
			$_SESSION['txprotectblock'] = true;
			break;
		}
	}

	// 检查其他特征
	if (
		!isset($_SERVER['HTTP_ACCEPT']) ||
		empty($_SERVER['HTTP_USER_AGENT']) ||
		stripos(strtolower($_SERVER['HTTP_USER_AGENT']), "manager") !== false ||
		(stripos($_SERVER['HTTP_USER_AGENT'], 'ozilla') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'Mozilla') === false) ||
		(stripos($_SERVER['HTTP_USER_AGENT'], "Windows NT 6.1") !== false && $_SERVER['HTTP_ACCEPT'] == '*/*') ||
		(stripos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1") !== false && $_SERVER['HTTP_ACCEPT'] == '*/*') ||
		(stripos($_SERVER['HTTP_ACCEPT'], "vnd.wap.wml") !== false && stripos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1") !== false) ||
		isset($_COOKIE['ASPSESSIONIDQASBQDRC']) ||
		stripos($_SERVER['HTTP_USER_AGENT'], "Alibaba.Security.Heimdall") !== false ||
		stripos($_SERVER['HTTP_USER_AGENT'], 'wechatdevtools/') !== false ||
		stripos($_SERVER['HTTP_USER_AGENT'], 'libcurl/') !== false ||
		stripos($_SERVER['HTTP_USER_AGENT'], 'python') !== false ||
		stripos($_SERVER['HTTP_USER_AGENT'], 'Go-http-client') !== false ||
		stripos($_SERVER['HTTP_USER_AGENT'], 'HeadlessChrome') !== false ||
		isset($_SESSION['txprotectblock'])
	) {
		exit('Click to continue!' . date('Y-m-d'));
	}

	// 检查 Accept-Language
	if (
		(stripos($_SERVER['HTTP_USER_AGENT'], 'Coolpad Y82-520') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*') ||
		(stripos($_SERVER['HTTP_USER_AGENT'], 'Mac OS X 10_12_4') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*') ||
		(stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp/') === false && $_SERVER['HTTP_ACCEPT'] == '*/*') ||
		(stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false && $_SERVER['HTTP_ACCEPT'] == '*/*') ||
		(stripos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en') !== false && stripos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh') === false) ||
		(stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'en-') !== false && stripos($_SERVER['HTTP_USER_AGENT'], 'zh') === false) ||
		(stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS 9_1') !== false && $_SERVER['HTTP_CONNECTION'] == 'close')
	) {
		exit('您当前浏览器不支持或操作系统语言设置非中文，无法访问本站！');
	}
})();

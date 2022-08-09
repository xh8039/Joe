<?php

/*
Description：反腾讯网址安全检测系统
Author：易航
Link：http://blog.bri6.cn
*/
if (empty($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = '';
}
if(strpos($_SERVER['HTTP_REFERER'], '.tr.com')!==false||strpos($_SERVER['HTTP_REFERER'], '.wsd.com')!==false || strpos($_SERVER['HTTP_REFERER'], '.oa.com')!==false || strpos($_SERVER['HTTP_REFERER'], '.cm.com')!==false || strpos($_SERVER['HTTP_REFERER'], '/membercomprehensive/')!==false || strpos($_SERVER['HTTP_REFERER'], 'www.internalrequests.org')!==false){
	$txprotectblock=true;
}
if (empty($txprotectblock)) {
    $txprotectblock = false;
}
//HEADER特征屏蔽
if(!isset($_SERVER['HTTP_ACCEPT']) || empty($_SERVER['HTTP_USER_AGENT']) || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "manager")!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'ozilla')!==false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla')===false || strpos($_SERVER['HTTP_USER_AGENT'], "Windows NT 6.1")!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1")!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_ACCEPT'], "vnd.wap.wml")!==false && strpos($_SERVER['HTTP_USER_AGENT'], "Windows NT 5.1")!==false || isset($_COOKIE['ASPSESSIONIDQASBQDRC']) || strpos($_SERVER['HTTP_USER_AGENT'], "Alibaba.Security.Heimdall")!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'wechatdevtools/')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'libcurl/')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'python')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'Go-http-client')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'HeadlessChrome')!==false || $txprotectblock==true) {
	exit('Click to continue!'.$date);
}
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Coolpad Y82-520')!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_USER_AGENT'], 'Mac OS X 10_12_4')!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS')!==false && strpos($_SERVER['HTTP_USER_AGENT'], 'baiduboxapp/')===false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en')!==false && strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh')===false || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')!==false && strpos($_SERVER['HTTP_USER_AGENT'], 'en-')!==false && strpos($_SERVER['HTTP_USER_AGENT'], 'zh')===false || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone OS 9_1')!==false && $_SERVER['HTTP_CONNECTION']=='close') {
	exit('您当前浏览器不支持或操作系统语言设置非中文，无法访问本站！');
}
$user_agent = $_SERVER['HTTP_USER_AGENT'];

if ( ($this->options->JPrevent == 'on') && ((strpos($user_agent, 'MicroMessenger') !== false) || (strpos($user_agent, 'QQ/') !== false)) ) {
    // 我就不信这次腾讯会再给封了！！！
	$this->need('public/jump.php');
    exit;
}
<?php

/*
Description：反腾讯网址安全检测系统
Author：易航
Link：http://blog.bri6.cn
*/
if (empty($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = '';
}
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$txprotectblock = false;
if(strpos($_SERVER['HTTP_REFERER'], '.tr.com')!==false||strpos($_SERVER['HTTP_REFERER'], '.wsd.com')!==false || strpos($_SERVER['HTTP_REFERER'], '.oa.com')!==false || strpos($_SERVER['HTTP_REFERER'], '.cm.com')!==false || strpos($_SERVER['HTTP_REFERER'], '/membercomprehensive/')!==false || strpos($_SERVER['HTTP_REFERER'], 'www.internalrequests.org')!==false){
	$txprotectblock=true;
}
//HEADER特征屏蔽
if(!isset($_SERVER['HTTP_ACCEPT']) || empty($user_agent) || strpos(strtolower($user_agent), "manager")!==false || strpos($user_agent, 'ozilla')!==false && strpos($user_agent, 'Mozilla')===false || strpos($user_agent, "Windows NT 6.1")!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($user_agent, "Windows NT 5.1")!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_ACCEPT'], "vnd.wap.wml")!==false && strpos($user_agent, "Windows NT 5.1")!==false || isset($_COOKIE['ASPSESSIONIDQASBQDRC']) || strpos($user_agent, "Alibaba.Security.Heimdall")!==false || strpos($user_agent, 'wechatdevtools/')!==false || strpos($user_agent, 'libcurl/')!==false || strpos($user_agent, 'python')!==false || strpos($user_agent, 'Go-http-client')!==false || strpos($user_agent, 'HeadlessChrome')!==false || $txprotectblock==true) {
	exit('Click to continue!'.$date);
}
if(strpos($user_agent, 'Coolpad Y82-520')!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($user_agent, 'Mac OS X 10_12_4')!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($user_agent, 'iPhone OS')!==false && strpos($user_agent, 'baiduboxapp/')===false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($user_agent, 'Android')!==false && $_SERVER['HTTP_ACCEPT']=='*/*' || strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en')!==false && strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'zh')===false || strpos($user_agent, 'iPhone')!==false && strpos($user_agent, 'en-')!==false && strpos($user_agent, 'zh')===false || strpos($user_agent, 'iPhone OS 9_1')!==false && $_SERVER['HTTP_CONNECTION']=='close') {
	exit('您当前浏览器不支持或操作系统语言设置非中文，无法访问本站！');
}
if ( ($this->options->JPrevent == 'on') && ((strpos($user_agent, 'MicroMessenger') !== false) || (strpos($user_agent, 'QQ/') !== false)) ) {
    // 我就不信这次腾讯会再给封了！！！
	$this->need('public/jump.php');
    exit;
}
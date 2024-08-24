<?php

namespace joe;

if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}

/* 判断是否是手机 */

function isMobile()
{
	if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
		return true;
	if (isset($_SERVER['HTTP_VIA'])) {
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
		if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
			return true;
	}
	if (isset($_SERVER['HTTP_ACCEPT'])) {
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
	}
	return false;
}

/* 根据评论agent获取浏览器类型 */
function getAgentBrowser($agent)
{
	if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
		$outputer = 'Internet Explore';
	} else if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
		$outputer = 'FireFox';
	} else if (preg_match('/Maxthon([\d]*)\/([^\s]+)/i', $agent, $regs)) {
		$outputer = 'MicroSoft Edge';
	} else if (preg_match('#360([a-zA-Z0-9.]+)#i', $agent, $regs)) {
		$outputer = '360 Fast Browser';
	} else if (preg_match('/Edge([\d]*)\/([^\s]+)/i', $agent, $regs)) {
		$outputer = 'MicroSoft Edge';
	} else if (preg_match('/UC/i', $agent)) {
		$outputer = 'UC Browser';
	} else if (preg_match('/QQ/i', $agent, $regs) || preg_match('/QQ Browser\/([^\s]+)/i', $agent, $regs)) {
		$outputer = 'QQ Browser';
	} else if (preg_match('/UBrowser/i', $agent, $regs)) {
		$outputer = 'UC Browser';
	} else if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
		$outputer = 'Opera';
	} else if (preg_match('/Chrome([\d]*)\/([^\s]+)/i', $agent, $regs)) {
		$outputer = 'Google Chrome';
	} else if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
		$outputer = 'Safari';
	} else {
		$outputer = 'Google Chrome';
	}
	echo $outputer;
}

/* 根据评论agent获取设备类型 */
function getAgentOS($agent)
{
	$os = "Linux";
	if (preg_match('/win/i', $agent)) {
		if (preg_match('/nt 6.0/i', $agent)) {
			$os = 'Windows Vista';
		} else if (preg_match('/nt 6.1/i', $agent)) {
			$os = 'Windows 7';
		} else if (preg_match('/nt 6.2/i', $agent)) {
			$os = 'Windows 8';
		} else if (preg_match('/nt 6.3/i', $agent)) {
			$os = 'Windows 8.1';
		} else if (preg_match('/nt 5.1/i', $agent)) {
			$os = 'Windows XP';
		} else if (preg_match('/nt 10.0/i', $agent)) {
			$os = 'Windows 10';
		} else {
			$os = 'Windows X64';
		}
	} else if (preg_match('/android/i', $agent)) {
		if (preg_match('/android 9/i', $agent)) {
			$os = 'Android Pie';
		} else if (preg_match('/android 8/i', $agent)) {
			$os = 'Android Oreo';
		} else {
			$os = 'Android';
		}
	} else if (preg_match('/ubuntu/i', $agent)) {
		$os = 'Ubuntu';
	} else if (preg_match('/linux/i', $agent)) {
		$os = 'Linux';
	} else if (preg_match('/iPhone/i', $agent)) {
		$os = 'iPhone';
	} else if (preg_match('/mac/i', $agent)) {
		$os = 'MacOS';
	} else if (preg_match('/fusion/i', $agent)) {
		$os = 'Android';
	} else {
		$os = 'Linux';
	}
	echo $os;
}

/* 获取全局懒加载图 */
function getLazyload($type = true)
{
	$JLazyload = empty(\Helper::options()->JLazyload) ? theme_url('assets/images/lazyload.jpg') : \Helper::options()->JLazyload;
	if ($type) echo $JLazyload;
	else return $JLazyload;
}

/* 获取头像懒加载图 */
function getAvatarLazyload($type = true)
{
	$str = theme_url('assets/images/AvatarLazyload.png');
	if ($type) echo $str;
	else return $str;
}

/* 查询文章浏览量 */
function getViews($item, $type = true)
{
	$db = \Typecho_Db::get();
	$result = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $item->cid))['views'];
	if ($type) echo number_format($result);
	else return number_format($result);
}

/* 查询文章点赞量 */
function getAgree($item, $type = true)
{
	$db = \Typecho_Db::get();
	$result = $db->fetchRow($db->select('agree')->from('table.contents')->where('cid = ?', $item->cid))['agree'];
	if ($type) echo number_format($result);
	else return number_format($result);
}

/* 通过邮箱生成头像地址 */
function getAvatarByMail($mail)
{
	if (empty($mail)) {
		$db = \Typecho_Db::get();
		$authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', 1));
		$mail = $authoInfo['mail'];
	}
	$gravatarsUrl = \Helper::options()->JCustomAvatarSource ? \Helper::options()->JCustomAvatarSource : 'https://gravatar.helingqi.com/wavatar/';
	$mailLower = strtolower($mail);
	$md5MailLower = md5($mailLower);
	$qqMail = str_replace('@qq.com', '', $mailLower);
	if (strstr($mailLower, "qq.com") && is_numeric($qqMail) && strlen($qqMail) < 11 && strlen($qqMail) > 4) {
		echo 'https://thirdqq.qlogo.cn/g?b=qq&nk=' . $qqMail . '&s=640';
	} else {
		echo $gravatarsUrl . $md5MailLower . '?d=mm';
	}
};

/* 获取侧边栏随机一言 */
function getAsideAuthorMotto()
{
	$JMottoRandom = explode("\r\n", \Helper::options()->JAside_Author_Motto);
	echo $JMottoRandom[array_rand($JMottoRandom, 1)];
}

/* 获取文章摘要 */
function getAbstract($item, $type = true)
{
	$abstract = "";
	if ($item->password) {
		$abstract = "加密文章，请前往内页查看详情";
	} else {
		if ($item->fields->abstract) {
			$abstract = $item->fields->abstract;
		} else {
			$abstract = strip_tags($item->excerpt);
			if (strpos($abstract, '{hide') !== false) {
				$abstract = preg_replace('/{hide[^}]*}([\s\S]*?){\/hide}/', '隐藏内容，请前往内页查看详情', $abstract);
			}
		}
	}
	if ($abstract === '') {
		$abstract = "暂无简介";
	} else {
		$abstract = markdown_filter($abstract);
	}
	if ($type) echo $abstract;
	else return $abstract;
}

/* 获取列表缩略图 */
function getThumbnails($item)
{
	$result = [];
	$pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
	$patternMD = '/\!\[.*?\]\((http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
	$patternMDfoot = '/\[.*?\]:\s*(http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
	/* 如果填写了自定义缩略图，则优先显示填写的缩略图 */
	if ($item->fields->thumb) {
		$fields_thumb_arr = explode("\r\n", $item->fields->thumb);
		foreach ($fields_thumb_arr as $list) $result[] = $list;
	}
	/* 如果匹配到正则，则继续补充匹配到的图片 */
	if (preg_match_all($pattern, $item->content, $thumbUrl)) {
		foreach ($thumbUrl[1] as $list) $result[] = $list;
	}
	if (preg_match_all($patternMD, $item->content, $thumbUrl)) {
		foreach ($thumbUrl[1] as $list) $result[] = $list;
	}
	if (preg_match_all($patternMDfoot, $item->content, $thumbUrl)) {
		foreach ($thumbUrl[1] as $list) $result[] = $list;
	}
	/* 如果上面的数量不足3个，则直接补充3个随即图进去 */
	if (sizeof($result) < 3) {
		$custom_thumbnail = \Helper::options()->JThumbnail;
		/* 将for循环放里面，减少一次if判断 */
		if ($custom_thumbnail) {
			$custom_thumbnail_arr = explode("\r\n", $custom_thumbnail);
			for ($i = 0; $i < 3; $i++) {
				$result[] = $custom_thumbnail_arr[array_rand($custom_thumbnail_arr, 1)] . "?key=" . mt_rand(0, 1000000);
			}
		} else {
			for ($i = 0; $i < 3; $i++) {
				// 生成一个在 1 到 42 之间的随机数
				$randomNumber = rand(1, 42);
				// 将随机数格式化为两位数
				$formattedNumber = sprintf('%02d', $randomNumber);
				$result[] = theme_url('assets/images/thumb/' . $formattedNumber . '.jpg');
			}
		}
	}
	return $result;
}

/* 获取父级评论 */
function getParentReply($parent)
{
	if ($parent !== "0") {
		$db = \Typecho_Db::get();
		$commentInfo = $db->fetchRow($db->select('author')->from('table.comments')->where('coid = ?', $parent));
		if (empty($commentInfo['author'])) return;
		echo '<div class="parent"><span style="vertical-align: 1px;">@</span> ' . $commentInfo['author'] . '</div>';
	}
}

/* 获取侧边栏作者随机文章 */
function getAsideAuthorNav()
{
	if (\Helper::options()->JAside_Author_Nav && \Helper::options()->JAside_Author_Nav !== "off") {
		$limit = \Helper::options()->JAside_Author_Nav;
		$db = \Typecho_Db::get();
		$prefix = $db->getPrefix();
		$sql = "SELECT * FROM `{$prefix}contents` WHERE cid >= (SELECT floor( RAND() * ((SELECT MAX(cid) FROM `{$prefix}contents`)-(SELECT MIN(cid) FROM `{$prefix}contents`)) + (SELECT MIN(cid) FROM `{$prefix}contents`))) and type='post' and status='publish' and (password is NULL or password='') ORDER BY cid LIMIT $limit";
		$result = $db->query($sql);
		if ($result instanceof \Traversable) {
			foreach ($result as $item) {
				$item = \Typecho_Widget::widget('Widget_Abstract_Contents')->push($item);
				$title = htmlspecialchars($item['title']);
				$permalink = $item['permalink'];
				echo "<li class='item'><a class='link' href='{$permalink}' title='{$title}'>{$title}</a><svg class='icon' viewBox='0 0 1024 1024' xmlns='http://www.w3.org/2000/svg' width='16' height='16'><path d='M448.12 320.331a30.118 30.118 0 0 1-42.616-42.586L552.568 130.68a213.685 213.685 0 0 1 302.2 0l38.552 38.551a213.685 213.685 0 0 1 0 302.2L746.255 618.497a30.118 30.118 0 0 1-42.586-42.616l147.034-147.035a153.45 153.45 0 0 0 0-217.028l-38.55-38.55a153.45 153.45 0 0 0-216.998 0L448.12 320.33zM575.88 703.67a30.118 30.118 0 0 1 42.616 42.586L471.432 893.32a213.685 213.685 0 0 1-302.2 0l-38.552-38.551a213.685 213.685 0 0 1 0-302.2l147.065-147.065a30.118 30.118 0 0 1 42.586 42.616L173.297 595.125a153.45 153.45 0 0 0 0 217.027l38.55 38.551a153.45 153.45 0 0 0 216.998 0L575.88 703.64zm-234.256-63.88L639.79 341.624a30.118 30.118 0 0 1 42.587 42.587L384.21 682.376a30.118 30.118 0 0 1-42.587-42.587z'/></svg></li>";
			}
		}
	}
}

/* 判断敏感词是否在字符串内 */
function checkSensitiveWords($words_str, $str)
{
	$words = explode("||", $words_str);
	if (empty($words)) {
		return false;
	}
	foreach ($words as $word) {
		if (false !== strpos($str, trim($word))) {
			return true;
		}
	}
	return false;
}

function theme_url($path)
{
	if (empty(\Helper::options()->JStaticAssetsUrl)) {
		$path = url_builder($path, ['version' => JOE_VERSION]);
		return \Helper::options()->themeUrl . '/' . $path;
	}
	$url = \Helper::options()->JStaticAssetsUrl . '/' . $path;
	$url = url_builder($url, ['version' => JOE_VERSION]);
	return $url;
}

function url_builder($url, array $param)
{
	$param = http_build_query($param);
	$url = strstr($url, '?') ? trim($url, '&') . '&' . $param : $url . '?' . $param;
	return $url;
}

/** 过滤Markdown语法代码 */
function markdown_filter($text)
{
	$text = preg_replace('/{.*?}/', '', $text);
	return $text;
}

function user_login($uid, $expire = 30243600)
{
	$db = \Typecho_Db::get();
	\Typecho_Widget::widget('Widget_User')->simpleLogin($uid);
	$authCode = function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : sha1(\Typecho_Common::randString(20));
	\Typecho_Cookie::set('__typecho_uid', $uid, time() + $expire);
	\Typecho_Cookie::set('__typecho_authCode', \Typecho_Common::hash($authCode), time() + $expire);
	//更新最后登录时间以及验证码
	$db->query($db->update('table.users')->expression('logged', 'activated')->rows(array('authCode' => $authCode))->where('uid = ?', $uid));
}

function user_url($action)
{
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
	$url = urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
	switch ($action) {
		case 'register':
			$url = \Typecho_Common::url('user/register', \Helper::options()->index) . '?from=' . $url;
			break;
		case 'login':
			$url = \Typecho_Common::url('user/login', \Helper::options()->index) . '?from=' . $url;
			break;
		case 'forget':
			$url = \Typecho_Common::url('user/forget', \Helper::options()->index) . '?from=' . $url;
			break;
	}
	return $url;
}


/** 获取百度统计配置 */
function baidu_statistic_config()
{
	$statistics_config = \Helper::options()->baidu_statistics ? explode(PHP_EOL, \Helper::options()->baidu_statistics) : null;
	if (is_array($statistics_config) && count($statistics_config) == 4) {
		return [
			'access_token' => trim($statistics_config[0]),
			'refresh_token' => trim($statistics_config[1]),
			'client_id' => trim($statistics_config[2]),
			'client_secret' => trim($statistics_config[3])
		];
	}
	return null;
}

/** 检测主题设置是否配置邮箱 */
function email_config()
{
	if (
		empty(\Helper::options()->JCommentMailHost) ||
		empty(\Helper::options()->JCommentMailPort) ||
		empty(\Helper::options()->JCommentMailAccount) ||
		empty(\Helper::options()->JCommentMailFromName) ||
		empty(\Helper::options()->JCommentSMTPSecure) ||
		empty(\Helper::options()->JCommentMailPassword)
	) {
		return false;
	} else {
		return true;
	}
}

/** 发送电子邮件 */
function send_email($title, $subtitle, $content, $email = '')
{
	if (!email_config()) {
		return false;
	}
	if (empty($email)) {
		$db = \Typecho_Db::get();
		$authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', 1));
		if (empty($authoInfo['mail'])) {
			$email = \Helper::options()->JCommentMailAccount;
		} else {
			$email = $authoInfo['mail'];
		}
	}
	$mail = new \PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->CharSet = 'UTF-8';
	$mail->SMTPSecure = \Helper::options()->JCommentSMTPSecure;
	$mail->Host = \Helper::options()->JCommentMailHost;
	$mail->Port = \Helper::options()->JCommentMailPort;
	$mail->FromName = \Helper::options()->JCommentMailFromName;
	$mail->Username = \Helper::options()->JCommentMailAccount;
	$mail->From = \Helper::options()->JCommentMailAccount;
	$mail->Password = \Helper::options()->JCommentMailPassword;
	$mail->isHTML(true);
	$html = '<style>.Joe{width:550px;margin:0 auto;border-radius:8px;overflow:hidden;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.Joe_title{color:#fff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400% 400%;background-position:50% 100%;padding:15px;font-size:15px;line-height:1.5}</style><div class="Joe"><div class="Joe_title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;"><div style="margin-bottom: 20px;line-height: 1.5;">{subtitle}</div><div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div>';
	$mail->Body = strtr(
		$html,
		array(
			"{title}" => $title . ' - ' . \Helper::options()->title,
			"{subtitle}" => $subtitle,
			"{content}" => $content,
		)
	);
	$mail->addAddress($email);
	$mail->Subject = $title . ' - ' . \Helper::options()->title;
	if ($mail->send()) {
		return 'success';
	} else {
		return $mail->ErrorInfo;
	}
}

/** 对部分背景壁纸效果的适配CSS代码 */
function background_adaptive()
{
	return '
		html .joe_footer .joe_container>.item,
	html .joe_footer .joe_container a,
	html .joe_bread__bread .item,
	html .joe_bread__bread .item .link,
	html .text-muted,
	html .joe_index__title-title>.item,
	html .joe_index__title-notice>a {
	color: var(--classC);
	}

	html .joe_bread__bread>.item>.icon {
	fill: var(--classC);
	}

	html .text-muted>a {
	color: var(--classD);
	}

	html .joe_action_item {
	background: var(--back-trn-85);
	}
	';
}

/**
 * 输出CDN链接
 *
 * @param string|null $path 子路径
 * @return string
 */
function cdn($path)
{
	$cdnpublic = empty(\Helper::options()->JCdnUrl) ? '//cdn.bootcdn.net/ajax/libs/' : \Helper::options()->JCdnUrl;
	$lastChar = substr($cdnpublic, -1);
	if ($lastChar != '/') {
		$cdnpublic = $cdnpublic . '/';
	}
	$url = $cdnpublic . $path;
	return $url;
}
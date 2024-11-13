<?php

namespace joe;

use \Helper;
use \Typecho_Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

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

function isPc()
{
	return !isMobile();
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
	$JLazyload = empty(\Helper::options()->JLazyload) ? theme_url('assets/images/lazyload.gif', false) : \Helper::options()->JLazyload;
	if ($type) echo $JLazyload;
	else return $JLazyload;
}

/**
 * 获取头像懒加载图
 */
function getAvatarLazyload($type = true)
{
	$str = theme_url('assets/images/avatar-default.png');
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
function getAvatarByMail($mail, $type = true)
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
	if (strstr($mailLower, "qq.com") && is_numeric($qqMail) && strlen($qqMail) < 13 && strlen($qqMail) > 4) {
		'https://q4.qlogo.cn/headimg_dl?dst_uin=2136118039&spec=640';
		$result =  'https://thirdqq.qlogo.cn/g?b=qq&nk=' . $qqMail . '&s=640';
	} else {
		$result = $gravatarsUrl . $md5MailLower . '?d=mm';
	}
	if ($type) echo $result;
	return $result;
};

/* 获取侧边栏随机一言 */
function getAsideAuthorMotto()
{
	$Motto = isset(\Helper::options()->JAside_Author_Motto) ? \Helper::options()->JAside_Author_Motto : '';
	$JMottoRandom = explode("\r\n", $Motto);
	echo $JMottoRandom[array_rand($JMottoRandom, 1)];
}

/* 获取文章摘要 */
function getAbstract($item, $type = true)
{
	if ($item->fields->abstract) {
		$abstract = $item->fields->abstract;
	} else {
		$abstract = post_description($item, null);
	}
	if (empty($abstract)) {
		$abstract = "暂无简介";
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
	if (!is_string($item->content)) $item->content = '';
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

function theme_url($path, $param = ['version' => JOE_VERSION])
{
	$themeUrl = \Helper::options()->themeUrl;
	$theme_url_parse = parse_url($themeUrl);
	$theme_url_domain = $theme_url_parse['host'] . ($theme_url_parse['port'] ?? '');
	if ($theme_url_domain != $_SERVER['HTTP_HOST']) {
		$themeUrl = str_replace($theme_url_domain, $_SERVER['HTTP_HOST'], $themeUrl);
	}
	$themeUrl = preg_replace("/^https?:\/\//", '//', $themeUrl);
	$url_root = empty(\Helper::options()->JStaticAssetsUrl) ? $themeUrl : \Helper::options()->JStaticAssetsUrl;
	$lastChar = substr($url_root, -1);
	if ($lastChar != '/') $url_root = $url_root . '/';
	$url = $url_root . $path;
	return url_builder($url, $param);
}

function url_builder($url, $param = null)
{
	if (is_array($param) && !empty($param)) {
		$param = http_build_query($param);
		$url = strstr($url, '?') ? (trim($url, '&') . '&' . $param) : ($url . '?' . $param);
	}
	return $url;
}

/** 过滤Markdown语法代码 */
function markdown_filter($content): string
{
	if (!is_string($content)) return '';

	// 跑马灯
	$content = str_replace('{lamp/}', ' ', $content);

	// 任务
	$content = str_replace('{ }', ' ', $content);
	$content = str_replace('{x}', ' ', $content);

	// 网易云音乐
	$content = preg_replace('/{music-list([^}]*)\/}/', ' ', $content);
	$content = preg_replace('/{music([^}]*)\/}/', ' ', $content);

	// 音乐标签
	$content = preg_replace('/\{mp3 name\="(.*?)" artist\="(.*?)".*?\/\}/S', '$1 - $2', $content);

	// 哔哩哔哩视频
	$content = preg_replace('/{bilibili([^}]*)\/}/', ' ', $content);

	// 视频
	$content = preg_replace('/{dplayer([^}]*)\/}/', ' ', $content);

	// 居中标题标签
	$content = preg_replace('/\{mtitle title\="(.*?)"\/\}/', '$1', $content);

	// 多彩按钮
	$content = preg_replace('/\{abtn.*?content\="(.*?)"\/\}/', '$1', $content);

	// 云盘下载
	$content = preg_replace('/\{cloud title\="(.*?)" type\="\w+" url\="(.*?)" password\="(.*?)"\/\}/', '$1 下载地址：$2 提取码：$3', $content);

	// 便条按钮
	$content = preg_replace('/\{anote.*?content\="(.*?)"\/\}/', '$1', $content);

	// 彩色虚线
	$content = preg_replace('/{dotted([^}]*)\/}/', ' ', $content);

	// 消息提示
	$content = preg_replace('/\{message type="\w+" content\="(.*?)"\/\}/', '$1', $content);

	// 进度条
	$content = preg_replace('/\{progress percentage="(\d+)" color\="#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})"\/\}/', '进度$1%', $content);

	// 隐藏内容
	$content = preg_replace('/{hide[^}]*}([\s\S]*?){\/hide}/', '隐藏内容，请前往内页查看详情', $content);

	// 以下为双标签

	// 默认卡片
	$content = preg_replace('/\{card\-default label\="(.*?)" width\="\d+"\}([\s\S]*?)\{\/card\-default\}/', '$1 - $2', $content);

	// 标注
	$content = preg_replace('/\{callout color\="#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})"\}([\s\S]*?)\{\/callout\}/', '$1', $content);

	// 警告提示
	$content = preg_replace('/\{alert type\="\w+"\}([\s\S]*?)\{\/alert\}/', '$1', $content);

	// 描述卡片
	$content = preg_replace('/\{card\-describe title\="(.*?)"\}([\s\S]*?)\{\/card\-describe\}/', '$1 - $2', $content);

	// 标签页
	$content = preg_replace('/\{tabs\}([\s\S]*?)\{\/tabs\}/', '$1', $content);
	$content = preg_replace('/\{tabs\-pane label\="(.*?)"}([\s\S]*?)\{\/tabs\-pane\}/', '$1 $2', $content);

	// 卡片列表
	$content = preg_replace('/\{card\-list\}([\s\S]*?)\{\/card\-list\}/', '$1', $content);
	$content = preg_replace('/\{card\-list\-item\}([\s\S]*?)\{\/card\-list\-item\}/', '$1', $content);

	// 时间轴
	$content = preg_replace('/\{timeline\}([\s\S]*?)\{\/timeline\}/', '$1', $content);
	$content = preg_replace('/\{timeline\-item color\="#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})"\}([\s\S]*?)\{\/timeline\-item\}/', '$1', $content);

	// 折叠面板
	$content = preg_replace('/\{collapse\}([\s\S]*?)\{\/collapse\}/', '$1', $content);
	$content = preg_replace('/\{collapse\-item label\="(.*?)"\s?[open]*\}([\s\S]*?)\{\/collapse\-item\}/', '$1 - $2', $content);

	// 宫格
	$content = preg_replace('/\{gird column\="\d+" gap\="\d+"\}([\s\S]*?)\{\/gird\}/', '$1', $content);
	$content = preg_replace('/\{gird\-item\}([\s\S]*?)\{\/gird\-item\}/', '$1', $content);

	// 复制
	$content = preg_replace('/\{copy showText\="(.*?)" copyText\="(.*?)"\/\}/', '$1 $2', $content);

	// 其他开合标签
	// $content = preg_replace('/\{[\w,\-]+.*?\}(.*?)\{\/[\w,\-]+\}/S', '$1', $content);

	// 标签中有content值
	// $content = preg_replace('/\{.*?content\="(.*?)"\/\}/S', '$1', $content);

	// 剩下没有文本的单标签
	// $content = preg_replace('/\{.*?\/\}/S', ' ', $content);

	$content = trim($content);
	return $content;
}

/**
 * 对文章的简短纯文本描述
 *
 * @return string|null
 */
function post_description($item, ?int $length = 150): ?string
{
	if ($item->password) {
		$content = "加密文章，请前往内页查看详情";
	} else {
		$content = $item->content;
		$content = html_tags_filter($content, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p']);
		$content = str_replace(['<br>', '<li>', '</li>'], [' ', '<li> ', '</li> '], $content);
		$content = preg_replace('/\<img src\=".*?" alt\="(.*?)" title\=".*?"\>/', '$1图片', $content);
		$content = str_replace(["\n", '"'], [' ', '&quot;'], strip_tags(markdown_filter($content)));
		$content = preg_replace('/\s+/s', ' ', $content);
		$content = empty($content) ? $item->title : $content;
		if (is_numeric($length)) {
			return trim(\Typecho\Common::subStr($content, 0, $length, '...'));
		}
		return trim($content);
	}
}

function html_tags_filter(string $content, array $tags): string
{
	foreach ($tags as $value) {
		$content = preg_replace('/\<' . $value . '\>(.*?)\<\/' . $value . '\>/i', '$1 ', $content);
	}
	return $content;
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

function user_url($action, $from = true)
{
	if ($from === true) {
		if (!empty($_GET['from'])) {
			$url = '?from=' . urlencode($_GET['from']);
		} else {
			$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
			$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
			$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
			$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
			$url = '?from=' . urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
		}
	} else if (is_string($from)) {
		$url = '?from=' . urlencode($from);
	} else {
		$url = '';
	}
	switch ($action) {
		case 'register':
			if (\Helper::options()->JUser_Switch == 'on') {
				$url = \Typecho_Common::url('user/register', \Helper::options()->index) . $url;
			} else {
				$url = \Helper::options()->adminUrl . 'register.php';
			}
			break;
		case 'login':
			if (\Helper::options()->JUser_Switch == 'on') {
				$url = \Typecho_Common::url('user/login', \Helper::options()->index) . $url;
			} else {
				$url = \Helper::options()->adminUrl . 'login.php';
			}
			break;
		case 'forget':
			$url = \Typecho_Common::url('user/forget', \Helper::options()->index) . $url;
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
	if (!class_exists('\PHPMailer', false)) {
		require_once JOE_ROOT . 'public/phpmailer.php';
		require_once JOE_ROOT . 'public/smtp.php';
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
	$html = '<!DOCTYPE html><html lang="zh-cn"><head><meta charset="UTF-8"><meta name="viewport"content="width=device-width, initial-scale=1.0"></head><body><style>.container{width:95%;margin:0 auto;border-radius:8px;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.title{color:#fff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400%400%;background-position:50%100%;padding:15px;font-size:15px;line-height:1.5}</style><div class="container"><div class="title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;"><div style="margin-bottom: 20px;line-height: 1.5;">{subtitle}</div><div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div></body></html>';
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

/**
 * 输出CDN链接
 *
 * @param string|null $path 子路径
 * @return string
 */
function cdn($path)
{
	$cdnpublic = empty(\Helper::options()->JCdnUrl) ? theme_url('assets/plugin/', false) : \Helper::options()->JCdnUrl;
	$lastChar = substr($cdnpublic, -1);
	if ($lastChar != '/') $cdnpublic = $cdnpublic . '/';
	if (strstrs($cdnpublic, ['||', '//cdn.jsdelivr.net/npm/', '//jsd.onmicrosoft.cn/npm/'])) {
		$pos = strpos($cdnpublic, '||'); // 查找 || 的位置
		if ($pos !== false) {
			$cdnpublic_explode = explode('||', $cdnpublic, 2); // 通过 || 分割 $cdnpublic
			$cdnpublic = trim($cdnpublic_explode[0]); // 获取 || 之前的内容
			$backslash = trim($cdnpublic_explode[1]); // 获取 || 之后的内容
			$backslash = empty($backslash) ? '@' : $backslash; // 检查 || 之后的内容是否为空
		} else {
			$backslash = '@';
		}
		$start_backslash = strpos($path, '/');
		if ($start_backslash !== false) {
			$path = substr_replace($path, $backslash, $start_backslash, 1);
		}
	}
	$url = trim($cdnpublic) . trim($path);
	return $url;
}

function strstrs(string $haystack, array $needles): bool
{
	foreach ($needles as $value) {
		if (stristr($haystack, $value) !== false) return true;
	}
	return false;
}

/**
 * 显示上一篇
 *
 * 如果没有下一篇,返回null
 */
function thePrev($widget, $default = NULL)
{
	$db = \Typecho_Db::get();
	$content = $db->fetchRow($widget->select()->where('table.contents.created < ?', $widget->created)
		->where('table.contents.status = ?', 'publish')
		->where('table.contents.type = ?', $widget->type)
		->where("table.contents.password IS NULL OR table.contents.password = ''")
		->order('table.contents.created', \Typecho\Db::SORT_DESC)
		->limit(1));

	if ($content) {
		$content = $widget->filter($content);
		return $content;
	} else {
		return $default;
	}
}

/**
 * 获取下一篇文章mid
 *
 * 如果没有下一篇,返回null
 */
function theNext($widget, $default = NULL)
{
	$db = \Typecho_Db::get();
	$content = $db->fetchRow($widget->select()->where(
		'table.contents.created > ? AND table.contents.created < ?',
		$widget->created,
		\Helper::options()->time
	)
		->where('table.contents.status = ?', 'publish')
		->where('table.contents.type = ?', $widget->type)
		->where("table.contents.password IS NULL OR table.contents.password = ''")
		->order('table.contents.created', \Typecho\Db::SORT_ASC)
		->limit(1));

	if ($content) {
		$content = $widget->filter($content);
		return $content;
	} else {
		return $default;
	}
}

function dateWord($original_date)
{
	// 2022年08月01日 -> 2022年
	if (preg_match('/(\d+)年\d+月\d+日/i', $original_date, $match)) {
		$original_date = (date('Y') - $match[1]) . '年前';
	}

	// 昨天 21:11 -> 昨天
	$original_date = preg_replace('/昨天 \d+:\d+/i', '昨天', $original_date);

	// 10月8日 -> 10月
	// $original_date = preg_replace('/(\d+月)\d+日/i', '$1', $original_date); 

	return $original_date;
}

function optionMulti($string, string $line = "\r\n", $separator = '||'): array
{
	if (empty($string) || !is_string($string)) return [];
	$optionMulti = [];
	$customArr = explode($line, $string);
	foreach ($customArr as $value) {
		$optionMulti[] = is_string($separator) ? array_map('trim', explode($separator, $value)) : trim($value);
	}
	return $optionMulti;
}

/**
 * 检测面板是否存在
 *
 * @param string $fileName 文件名称
 * @return bool
 */
function panel_exists(string $fileName): bool
{
	$panelTable = is_array(\Helper::options()->panelTable) ? \Helper::options()->panelTable : unserialize(\Helper::options()->panelTable);
	$panelTable['file'] = empty($panelTable['file']) ? [] : $panelTable['file'];
	$fileName = urlencode(trim($fileName, '/'));
	return in_array($fileName, $panelTable['file']);
}

function install()
{
	if (PHP_VERSION < 7.4) {
		echo '<script>alert("请使用 PHP 7.4 及以上版本！");</script>';
		exit;
		return;
	}

	// 检查目录本身的权限
	if (!is_writeable(__FILE__) || !is_readable(__FILE__)) {
		if (chmod(__FILE__, 0755)) {
			echo '<script>alert("请刷新本页面！");</script>';
		} else {
			echo '<script>alert("请设置主题目录及其子目录的权限为755后再设置本主题！");</script>';
		}
		exit;
		return;
	}

	$_db = Typecho_Db::get();
	if ((float) $_db->getVersion() < 5.6) {
		echo '<script>alert("请使用 MySql 5.6 及以上版本！");</script>';
		exit;
		return;
	}

	$orders_url = '../themes/' . THEME_NAME . '/admin/orders.php';
	$friends_url = '../themes/' . THEME_NAME . '/admin/friends.php';
	$lock_file = JOE_ROOT . 'public' . DIRECTORY_SEPARATOR . 'install.lock';

	if (file_exists($lock_file)) {
		$lock_file_content = file_get_contents($lock_file);
		if (is_string($lock_file_content) && $lock_file_content != THEME_NAME) {
			// 删除更改主题目录名后的重复注册面板沉淀
			Helper::removePanel(3, '../themes/' . $lock_file_content . '/admin/orders.php');
			Helper::removePanel(3, '../themes/' . $lock_file_content . '/admin/friends.php');

			// 重新注册新的面板
			if (!panel_exists($orders_url)) Helper::addPanel(3, $orders_url, '订单', '订单管理', 'administrator');
			if (!panel_exists($friends_url)) Helper::addPanel(3, $friends_url, '友链', '友情链接', 'administrator');
			if (file_put_contents($lock_file, THEME_NAME)) {
				echo '<script>alert("主题目录更换为 [' . THEME_NAME . '] 成功！");</script>';
			} else {
				echo '<script>alert("主题目录更换为 [' . THEME_NAME . '] 失败！请务必手动创建安装锁文件 [install.lock] 文件内容为 [' . THEME_NAME . '] 到主题的 public 目录下！");</script>';
			}
		}
		return;
	}

	// 删除某些特殊情况下的重复注册沉淀
	Helper::removePanel(3, $orders_url);
	Helper::removePanel(3, $friends_url);

	// 注册后台订单页面
	if (!panel_exists($orders_url)) {
		Helper::addPanel(3, $orders_url, '订单', '订单管理', 'administrator');
	}

	// 注册后台友链页面
	if (!panel_exists($friends_url)) {
		Helper::addPanel(3, $friends_url, '友链', '友情链接', 'administrator');
	}

	// 注册订单删除接口
	// $actionTable = unserialize(Helper::options()->actionTable);
	// $actionTable = empty($actionTable) ? [] : $actionTable;
	// if (!isset($actionTable['joe-pay-edit']) || $actionTable['joe-pay-edit'] != 'JoeOrders_Widget') {
	// 	Helper::addAction('joe-pay-edit', 'JoeOrders_Widget');
	// }

	try {
		$_prefix = $_db->getPrefix();
		$adapter = $_db->getAdapterName();
		$joe_pay = $_prefix . "joe_pay";
		$friends = $_prefix . 'friends';
		if ($adapter == 'Pdo_SQLite' || $adapter == 'SQLite') {
			$_db->query("CREATE TABLE IF NOT EXISTS `$joe_pay` (
				`id` INTEGER PRIMARY KEY AUTOINCREMENT,
				`trade_no` TEXT NOT NULL UNIQUE,
				`api_trade_no` TEXT,
				`name` TEXT NOT NULL,
				`content_title` TEXT,
				`content_cid` INTEGER NOT NULL,
				`type` TEXT NOT NULL,
				`money` TEXT NOT NULL,
				`ip` TEXT,
				`user_id` TEXT NOT NULL,
				`create_time` TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`update_time` TEXT,
				`pay_type` TEXT,
				`pay_price` TEXT,
				`admin_email` INTEGER NOT NULL DEFAULT 0,
				`user_email` INTEGER NOT NULL DEFAULT 0,
				`status` INTEGER NOT NULL DEFAULT 0
			);");
			$_db->query("CREATE TABLE IF NOT EXISTS `$friends` (
				`id` INTEGER PRIMARY KEY AUTOINCREMENT,
				`title` TEXT NOT NULL,
				`url` TEXT NOT NULL,
				`description` TEXT,
				`logo` TEXT,
				`rel` TEXT,
				`qq` TEXT,
				`order` INTEGER NOT NULL DEFAULT 0,
				`status` INTEGER NOT NULL DEFAULT 0,
				`create_time` TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
			);");
		} else if ($adapter == 'Pdo_Mysql' || $adapter == 'Mysql' || $adapter == 'Mysqli') {
			$_db->query("CREATE TABLE IF NOT EXISTS `$joe_pay` (
				`id` INT NOT NULL AUTO_INCREMENT,
				`trade_no` varchar(64) NOT NULL unique,
				`api_trade_no` varchar(64) DEFAULT NULL,
				`name` varchar(64) NOT NULL,
				`content_title` varchar(150) DEFAULT NULL,
				`content_cid` INT NOT NULL,
				`type` varchar(10) NOT NULL,
				`money` varchar(32) NOT NULL,
				`ip` varchar(32) DEFAULT NULL,
				`user_id` varchar(32) NOT NULL,
				`create_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`update_time` DATETIME DEFAULT NULL,
				`pay_type` varchar(10) DEFAULT NULL,
				`pay_price` varchar(32) DEFAULT NULL,
				`admin_email` BOOLEAN NOT NULL DEFAULT FALSE,
				`user_email` BOOLEAN NOT NULL DEFAULT FALSE,
				`status` BOOLEAN NOT NULL DEFAULT FALSE,
				PRIMARY KEY  (`id`)
			) DEFAULT CHARSET=utf8mb4;");
			$_db->query("CREATE TABLE IF NOT EXISTS `$friends` (
				`id` INT NOT NULL AUTO_INCREMENT,
				`title` varchar(128) NOT NULL,
				`url` varchar(255) NOT NULL,
				`description` TEXT DEFAULT NULL,
				`logo` TEXT DEFAULT NULL,
				`rel` varchar(128) DEFAULT NULL,
				`qq` varchar(32) DEFAULT NULL,
				`order` INT NOT NULL DEFAULT 0,
				`status` BOOLEAN NOT NULL DEFAULT FALSE,
				`create_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY  (`id`)
			) DEFAULT CHARSET=utf8mb4;");
		} else if ($adapter == 'Pdo_Pgsql' || $adapter == 'Pgsql') {
			$_db->query('CREATE TABLE IF NOT EXISTS ' . $joe_pay . ' (
				id SERIAL PRIMARY KEY,
				trade_no VARCHAR(64) UNIQUE NOT NULL,
				api_trade_no VARCHAR(64),
				name VARCHAR(64) NOT NULL,
				content_title VARCHAR(150),
				content_cid INT NOT NULL,
				"type" VARCHAR(10) NOT NULL,
				money VARCHAR(32) NOT NULL,
				ip VARCHAR(32),
				user_id VARCHAR(32) NOT NULL,
				create_time TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW(),
				update_time TIMESTAMP WITHOUT TIME ZONE,
				pay_type VARCHAR(10),
				pay_price VARCHAR(32),
				admin_email BOOLEAN NOT NULL DEFAULT FALSE,
				user_email BOOLEAN NOT NULL DEFAULT FALSE,
				"status" BOOLEAN NOT NULL DEFAULT FALSE
			);');
			$_db->query('CREATE TABLE IF NOT EXISTS ' . $friends . ' (
				id SERIAL PRIMARY KEY,
				title VARCHAR(128) NOT NULL,
				url VARCHAR(255) NOT NULL,
				description TEXT,
				logo TEXT,
				rel VARCHAR(128),
				qq VARCHAR(32),
				"order" INT NOT NULL DEFAULT 0,
				"status" BOOLEAN NOT NULL DEFAULT FALSE,
				create_time TIMESTAMP WITHOUT TIME ZONE DEFAULT NOW()
			);');
		} else {
			echo '暂不兼容 [' . $adapter . '] 数据库适配器！';
			exit;
		}

		$JFriends = optionMulti(Helper::options()->JFriends);
		$JFriends[] = ['易航博客', 'http://blog.bri6.cn', 'http://blog.bri6.cn/favicon.ico', '一名编程爱好者的博客，记录与分享编程、学习中的知识点', 'friend'];

		foreach ($JFriends as $value) {
			$_db->query($_db->insert('table.friends')->rows(
				array(
					'title' => ($value[0] ?? ''),
					'url' => ($value[1] ?? ''),
					'logo' => ($value[2] ?? ''),
					'description' => ($value[3] ?? ''),
					'rel' => ($value[4] ?? ''),
					'order' => ($value[5] ?? '0'),
					'status' => '1'
				)
			));
		}

		$table_contents = $_db->fetchRow($_db->select()->from('table.contents')->page(1, 1));
		$table_contents = empty($table_contents) ? [] : $table_contents;

		$views = $_db->fetchRow("SHOW COLUMNS FROM `{$_prefix}contents` LIKE 'views';");
		$agree = $_db->fetchRow("SHOW COLUMNS FROM `{$_prefix}contents` LIKE 'views';");

		if (!array_key_exists('views', $table_contents) && !$views) {
			$_db->query("ALTER TABLE `{$_prefix}contents` ADD `views` INT DEFAULT 0;");
		}
		if (!array_key_exists('agree', $table_contents) && !$agree) {
			$_db->query("ALTER TABLE `{$_prefix}contents` ADD `agree` INT DEFAULT 0;");
		}

		$typecho_admin_root = __TYPECHO_ROOT_DIR__ . __TYPECHO_ADMIN_DIR__;
		if (file_exists($typecho_admin_root . 'themes.php')) {
			file_put_contents($typecho_admin_root . 'themes.php', '<?php echo base64_decode("PHNjcmlwdD4KCSQoZG9jdW1lbnQpLnJlYWR5KHNldFRpbWVvdXQoKCkgPT4gewoJCSQoJ3Rib2R5PnRyOm5vdCgjdGhlbWUtSm9lKT50ZD5wPmEuYWN0aXZhdGUnKS5hdHRyKCdocmVmJywgJ2phdmFzY3JpcHQ6YWxlcnQoIuWQr+eUqOWksei0pe+8gVR5cGVjaG/lt7Lnu4/mt7Hmt7HlnLDniLHkuIpKb2Xlho3nu63liY3nvJjkuoblk6YiKScpOwoJfSwgMTAwKSk7Cjwvc2NyaXB0Pg=="); ?>', FILE_APPEND | LOCK_EX);
		}

		if (file_put_contents($lock_file, THEME_NAME)) {
			echo '<script>alert("主题首次启用安装成功！");</script>';
		} else {
			echo '<script>alert("主题首次启用安装失败！请务必手动创建安装锁文件 install.lock 到主题的public目录下！");</script>';
		}
	} catch (\Exception $e) {
		echo $e;
		exit;
	}
}


/**
 * 检测各大平台蜘蛛函数
 * 
 * @return string 返回检测到的平台名称，如：百度，谷歌，必应等，否则返回空字符串
 */
function detectSpider()
{
	static $spider = false;
	if ($spider === false) {
		$spiders = [
			// 搜索引擎
			'Baidu' => ['Baiduspider', 'baidu.com/search', 'Baiduspider-image', 'Baiduspider-video'],
			'Google' => ['Googlebot', 'google.com/bot', 'Googlebot-Image', 'Googlebot-Mobile', 'Googlebot-News'],
			'Bing' => ['Bingbot', 'bing.com/bot', 'BingPreview', 'msnbot', 'bing.com'],
			'Yahoo' => ['Yahoo! Slurp', 'yahoo.com/slurp'],
			'Yandex' => ['YandexBot', 'yandex.com/bot'],
			'DuckDuckGo' => ['DuckDuckBot', 'duckduckgo.com/bot'],

			// 爬虫
			'Ahrefs' => ['AhrefsBot', 'ahrefs.com'],
			'Semrush' => ['SemrushBot', 'semrush.com'],
			'Moz' => ['MozBot', 'moz.com'],
			'SEOZoom' => ['SEOZoomBot', 'seozoom.com'],

			// 其他
			'Facebook' => ['facebookexternalhit', 'facebook.com'],
			'Twitter' => ['Twitterbot', 'twitter.com'],
			'LinkedIn' => ['LinkedInBot', 'linkedin.com'],
		];

		// 遍历所有平台
		foreach ($spiders as $name => $patterns) {
			// 遍历每个平台的匹配模式
			foreach ($patterns as $pattern) {
				// 如果用户代理字符串匹配模式
				if (stripos($_SERVER['HTTP_USER_AGENT'], $pattern) !== false) {
					$spider = $name;
				}
			}
		}

		// 未匹配到任何平台
		$spider = is_string($spider) ? $spider : null;
	}
	return $spider;
}

function get_archive_tags($item)
{
	$color_array = ['c-blue', 'c-yellow', 'c-green', 'c-cyan', 'c-blue-2', 'c-purple-2', 'c-yellow-2', 'c-purple', 'c-red-2', 'c-red'];
	$tags = '';
	if ($item->fields->hide == 'pay' && $item->fields->pay_tag_background != 'none') {
		$tags .= '<a rel="nofollow" href="' . $item->permalink . '?scroll=pay-box" class="meta-pay but jb-' . $item->fields->pay_tag_background . '">' . ($item->fields->price > 0 ? '付费阅读<span class="em09 ml3">￥</span>' . $item->fields->price : '免费资源') . '</a>';
	}
	foreach ($item->categories as $key => $value) {
		$tags .= '<a class="but ' . $color_array[$key] . '" title="查看此分类更多文章" href="' . $value['permalink'] . '"><i class="fa fa-folder-open-o" aria-hidden="true"></i>' . $value['name'] . '</a>';
	}
	foreach ($item->tags as $key => $value) {
		$tags .= '<a href="' . $value['permalink'] . '" title="查看此标签更多文章" class="but"># ' . $value['name'] . '</a>';
	}
	return $tags;
}

/**
 * 输出解析地址
 *
 * @param string|null $path 子路径
 */
function index($path, $prefix = false)
{
	$index = \Typecho\Common::url($path, \Helper::options()->index);
	return is_string($prefix) ? str_ireplace(['http://', 'https://'], $prefix, $index) : $index;
}

/**
 * 获取自定义导航栏
 */
function custom_navs()
{
	static $custom_navs = null;
	if (is_null($custom_navs)) {
		$custom_navs_text = Helper::options()->JCustomNavs;
		$custom_navs_block = optionMulti($custom_navs_text, "\r\n\r\n", false);
		$custom_navs = [];
		foreach ($custom_navs_block as $key => $value) {
			$custom_navs_explode = optionMulti($value);
			$custom_navs[$key] = [
				'title' => $custom_navs_explode[0][0] ? custom_navs_title($custom_navs_explode[0][0]) : '菜单标题',
				'url' => $custom_navs_explode[0][1] ?? 'javascript:;',
				'target' => $custom_navs_explode[0][2] ?? '_self',
				'list' => []
			];
			unset($custom_navs_explode[0]);
			foreach ($custom_navs_explode as $value) {
				$custom_navs[$key]['list'][] = [
					'title' => $value[0] ? custom_navs_title($value[0]) : '二级标题',
					'url' => $value[1] ?? 'javascript:;',
					'target' => $value[2] ?? '_self'
				];
			}
		}
	}
	return $custom_navs;
}

function custom_navs_title($title)
{
	$title = preg_replace('/\[(.+)\]/i', '<svg class="svg" aria-hidden="true"><use xlink:href="#$1"></use></svg>', $title);
	return $title;
}

/**
 * 输出作者指定字段总数，可以指定
 */
function author_post_field_sum($id, $field)
{
	$db = Typecho_Db::get();
	$postnum = $db->fetchRow($db->select(array('SUM(' . $field . ')' => 'field'))->from('table.contents')->where('table.contents.authorId=?', $id)->where('table.contents.type=?', 'post'));
	return $postnum['field'];
}

/**
 * 语义化数字
 */
function number_word($number)
{
	if (is_numeric($number)) {
		if ($number >= 10000) {
			return number_format(floor($number / 10000)) . 'W+';
		} elseif ($number >= 1000) {
			return number_format(floor($number / 1000)) . 'K+';
		} else {
			return $number;
		}
	}
	return 0;
}

function draw_save($base64String, $outputFile)
{
	if (file_exists($outputFile)) return true;
	// 检查字符串是否包含前缀
	if (preg_match('/^data:image\/webp;base64,/', $base64String)) {
		// 移除前缀
		$base64String = preg_replace('/^data:image\/webp;base64,/', '', $base64String);
		// 解码
		$imageData = base64_decode($base64String);
		if ($imageData === false) return false;
		// 保存文件
		$dir = dirname($outputFile);
		if (!is_dir($dir)) mkdir($dir, 0755, true);
		return file_put_contents($outputFile, $imageData);
	} else {
		return null;
	}
}

function icon_crid_info($content)
{
	$title_explode = explode('[', $content[0], 2);
	$title = trim($title_explode[0]);

	$description_explode = explode('--', $title, 2);
	if (isset($description_explode[1])) {
		$description = trim($description_explode[1]);
	} else {
		$title = str_replace('-/-', '--', $title);
		$description = null;
	}

	if (isset($title_explode[1])) {
		$icon_explode = explode(']', trim($title_explode[1], ']'), 2);
		$icon = trim($icon_explode[0]);
	} else {
		$icon = null;
	}

	if (isset($icon_explode[1])) {
		$icon_class = trim($icon_explode[1], '()');
	} else {
		$icon_class = 'c-blue';
	}

	return [
		'title' => $title,
		'description' => $description,
		'icon' => $icon,
		'icon_class' => $icon_class,
		'url' => $content[1] ?? 'javascript:;',
		'target' => $content[2] ?? '_self'
	];
}

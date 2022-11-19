<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$JMusic = new Typecho_Widget_Helper_Form_Element_Select(
	'JMusic',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'前端音乐开关',
	'介绍：开启后站点前端将全局播放网易云音乐'
);
$JMusic->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusic);

$JMusicServer = new Typecho_Widget_Helper_Form_Element_Select(
	'JMusicServer',
	[
		'netease' => '网易云音乐（默认）',
		'tencent' => 'QQ音乐',
		'kugou' => '酷狗音乐',
		'xiami' => '虾米音乐',
		'baidu' => '百度音乐'
	],
	'netease',
	'音乐平台',
	'介绍：使用的音乐平台'
);
$JMusicServer->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusicServer);

$JMusicType = new Typecho_Widget_Helper_Form_Element_Select(
	'JMusicType',
	[
		'playlist' => '歌单（默认）',
		'song' => '单曲',
		'album' => '专辑',
		'search' => '搜索结果',
		'artist' => '艺术家'
	],
	'playlist',
	'音乐类型',
	'介绍：使用的音乐解析类型'
);
$JMusicType->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusicType);

$JMusicId = new Typecho_Widget_Helper_Form_Element_Text(
	'JMusicId',
	null,
	'7757541927',
	'ID',
	'介绍：类型指向的ID，一般可以在网页版地址栏中找到'
);
$JMusicId->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusicId);

$JMusicPlay = new Typecho_Widget_Helper_Form_Element_Select(
	'JMusicPlay',
	['off' => '关闭', 'on' => '开启（默认）'],
	'on',
	'自动播放',
	'介绍：音乐数据加载完毕后自动播放，部分浏览器已禁用自动播放声音策略'
);
$JMusicPlay->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusicPlay);

$JMusicOrder = new Typecho_Widget_Helper_Form_Element_Select(
	'JMusicOrder',
	['random' => '随机播放（默认）', 'list' => '默认排序'],
	'random',
	'播放顺序',
	'介绍：播放顺序'
);
$JMusicOrder->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusicOrder);
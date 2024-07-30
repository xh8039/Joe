<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$extension_tip = '';
$extension = ['bcmath', 'curl', 'openssl'];
foreach ($extension as  $value) {
	if (!extension_loaded($value)) {
		$extension_tip .= '<br /><font color="red">务必先安装 PHP 的 ' . $value . ' 扩展后再开启本功能！安装后重启PHP生效';
	}
}

$JMusic = new Typecho_Widget_Helper_Form_Element_Select(
	'JMusic',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'全局音乐开关',
	'介绍：开启后站点前端将全局播放音乐' . $extension_tip
);
$JMusic->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusic);

$JMusicCookie = new Typecho_Widget_Helper_Form_Element_Text(
	'JMusicCookie',
	NULL,
	NULL,
	'账号Cookie',
	'介绍：登录音乐平台后的Cookie，需要自己抓包获取，如果您有此平台的会员，那么填写上您账号的Cookie就可以解析会员音乐'
);
$JMusicCookie->setAttribute('class', 'joe_content joe_music');
$form->addInput($JMusicCookie);

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
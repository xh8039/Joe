<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JMaccmsAPI = new \Typecho\Widget\Helper\Form\Element\Text(
	'JMaccmsAPI',
	NULL,
	NULL,
	'苹果CMS开放API',
	'介绍：请填写苹果CMS V10开放API，用于视频页面使用<br />
	例如：http://blog.yihang.info/api.php/provide/vod/ <br />
	如果您搭建了苹果cms网站，那么用你自己的即可，如果没有，请去网上找API丨<a target="_blank" href="http://blog.yihang.info/archives/427.html">查看推荐接口</a>'
);
$JMaccmsAPI->setAttribute('class', 'joe_content joe_other');
$form->addInput($JMaccmsAPI);

$WallpaperAPI = new \Typecho\Widget\Helper\Form\Element\Text(
	'WallpaperAPI',
	NULL,
	NULL,
	'壁纸模板API（非必填）',
	'格式：壁纸分类API || 壁纸列表API <br />
	示例：http://cdn.apc.360.cn/index.php || http://wallpaper.apc.360.cn/index.php<br>
	教程：<a target="_blank" href="http://blog.yihang.info/archives/365.html">查看官网教程</a>'
);
$WallpaperAPI->setAttribute('class', 'joe_content joe_other');
$form->addInput($WallpaperAPI);

$JCustomPlayer = new \Typecho\Widget\Helper\Form\Element\Text(
	'JCustomPlayer',
	NULL,
	NULL,
	'自定义视频播放器（非必填）',
	'介绍：用于修改主题自带的默认播放器 <br />
	示例：' . Helper::options()->themeUrl . '/module/player.php?url=<br />
	注意：示例可用，为主题自带播放器，但建议默认留空，因为使用了自定义播放器后播放器无法准确自动跟随视频的真实高度。<br>
	支持：主题自带的播放器可以解析MP4、MPEG-DASH、WEBM、OGG、HEVC（H.265）、M3U8、MPD、FLV、磁力链接（BT种子）格式的视频'
);
$JCustomPlayer->setAttribute('class', 'joe_content joe_other');
$form->addInput($JCustomPlayer);

$JoeDeBug = new \Typecho\Widget\Helper\Form\Element\Select(
	'JoeDeBug',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'主题调试模式',
	'介绍：开启后可查看主题的SQL日志'
);
$JoeDeBug->setAttribute('class', 'joe_content joe_other');
$form->addInput($JoeDeBug);

<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$WallpaperAPI = new \Typecho\Widget\Helper\Form\Element\Text(
	'WallpaperAPI',
	NULL,
	'http://cdn.apc.360.cn/index.php || http://wallpaper.apc.360.cn/index.php',
	'壁纸模板API',
	'格式：壁纸分类API || 壁纸列表API <br />
	示例：http://cdn.apc.360.cn/index.php || http://wallpaper.apc.360.cn/index.php'
);
$WallpaperAPI->setAttribute('class', 'joe_content joe_other');
$form->addInput($WallpaperAPI);

$JMaccmsAPI = new \Typecho\Widget\Helper\Form\Element\Text(
	'JMaccmsAPI',
	NULL,
	NULL,
	'苹果CMS开放API',
	'介绍：请填写苹果CMS V10开放API，用于视频页面使用<br />
		 例如：http://blog.bri6.cn/api.php/provide/vod/ <br />
		 如果您搭建了苹果cms网站，那么用你自己的即可，如果没有，请去网上找API <br />
		 '
);
$JMaccmsAPI->setAttribute('class', 'joe_content joe_other');
$form->addInput($JMaccmsAPI);

$JCustomPlayer = new \Typecho\Widget\Helper\Form\Element\Text(
	'JCustomPlayer',
	NULL,
	NULL,
	'自定义视频播放器（非必填）',
	'介绍：用于修改主题自带的默认播放器 <br />
		 例如：http://blog.bri6.cn/player/?url= <br />
		 注意：主题自带的播放器可以解析MP4、WEBM、OGG、H265、HEVC、M3U8、MPD、FLV、磁力链接（BT种子）格式的视频'
);
$JCustomPlayer->setAttribute('class', 'joe_content joe_other');
$form->addInput($JCustomPlayer);

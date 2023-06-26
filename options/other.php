<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$JMaccmsAPI = new Typecho_Widget_Helper_Form_Element_Text(
	'JMaccmsAPI',
	NULL,
	NULL,
	'苹果CMS开放API',
	'介绍：请填写苹果CMS V10开放API，用于视频页面使用<br />
		 例如：https://v.ini0.com/api.php/provide/vod/ <br />
		 如果您搭建了苹果cms网站，那么用你自己的即可，如果没有，请去网上找API <br />
		 '
);
$JMaccmsAPI->setAttribute('class', 'joe_content joe_other');
$form->addInput($JMaccmsAPI);

$JCustomPlayer = new Typecho_Widget_Helper_Form_Element_Text(
	'JCustomPlayer',
	NULL,
	NULL,
	'自定义视频播放器（非必填）',
	'介绍：用于修改主题自带的默认播放器 <br />
		 例如：https://v.ini0.com/player/?url= <br />
		 注意：主题自带的播放器只能解析M3U8的视频格式'
);
$JCustomPlayer->setAttribute('class', 'joe_content joe_other');
$form->addInput($JCustomPlayer);
<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$JIndex_Title = new Typecho_Widget_Helper_Form_Element_Text(
	'JIndex_Title',
	NULL,
	NULL,
	'自定义首页标题',
	'介绍：填写后可自定义站点首页的标题'
);
$JIndex_Title->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Title);

$JIndex_Header_Img = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JIndex_Header_Img',
	NULL,
	'http://p7.qhimg.com/bdr/__85/t014d46a590e4d07543.jpg',
	'首页顶部大图背景壁纸',
	'格式：图片地址 或 Base64地址<br>
		 填写 “透明” 即使用透明壁纸 可配合背景壁纸使用'
);
$JIndex_Header_Img->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Header_Img);

$JIndex_Carousel = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JIndex_Carousel',
	NULL,
	NULL,
	'首页轮播图',
	'介绍：用于显示首页轮播图，请务必填写正确的格式 <br />
		 格式：图片地址 || 跳转链接 || 标题 （中间使用两个竖杠分隔）<br />
		 或者填写文章ID，例：99 <br />
		 其他：一行一个，一行代表一个轮播图 <br />
		 例如：<br />
			https://puui.qpic.cn/media_img/lena/PICykqaoi_580_1680/0 || https://baidu.com || 百度一下 <br />
			https://puui.qpic.cn/tv/0/1223447268_1680580/0 || https://v.qq.com || 腾讯视频
		 '
);
$JIndex_Carousel->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Carousel);

$JIndex_Carousel_Target = new Typecho_Widget_Helper_Form_Element_Select(
	'JIndex_Carousel_Target',
	array(
		'_blank' => '_blank（默认，新窗口）',
		'_parent' => '_parent（当前窗口）',
		'_self' => '_self（同窗口）',
		'_top' => '_top（顶端打开窗口）',
	),
	'_blank',
	'首页轮播图打开窗口方式',
);
$JIndex_Carousel_Target->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Carousel_Target->multiMode());

$JIndex_Recommend = new Typecho_Widget_Helper_Form_Element_Text(
	'JIndex_Recommend',
	NULL,
	NULL,
	'首页推荐文章（非必填）',
	'介绍：用于显示推荐文章，请务必填写正确的格式 <br/>
		 格式：文章的id || 文章的id （中间使用两个竖杠分隔）<br />
		 例如：1 || 2'
);
$JIndex_Recommend->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Recommend);

$JIndexSticky = new Typecho_Widget_Helper_Form_Element_Text(
	'JIndexSticky',
	NULL,
	NULL,
	'首页置顶文章（非必填）',
	'介绍：请务必填写正确的格式 <br />
		 格式：文章的ID || 文章的ID || 文章的ID （中间使用两个竖杠分隔）<br />
		 例如：1 || 2 || 3'
);
$JIndexSticky->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndexSticky);

$JIndex_Hot = new Typecho_Widget_Helper_Form_Element_Text(
	'JIndex_Hot',
	NULL,
	'0',
	'首页热门文章显示数量',
	'介绍：填写指定数字后，网站首页将会显示浏览量最多的指定数量篇数热门文章'
);
$JIndex_Hot->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Hot->multiMode());

$JIndex_Ad = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JIndex_Ad',
	NULL,
	NULL,
	'首页大屏广告',
	'介绍：请务必填写正确的格式 <br />
		 格式：广告图片 || 广告链接 （中间使用两个竖杠分隔，限制一个）<br />
		 例如：https://puui.qpic.cn/media_img/lena/PICykqaoi_580_1680/0 || https://baidu.com'
);
$JIndex_Ad->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Ad);

$JIndex_Google_AdSense_switch = new Typecho_Widget_Helper_Form_Element_Select(
	'JIndex_Google_AdSense_switch',
	array(
		'off' => '关闭（默认）',
		'list' => '文章列表处',
		'ad' => '文章列表上方'
	),
	'off',
	'首页谷歌广告展示方式',
	'介绍：首页谷歌广告展示方式，关闭后即便部署代码也将不再展示'
);
$JIndex_Google_AdSense_switch->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Google_AdSense_switch);

$JIndex_Google_AdSense_phone = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JIndex_Google_AdSense_phone',
	NULL,
	NULL,
	'首页移动端谷歌广告代码',
	'介绍：用于移动端显示首页文章列表谷歌广告代码'
);
$JIndex_Google_AdSense_phone->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Google_AdSense_phone);

$JIndex_Google_AdSense_pc = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JIndex_Google_AdSense_pc',
	NULL,
	NULL,
	'首页PC端谷歌广告代码',
	'介绍：用于PC端显示首页文章列表谷歌广告代码'
);
$JIndex_Google_AdSense_pc->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Google_AdSense_pc);

$JIndex_Notice = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JIndex_Notice',
	NULL,
	NULL,
	'首页通知文字（非必填）',
	'介绍：请务必填写正确的格式 <br />
		 格式：通知文字 || 跳转链接（中间使用两个竖杠分隔，限制一个）<br />
		 例如：欢迎加入Joe官方QQ群 || https://baidu.com'
);
$JIndex_Notice->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Notice);
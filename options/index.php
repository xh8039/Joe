<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$JIndex_Article_Double_Column = new Typecho_Widget_Helper_Form_Element_Select(
	'JIndex_Article_Double_Column',
	array(
		'off' => '关闭（默认）',
		'on' => '开启'
	),
	'off',
	'首页文章双栏排版并隐藏侧边栏（仅在屏幕分辨率大于1400px下生效）',
	NULL
);
$JIndex_Article_Double_Column->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Article_Double_Column->multiMode());

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

$Jessay_target = new Typecho_Widget_Helper_Form_Element_Select(
	'Jessay_target',
	array(
		'_blank' => '_blank（默认，新窗口）',
		'_parent' => '_parent（当前窗口）',
		'_self' => '_self（同窗口）',
		'_top' => '_top（顶端打开窗口）',
	),
	'_blank',
	'首页文章列表打开方式',
);
$Jessay_target->setAttribute('class', 'joe_content joe_index');
$form->addInput($Jessay_target->multiMode());

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

$JIndex_Notice = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JIndex_Notice',
	NULL,
	NULL,
	'首页通知文字（非必填）',
	'介绍：请务必填写正确的格式 <br />
		 格式：通知文字 || 跳转链接（中间使用两个竖杠分隔，限制一个）<br />
		 例如：欢迎加入Joe主题QQ交流群 || https://jq.qq.com/?_wv=1027&k=j9lt1kwg'
);
$JIndex_Notice->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Notice);
<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JIndex_Icon_Card_Title = new \Typecho\Widget\Helper\Form\Element\Text(
	'JIndex_Icon_Card_Title',
	NULL,
	'板块分类',
	'首页图标卡片模块标题',
	'介绍：用于显示首页图标卡片模块标题，若不想显示标题则留空'
);
$JIndex_Icon_Card_Title->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Icon_Card_Title);

$JIndex_Icon_Card = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JIndex_Icon_Card',
	NULL,
	NULL,
	'首页图标卡片模块',
	'介绍：用于显示首页轮播图下方的图标卡片，请务必填写正确的格式丨<a target="_blank" href="http://blog.bri6.cn/archives/295.html">查看官网教程</a>'
);
$JIndex_Icon_Card->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Icon_Card);

$JIndex_Ajax_List = new \Typecho\Widget\Helper\Form\Element\Select(
	'JIndex_Ajax_List',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'首页文章Ajax加载'
);
$JIndex_Ajax_List->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Ajax_List->multiMode());

$JIndex_Hide_Post = new \Typecho\Widget\Helper\Form\Element\Text(
	'JIndex_Hide_Post',
	NULL,
	NULL,
	'首页隐藏文章（非必填）',
	'介绍：用于隐藏指定文章，请务必填写正确的格式 <br/>
		 格式：文章的id || 文章的id （中间使用两个竖杠分隔）<br />
		 例如：1 || 2'
);
$JIndex_Hide_Post->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Hide_Post);

$JIndex_Hide_Categorize = new \Typecho\Widget\Helper\Form\Element\Text(
	'JIndex_Hide_Categorize',
	NULL,
	NULL,
	'首页隐藏分类（非必填）',
	'介绍：用于隐藏指定分类，请务必填写正确的格式 <br/>
		 格式：分类的缩略名 || 分类的缩略名 （中间使用两个竖杠分隔）<br />
		 例如：slug || about'
);
$JIndex_Hide_Categorize->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Hide_Categorize);

$JIndex_Article_Double_Column = new \Typecho\Widget\Helper\Form\Element\Select(
	'JIndex_Article_Double_Column',
	array(
		'off' => '关闭（默认）',
		'on' => '开启'
	),
	'off',
	'首页文章双栏排版并隐藏侧边栏（仅在屏幕分辨率大于1400px下生效）'
);
$JIndex_Article_Double_Column->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Article_Double_Column->multiMode());

$JIndex_Header_Img = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JIndex_Header_Img',
	NULL,
	null,
	'首页顶部大图背景壁纸',
	'格式：图片URL地址 或 Base64编码<br>
	 填写 “透明” 即使用透明壁纸 可配合背景壁纸使用'
);
$JIndex_Header_Img->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Header_Img);

$JIndex_Carousel = new \Typecho\Widget\Helper\Form\Element\Textarea(
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

$JIndex_Carousel_Target = new \Typecho\Widget\Helper\Form\Element\Select(
	'JIndex_Carousel_Target',
	array(
		'_self' => '_self（默认，在与点击相同的框架中打开链接的文档）',
		'_blank' => '_blank（在新窗口或选项卡中打开链接文档）',
		'_parent' => '_parent（在父框架中打开链接文档）',
		'_top' => '_top（在窗口的整个主体中打开链接的文档）',
	),
	'_self',
	'首页轮播图打开窗口方式',
);
$JIndex_Carousel_Target->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Carousel_Target->multiMode());

$Jessay_target = new \Typecho\Widget\Helper\Form\Element\Select(
	'Jessay_target',
	array(
		'_self' => '_self（默认，在与点击相同的框架中打开链接的文档）',
		'_blank' => '_blank（在新窗口或选项卡中打开链接文档）',
		'_parent' => '_parent（在父框架中打开链接文档）',
		'_top' => '_top（在窗口的整个主体中打开链接的文档）',
	),
	'_self',
	'首页文章列表打开方式',
);
$Jessay_target->setAttribute('class', 'joe_content joe_index');
$form->addInput($Jessay_target->multiMode());

$JIndex_Recommend = new \Typecho\Widget\Helper\Form\Element\Text(
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

$JIndex_Mobile_Recommend = new \Typecho\Widget\Helper\Form\Element\Text(
	'JIndex_Mobile_Recommend',
	NULL,
	NULL,
	'首页移动端推荐文章（非必填）',
	'介绍：用于显示移动端推荐文章，请务必填写正确的格式 <br/>
	格式：文章的id || 文章的id （中间使用两个竖杠分隔）<br />
	例如：1 || 2'
);
$JIndex_Mobile_Recommend->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Mobile_Recommend);

$JIndex_Recommend_Style = new \Typecho\Widget\Helper\Form\Element\Select(
	'JIndex_Recommend_Style',
	['simple' => '简约样式（默认）', 'full' => '比较全（和热门文章风格相同）',],
	'simple',
	'首页推荐文章风格',
);
$JIndex_Recommend_Style->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Recommend_Style->multiMode());

$JIndexSticky = new \Typecho\Widget\Helper\Form\Element\Text(
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

$JIndex_Hot = new \Typecho\Widget\Helper\Form\Element\Text(
	'JIndex_Hot',
	NULL,
	'0',
	'首页热门文章显示数量',
	'介绍：填写指定数字后，网站首页将会显示浏览量最多的指定数量篇数热门文章'
);
$JIndex_Hot->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Hot->multiMode());

$JIndex_Mobile_Hot = new \Typecho\Widget\Helper\Form\Element\Text(
	'JIndex_Mobile_Hot',
	NULL,
	'0',
	'首页移动端热门文章显示数量',
	'介绍：填写指定数字后，移动端网站首页将会显示浏览量最多的指定数量篇数热门文章'
);
$JIndex_Mobile_Hot->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Mobile_Hot->multiMode());

$JIndex_Ad_Title = new \Typecho\Widget\Helper\Form\Element\Text(
	'JIndex_Ad_Title',
	NULL,
	'推广宣传',
	'首页大屏广告标题'
);
$JIndex_Ad_Title->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Ad_Title);

$JIndex_Ad = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JIndex_Ad',
	NULL,
	NULL,
	'首页大屏广告',
	'介绍：请务必填写正确的格式 <br />
		格式：广告图片 || 广告链接（可为空） || 广告文字（可为空）（中间使用两个竖杠分隔，一行一个）<br />
		例如：https://puui.qpic.cn/media_img/lena/PICykqaoi_580_1680/0 || https://baidu.com || 广告'
);
$JIndex_Ad->setAttribute('class', 'joe_content joe_index');
$form->addInput($JIndex_Ad);

$JIndex_Notice = new \Typecho\Widget\Helper\Form\Element\Textarea(
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

<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JFavicon = new \Typecho\Widget\Helper\Form\Element\Text(
	'JFavicon',
	NULL,
	'http://blog.yihang.info/favicon.ico',
	'网站 Favicon 设置',
	'介绍：用于设置网站 Favicon，一个好的 Favicon 可以给用户一种很专业的观感 <br />
		 格式：图片 URL地址 或 Base64 地址 <br />
		 其他：免费转换 Favicon 网站 <a target="_blank" href="//tool.lu/favicon">tool.lu/favicon</a>'
);
$JFavicon->setAttribute('class', 'joe_content joe_image');
$form->addInput($JFavicon);

$JLogo = new \Typecho\Widget\Helper\Form\Element\Text(
	'JLogo',
	NULL,
	NULL,
	'网站 Logo 设置',
	'介绍：用于设置网站 Logo，一个好的 Logo 能为网站带来有效的流量 <br />
		 格式：图片 URL地址 或 Base64 地址 <br />
		 其他：免费制作 logo 网站 <a target="_blank" href="//www.uugai.com">www.uugai.com</a>'
);
$JLogo->setAttribute('class', 'joe_content joe_image');
$form->addInput($JLogo);

$JDarkLogo = new \Typecho\Widget\Helper\Form\Element\Text(
	'JDarkLogo',
	NULL,
	NULL,
	'网站夜间模式 Logo',
	'介绍：用于设置网站夜间模式的Logo，一个好的 Logo 能为网站带来有效的流量 <br />
		 格式：图片 URL地址 或 Base64 地址 <br />
		 其他：免费制作 logo 网站 <a target="_blank" href="//www.uugai.com">www.uugai.com</a>'
);
$JDarkLogo->setAttribute('class', 'joe_content joe_image');
$form->addInput($JDarkLogo);

$JThumbnail = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JThumbnail',
	NULL,
	NULL,
	'自定义文章缩略图',
	'介绍：用于修改主题默认的文章缩略图 <br/>
		 格式：图片地址，一行一个 <br />
		 注意：不填写时，则使用主题内置的默认缩略图'
);
$JThumbnail->setAttribute('class', 'joe_content joe_image');
$form->addInput($JThumbnail);

$JLazyload = new \Typecho\Widget\Helper\Form\Element\Text(
	'JLazyload',
	NULL,
	NULL,
	'自定义懒加载图',
	'介绍：用于修改主题默认懒加载图 <br/>
		 格式：图片地址'
);
$JLazyload->setAttribute('class', 'joe_content joe_image');
$form->addInput($JLazyload);

$DynamicBackground = new \Typecho\Widget\Helper\Form\Element\Select(
	'DynamicBackground',
	array(
		'off' => '关闭（默认）',
		'backdrop1.js' => '几何蜘网',
		'backdrop2.js' => '流动线条',
		'backdrop3.js' => '绚烂彩虹',
		'backdrop4.js' => '樱花飘落',
		'backdrop5.js' => '素描气球',
		'backdrop6.js' => '一个线条'
	),
	'off',
	'是否开启动态背景图',
	'介绍：用于设置动态背景<br />
	 注意：如果您填写了下方的 PC端/移动端 静态壁纸，将优先展示下方静态壁纸！如需显示动态壁纸，请将PC端静态壁纸设置成空'
);
$DynamicBackground->setAttribute('class', 'joe_content joe_image');
$form->addInput($DynamicBackground->multiMode());

$JWallpaper_Background_PC = new \Typecho\Widget\Helper\Form\Element\Text(
	'JWallpaper_Background_PC',
	NULL,
	NULL,
	'PC端网站背景图片（非必填）',
	'介绍：PC端网站的背景图片，不填写时显示默认的灰色。<br />
		 格式：图片URL地址 或 随机图片api 例如：https://api.btstu.cn/sjbz/?lx=dongman <br />
		 注意：如果需要显示上方动态壁纸，请不要填写此项，此项优先级最高！'
);
$JWallpaper_Background_PC->setAttribute('class', 'joe_content joe_image');
$form->addInput($JWallpaper_Background_PC);

$JWallpaper_Background_WAP = new \Typecho\Widget\Helper\Form\Element\Text(
	'JWallpaper_Background_WAP',
	NULL,
	NULL,
	'移动端网站背景图片（非必填）',
	'介绍：移动端网站的背景图片，不填写时显示默认的灰色。<br />
		 格式：图片URL地址 或 随机图片api 例如：https://api.btstu.cn/sjbz/?lx=m_dongman'
);
$JWallpaper_Background_WAP->setAttribute('class', 'joe_content joe_image');
$form->addInput($JWallpaper_Background_WAP);

$JWallpaper_Background_Optimal = new \Typecho\Widget\Helper\Form\Element\Select(
	'JWallpaper_Background_Optimal',
	[
		'off' => '关闭（默认）',
		'pc' => '仅PC端',
		'wap' => '仅移动端',
		'all' => '全部'
	],
	'off',
	'是否开启自定义背景壁纸优化',
	'介绍：开启后将对自定义背景壁纸模式下没有覆盖到的小地方的样式进行优化'
);
$JWallpaper_Background_Optimal->setAttribute('class', 'joe_content joe_image');
$form->addInput($JWallpaper_Background_Optimal->multiMode());

$JShare_QQ_Image = new \Typecho\Widget\Helper\Form\Element\Text(
	'JShare_QQ_Image',
	NULL,
	'http://blog.yihang.info/favicon.ico',
	'QQ分享链接图片',
	'介绍：用于修改在QQ内分享时卡片链接显示的图片 <br/>
		 格式：图片地址'
);
$JShare_QQ_Image->setAttribute('class', 'joe_content joe_image');
$form->addInput($JShare_QQ_Image);

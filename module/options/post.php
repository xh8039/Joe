<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$JPostMetaReferrer = new \Typecho\Widget\Helper\Form\Element\Text(
	'JPostMetaReferrer',
	NULL,
	NULL,
	'文章页面Referrer属性',
	'介绍：设置为 no-referrer 可有效破解对方站点的图片、视频等防盗链功能，留空则浏览器会发送默认的Referrer请求头'
);
$JPostMetaReferrer->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPostMetaReferrer);

$JPostLinkRedirect = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPostLinkRedirect',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'是否开启文章外链重定向',
	'介绍：开启此功能后，非本站的链接将会重定向至内部链接，点击后延迟跳转，有利于SEO。如果对正常链接造成了影响，请关闭此功能'
);
$JPostLinkRedirect->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPostLinkRedirect);

$JPost_Title_Bold = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPost_Title_Bold',
	['on' => '开启', 'off' => '关闭（默认）'],
	'off',
	'是否开启文章标题粗体'
);
$JPost_Title_Bold->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPost_Title_Bold);

$JPost_Title_Center = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPost_Title_Center',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'是否开启文章标题居中',
	'介绍：开启后文章页和独立页面的文章标题将会居中显示'
);
$JPost_Title_Center->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPost_Title_Center);

$JPost_Header_Img_Switch = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPost_Header_Img_Switch',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'是否开启文章页面顶部大图',
	'介绍：开启后顶部大图将背景将使用文章缩略图 文字将使用文字标题 如果没有文章没有缩略图那么使用首页顶部大图和侧边栏随机一言充当文字'
);
$JPost_Header_Img_Switch->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPost_Header_Img_Switch);

$JPost_Header_Img = new \Typecho\Widget\Helper\Form\Element\Text(
	'JPost_Header_Img',
	NULL,
	NULL,
	'文章页顶部大图背景壁纸',
	'介绍：填写后将强制代替文章页顶部大图所有背景壁纸并忽略顶部大图开关<br>
	格式：图片地址 或 Base64地址<br>'
);
$JPost_Header_Img->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPost_Header_Img);

$JArticle_Bottom_Text = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JArticle_Bottom_Text',
	NULL,
	NULL,
	'文章底部自定义信息',
	'介绍：暂无 <br>
		 格式：一行代表一列<br>
		 例：<br>
		 本站资源多为网络收集，如涉及版权问题请及时与站长联系，我们会在第一时间内删除资源。<br>
		 本站用户发帖仅代表本站用户个人观点，并不代表本站赞同其观点和对其真实性负责。<br>
		 本站一律禁止以任何方式发布或转载任何违法的相关信息，访客发现请向站长举报。<br>
		 本站资源大多存储在云盘，如发现链接失效，请及时与站长联系，我们会第一时间更新。<br>
		 转载本网站任何内容，请按照转载方式正确书写本站原文地址。<br>'
);
$JArticle_Bottom_Text->setAttribute('class', 'joe_content joe_post');
$form->addInput($JArticle_Bottom_Text);

$JPost_Ad = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JPost_Ad',
	NULL,
	NULL,
	'文章页大屏广告',
	'介绍：请务必填写正确的格式 <br />
		格式：广告图片 || 广告链接（可为空） || 广告文字（可为空）（中间使用两个竖杠分隔，一行一个）<br />
		例如：https://puui.qpic.cn/media_img/lena/PICykqaoi_580_1680/0 || https://baidu.com || 广告'
);
$JPost_Ad->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPost_Ad);

$JPost_Record_Detection = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPost_Record_Detection',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'是否开启文章百度收录检测'
);
$JPost_Record_Detection->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPost_Record_Detection);

if (!empty(\Helper::options()->JPost_Record_Detection) && \Helper::options()->JPost_Record_Detection == 'on') {
	$BaiduRecordCookie = new \Typecho\Widget\Helper\Form\Element\Textarea(
		'BaiduRecordCookie',
		NULL,
		NULL,
		'百度收录检测请求 Cookie 标头',
		'介绍：检测百度是否收录指定文章时必须带有正确的 Cookie，否则会检测失败<br>
		获取方法：[<a href="https://www.baidu.com/s?wd=blog.yihang.info&rn=1&tn=json&ie=utf-8&cl=3&f=9" target="_blank">进入此网址</a>] 后打开浏览器开发者工具，再次刷新该网址的窗口，查看调试界面网络栏中的原始请求标头中的 Cookie 请求头的值，复制粘贴到这里即可'
	);
	$BaiduRecordCookie->setAttribute('class', 'joe_content joe_post');
	$form->addInput($BaiduRecordCookie);

	$BaiduRecordUserAgent = new \Typecho\Widget\Helper\Form\Element\Text(
		'BaiduRecordUserAgent',
		NULL,
		NULL,
		'百度收录检测请求 User-Agent 标头',
		'介绍：检测百度是否收录指定文章时必须带有正确的 User-Agent，否则会检测失败<br>
		获取方法：[<a href="https://www.baidu.com/s?wd=blog.yihang.info&rn=1&tn=json&ie=utf-8&cl=3&f=9" target="_blank">进入此网址</a>] 后打开浏览器开发者工具，再次刷新该网址的窗口，查看调试界面网络栏中的原始请求标头中的 User-Agent 请求头的值，复制粘贴到这里即可'
	);
	$BaiduRecordUserAgent->setAttribute('class', 'joe_content joe_post');
	$form->addInput($BaiduRecordUserAgent);

	$BaiduPushToken = new \Typecho\Widget\Helper\Form\Element\Text(
		'BaiduPushToken',
		NULL,
		NULL,
		'百度推送Token',
		'介绍：填写此处，前台文章页如果未收录，则会自动将当前链接推送给百度加快收录 <br />
			 其他：Token在百度收录平台注册账号获取'
	);
	$BaiduPushToken->setAttribute('class', 'joe_content joe_post');
	$form->addInput($BaiduPushToken);

	$BingPushToken = new \Typecho\Widget\Helper\Form\Element\Text(
		'BingPushToken',
		NULL,
		NULL,
		'必应推送Token',
		'介绍：填写此处，则会自动将当前链接推送给必应加快收录 <br />
			 其他：Token在必应收录平台注册账号获取'
	);
	$BingPushToken->setAttribute('class', 'joe_content joe_post');
	$form->addInput($BingPushToken);
}

$JArticle_Guide = new \Typecho\Widget\Helper\Form\Element\Select(
	'JArticle_Guide',
	array(
		'on' => '开启（默认）',
		'off' => '关闭',
	),
	'on',
	'是否开启文章导读目录模块',
	NULL
);
$JArticle_Guide->setAttribute('class', 'joe_content joe_post');
$form->addInput($JArticle_Guide->multiMode());

$JOverdue = new \Typecho\Widget\Helper\Form\Element\Text(
	'JOverdue',
	NULL,
	NULL,
	'是否开启文章更新时间大于多少天提示（仅针对文章有效）',
	'介绍：开启后如果文章在多少天内无任何修改，则进行提示 <br>
	填写示例：365'
);
$JOverdue->setAttribute('class', 'joe_content joe_post');
$form->addInput($JOverdue->multiMode());

$JEditor = new \Typecho\Widget\Helper\Form\Element\Select(
	'JEditor',
	array(
		'on' => '开启（默认）',
		'off' => '关闭',
	),
	'on',
	'是否启用Joe自定义编辑器',
	'介绍：开启后，文章编辑器将替换成Joe编辑器 <br>
		 其他：目前编辑器处于拓展阶段，如果想继续使用原生编辑器，关闭此项即可'
);
$JEditor->setAttribute('class', 'joe_content joe_post');
$form->addInput($JEditor->multiMode());

$JPrismTheme = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPrismTheme',
	array(
		'prism-a11y-dark.css' => 'prism-a11y-dark',
		'prism-atom-dark.css' => 'prism-atom-dark',
		'prism-base16-ateliersulphurpool.light.css' => 'prism-base16-ateliersulphurpool.light',
		'prism-cb.css' => 'prism-cb',
		'prism-coldark-cold.css' => 'prism-coldark-cold',
		'prism-coldark-dark.css' => 'prism-coldark-dark',
		'prism-coy-without-shadows.css' => 'prism-coy-without-shadows',
		'prism-darcula.css' => 'prism-darcula',
		'prism-dracula.css' => 'prism-dracula',
		'prism-duotone-dark.css' => 'prism-duotone-dark',
		'prism-duotone-earth.css' => 'prism-duotone-earth',
		'prism-duotone-forest.css' => 'prism-duotone-forest',
		'prism-duotone-light.css' => 'prism-duotone-light',
		'prism-duotone-sea.css' => 'prism-duotone-sea',
		'prism-duotone-space.css' => 'prism-duotone-space',
		'prism-ghcolors.css' => 'prism-ghcolors',
		'prism-gruvbox-dark.css' => 'prism-gruvbox-dark',
		'prism-gruvbox-light.css' => 'prism-gruvbox-light',
		'prism-holi-theme.css' => 'prism-holi-theme',
		'prism-hopscotch.css' => 'prism-hopscotch',
		'prism-lucario.css' => 'prism-lucario',
		'prism-material-dark.css' => 'prism-material-dark',
		'prism-material-light.css' => 'prism-material-light',
		'prism-material-oceanic.css' => 'prism-material-oceanic',
		'prism-night-owl.css' => 'prism-night-owl',
		'prism-nord.css' => 'prism-nord',
		'prism-one-dark.css' => 'prism-one-dark（默认）',
		'prism-one-light.css' => 'prism-one-light',
		'prism-pojoaque.css' => 'prism-pojoaque',
		'prism-shades-of-purple.css' => 'prism-shades-of-purple',
		'prism-solarized-dark-atom.css' => 'prism-solarized-dark-atom',
		'prism-synthwave84.css' => 'prism-synthwave84',
		'prism-vs.css' => 'prism-vs',
		'prism-vsc-dark-plus.css' => 'prism-vsc-dark-plus',
		'prism-xonokai.css' => 'prism-xonokai',
		'prism-z-touch.css' => 'prism-z-touch',

		// 'prism.css' => 'prism（默认）',
		// 'prism-dark.css' => 'prism-dark',
		// 'prism-okaidia.css' => 'prism-okaidia',
		// 'prism-solarizedlight.css' => 'prism-solarizedlight',
		// 'prism-tomorrow.css' => 'prism-tomorrow',
		// 'prism-twilight.css' => 'prism-twilight',
		// 'prism-onedark-1.0.0.css' => 'prism-onedark2',
	),
	'prism-one-dark.css',
	'选择一款您喜欢的代码高亮样式',
	'介绍：用于修改代码块的高亮风格 <br>
		 其他：如果您有其他样式，可通过源代码修改此项，引入您的自定义样式链接'
);
$JPrismTheme->setAttribute('class', 'joe_content joe_post');
$form->addInput($JPrismTheme->multiMode());

$JRewardTitle = new \Typecho\Widget\Helper\Form\Element\Text(
	'JRewardTitle',
	NULL,
	'文章很赞！支持一下吧',
	'文章赞赏标题',
	'例如：文章很赞！支持一下吧'
);
$JRewardTitle->setAttribute('class', 'joe_content joe_post');
$form->addInput($JRewardTitle);

$JWeChatRewardImg = new \Typecho\Widget\Helper\Form\Element\Text(
	'JWeChatRewardImg',
	NULL,
	NULL,
	'微信赞赏收款码',
	'介绍：微信赞赏收款码链接，不填写则不启用<br>格式：图片文件直链地址 或 Base64地址'
);
$JWeChatRewardImg->setAttribute('class', 'joe_content joe_post');
$form->addInput($JWeChatRewardImg);

$JAlipayRewardImg = new \Typecho\Widget\Helper\Form\Element\Text(
	'JAlipayRewardImg',
	NULL,
	NULL,
	'支付宝赞赏收款码',
	'介绍：支付宝赞赏收款码链接，不填写则不启用<br>格式：图片文件直链地址 或 Base64地址'
);
$JAlipayRewardImg->setAttribute('class', 'joe_content joe_post');
$form->addInput($JAlipayRewardImg);

$JQQRewardImg = new \Typecho\Widget\Helper\Form\Element\Text(
	'JQQRewardImg',
	NULL,
	NULL,
	'QQ赞赏收款码',
	'介绍：QQ赞赏收款码链接，不填写则不启用<br>格式：图片文件直链地址 或 Base64地址'
);
$JQQRewardImg->setAttribute('class', 'joe_content joe_post');
$form->addInput($JQQRewardImg);

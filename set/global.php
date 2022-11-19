<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$JStorageUrl = new Typecho_Widget_Helper_Form_Element_Text(
	'JStorageUrl',
	null,
	null,
	'自定义存储空间资源',
	'介绍：将本主题所需要的CSS、JS等资源文件使用某个站点来提供，以便节省服务器宽带 提升小型服务器加载速度<br>
		 注意：必须保证对方站点同样为再续前缘版并且版本一致<br>
		 格式：blog.bri6.cn（此站点虽是同款，然已启用防盗链，无需再试了）<br>
		 其他：本站同款站点列表 <a target="_blank" href="http://web.bri6.cn/api/joe/JoeLinks.php">web.bri6.cn/api/joe/JoeLinks.php</a>'
);
$JStorageUrl->setAttribute('class', 'joe_content joe_global');
$form->addInput($JStorageUrl);

$JPendant_SSL = new Typecho_Widget_Helper_Form_Element_Select(
	'JPendant_SSL',
	array('on' => '开启（默认）', 'off' => '关闭'),
	'on',
	'是否SSL安全认证图标',
	'介绍：开启后站点右下角将会显示SSL安全认证图标'
);
$JPendant_SSL->setAttribute('class', 'joe_content joe_global');
$form->addInput($JPendant_SSL->multiMode());

$JPrevent = new Typecho_Widget_Helper_Form_Element_Select(
	'JPrevent',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启QQ、微信防红拦截',
	'介绍：开启后，如果在QQ里打开网站，则会提示跳转浏览器打开'
);
$JPrevent->setAttribute('class', 'joe_content joe_global');
$form->addInput($JPrevent->multiMode());

$JNavMaxNum = new Typecho_Widget_Helper_Form_Element_Select(
	'JNavMaxNum',
	array(
		'3' => '3个（默认）',
		'4' => '4个',
		'5' => '5个',
		'6' => '6个',
		'7' => '7个',
	),
	'3',
	'选择导航栏最大显示的个数',
	'介绍：用于设置最大多少个后，以更多下拉框显示'
);
$JNavMaxNum->setAttribute('class', 'joe_content joe_global');
$form->addInput($JNavMaxNum->multiMode());

$JCustomNavs = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomNavs',
	NULL,
	NULL,
	'导航栏自定义链接（非必填）',
	'介绍：用于自定义导航栏链接 <br />
		 格式：跳转文字 || 跳转链接（中间使用两个竖杠分隔）<br />
		 其他：一行一个，一行代表一个超链接 <br />
		 例如：<br />
			百度一下 || https://baidu.com <br />
			腾讯视频 || https://v.qq.com
		 '
);
$JCustomNavs->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomNavs);

$JFooter_Left = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JFooter_Left',
	NULL,
	'2021 - 2022 ©<a href="http://blog.bri6.cn">易航博客</a>丨技术支持：<a href="http://blog.bri6.cn" target="_blank">易航</a>',
	'自定义底部栏左侧内容（非必填）',
	'介绍：用于修改全站底部左侧内容（wap端上方） <br>
		 例如：<style style="display:inline">2021 - 2022 ©<a href="{站点链接}">{站点标题}</a>丨技术支持：<a href="http://blog.bri6.cn" target="_blank">易航</a></style>'
);
$JFooter_Left->setAttribute('class', 'joe_content joe_global');
$form->addInput($JFooter_Left);

$JFooter_Right = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JFooter_Right',
	NULL,
	'<a href="http://blog.bri6.cn/feed/" target="_blank" rel="noopener noreferrer">RSS</a>
		 <a href="http://blog.bri6.cn/sitemap.xml" target="_blank" rel="noopener noreferrer" style="margin-left: 15px">MAP</a>
		 <a href="https://beian.miit.gov.cn/#/Integrated/index" target="_blank" rel="noopener noreferrer" style="margin-left: 15px">冀ICP备2021010323号</a>',
	'自定义底部栏右侧内容（非必填）',
	'介绍：用于修改全站底部右侧内容（wap端下方） <br>
		 例如：&lt;a href="/"&gt;首页&lt;/a&gt; &lt;a href="/"&gt;关于&lt;/a&gt;'
);
$JFooter_Right->setAttribute('class', 'joe_content joe_global');
$form->addInput($JFooter_Right);

$JDocumentTitle = new Typecho_Widget_Helper_Form_Element_Text(
	'JDocumentTitle',
	NULL,
	'网站崩溃了...',
	'网页被隐藏时显示的标题',
	'介绍：在PC端切换网页标签时，网站标题显示的内容。如果不填写，则默认不开启 <br />
		 注意：严禁加单引号或双引号！！！否则会导致网站出错！！'
);
$JDocumentTitle->setAttribute('class', 'joe_content joe_global');
$form->addInput($JDocumentTitle);

$JCustomCSS = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomCSS',
	NULL,
	NULL,
	'自定义CSS（非必填）',
	'介绍：请填写自定义CSS内容，填写时无需填写style标签。<br />
		 其他：如果想修改主题色、卡片透明度等，都可以通过这个实现 <br />
		 例如：body { --theme: #ff6800; --background: rgba(255,255,255,0.85) }'
);
$JCustomCSS->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomCSS);

$JCustomScript = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomScript',
	NULL,
	NULL,
	'自定义JS（非必填）',
	'介绍：请填写自定义JS内容，例如网站统计等，填写时无需填写script标签。'
);
$JCustomScript->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomScript);

$JCustomHeadEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomHeadEnd',
	NULL,
	NULL,
	'自定义增加&lt;head&gt;&lt;/head&gt;里内容（非必填）',
	'介绍：此处用于在&lt;head&gt;&lt;/head&gt;标签里增加自定义内容 <br />
		 例如：可以填写引入第三方css、js等等'
);
$JCustomHeadEnd->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomHeadEnd);

$JCustomBodyEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomBodyEnd',
	NULL,
	NULL,
	'自定义&lt;body&gt;&lt;/body&gt;末尾位置内容（非必填）',
	'介绍：此处用于填写在&lt;body&gt;&lt;/body&gt;标签末尾位置的内容 <br>
		 例如：可以填写引入第三方js脚本等等'
);
$JCustomBodyEnd->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomBodyEnd);

$JBirthDay = new Typecho_Widget_Helper_Form_Element_Text(
	'JBirthDay',
	NULL,
	NULL,
	'网站成立日期（非必填）',
	'介绍：用于显示当前站点已经运行了多少时间。<br>
		 注意：填写时务必保证填写正确！例如：2021/1/1 00:00:00 <br>
		 其他：不填写则不显示，若填写错误，则不会显示计时'
);
$JBirthDay->setAttribute('class', 'joe_content joe_global');
$form->addInput($JBirthDay);

$JCustomFont = new Typecho_Widget_Helper_Form_Element_Text(
	'JCustomFont',
	NULL,
	NULL,
	'自定义网站字体（非必填）',
	'介绍：用于修改全站字体，填写则使用引入的字体，不填写使用默认字体 <br>
		 格式：字体URL链接（推荐使用woff格式的字体，网页专用字体格式） <br>
		 注意：字体文件一般有几兆，建议使用cdn链接'
);
$JCustomFont->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomFont);

$JCustomAvatarSource = new Typecho_Widget_Helper_Form_Element_Text(
	'JCustomAvatarSource',
	NULL,
	NULL,
	'自定义头像源（非必填）',
	'介绍：用于修改全站头像源地址 <br>
		 例如：https://gravatar.ihuan.me/avatar/ <br>
		 其他：非必填，默认头像源为https://gravatar.helingqi.com/wavatar/ <br>
		 注意：填写时，务必保证最后有一个/字符，否则不起作用！'
);
$JCustomAvatarSource->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomAvatarSource);
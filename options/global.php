<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$Turbolinks = new \Typecho\Widget\Helper\Form\Element\Select(
	'Turbolinks',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'Turbolinks单页模式',
	'介绍：目前处于测试阶段，Turbolinks 可以更快地导航您的 Web 应用程序。获得单页应用程序的性能优势，而不会增加客户端 JavaScript 框架的复杂性。使用 HTML 在服务器端呈现您的视图，并像往常一样链接到页面。当您点击链接时，Turbolinks 会自动获取该页面，交换并合并该页面，所有这些都不会产生整个页面加载的成本，开启后网站全局音乐不会被打断'
);
$Turbolinks->setAttribute('class', 'joe_content joe_global');
$form->addInput($Turbolinks);

$JThemeMode = new \Typecho\Widget\Helper\Form\Element\Select(
	'JThemeMode',
	['auto' => '早6晚7自动切换', 'light' => '日间亮色主题（默认）', 'night' => '夜间深色主题'],
	'light',
	'默认主题风格',
	'介绍：此处设置为默认风格，实际显示风格以用户设置优先。如需固定风格，则关闭还需下方主题切换按钮'
);
$JThemeMode->setAttribute('class', 'joe_content joe_global');
$form->addInput($JThemeMode);

$JThemeModeSwitch = new \Typecho\Widget\Helper\Form\Element\Select(
	'JThemeModeSwitch',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'昼夜模式切换功能',
	'介绍：关闭后用户将无法手动切换昼夜模式'
);
$JThemeModeSwitch->setAttribute('class', 'joe_content joe_global');
$form->addInput($JThemeModeSwitch);

$JStaticAssetsUrl = new \Typecho\Widget\Helper\Form\Element\Text(
	'JStaticAssetsUrl',
	null,
	null,
	'自定义存储空间资源',
	'介绍：将本主题所需要的CSS、JS等资源文件使用某个站点来提供，以便节省服务器宽带 提升小型服务器加载速度<br>
	注意：必须保证对方站点同样为再续前缘版并且版本一致<br>
	例如：//storage.bri6.cn/typecho/usr/themes/Joe@1.33<br>
	其他：<a target="_blank" href="http://auth.bri6.cn/server/joe/sitelist">本站同款站点列表</a>'
);
$JStaticAssetsUrl->setAttribute('class', 'joe_content joe_global');
$form->addInput($JStaticAssetsUrl);

$JCdnUrl = new \Typecho\Widget\Helper\Form\Element\Text(
	'JCdnUrl',
	NULL,
	NULL,
	'公共静态资源CDN接口',
	'
	<span>介绍：留空则使用本地资源，不懂请勿乱填。若您的站点宽带不高，可从下方接口列表选择或提供自己的接口</span><br>
	<span>格式：URL接口 || 版本号分隔符</span><br>
	<span>注意：版本号分隔符不填写则默认为 / ，JsDelivr类型的需要使用 @</span><br>
	<span>BootCDN：https://cdn.bootcdn.net/ajax/libs/</span><br>
	<span>360CDN：https://lib.baomitu.com/</span><br>
	<span>Staticfile CDN：https://cdn.staticfile.net/</span><br>
	<span>字节跳动（距上次更新已两年多）：https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/</span><br>
	<span>Zstatic（又拍云赞助）：https://s4.zstatic.net/ajax/libs/</span><br>
	<span>7ED Services（www.7ed.net）：https://use.sevencdn.com/ajax/libs/</span><br>
	<span>渺软公益CDN回源JsDelivr（cdn.onmicrosoft.cn）：https://jsd.onmicrosoft.cn/npm/ || @</span><br>
	<span>渺软公益CDN回源UNPKG（cdn.onmicrosoft.cn）：https://npm.onmicrosoft.cn/</span><br>
	<span>渺软公益CDN回源CDNJS（cdn.onmicrosoft.cn）：https://cdnjs.onmicrosoft.cn/ajax/libs/</span><br>
	<span>南方科技大学：https://mirrors.sustech.edu.cn/cdnjs/ajax/libs/</span><br>
	<span>360CDN: https://cdn.baomitu.com</span><br>
	<span>JsDelivr：https://cdn.jsdelivr.net/npm/ || @</span><br>
	<span>Google Hosted Libraries（国内用不了）：https://ajax.googleapis.com/ajax/libs/</span><br>
	<span>CDNJS（国内不稳定）：https://cdnjs.cloudflare.com/ajax/libs/</span><br>
	<span>烧饼博客（CDNJS镜像）：https://cdnjs.loli.net/ajax/libs/</span><br>
	'
);
$JCdnUrl->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCdnUrl);

$JNavMaxNum = new \Typecho\Widget\Helper\Form\Element\Select(
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

$JMoreNavs = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JMoreNavs',
	NULL,
	NULL,
	'导航栏更多链接（非必填）',
	'介绍：用于添加更多导航栏链接 <br />
	格式：跳转文字 || 跳转链接（中间使用两个竖杠分隔）<br />
	其他：一行一个，一行代表一个超链接 <br />
	例如：<br />
	百度一下 || https://baidu.com <br />
	腾讯视频 || https://v.qq.com
	'
);
$JMoreNavs->setAttribute('class', 'joe_content joe_global');
$form->addInput($JMoreNavs);

$JCustomNavs = new \Typecho\Widget\Helper\Form\Element\Textarea(
	'JCustomNavs',
	NULL,
	NULL,
	'全新自定义导航栏（非必填）',
	'介绍：可随意设置导航链接和导航文字加图标的全新导航栏丨<a target="_blank" href="http://blog.bri6.cn/archives/286.html">查看官网教程</a>'
);
$JCustomNavs->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomNavs);

$JCustomFont = new \Typecho\Widget\Helper\Form\Element\Text(
	'JCustomFont',
	NULL,
	NULL,
	'自定义网站字体（非必填）',
	'介绍：用于修改全站字体，填写则使用引入的字体，不填写使用默认字体 <br>
		 格式：字体URL链接（推荐使用woff2格式的字体，网页专用字体格式，占用空间小，加载速度更快） <br>
		 注意：字体文件一般能上MB大小，建议使用cdn链接，如果和本站不是同一个域名，则需要远程资源URL响应允许跨域的响应头规则'
);
$JCustomFont->setAttribute('class', 'joe_content joe_global');
$form->addInput($JCustomFont);

$JCustomAvatarSource = new \Typecho\Widget\Helper\Form\Element\Text(
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

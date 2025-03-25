<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

$UISoundEffect = new \Typecho\Widget\Helper\Form\Element\Select(
	'UISoundEffect',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'界面交互音效'
);
$UISoundEffect->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($UISoundEffect->multiMode());

$UITickEffectUrl = new \Typecho\Widget\Helper\Form\Element\Text(
	'UITickEffectUrl',
	NULL,
	'EffectTick.ogg',
	'界面点击音效',
	'填写URL地址，不填写则关闭，推荐使用OGG音频文件，解析快，声音播放延迟低'
);
$UITickEffectUrl->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($UITickEffectUrl->multiMode());

$NProgressJS = new \Typecho\Widget\Helper\Form\Element\Select(
	'NProgressJS',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'页面顶部链接加载进度条动画'
);
$NProgressJS->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($NProgressJS->multiMode());

$JFestivalLantern = new \Typecho\Widget\Helper\Form\Element\Text(
	'JFestivalLantern',
	NULL,
	NULL,
	'节日灯笼挂件文字',
	'介绍：填写后网站顶部将会始终展示新年灯笼挂件，增添网站新年氛围<br>
	示例：新年快乐<br>
	关于：春节，亦称农历新年，是中国最重要的传统节日之一，春节时期往往伴随着阖家团圆和一个新的开始，春节不仅是一个普通的节日，它凝聚了中华民族的历史记忆和文化情感，承载着深厚的文化意义和社会价值'
);
$JFestivalLantern->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JFestivalLantern->multiMode());

$JLogo_Light_Effect = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLogo_Light_Effect',
	['on' => '开启（默认）', 'off' => '关闭'],
	'on',
	'LOGO扫光效果',
	'介绍：开启后顶栏的LOGO图标将会有扫光效果'
);
$JLogo_Light_Effect->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JLogo_Light_Effect->multiMode());

$loading_list = [];
$loading_scan = scandir(JOE_ROOT . 'module/loading');
if (is_array($loading_scan)) {
	foreach ($loading_scan as $value) {
		$loading_file = JOE_ROOT . 'module/loading/' . $value;
		if (!is_file($loading_file)) continue;
		$loading_name = pathinfo($value, PATHINFO_FILENAME);
		$loading_content = file_get_contents(JOE_ROOT . 'module/loading/' . $value);
		if (preg_match('/\<\!\-\-(.+)\-\-\>/', $loading_content, $loading_value_match)) {
			$loading_value = trim($loading_value_match[1]);
		} else {
			$loading_value = $loading_name;
		}
		$loading_list[$loading_name] = $loading_value;
	}
}
$loading_list['off'] = '关闭';
$default_loading = isset($loading_list['concise']) ? 'concise' : array_key_first($loading_list);
$JLoading = new \Typecho\Widget\Helper\Form\Element\Select(
	'JLoading',
	$loading_list,
	$default_loading,
	'全局加载动画',
	'介绍：网站全局页面加载 Loading 动画，开启后可防止使用谷歌内核的浏览器出现闪动问题'
);
$JLoading->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JLoading->multiMode());

$FirstLoading = new \Typecho\Widget\Helper\Form\Element\Select(
	'FirstLoading',
	['on' => '开启', 'off' => '关闭（默认）'],
	'off',
	'仅首次加载动画',
	'介绍：只在用户首次进入网站时展示加载动画，开启后可防止使用谷歌内核的浏览器出现闪动问题'
);
$FirstLoading->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($FirstLoading->multiMode());

$JArticleListAtropos = new \Typecho\Widget\Helper\Form\Element\Select(
	'JArticleListAtropos',
	['off' => '关闭（默认）', 'on' => '开启'],
	'off',
	'是否开启文章列表选中动画',
	'介绍：开启后首页和搜索页面展示的文章列表中文章被鼠标移入或者选中则会出现浮起动画'
);
$JArticleListAtropos->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JArticleListAtropos->multiMode());

$JPendant_SSL = new \Typecho\Widget\Helper\Form\Element\Select(
	'JPendant_SSL',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启SSL安全认证图标',
	'介绍：开启后站点右下角将会显示SSL安全认证图标'
);
$JPendant_SSL->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JPendant_SSL->multiMode());

$JGrey_Model = new \Typecho\Widget\Helper\Form\Element\Select(
	'JGrey_Model',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启哀悼模式',
	'介绍：开启后全站都显示灰色，为逝者默哀！'
);
$JGrey_Model->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JGrey_Model->multiMode());

$JHeaderCounter = new \Typecho\Widget\Helper\Form\Element\Select(
	'JHeaderCounter',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启顶部浏览进度条',
	'介绍：开启后页面顶部位置将会展示屏幕浏览进度条'
);
$JHeaderCounter->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JHeaderCounter->multiMode());

$JFooter_Fish = new \Typecho\Widget\Helper\Form\Element\Select(
	'JFooter_Fish',
	array('off' => '关闭（默认）', 'on' => '开启'),
	'off',
	'是否开启底部鱼群跳跃（消耗性能，可能会卡）',
	'介绍：开启后页面底部位置将会展示灵动的鱼群跳跃，增添网站灵动气氛'
);
$JFooter_Fish->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JFooter_Fish->multiMode());

$JListAnimate = new \Typecho\Widget\Helper\Form\Element\Select(
	'JListAnimate',
	array(
		'off' => '关闭（默认）',
		'animate__bounce' => '弹起',
		'animate__flash' => '闪光',
		'animate__pulse' => '脉搏',
		'animate__rubberBand' => '橡皮筋',
		'animate__headShake' => '摇头',
		'animate__swing' => '摆动',
		'animate__tada' => 'tada',
		'animate__wobble' => '晃动',
		'animate__jello' => '果冻',
		'animate__heartBeat' => '心跳',
		'animate__bounceIn' => '弹跳入',
		'animate__bounceInDown' => '向下弹跳',
		'animate__bounceInLeft' => '向左弹跳',
		'animate__bounceInRight' => '向右弹跳',
		'animate__bounceInUp' => '向上弹跳',
		'animate__bounceOut' => '跳出',
		'animate__bounceOutDown' => '向下弹跳',
		'animate__bounceOutLeft' => '向左反弹',
		'animate__bounceOutRight' => '向右弹起',
		'animate__bounceOutUp' => '弹起来',
		'animate__fadeIn' => '淡入',
		'animate__fadeInDown' => '向下淡入',
		'animate__fadeInDownBig' => '向下长淡入',
		'animate__fadeInLeft' => '向左淡入',
		'animate__fadeInLeftBig' => '向左长淡入',
		'animate__fadeInRight' => '向右淡入',
		'animate__fadeInRightBig' => '向右长淡入',
		'animate__fadeInUp' => '向上淡入',
		'animate__fadeInUpBig' => '向上长淡入',
		'animate__fadeOut' => '淡出',
		'animate__fadeOutDown' => '向下淡出',
		'animate__fadeOutDownBig' => '向下长淡出',
		'animate__fadeOutLeft' => '向左淡出',
		'animate__fadeOutLeftBig' => '向左长淡出',
		'animate__fadeOutRight' => '向右淡出',
		'animate__fadeOutRightBig' => '向右长淡出',
		'animate__fadeOutUp' => '向上淡出',
		'animate__fadeOutUpBig' => '向上长淡出',
		'animate__flip' => '翻转',
		'animate__flipInX' => 'X轴翻转',
		'animate__flipInY' => 'Y轴翻转',
		'animate__flipOutX' => 'X轴淡出翻转',
		'animate__flipOutY' => 'Y轴淡出翻转',
		'animate__rotateIn' => '旋转淡入',
		'animate__rotateInDownLeft' => '从左旋转向下淡入',
		'animate__rotateInDownRight' => '从右旋转向下淡入',
		'animate__rotateInUpLeft' => '从左旋转向上淡入',
		'animate__rotateInUpRight' => '从右旋转向上淡入',
		'animate__rotateOut' => '旋转淡出',
		'animate__rotateOutDownLeft' => '从左旋转向下淡出',
		'animate__rotateOutDownRight' => '从右旋转向下淡出',
		'animate__rotateOutUpLeft' => '从左旋转向上淡出',
		'animate__rotateOutUpRight' => '从右旋转向上淡出',
		'animate__hinge' => '晃动掉下去',
		'animate__jackInTheBox' => 'jackInTheBox',
		'animate__rollIn' => '从左滚动过来',
		'animate__rollOut' => '向右滚动出去',
		'animate__zoomIn' => '拉近',
		'animate__zoomInDown' => '从上拉近',
		'animate__zoomInLeft' => '从左拉近',
		'animate__zoomInRight' => '从右拉近',
		'animate__zoomInUp' => '从下拉近',
		'animate__zoomOut' => '缩小',
		'animate__zoomOutDown' => '往下缩小',
		'animate__zoomOutLeft' => '往左缩小',
		'animate__zoomOutRight' => '往右缩小',
		'animate__zoomOutUp' => '往上缩小',
		'animate__slideInDown' => '从下滑入',
		'animate__slideInLeft' => '从左滑入',
		'animate__slideInRight' => '从右滑入',
		'animate__slideInUp' => '从下滑入',
		'animate__slideOutDown' => '往下滑出',
		'animate__slideOutLeft' => '往左滑出',
		'animate__slideOutRight' => '往右滑出',
		'animate__slideOutUp' => '往上滑出',
	),
	'off',
	'文章列表动画',
	'介绍：开启后，文章列表将会显示所选择的炫酷动画'
);
$JListAnimate->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JListAnimate->multiMode());

$JCursorEffects = new \Typecho\Widget\Helper\Form\Element\Select(
	'JCursorEffects',
	[
		'off' => '关闭（默认）',
		'cursor3.js' => '点击文字',
		'firework.js' => '点击烟花',
		'cursor1.js' => '点击爆开-小',
		'cursor2.js' => '点击爆开-大',
		'cursor4.js' => '点击爱心-随机',
		'cursor5.js' => '点击爱心-红色',
		'cursor6.js' => '跟随星星',
		'cursor7.js' => '跟随残影',
		'cursor8.js' => '跟随黄脸-淘气',
		'cursor9.js' => '跟随黄脸-哭笑',
		'cursor10.js' => '跟随水泡泡',
		'cursor11.js' => '跟随雪花',
	],
	'off',
	'选择鼠标特效',
	'介绍：用于开启炫酷的鼠标特效'
);
$JCursorEffects->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JCursorEffects->multiMode());

$JDocumentTitle = new \Typecho\Widget\Helper\Form\Element\Text(
	'JDocumentTitle',
	NULL,
	NULL,
	'网页被隐藏时显示的标题',
	'介绍：在PC端切换网页标签时，网站标题显示的内容。如果不填写，则默认不开启 <br />
		 注意：严禁加单引号或双引号！！！否则会导致网站出错！！'
);
$JDocumentTitle->setAttribute('class', 'joe_content joe_decoration');
$form->addInput($JDocumentTitle);

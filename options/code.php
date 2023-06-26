<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$JCustomAside = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomAside',
	NULL,
	NULL,
	'自定义侧边栏模块 - PC',
	'介绍：用于自定义侧边栏模块 <br />
		 格式：请填写前端代码，不会写请勿填写 <br />
		 例如：您可以在此处添加搜索框、时间、宠物、恋爱计时等等'
);
$JCustomAside->setAttribute('class', 'joe_content joe_code');
$form->addInput($JCustomAside);

$JCustomCSS = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomCSS',
	NULL,
	NULL,
	'自定义CSS（非必填）',
	'介绍：请填写自定义CSS内容，填写时无需填写style标签。<br />
		 其他：如果想修改主题色、卡片透明度等，都可以通过这个实现 <br />
		 例如：body { --theme: #ff6800; --background: rgba(255,255,255,0.85) }'
);
$JCustomCSS->setAttribute('class', 'joe_content joe_code');
$form->addInput($JCustomCSS);

$JCustomScript = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomScript',
	NULL,
	NULL,
	'自定义JS（非必填）',
	'介绍：请填写自定义JS内容，例如网站统计等，填写时无需填写script标签。'
);
$JCustomScript->setAttribute('class', 'joe_content joe_code');
$form->addInput($JCustomScript);

$JCustomHeadEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomHeadEnd',
	NULL,
	NULL,
	'自定义增加&lt;head&gt;&lt;/head&gt;里内容（非必填）',
	'介绍：此处用于在&lt;head&gt;&lt;/head&gt;标签里增加自定义内容 <br />
		 例如：可以填写引入第三方css、js等等'
);
$JCustomHeadEnd->setAttribute('class', 'joe_content joe_code');
$form->addInput($JCustomHeadEnd);

$JCustomBodyEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
	'JCustomBodyEnd',
	NULL,
	NULL,
	'自定义&lt;body&gt;&lt;/body&gt;末尾位置内容（非必填）',
	'介绍：此处用于填写在&lt;body&gt;&lt;/body&gt;标签末尾位置的内容 <br>
		 例如：可以填写引入第三方js脚本等等'
);
$JCustomBodyEnd->setAttribute('class', 'joe_content joe_code');
$form->addInput($JCustomBodyEnd);
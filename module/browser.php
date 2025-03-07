<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>请使用浏览器打开 - <?= $this->options->title ?></title>
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="wap-font-scale" content="no">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<style type="text/css">
		html {
			color: #000;
			overflow-y: scroll;
			-webkit-text-size-adjust: 100%;
			-ms-text-size-adjust: 100%
		}

		html * {
			outline: 0;
			-webkit-text-size-adjust: none;
			-webkit-tap-highlight-color: transparent
		}

		body,
		html {
			font-family: sans-serif
		}

		a:hover {
			text-decoration: underline
		}

		a,
		ins {
			text-decoration: none
		}

		#app,
		body,
		html {
			width: 100%;
			height: 100vh;
			margin: 0;
			padding: 0
		}

		.ios-wechat-qq {
			height: 100%;
			width: 100%;
			position: relative
		}

		.left-22 {
			width: 38%;
			margin-top: 2.32rem;
			float: left
		}

		.right-33 {
			width: 38%;
			margin-right: 10px;
			float: right
		}

		.description-1 {
			font-size: 15px;
			margin-left: 5px;
			margin-top: 30%;
			float: left
		}

		.description-1 .t-1 {
			color: #757575;
			letter-spacing: 0
		}

		.description-1 .t-2 {
			color: #0f0f0f;
			letter-spacing: 0
		}

		.line-1 {
			width: 14%;
			float: right
		}

		.line-2 {
			top: 5.33333rem;
			width: 8%;
			float: left
		}

		.description-2 {
			text-align: center;
			font-size: 15px;
			margin-left: 10px;
			margin-top: 10px;
			float: right
		}

		.download-area {
			display: -ms-flexbox;
			display: flex;
			flex-direction: row;
			-ms-flex-direction: row;
			width: 100%;
			max-width: 560px;
			position: fixed;
			bottom: 0;
			font-size: 12px;
			padding: 5%;
			background: #f9f9f9;
			border-radius: 4px;
			-ms-flex-align: center;
			align-items: center
		}

		.download-area .logo {
			width: 15%;
			margin-right: 5%
		}

		.download-area .t-1 {
			text-align: left;
			font-size: 14px;
			color: #fb7299
		}

		.download-area .t-2 {
			text-align: left;
			margin-top: .21333rem;
			font-size: 15px;
			color: #999
		}

		.download-area .button {
			font-size: 14px;
			color: #fff;
			width: 5rem;
			height: 1.5rem;
			line-height: 1.5rem;
			text-align: center;
			background: #fb7299;
			border-radius: 4px;
			margin-left: auto
		}

		.linear {
			background-image: url("https://external-30160.picsz.qpic.cn/632d2c7051d7d9d8764fb3f8983bce4c");
			height: 100%;
		}
	</style>
</head>

<body>
	<?php
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	// if (strpos($useragent, 'iphone') !== false || strpos($useragent, 'ipod') !== false) {
	//     $alert = '<img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rLrNvRzmibibqrjTFj5g2kzGyoQj3ViartAEQ/0" class="icon-safari" /> <span id="openm">Safari打开</span>';
	// } else
	if (strpos($useragent, 'micromessenger') !== false) {
		$alert = joe\theme_url('assets/images/browser/WeChat.jpg');
	?>
		<div style="width: 100%;height:3px;background-color:rgb(7, 193, 96);position:fixed;top:0px;z-index:9999999999;">
		</div>
	<?php
	} else {
		$alert = '//external-30160.picsz.qpic.cn/f5c4cd695818494d727d8715bcfe7239';
	}
	?>
	<div class="linear">
		<img src="<?php echo $alert ?>" width="100%" />
	</div>
	<div class="download-area">
		<img src="//external-30160.picsz.qpic.cn/60d9f46afebf468646b2e008a020d1a2" class="logo">
		<div>
			<div class="t-1">管家检测正常，请按上图提示打开。</div>
			<div width="10">您所访问的地址：<?= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] ?></div>
		</div>
	</div>
	<a style="display: none;" href="" id="vurl" rel="noreferrer"></a>
	<script>
		function openu(u) {
			document.getElementById("vurl").href = u;
			document.getElementById("vurl").click();
		}
		var url = window.location.href;
		document.querySelector('body').addEventListener('touchmove', function(event) {
			event.preventDefault();
		});
		if (navigator.userAgent.indexOf("QQ/") > -1) {
			openu("ucbrowser://" + url);
			openu("mttbrowser://url=" + url);
			openu("baiduboxapp://browse?url=" + url);
			openu("googlechrome://browse?url=" + url);
			document.getElementsByTagName('html')[0].onclick = function() {
				openu("ucbrowser://" + url);
				openu("mttbrowser://url=" + url);
				openu("baiduboxapp://browse?url=" + url);
				openu("googlechrome://browse?url=" + url);
			}
		}
	</script>
</body>

</html>
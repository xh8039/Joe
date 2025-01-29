<?php
if (!defined('__TYPECHO_ROOT_DIR__') || empty($_SERVER['HTTP_HOST'])) {
	http_response_code(404);
	exit;
}
?>
<!DOCTYPE html>
<html lang='zh-CN'>

<head>
	<meta name="referrer" content="no-referrer">
	<title>即将跳转到外部网站 - <?= $this->options->title ?></title>
	<meta content='noindex, nofollow' name='robots'>
	<meta charset='utf-8'>
	<link rel="shortcut icon" href="<?= $this->options->JFavicon ?>" />
	<meta content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1.0, user-scalable=no' name='viewport'>
	<link rel="stylesheet" media="all" href="https://cn-assets.gitee.com/assets/external_link/app-cdbd476d857bfad8751f35a067840adf.css" />
</head>

<body>
	<div class='gitee-external-link-wrapper'>
		<img class="logo-img" src="<?= $this->options->JLogo ?>" alt="Logo black" />
		<div class='content-box'>
			<div class='content-title'>即将跳转到外部网站</div>
			<div class='content-text'>您将要访问的链接不属于 <?= $this->options->title ?> ，请关注您的账号安全。</div>
			<div class='content-link'>
				<div class='external-link-href'></div>
			</div>
			<div style="background:#409eff" class='ui button orange external-link-btn'>继续前往</div>
		</div>
	</div>
</body>

</html>
<script>
	window.is_black = false;
	(function() {
		var e = document.querySelector(".external-link-href");
		var t = window.atob(new URLSearchParams(location.search).get("url"));
		if (t && (e.innerText = t, !window.is_black)) {
			var n = document.querySelector(".external-link-btn");
			n && n.addEventListener("click", function() {
				window.location.href = t
			})
		}
	}());
</script>
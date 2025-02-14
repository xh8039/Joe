<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<?php $this->need('module/head.php') ?>
</head>

<body>
	<?php $this->need('module/header.php') ?>
	<div id="Joe">
		<div class="joe_container">
			<main class="joe_main">
				<center>
					<div class="image-box"><img referrerpolicy="no-referrer" rel="noreferrer" src="https://gitee.com/static/errors/images/404.png"></div>
					<h3>您所访问的页面不存在！</h3>
					<p>资源不存在或没有访问权限，<a href="/" style="color: var(--theme);">点击这里</a>返回首页</p>
				</center>
			</main>
			<?php joe\isPc() ? $this->need('module/aside.php') : null ?>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
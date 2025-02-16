<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<?php $this->need('module/head.php') ?>
	<link rel="stylesheet" href="<?= joe\theme_url('assets/css/404.css') ?>">
</head>

<body class="error404">
	<?php $this->need('module/header.php') ?>
	<div id="Joe">
		<div class="joe_container">
			<main class="joe_main">
				<div class="f404"><img src="<?= joe\theme_url('assets/images/404.svg') ?>"></div>
				<div class="theme-box box-body main-search">
					<div class="search-input">
						<form method="get" class="padding-10 search-form" action="<?php $this->options->siteUrl(); ?>">
							<div class="line-form">
								<div class="search-input-text">
									<input type="text" name="s" class="line-form-input" tabindex="1" value="">
									<i class="line-form-line"></i>
									<div class="scale-placeholder" default="开启精彩搜索">开启精彩搜索</div>
									<div class="abs-right muted-color">
										<button type="submit" tabindex="2" class="null">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-search"></use>
											</svg>
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<center>
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
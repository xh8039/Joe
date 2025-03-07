<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<?php $this->setArchiveTitle('重置密码') ?>
	<?php $this->need('module/head.php') ?>
	<?php $this->need('module/user/head.php') ?>
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<?php $referer = empty($_GET['referer']) ? '/' : urlencode(strip_tags($_GET['referer'])); ?>
		<div class="joe_container">
			<main class="joe_main">
				<div class="card-body">
					<div class="title">
						<h4>重置密码</h4>
						<p>通过注册时的邮箱设置新密码</p>
					</div>
					<form id="user-form" operate="设置" action="<?= joe\root_relative_link($this->options->index . '/joe/api/user-retrieve') ?>" method="post">
						<div class="form-group mb-3" id="post1">
							<label>邮箱</label>
							<input class="form-control" type="email" name="email" maxlength="64" placeholder="请输入您的邮箱">
						</div>
						<div class="form-group mb-3">
							<label>新密码</label>
							<input class="form-control" type="password" name="password" minlength="6" maxlength="18" placeholder="请输入您的新密码">
						</div>
						<div class="form-group mb-3">
							<label>确认密码</label>
							<input class="form-control" type="password" name="confirm_password" minlength="6" maxlength="18" placeholder="请再次输入您的新密码">
						</div>
						<?php
						if (extension_loaded('gd')) {
						?>
							<div class="form-group">
								<label>图像验证码</label>
								<div class="input-group">
									<input type="text" class="form-control" id="captcha" minlength="4" maxlength="4" placeholder="请先填写图像验证码后再获取邮箱验证码">
									<img style="cursor:pointer;width:100px;" src="<?php $this->options->themeUrl('module/captcha.php') ?>" alt="验证码" onclick="this.src=this.src+'?d='+Math.random();" data-toggle="tooltip" title="点击刷新">
								</div>
							</div>
						<?php
						}
						?>
						<div class="form-group">
							<label>验证码</label>
							<div class="input-group">
								<input type="number" class="form-control" name="captcha" minlength="6" maxlength="6" placeholder="请输入邮箱验证码">
								<button class="btn" id="send-captcha" action="<?= $this->options->index ?>/joe/api/user-retrieve-captcha" type="button">获取验证码</button>
							</div>
						</div>
						<input type="hidden" name="referer" value="<?= $referer ?>">
						<button class="btn btn-primary" type="submit">设置新密码</button>
					</form>
				</div>
				<p class="text-muted">返回 <a href="<?= joe\user_url('login', $referer) ?>" class="text-dark ml-1"><b>登陆</b></a></p>
			</main>
			<?php if (joe\isPc()) $this->need('module/aside.php') ?>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$rememberName = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_name') ?? '');
$rememberMail = htmlspecialchars(\Typecho\Cookie::get('__typecho_remember_mail') ?? '');
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<?php $this->setArchiveTitle('账号注册') ?>
	<?php $this->need('module/head.php') ?>
	<?php $this->need('module/user/head.php') ?>
	<script src="<?= joe\theme_url('assets/js/joe.user.register.js') ?>"></script>
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<?php $referer = empty($_GET['referer']) ? '/' : urlencode(strip_tags($_GET['referer'])); ?>
		<div class="container">
			<div>
				<div class="card-body">
					<div class="title">
						<h4>账号注册</h4>
						<p>创建您的帐号，只需不到一分钟</p>
					</div>
					<div class="form-group mb-3">
						<label>昵称</label>
						<input class="form-control" type="text" id="nickname" maxlength="10" placeholder="请输入您要展示的昵称">
					</div>
					<div class="form-group mb-3">
						<label>账号（用于登录）</label>
						<input class="form-control" type="text" id="username" value="<?= $rememberName ?>" minlength="3" maxlength="30" placeholder="请输入 3 到 30 位的数字或字母">
					</div>
					<div class="form-group mb-3">
						<label>密码</label>
						<input class="form-control" type="password" id="password" minlength="6" maxlength="18" placeholder="请输入 6 到 18 位的密码">
					</div>
					<div class="form-group mb-3">
						<label>确认密码</label>
						<input class="form-control" type="password" id="confirm_password" minlength="6" maxlength="18" placeholder="请再次输入您的密码">
					</div>
					<div class="form-group mb-3">
						<label>邮箱（用于找回密码）</label>
						<input class="form-control" type="text" id="email" value="<?= $rememberMail ?>" placeholder="请输入邮箱" maxlength="64">
					</div>
					<?php
					if (joe\email_config()) {
					?>
						<div class="form-group">
							<label>邮箱验证码</label>
							<div class="input-group">
								<input type="text" class="form-control" id="captcha" placeholder="请输入邮箱验证码">
								<button id="send" class="btn" type="button">获取验证码</button>
							</div>
						</div>
					<?php
					}
					?>
					<button class="btn btn-light" id="register">注 册</button>
				</div>
				<?php
				if ($this->options->allowRegister) {
				?>
					<p class="text-muted">已有账号? <a href="/user/login<?= '?referer=' . $referer ?>" class="text-dark ml-1"><b>登陆</b></a></p>
				<?php
				}
				?>
			</div>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
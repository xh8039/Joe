<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}
$this->need('user/header.php');
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<title>账号注册 - <?php $this->options->title() ?></title>
	<?php $this->need('module/head.php'); ?>
	<link href="<?= joe\theme_url('assets/css/joe.user.css') ?>" rel="stylesheet" type="text/css" />
</head>

<body>
	<?php
	$this->need('module/header.php');
	$referer = empty($_GET['referer']) ? '/' : urlencode(strip_tags($_GET['referer']));
	?>
	<div class="container">
		<div>
			<div class="card-body">
				<div class="title">
					<h4>账号注册</h4>
					<p>创建您的帐号，只需不到一分钟</p>
				</div>
				<div class="form-group mb-3">
					<label>昵称（用于显示）</label>
					<input class="form-control" type="text" id="nickname" placeholder="输入昵称">
				</div>

				<div class="form-group mb-3">
					<label>账号（用于登录）</label>
					<input class="form-control" type="text" id="username" placeholder="输入账号">
				</div>

				<div class="form-group mb-3">
					<label>邮箱（用于找回密码）</label>
					<input class="form-control" type="text" id="email" placeholder="邮箱">
				</div>
				<?php
				if (joe\email_config()) {
				?>
					<div class="form-group">
						<label>邮箱验证码</label>
						<div class="input-group">
							<input type="text" class="form-control" id="code" placeholder="请输入邮箱验证码">
							<button id="send" class="btn" type="button">获取验证码</button>
						</div>
					</div>
				<?php
				}
				?>
				<div class="form-group mb-3">
					<label>密码</label>
					<input class="form-control" type="password" id="password" placeholder="输入密码">
				</div>

				<div class="form-group mb-3">
					<label>确认密码</label>
					<input class="form-control" type="password" id="cpassword" placeholder="输入确认密码">
				</div>
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
	<?php $this->need('module/footer.php'); ?>
	<script src="<?= joe\theme_url('assets/js/joe.user.register.js') ?>"></script>
</body>

</html>
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
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div id="Joe">
		<?php $referer = empty($_GET['referer']) ? '/' : urlencode(strip_tags($_GET['referer'])); ?>
		<div class="joe_container">
			<main class="joe_main">
				<div class="card-body">
					<div class="title">
						<h4>账号注册</h4>
						<p>创建您的帐号，只需不到一分钟</p>
						<p class="text-muted">已有账号? <a href="<?= joe\user_url('login', $referer) ?>" class="text-dark ml-1"><b>登陆</b></a></p>
					</div>
					<form id="user-form" operate="注册" action="<?= joe\root_relative_link($this->options->index . '/joe/api/user-register') ?>" method="post">
						<div class="form-group mb-3">
							<label>昵称</label>
							<input class="form-control" type="text" name="nickname" maxlength="10" placeholder="请输入您要显示的名称">
						</div>
						<div class="form-group mb-3">
							<label>账号（用于登录）</label>
							<input class="form-control" type="text" name="username" value="<?= $rememberName ?>" minlength="3" maxlength="30" placeholder="请输入 3 到 30 位的数字或字母">
						</div>
						<div class="form-group mb-3">
							<label>密码</label>
							<input class="form-control" type="password" name="password" minlength="6" maxlength="18" placeholder="请输入 6 到 18 位的密码">
						</div>
						<div class="form-group mb-3">
							<label>邮箱（用于找回密码）</label>
							<input class="form-control" type="email" name="email" value="<?= $rememberMail ?>" placeholder="请输入邮箱" maxlength="64">
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
						if (joe\email_config()) {
						?>
							<div class="form-group">
								<label>邮箱验证码</label>
								<div class="input-group">
									<input type="number" class="form-control" name="captcha" minlength="6" maxlength="6" placeholder="请输入邮箱验证码">
									<button action="<?= $this->options->index ?>/joe/api/user-register-captcha" id="send-captcha" class="btn" type="button">获取验证码</button>
								</div>
							</div>
						<?php
						}
						?>
						<input type="hidden" name="referer" value="<?= $referer ?>">
						<button class="btn btn-primary" type="submit">注 册</button>
					</form>
				</div>
			</main>
			<?php if (joe\isPc()) $this->need('module/aside.php') ?>
		</div>
		<?php $this->need('module/bottom.php'); ?>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
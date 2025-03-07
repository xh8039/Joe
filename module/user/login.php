<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<?php $this->setArchiveTitle('账号登陆') ?>
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
						<h4>用户登录</h4>
						<p>请输入帐号密码进行登录</p>
					</div>
					<form id="user-form" operate="登录" action="<?= joe\root_relative_link($this->options->index . '/joe/api/user-login') ?>" method="post">
						<div class="form-group">
							<label class="float-left">账号/邮箱</label>
							<?php
							if ($this->options->allowRegister) {
							?>
								<span class="text-muted" style="float: right;color:var(--routine);">没有账号吗？<a href="<?= joe\user_url('register', $referer) ?>" class="text-dark ml-1"><b>注册</b></a></span>
							<?php
							}
							?>
							<input class="form-control" type="text" name="username" minlength="3" maxlength="30" required placeholder="请输入您的账号/邮箱">
						</div>
						<div class="form-group">
							<label class="float-left" for="password">密码</label>
							<?php
							if ($this->options->JUserRetrieve == 'on' && joe\email_config()) {
							?>
								<a href="<?= joe\user_url('retrieve', $referer) ?>" style="float: right;color:var(--routine);">
									<small>忘记密码?</small>
								</a>
							<?php
							}
							?>
							<input class="form-control" type="password" name="password" minlength="6" maxlength="18" required placeholder="请输入您的密码">
						</div>
						<input type="hidden" name="referer" value="<?= $referer ?>">
						<button type="submit" class="btn btn-primary">登 录</button>
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
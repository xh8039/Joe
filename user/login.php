<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {
	http_response_code(404);
	exit;
}

?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<title>账号登陆 - <?php $this->options->title() ?></title>
	<?php $this->need('module/head.php') ?>
	<?php $this->need('user/head.php') ?>
	<link href="<?= joe\theme_url('assets/css/joe.user.css') ?>" rel="stylesheet" type="text/css" />
</head>

<body>
	<div id="Joe">
		<?php
		$this->need('module/header.php');
		$referer = empty($_GET['referer']) ? '/' : urlencode(strip_tags($_GET['referer']));
		?>
		<div class="container">
			<div>
				<div class="card-body">
					<div class="title">
						<h4>用户登录</h4>
						<p>请输入帐号密码进行登录</p>
					</div>
					<div class="form-group">
						<label>账号/邮箱</label>
						<input class="form-control" type="text" id="username" placeholder="请输入您的账号/邮箱">
					</div>

					<div class="form-group">
						<label class="float-left" for="password">密码</label>
						<?php
						if ($this->options->JUser_Forget == 'on') {
						?>
							<a href="/user/forget<?= '?referer=' . $referer ?>" class="text-muted float-right">
								<small>忘记密码?</small>
							</a>
						<?php
						}
						?>
						<input class="form-control" type="password" id="password" placeholder="请输入您的密码">
					</div>
					<button class="btn btn-light" id="login">登 录</button>
				</div>
				<?php
				if ($this->options->allowRegister) {
				?>
					<p class="text-muted">没有账号吗？<a href="/user/register<?= '?referer=' . $referer ?>" class="text-dark ml-1"><b>注册</b></a></p>
				<?php
				}
				?>
			</div>
		</div>
		<?php $this->need('module/footer.php'); ?>
		<script src="<?= joe\theme_url('assets/js/joe.user.login.js'); ?>"></script>
	</div>
	<?php $this->need('module/footer.php') ?>
</body>

</html>
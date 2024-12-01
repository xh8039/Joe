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
	<?php $this->need('module/header.php'); ?>
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
				<p class="text-muted">已有账号? <a href="./login<?php echo isset($_GET['from']) ? '?from=' . urlencode($_GET['from']) : ''; ?>" class="text-dark ml-1"><b>登陆</b></a></p>
			<?php
			}
			?>
		</div>
	</div>
	<?php $this->need('module/footer.php'); ?>
	<script>
		! function(t) {
			let from = '<?= isset($_GET['from']) ? $_GET['from'] : '' ?>';
			let btn = function(obj, msg, code) {
				obj.html(msg);
				obj.attr("disabled", code);
			}
			let countdown = 60;
			let setTime = function() {
				if (countdown == 0) {
					$("#send").html('获取验证码');
					$("#send").attr("disabled", false);
					$("#email").attr("disabled", false);
					countdown = 60;
					return;
				} else {
					$("#send").html(countdown + "秒后重可发");
					$("#email").attr("disabled", true);
					$("#send").attr("disabled", true);
					countdown--;
				}
				setTimeout(function() {
					setTime()
				}, 1000);
			}
			$("#send").click(function() {
				let email = $("#email").val();
				if (!email) return Qmsg.warning("请输入邮箱后发送验证码");
				$.ajax({
					url: '<?= Typecho_Common::url('user/api', Helper::options()->index) ?>',
					type: 'post',
					dataType: 'json',
					async: true,
					data: {
						action: 'reg_code',
						email: email
					},
					beforeSend: function() {
						btn($("#send"), "发送中...", true);
					},
					complete: function() {
						btn($("#send"), "获取验证码", false);
					},
					error: function() {
						btn($("#send"), "获取验证码", false);
						Qmsg.error("服务器繁忙");
					},
					success: function(res) {
						if (res.code == 1) {
							setTime();
							Qmsg.success("验证码已发送到您的邮箱");
						} else {
							Qmsg.warning(res.msg);
						}
					}
				});
			});
			$("#register").click(function() {
				let nickname = $("#nickname").val();
				let username = $("#username").val();
				let email = $("#email").val();
				let code = $("#code").val();
				let password = $("#password").val();
				let cpassword = $("#cpassword").val();
				if (!nickname) return Qmsg.warning("请输入昵称");
				if (!username) return Qmsg.warning("请输入用户名");
				if (!email) return Qmsg.warning("请输入邮箱");
				<?php
				if (joe\email_config()) {
				?>
					if (!code) return Qmsg.warning("请输入验证码");
				<?php
				}
				?>
				if (!password) return Qmsg.warning("请输入密码");
				if (!cpassword) return Qmsg.warning("请输入确认密码");
				if (cpassword != password) return Qmsg.warning("两次密码不一致");
				$.ajax({
					url: '<?= Typecho_Common::url('user/api', Helper::options()->index) ?>',
					type: 'post',
					dataType: 'json',
					async: true,
					data: {
						action: 'register',
						nickname: nickname,
						username: username,
						email: email,
						code: code,
						password: password,
						cpassword: cpassword
					},
					beforeSend: function() {
						btn($("#register"), "注册中...", true);
					},
					complete: function() {
						btn($("#register"), "注册", false);
					},
					error: function() {
						btn($("#register"), "注册", false);
						Qmsg.error("服务器繁忙");
					},
					success: function(res) {
						if (res.code == 1) {
							Qmsg.success("注册成功");
							setTimeout(function() {
								window.location.href = from ? from : "<?= Typecho_Common::url('/', Helper::options()->index) ?>";
							}, 1000);
						} else {
							Qmsg.warning(res.msg);
						}
					}
				});
			});
		}(window.jQuery)
	</script>
</body>

</html>
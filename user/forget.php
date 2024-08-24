<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}
$this->need('user/header.php');
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<title>重置密码 - <?php $this->options->title() ?></title>
	<?php $this->need('module/head.php'); ?>
	<link href="<?= joe\theme_url('assets/css/joe.user.css') ?>" rel="stylesheet" type="text/css" />
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div class="container">
		<div>
			<div class="card-body">
				<div class="title">
					<h4>重置密码</h4>
					<p>输入注册时的邮箱设置新密码</p>
				</div>
				<div class="form-group mb-3" id="post1">
					<label>邮箱</label>
					<input class="form-control" type="text" id="email" placeholder="邮箱">
				</div>
				<div class="form-group" id="post2">
					<label>验证码</label>
					<div class="input-group">
						<input type="text" class="form-control" id="code" placeholder="输入验证码">
						<button class="btn" id="send" type="button">获取验证码</button>
					</div>
				</div>
				<div class="form-group mb-3" id="new1" style="display:none">
					<label>新密码</label>
					<input class="form-control" type="password" id="password" placeholder="新密码">
				</div>
				<div class="form-group mb-3" id="new2" style="display:none">
					<label>确认密码</label>
					<input class="form-control" type="password" id="cpassword" placeholder="确认密码">
				</div>
				<button class="btn btn-light" id="check" type="submit">验 证</button>
				<button class="btn btn-light" id="forget" type="submit" style="display:none">设置密码</button>
			</div>
			<?php
			if ($this->options->allowRegister) {
			?>
				<p class="text-muted">返回 <a href="./login<?php echo isset($_GET['from']) ? '?from=' . urlencode($_GET['from']) : ''; ?>" class="text-dark ml-1"><b>登陆</b></a></p>
			<?php
			}
			?>
		</div>
	</div>
	<?php $this->need('module/footer.php'); ?>
	<script>
		! function(t) {
			let from = '<?php print $_GET['from'] ?>';
			let btn = function(obj, msg, code) {
				obj.html(msg);
				obj.attr("disabled", code);
			}
			let countdown = 60;
			let setTime = function() {
				if (countdown == 0) {
					$("#send").html(' 获取验证码 ');
					$("#send").attr("disabled", false);
					$("#email").attr("disabled", false);
					countdown = 60;
					return;
				} else {
					$("#send").html(" (" + countdown + "秒)后重可发 ");
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
					url: '<?php print Typecho_Common::url('user/api', Helper::options()->index) ?>',
					type: 'post',
					dataType: 'json',
					async: true,
					data: {
						action: 'forget_code',
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
			let state;
			$("#check").click(function() {
				let email = $("#email").val();
				let code = $("#code").val();
				if (!email) return Qmsg.warning("请输入邮箱");
				if (!code) return Qmsg.warning("请输入验证码");
				$.ajax({
					url: '<?php print Typecho_Common::url('user/api', Helper::options()->index) ?>',
					type: 'post',
					dataType: 'json',
					async: true,
					data: {
						action: 'forget_check',
						email: email,
						code: code
					},
					beforeSend: function() {
						btn($("#check"), "验证中...", true);
					},
					complete: function() {
						btn($("#check"), "验证", false);
					},
					error: function() {
						btn($("#check"), "验证", false);
						Qmsg.error("服务器繁忙");
					},
					success: function(res) {
						if (res.code == 1) {
							$("#post1").hide(100);
							$("#post2").hide(100);
							$("#post3").hide(100);
							$('#check').hide()
							$("#new1").show(100);
							$("#new2").show(100);
							$("#new3").show(100);
							$('#forget').show();
							Qmsg.success("验证通过，请设置新密码");
							t.state = res.state;
						} else {
							Qmsg.warning(res.msg);
						}
					}
				});
			});
			$("#forget").click(function() {
				let password = $("#password").val();
				let cpassword = $("#cpassword").val();
				if (!password) return Qmsg.warning("请输入密码");
				if (!cpassword) return Qmsg.warning("请输入确认密码");
				if (password != cpassword) return Qmsg.warning("两次密码不一致");
				$.ajax({
					url: '<?php print Typecho_Common::url('user/api', Helper::options()->index) ?>',
					type: 'post',
					dataType: 'json',
					async: true,
					data: {
						action: 'forget',
						state: t.state,
						password: password,
						cpassword: cpassword
					},
					beforeSend: function() {
						btn($("#forget"), "设置中...", true);
					},
					complete: function() {
						btn($("#forget"), "设置密码", false);
					},
					error: function() {
						btn($("#forget"), "设置密码", false);
						Qmsg.error("服务器繁忙");
					},
					success: function(res) {
						if (res.code == 1) {
							Qmsg.success("密码重置成功");
							setTimeout(function() {
								window.location.href = from ? from : "<?php print Typecho_Common::url('/', Helper::options()->index) ?>";
							}, 1500);
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
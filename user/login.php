<?php
if (!defined('__TYPECHO_ROOT_DIR__')) {http_response_code(404);exit;}
$this->need('user/header.php');
?>
<!DOCTYPE html>
<html lang="zh-cn">

<head>
	<title>账号登陆 - <?php $this->options->title() ?></title>
	<?php $this->need('module/head.php'); ?>
	<link href="<?= joe\theme_url('assets/css/joe.user.css') ?>" rel="stylesheet" type="text/css" />
</head>

<body>
	<?php $this->need('module/header.php'); ?>
	<div class="container">
		<div>
			<div class="card-body">
				<div class="title">
					<h4>密码登录</h4>
					<p>请输入帐号密码进行登录</p>
				</div>
				<div class="form-group">
					<label>邮箱/用户名</label>
					<input class="form-control" type="text" id="username" placeholder="输入手机/邮箱/用户名">
				</div>

				<div class="form-group">
					<label class="float-left" for="password">密码</label>
					<?php
					if ($this->options->JUser_Forget == 'on') {
					?>
						<a href="./forget<?php echo isset($_GET['from']) ? '?from=' . urlencode($_GET['from']) : ''; ?>" class="text-muted float-right">
							<small>忘记密码?</small>
						</a>
					<?php
					}
					?>
					<input class="form-control" type="password" id="password" placeholder="输入密码">
				</div>
				<button class="btn btn-light" id="login">登 录</button>
			</div>
			<?php
			if ($this->options->allowRegister) {
			?>
				<p class="text-muted">没有账号吗？<a href="./register<?php echo isset($_GET['from']) ? '?from=' . urlencode($_GET['from']) : ''; ?>" class="text-dark ml-1"><b>注册</b></a></p>
			<?php
			}
			?>
		</div>
	</div>
	<?php $this->need('module/footer.php'); ?>
	<script>
		! function(t) {
			let from = '<?= addslashes(strip_tags($_GET['from'])) ?>';
			let btn = function(obj, msg, code) {
			    obj.html(msg);
			    obj.attr("disabled", code);
			}
			$("#login").click(function() {
				let username = $("#username").val();
				let password = $("#password").val();
				if (!username) return Qmsg.warning("请输入邮箱/用户名");
				if (!password) return Qmsg.warning("请输入密码");
				$.ajax({
					url: '<?= Typecho_Common::url('user/api', Helper::options()->index) ?>',
					type: 'post',
					dataType: 'json',
					async: true,
					data: {
						action: 'login',
						username: username,
						password: password
					},
					beforeSend: function() {
					    btn($("#login"), '登录中...', true);
					},
					complete: function() {
					    btn($("#login"),'登录', false);
					},
					error: function() {
						$("#login").text('登录', false);
						Qmsg.error("服务器繁忙");
					},
					success: function(res) {
						if (res.code == 1) {
							Qmsg.success("登录成功");
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
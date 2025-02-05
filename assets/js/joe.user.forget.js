Joe.DOMContentLoaded.userForget ||= () => {
	console.log('调用：Joe.DOMContentLoaded.userForget');
	var btn = function (obj, msg, code) {
		obj.html(msg);
		obj.attr("disabled", code);
	}
	var countdown = 60;
	var setTime = function () {
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
		setTimeout(function () {
			setTime()
		}, 1000);
	}
	$("#send").click(function () {
		let email = $("#email").val();
		if (!email) return autolog.log("请输入邮箱后发送验证码", 'warn');
		$.ajax({
			url: Joe.options.index + '/user/api',
			type: 'post',
			dataType: 'json',
			async: true,
			data: {
				action: 'forget_code',
				email: email
			},
			beforeSend: function () {
				btn($("#send"), '<i class="loading mr6"></i>发送中...', true);
			},
			complete: function () {
				btn($("#send"), "获取验证码", false);
			},
			error: function () {
				btn($("#send"), "获取验证码", false);
				autolog.log("服务器繁忙", 'error');
			},
			success: function (res) {
				if (res.code == 1) {
					setTime();
					autolog.log("验证码已发送到您的邮箱", 'success');
				} else {
					autolog.log(res.msg, 'warn');
				}
			}
		});
	});
	var state;
	$("#check").click(function () {
		let email = $("#email").val();
		let code = $("#code").val();
		if (!email) return autolog.log("请输入邮箱", 'warn');
		if (!code) return autolog.log("请输入验证码", 'warn');
		$.ajax({
			url: Joe.options.index + '/user/api',
			type: 'post',
			dataType: 'json',
			async: true,
			data: {
				action: 'forget_check',
				email: email,
				code: code
			},
			beforeSend: function () {
				btn($("#check"), '<i class="loading mr6"></i>验证中...', true);
			},
			complete: function () {
				btn($("#check"), "验证", false);
			},
			error: function () {
				btn($("#check"), "验证", false);
				autolog.log("服务器繁忙", 'error');
			},
			success: function (res) {
				if (res.code == 1) {
					$("#post1").hide(100);
					$("#post2").hide(100);
					$("#post3").hide(100);
					$('#check').hide()
					$("#new1").show(100);
					$("#new2").show(100);
					$("#new3").show(100);
					$('#forget').show();
					autolog.log("验证通过，请设置新密码", 'success');
					state = res.state;
				} else {
					autolog.log(res.msg, 'warn');
				}
			}
		});
	});
	$("#forget").click(function () {
		let password = $("#password").val();
		let cpassword = $("#cpassword").val();
		if (!password) return autolog.log("请输入密码", 'warn');
		if (!cpassword) return autolog.log("请输入确认密码", 'warn');
		if (password != cpassword) return autolog.log("两次密码不一致", 'warn');
		$.ajax({
			url: Joe.options.index + '/user/api',
			type: 'post',
			dataType: 'json',
			async: true,
			data: {
				action: 'forget',
				state: state,
				password: password,
				cpassword: cpassword
			},
			beforeSend: function () {
				btn($("#forget"), '<i class="loading mr6"></i>设置中...', true);
			},
			complete: function () {
				btn($("#forget"), "设置密码", false);
			},
			error: function () {
				btn($("#forget"), "设置密码", false);
				autolog.log("服务器繁忙", 'error');
			},
			success: function (res) {
				if (res.code == 1) {
					autolog.log('密码重置成功', 'success');
					setTimeout(function () {
						window.location.href = window.Joe.referer;
					}, 1500);
				} else {
					autolog.log(res.msg, 'warn');
				}
			}
		});
	});
}
document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.userForget, { once: true });
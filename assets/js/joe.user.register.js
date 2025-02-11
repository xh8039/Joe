Joe.DOMContentLoaded.userRegister ||= () => {
	console.log('调用：Joe.DOMContentLoaded.userRegister');
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
	// 使用jQuery绑定input和propertychange事件
	$("#username").on('input propertychange', function () {
		// 获取input元素，并实时监听用户输入
		let QQ = $(this).val();
		if (/^[1-9][0-9]{4,}$/.test(QQ)) $("#email").val(QQ + '@qq.com');
	});
	$("#send").click(function () {
		let email = $("#email").val();
		if (!email) return autolog.log("请输入邮箱后发送验证码", 'warning');
		$.ajax({
			url: Joe.options.index + '/user/api',
			type: 'post',
			dataType: 'json',
			async: true,
			data: {
				action: 'reg_code',
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
	$("#register").click(function () {
		let nickname = $("#nickname").val();
		let username = $("#username").val();
		let email = $("#email").val();
		let code = $("#code").val();
		let password = $("#password").val();
		let confirm_password = $("#confirm_password").val();
		if (!nickname) return autolog.log("请输入昵称", 'warn');
		if (!username) return autolog.log("请输入用户名", 'warn');
		if (!email) return autolog.log("请输入邮箱", 'warn');
		if ($("#code").length > 0 && !code) return autolog.log("请输入验证码", 'warn');
		if (!password) return autolog.log("请输入密码", 'warn');
		if (!confirm_password) return autolog.log("请输入确认密码", 'warn');
		if (confirm_password != password) return autolog.log("两次密码不一致", 'warn');
		$.ajax({
			url: Joe.options.index + '/user/api',
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
				confirm_password: confirm_password
			},
			beforeSend: function () {
				btn($("#register"), '<i class="loading mr6"></i>注册中...', true);
			},
			complete: function () {
				btn($("#register"), "注册", false);
			},
			error: function () {
				btn($("#register"), "注册", false);
				autolog.log('服务器繁忙', 'error');
			},
			success: function (res) {
				if (res.code == 1) {
					autolog.log('注册成功', 'success');
					setTimeout(function () {
						window.location.href = window.Joe.referer;
					}, 1000);
				} else {
					autolog.log(res.msg, 'warn');
				}
			}
		});
	});
}
document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.userRegister, { once: true });
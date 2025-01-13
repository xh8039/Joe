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
	if (/[1-9][0-9]{4,}/.test(QQ)) $("#email").val(QQ + '@qq.com');
});
$("#send").click(function () {
	let email = $("#email").val();
	if (!email) return Qmsg.warning("请输入邮箱后发送验证码");
	$.ajax({
		url: '/user/api',
		type: 'post',
		dataType: 'json',
		async: true,
		data: {
			action: 'reg_code',
			email: email
		},
		beforeSend: function () {
			btn($("#send"), "发送中...", true);
		},
		complete: function () {
			btn($("#send"), "获取验证码", false);
		},
		error: function () {
			btn($("#send"), "获取验证码", false);
			Qmsg.error("服务器繁忙");
		},
		success: function (res) {
			if (res.code == 1) {
				setTime();
				Qmsg.success("验证码已发送到您的邮箱");
			} else {
				Qmsg.warning(res.msg);
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
	let cpassword = $("#cpassword").val();
	if (!nickname) return Qmsg.warning("请输入昵称");
	if (!username) return Qmsg.warning("请输入用户名");
	if (!email) return Qmsg.warning("请输入邮箱");
	if ($("#code").length > 0 && !code) return Qmsg.warning("请输入验证码");
	if (!password) return Qmsg.warning("请输入密码");
	if (!cpassword) return Qmsg.warning("请输入确认密码");
	if (cpassword != password) return Qmsg.warning("两次密码不一致");
	$.ajax({
		url: '/user/api',
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
		beforeSend: function () {
			btn($("#register"), "注册中...", true);
		},
		complete: function () {
			btn($("#register"), "注册", false);
		},
		error: function () {
			btn($("#register"), "注册", false);
			Qmsg.error("服务器繁忙");
		},
		success: function (res) {
			if (res.code == 1) {
				Qmsg.success("注册成功");
				setTimeout(function () {
					window.location.href = from ? from : "/";
				}, 1000);
			} else {
				Qmsg.warning(res.msg);
			}
		}
	});
});
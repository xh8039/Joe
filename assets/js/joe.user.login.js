var btn = function (obj, msg, code) {
	obj.html(msg);
	obj.attr("disabled", code);
}
$("#login").click(function () {
	let username = $("#username").val();
	let password = $("#password").val();
	if (!username) return Qmsg.warning("请输入邮箱/用户名");
	if (!password) return Qmsg.warning("请输入密码");
	$.ajax({
		url: 'user/api',
		type: 'post',
		dataType: 'json',
		async: true,
		data: {
			action: 'login',
			username: username,
			password: password
		},
		beforeSend: function () {
			btn($("#login"), '<i class="loading mr6"></i>登录中...', true);
		},
		complete: function () {
			btn($("#login"), '登 录', false);
		},
		error: function () {
			$("#login").text('登 录', false);
			Qmsg.error("服务器繁忙");
		},
		success: function (res) {
			if (res.code == 1) {
				Qmsg.success("登录成功");
				setTimeout(function () {
					window.location.href = window.Joe.referer;
				}, 1000);
			} else {
				Qmsg.warning(res.msg);
			}
		}
	});
});
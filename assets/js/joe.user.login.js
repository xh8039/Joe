var btn = function (obj, msg, code) {
	obj.html(msg);
	obj.attr("disabled", code);
}
$("#login").click(function () {
	let username = $("#username").val();
	let password = $("#password").val();
	if (!username) return autolog.log("请输入邮箱/用户名", 'warn');
	if (!password) return autolog.log("请输入密码", 'warn');
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
			autolog.log("服务器繁忙", 'error');
		},
		success: function (res) {
			if (res.code == 1) {
				autolog.log("登录成功", 'success');
				setTimeout(function () {
					window.location.href = window.Joe.referer;
				}, 1000);
			} else {
				autolog.log(res.msg, 'warning');
			}
		}
	});
});
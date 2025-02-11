Joe.DOMContentLoaded.userLogin ||= () => {
	console.log('调用：Joe.DOMContentLoaded.userLogin');
	$("#user-login").submit(function (event) {
		event.preventDefault();
		const button = document.querySelector('#user-login button[type=submit]');
		$.ajax({
			url: $(this).attr('action'),
			type: 'post',
			dataType: 'json',
			data: $(this).serialize(),
			beforeSend: function () {
				Joe.btnLoad(button, '登录中...');
			},
			complete: function () {
				Joe.btnLoad(button, false);
			},
			error: function () {
				Joe.btnLoad(button, false);
				autolog.log("服务器繁忙", 'error');
			},
			success: function (res) {
				if (res.code == 200) {
					autolog.log('登录成功', 'success');
					setTimeout(function () {
						window.location.href = window.Joe.referer;
					}, 1000);
				} else {
					autolog.log(res.message, 'warning');
				}
			}
		});
	});
}
document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.userLogin, { once: true });
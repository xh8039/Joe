document.addEventListener("DOMContentLoaded", function () {
	Joe.domain = window.location.host;
	Joe.service_domain = '//auth.bri6.cn/server/joe/';
	var e = document.querySelectorAll(".joe_config__aside .item"),
		t = document.querySelector(".joe_config__notice"),
		s = document.querySelector(".joe_config > form"),
		n = document.querySelectorAll(".joe_content");
	if (e.forEach(function (o) {
		o.addEventListener("click", function () {
			e.forEach(function (e) {
				e.classList.remove("active")
			}), o.classList.add("active");
			var c = o.getAttribute("data-current");
			sessionStorage.setItem("joe_config_current", c), "joe_notice" === c ? (t.style
				.display = "block", s.style.display = "none") : (t.style.display = "none", s
					.style.display = "block"), n.forEach(function (e) {
						e.style.display = "none";
						var t = e.classList.contains(c);
						t && (e.style.display = "block")
					})
		})
	}), sessionStorage.getItem("joe_config_current")) {
		var o = sessionStorage.getItem("joe_config_current");
		"joe_notice" === o ? (t.style.display = "block", s.style.display = "none") : (s.style.display = "block",
			t.style.display = "none"), e.forEach(function (e) {
				var t = e.getAttribute("data-current");
				t === o && e.classList.add("active")
			}), n.forEach(function (e) {
				e.classList.contains(o) && (e.style.display = "block")
			})
	} else e[0].classList.add("active"), t.style.display = "block", s.style.display = "none";
	var c = new XMLHttpRequest;
	c.onreadystatechange = function () {
		if (4 === c.readyState)
			if (200 <= c.status && 300 > c.status || 304 === c.status) {
				var e = JSON.parse(c.responseText);
				t.innerHTML = e.success ? '<p class="title">最新版本：' + e.title + "</p>" + e.content : "请求失败！"
			} else t.innerHTML = "请求失败！"
	}, c.open("get", `${Joe.service_domain}message`, !0), c.send(
		null);
	if ($('[data-current="joe_code"]').hasClass('active')) {
		sessionStorage.setItem("joe_config_current", 'joe_notice');
		// sessionStorage.removeItem("joe_config_current");
	}
	function openLinkInNewTab(url) {
		const a = document.createElement('a');
		a.href = url;
		a.target = '_blank';
		a.dispatchEvent(new MouseEvent('click', {
			bubbles: true,
			cancelable: true,
			view: window
		}));
	}
	function update(type = 'passive') {
		const Feedback = YiHang.Feedback;
		$.ajax({
			type: "post",
			url: `${Joe.service_domain}update`,
			data: {
				title: Joe.title,
				version: Joe.version,
				domain: Joe.domain,
				logo: Joe.logo,
				favicon: Joe.Favicon
			},
			dataType: "json",
			beforeSend: () => {
				if (type == 'active') {
					Feedback.loading();
				}
			},
			success: (data) => {
				Feedback.closeAll();
				if (data.update) {
					Feedback.alert({
						content: data.msg,
						buttons: {
							'暂不更新': {
								background: 'transparent',
								color: '#444'
							},
							"前往更新": {
								callback: () => {
									openLinkInNewTab(data.download);
								}
							},
						},
					});
				} else if (type == 'active') {
					Qmsg.info(data.msg);
				}
			},
			error: () => {
				Feedback.closeAll();
				Qmsg.error('请求错误，请检查您的网络');
			}
		});
	}
	update();
	$('#update').click(() => {
		update('active');
	});
});
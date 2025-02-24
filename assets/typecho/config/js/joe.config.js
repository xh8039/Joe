; window.Joe.service_domain = '//auth.bri6.cn/server/joe/';

/** 切换面板显示状态的函数 */
window.Joe.togglePanelDisplay = (currentTab) => {
	// 切换通知和表单面板
	const isNoticeTab = currentTab === "joe_notice";
	if (window.jQuery) {
		isNoticeTab ? $(Joe.noticePanel).fadeIn('fast') : Joe.noticePanel.style.display = "none";
	} else {
		Joe.noticePanel.style.display = isNoticeTab ? "block" : "none";
	}
	Joe.formPanel.style.display = isNoticeTab ? "none" : "block";
	// 切换内容面板
	document.querySelectorAll(".joe_content").forEach(panel => {
		if (window.jQuery) {
			panel.classList.contains(currentTab) ? $(panel).fadeIn('fast') : $(panel).fadeOut('fast');
		} else {
			panel.style.display = panel.classList.contains(currentTab) ? "block" : "none";
		}
	});
}

/** 激活当前标签页 */
window.Joe.activateTab = (targetTab) => {
	Joe.tabItems.forEach(item => item.classList.remove("active"));
	targetTab.classList.add("active");
}

/** 处理标签页点击事件 */
window.Joe.handleTabClick = (clickedTab) => {
	const currentTab = clickedTab.getAttribute("data-current");
	Joe.activateTab(clickedTab);
	sessionStorage.setItem("joe_config_current", currentTab);
	Joe.togglePanelDisplay(currentTab);
}

window.Joe.openLinkInNewTab = (url) => {
	const a = document.createElement('a');
	a.href = url;
	a.target = '_blank';
	a.dispatchEvent(new MouseEvent('click', {
		bubbles: true,
		cancelable: true,
		view: window
	}));
}

window.Joe.update = (type = 'passive') => {
	if (type == 'active') var loading = layer.load(2, { shade: 0.3 });
	$.ajax({
		type: "post",
		url: `${Joe.service_domain}update`,
		data: {
			title: Joe.title,
			version: Joe.version,
			domain: window.location.host,
			logo: Joe.logo,
			favicon: Joe.Favicon
		},
		dataType: "json",
		success: (data) => {
			layer.close(loading);
			if (data.update) {
				layer.confirm(data.message, { btn: data.buttons }, () => {
					Joe.openLinkInNewTab(data.download);
				}, () => {
					layer.alert(`<p>最怕问初衷，大梦成空。</p><p>眉间鬓上老英雄，剑甲鞮鍪封厚土，说甚擒龙。</p><p>壮志付西风，逝去无踪。</p><p>少年早作一闲翁，诗酒琴棋终日里，岁月匆匆。</p><p>不更新等着养老吗？</p>`);
				});
			} else if (type == 'active') {
				autolog.success(data.message);
			}
		},
		error: () => {
			layer.close(loading);
			autolog.error('请求错误，请检查您的网络');
		}
	});
}

window.Joe.backup = (action = 'backup') => {
	var loading = layer.load(2, { shade: 0.3 });
	$.ajax({
		type: 'post',
		url: Joe.BASE_API + '/options-backup',
		data: { action },
		dataType: 'json',
		success: (data) => {
			layer.close(loading);
			autolog.log(data.message, data.code == 200 ? 'info' : 'error');
			if (action == 'revert' && data.code == 200) {
				setTimeout(() => window.location.reload(), 3000);
			}
		},
		error: () => {
			layer.close(loading);
			autolog.error('请求错误，请检查您的网络');
		}
	});
}

window.Joe.mailTest = () => {
	var loading = layer.load(2, { shade: 0.3 });
	$.ajax({
		type: 'post',
		url: Joe.BASE_API + '/mail-test',
		dataType: 'json',
		success: (data) => {
			layer.close(loading);
			autolog.log(data.message, data.code == 200 ? 'success' : 'error');
		},
		error: () => {
			layer.close(loading);
			autolog.error('请求错误，请检查您的网络');
		}
	});
}

document.addEventListener("DOMContentLoaded", function () {

	{
		// 缓存DOM元素引用
		Joe.tabItems = document.querySelectorAll(".joe_config__aside .item");
		Joe.noticePanel = document.querySelector(".joe_config__notice");
		Joe.formPanel = document.querySelector(".joe_config > form");
		// 初始化标签页事件监听
		Joe.tabItems.forEach(item => {
			item.addEventListener("click", () => Joe.handleTabClick(item));
		});

		// 恢复上次保存的状态
		const savedTab = sessionStorage.getItem("joe_config_current");
		if (savedTab) {
			// 应用保存的状态
			Joe.tabItems.forEach(item => {
				if (item.getAttribute("data-current") === savedTab) Joe.activateTab(item);
			});
			Joe.togglePanelDisplay(savedTab);
		} else {
			// 默认显示第一个标签页
			Joe.tabItems[0].classList.add("active");
			Joe.noticePanel.style.display = "block";
			Joe.formPanel.style.display = "none";
		}

		if ($('[data-current="joe_code"]').hasClass('active')) sessionStorage.setItem("joe_config_current", 'joe_notice');
	}

	{
		$.getJSON(`${Joe.service_domain}message`, function (data) {
			Joe.noticePanel.innerHTML = '<p class="title">最新版本：' + data.title + "</p>" + data.content;
		});
		Joe.update();
	}

});

if (!window.Joe) window.Joe = {};
window.Joe.options = window.Joe.options ? window.Joe.options : {};

document.addEventListener('DOMContentLoaded', () => {
	window.Joe.$body = $(document.body);
});

window.Joe.WeakMap = new WeakMap;

if (typeof AudioManager === 'function') {
	window.Joe.AudioManager = new AudioManager({ base: Joe.THEME_URL + 'assets/audio/' });
}

if (typeof ThemeManager === 'function') window.Joe.themeManager = new ThemeManager();

window.Joe.htmlTextContent = (string) => {
	string = string.replace(/<script.*?<\/script>/gis, "").replace(/<style.*?<\/style>/gis, "");
	const div = document.createElement('div');
	div.innerHTML = string;
	let textContent = div.innerText || div.textContent || "";
	textContent = textContent.replace(/[\r\n]/g, " ").replace(/\s+/g, " ");
	return textContent;
}

window.Joe.detectIE = () => {
	var n = window.navigator.userAgent,
		e = n.indexOf("MSIE ");
	if (e > 0) {
		return parseInt(n.substring(e + 5, n.indexOf(".", e)), 10)
	}
	if (n.indexOf("Trident/") > 0) {
		var r = n.indexOf("rv:");
		return parseInt(n.substring(r + 3, n.indexOf(".", r)), 10)
	}
	var i = n.indexOf("Edge/");
	return i > 0 && parseInt(n.substring(i + 5, n.indexOf(".", i)), 10)
}

window.Joe.httpBuildQuery = (object) => {
	if (object === null || typeof object !== 'object') return '';
	return Object.keys(object).map(k => {
		const encodedKey = encodeURIComponent(k);
		let value = object[k];
		try {
			// 检查是否为已编码字符串，避免二次编码
			const decodedValue = decodeURIComponent(value);
			if (value === decodedValue) value = encodeURIComponent(value);
		} catch (e) {
			// 解码失败时视为未编码字符串
			value = encodeURIComponent(value);
		}
		return `${encodedKey}=${value}`;
	}).join('&');
}

window.Joe.playNotificationAudio = () => {
	const list = ['WaterDay.ogg', 'WaterEvening.ogg', 'WaterMidday.ogg', 'WaterNight.ogg', 'WaterDropPreview.ogg', 'WaterDropDay1.ogg', 'WaterDropDay2.ogg', 'WaterDropDay3.ogg', 'WaterDropEvening1.ogg', 'WaterDropEvening2.ogg', 'WaterDropEvening3.ogg', 'WaterDropMidday1.ogg', 'WaterDropMidday2.ogg', 'WaterDropMidday3.ogg', 'WaterDropNight1.ogg', 'WaterDropNight2.ogg', 'WaterDropNight3.ogg'];
	const index = Math.floor(Math.random() * list.length);
	const value = list[index];
	console.log(value);
	return Joe.AudioManager.play(`notification/${value}`);
}

window.Joe.getMemoryUsage = () => {
	if (window.performance && window.performance.memory) {
		const memory = window.performance.memory;
		return {
			usedMB: (memory.usedJSHeapSize / (1024 * 1024)).toFixed(2),
			totalMB: (memory.totalJSHeapSize / (1024 * 1024)).toFixed(2),
			limitMB: (memory.jsHeapSizeLimit / (1024 * 1024)).toFixed(2)
		};
	} else {
		console.log('当前浏览器不支持 performance.memory');
		return null;
	}
}

window.Joe.btnLoad = (element, message = null) => {
	if (message === false) return Joe.btnLoaded(element);
	Joe.WeakMap.set(element, element.innerHTML);
	element.innerHTML = `<i class="loading ${message ? 'mr6' : null}"></i>` + message;
	element.disabled = true;
	element.classList.add('disabled');
	return element;
}

window.Joe.btnLoaded = (element) => {
	const html = Joe.WeakMap.get(element);
	element.innerHTML = html;
	element.disabled = false;
	element.classList.remove('disabled');
	return element;
}

window.Joe.thumbnailError = (element) => {
	if (element.dataset.thumbnailLoaded) return true
	console.log('缩略图加载失败', element, element.src);
	// 生成一个 1 到 42 之间的随机整数
	const randomNumber = Math.floor(Math.random() * 41) + 1;
	// 将随机数格式化为两位数
	const formattedNumber = ("0" + randomNumber).slice(-2);
	const thumb = `${Joe.THEME_URL}assets/images/thumb/${formattedNumber}.jpg`;
	element.dataset.src = thumb;
	element.src = thumb;
	element.dataset.thumbnailLoaded = true;
}

window.Joe.avatarError = (element) => {
	if (element.tagName.toLowerCase() != 'img') return;
	if (element.dataset.defaultAvatarLoaded) return true;
	console.log('头像加载失败', element, element.src);
	const defaultAvatar = Joe.THEME_URL + 'assets/images/avatar-default.png';
	element.dataset.src = defaultAvatar;
	element.src = defaultAvatar;
	element.dataset.defaultAvatarLoaded = true;
}

window.Joe.pjax = (url, selectors = [], options = {}) => {
	if (url instanceof Object) {
		options = url;
	} else {
		if (url.startsWith('/') || url.startsWith('http')) {
			options.url = url;
		} else {
			options.element = url;
		}
		options.selectors = selectors;
	}
	console.log(options);
	return new class {
		constructor(options) {
			if (options.url) this.ajax(options);
			if (options.element && document.querySelector(options.element)) {
				$(options.element).attr('data-turbolinks', 'false').attr('ajax-replace', 'true');
				$(document).off('click', options.element);
				$(document).on('click', options.element, (event) => {
					event.preventDefault();
					options.url = event.target.href;
					this.ajax(options);
				});
			}
		}
		ajax(options) {
			let ajax = {
				type: options.type ? options.type : 'GET',
				url: options.url.replace(/^https?:/i, location.protocol),
				dataType: 'html',
				beforeSend(xhr) {
					xhr.setRequestHeader('x-ajax', 'true');
					xhr.setRequestHeader('x-ajax-selectors', JSON.stringify(options.selectors));
					if (options.beforeSend) options.beforeSend(xhr);
				},
				success: function (response) {
					window.Joe.commentListAutoRefresh = true;
					let success = options.success ? options.success(response) : true;
					if (success !== false) {
						const DocumentParser = (new DOMParser()).parseFromString(response, 'text/html');
						options.selectors.forEach(selector => {
							let responseHTML = $(DocumentParser).find(selector).prop('outerHTML');
							$(selector).replaceWith(responseHTML);
						});
					}
					if (window.Joe.tooltip) window.Joe.tooltip();
					if (options.replace) options.replace(response);
					if (options.element && document.querySelector(options.element)) $(options.element).attr('data-turbolinks', 'false').attr('ajax-replace', 'true');
					if (options.scrollTo != undefined) Joe.scrollTo(options.scrollTo);
				},
				error(xhr, status, error) {
					if (options.error) options.error(xhr, status, error);
				},
				complete(xhr, status) {
					if (options.complete) options.complete(xhr, status);
				}
			};
			if (options.processData != undefined) ajax.processData = options.processData;
			if (options.contentType != undefined) ajax.contentType = options.contentType;
			if (options.data != undefined) ajax.data = options.data;
			$.ajax(ajax);
		}
	}(options);
}

window.Joe.clipboard = (content, success = undefined, error = undefined) => {
	if (success == undefined) success = () => { window.autolog ? autolog.success('复制成功') : alert('复制成功') };
	if (location.protocol == 'https:' && 'clipboard' in navigator) {
		if (error == undefined) error = () => { window.autolog ? autolog.error('复制失败！') : alert('复制失败！') };
		navigator.clipboard.writeText(content).then(success, error);
	} else {
		let aux = document.createElement("input");
		aux.setAttribute("value", content);
		document.body.appendChild(aux);
		aux.select();
		document.execCommand("copy");
		document.body.removeChild(aux);
		success();
	}
}

String.prototype.startsWithArray = function (searchArray, start = 0) {
	for (const key in searchArray) {
		if (this.startsWith(searchArray[key], start)) return true;
	}
	return false;
}

window.Joe.internalForwardUrl = (string) => {
	try {
		if (string instanceof Element) {
			let $element = $(string);
			if ($element.attr('target') == '_blank') return false;
			if ($element.attr('ajax-replace') != undefined) return false;
			if ($element.attr('data-pjax-state') != undefined) return false;
			string = string.href;
		}
		console.log(string);
		if (string.startsWithArray([
			'/admin',
			location.origin + '/admin',
			'/goto',
			location.origin + '/goto',
			'#'
		])) {
			return false
		};
		if (string.startsWith('/')) return true;
		let url = new URL(string);
		if (url.hash) return false;
		if (url.host != location.host) return false;
		if (url.protocol == 'javascript:') return false;
	} catch (error) {
		console.log(error);
		return false;
	}
	return true;
}

window.Joe.internalUrl = (string) => {
	try {
		if (string instanceof Element) {
			if (string.target == '_blank') return false;
			string = string.href;
		}
		if (string.startsWithArray([
			'/admin',
			location.origin + '/admin',
			'/goto',
			location.origin + '/goto'
		])) {
			return false
		};
		if (string.startsWith('/')) return true;
		let url = new URL(string);
		if (url.protocol == 'javascript:') return true;
		if (url.host == location.host) return true;
	} catch (error) {
		console.log(error);
	}
	return false;
}

window.Joe.scrollTo = (selector) => {
	const reservedHeight = document.querySelector('.joe_header').offsetHeight + 15;
	var top;
	if (/^-?\d+(\.\d+)?$/.test(selector)) {
		top = ((selector - reservedHeight) < 0) ? 0 : (selector - reservedHeight);
	} else {
		const $selector = (selector instanceof Element) ? selector : document.querySelector(selector);
		if (!$selector) return;
		top = ($selector.getBoundingClientRect().top + window.scrollY) - reservedHeight;
	}
	console.log(top);
	window.scrollTo({ top: top, behavior: 'smooth' });
}

window.Joe.removeMeta = (name) => {
	const existingMeta = document.querySelectorAll(`meta[name="${name}"]`);
	if (existingMeta.length > 0) existingMeta.forEach((meta) => {
		if (meta.name == name) meta.remove();
	});
}

window.Joe.addMeta = (name, content) => {
	window.Joe.removeMeta(name);

	const lastMeta = document.head.querySelector('meta:last-of-type');

	if (lastMeta) {
		lastMeta.insertAdjacentHTML('afterend', `<meta name="${name}" content="${content}">`);
	} else {
		const newMeta = document.createElement('meta');
		newMeta.name = name;
		newMeta.content = content;
		document.head.appendChild(newMeta);
	}
}

window.Joe.tooltip = (selectors = '', options = {}) => {
	const tooltip = '[data-toggle="tooltip"]:not([data-original-title])';
	const selector = selectors ? `${selectors}${tooltip},${selectors} ${tooltip}` : tooltip;
	if (Joe.IS_MOBILE && options instanceof Object) {
		$(selector).each(function () {
			['data-toggle', 'data-placement'].forEach(value => {
				$(this).removeAttr(value);
			});
		});
	} else {
		if (options instanceof Object) options.container = options.container ? options.container : 'body';
		$(selector).tooltip(options);
		if (options instanceof Object) $(selector).on('click', function (event) {
			$(this).tooltip('hide');
		});
	}
}

if (window.Joe.options.JLoading != 'off') {
	window.Joe.loadingStart = () => {
		$("#loading-animation").fadeIn(150);
	}
	window.Joe.loadingEnd = () => {
		setTimeout(() => {
			$("#loading-animation").fadeOut(540);
		}, 500);
	}
}

window.Joe.loadJS = (url, callback = function () { }) => {
	window.Joe.loadJSList = window.Joe.loadJSList ? window.Joe.loadJSList : {};
	if (!Joe.loadJSList[url]) {
		let script = document.createElement('script');
		script.type = 'text/javascript';
		script.addEventListener('load', callback);
		script.src = url;
		document.head.appendChild(script);
		Joe.loadJSList[url] = script;
	} else {
		// let script = Joe.loadJSList[url];
		// script.addEventListener('load', callback);
		callback();
	}
}

window.Joe.ResourceLoader = class {
	static scriptsLoaded = [];
	promises = [];

	static isScriptLoaded(_0x1bdc9a) {
		return Joe.ResourceLoader.scriptsLoaded.indexOf(_0x1bdc9a.split("/").pop()) > -1 ? true : false;
	}

	/**
	 * 将文件添加到加载队列。
	 * @param {string} fileUrl 要加载的文件的 URL。
	 */
	add(fileUrl) {
		const promise = new Promise((resolve, reject) => {
			const element = fileUrl.endsWith(".js") ? this.getScriptElm(fileUrl) : this.getLinkElm(fileUrl); // 根据扩展名确定元素类型

			element.addEventListener("load", () => {
				// 从 src 或 href 中提取文件名
				let url = element.src || element.href;
				const filename = url.split("/").pop();
				Joe.ResourceLoader.scriptsLoaded.push(filename);
				console.log(`文件已加载: ${filename}`);
				resolve(element); // 使用已加载的元素解析 Promise
			});

			element.addEventListener("error", () => {
				console.error(`加载失败: ${fileUrl}`); // 更具信息性的错误消息
				reject(fileUrl); // 使用失败的 URL 拒绝 Promise
			});
		});
		this.promises.push(promise);
	}

	/**
	 * 返回一个 Promise，在添加的所有文件加载完成后解析。
	 * @returns {Promise<any[]>} 解析为已加载元素数组的 Promise。
	 */
	loaded() {
		return Promise.all(this.promises);
	}

	getScriptElm(url) {
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = url;
		script.async = false;
		document.getElementsByTagName("head")[0].appendChild(script);
		return script;
	}

	getLinkElm(url) {
		var link = document.createElement("link");
		link.rel = "stylesheet";
		link.type = "text/css";
		link.href = url;
		document.getElementsByTagName("HEAD")[0].appendChild(link);
		return link;
	}
}

window.Joe.loadResource = () => {
	const ResourceLoader = new Joe.ResourceLoader();

	// 只添加尚未加载的文件
	fileUrls.forEach(fileUrl => {
		if (!Joe.ResourceLoader.isScriptLoaded(fileUrl)) ResourceLoader.add(fileUrl);
	});

	// 返回一个 Promise，在所有文件加载完成后解析
	return ResourceLoader.loaded().then(() => {
		console.log('资源加载完成');
	});
}

function isElementInViewport(element) {
	const rect = element.getBoundingClientRect();
	const viewportHeight = (window.innerHeight || document.documentElement.clientHeight);
	const viewportWidth = (window.innerWidth || document.documentElement.clientWidth);
	// 检查顶部和底部是否都在视口内
	return (
		(rect.top != 0 && rect.bottom != 0 && rect.left != 0 && rect.right != 0) &&
		rect.top <= viewportHeight &&
		rect.bottom >= 0 &&
		rect.left <= viewportWidth &&
		rect.right >= 0
	);
}

/**
 * 防抖函数
 * @param {*} fn 
 * @param {*} delay 
 * @returns 
 */
function throttle(fn, delay = 100) {
	let valid = true;
	return function (...args) {
		if (!valid) {
			//休息时间 暂不接客
			return false;
		}
		// 工作时间，执行函数并且在间隔期内把状态位设为无效
		valid = false;
		setTimeout(function () {
			fn.apply(this, args);
			valid = true;
		}, delay);
	};
}


/**
 * @description: 节流函数
 * @param {*} callback 函数
 * @param {*} delay 时间
 * @param {*} immediate  是否立即执行 为true则计时开始就就执行
 * @return {*}
 */
// 定义一个debounce函数，用于函数防抖
function debounce(callback, delay, immediate) {
	// 定义一个变量，用于存放定时器
	var timeout;
	// 返回一个函数
	return function () {
		// 定义一个变量，用于存放this
		var context = this,
			// 定义一个变量，用于存放参数
			args = arguments;
		// 定义一个函数，用于清除定时器
		var later = function () {
			// 将定时器置为null
			timeout = null;
			// 如果不是立即执行，则执行回调函数
			if (!immediate) {
				callback.apply(context, args);
			}
		};
		// 如果是立即执行，且定时器不存在，则立即执行回调函数
		var callNow = immediate && !timeout;
		// 清除定时器
		clearTimeout(timeout);
		// 重新设置定时器
		timeout = setTimeout(later, delay);
		// 如果是立即执行，则立即执行回调函数
		if (callNow) {
			callback.apply(context, args);
		}
	};
}

window.Joe.base64_encode = str => window.btoa(encodeURIComponent(str));

window.Joe.base64_decode = str => decodeURIComponent(window.atob(str));

window.Joe.anchor_scroll = () => {
	// 检查 referer 是否包含 baidu.com
	if (document.referrer.includes('baidu.com')) return;
	/* 判断地址栏是否有锚点链接，有则跳转到对应位置 */
	const scroll = new URLSearchParams(location.search).get('scroll');
	if (!scroll) return;
	let elementEL = document.querySelector('#' + scroll) || document.querySelector('.' + scroll);
	if (elementEL) window.Joe.scrollTo(elementEL);
}

window.Joe.submit_baidu = (msg = '推送中...') => {
	$('#Joe_Baidu_Record').html(`<span style="color: #E6A23C">${msg}</span>`);
	$.ajax({
		url: Joe.BASE_API + '/baidu-push',
		type: 'POST',
		dataType: 'json',
		data: {
			domain: window.location.protocol + '//' + window.location.hostname,
			url: encodeURI(window.location.href),
			cid: window.Joe.CONTENT.cid
		},
		success(res) {
			if (res.already) {
				$('#Joe_Baidu_Record').css('color', 'var(--theme)');
				$('#Joe_Baidu_Record').html('已推送');
				return
			}
			if (res.data.error) {
				if (res.data.message == 'over quota') res.data.message = '超过配额';
				$('#Joe_Baidu_Record').html('<span style="color: #F56C6C">推送失败，' + res.data.message + '</span>');
			} else {
				$('#Joe_Baidu_Record').html('<span style="color: var(--theme)">推送成功！今日剩余' + res.data.remain + '条</span>');
			}
		},
		error(res) {
			$('#Joe_Baidu_Record').html('<span style="color: #F56C6C">推送失败，请检查！</span>');
		}
	});
	// 	顺便推送URL到必应
	if (!Joe.options.BingPush) return;
	$.ajax({
		url: Joe.BASE_API + '/bing-push',
		type: 'POST',
		dataType: 'json',
		data: {
			domain: window.location.protocol + '//' + window.location.hostname,
			url: encodeURI(window.location.href)
		}
	});
}

/**
 * 递归为元素及其子元素添加层级标记
 * @param {HTMLElement} element - 当前要处理的元素
 * @param {number} currentLevel - 当前层级（从 0 开始）
 */
function setAtroposOffset(element, currentLevel = 0) {
	// 为当前元素添加层级标记
	element.setAttribute('data-atropos-offset', currentLevel.toString());

	// 遍历所有子元素（只处理元素节点，忽略文本节点等）
	const children = element.children;
	for (let i = 0; i < children.length; i++) {
		const child = children[i];
		// 递归处理子元素，层级 +1
		setAtroposOffset(child, currentLevel + 1);
	}
}

/**
 * @description: ajax请求封装
 * @param {*} _this 按钮的jquery对象
 * @param {*} data 传递的数据
 * @param {*} success 成功后的回调函数
 * @param {*} noty 提示信息
 * @param {*} no_loading 是否不显示加载动画
 * @return {*}
 */
function zib_ajax(_this, data, success, noty, no_loading) {
	if (_this.attr('disabled')) {
		return !1;
	}
	if (!data) {
		var _data = _this.attr('form-data');
		if (_data) {
			try {
				data = $.parseJSON(_data);
			} catch (e) { }
		}
		if (!data) {
			var form = _this.parents('form');
			data = form.serializeObject();
		}
	}

	var _action = _this.attr('form-action');
	if (_action) {
		data.action = _action;
	}

	//人机验证
	if (data.captcha_mode && is_captcha(data.captcha_mode)) {
		tbquire(['captcha'], function () {
			CaptchaOpen(_this, data.captcha_mode);
		});
		return !1;
	}

	if (window.captcha) {
		data.captcha = JSON.parse(JSON.stringify(window.captcha));
		data.captcha._this && delete data.captcha._this;
		window.captcha = {}; //只能使用一次
	}

	var _text = _this.html();
	var _loading = no_loading ? _text : '<i class="loading mr6"></i><text>请稍候</text>';
	noty != 'stop' && autolog.warn(noty || '正在处理请稍后...', 'load', '', 'wp_ajax');
	_this.attr('disabled', true).html(_loading);
	var _url = _this.attr('ajax-href') || window.Joe.BASE_API;

	$.ajax({
		type: 'POST',
		url: _url,
		data: data,
		dataType: 'json',
		error: function (n) {
			var _msg = '操作失败 ' + n.status + ' ' + n?.responseText + '，请刷新页面后重试';
			if (n.responseText && n.responseText.indexOf('致命错误') > -1) {
				_msg = '网站遇到致命错误，请检查插件冲突或通过错误日志排除错误';
			}
			console.error('ajax请求错误，错误信息如下：', n);
			// Qmsg.error(_msg, 'danger', '', noty != 'stop' ? 'wp_ajax' : '');
			autolog.error(_msg);
			_this.attr('disabled', false).html(_text);
		},
		success: function (n) {
			var ys = n.ys ? n.ys : n.error ? 'danger' : '';
			if (n.error) {
				// _win.slidercaptcha = false;
				data.tcaptcha_ticket && (tcaptcha = {});
			}
			if (noty != 'stop') {
				// Qmsg.success(n.msg || '处理完成', ys, '', 'wp_ajax');
				autolog.success(n.msg || '处理完成');
			} else if (n.msg) {
				// Qmsg.success(n.msg, ys);
				autolog.success(n.msg);
			}

			_this.attr('disabled', false).html(_text).trigger('zib_ajax.success', n); //完成
			$.isFunction(success) && success(n, _this, data);

			if (n.hide_modal) {
				_this.closest('.modal').modal('hide');
			}
			if (n.reload) {
				if (n.goto) {
					window.location.href = n.goto;
					window.location.reload;
				} else {
					window.location.reload();
				}
			}
		},
	});
}

//AJAX执行完成后自动切换到下一个tab
$('body').on('zib_ajax.success', '[next-tab]', function (e, n) {
	var _next = $(this).attr('next-tab');
	if (_next && n && !n.error) {
		$('a[href="#' + _next + '"]').tab('show');
	}
});

jQuery.fn.extend({
	insertContent: function (t, n) {
		var a = jQuery(this)[0];
		if (document.selection) {
			this.focus();
			var s = document.selection.createRange();
			s.text = t, this.focus(), s.moveStart("character", -i);
			var c = s.text.length;
			if (2 == arguments.length) {
				var i = a.value.length;
				s.moveEnd("character", c + n), n <= 0 ? s.moveStart("character", c - 2 * n - t.length) : s.moveStart("character", c - n - t.length), s.select()
			}
		} else if (a.selectionStart || "0" == a.selectionStart) {
			var o = a.selectionStart,
				r = a.selectionEnd,
				l = a.scrollTop;
			a.value = a.value.substring(0, o) + t + a.value.substring(r, a.value.length), this.focus(), a.selectionStart = o + t.length, a.selectionEnd = o + t.length, a.scrollTop = l, 2 == arguments.length && (a.setSelectionRange(o - n, a.selectionEnd + n), this.focus())
		} else this.value += t, this.focus()
	}
})

// 滑动手势minitouch
$.fn.minitouch = function (options) {
	var is_on = 'minitouch-isload';
	var _e = $(this);
	if (_e.data(is_on)) {
		return;
	}

	options = $.extend(
		{
			direction: 'bottom',
			selector: '',
			start_selector: '',
			depreciation: 50,
			stop: false,
			onStart: false,
			onIng: false,
			onEnd: false,
			inEnd: false,
		},
		options
	);
	var is_stop = false;
	var dep = options.depreciation;
	var startX = 0;
	var startY = 0;
	var endX = 0;
	var endY = 0;
	var angle = 0;
	var distanceX = 0;
	var distanceY = 0;
	var dragging = false;

	var cssTransition = function (a, b, c, d, s) {
		var e, f, g;
		d && ((b += 'px'), (c += 'px'), (e = 'translate3D(' + b + ',' + c + ' , 0)'), (f = {}), (g = cssT_Support()), (f[g + 'transform'] = e), (f[g + 'transition'] = g + 'transform 0s linear'), (f['cursor'] = s), 'null' == d && ((f[g + 'transform'] = ''), (f[g + 'transition'] = '')), a.css(f));
	};
	var cssT_Support = function () {
		var a = document.body || document.documentElement;
		a = a.style;
		return '' == a.WebkitTransition ? '-webkit-' : '' == a.MozTransition ? '-moz-' : '' == a.OTransition ? '-o-' : '' == a.transition ? '' : void 0;
	};

	var touch_selector = options.start_selector || options.selector;
	_e.on('touchstart pointerdown MSPointerDown', touch_selector, function (e) {
		startX = startY = endX = endY = angle = distanceX = distanceY = 0;
		startX = e.originalEvent.pageX || e.originalEvent.touches[0].pageX;
		startY = e.originalEvent.pageY || e.originalEvent.touches[0].pageY;
		dragging = !0;
		//兼容swiper
		if ($(e.target).parentsUntil(touch_selector, '.swiper-container,.scroll-x').length) {
			dragging = !1;
		}
	})
		.on('touchmove pointermove MSPointerMove', touch_selector, function (a) {
			var _move = options.start_selector ? (options.selector ? _e.find(options.selector) : _e.find(options.start_selector)) : $(this);
			if ($.isFunction(options.stop)) {
				is_stop = options.stop(_e, _move, startX, startY);
			}
			if (dragging && !is_stop) {
				endX = a.originalEvent.pageX || a.originalEvent.touches[0].pageX;
				endY = a.originalEvent.pageY || a.originalEvent.touches[0].pageY;
				distanceX = endX - startX;
				distanceY = endY - startY;
				angle = (180 * Math.atan2(distanceY, distanceX)) / Math.PI;
				'right' == options.direction && ((distanceY = 0), (distanceX = angle > -40 && angle < 40 && distanceX > 0 ? distanceX : 0));
				'left' == options.direction && ((distanceY = 0), (distanceX = (angle > 150 || angle < -150) && 0 > distanceX ? distanceX : 0));
				'top' == options.direction && ((distanceX = 0), (distanceY = angle > -130 && angle < -50 && 0 > distanceY ? distanceY : 0));
				'bottom' == options.direction && ((distanceX = 0), (distanceY = angle > 50 && angle < 130 && distanceY > 0 ? distanceY : 0));
				if (distanceX !== 0 || distanceY !== 0) {
					a.preventDefault ? a.preventDefault() : (a.returnValue = !1);
					cssTransition(_move, distanceX, distanceY, dragging, 'grab');
					$.isFunction(options.onIng) && options.onIng(_e, _move, distanceX, distanceY);
				}
			}
		})
		.on('touchend touchcancel pointerup MSPointerUp', touch_selector, function () {
			var _move = options.start_selector ? (options.selector ? _e.find(options.selector) : _e.find(options.start_selector)) : $(this);
			if (dragging && !is_stop) {
				cssTransition(_move, 0, 0, 'null', '');
				$.isFunction(options.inEnd) && options.inEnd(_e, _move, distanceX, distanceY);
				if (Math.abs(distanceX) > dep || Math.abs(distanceY) > dep) {
					$.isFunction(options.onEnd) && options.onEnd(_e, _move, distanceX, distanceY);
				}
				startX = startY = endX = endY = angle = distanceX = distanceY = 0;
				dragging = !1;
			}
		})
		.data(is_on, true);
};

$.fn.serializeObject = function () {
	var t = {}
		, e = this.serializeArray();
	return $.each(e, function () {
		void 0 !== t[this.name] ? (t[this.name].push || (t[this.name] = [t[this.name]]),
			t[this.name].push(this.value || "")) : t[this.name] = this.value || ""
	}),
		t
}
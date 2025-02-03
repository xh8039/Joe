if (!window.Joe) window.Joe = {};
window.Joe.options = window.Joe.options ? window.Joe.options : {};

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
				$(options.element).attr('data-turbolinks', 'false');
				$(options.element).attr('ajax-replace', 'true');
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
					$(".comment-list [data-toggle='popover']").popover({ html: true });
					if (options.replace) options.replace(response);
					if (options.scrollTo != undefined) Joe.scrollTo(options.scrollTo);
				},
				error(xhr, status, error) {
					options.error(xhr, status, error);
				}
			};
			if (options.processData != undefined) ajax.processData = options.processData;
			if (options.contentType != undefined) ajax.contentType = options.contentType;
			if (options.data != undefined) ajax.data = options.data;
			$.ajax(ajax);
		}
	}(options);
}

window.Joe.clipboard = (content, success, error = () => { autolog.log('复制失败！', 'error') }) => {
	if (location.protocol == 'https:' && 'clipboard' in navigator) {
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

window.Joe.internalUrl = (string) => {
	try {
		if (string instanceof Element) {
			if ($(string).attr('target') == '_blank') return false;
			if ($(string).attr('ajax-replace') != undefined) return false;
			if ($(string).attr('data-pjax-state') != undefined) return false;
			string = string.href;
		}
		if (string.startsWith('/')) return true;
		let url = new URL(string);
		if (url.host != location.host) return false;
		if (url.protocol == 'javascript:') return false;
		console.log(url);
	} catch (error) {
		return false;
	}
	return true;
}

window.Joe.scrollTo = (selector) => {
	const reservedHeight = document.querySelector('.joe_header').offsetHeight + 15;
	var top;
	if (/^\d+$/.test(selector)) {
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

window.Joe.tooltip = (selectors = '') => {
	if (Joe.IS_MOBILE) {
		// 遍历所有的元素
		$(selectors + ' [data-toggle="tooltip"]').each(function () {
			// 获取当前元素的data-original-title属性
			var title = $(this).attr('data-original-title') || $(this).attr('title');
			// 设置title属性为data-original-title的值
			$(this).attr('title', title);
			['data-toggle', 'data-placement'].forEach(value => {
				$(this).removeAttr(value);
			});
		});
	} else {
		$(selectors + ' [data-toggle="tooltip"]').tooltip({
			container: "body"
		});
		$(selectors + ' [data-toggle="tooltip"]').on('click', function (event) {
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

/** 文章/页面 浏览功能 */
window.Joe.article_view = () => {
	const encryption = str => window.btoa(unescape(encodeURIComponent(str)));
	const decrypt = str => decodeURIComponent(escape(window.atob(str)));
	const cid = window.Joe.CONTENT.cid || $('.joe_detail').attr('data-cid');
	let viewsArr = localStorage.getItem(encryption('views')) ? JSON.parse(decrypt(localStorage.getItem(encryption('views')))) : [];
	const flag = viewsArr.includes(cid);
	if (!flag) {
		$.ajax({
			url: Joe.BASE_API,
			type: 'POST',
			dataType: 'json',
			data: { routeType: 'handle_views', cid },
			success(res) {
				if (res.code !== 1) return;
				$('#Joe_Article_Views').html(res.data.views);
				viewsArr.push(cid);
				const name = encryption('views');
				const val = encryption(JSON.stringify(viewsArr));
				localStorage.setItem(name, val);
			}
		});
	}
}

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
		url: Joe.BASE_API,
		type: 'POST',
		dataType: 'json',
		data: {
			routeType: 'baidu_push',
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
		url: Joe.BASE_API,
		type: 'POST',
		dataType: 'json',
		data: {
			routeType: 'bing_push',
			domain: window.location.protocol + '//' + window.location.hostname,
			url: encodeURI(window.location.href)
		}
	});
}

window.Joe.get_baidu_record = () => {
	if (!document.getElementById('Joe_Baidu_Record')) return;
	$.ajax({
		url: Joe.BASE_API,
		type: 'POST',
		dataType: 'json',
		data: { routeType: 'baidu_record', site: window.location.href, cid: window.Joe.CONTENT.cid },
		success(res) {
			if (!res.data) {
				if (Joe.options.BaiduPush) {
					$('#Joe_Baidu_Record').html(`<a href="javascript:window.Joe.submit_baidu();" style="color: #F56C6C">检测失败，提交收录</a>`);
					return
				}
				const url = `https://ziyuan.baidu.com/linksubmit/url?sitename=${encodeURI(window.location.href)}`;
				$('#Joe_Baidu_Record').html(`<a target="_blank" href="${url}" rel="noopener noreferrer nofollow" style="color: #F56C6C">检测失败，提交收录</a>`);
				return
			}
			if (res.data == '未收录，已推送') {
				$('#Joe_Baidu_Record').css('color', 'var(--theme)');
				$('#Joe_Baidu_Record').html(res.data);
				return
			}
			if (res.data == '已收录') {
				$('#Joe_Baidu_Record').css('color', '#67C23A');
				$('#Joe_Baidu_Record').html('已收录');
				return
			}
			/* 如果填写了Token，则自动推送给百度 */
			if ((res.data == '未收录') && (Joe.options.BaiduPush)) {
				window.Joe.submit_baidu('未收录，推送中...');
				return
			}
			if (Joe.options.BaiduPush) {
				$('#Joe_Baidu_Record').html(`<a href="javascript:window.Joe.submit_baidu();" style="color: #F56C6C">${res.data}，提交收录</a>`);
				return
			}
			const url = `https://ziyuan.baidu.com/linksubmit/url?sitename=${encodeURI(window.location.href)}`;
			$('#Joe_Baidu_Record').html(`<a target="_blank" href="${url}" rel="noopener noreferrer nofollow" style="color: #F56C6C">${res.data}，提交收录</a>`);
		}
	});
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
	noty != 'stop' && autolog.log(noty || '正在处理请稍后...', 'load', '', 'wp_ajax', 'warn');
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
			autolog.log(_msg, 'error');
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
				autolog.log(n.msg || '处理完成', 'success');
			} else if (n.msg) {
				// Qmsg.success(n.msg, ys);
				autolog.log(n.msg, 'success');
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
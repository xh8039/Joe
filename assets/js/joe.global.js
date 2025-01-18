Joe.DOMContentLoaded.global = Joe.DOMContentLoaded.global ? Joe.DOMContentLoaded.global : () => {
	/* 检测IE */
	{
		function detectIE() {
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
		};
		detectIE() && (alert('当前站点不支持IE浏览器或您开启了兼容模式，请使用其他浏览器访问或关闭兼容模式。'), (location.href = 'https://www.baidu.com'))
	}

	/* 设置$.getScript()方法缓存 */
	{
		jQuery.ajaxSetup({ cache: true });
	}

	/** 初始化评论 */
	{
		document.addEventListener('pjax:send', (options) => {
			if (options.pjax != "comment-pagination") return;
			if ($('#comment_module>.joe_pagination a').length) {
				window.Joe.commentListAutoRefresh = false;
				$('#comment_module>.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
			}
		});
	}

	/** 通用Pjax成功回调 */
	{
		document.addEventListener('pjax:success', (options) => {
			window.Joe.commentListAutoRefresh = true;
			console.log('pjax-success：' + options.pjax);
			if (window.Joe.tooltip) window.Joe.tooltip();
			$(".comment-list [data-toggle='popover']").popover();
			if (options.pjax == 'comment-submit' || options.pjax == 'comment-pagination') {
				if (Joe.initComment) Joe.initComment({
					draw: false,
					owo: false,
					submit: false
				});
			}
			if (options.pjax == 'comment-auto-refresh') {
				if (Joe.initComment) Joe.initComment({
					draw: false,
					owo: false,
					submit: false,
					pagination: false,
				});
			}
			if (options.pjax == 'global') {

			}
		});
	}

	/* 切换标签显示不同的标题 */
	{
		if (Joe.options.JDocumentTitle) {
			const TITLE = document.title;
			document.addEventListener("visibilitychange", () => {
				if (document.visibilityState === "hidden") {
					document.title = Joe.options.JDocumentTitle;
				} else {
					document.title = TITLE;
				}
			});
		}
	}

	{
		$(document).on('click', '[data-toggle-class]', function () {
			var c = $(this).attr('data-toggle-class') || 'show';
			var e = $(this).attr('data-target') || this;
			return $(e).toggleClass(c).trigger('toggleClass'), !1;
		});
	}

	/** 页面滚动监听函数 */
	{
		if (!window.Joe.IS_MOBILE) {
			var flag = true;
			function handleHeader(diffY) {
				const headerAbove = document.querySelector('.joe_header__above');
				if (window.pageYOffset >= $(".joe_header").height() && diffY <= 0) {
					if (flag) return;
					if (headerAbove) {
						$(".joe_header").addClass("active");
						$(".joe_aside .joe_aside__item:last-child").css("top", $(".joe_header").height() - 60 + 23);
					} else {
						$(".joe_aside .joe_aside__item:last-child").css("top", $(".joe_header").height() + 25);
					}
					flag = true;
				} else {
					if (!flag) return;
					if (headerAbove) {
						$(".joe_header").removeClass("active");
						$(".joe_aside .joe_aside__item:last-child").css("top", $(".joe_header").height() + 23);
					} else {
						$(".joe_aside .joe_aside__item:last-child").css("top", $(".joe_header").height() + 25);
					}
					flag = false;
				}
			};
			var Y = window.pageYOffset;
			handleHeader(Y);

			var lastPostNav = $(".joe_aside .joe_aside__item.posts-nav-box:last");
			if (lastPostNav.length > 0) {
				var lastPostNavHeight = lastPostNav.height();
				lastPostNav.hide();
				var asideHeight = 0;
				$('.joe_aside .joe_aside__item').each(function (index, element) {
					asideHeight += $(element).height();
				});
				asideHeight = (asideHeight - lastPostNavHeight) - $(".joe_header").height();
			}
		}
		$(window).scroll(throttle(() => {
			// 激活全局返回顶部功能
			var h = document.documentElement.scrollTop + document.body.scrollTop;
			var ontop = $(".joe_action_item.scroll");
			h > 100 ? $('body').addClass('body-scroll') : $('body').removeClass('body-scroll');
			h > 400 ? ontop.addClass('active') : ontop.removeClass('active');

			// 头部滚动
			if (!window.Joe.IS_MOBILE) {
				if (lastPostNav.length > 0) {
					if (h > asideHeight && lastPostNav.is(":hidden")) {
						lastPostNav.fadeIn('slow');
					}
					if (h < asideHeight && lastPostNav.is(":visible")) {
						lastPostNav.fadeOut('slow');
					}
				}
				handleHeader(Y - window.pageYOffset);
				Y = window.pageYOffset;
			}
		}, 100))//.trigger("scroll");

		// 页面滚动隐藏 tooltip 提示
		$(window).scroll(debounce(() => {
			if (!Joe.IS_MOBILE) $("[data-toggle='tooltip']").tooltip('hide');
		}, 500, true));
	}

	/* 监听移动端键盘弹出 */
	{
		const footerTabbar = document.querySelector('.footer-tabbar');
		const joeAction = document.querySelector('.joe_action');
		const aplayer = document.querySelector('.aplayer.aplayer-fixed');
		if (footerTabbar || joeAction || aplayer) {
			const ua = typeof window === 'object' ? window.navigator.userAgent : '';
			let _isIOS = -1;
			let _isAndroid = -1;
			const isIOS = () => {
				if (_isIOS === -1) {
					_isIOS = /iPhone|iPod|iPad/i.test(ua) ? 1 : 0;
				}
				return _isIOS === 1;
			}
			const isAndroid = () => {
				if (_isAndroid === -1) {
					_isAndroid = /Android/i.test(ua) ? 1 : 0;
				}
				return _isAndroid === 1;
			}
			const popUp = () => {
				if (footerTabbar) document.querySelector('.footer-tabbar').style.display = 'none';
				if (joeAction) document.querySelector('.joe_action').style.display = 'none';
				if (aplayer) document.querySelector('.aplayer.aplayer-fixed').style.display = 'none';
			}
			const retract = () => {
				if (footerTabbar) footerTabbar.style.display = null;
				if (joeAction) document.querySelector('.joe_action').style.display = null;
				if (aplayer) document.querySelector('.aplayer.aplayer-fixed').style.display = null;
			}
			if (isAndroid()) {
				const innerHeight = window.innerHeight;
				window.addEventListener('resize', () => {
					const newInnerHeight = window.innerHeight;
					if (innerHeight > newInnerHeight) {
						// 键盘弹出事件处理
						popUp();
					} else {
						// 键盘收起事件处理
						retract();
					}
				});
			} else if (isIOS()) {
				window.addEventListener('focusin', () => {
					// 键盘弹出事件处理
					popUp();
				});
				window.addEventListener('focusout', () => {
					// 键盘收起事件处理
					retract();
				});
			}
		}
	}

	/** 动态监听实际VH高度 */
	{
		if (window.Joe.IS_MOBILE) {
			function resetVhAndPx() {
				let vh = window.innerHeight * 0.01
				document.documentElement.style.setProperty('--vh', `${vh}px`)
				// document.documentElement.style.fontSize = document.documentElement.clientWidth / 375 + 'px'
				console.log('重新计算VH高度')
			}
			resetVhAndPx();
			// 监听resize事件 视图大小发生变化就重新计算1vh的值
			window.addEventListener('resize', resetVhAndPx);
		}
	}

	/** 模态框 */
	{
		var _wid = $(window).width();
		var _hei = $(window).height();
		// 模态框居中
		$(document).on('show.bs.modal loaded.bs.modal', '.modal:not(.flex)', function () {
			var o = $(this);
			var i = o.find('.modal-dialog');
			o.css('display', 'block');
			i.css({ 'margin-top': Math.max(0, (_hei - i.height()) / 2), });
		});

		// 每次都刷新的模态框
		$(document).on('click', '[data-toggle="RefreshModal"]', function () {
			var _this = $(this);
			var dataclass = _this.attr('data-class') || '';
			var remote = _this.attr('data-remote');
			var height = _this.attr('data-height') || 300;
			var mobile_bottom = _this.attr('mobile-bottom') && _wid < 769 ? ' bottom' : '';
			var modal_class = 'modal flex jc fade' + mobile_bottom;
			var id = 'refresh_modal';
			var is_new = _this.attr('new');
			id += is_new ? parseInt((Math.random() + 1) * Math.pow(10, 4)) : '';

			var _id = '#' + id;

			dataclass += ' modal-dialog';
			var modal_html =
				'<div class="' +
				modal_class +
				'" id="' +
				id +
				'" tabindex="-1" role="dialog" aria-hidden="false">\
		<div class="' +
				dataclass +
				'" role="document">\
		<div class="modal-content">\
		</div>\
		</div>\
		</div>';

			var loading = '<div class="modal-body" style="display:none;"></div><div class="flex jc loading-mask absolute main-bg radius8"><div class="em2x opacity5"><i class="loading"></i></div></div>';
			// console.log(_id);
			var _modal = $(_id);
			if (_modal.length) {
				if (_modal.hasClass('in')) modal_class += ' in';
				_modal.removeClass().addClass(modal_class);
				_modal.find('.modal-dialog').removeClass().addClass(dataclass);
				_modal.find('.loading-mask').fadeIn(200);
				_modal
					.find('.modal-content')
					.css({
						overflow: 'hidden',
					})
					.animate({
						height: height,
					});
			} else {
				$('body').append(modal_html);
				_modal = $(_id);
				if (is_new) {
					_modal.on('hidden.bs.modal', function () {
						$(this).remove();
					});
				}
				_modal.find('.modal-content').html(loading).css({
					height: height,
					overflow: 'hidden',
				});
				if (_wid < 769) {
					_modal.minitouch({
						direction: 'bottom',
						selector: '.modal-dialog',
						start_selector: '.modal-colorful-header,.touch-close,.touch',
						onEnd: function () {
							_modal.modal('hide');
						},
						stop: function () {
							return !_modal.hasClass('bottom');
						},
					});
				}
			}

			_modal.find('.touch-close').remove();
			var touch_close = '<div class="touch-close"></div>';
			if (mobile_bottom && !_this.attr('no-touch')) {
				_modal.find('.modal-dialog').append(touch_close);
			}

			_modal.modal('show');

			$.get(remote, null, function (data) {
				try {
					jsonData = JSON.parse(data);
					if (jsonData) {
						Qmsg.error(jsonData.message);
						_modal.modal('hide');
						return;
					}
				} catch (e) {
					// console.log(data);
					_modal.find('.modal-body').html(data).slideDown(200, function () {
						_modal.trigger('loaded.bs.modal').find('.loading-mask').fadeOut(200);
						var b_height = $(this).outerHeight();
						_modal.find('.modal-content').animate(
							{
								height: b_height,
							},
							200,
							'swing',
							function () {
								_modal.find('.modal-content').css({
									height: '',
									overflow: '',
									transition: '',
								});
							}
						);
					});
				}
			});
			return false;
		});
	}

	{
		//搜索多选择
		$(document).on('click', '[data-for]', function () {
			var _this = $(this);
			var _tt;
			var _for = _this.attr('data-for');
			var _f = _this.parents('form');
			var _v = _this.attr('data-value');
			var multiple = _this.attr('data-multiple');
			var _group = _this.closest('[for-group]');
			if (!_group.length) {
				_group = _this.parent();
			}

			if (multiple) {
				_tt = '';
				var active_array = [];
				var _input = '';
				var is_active = _this.hasClass('active');
				if (!is_active) {
					//添加
					if (_group.find('[data-for="_for"].active').length >= multiple) {
						return Qmsg.info('最多可选择' + multiple + '个', 'danger');
					}
				}

				if (is_active) {
					//已存在-删除
					_group.find('[data-for="' + _for + '"][data-value="' + _v + '"]').removeClass('active');
				} else {
					//不存在-添加
					_group.find('[data-for="' + _for + '"][data-value="' + _v + '"]').addClass('active');
				}

				_group.find('[data-for="' + _for + '"].active').each(function () {
					var _this_value = $(this).attr('data-value');
					//不重复
					if (active_array.indexOf(_this_value) == -1) {
						_tt += $(this).html();
						_input += '<input type="hidden" name="' + _for + '[]" value="' + _this_value + '">';
						active_array.push(_this_value);
					}
				});

				//循环将所有的active_array添加active的calass
				$.each(active_array, function (index, value) {
					_group.find('[data-for="' + _for + '"][data-value="' + value + '"]').addClass('active');
				});

				_f.find("input[name='" + _for + "[]']").remove();
				_f.append(_input);
			} else {
				_group.find('[data-for="' + _for + '"]').removeClass('active');
				_group.find('[data-for="' + _for + '"][data-value="' + _v + '"]').addClass('active');

				_tt = _this.html();
				_f.find("input[name='" + _for + "']")
					.val(_v)
					.trigger('change');
			}

			_f.find("span[name='" + _for + "']").html(_tt);
			_f.find('input[name=s]').focus();
		});
	}

	/** 文章列表缩略图加载失败自动使用主题自带缩略图 */
	{
		document.addEventListener("error", function (event) {
			var element = event.target;
			if (element.tagName.toLowerCase() == 'img' && element.classList.contains('error-thumbnail') && !element.dataset.thumbnailLoaded) {
				// 生成一个 1 到 42 之间的随机整数
				const randomNumber = Math.floor(Math.random() * 41) + 1;
				// 将随机数格式化为两位数
				const formattedNumber = ("0" + randomNumber).slice(-2);
				const thumb = `${Joe.THEME_URL}assets/images/thumb/${formattedNumber}.jpg`;
				$(element).attr('data-src', thumb);
				element.src = thumb;
				element.dataset.thumbnailLoaded = true;
			}
		}, true);
	}

	/** 头像加载失败代替 */
	{
		document.addEventListener("error", function (event) {
			var element = event.target;
			if (element.tagName.toLowerCase() == 'img' && element.classList.contains('avatar') && !element.dataset.defaultAvatarLoaded) {
				element.setAttribute('data-src', Joe.THEME_URL + 'assets/images/avatar-default.png');
				element.setAttribute('src', Joe.THEME_URL + 'assets/images/avatar-default.png');
				element.dataset.defaultAvatarLoaded = true;
			}
		}, true);
	}

	/** 全局Loading动画补全 */
	if (window.Joe.options.JLoading == 'on') {
		window.Joe.loadingEnd();
		if (window.Joe.loadingStart && window.Joe.loadingEnd && window.Joe.options.FirstLoading != 'on') {
			// a标签加载动画
			$(document).on('click', 'a[href]:not([href=""])', function (e) {
				if (window.Joe.checkUrl(this)) window.Joe.loadingStart();
				setTimeout(() => {
					window.Joe.loadingEnd();
				}, 5000);
				window.addEventListener('unload', function (event) {
					window.Joe.loadingEnd();
				});
			});
		}
	}

	/* NProgress.js */
	if (window.NProgress) {
		NProgress.configure({ trickleSpeed: 10 });
		$(document).on('turbolinks:click', function () {
			NProgress.start();
		});
		$(document).on('turbolinks:render', function () {
			NProgress.done();
			NProgress.remove();
		});
		if (window.Joe.options.NProgressJS == 'on') {
			$(document).on('click', 'a[href]:not([href=""])', function (e) {
				if (window.Joe.checkUrl(this)) {
					NProgress.start();
					window.addEventListener('visibilitychange', function () {
						if (document.visibilityState === 'hidden') NProgress.done();
					});
					window.addEventListener('pagehide', function (event) {
						NProgress.done();
					});
					window.addEventListener('unload', function (event) {
						NProgress.remove();
					});
				}
			});
		}
	}

	if (window.Turbolinks) {
		document.addEventListener('turbolinks:request-start', function (event) {
			event.data.xhr.setRequestHeader('X-Turbolinks', 'true')
		})
		$(document).on('click', 'a[href]:not([href=""])', function (event) {
			if (!window.Joe.checkUrl(this)) return true;
			event.preventDefault(); // 阻止默认行为
			let url = this.href;
			if (url.startsWith('/')) url = location.origin + url;
			NProgress.start();
			Turbolinks.visit(url);
		});
	}

	if (window.Joe.options.Turbolinks == 'on') {
		// document.addEventListener('turbolinks:request-start', function (event) {
		// 	event.data.xhr.setRequestHeader('X-Turbolinks', 'true')
		// })
		$(document).on('click', 'a[href]:not([href=""])', function (event) {
			if (!window.Joe.checkUrl(this)) return true;
			event.preventDefault(); // 阻止默认行为
			let url = this.href;
			if (url.startsWith('/')) url = location.origin + url;
			NProgress.start();
			var pjax = new Pjax({
				// elements: '#global-pjax-element',
				selectors: ["#Joe", 'script:not([data-turbolinks-permanent])', 'link'],
				pjax: 'global',
			});
			pjax.loadUrl(url);
		});
	}
}
document.addEventListener('DOMContentLoaded', Joe.DOMContentLoaded.global);
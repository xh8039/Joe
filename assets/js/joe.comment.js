Joe.DOMContentLoaded.comment ||= () => {
	console.log('调用：Joe.DOMContentLoaded.comment');

	/* 评论框点击切换画图模式和文本模式 */
	(() => {
		const switchMode = (type) => {
			$('.joe_comment__respond-form').attr('data-type', type).find(`.body .${type}`).show().siblings().hide();
			$('.joe_comment__respond-form .foot .owo').css('opacity', type == 'draw' ? 0 : 1);
		};

		const initSketchpad = () => {
			const drawArea = document.getElementById('joe_comment_draw');
			if (!drawArea.dataset.sketchpad) {
				const width = $('.joe_comment__respond-form .body').width();
				Joe.sketchpad = new Sketchpad({
					element: '#joe_comment_draw',
					height: 300,
					width: width,
					penSize: 5,
					color: '303133'
				});
				drawArea.dataset.sketchpad = true;
			}
		};

		const loadSketchpad = () => {
			const scriptUrl = `${Joe.CDN_URL}sketchpad/0.1.0/scripts/sketchpad.min.js`;
			$.getScript(scriptUrl).done(initSketchpad).fail(console.error);
		};

		Joe.$body.on('click', '.joe_comment__respond-type .item', function () {
			if (!document.querySelector('.joe_comment__respond-form')) return;
			if (!document.getElementById('joe_comment_draw')) return;
			const type = $(this).addClass('active').siblings().removeClass('active').end().data('type');
			if (type == 'draw') window.Sketchpad ? initSketchpad() : loadSketchpad();
			switchMode(type);
		});

		if (Joe.options.JcommentDraw === 'on') {
			const handleDrawAction = (selector, callback) => {
				Joe.$body.on('click', `.joe_comment__respond-form .body .draw ${selector}`, callback);
			};
			handleDrawAction('.icon-undo', () => Joe.sketchpad?.undo());
			handleDrawAction('.icon-animate', () => Joe.sketchpad?.animate(10));
			handleDrawAction('.line li', function () {
				Joe.sketchpad.penSize = $(this).addClass('active').siblings().removeClass('active').end().data('line');
			});
			handleDrawAction('.color li', function () {
				Joe.sketchpad.color = $(this).addClass('active').siblings().removeClass('active').end().data('color');
			});
		}
	})();

	/* 重写评论功能 */
	(() => {
		/* 重写回复功能 */
		$(document.body).on('click', '.joe_comment__reply', function () {
			if (Joe.IS_MOBILE) $(`.comment-list__item .content`).tooltip('destroy');
			const respond = $(".joe_comment__respond");
			/* 父级ID */
			const coid = $(this).attr('data-coid');
			/* 当前的项 */
			const item = $("#" + $(this).attr("data-id"));
			/* 添加自定义属性表示父级ID */
			respond.find(".joe_comment__respond-form").attr('data-coid', coid);
			item.append(respond);
			$(".joe_comment__respond-type .item[data-type='text']").click();
			$(".joe_comment__cancle").show();
			Joe.scrollTo(item.offset().top);
			window.Joe.commentListAutoRefresh = false;
		});
		/* 重写取消回复功能 */
		$(document.body).on('click', '.joe_comment__cancle', function () {
			const respond = $(".joe_comment__respond");
			/* 移除自定义属性父级ID */
			respond.find(".joe_comment__respond-form").removeAttr('data-coid');
			$(".joe_comment__cancle").hide();
			$(".joe_comment>.comment-list").before(respond);
			$(".joe_comment__respond-type .item[data-type='text']").click();
			window.Joe.commentListAutoRefresh = true;
		});
		/* 评论操作 */
		$(document.body).on('click', '.joe_comment__operate', function () {
			const $button = $(this);
			const coid = $button.attr('data-coid');
			const button_html = $button.html();
			const operate_list = { delete: '删除', waiting: '标记审核', spam: '标记垃圾' };
			const status = $button.attr('status');
			const operate = operate_list[status];
			$button.html(`<i class="loading mr3"></i>${operate}中...`);
			$button.addClass('disabled');
			$.get(Joe.BASE_API + '/comment-operate', { coid, status }, function (data, textStatus, jqXHR) {
				if (data.code == 200) {
					if (Joe.IS_MOBILE) $(`.comment-list__item .content`).tooltip('destroy');
					$('.joe_comment__title small').html(`共${data.commentsNum}条`);
					$('.comment-list__item[data-coid="' + coid + '"]').hide('fast', () => {
						$('.comment-list__item[data-coid="' + coid + '"]').remove();
						autolog.log(`评论 ${coid} 已${operate}`, 'success');
					});
				} else {
					autolog.log(data.message, 'error', false);
					$button.html(button_html);
					$button.removeClass('disabled');
				}
			}, 'json');
		});
		/* 移动端评论长按回复或删除 */
		if (!Joe.IS_MOBILE) return;
		$(document.body).on('click', '.comment-list__item .content', function () {
			const coid = $(this).attr('data-coid');
			const data_id = $(this).attr('data-id');
			let html = `<span class="joe_comment__reply" data-id="${data_id}" data-coid="${coid}">回复</span>`;
			if (Joe.user.group == 'administrator') html = `<span class="joe_comment__operate" status="delete" data-coid="${coid}">删除</span>丨<span class="joe_comment__operate" status="spam" data-coid="${coid}">垃圾</span>丨<span class="joe_comment__operate" status="waiting" data-coid="${coid}">审核</span>丨` + html;
			$(`.comment-list__item .content:not([data-id="${data_id}"])`).tooltip('destroy');
			$(this).tooltip({
				html: true,
				sanitize: false,
				title: html,
				trigger: 'manual',
				container: 'body'
			}).tooltip('toggle');
		});
	})();

	/**
	 * 表情功能核心类
	 * 职责：管理表情功能的初始化、事件处理、数据加载和UI渲染
	 */
	new class {

		/** 初始化时缓存DOM元素并绑定事件 */
		constructor() {
			console.log('初始化评论表情功能')
			this.cacheElements();
			this.initEvents();
		}

		/** 缓存常用DOM元素，减少重复查询 */
		cacheElements() {
			this.$container = $('.joe_owo__contain');
			this.$box = this.$container.find('.box');
			this.$seat = this.$container.find('.seat');
		}

		/** 事件委托：统一管理所有交互事件 */
		initEvents() {
			// 点击触发按钮
			Joe.$body.on('click', '.joe_owo__contain .seat', (event) => {
				event.stopPropagation();

				// 检查本地缓存，有缓存直接渲染;
				var localEmo;
				try {
					localEmo = localStorage.getItem('comment-emote');
					localEmo = localEmo ? JSON.parse(localEmo) : null;
				} catch {
					localEmo = null;
				}
				if (localEmo) return this.renderUI(localEmo);

				// 异步加载数据
				this.fetchEmoticonsData().then(data => {
					localStorage.setItem('comment-emote', JSON.stringify(data));
					this.renderUI(data);
				}).catch(() => {
					console.warn("无法加载表情包数据")
				}).finally(() => {
					// 无论成功失败都重置按钮状态
					this.$seat.html('OωO').removeClass('disabled')
				});
			});

			// 处理表情项点击（插入内容到输入框）
			Joe.$body.on('click', '.joe_owo__contain .scroll .item', (event) => {
				$(".joe_owo__target").insertContent($(event.currentTarget).data('text'));
			});

			// 点击分类标签，处理分类标签切换
			Joe.$body.on('click', '.joe_owo__contain .bar .item', (event) => {
				const $target = $(event.currentTarget);
				const type = $target.data('type');
				// 切换激活状态
				$target.addClass('active').siblings().removeClass('active');
				// 显示对应分类的表情
				this.$box.find(`.scroll[data-type="${type}"]`).show().siblings('.scroll').hide();
			});
		}

		/**
		 * 异步获取表情数据
		 * 1. 多数据源备选
		 * 2. 自动切换数据源
		 * 3. 统一的加载状态管理
		 */
		async fetchEmoticonsData() {
			const urls = [
				`${window.Joe.THEME_URL}assets/json/joe.owo.json`,
				`${window.Joe.THEME_URL}assets/json/joe.owo.php`,
				`${window.Joe.options.themeUrl}/assets/json/joe.owo.json`,
			];

			// 显示加载状态
			this.$seat.html('<i class="loading mr3"></i>').addClass('disabled');

			// 顺序尝试多个数据源
			for (const url of urls) {
				try {
					const response = await fetch(url);
					if (!response.ok) continue;  // 跳过无效响应
					return await response.json();
				} catch (error) {
					console.debug(`加载失败: ${url}`);
				}
			}
			throw new Error('所有数据源加载失败');
		}

		/**
		 * 渲染主界面
		 * 1. 已存在时直接切换显示状态
		 * 2. 批量DOM操作减少重绘
		 */
		renderUI(data) {
			// 如果已经初始化过，直接切换显示
			if (this.$container.find('.bar .item').length) return this.toggleBox();

			// 生成内容结构
			const { barHTML, scrollHTML } = this.generateContent(data);
			this.$box.html(`${scrollHTML}<div class="bar no-scrollbar">${barHTML}</div>`);

			this.toggleBox(); // 显示容器
			this.$container.find('.bar .item').first().trigger('click'); // 触发默认分类
			window.Joe.tooltip('.joe_owo__contain .scroll .item'); // 初始化工具提示
		}

		/** 生成内容结构（分离逻辑与视图） */
		generateContent(data) {
			let barHTML = [];  // 分类标签
			let scrollHTML = []; // 表情列表
			const OwOUrl = this.$container.data('url') || window.Joe.THEME_URL;

			// 遍历数据生成结构
			for (const [rawKey, items] of Object.entries(data)) {
				const key = rawKey.replace('表情', '');
				barHTML.push(`<div class="item" data-type="${key}">${key}</div>`);
				scrollHTML.push(this.generateScrollItems(key, items, OwOUrl));
			}

			return {
				barHTML: barHTML.join(''),
				scrollHTML: scrollHTML.join('')
			};
		}

		/** 生成单个分类的表情项 */
		generateScrollItems(key, items, OwOUrl) {
			const isTextType = ['颜文字', 'emoji'].includes(key);
			const itemsHTML = items.map(item => {
				// 文本类型表情（颜文字/emoji）
				if (isTextType) return `<li data-toggle="tooltip" title="${item.text}" class="item" data-text="${item.icon}">${item.icon}</li>`;

				// 图片类型表情
				const titleMatch = item.text.match(/\((.*?)\)/);
				const title = titleMatch ? titleMatch[1] : item.text;  // 增强容错处理
				return `<li data-toggle="tooltip" title="${title}" class="item" data-text="${item.text}"><img class="lazyload" src="${window.Joe.options.JLazyload}" data-src="${OwOUrl + item.icon}" title="${title}" alt="${title}"/></li>`;
			}).join('');

			return `<ul class="scroll" data-type="${key}">${itemsHTML}</ul>`;
		}

		/** 切换容器显示状态 */
		toggleBox() {
			this.$seat.siblings('.box').stop().slideToggle("fast");
		}
	};

	/* 激活评论提交 */
	(() => {
		$(document.body).on('keydown', '.joe_comment__respond-form textarea', function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				$(".joe_comment__respond-form").submit();
			}
		});
		$(document.body).on('submit', '.joe_comment__respond-form', function (event) {
			event.preventDefault();
			window.Joe.commentListAutoRefresh = false;
			var isSubmit = false;
			const submit_html = $(".joe_comment__respond-form .foot .submit button").html();
			const action = $(".joe_comment__respond-form").attr("action") + "?time=" + +new Date();
			const type = $(".joe_comment__respond-form").attr("data-type");
			const parent = $(".joe_comment__respond-form").attr('data-coid') || null;
			const author = $(".joe_comment__respond-form .head input[name='author']").val();
			const _ = $(".joe_comment__respond-form input[name='_']").val();
			const mail = $(".joe_comment__respond-form .head input[name='mail']").val();
			const url = $(".joe_comment__respond-form .head input[name='url']").val();
			let text = $(".joe_comment__respond-form .body textarea[name='text']").val();
			if (author.trim() === "") return autolog.log("请输入昵称！", 'info');
			if (!/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(mail)) return autolog.log("请输入正确的邮箱！", 'info');
			if (type === "text" && text.trim() === "") return autolog.log("请输入评论内容！", 'info');
			if (type === "draw") {
				const txt = $("#joe_comment_draw")[0].toDataURL("image/webp", 0.1);
				text = "{!{" + txt + "}!} ";
			}
			if (isSubmit) return;

			isSubmit = true;
			$(".joe_comment__respond-form .foot .submit button").html('<i class="loading mr6"></i>发送中...');
			$(".joe_owo__contain .box").stop().slideUp("fast");

			var data = new FormData();
			data.append('author', author);
			data.append('mail', mail);
			data.append('text', text);
			if (parent) data.append('parent', parent);
			data.append('url', url);
			data.append('_', _);
			var referrer = document.querySelector('meta[name="referrer"]:last-of-type');
			if (referrer && referrer.content == 'no-referrer') window.Joe.addMeta('referrer', 'unsafe-url');
			window.Joe.pjax({
				type: "POST",
				url: action,
				data: data,
				processData: false,
				contentType: false,
				selectors: ["#comment_module>.comment-list", '.joe_comment__title>small', '#comment_module>.joe_pagination'],
				beforeSend() {
					window.Joe.commentListAutoRefresh = false;
					$('#comment_module>.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
				},
				success() {
					if (referrer && referrer.content == 'no-referrer') window.Joe.addMeta('referrer', 'no-referrer');
					$('.joe_comment__cancle').click();
					if (Joe.IS_MOBILE) $(`.comment-list__item .content`).tooltip('destroy');
				},
				replace() {
					if (window.Joe.leavingListInit) window.Joe.leavingListInit();
					isSubmit = false;
					autolog.log('发送成功', 'success');
					$('textarea.joe_owo__target').val('');
					$(".joe_comment__respond-form .foot .submit button").html(submit_html).blur();
					$(".joe_comment__respond-form .body textarea[name='text']").focus();
					if ($('.joe_hide>.joe_hide__button').length) {
						window.Joe.pjax(window.location.href, ['.joe_detail__article']);
					}
				},
				error(xhr, status, error) {
					isSubmit = false;
					$(".joe_comment__respond-form .foot .submit button").html(submit_html).blur();
					responseText = xhr.responseText;
					let match = /<div class="container">\s+(.+)\s+<\/div>/;
					var msg = responseText.match(match)[1];
					if (msg) {
						autolog.log(msg, 'warn');
					} else {
						autolog.log('发送失败！请刷新重试！', 'warn');
					}
				}
			});
		});
	})();

	/* 评论分页 Ajax 切换 */
	(() => {
		$(document.body).on('click', '#comment_module>.joe_pagination a[href]', function (event) {
			event.preventDefault();
			let selectors = ["#comment_module>.comment-list", '#comment_module>.joe_pagination'];
			if (document.querySelector('.joe_detail__leaving')) selectors.push('.joe_detail__leaving');
			window.Joe.pjax(this.href, selectors, {
				beforeSend() {
					window.Joe.commentListAutoRefresh = false;
					$('#comment_module>.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
				},
				success() {
					$('.joe_comment__cancle').click();
					if (Joe.IS_MOBILE) $(`.comment-list__item .content`).tooltip('destroy');
				},
				replace() {
					if (window.Joe.leavingListInit) window.Joe.leavingListInit();
					Joe.scrollTo('.comment-list');
				}
			});
		});
	})();

	/** 评论区内容实时刷新 */
	(() => {
		if (!Joe.options.JcommentAutoRefresh) return;
		let time = Number(Joe.options.JcommentAutoRefresh);
		if (!time || !Number.isInteger(time)) return;
		window.Joe.commentListAutoRefresh = true;
		setInterval(() => {
			if (!document.querySelector('.comment-list')) return;
			if (document.visibilityState == "hidden" || document.hidden) return;
			if (!window.Joe.commentListAutoRefresh) return;
			if (!isElementInViewport(document.querySelector('.comment-list'))) return;
			let url = $('#comment_module>.joe_pagination>li.active>a').attr('href') || location.href;
			window.Joe.pjax(url, ['#comment_module>.comment-list', '.joe_comment__title>small'], {
				success() {
					return window.Joe.commentListAutoRefresh;
				},
				replace() {
					$("#comment_module>.joe_pagination a[href]").each((index, item) => {
						$(item).attr('ajax-replace', true);
					});
				}
			});
		}, time * 1000);
	})();

}

document.addEventListener('DOMContentLoaded', Joe.DOMContentLoaded.comment, { once: true });
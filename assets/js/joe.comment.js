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
			if (Joe.IS_MOBILE) $('.comment-list__item .content').tooltip('destroy');
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
			if (Joe.options.UISoundEffect) Joe.AudioManager.play(status == 'delete' ? 'Delete.ogg' : 'Ocelot.mp3');
			const operate = operate_list[status];
			$button.html(`<i class="loading mr3"></i>${operate}中...`);
			$button.addClass('disabled');
			$.get(Joe.BASE_API + '/comment-operate', { coid, status }, function (data, textStatus, jqXHR) {
				if (data.code == 200) {
					if (Joe.IS_MOBILE) $('.comment-list__item .content').tooltip('destroy');
					$('.joe_comment__title small').html(`共${data.commentsNum}条`);
					$('.comment-list__item[data-coid="' + coid + '"]').hide('fast', () => {
						$('.comment-list__item[data-coid="' + coid + '"]').remove();
						autolog.success(`评论 ${coid} 已${operate}`);
					});
				} else {
					autolog.error(data.message, false);
					$button.html(button_html);
					$button.removeClass('disabled');
				}
			}, 'json');
		});
		/* 移动端评论长按回复或删除 */
		if (!Joe.IS_MOBILE) return;
		$(document.body).on('focus', '.comment-list__item .content', function () {
			const coid = $(this).attr('data-coid');
			const data_id = $(this).attr('data-id');
			let html = `<span class="joe_comment__reply" data-id="${data_id}" data-coid="${coid}">回复</span>`;
			if (Joe.user.group == 'administrator') html = `<span class="joe_comment__operate" status="delete" data-coid="${coid}">删除</span>丨<span class="joe_comment__operate" status="spam" data-coid="${coid}">垃圾</span>丨<span class="joe_comment__operate" status="waiting" data-coid="${coid}">待审</span>丨` + html;
			// $(`.comment-list__item .content:not([data-id="${data_id}"])`).tooltip('destroy');
			$(this).tooltip({
				html: true,
				sanitize: false,
				title: html,
				trigger: 'manual',
				container: 'body'
			}).tooltip('show');
		});
		$(document.body).on('blur', '.comment-list__item .content', function () {
			$(this).tooltip('destroy');
		});
	})();

	/**
	 * 表情功能核心类
	 * 职责：管理表情功能的初始化、事件处理、数据加载和UI渲染
	 */
	new class {

		/** 初始化时缓存DOM元素并绑定事件 */
		constructor() {
			console.log('初始化评论表情功能');
			this.initEvents();
		}

		/** 事件委托：统一管理所有交互事件 */
		initEvents() {
			// 点击触发按钮
			Joe.$body.on('click', '.joe_owo__contain .seat', (event) => {
				event.stopPropagation();

				// 缓存常用DOM元素，减少重复查询
				this.$container = $('.joe_owo__contain');
				this.$box = this.$container.find('.box');
				this.$seat = this.$container.find('.seat');

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
			setTimeout(() => Joe.tooltip('.joe_owo__contain'), 500);
		}

		/** 生成内容结构（分离逻辑与视图） */
		generateContent(data) {
			let barHTML = [];  // 分类标签
			let scrollHTML = []; // 表情列表

			// 遍历数据生成结构
			for (const [rawKey, items] of Object.entries(data)) {
				const key = rawKey.replace('表情', '');
				barHTML.push(`<div class="item" data-type="${key}">${key}</div>`);
				scrollHTML.push(this.generateScrollItems(key, items));
			}

			return {
				barHTML: barHTML.join(''),
				scrollHTML: scrollHTML.join('')
			};
		}

		/** 生成单个分类的表情项 */
		generateScrollItems(key, items) {
			const isTextType = ['颜文字', 'emoji'].includes(key);
			const itemsHTML = items.map(item => {
				// 文本类型表情（颜文字/emoji）
				if (isTextType) return `<li data-toggle="tooltip" title="${item.text}" class="item" data-text="${item.icon}">${item.icon}</li>`;

				// 图片类型表情
				const titleMatch = item.text.match(/\((.*?)\)/);
				const title = titleMatch ? titleMatch[1] : item.text;  // 增强容错处理
				return `<li data-toggle="tooltip" title="${title}" class="item" data-text="${item.text}"><img class="lazyload" src="${window.Joe.options.JLazyload}" data-src="${Joe.THEME_URL + item.icon}" title="${title}" alt="${title}"/></li>`;
			}).join('');

			return `<ul class="scroll" data-type="${key}">${itemsHTML}</ul>`;
		}

		/** 切换容器显示状态 */
		toggleBox() {
			this.$seat.siblings('.box').stop().slideToggle("fast");
		}
	};

	/* 激活评论提交 */
	{

		// 事件监听
		$(document.body).on('keydown', '.joe_comment__respond-form textarea', function (event) {
			if (event.key == 'Enter') {
				event.preventDefault();
				$(".joe_comment__respond-form").trigger('submit');
			}
		});

		// 表单验证函数
		function validateForm(author, mail, text, type) {
			if (!author.trim()) return autolog.info("请输入昵称！");
			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(mail)) return autolog.info("请输入正确的邮箱！");
			if (type == 'text' && !text.trim()) return autolog.info("请输入评论内容！");
			return true;
		}

		// 提取提交数据准备
		function prepareData($form, type) {
			const coid = $form.attr('data-coid');
			const data = new FormData();
			const $textarea = $form.find('textarea[name="text"]');
			const text = type == "draw" ? `{!{${document.getElementById('joe_comment_draw').toDataURL("image/webp", 0.1)}}!}` : $textarea.val();

			data.append('author', $form.find('input[name="author"]').val());
			data.append('mail', $form.find('input[name="mail"]').val());
			data.append('url', $form.find('input[name="url"]').val());
			data.append('_', $form.find('input[name="_"]').val());
			data.append('text', text);

			if (coid) data.append('parent', coid);

			return data;
		}

		$(document.body).on('submit', '.joe_comment__respond-form', async function (event) {
			event.preventDefault();

			// 提取公共元素缓存
			const $form = $('.joe_comment__respond-form');
			const $submitBtn = $form.find('.foot .submit button');
			const $textarea = $form.find('textarea[name="text"]');
			const initialHTML = $submitBtn.html();

			// 防止重复提交
			if ($submitBtn.hasClass('disabled')) return;

			// 收集数据
			const type = $form.attr("data-type");
			const action = `${$form.attr("action")}?time=${Date.now()}`;
			const author = $form.find('input[name="author"]').val();
			const mail = $form.find('input[name="mail"]').val();

			// 验证数据
			if (validateForm(author, mail, $textarea.val(), type) !== true) return;

			// 准备提交
			window.Joe.commentListAutoRefresh = false;
			$submitBtn.html('<i class="loading mr6"></i>发送中...');
			$submitBtn.addClass('disabled');
			$(".joe_owo__contain .box").stop().slideUp("fast");

			Joe.pjax({
				type: "POST",
				url: action,
				data: prepareData($form, type),
				processData: false,
				contentType: false,
				selectors: ["#comment_module>.comment-list", '.joe_comment__title>small', '#comment_module>.joe_pagination'],
				beforeSend() {
					const referrer = document.querySelector('meta[name="referrer"]:last-of-type');
					if (referrer?.content == 'no-referrer') {
						referrer.remove();
						window.Joe.addMeta('referrer', 'unsafe-url');
					}
					$('#comment_module>.joe_pagination').html(`<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>`);
				},
				complete(xhr, status) {
					isSubmit = false;
					window.Joe.commentListAutoRefresh = true;
					$submitBtn.removeClass('disabled').html(initialHTML).blur();
					const referrer = document.querySelector('meta[name="referrer"]:last-of-type');
					if (referrer?.content == 'unsafe-url') {
						referrer.remove();
						window.Joe.addMeta('referrer', 'no-referrer');
					}
				},
				success() {
					autolog.success('发送成功');
					$('.joe_comment__cancle').trigger('click');
					if (Joe.IS_MOBILE) $('.comment-list__item .content').tooltip('destroy');
				},
				replace() {
					if (window.Joe.leavingListInit) window.Joe.leavingListInit();
					$textarea.val('');
					$textarea.focus();
					if (Joe.IS_MOBILE) $(".comment-list [data-toggle='popover']").popover({ html: true });
					if (document.querySelector('.joe_hide>.joe_hide__button')) window.Joe.pjax(window.location.href, ['.joe_detail__article']);
				},
				error(xhr, status, error) {
					const responseText = xhr.responseText;
					const msg = responseText.match(/<div class="container">\s+(.+)\s+<\/div>/)?.[1];
					autolog.warn(msg || '发送失败！请刷新重试！');
				}
			});
		});
	}

	/* 评论分页 Ajax 切换 */
	{
		$(document.body).on('click', '#comment_module>.joe_pagination a[href]', function (event) {
			event.preventDefault();
			let selectors = ["#comment_module>.comment-list", '#comment_module>.joe_pagination'];
			if (document.querySelector('.joe_detail__leaving')) selectors.push('.joe_detail__leaving');
			window.Joe.pjax(this.href, selectors, {
				scrollTo: '.comment-list',
				beforeSend() {
					window.Joe.commentListAutoRefresh = false;
					$('#comment_module>.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
				},
				success() {
					$('.joe_comment__cancle').click();
					if (Joe.IS_MOBILE) $('.comment-list__item .content').tooltip('destroy');
				},
				replace() {
					if (window.Joe.leavingListInit) window.Joe.leavingListInit();
					if (Joe.IS_MOBILE) $(".comment-list [data-toggle='popover']").popover({ html: true });
				}
			});
		});
	}

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
					if (Joe.IS_MOBILE) $('.comment-list__item .content').tooltip('destroy');
					return window.Joe.commentListAutoRefresh;
				},
				replace() {
					if (Joe.IS_MOBILE) $(".comment-list [data-toggle='popover']").popover({ html: true });
				}
			});
		}, time * 1000);
	})();

}

document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.comment, { once: true });
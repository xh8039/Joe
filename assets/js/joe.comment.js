window.Joe.initComment ||= (options = {}) => {

	/* 评论框点击切换画图模式和文本模式 */
	{
		if (options.draw !== false && $(".joe_comment__respond-form").length) {
			$(".joe_comment__respond-type .item").on('click', function () {
				$(this).addClass("active").siblings().removeClass("active");
				if ($(this).attr("data-type") === "draw") {
					$(".joe_comment__respond-form .body .draw").show().siblings().hide();
					$("#joe_comment_draw").prop("width", $(".joe_comment__respond-form .body").width());
					/* 设置表单格式为画图模式 */
					$(".joe_comment__respond-form").attr("data-type", "draw");
					/** 隐藏表情包功能 */
					$('.joe_comment__respond-form .foot .owo').css('opacity', '0');
				} else {
					$(".joe_comment__respond-form .body .text").show().siblings().hide();
					/* 设置表单格式为文字模式 */
					$(".joe_comment__respond-form").attr("data-type", "text");
					/** 显示表情包功能 */
					$('.joe_comment__respond-form .foot .owo').css('opacity', '1');
				}
			});
		}
	}

	/* 激活画图功能 */
	{
		if (window.Sketchpad && options.draw !== false && $(".joe_comment__respond-form").length && $("#joe_comment_draw").length) {
			/* 激活画板 */
			window.sketchpad = new Sketchpad({
				element: "#joe_comment_draw",
				height: 300,
				penSize: 5,
				color: "303133"
			});
			/* 撤销上一步 */
			$(".joe_comment__respond-form .body .draw .icon-undo").on('click', () => window.sketchpad.undo());
			/* 动画预览 */
			$(".joe_comment__respond-form .body .draw .icon-animate").on('click', () => window.sketchpad.animate(10));
			/* 更改画板的线宽 */
			$(".joe_comment__respond-form .body .draw .line li").on('click', function () {
				window.sketchpad.penSize = $(this).attr("data-line");
				$(this).addClass("active").siblings().removeClass("active");
			});
			/* 更改画板的颜色 */
			$(".joe_comment__respond-form .body .draw .color li").on('click', function () {
				window.sketchpad.color = $(this).attr("data-color");
				$(this).addClass("active").siblings().removeClass("active");
			});
		}
	}

	/* 重写评论功能 */
	(() => {
		if (options.operate === false || !document.querySelector('.joe_comment__respond>.joe_comment__respond-form')) return;
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
			window.scrollTo({
				top: item.offset().top - $(".joe_header").height() - 15,
				behavior: "smooth",
			});
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
			// window.scrollTo({
			// 	top: $(".joe_comment").offset().top - $(".joe_header").height() - 15,
			// 	behavior: "smooth",
			// });
			window.Joe.commentListAutoRefresh = true;
		});
		/* 评论删除 */
		$(document.body).on('click', '.joe_comment__delete', function () {
			if (Joe.IS_MOBILE) $(`.comment-list__item .content`).tooltip('destroy');
			const $button = $(this);
			const coid = $button.attr('data-coid');
			const button_html = $button.html();
			$button.html('<i class="loading mr3"></i>删除中...');
			$button.addClass('disabled');
			$.get(Joe.BASE_API + '/comment-delete', { coid }, function (data, textStatus, jqXHR) {
				if (data.code == 200) {
					$('.comment-list__item[data-coid="' + coid + '"]').hide('fast', () => {
						$('.comment-list__item[data-coid="' + coid + '"]').remove();
						autolog.log(`删除评论 ${coid} 成功`, 'success');
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
			$(`.comment-list__item .content:not([data-id="${data_id}"])`).tooltip('destroy');
			$(this).tooltip({
				html: true,
				sanitize: false,
				title: `<span class="joe_comment__reply" data-id="${data_id}" data-coid="${coid}">回复</span>丨<span class="joe_comment__delete" data-coid="${coid}">删除</span>`,
				trigger: 'manual',
				container: 'body'
			}).tooltip('toggle');
		});
	})();

	/* 激活评论提交 */
	{
		if (options.submit !== false && $(".joe_comment__respond-form").length) {
			var isSubmit = false;
			$(".joe_comment__respond-form").on("submit", function (event) {
				event.preventDefault();
				window.Joe.commentListAutoRefresh = false;
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
					},
					replace() {
						if (Joe.initComment) Joe.initComment({ draw: false, owo: false, submit: false, operate: false });
						if (window.Joe.leavingListInit) window.Joe.leavingListInit();
						isSubmit = false;
						autolog.log('发送成功', 'success');
						$('textarea.joe_owo__target').val('');
						$(".joe_comment__respond-form .foot .submit button").html("发送评论").blur();
						$(".joe_comment__respond-form .body textarea[name='text']").focus();
						if ($('.joe_hide>.joe_hide__button').length) {
							window.Joe.pjax(window.location.href, ['.joe_detail__article']);
						}
					},
					error(xhr, status, error) {
						isSubmit = false;
						$(".joe_comment__respond-form .foot .submit button").html("发送评论").blur();
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
			document.querySelector(".joe_comment__respond-form").addEventListener("keydown", function (event) {
				if (event.keyCode === 13) {
					event.preventDefault();
					$(".joe_comment__respond-form").submit();
				}
			});
		}
	}

	/* 设置评论回复网址为新窗口打开 */
	{
		$(".comment-list__item .term .content .user .author a").each((index, item) => $(item).attr("target",
			"_blank"));
	}

	/* 格式化评论分页的hash值 */
	{
		if (options.pagination !== false && $('#comment_module>.joe_pagination a').length) {
			$(".joe_comment .joe_pagination a").each((index, item) => {
				const href = $(item).attr("href");
				if (href && href.includes("#")) {
					$(item).attr("href", href.replace("#comments", "#comment_module"));
				}
				$(item).attr('ajax-replace', true);
			});
			let selectors = ["#comment_module>.comment-list", '#comment_module>.joe_pagination'];
			if (document.querySelector('.joe_detail__leaving')) selectors.push('.joe_detail__leaving');
			$('#comment_module>.joe_pagination a[href]').click(function (event) {
				event.preventDefault();
				window.Joe.pjax(this.href, selectors, {
					beforeSend() {
						window.Joe.commentListAutoRefresh = false;
						$('#comment_module>.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
					},
					success() {
						$('.joe_comment__cancle').click();
					},
					replace() {
						Joe.initComment({ draw: false, owo: false, submit: false, operate: false });
						if (window.Joe.leavingListInit) window.Joe.leavingListInit();
						Joe.scrollTo('.comment-list');
					}
				});
			});
		}
	}

	/* 初始化表情功能 */
	{
		if (options.owo !== false && $(".joe_owo__contain").length && $(".joe_owo__target").length && !$('.joe_owo__target').attr('disabled')) {
			console.log('初始化评论区表情功能');
			$(document.body).on('click', '.joe_owo__contain .scroll .item', function () {
				const text = $(this).attr("data-text");
				$(".joe_owo__target").insertContent(text);
			});
			$(document.body).on('click', '.joe_owo__contain .box .bar .item', function (e) {
				e.stopPropagation();
				$(this).addClass("active").siblings().removeClass("active");
				const scrollIndx = '.joe_owo__contain .box .scroll[data-type="' + $(this).attr("data-type") + '"]';
				$(scrollIndx).show().siblings(".scroll").hide();
			});
			if (window.Joe.CommentOwO) {
				Joe.initCommentOwO(window.Joe.CommentOwO);
			} else {
				(async () => {
					const urls = [
						window.Joe.THEME_URL + "assets/json/joe.owo.json",
						window.Joe.THEME_URL + "assets/json/joe.owo.php",
						window.Joe.options.themeUrl + "/assets/json/joe.owo.json",
					];
					for (const url of urls) try {
						const response = await fetch(url);
						if (!response.ok) throw new Error(`HTTP错误！状态码：${response.status}`);
						const res = await response.json();
						Joe.initCommentOwO(res);
						return;
					} catch (error) { };
					console.warn("所有URL都无法加载表情包数据");
				})();
			}
		}
	}
}
window.Joe.initCommentOwO ||= (res) => {
	window.Joe.CommentOwO = res;
	var OwOUrl = $('.joe_owo__contain').attr('data-url') || window.Joe.THEME_URL;
	let barStr = "";
	let scrollStr = "";
	for (let key in res) {
		const item = res[key];
		key = key.replace('表情', '');
		barStr += `<div class="item" data-type="${key}">${key}</div>`;
		scrollStr += `
		<ul class="scroll" data-type="${key}">
			${item.map((_) => {
			if (key == '颜文字' || key == 'emoji') {
				return `<li data-toggle="tooltip" data-original-title="${_.text}" class="item" data-text="${_.icon}">${_.icon}</li>`;
			} else {
				let title = /.*?\((.*?)\)/.exec(_.text)[1];
				return `<li data-toggle="tooltip" data-original-title="${title}" class="item" data-text="${_.text}"><img class="lazyload" src="${window.Joe.options.JLazyload}" data-src="${OwOUrl + _.icon}" title="${title}" alt="${title}"/></li>`;
			}
		}).join("")}
		</ul>`;
	}
	$(".joe_owo__contain").html(`
		<div class="seat">OωO</div>
		<div class="box">
			${scrollStr}
			<div class="bar no-scrollbar">${barStr}</div>
		</div>
	`);
	$(".joe_owo__contain .seat").on('click', function (e) {
		e.stopPropagation();
		$(this).siblings(".box").stop().slideToggle("fast");
	});
	$(".joe_owo__contain .box .bar .item").first().click();
	window.Joe.tooltip('.joe_owo__contain .scroll .item');
}
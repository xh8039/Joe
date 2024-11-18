window.Joe.initComment = (options = {}) => {

	/* 评论框点击切换画图模式和文本模式 */
	{
		if (options.draw !== false && $(".joe_comment").length) {
			$(".joe_comment__respond-type .item").on("click", function () {
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
		if (options.draw !== false && $("#joe_comment_draw").length) {
			/* 激活画板 */
			window.sketchpad = new Sketchpad({
				element: "#joe_comment_draw",
				height: 300,
				penSize: 5,
				color: "303133"
			});
			/* 撤销上一步 */
			$(".joe_comment__respond-form .body .draw .icon-undo").on("click", () => window.sketchpad.undo());
			/* 动画预览 */
			$(".joe_comment__respond-form .body .draw .icon-animate").on("click", () => window.sketchpad
				.animate(10));
			/* 更改画板的线宽 */
			$(".joe_comment__respond-form .body .draw .line li").on("click", function () {
				window.sketchpad.penSize = $(this).attr("data-line");
				$(this).addClass("active").siblings().removeClass("active");
			});
			/* 更改画板的颜色 */
			$(".joe_comment__respond-form .body .draw .color li").on("click", function () {
				window.sketchpad.color = $(this).attr("data-color");
				$(this).addClass("active").siblings().removeClass("active");
			});
		}
	}

	/* 重写评论功能 */
	{
		if ($(".joe_comment__respond").length) {
			const respond = $(".joe_comment__respond");
			/* 重写回复功能 */
			$(".joe_comment__reply").on("click", function () {
				/* 父级ID */
				const coid = $(this).attr("data-coid");
				/* 当前的项 */
				const item = $("#" + $(this).attr("data-id"));
				/* 添加自定义属性表示父级ID */
				respond.find(".joe_comment__respond-form").attr("data-coid", coid);
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
			$(".joe_comment__cancle").on("click", function () {
				/* 移除自定义属性父级ID */
				respond.find(".joe_comment__respond-form").removeAttr("data-coid");
				$(".joe_comment__cancle").hide();
				$(".joe_comment>.comment-list").before(respond);
				$(".joe_comment__respond-type .item[data-type='text']").click();
				// window.scrollTo({
				// 	top: $(".joe_comment").offset().top - $(".joe_header").height() - 15,
				// 	behavior: "smooth",
				// });
				window.Joe.commentListAutoRefresh = true;
			});
		}
	}

	/* 激活评论提交 */
	{
		if (options.submit !== false && $(".joe_comment").length) {
			var isSubmit = false;
			$(".joe_comment__respond-form").on("submit", function (event) {
				event.preventDefault();
				window.Joe.commentListAutoRefresh = false;
				// let action = $('#comment_module>.joe_pagination>li.active>a').attr('href');
				// action = action ? action : $(".joe_comment__respond-form").attr("action");
				// action = action + "?time=" + +new Date();
				const action = $(".joe_comment__respond-form").attr("action") + "?time=" + +new Date();
				const type = $(".joe_comment__respond-form").attr("data-type");
				const parent = $(".joe_comment__respond-form").attr("data-coid");
				const author = $(".joe_comment__respond-form .head input[name='author']").val();
				const _ = $(".joe_comment__respond-form input[name='_']").val();
				const mail = $(".joe_comment__respond-form .head input[name='mail']").val();
				const url = $(".joe_comment__respond-form .head input[name='url']").val();
				let text = $(".joe_comment__respond-form .body textarea[name='text']").val();
				if (author.trim() === "") return Qmsg.info("请输入昵称！");
				if (!/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(mail)) return Qmsg.info(
					"请输入正确的邮箱！");
				if (type === "text" && text.trim() === "") return Qmsg.info("请输入评论内容！");
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
				data.append('parent', parent);
				data.append('url', url);
				data.append('_', _);

				var pjax = new Pjax({
					elements: '.joe_comment__respond-form',
					selectors: ["#comment_module>.comment-list", '#comment_module>.joe_pagination'],
					history: false,
					scrollRestoration: false,
					pjax: 'comment-submit',
					cacheBust: false,
				});
				pjax._handleResponse = pjax.handleResponse;
				pjax.handleResponse = function (responseText, request, href, options) {
					$('.joe_comment__cancle').click();
					pjax._handleResponse(responseText, request, href, options);
				}
				pjax.loadUrl(action, {
					requestOptions: {
						requestMethod: 'POST',
						formData: data,
					},
				});
				// console.log(pjax);
			});
			document.addEventListener('pjax:success', (options) => {
				if (options.pjax != "comment-submit") return;
				isSubmit = false;
				Qmsg.success('发送成功');
				$('textarea.joe_owo__target').val('');
				$(".joe_comment__respond-form .foot .submit button").html("发送评论").blur();
				if ($('joe-hide>.joe_hide>.joe_hide__button').length) {
					var pjax = new Pjax({
						elements: '',
						selectors: ['joe-hide'],
						history: false,
						scrollRestoration: false,
						pjax: 'joe-hide',
						switches: {
							// 切换函数
							'joe-hide': Pjax.switches.innerHTML
						},
						cacheBust: false,
					});
					pjax.loadUrl(window.location.href);
				}
			});
			document.addEventListener('pjax:error', (event) => {
				if (options.pjax != "comment-submit") return;
				isSubmit = false;
				$(".joe_comment__respond-form .foot .submit button").html("发送评论");
				console.log(event.options.request)
				res = event.options.request.responseText;
				var str = /<div class="container">\s+(.+)\s+<\/div>/;
				var msg = str.match(res)[1];
				if (msg) {
					Qmsg.warning(msg);
				} else {
					Qmsg.warning("发送失败！请刷新重试！");
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
		if (options.pagination !== false) {
			if ($('#comment_module>.joe_pagination a').length) {
				$(".joe_comment .joe_pagination a").each((index, item) => {
					const href = $(item).attr("href");
					if (href && href.includes("#")) {
						$(item).attr("href", href.replace("#comments", "#comment_module"));
					}
					$(item).attr('ajax-replace', 'true');
					$(item).addClass('pjax');
				});
				new Pjax({
					elements: "#comment_module>.joe_pagination a[href]", // default is "a[href], form[action]"
					selectors: ["#comment_module>.comment-list", '#comment_module>.joe_pagination'],
					history: false,
					scrollRestoration: false,
					pjax: 'comment-pagination',
					cacheBust: false,
				});
			}
		}
	}

	/* 初始化表情功能 */
	{
		function initOwO(res) {
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
						return `<li data-toggle="tooltip" data-original-title="${title}" class="item" data-text="${_.text}"><img class="lazyload" src="${window.Joe.LAZY_LOAD}" data-src="${OwOUrl + _.icon}" title="${title}" alt="${title}"/></li>`;
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
			window.Joe.tooltip();
			// $(".joe_owo__contain .seat").on("click", function () {
			// 	$(".joe_owo__contain .box").stop().slideUp("fast");
			// });
			$(".joe_owo__contain .seat").on("click", function (e) {
				e.stopPropagation();
				$(this).siblings(".box").stop().slideToggle("fast");
			});
			$(".joe_owo__contain .box .bar .item").on("click", function (e) {
				e.stopPropagation();
				$(this).addClass("active").siblings().removeClass("active");
				const scrollIndx = '.joe_owo__contain .box .scroll[data-type="' + $(
					this).attr("data-type") + '"]';
				$(scrollIndx).show().siblings(".scroll").hide();
			});
			$(".joe_owo__contain .scroll .item").on("click", function () {
				const text = $(this).attr("data-text");
				$(".joe_owo__target").insertContent(text);
			});
			$(".joe_owo__contain .box .bar .item").first().click();
		}
		if (options.owo !== false && $(".joe_owo__contain").length && $(".joe_owo__target").length && !$('.joe_owo__target').attr('disabled')) {
			console.log('初始化表情');
			var OwOUrl = $('.joe_owo__contain').attr('data-url') || window.Joe.THEME_URL;
			if (!window.Joe.OwO) {
				$.ajax({
					url: window.Joe.THEME_URL + "assets/json/joe.owo.json",
					dataType: "json",
					success(res) {
						window.Joe.OwO = res;
						initOwO(res);
					}
				});
			} else {
				initOwO(window.Joe.OwO);
			}
		}
	}

}
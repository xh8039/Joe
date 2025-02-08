Joe.DOMContentLoaded.initComment ||= (options = {}) => {
	console.log('调用：Joe.DOMContentLoaded.initComment', options);

	/** 评论区禁止评论删除表情包功能 */
	(() => {
		if (!$(".joe_owo__target").length || $('.joe_owo__target').attr('disabled')) return $('.joe_owo__contain .seat').remove();
	})();

	/** 评论区内容实时刷新 */
	(() => {
		if (window.Joe.commentListSetInterval !== undefined) return;
		let RefreshDOM = '#comment_module a[auto-refresh]';
		if (!document.querySelector(RefreshDOM)) return;
		let time = Number($(RefreshDOM).attr('auto-refresh'));
		if (!time || !Number.isInteger(time)) return;
		window.Joe.commentListAutoRefresh = true;
		window.Joe.commentListSetInterval = setInterval(() => {
			if (!document.querySelector(RefreshDOM)) return;
			if (document.visibilityState == "hidden" || document.hidden) return;
			if (!window.Joe.commentListAutoRefresh) return;
			if (!isElementInViewport(document.querySelector('.comment-list'))) return;
			let url = $('#comment_module>.joe_pagination>li.active>a').attr('href') || $(RefreshDOM).attr('href');
			window.Joe.pjax(url, ['#comment_module>.comment-list', '.joe_comment__title>small'], {
				success() {
					return window.Joe.commentListAutoRefresh;
				},
				replace() {
					Joe.DOMContentLoaded.initComment({ submit: false, pagination: false });
				}
			});
		}, time * 1000);
	})();

	/* 激活评论提交 */
	(() => {
		if (options.submit === false || !document.querySelector('.joe_comment__respond-form')) return;
		var isSubmit = false;
		$(".joe_comment__respond-form").on("submit", function (event) {
			event.preventDefault();
			window.Joe.commentListAutoRefresh = false;
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
					if (Joe.DOMContentLoaded.initComment) Joe.DOMContentLoaded.initComment({ submit: false });
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
		document.querySelector(".joe_comment__respond-form").addEventListener("keydown", function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				$(".joe_comment__respond-form").submit();
			}
		});
	})();

	/* 格式化评论分页的hash值 */
	(() => {
		if (options.pagination === false || !document.querySelector('#comment_module>.joe_pagination a')) return;
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
					if (Joe.IS_MOBILE) $(`.comment-list__item .content`).tooltip('destroy');
				},
				replace() {
					Joe.DOMContentLoaded.initComment({ submit: false });
					if (window.Joe.leavingListInit) window.Joe.leavingListInit();
					Joe.scrollTo('.comment-list');
				}
			});
		});
	})();
}

document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.initComment, { once: true });
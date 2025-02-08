Joe.DOMContentLoaded.initComment ||= (options = {}) => {
	console.log('调用：Joe.DOMContentLoaded.initComment', options);

	/** 评论区禁止评论删除表情包功能 */
	(() => {
		if (!$(".joe_owo__target").length || $('.joe_owo__target').attr('disabled')) return $('.joe_owo__contain .seat').remove();
	})();

	/* 激活评论回车提交 */
	(() => {
		return;
		if (options.submit === false || !document.querySelector('.joe_comment__respond-form')) return;
		document.querySelector(".joe_comment__respond-form").addEventListener("keydown", function (event) {
			if (event.keyCode === 13) {
				event.preventDefault();
				$(".joe_comment__respond-form").submit();
			}
		});
	})();

	/* 格式化评论分页的hash值 */
	(() => {
		if (options.pagination === false || !document.querySelector('#comment_module>.joe_pagination a[href]')) return;
		$("#comment_module>.joe_pagination a[href]").each((index, item) => {
			$(item).attr('ajax-replace', true);
		});
	})();
}

document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.initComment, { once: true });
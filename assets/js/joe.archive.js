/* 搜索页面需要用到的JS */
Joe.DOMContentLoaded.archive = Joe.DOMContentLoaded.archive ? Joe.DOMContentLoaded.archive : () => {

	/* 激活列表特效 */
	{
		var wow = $('.joe_archive__list').attr('data-wow');
		if (wow !== 'off' && wow)
			new WOW({
				boxClass: 'wow',
				animateClass: 'animated '.concat(wow),
				offset: 0,
				mobile: true,
				live: true,
				scrollContainer: null
			}).init();
	}

	/* 分页Pjax加载 */
	{
		new Pjax({
			elements: '.joe_pagination>li>a[href]',
			selectors: ['title', 'h1', '.joe_main'],
			history: true,
			scrollRestoration: false,
			pjax: 'archive-pagination',
			cacheBust: false,
			scrollTo: 0,
		});
		document.addEventListener('pjax:send', (options) => {
			if (options.pjax != 'archive-pagination') return;
			$('.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
		});
	}

};
document.addEventListener(window.Turbolinks ? 'turbolinks:load' : 'DOMContentLoaded', Joe.DOMContentLoaded.archive);
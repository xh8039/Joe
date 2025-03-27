/* 搜索页面需要用到的JS */
Joe.DOMContentLoaded.archive ||= () => {
	console.log('调用：Joe.DOMContentLoaded.archive');
	/* 激活列表特效 */
	{
		var wow = $('.joe_archive__list').attr('data-wow');
		if (wow !== 'off' && wow)
			new WOW({
				boxClass: 'wow',
				animateClass: 'animate__animated animate__'.concat(wow),
				offset: 0,
				mobile: true,
				live: true,
				scrollContainer: null
			}).init();
	}

	/** Atropos 悬浮动画 */
	{
		if (window.Atropos) document.querySelectorAll('.atropos').forEach(element => {
			Atropos({ el: element, shadowOffset: 80 });
			setAtroposOffset(element.querySelector('.atropos-inner'));
		});
	}

	/* 分页Pjax加载 */
	{
		Joe.pjax('.joe_pagination>li>a[href]', ['title', '.joe_main'], {
			beforeSend() {
				$('.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
			},
			replace() {
				/** Atropos 悬浮动画 */
				if (window.Atropos) document.querySelectorAll('.atropos').forEach(element => {
					Atropos({ el: element, shadowOffset: 80 });
					setAtroposOffset(element.querySelector('.atropos-inner'));
				});
			},
			scrollTo: 0
		});
	}
};
document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.archive, { once: true });
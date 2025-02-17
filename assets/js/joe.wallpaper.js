Joe.DOMContentLoaded.wallpaper ||= () => {
	console.log('调用：Joe.DOMContentLoaded.wallpaper');
	let isLoading = false;
	let queryData = { cid: -999, start: -999, count: 48 };
	let total = -999;
	$.ajax({
		url: Joe.BASE_API + '/wallpaper-type',
		type: 'POST',
		dataType: 'json',
		success(res) {
			if (res.code !== 1) return $('.joe_wallpaper__type-list').html('<li class="error">壁纸抓取失败！请联系作者！</li>');
			if (!res.data.length) return $('.joe_wallpaper__type-list').html(`<li class="error">暂无数据！</li>`);
			let htmlStr = '';
			res.data.forEach(_ => (htmlStr += `<li class="item animate__animated animate__swing" data-cid="${_.id}">${_.name}</li>`));
			$('.joe_wallpaper__type-list').html(htmlStr);
			$('.joe_wallpaper__type-list .item').first().click();
		}
	});
	$('.joe_wallpaper__type-list').on('click', '.item', function () {
		const cid = $(this).attr('data-cid');
		if (isLoading) return;
		$(this).addClass('active').siblings().removeClass('active');
		queryData.cid = cid;
		queryData.start = 0;
		renderDom();
	});
	function renderDom() {
		window.scrollTo({ top: 0, behavior: 'smooth' });
		$('.joe_wallpaper__list').html('');
		isLoading = true;
		$.ajax({
			url: Joe.BASE_API + 'wallpaper-list',
			type: 'POST',
			dataType: 'json',
			data: {
				cid: queryData.cid,
				start: queryData.start,
				count: queryData.count
			},
			success(res) {
				if (res.code !== 1) return (isLoading = false);
				isLoading = false;
				let htmlStr = '';
				res.data.forEach(_ => {
					htmlStr += `
						<a class="item animate__animated animate__bounceIn" data-fancybox="gallery" href="${_.url}">
							<img width="100%" height="100%" class="lazyload" src="${Joe.options.JLazyload}" data-src="${_.img_1024_768 || _.url}" alt="壁纸">
						</a>`;
				});
				$('.joe_wallpaper__list').html(htmlStr);
				total = res.total;
				initPagination();
			}
		});
	}
	function initPagination() {
		let htmlStr = '';
		let totalPages = Math.ceil(total / queryData.count);
		let currentPage = Math.ceil(queryData.start / queryData.count) + 1;
		if (queryData.start / queryData.count !== 0) {
			htmlStr += `<li class="joe_wallpaper__pagination-item" data-start="0">首页</li><li class="joe_wallpaper__pagination-item hide-sm" data-start="${queryData.start - queryData.count}"><i class="fa fa-angle-left em12"></i><span class="ml6">上一页</span></li><li class="joe_wallpaper__pagination-item" data-start="${queryData.start - queryData.count}">${currentPage - 1}</li>`;
		}
		htmlStr += `<li class="joe_wallpaper__pagination-item active">${currentPage}</li>`;
		if (currentPage < totalPages) {
			// if (queryData.start != total - queryData.count) {
			htmlStr += `<li class="joe_wallpaper__pagination-item" data-start="${queryData.start + queryData.count}">${currentPage + 1}</li><li class="joe_wallpaper__pagination-item hide-sm" data-start="${queryData.start + queryData.count}"><span class="mr6">下一页</span><i class="fa fa-angle-right em12"></i></li>`;
		}
		if (queryData.start < total - queryData.count) htmlStr += `<li class="joe_wallpaper__pagination-item" data-start="${total - queryData.count}">末页</li>`;
		$('.joe_wallpaper__pagination').html(htmlStr);
	}
	$('.joe_wallpaper__pagination').on('click', '.joe_wallpaper__pagination-item', function () {
		$('.joe_wallpaper__pagination-item').removeClass('active');
		$(this).addClass('active');
		const start = $(this).attr('data-start');
		if (!start || isLoading) return;
		queryData.start = Number(start);
		renderDom();
	});
}
document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.wallpaper, { once: true });
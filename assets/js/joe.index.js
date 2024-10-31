document.addEventListener('DOMContentLoaded', () => {
	/* 激活轮播图功能 */
	{
		if ($('.joe_index__banner .swiper').length !== 0) {
			let direction = 'horizontal';
			if (!Joe.IS_MOBILE && $('.joe_index__banner-recommend .item').length === 2) direction = 'vertical';
			new Swiper('.swiper', {
				keyboard: false, // 使用键盘在幻灯片中导航
				direction, // '水平' |'垂直'
				loop: true,
				autoplay: true,
				mousewheel: false, // 允许使用鼠标滚轮在幻灯片中导航
				pagination: { el: '.swiper-pagination' }, // 具有分页参数的对象或布尔值，以使用默认设置启用。
				// 具有导航参数的对象或布尔值，以使用默认设置启用。
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev'
				}
			});
		}
	}

	/* 初始化首页列表功能 */
	{
		const getTags = (data) => {
			let tagsHtml = '';
			if (data.fields.hide == 'pay' && data.fields.pay_tag_background != 'none') {
				tagsHtml += `<a rel="nofollow" href="${data.permalink}?scroll=pay-box" class="meta-pay but jb-${data.fields.pay_tag_background}">付费阅读<span class="em09 ml3">￥</span>${data.fields.price}</a>`;
			}
			var color = ['c-blue', 'c-yellow', 'c-green', 'c-cyan', 'c-blue-2', 'c-purple-2', 'c-yellow-2', 'c-purple', 'c-red-2', 'c-red'];
			data.category.forEach((element, index) => {
				tagsHtml += `<a target="${data.target}" class="but ${color[index]}" title="查看更多分类文章" href="${element.permalink}"><i class="fa fa-folder-open-o" aria-hidden="true"></i>${element.name}</a>`;
			});
			data.tags.forEach(tag => {
				tagsHtml += `<a target="${data.target}" class="but" title="查看此标签更多文章" href="${tag.permalink}"># ${tag.name}</a>`
			});
			return tagsHtml;
		}

		const getListMode = _ => {
			if (_.mode === 'default') {
				return `
					<li class="joe_list__item wow default">
						<div class="line"></div>
						<a href="${_.permalink}" class="thumbnail" title="${_.title}" target="${_.target}" rel="noopener noreferrer">
							<img referrerpolicy="no-referrer" rel="noreferrer" width="100%" height="100%" class="lazyload" src="${_.lazyload}" data-src="${_.image[0]}" alt="${_.title}" />
							<time datetime="${_.time}">${_.time}</time>
							<svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20"><path d="M903.93 107.306H115.787c-51.213 0-93.204 42.505-93.204 93.72V825.29c0 51.724 41.99 93.717 93.717 93.717h788.144c51.72 0 93.717-41.993 93.717-93.717V201.025c-.512-51.214-43.017-93.719-94.23-93.719zm-788.144 64.527h788.657c16.385 0 29.704 13.316 29.704 29.704v390.229L760.54 402.285c-12.805-13.828-30.217-21.508-48.14-19.971-17.924 1.02-34.821 10.754-46.602 26.114l-172.582 239.16-87.06-85.52c-12.29-11.783-27.654-17.924-44.039-17.924-16.39.508-31.755 7.676-43.53 20.48L86.595 821.705V202.05c-1.025-17.416 12.804-30.73 29.191-30.217zm788.145 683.674H141.906l222.255-245.82 87.06 86.037c12.8 12.807 30.212 18.95 47.115 17.417 17.41-1.538 33.797-11.266 45.063-26.118l172.584-238.647 216.111 236.088 2.051-1.54V825.8c.509 16.39-13.315 29.706-30.214 29.706zm0 0"/><path d="M318.072 509.827c79.89 0 144.417-65.037 144.417-144.416 0-79.378-64.527-144.925-144.417-144.925-79.891 0-144.416 64.527-144.416 144.412 0 79.892 64.525 144.93 144.416 144.93zm0-225.327c44.553 0 80.912 36.362 80.912 80.91 0 44.557-35.847 81.43-80.912 81.43-45.068 0-80.916-36.36-80.916-80.915 0-44.556 36.872-81.425 80.916-81.425zm0 0"/></svg>
						</a>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}" target="${_.target}" rel="noopener noreferrer">
								${!_.type || _.type == 'normal' ? '' : '<span class="badge">' + _.type + '</span>'}${_.title}
							</a>
							<a class="abstract" href="${_.permalink}" title="文章摘要" target="${_.target}" rel="noopener noreferrer">${_.abstract}</a>
							<div class="meta">

								<div class="item-tags scroll-x no-scrollbar mb6">
									${getTags(_)}
								</div>
								<div class="item-meta muted-2-color flex jsb ac">
									<item class="meta-author flex ac">
										<a href="${_.author_permalink}">
											<span class="avatar-mini">
												<img alt="${_.author_screenName}的头像 - ${Joe.TITLE}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1">
											</span>
										</a>
										<span class="hide-sm ml6">${_.author_screenName}</span>
										<span title="${_.date_time}" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
									</item>
									<div class="meta-right">
										<item class="meta-comm">
											<a rel="nofollow" data-toggle="tooltip" title="去评论" href="${_.permalink}?scroll=comment_module">
												<svg class="icon svg" aria-hidden="true">
													<use xlink:href="#icon-comment"></use>
												</svg>${_.commentsNum}
											</a>
										</item>
										<item class="meta-view">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-view"></use>
											</svg>${_.views}
										</item>
										<item class="meta-like">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-like"></use>
											</svg>${_.agree}
										</item>
									</div>
								</div>

							</div>
						</div>
					</li>
				`;
			} else if (_.mode === 'single') {
				return `
					<li class="joe_list__item wow single">
						<div class="line"></div>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}" target="${_.target}" rel="noopener noreferrer">
								${!_.type || _.type == 'normal' ? '' : '<span class="badge">' + _.type + '</span>'}${_.title}
							</a>
							<div class="meta">
								<div class="item-tags scroll-x no-scrollbar mb6">
									${getTags(_)}
								</div>
								<div class="item-meta muted-2-color flex jsb ac">
									<item class="meta-author flex ac">
										<a href="${_.author_permalink}">
											<span class="avatar-mini">
												<img alt="${_.author_screenName}的头像 - ${Joe.TITLE}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1">
											</span>
										</a>
										<span class="hide-sm ml6">${_.author_screenName}</span>
										<span title="${_.date_time}" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
									</item>
									<div class="meta-right">
										<item class="meta-comm">
											<a rel="nofollow" data-toggle="tooltip" title="去评论" href="${_.permalink}?scroll=comment_module">
												<svg class="icon svg" aria-hidden="true">
													<use xlink:href="#icon-comment"></use>
												</svg>${_.commentsNum}
											</a>
										</item>
										<item class="meta-view">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-view"></use>
											</svg>${_.views}
										</item>
										<item class="meta-like">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-like"></use>
											</svg>${_.agree}
										</item>
									</div>
								</div>
							</div>
						</div>
						<a href="${_.permalink}" class="thumbnail" title="${_.title}" target="${_.target}" rel="noopener noreferrer">
							<img referrerpolicy="no-referrer" rel="noreferrer" width="100%" height="100%" class="lazyload" src="${_.lazyload}" data-src="${_.image[0]}" alt="${_.title}" />
							<time datetime="${_.time}">${_.time}</time>
							<svg viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" width="20" height="20"><path d="M903.93 107.306H115.787c-51.213 0-93.204 42.505-93.204 93.72V825.29c0 51.724 41.99 93.717 93.717 93.717h788.144c51.72 0 93.717-41.993 93.717-93.717V201.025c-.512-51.214-43.017-93.719-94.23-93.719zm-788.144 64.527h788.657c16.385 0 29.704 13.316 29.704 29.704v390.229L760.54 402.285c-12.805-13.828-30.217-21.508-48.14-19.971-17.924 1.02-34.821 10.754-46.602 26.114l-172.582 239.16-87.06-85.52c-12.29-11.783-27.654-17.924-44.039-17.924-16.39.508-31.755 7.676-43.53 20.48L86.595 821.705V202.05c-1.025-17.416 12.804-30.73 29.191-30.217zm788.145 683.674H141.906l222.255-245.82 87.06 86.037c12.8 12.807 30.212 18.95 47.115 17.417 17.41-1.538 33.797-11.266 45.063-26.118l172.584-238.647 216.111 236.088 2.051-1.54V825.8c.509 16.39-13.315 29.706-30.214 29.706zm0 0"/><path d="M318.072 509.827c79.89 0 144.417-65.037 144.417-144.416 0-79.378-64.527-144.925-144.417-144.925-79.891 0-144.416 64.527-144.416 144.412 0 79.892 64.525 144.93 144.416 144.93zm0-225.327c44.553 0 80.912 36.362 80.912 80.91 0 44.557-35.847 81.43-80.912 81.43-45.068 0-80.916-36.36-80.916-80.915 0-44.556 36.872-81.425 80.916-81.425zm0 0"/></svg>
						</a>
						<div class="information" style="margin-bottom: 0;">
							<a class="abstract" href="${_.permalink}" title="文章摘要" target="${_.target}" rel="noopener noreferrer">${_.abstract}</a>
						</div>
					</li>
				`;
			} else if (_.mode === 'multiple') {
				return `
					<li class="joe_list__item wow multiple">
						<div class="line"></div>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}" target="${_.target}" rel="noopener noreferrer">
								${!_.type || _.type == 'normal' ? '' : '<span class="badge">' + _.type + '</span>'}${_.title}
							</a>
							<a class="abstract" href="${_.permalink}" title="文章摘要" target="${_.target}" rel="noopener noreferrer">${_.abstract}</a>
						</div>
						<a href="${_.permalink}" class="thumbnail" title="${_.title}" target="${_.target}" rel="noopener noreferrer">
							${_.image
						.map((item, index) => {
							if (index < 3) {
								return `<img referrerpolicy="no-referrer" rel="noreferrer" width="100%" height="100%" class="lazyload" src="${_.lazyload}" data-src="${item}" alt="${_.title}" />`;
							}
						})
						.join('')}
						</a>
						<div class="meta">
							<div class="item-tags scroll-x no-scrollbar mb6">
								${getTags(_)}
							</div>
							<div class="item-meta muted-2-color flex jsb ac">
								<item class="meta-author flex ac">
									<a href="${_.author_permalink}">
										<span class="avatar-mini">
											<img alt="${_.author_screenName}的头像 - ${Joe.TITLE}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1">
										</span>
									</a>
									<span class="hide-sm ml6">${_.author_screenName}</span>
									<span title="${_.date_time}" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
								</item>
								<div class="meta-right">
									<item class="meta-comm">
										<a rel="nofollow" data-toggle="tooltip" title="去评论" href="${_.permalink}?scroll=comment_module">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-comment"></use>
											</svg>${_.commentsNum}
										</a>
									</item>
									<item class="meta-view">
										<svg class="icon svg" aria-hidden="true">
											<use xlink:href="#icon-view"></use>
										</svg>${_.views}
									</item>
									<item class="meta-like">
										<svg class="icon svg" aria-hidden="true">
											<use xlink:href="#icon-like"></use>
										</svg>${_.agree}
									</item>
								</div>
							</div>
						</div>
					</li>
				`;
			} else {
				return `
					<li class="joe_list__item wow none">
						<div class="line"></div>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}" target="${_.target}" rel="noopener noreferrer">
								${!_.type || _.type == 'normal' ? '' : '<span class="badge">' + _.type + '</span>'}${_.title}
							</a>
							<a class="abstract" href="${_.permalink}" title="文章摘要" target="${_.target}" rel="noopener noreferrer">${_.abstract}</a>
							<div class="meta">
								<div class="item-tags scroll-x no-scrollbar mb6">
									${getTags(_)}
								</div>
								<div class="item-meta muted-2-color flex jsb ac">
									<item class="meta-author flex ac">
										<a href="${_.author_permalink}">
											<span class="avatar-mini">
												<img alt="${_.author_screenName}的头像 - ${Joe.TITLE}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1">
											</span>
										</a>
										<span class="hide-sm ml6">${_.author_screenName}</span>
										<span title="${_.date_time}" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
									</item>
									<div class="meta-right">
										<item class="meta-comm">
											<a rel="nofollow" data-toggle="tooltip" title="去评论" href="${_.permalink}?scroll=comment_module">
												<svg class="icon svg" aria-hidden="true">
													<use xlink:href="#icon-comment"></use>
												</svg>${_.commentsNum}
											</a>
										</item>
										<item class="meta-view">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-view"></use>
											</svg>${_.views}
										</item>
										<item class="meta-like">
											<svg class="icon svg" aria-hidden="true">
												<use xlink:href="#icon-like"></use>
											</svg>${_.agree}
										</item>
									</div>
								</div>
							</div>
						</div>
					</li>
				`;
			}
		};
		let queryData = { page: 1, pageSize: window.Joe.PAGE_SIZE, type: 'created' };
		const initDom = () => {
			$('.joe_index__list .joe_list').html('');
			$('.joe_load').show();
			let activeItem = $('.joe_index__title-title .item[data-type="' + queryData.type + '"]');
			activeItem.addClass('active').siblings().removeClass('active');
		};
		const pushDom = () => {
			return new Promise((reslove, reject) => {
				$('.joe_load').attr('loading', true);
				$('.joe_load').html('loading...');
				$('.joe_index__list .joe_list__loading').show();
				$.ajax({
					url: Joe.BASE_API,
					type: 'POST',
					dataType: 'json',
					data: { routeType: 'publish_list', page: queryData.page, pageSize: queryData.pageSize, type: queryData.type },
					beforeSend() {
						$('.joe_index__title-title .item').css({ 'pointer-events': 'none', 'cursor': 'not-allowed' });
					},
					success(res) {
						$('.joe_index__title-title .item').css({ 'pointer-events': '', 'cursor': '' });
						if (res.data.length === 0) {
							$('.joe_load').removeAttr('loading');
							$('.joe_index__list .joe_list__loading').hide(500);
							$('.joe_load').hide();
							$('.joe_load').html('<i class="fa fa-angle-right"></i>加载更多');
							return Qmsg.warning('没有更多内容了');
						}
						res.data.forEach(data => $('.joe_index__list .joe_list').append(getListMode(data)));
						// 文章列表缩略图加载失败自动使用主题自带缩略图
						if (window.thumbOnError) window.thumbOnError();
						// a标签点击后的离开Loading动画
						if (window.offLoading) window.offLoading();
						$('.joe_load').removeAttr('loading');
						$('.joe_load').html('<i class="fa fa-angle-right"></i>加载更多');
						$('.joe_index__list .joe_list__loading').hide();
						reslove(res.data.length > 0 ? res.data.length - 1 : 0);
					},
					error() {
						$('.joe_index__title-title .item').css({ 'pointer-events': '', 'cursor': '' });
					}
				});
			});
		};
		initDom();
		pushDom();
		$('.joe_index__title-title .item').on('click', async function () {
			if ($(this).attr('data-type') === queryData.type) return;
			queryData = { page: 1, pageSize: window.Joe.PAGE_SIZE, type: $(this).attr('data-type') };
			initDom();
			pushDom();
		});
		$('.joe_load').on('click', async function () {
			if ($(this).attr('loading')) return;
			queryData.page++;
			let length = await pushDom();
			length = $('.joe_index__list .joe_list .joe_list__item').length - length;
			const queryElement = `.joe_index__list .joe_list .joe_list__item:nth-child(${length})`;
			const offset = $(queryElement).offset().top - $('.joe_header').height();
			window.scrollTo({ top: offset - 15, behavior: 'smooth' });
		});
	}

	/* 激活列表特效 */
	{
		const wow = $('.joe_index__list').attr('data-wow');
		if (wow !== 'off' && wow) new WOW({ boxClass: 'wow', animateClass: `animated ${wow}`, offset: 0, mobile: true, live: true, scrollContainer: null }).init();
	}
});

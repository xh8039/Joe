Joe.DOMContentLoaded.index ||= () => {
	console.log('调用：Joe.DOMContentLoaded.index');
	/* 激活轮播图功能 */
	{
		if ($('.joe_index__banner .swiper').length !== 0) {
			new Swiper('.swiper', {
				keyboard: false, // 使用键盘在幻灯片中导航
				direction: Joe.options.JIndexCarouselDirection || 'horizontal',
				loop: true,
				autoplay: true,
				mousewheel: false, // 允许使用鼠标滚轮在幻灯片中导航
				speed: 800, // 控制过渡时间为800毫秒
				autoplay: { delay: 2000 }, // 控制自动播放之间的延迟为2秒
				pagination: { el: '.swiper-pagination' }, // 具有分页参数的对象或布尔值，以使用默认设置启用。
				// 具有导航参数的对象或布尔值，以使用默认设置启用。
				navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
			});
		}
	}

	/* 初始化首页列表功能 */
	{
		if (Joe.options.IndexAjaxList == 'on') {

			const getTags = (data) => {
				let tagsHtml = '';
				if (data.fields.hide == 'pay' && data.fields.pay_tag_background != 'none') {
					tagsHtml += `<a rel="nofollow" href="${data.permalink}?scroll=pay-box" class="meta-pay but jb-${data.fields.pay_tag_background}">${data.fields.price > 0 ? ('付费阅读<span class="em09 ml3">￥</span>' + data.fields.price) : '免费资源'}</a>`;
				}
				var color = ['c-blue', 'c-yellow', 'c-green', 'c-cyan', 'c-blue-2', 'c-purple-2', 'c-yellow-2', 'c-purple', 'c-red-2', 'c-red'];
				data.category.forEach((element, index) => {
					tagsHtml += `<a class="but ${color[index]}" title="查看此分类更多文章" href="${element.permalink}"><i class="fa fa-folder-open-o" aria-hidden="true"></i>${element.name}</a>`;
				});
				data.tags.forEach(tag => {
					tagsHtml += `<a class="but" title="查看此标签更多文章" href="${tag.permalink}"># ${tag.name}</a>`
				});
				return tagsHtml;
			}

			const getListMode = _ => {
				if (_.mode === 'default') {
					return `
					${Joe.options.JArticleListAtropos == 'on' ? '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' : ''}
					<li class="joe_list__item wow default">
						<div class="line"></div>
						<a href="${_.permalink}" class="thumbnail" title="${_.title}">
							${!_.type || _.type == 'normal' ? '' : '<badge class="img-badge left jb-red">' + _.type + '</badge>'}
							<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="${_.lazyload}" data-src="${_.image[0]}" alt="${_.title}" />
							<time datetime="${_.time}">${_.time}</time>
							<svg width="20" height="20"><use xlink:href="#icon-joe-article-thumbnail"></use></svg>
						</a>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}">${_.title}</a>
							<a class="abstract" href="${_.permalink}" title="文章摘要">${_.abstract}</a>
							<div class="meta">
								<div class="item-tags scroll-x no-scrollbar mb6">
									${getTags(_)}
								</div>
								<div class="item-meta muted-2-color flex jsb ac">
									<item class="meta-author flex ac">
										<a href="${_.author_permalink}">
											<span class="avatar-mini">
												<img alt="${_.author_screenName}的头像 - ${Joe.options.title}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1" onerror="Joe.avatarError(this)">
											</span>
										</a>
										<span class="hide-sm ml6">${_.author_screenName}</span>
										<span title="${_.date_time}" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
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
					${Joe.options.JArticleListAtropos == 'on' ? '</div></div></div></div>' : ''}
				`;
				} else if (_.mode === 'single') {
					return `
					${Joe.options.JArticleListAtropos == 'on' ? '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' : ''}
					<li class="joe_list__item wow single">
						<div class="line"></div>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}">
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
												<img alt="${_.author_screenName}的头像 - ${Joe.options.title}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1" onerror="Joe.avatarError(this)">
											</span>
										</a>
										<span class="hide-sm ml6">${_.author_screenName}</span>
										<span title="${_.date_time}" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
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
						<a href="${_.permalink}" class="thumbnail" title="${_.title}">
							<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="${_.lazyload}" data-src="${_.image[0]}" alt="${_.title}" />
							<time datetime="${_.time}">${_.time}</time>
							<svg width="20" height="20"><use xlink:href="#icon-joe-article-thumbnail"></use></svg>
						</a>
						<div class="information" style="margin-bottom: 0;">
							<a class="abstract" href="${_.permalink}" title="文章摘要">${_.abstract}</a>
						</div>
					</li>
					${Joe.options.JArticleListAtropos == 'on' ? '</div></div></div></div>' : ''}
				`;
				} else if (_.mode === 'multiple') {
					return `
					${Joe.options.JArticleListAtropos == 'on' ? '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' : ''}
					<li class="joe_list__item wow multiple">
						<div class="line"></div>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}">
								${!_.type || _.type == 'normal' ? '' : '<span class="badge">' + _.type + '</span>'}${_.title}
							</a>
							<a class="abstract" href="${_.permalink}" title="文章摘要">${_.abstract}</a>
						</div>
						<a href="${_.permalink}" class="thumbnail" title="${_.title}">
							${_.image
							.map((item, index) => {
								if (index < 3) {
									return `<img referrerpolicy="no-referrer" rel="noreferrer" onerror="Joe.thumbnailError(this)" width="100%" height="100%" class="lazyload" src="${_.lazyload}" data-src="${item}" alt="${_.title}" />`;
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
											<img alt="${_.author_screenName}的头像 - ${Joe.options.title}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1" onerror="Joe.avatarError(this)">
										</span>
									</a>
									<span class="hide-sm ml6">${_.author_screenName}</span>
									<span title="${_.date_time}" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
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
					${Joe.options.JArticleListAtropos == 'on' ? '</div></div></div></div>' : ''}
				`;
				} else {
					return `
					${Joe.options.JArticleListAtropos == 'on' ? '<div class="atropos"><div class="atropos-scale"><div class="atropos-rotate"><div class="atropos-inner">' : ''}
					<li class="joe_list__item wow none">
						<div class="line"></div>
						<div class="information">
							<a href="${_.permalink}" class="title" title="${_.title}">
								${!_.type || _.type == 'normal' ? '' : '<span class="badge">' + _.type + '</span>'}${_.title}
							</a>
							<a class="abstract" href="${_.permalink}" title="文章摘要">${_.abstract}</a>
							<div class="meta">
								<div class="item-tags scroll-x no-scrollbar mb6">
									${getTags(_)}
								</div>
								<div class="item-meta muted-2-color flex jsb ac">
									<item class="meta-author flex ac">
										<a href="${_.author_permalink}">
											<span class="avatar-mini">
												<img alt="${_.author_screenName}的头像 - ${Joe.options.title}" src="${Joe.THEME_URL}/assets/images/avatar-default.png" data-src="${_.author_avatar}" class="lazyload avatar avatar-id-1" onerror="Joe.avatarError(this)">
											</span>
										</a>
										<span class="hide-sm ml6">${_.author_screenName}</span>
										<span title="${_.date_time}" data-toggle="tooltip" class="icon-circle" style="white-space: nowrap;overflow: hidden;">${_.dateWord}</span>
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
					${Joe.options.JArticleListAtropos == 'on' ? '</div></div></div></div>' : ''}
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
						url: Joe.BASE_API + '/publish-list',
						type: 'POST',
						dataType: 'json',
						data: { page: queryData.page, pageSize: queryData.pageSize, type: queryData.type },
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
								return autolog.warn('没有更多内容了');
							}
							res.data.forEach(data => $('.joe_index__list .joe_list').append(getListMode(data)));
							if (window.Joe.tooltip) window.Joe.tooltip('.joe_index__list');
							$('.joe_load').removeAttr('loading');
							$('.joe_load').html('<i class="fa fa-angle-right"></i>加载更多');
							$('.joe_index__list .joe_list__loading').hide();
							reslove(res.data.length > 0 ? res.data.length - 1 : 0);
							/** Atropos 悬浮动画 */
							if (window.Atropos) document.querySelectorAll('.atropos').forEach(element => {
								Atropos({ el: element, shadowOffset: 80 });
								setAtroposOffset(element.querySelector('.atropos-inner'));
							});
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
		} else {
			/** Atropos 悬浮动画 */
			if (window.Atropos) document.querySelectorAll('.atropos').forEach(element => {
				Atropos({ el: element, shadowOffset: 80 });
				setAtroposOffset(element.querySelector('.atropos-inner'));
			});
			Joe.pjax('.joe_pagination>li>a[href]', ['.joe_index__list', '.joe_pagination'], {
				beforeSend() {
					$('.joe_pagination').html('<div class="loading-module"><i class="loading mr6"></i><text>请稍候</text></div>');
				},
				replace() {
					$('.joe_pagination>li>a[href]').attr('ajax-replace', 'true');
					/** Atropos 悬浮动画 */
					if (window.Atropos) document.querySelectorAll('.atropos').forEach(element => {
						Atropos({ el: element, shadowOffset: 80 });
						setAtroposOffset(element.querySelector('.atropos-inner'));
					});
				},
				scrollTo: '.joe_index__list'
			});
		}
	}

	/* 激活列表特效 */
	{
		const wow = $('.joe_index__list').attr('data-wow');
		if (wow !== 'off' && wow) new WOW({ boxClass: 'wow', animateClass: `animate__animated animate__${wow}`, offset: 0, mobile: true, live: true, scrollContainer: null }).init();
	}
}
document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.index, { once: true });
var articleTitleList = $('.joe_detail__article').find('h1, h2, h3, h4, h5, h6');
if (articleTitleList.length > 0) {
	(function () {
		// 生成唯一ID的计数器
		let idCounter = 0;

		for (let heading of articleTitleList) {
			const headingLevel = heading.tagName.toUpperCase();
			const $heading = $(heading);
			// console.log($heading);
			const headingName = $heading.text().trim();
			// const enHeadingName = encodeURIComponent(headingName);
			// 使用计数器生成唯一ID
			const uniqueId = `heading-${idCounter}`;
			idCounter++;
			$heading.attr('id', `${uniqueId}`);
			$('.posts-nav-lists>ul').append(`<li class="n-${headingLevel}"><a id="catalog-${uniqueId}" href="#${uniqueId}">${headingName}</a></li>`);
			// const anchorName = $heading.attr('id');
			// console.log(headingLevel, headingName);
		}

		if (Joe.IS_MOBILE) {
			$('.joe_action').append(`<div class="joe_action_item posts-nav-switcher"><i class="fa fa-list-ul"></i></div>`)
			let joe_aside = $('.joe_aside .joe_aside__item.posts-nav-box .joe_aside__item-contain').html();
			$('.joe_aside .joe_aside__item.posts-nav-box').remove();
			let html = document.createElement('div');
			html.className = 'posts-nav-box';
			html.innerHTML = joe_aside;
			// console.log(html);
			$('.posts-nav-switcher').append(html);
			$('.joe_action_item .posts-nav-box').css({
				'position': 'absolute',
				'display': 'none',
				'right': '50px',
				'bottom': '0px',
				'padding': '15px',
				'border-radius': 'var(--radius-wrap)',
				'box-shadow': '0 0 10px 8px var(--main-shadow)',
				'overflow': 'auto',
				'max-height': '50vh',
				'max-width': '80vw'
			})
			$('.joe_action_item.posts-nav-switcher').click(() => {
				$('.joe_action_item.posts-nav-switcher .posts-nav-box').fadeToggle(200);
			});
		}

		const catalogTrack = () => {
			// console.log('页面滚动标题监听');
			let $currentHeading = $('h1');
			for (let heading of articleTitleList) {
				const $heading = $(heading);
				if (($heading.offset().top - $(document).scrollTop()) > $('.joe_header').height()) {
					break;
				}
				$currentHeading = $heading;
				const anchorName = $currentHeading.attr('id');
				const catalog = document.getElementById(`catalog-${anchorName}`);
				const $catalog = $(catalog).parent();
				if (!$catalog.hasClass('active')) {
					$('.posts-nav-lists>ul>li').removeClass('active');
					$catalog.addClass('active');
				}
				if ($catalog.length > 0) {
					if ($('.posts-nav-box .joe_aside__item-contain').length > 0) {
						$('.posts-nav-box .joe_aside__item-contain').scrollTop($catalog[0].offsetTop - 50);
					} else {
						$('.posts-nav-box').scrollTop($catalog[0].offsetTop - 50);
					}
				} else {
					$('.posts-nav-lists').scrollTop(0);
				}
			}
		};

		/**
		 * 函数节流，时间戳方案
		 * @param {*} fn 
		 * @param {*} wait 
		 * @returns 
		 */
		function throttle(fn, wait) {
			var pre = Date.now();
			return function () {
				var context = this;
				var args = arguments;
				var now = Date.now();
				if (now - pre >= wait) {
					fn.apply(context, args);
					pre = Date.now();
				}
			}
		}

		var isCatalogClicking = false;

		window.addEventListener('scroll', throttle(() => {
			if (!isCatalogClicking) {
				catalogTrack();
			}
		}, 500));

		function scrollcallback(fn) {
			let lastScrollTime = 0;
			const scrollThreshold = 200; // 滚动完成后等待的时间阈值

			window.addEventListener('scroll', () => {
				lastScrollTime = Date.now();
				setTimeout(() => {
					if (Date.now() - lastScrollTime > scrollThreshold) {
						// 滚动完成
						// console.log("Scroll finished!");
						fn();
					}
				}, scrollThreshold);
			});
		}

		// 监听文章目录a标签点击
		document.querySelectorAll('.posts-nav-lists>ul>li>a').forEach(link => {
			link.addEventListener('click', (event) => {
				event.preventDefault(); // 阻止默认跳转行为

				// 设置标志位，表示正在点击目录链接
				isCatalogClicking = true;

				catalog = $(link).parent();
				console.log(catalog);
				if (!catalog.hasClass('active')) {
					$('.posts-nav-lists>ul>li').removeClass('active');
					catalog.addClass('active');
				}

				// 获取目标元素 ID
				const targetId = link.getAttribute('href').substring(1);
				const targetElement = document.getElementById(targetId);
				if (targetElement) {
					// 获取目标元素距离页面顶部的距离
					const targetTop = $(targetElement).offset().top;
					// console.log(targetTop);
					// 获取顶栏高度，考虑动态高度变化和内部元素
					const headerHeight = document.querySelector('.joe_header').offsetHeight;
					// 计算滚动位置
					const scrollTop = (targetTop - headerHeight) - 10; // 预留10px的美观距离
					window.scrollTo({
						top: scrollTop,
						behavior: 'smooth'
					});
					scrollcallback(() => {
						isCatalogClicking = false;
					})
				} else {
					console.error('目标元素未找到:', targetId);
				}
			});
		});

	}())
} else {
	$('.joe_aside__item.posts-nav-box').remove();
}
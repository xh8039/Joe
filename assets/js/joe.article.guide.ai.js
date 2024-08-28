// 获取所有文章标题
const articleTitleList = $('.joe_detail__article').find('h1, h2, h3, h4, h5, h6');

// 如果存在文章标题
if (articleTitleList.length > 0) {
	// 生成目录
	generateArticleCatalog(articleTitleList);

	// 移动端目录处理
	if (Joe.IS_MOBILE) {
		handleMobileCatalog();
	} else {
		handleDesktopCatalog();
	}

	// 页面滚动监听
	window.addEventListener('scroll', throttle(catalogTrack, 200));
} else {
	// 移除目录容器
	$('.joe_aside__item.posts-nav-box').remove();
}

// 生成文章目录
function generateArticleCatalog(articleTitleList) {
	let idCounter = 0;
	const $catalogList = $('.posts-nav-lists>ul');
	for (const heading of articleTitleList) {
		const headingLevel = heading.tagName.toUpperCase();
		const $heading = $(heading);
		const headingName = $heading.text().trim();
		const uniqueId = `heading-${idCounter++}`;
		$heading.attr('id', uniqueId);
		$catalogList.append(`<li class="n-${headingLevel}"><a class="catalog-${uniqueId}" href="#${uniqueId}">${headingName}</a></li>`);
	}
}

// 移动端目录处理
function handleMobileCatalog() {
	// 添加目录切换按钮
	$('.joe_action').append(`<div class="joe_action_item posts-nav-switcher"><i class="fa fa-list-ul"></i></div>`);
	// 将目录移动到按钮内
	const $navBox = $('.joe_aside .joe_aside__item.posts-nav-box .joe_aside__item-contain').clone();
	$('.joe_aside .joe_aside__item.posts-nav-box').remove();
	$('.posts-nav-switcher').append($navBox);
	// 设置目录样式
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
		'max-width': '80vw',
		'background': 'var(--background)',
	});
	// 监听点击事件
	$('.joe_action_item.posts-nav-switcher').click(() => {
		$('.joe_action_item.posts-nav-switcher .posts-nav-box').fadeToggle(200);
	});
}

// 桌面端目录处理
function handleDesktopCatalog() {
	// 克隆目录容器
	const $navAsideClone = $('.joe_aside>.joe_aside__item.posts-nav-box').clone();
	// 添加到页面
	$('.joe_aside').append($navAsideClone);
}

// 页面滚动标题监听
function catalogTrack() {
	let $currentHeading = $('h1');
	const joeHeaderHeight = $('.joe_header').height();
	for (const heading of articleTitleList) {
		const $heading = $(heading);
		if (($heading.offset().top - $(document).scrollTop()) > (joeHeaderHeight + 10)) {
			break;
		}
		$currentHeading = $heading;
		const anchorName = $currentHeading.attr('id');
		const $catalog = $(`.catalog-${anchorName}`).parent();
		if (!$catalog.hasClass('active')) {
			$('.posts-nav-lists>ul>li').removeClass('active');
			$catalog.addClass('active');
		}
		if ($catalog.length > 0) {
			const $navBox = Joe.IS_MOBILE ? $('.posts-nav-box') : $('.posts-nav-box .joe_aside__item-contain');
			$navBox.scrollTop($catalog[0].offsetTop - 50);
		} else {
			$('.posts-nav-lists').scrollTop(0);
		}
	}
}

// 函数节流
function throttle(fn, wait) {
	let pre = Date.now();
	return function () {
		const now = Date.now();
		if (now - pre >= wait) {
			fn.apply(this, arguments);
			pre = now;
		}
	};
}

// 监听文章目录a标签点击
document.querySelectorAll('.posts-nav-lists>ul>li>a').forEach(link => {
	link.addEventListener('click', (event) => {
		event.preventDefault(); // 阻止默认跳转行为
		let isCatalogClicking = true; // 设置标志位，表示正在点击目录链接
		const $catalog = $(link).parent();
		if (!$catalog.hasClass('active')) {
			$('.posts-nav-lists>ul>li').removeClass('active');
			$catalog.addClass('active');
		}
		const targetId = link.getAttribute('href').substring(1);
		const targetElement = document.getElementById(targetId);
		if (targetElement) {
			const targetTop = $(targetElement).offset().top;
			const scrollTop = (targetTop - $('.joe_header').height()) - 10;
			window.scrollTo({
				top: scrollTop,
				behavior: 'smooth'
			});
			scrollcallback(() => {
				isCatalogClicking = false;
			});
		} else {
			console.error('目标元素未找到:', targetId);
		}
	});
});

// 滚动完成回调
function scrollcallback(fn) {
	let lastScrollTime = 0;
	const scrollThreshold = 200;
	window.addEventListener('scroll', () => {
		lastScrollTime = Date.now();
		setTimeout(() => {
			if (Date.now() - lastScrollTime > scrollThreshold) {
				fn();
			}
		}, scrollThreshold);
	});
}
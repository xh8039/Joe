function section_navs(selector) {

	//严格模式
	'use strict';

	var navbox_selector = '.posts-nav-box';
	var _body = $('body');
	var nav_class = 'posts-nav-lists';
	var nav_selector = '.' + nav_class;
	var selector_s = selector + ' h1,' + selector + ' h2,' + selector + ' h3,' + selector + ' h4,' + selector + ' h5,' + selector + ' h6';

	var index = 0;

	function scrollTo(o, t, l) {
		var scrollTop = 0;
		var _body = $('body,html');
		l = l || 300;
		t = t || 0;

		if (o) {
			var _o = o instanceof jQuery ? o : $(o);
			// scrollTop = _o.length ? _o.offset().top + t - (_body.hasClass('nav-fixed') ? $('.header').innerHeight() + 20 : 0) : 0;
			// scrollTop = _o.length ? (_o.offset().top + t - $('.joe_header').innerHeight() + 20) : 0;
			scrollTop = _o.length ? (_o.offset().top + t - $('.joe_header').innerHeight() - 10) : 0;
		}
		_body.animate(
			{
				scrollTop: scrollTop,
			},
			l,
			'swing'
		);
	}

	function aotu_scrollTo() {
		var hash = window.location.hash;
		if (hash && $(hash).length) {
			$('a[href="' + hash + '"]').click();
		}
	}

	function getHiddenElementHeight($element) {
		let innerHeight = $element.innerHeight();
		if (innerHeight > 0) {
			return innerHeight;
		} else {
			$('.posts-nav-switcher>.zib-widget').css({
				'visibility': 'hidden',
				'display': 'block',
			});
			const height = $element.innerHeight();
			$('.posts-nav-switcher>.zib-widget').css({
				'visibility': '',
				'display': 'none',
			});
			return height;
		}
	}

	var add_box = function () {
		var navs_lists = GetnavsLists();
		if (!navs_lists) return;

		$(navbox_selector).each(function () {
			var _this = $(this);
			if (window.Joe.IS_MOBILE) {
				var name = '';
			} else {
				var name = _this.attr('data-title') || '';
				name = name && '<div class="joe_aside__item-title"><div class="title-theme">' + name + '</div></div>';
			}
			_this.append(name + '<div class="zib-widget"><div class="' + nav_class + ' scroll-y mini-scrollbar list-unstyled"></div></div>');
		});
		add_lists(navs_lists);
		setTimeout(function () {
			aotu_scrollTo();
		}, 20);
	};

	//设置排除的元素
	function isExcludedElement(el) {
		return el.hasClass('item-heading') || el.parents('.no-nav').length;
	}

	function GetnavsLists() {
		var navs_lists = '';
		$(selector_s).each(function (indexInArray) {
			var _this = $(this);
			if (!isExcludedElement(_this)) {
				var tag = _this.prop('tagName');
				var text = _this.text();
				var _class = 'n-' + tag;
				_class += indexInArray == 1 ? ' active' : '';
				index = indexInArray;
				_this.attr('id', 'wznav_' + index);
				navs_lists += '<li class="' + _class + '"><a class="text-ellipsis" href="#wznav_' + index + '">' + text + '</a></li>';
			}
		});
		return navs_lists;
	}

	var add_lists = function (navs_lists) {
		$(nav_selector).html('<ul class="bl relative nav">' + navs_lists + '</ul>');
		$(nav_selector + ' a').on('click', function (event) {
			event.preventDefault();
			var _href = $($(this).attr('href'));
			_href.parents('.panel').each(function () {
				var _collapse = $(this).find('.collapse');
				if (_collapse.length) {
					_collapse.collapse('show');
				}
			});

			$('.read-more-open').click();
			scrollTo(_href);
			if (window.Joe.IS_MOBILE) {
				$('.joe_action_item.posts-nav-switcher>.zib-widget').fadeOut(200);
			}
			return false;
		});

		// 监测滚动自动为目录添加类名 active 
		_body.scrollspy({
			target: nav_selector,
			// 抵消顶栏高度
			offset: $('.joe_header').innerHeight() + 10 + 1,
		});
		// 目录随文章内容滚动
		if (getHiddenElementHeight($(nav_selector + '>ul')) >= 400) {
			$(nav_selector).on('activate.bs.scrollspy', function () {
				var currentItem = $(this).find('ul>li.active');
				$(this).scrollTop(currentItem[0].offsetTop - 50);
			});
		}
		collapse();
	};

	//显示折叠内容
	var collapse = function () {
		var find_selector = '.n-H1,.n-H2';
		$(nav_selector + ' .bl').each(function () {
			var _this = $(this);
			let height = getHiddenElementHeight(_this);
			if (height > 380) {
				_this.find(find_selector).each(function () {
					var _this = $(this);
					var _n_h2 = _this.nextUntil(find_selector);
					if (_n_h2.length) {
						_n_h2.addClass('yc');
						_this.append('<i class="fa fa-angle-right nav-toggle-collapse"></i>');
					}
				});
			}
		});

		_body.off('click', nav_selector + ' .nav-toggle-collapse');
		_body.on('click', nav_selector + ' .nav-toggle-collapse', function () {
			$(this).toggleClass('fa-rotate-90').parent().nextUntil(find_selector).toggleClass('yc');
		});
	};

	if ($(selector_s).length > 2) {
		if (Joe.IS_MOBILE && !$('.joe_action>' + navbox_selector).length) {
			$('.joe_action').append('<div data-affix="true" class="joe_action_item posts-nav-box posts-nav-switcher" data-title="文章目录"><i class="fa fa-list-ul"></i></div>');
		}
		if (!Joe.IS_MOBILE) {
			if (!$('.joe_aside>' + navbox_selector).length) {
				const section = '<section data-affix="true" class="posts-nav-box joe_aside__item" data-title="文章目录"></section>';
				$('.joe_aside').children().eq(1).after(section);
				$('.joe_aside').append(section);
			}
			document.addEventListener('turbolinks:complete', () => {
				$('.joe_aside>' + navbox_selector).remove();
			},{ once: true });
		}
		add_box();
	} else {
		$(navbox_selector).remove();
	}

}

document.addEventListener(Joe.DOMContentLoaded.event, () => {
	// console.log('加载文章目录');
	$('.posts-nav-switcher>.zib-widget').remove();
	section_navs('[data-nav]');
	if (window.Joe.IS_MOBILE) {
		$('.joe_action_item.posts-nav-switcher').unbind('click');
		$('.joe_action_item.posts-nav-switcher').on('click', function (event) {
			if (event.target != this && event.target.parentElement != this) return;
			$('.posts-nav-switcher>.zib-widget').css('display') == 'none' ? $('.joe_action').css('z-index', '100') : $('.joe_action').css('z-index', '98');
			$('.posts-nav-switcher>.zib-widget').fadeToggle(200);
		});
	}
}, { once: true });
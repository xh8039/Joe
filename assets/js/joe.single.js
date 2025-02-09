Joe.DOMContentLoaded.single ||= () => {
	console.log('调用：Joe.DOMContentLoaded.single');

	/* 获取本篇文章百度收录情况 */
	(() => {
		if (!document.getElementById('Joe_Baidu_Record')) return;
		$.ajax({
			url: Joe.BASE_API + '/baidu-record',
			type: 'POST',
			dataType: 'json',
			data: { site: window.location.href, cid: window.Joe.CONTENT.cid },
			success(res) {
				if (!res.data) {
					if (Joe.options.BaiduPush) {
						$('#Joe_Baidu_Record').html(`<a href="javascript:window.Joe.submit_baidu();" style="color: #F56C6C">检测失败，提交收录</a>`);
						return
					}
					const url = `https://ziyuan.baidu.com/linksubmit/url?sitename=${encodeURI(window.location.href)}`;
					$('#Joe_Baidu_Record').html(`<a target="_blank" href="${url}" rel="noopener noreferrer nofollow" style="color: #F56C6C">检测失败，提交收录</a>`);
					return
				}
				if (res.data == '未收录，已推送') {
					$('#Joe_Baidu_Record').css('color', 'var(--theme)');
					$('#Joe_Baidu_Record').html(res.data);
					return
				}
				if (res.data == '已收录') {
					$('#Joe_Baidu_Record').css('color', '#67C23A');
					$('#Joe_Baidu_Record').html('已收录');
					return
				}
				/* 如果填写了Token，则自动推送给百度 */
				if ((res.data == '未收录') && (Joe.options.BaiduPush)) {
					window.Joe.submit_baidu('未收录，推送中...');
					return
				}
				if (Joe.options.BaiduPush) {
					$('#Joe_Baidu_Record').html(`<a href="javascript:window.Joe.submit_baidu();" style="color: #F56C6C">${res.data}，提交收录</a>`);
					return
				}
				const url = `https://ziyuan.baidu.com/linksubmit/url?sitename=${encodeURI(window.location.href)}`;
				$('#Joe_Baidu_Record').html(`<a target="_blank" href="${url}" rel="noopener noreferrer nofollow" style="color: #F56C6C">${res.data}，提交收录</a>`);
			}
		});
	})();

	/* 激活代码高亮 */
	(() => {
		return;
		if (!window.Prism || !document.querySelector("code[class*='lang-']")) return;
		Prism.highlightAll();
		$("pre[class*='language-']").each(function (index, item) {
			let text = $(item).find("code[class*='language-']").text().replace(/    /g, '	');
			let span = $(`<span data-toggle="tooltip" data-placement="top" title="点击复制" class="copy"><i class="fa fa-clone"></i></span>`);
			if (!Joe.IS_MOBILE) {
				span.tooltip({
					container: "body"
				});
				span.on('click', function (event) {
					$(this).tooltip('hide');
				});
			}
			span.click(() => {
				Joe.clipboard(text, () => {
					autolog.log(`代码已复制 代码版权属于 ${Joe.options.title} 转载请标明出处！`, 'success', false);
				});
			});
			$(item).append(span);
		});
	})();


	/* 监听网页复制行为 */
	(() => {
		if (!document.querySelector('.joe_detail__article')) return;
		document.querySelector('.joe_detail__article').addEventListener('copy', () => {
			autolog.log(`本文版权属于 ${Joe.options.title} 转载请标明出处！`, 'warn', false);
		});
	})();

	/* 激活图片预览功能 */
	{
		if ($.fancybox) $.fancybox.defaults.hash = false;
		$('.joe_detail__article img:not(img.owo_image)[fancybox!="false"]').each(function () {
			$(this).wrap($(`<span style="display: block;" data-fancybox="Joe" href="${$(this).attr('src')}"></span>`));
		});
	}

	/* 激活浏览功能 */
	(() => {
		if (!document.querySelector('#Joe_Article_Views')) return;
		const cid = window.Joe.CONTENT.cid || $('.joe_detail').attr('data-cid');
		let viewsArr = localStorage.getItem(Joe.base64_encode('views')) ? JSON.parse(Joe.base64_decode(localStorage.getItem(Joe.base64_encode('views')))) : [];
		const flag = viewsArr.includes(cid);
		if (!flag) {
			$.ajax({
				url: Joe.BASE_API,
				type: 'POST',
				dataType: 'json',
				data: { routeType: 'handle_views', cid },
				success(res) {
					if (res.code !== 1) return;
					$('#Joe_Article_Views').html(res.data.views);
					viewsArr.push(cid);
					const name = Joe.base64_encode('views');
					const val = Joe.base64_encode(JSON.stringify(viewsArr));
					localStorage.setItem(name, val);
				}
			});
		}
	})();

	/* 激活文章点赞功能 */
	(() => {
		if (!document.querySelector('.action-like')) return;
		let agreeArr = localStorage.getItem(Joe.base64_encode('agree')) ? JSON.parse(Joe.base64_decode(localStorage.getItem(
			Joe.base64_encode('agree')))) : [];
		if (agreeArr.includes(Joe.CONTENT.cid)) {
			$('.action-like').addClass('active');
			$('.action-like>text').text('已赞');
		} else {
			$('.action-like').removeClass('active');
			$('.action-like>text').text('点赞');
		}
		let _loading = false;
		$('.action-like').on('click', function () {
			if (_loading) return;
			_loading = true;
			agreeArr = localStorage.getItem(Joe.base64_encode('agree')) ? JSON.parse(Joe.base64_decode(localStorage
				.getItem(Joe.base64_encode('agree')))) : [];
			let flag = agreeArr.includes(Joe.CONTENT.cid);
			$.ajax({
				url: Joe.BASE_API,
				type: 'POST',
				dataType: 'json',
				data: {
					routeType: 'handle_agree',
					cid: Joe.CONTENT.cid,
					type: flag ? 'disagree' : 'agree'
				},
				beforeSend: function () {
					$('.action-like').css('pointer-events', 'none').find('count').html('<i class="loading zts"></i>');
				},
				success(res) {
					if (res.code !== 1) return;
					$('.action-like>count').html(res.data.agree);
					if (flag) {
						const index = agreeArr.findIndex(_ => _ === Joe.CONTENT.cid);
						agreeArr.splice(index, 1);
						$('.action-like').removeClass('active');
						$('.action-like>text').text('点赞');
						autolog.log('取消点赞', 'info');
					} else {
						agreeArr.push(Joe.CONTENT.cid);
						$('.action-like').addClass('active');
						$('.action-like>text').text('已赞');
						autolog.log('已赞，感谢您的支持！', 'success');
					}
					const name = Joe.base64_encode('agree');
					const val = Joe.base64_encode(JSON.stringify(agreeArr));
					localStorage.setItem(name, val);
					$('.action-like').css('pointer-events', '')
				},
				complete() {
					_loading = false;
				}
			});
		});
	})();

	/* 密码保护文章，输入密码访问 */
	(() => {
		if (!document.querySelector('.joe_detail__article-protected')) return;
		let isSubmit = false;
		$('.joe_detail__article-protected').on('submit', function (e) {
			e.preventDefault();
			const url = $(this).attr('action') + '&time=' + +new Date();
			const protectPassword = $(this).find('input[type="password"]').val();
			if (protectPassword.trim() === '') return autolog.log('请输入访问密码！', 'info');
			if (isSubmit) return;
			isSubmit = true;
			$.ajax({
				url,
				type: 'POST',
				data: {
					cid: Joe.CONTENT.cid,
					protectCID: Joe.CONTENT.cid,
					protectPassword
				},
				dataType: 'text',
				success(res) {
					let arr = [],
						str = '';
					arr = $(res).contents();
					Array.from(arr).forEach(_ => {
						if (_.parentNode.className === 'container') str = _;
					});
					if (!/Joe/.test(res)) {
						autolog.log(str.textContent.trim() || '', 'warn');
						isSubmit = false;
						$('.joe_comment__respond-form .foot .submit button').html('发表评论');
					} else {
						location.reload();
					}
				}
			});
		});
	})();

	/* 激活文章视频模块 */
	if (document.querySelector('.joe_detail__article-video')) $.getScript(Joe.CDN_URL + 'dplayer/1.27.0/DPlayer.min.js', () => {
		window.videoPlayer = new DPlayer({
			container: document.querySelector('.joe_detail__article-video>.dplayer-video'), // 播放器容器元素
			autoplay: true, // 视频自动播放
			theme: getComputedStyle(document.documentElement).getPropertyValue('--theme').trim(), // 主题色
			lang: 'zh-cn', // 可选值: 'en', 'zh-cn', 'zh-tw'
			preload: 'auto', // 视频预加载，可选值: 'none', 'metadata', 'auto'
			loop: false, // 视频循环播放
			screenshot: true, // 开启截图，如果开启，视频和视频封面需要允许跨域
			airplay: true, // 在 Safari 中开启 AirPlay
			volume: 1, // 默认音量，请注意播放器会记忆用户设置，用户手动设置音量后默认音量即失效
			playbackSpeed: [2.00, 1.75, 1.50, 1.25, 1.00, 0.75, 0.50, 0.25], // 可选的播放速率，可以设置成自定义的数组
			video: {
				pic: Joe.CONTENT.cover
			}
		});
		$('.featured-video-episode>.switch-video').on('click', function () {
			$(this).addClass('active').siblings().removeClass('active');
			const url = $(this).attr('video-url');
			let title = $(this).attr('data-original-title');
			videoPlayer.switchVideo({ url: url });
			if (title) $('.joe_detail__article-video>.title').html(title);
		});
		$('.featured-video-episode>.switch-video').first().click();
		const next = () => {
			const notice = document.querySelector('.joe_detail__article-video .dplayer-notice');
			if (notice) {
				notice.classList.add('remove-notice');
				videoPlayer.events.trigger('notice_hide');
				setTimeout(() => notice.remove(), 3000);
			}
			const item = document.querySelector('.featured-video-episode>.switch-video.active');
			if (item.nextSibling) item.nextSibling.nextElementSibling.click();
			$('.joe_detail__article-video>.dplayer-video:not(.dplayer-hide-controller)').addClass('dplayer-hide-controller');
		}
		videoPlayer.on('play', setTimeout(() => {
			$('.joe_detail__article-video>.dplayer-video:not(.dplayer-hide-controller)').addClass('dplayer-hide-controller');
		}, 1000));
		videoPlayer.on('ended', () => next());
		videoPlayer.on('loadeddata', () => {
			if (videoPlayer.video.paused) videoPlayer.video.play();
		});
		videoPlayer.on('error', () => {
			// 不是视频加载错误，可能是海报加载失败
			if (!videoPlayer.video.error) return;
			setTimeout(() => next(), 2000);
		});
	});

	/* 复制链接 */
	(() => {
		if (!document.querySelector('.share-btn.copy')) return;
		let button = document.querySelector('.share-btn.copy');
		button.addEventListener('click', () => {
			window.Joe.clipboard(button.dataset.clipboardText, () => {
				autolog.log('链接已复制！', 'success');
			});
		});
	})();


	(() => {
		if (!document.querySelector('.swiper-scroll')) return;
		$('.swiper-scroll').each(function (e) {
			if ($(this).hasClass('swiper-container-initialized')) return;
			var option = {};
			var _this = $(this);
			var _eq = 'swiper-scroll-eq-' + e;
			var slideClass = _this.attr('data-slideClass') || false;
			slideClass && (option.slideClass = slideClass);

			if (!_this.attr('scroll-nogroup')) {
				var c_w = _this.width();
				var i_w = _this.find('.swiper-slide').outerWidth(true);
				var slidesPerGroup = ~~(c_w / i_w);
				option.slidesPerGroup = slidesPerGroup || 1;
			}

			option.autoplay = _this.attr('data-autoplay') ? {
				delay: ~~_this.attr('data-interval') || 4000,
				disableOnInteraction: false,
			} :
				false;
			option.loop = _this.attr('data-loop');
			option.slidesPerView = 'auto';
			option.mousewheel = {
				forceToAxis: true,
			};
			option.freeMode = true;
			option.freeModeSticky = true;

			option.navigation = {
				nextEl: '.swiper-scroll.' + _eq + ' .swiper-button-next',
				prevEl: '.swiper-scroll.' + _eq + ' .swiper-button-prev',
			};

			// console.log(option)

			_this.addClass(_eq).attr('swiper-scroll-index', e);
			new Swiper('.swiper-scroll.' + _eq, option);
		});
	})();

	/** 锚点丝滑滚动 */
	{
		setTimeout(window.Joe.anchor_scroll, 500);
	}

	/** 评论区禁止评论删除表情包功能 */
	{
		if (!$(".joe_owo__target").length || $('.joe_owo__target').attr('disabled')) $('.joe_owo__contain .seat').remove();
	}

	/* 分享 */
	{
		// if ($('.joe_detail__operate-share').length) {
		// 	$('.joe_detail__operate-share > svg').on('click', e => {
		// 		e.stopPropagation();
		// 		$('.joe_detail__operate-share').toggleClass('active');
		// 	});
		// 	$(document).on('click', () => $('.joe_detail__operate-share').removeClass('active'));
		// }
	}
};

document.addEventListener(Joe.DOMContentLoaded.event, Joe.DOMContentLoaded.single, { once: true });
document.addEventListener('DOMContentLoaded', () => {
	const encryption = str => window.btoa(unescape(encodeURIComponent(str)));
	const decrypt = str => decodeURIComponent(escape(window.atob(str)));

	/* 当前页的CID */
	const cid = $('.joe_detail').attr('data-cid');
	window.cid = cid;

	function loadJS(url, callback = function () { }) {
		window.loadJSList = window.loadJSList ? window.loadJSList : {};
		if (!loadJSList[url]) {
			var script = document.createElement('script');
			script.type = 'text/javascript';
			script.addEventListener('load', callback);
			script.src = url;
			document.getElementsByTagName('head')[0].appendChild(script);
			loadJSList[url] = script;
		} else {
			var script = loadJSList[url];
			script.addEventListener('load', callback);
		}
		// 检查元素是否存在，如果存在，则删除，浏览器不会重复载入
		// const existingScript = document.querySelector(`script[src="${url}"]`);
		// if (existingScript) existingScript.remove();
	}

	function PrismInit(item, language) {
		let text = item.textContent.replace(/    /g, '	');
		// console.log(language)
		let highlightedCode = Prism.highlight(text, Prism.languages[language], language);
		item.innerHTML = highlightedCode;
		// console.log(text);
		let span = $(`<span class="copy"><i class="fa fa-clone"></i></span>`);
		$(item).append(span);
		new ClipboardJS(span[0], {
			text: () => text
		}).on('success', () => {
			window.code_copy = true;
			Qmsg.success(`复制成功 内容版权属于 ${Joe.TITLE} 转载请标明出处！`, { 'showClose': true, 'autoClose': false });
		});
	}

	/* 获取本篇文章百度收录情况 */
	{
		if (document.getElementById('Joe_Baidu_Record')) {
			$.ajax({
				url: Joe.BASE_API,
				type: 'POST',
				dataType: 'json',
				data: {
					routeType: 'baidu_record',
					site: window.location.href,
					cid: cid
				},
				success(res) {
					if (!res.data) {
						if (Joe.BAIDU_PUSH) {
							$('#Joe_Baidu_Record').html(`<a href="javascript:submit_baidu();" rel="noopener noreferrer nofollow" style="color: #F56C6C">检测失败，提交收录</a>`);
							return
						}
						const url = `https://ziyuan.baidu.com/linksubmit/url?sitename=${encodeURI(window.location.href)}`;
						$('#Joe_Baidu_Record').html(`<a target="_blank" href="${url}" rel="noopener noreferrer nofollow" style="color: #F56C6C">检测失败，提交收录</a>`);
						return
					}
					if (res.data == '未收录，已推送') {
						$('#Joe_Baidu_Record').css('color', '#67C23A');
						$('#Joe_Baidu_Record').html(res.data);
						return
					}
					if (res.data == '已收录') {
						$('#Joe_Baidu_Record').css('color', '#67C23A');
						$('#Joe_Baidu_Record').html('已收录');
						return
					}
					/* 如果填写了Token，则自动推送给百度 */
					if ((res.data == '未收录') && (Joe.BAIDU_PUSH)) {
						submit_baidu('未收录，推送中...');
						return
					}
					if (Joe.BAIDU_PUSH) {
						$('#Joe_Baidu_Record').html(`<a href="javascript:submit_baidu();" rel="noopener noreferrer nofollow" style="color: #F56C6C">${res.data}，提交收录</a>`);
						return
					}
					const url = `https://ziyuan.baidu.com/linksubmit/url?sitename=${encodeURI(window.location.href)}`;
					$('#Joe_Baidu_Record').html(`<a target="_blank" href="${url}" rel="noopener noreferrer nofollow" style="color: #F56C6C">${res.data}，提交收录</a>`);
				}
			});
		}

	}

	/* 激活代码高亮 */
	{
		// Prism.highlightAll();
		$("code[class*='language-']").each(function (index, item) {
			var language = item.classList[0].replace("language-", "");
			// console.log(language);
			if (language == 'shell') language = 'powershell';
			if (Prism.languages[language]) {
				PrismInit(item, language);
			} else {
				loadJS(Joe.CDN(`prism/1.9.0/components/prism-${language}.min.js`), () => {
					PrismInit(item, language)
				});
			}
		});
	}

	/* 监听网页复制行为 */
	{
		// 获取所有具有类名 "joe_detail__article" 的元素
		const articleElements = document.querySelectorAll('.joe_detail__article');
		// 遍历每个元素，添加复制事件监听
		articleElements.forEach(article => {
			article.addEventListener('copy', (event) => {
				// 获取被复制的元素
				// const copiedElement = event.target;
				if (window.code_copy !== true && window.post_copy !== true) {
					Qmsg.warning(`本文版权属于 ${Joe.TITLE} 转载请标明出处！`, {
						'showClose': true,
						'autoClose': false
					});
					window.post_copy = true;
				}
				window.code_copy = false;
			});
		});
	}

	/* 激活图片预览功能 */
	{
		$('.joe_detail__article img:not(img.owo_image)[fancybox!="false"]').each(function () {
			$(this).wrap($(
				`<span style="display: block;" data-fancybox="Joe" href="${$(this).attr('src')}"></span>`
			));
		});
	}

	/* 激活浏览功能 */
	{
		let viewsArr = localStorage.getItem(encryption('views')) ? JSON.parse(decrypt(localStorage.getItem(
			encryption('views')))) : [];
		const flag = viewsArr.includes(cid);
		if (!flag) {
			$.ajax({
				url: Joe.BASE_API,
				type: 'POST',
				dataType: 'json',
				data: {
					routeType: 'handle_views',
					cid
				},
				success(res) {
					if (res.code !== 1) return;
					$('#Joe_Article_Views').html(res.data.views);
					viewsArr.push(cid);
					const name = encryption('views');
					const val = encryption(JSON.stringify(viewsArr));
					localStorage.setItem(name, val);
				}
			});
		}
	}

	/* 激活文章点赞功能 */
	{
		let agreeArr = localStorage.getItem(encryption('agree')) ? JSON.parse(decrypt(localStorage.getItem(
			encryption('agree')))) : [];
		if (agreeArr.includes(cid)) {
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
			agreeArr = localStorage.getItem(encryption('agree')) ? JSON.parse(decrypt(localStorage
				.getItem(encryption('agree')))) : [];
			let flag = agreeArr.includes(cid);
			$.ajax({
				url: Joe.BASE_API,
				type: 'POST',
				dataType: 'json',
				data: {
					routeType: 'handle_agree',
					cid,
					type: flag ? 'disagree' : 'agree'
				},
				beforeSend: function () {
					$('.action-like').css('pointer-events', 'none').find('count').html('<i class="loading zts"></i>');
				},
				success(res) {
					if (res.code !== 1) return;
					$('.action-like>count').html(res.data.agree);
					if (flag) {
						const index = agreeArr.findIndex(_ => _ === cid);
						agreeArr.splice(index, 1);
						$('.action-like').removeClass('active');
						$('.action-like>text').text('点赞');
						Qmsg.info('点赞已取消');
					} else {
						agreeArr.push(cid);
						$('.action-like').addClass('active');
						$('.action-like>text').text('已赞');
						Qmsg.success('已赞，感谢您的支持！');
					}
					const name = encryption('agree');
					const val = encryption(JSON.stringify(agreeArr));
					localStorage.setItem(name, val);
					$('.action-like').css('pointer-events', '')
				},
				complete() {
					_loading = false;
				}
			});
		});
	}

	/* 密码保护文章，输入密码访问 */
	{
		let isSubmit = false;
		$('.joe_detail__article-protected').on('submit', function (e) {
			e.preventDefault();
			const url = $(this).attr('action') + '&time=' + +new Date();
			const protectPassword = $(this).find('input[type="password"]').val();
			if (protectPassword.trim() === '') return Qmsg.info('请输入访问密码！');
			if (isSubmit) return;
			isSubmit = true;
			$.ajax({
				url,
				type: 'POST',
				data: {
					cid,
					protectCID: cid,
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
						Qmsg.warning(str.textContent.trim() || '');
						isSubmit = false;
						$('.joe_comment__respond-form .foot .submit button').html('发表评论');
					} else {
						location.reload();
					}
				}
			});
		});
	}

	/* 激活文章视频模块 */
	{
		if ($('.joe_detail__article-video').length > 0) {
			window.videoPlayer = new DPlayer({
				container: document.querySelector('.joe_detail__article-video>.dplayer-video'), // 播放器容器元素
				autoplay: true, // 视频自动播放
				// theme: getComputedStyle(document.documentElement).getPropertyValue('--theme').trim(), // 主题色
				lang: 'zh-cn', // 可选值: 'en', 'zh-cn', 'zh-tw'
				preload: 'metadata', // 视频预加载，可选值: 'none', 'metadata', 'auto'
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
				if (Joe.CONTENT.cover) {
					videoPlayer.switchVideo({ url: url, pic: Joe.CONTENT.cover });
				} else {
					videoPlayer.switchVideo({ url: url });
				}
				if (title) $('.joe_detail__article-video>.title').html(title);
			});
			$('.featured-video-episode>.switch-video').first().click();
			const next = () => {
				let item = document.querySelector('.featured-video-episode>.switch-video.active');
				if (item.nextSibling) item.nextSibling.nextElementSibling.click();
			}
			videoPlayer.on('ended', next);
			videoPlayer.on('loadeddata', () => {
				if (videoPlayer.video.paused) {
					videoPlayer.play();
				}
				if (videoPlayer.video.paused) {
					console.log(videoPlayer.video.paused);
					videoPlayer.video.play();
				}
			});
			videoPlayer.video.addEventListener('error', () => {
				setTimeout(() => {
					console.log('视频加载错误：' + videoPlayer.video.src);
					next();
				}, 2000);
			})
		}
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
});

/* 写在load事件里，为了解决图片未加载完成，滚动距离获取会不准确的问题 */
window.addEventListener('load', function () {
	/* 判断地址栏是否有锚点链接，有则跳转到对应位置 */
	{
		const scroll = new URLSearchParams(location.search).get('scroll');
		if (scroll) {
			let elementEL = null;
			if ($('#' + scroll).length > 0) {
				elementEL = $('#' + scroll);
			} else {
				elementEL = $('.' + scroll);
			}
			if (elementEL && elementEL.length > 0) {
				const top = elementEL.offset().top - $('.joe_header').height() - 15;
				window.scrollTo({
					top,
					behavior: 'smooth'
				});
			}
		}
	}
});

function submit_baidu(msg = '推送中...') {
	$('#Joe_Baidu_Record').html(`<span style="color: #E6A23C">${msg}</span>`);
	$.ajax({
		url: Joe.BASE_API,
		type: 'POST',
		dataType: 'json',
		data: {
			routeType: 'baidu_push',
			domain: window.location.protocol + '//' + window
				.location.hostname,
			url: encodeURI(window.location.href),
			cid: window.cid
		},
		success(res) {
			if (res.already) {
				$('#Joe_Baidu_Record').css('color', '#67C23A');
				$('#Joe_Baidu_Record').html('已推送');
				return
			}
			if (res.data.error) {
				if (res.data.message == 'over quota') res.data.message = '超过配额';
				$('#Joe_Baidu_Record').html('<span style="color: #F56C6C">推送失败，' + res.data.message + '</span>');
			} else {
				$('#Joe_Baidu_Record').html('<span style="color: #67C23A">推送成功！今日剩余' + res.data.remain + '条</span>');
			}
		},
		error(res) {
			$('#Joe_Baidu_Record').html('<span style="color: #F56C6C">推送失败，请检查！</span>');
		}
	});
	// 	顺便推送URL到必应
	if (!Joe.BING_PUSH) return;
	$.ajax({
		url: Joe.BASE_API,
		type: 'POST',
		dataType: 'json',
		data: {
			routeType: 'bing_push',
			domain: window.location.protocol + '//' + window
				.location.hostname,
			url: encodeURI(window.location.href)
		}
	});
}
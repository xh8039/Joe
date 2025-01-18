
Joe.DOMContentLoaded.leaving = Joe.DOMContentLoaded.leaving ? Joe.DOMContentLoaded.leaving : () => {
	const leavingListInit = () => {
		let _index = 100;
		const colors = ['#F8D800', '#0396FF', '#EA5455', '#7367F0', '#32CCBC', '#F6416C', '#28C76F', '#9F44D3', '#F55555', '#736EFE', '#E96D71', '#DE4313', '#D939CD', '#4C83FF', '#F072B6', '#C346C2', '#5961F9', '#FD6585', '#465EFB', '#FFC600', '#FA742B', '#5151E5', '#BB4E75', '#FF52E5', '#49C628', '#00EAFF', '#F067B4', '#F067B4', '#ff9a9e', '#00f2fe', '#4facfe', '#f093fb', '#6fa3ef', '#bc99c4', '#46c47c', '#f9bb3c', '#e8583d', '#f68e5f'];
		const random = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
		const maxWidth = $('.joe_detail__leaving-list').width();
		const maxHeight = $('.joe_detail__leaving-list').height();
		const radius1 = ['20px 300px', '20px 400px', '20px 500px', '30px 300px', '30px 400px', '30px 500px', '40px 300px', '40px 400px', '40px 500px'];
		const radius2 = ['300px 20px', '400px 20px', '500px 20px', '300px 30px', '400px 30px', '500px 30px', '300px 40px', '400px 40px', '500px 40px'];
		$('.joe_detail__leaving-list .item').each((index, item) => {
			const zIndex = random(1, 99);
			const background = colors[random(0, colors.length - 1)];
			const width = Math.ceil($(item).width());
			const height = Math.ceil($(item).height());
			const top = random(0, maxHeight - height);
			const left = random(0, maxWidth - width);
			$(item).css({
				display: 'block',
				zIndex,
				background,
				top,
				left,
				borderTopLeftRadius: radius2[random(0, radius2.length - 1)],
				borderTopRightRadius: radius1[random(0, radius1.length - 1)],
				borderBottomLeftRadius: radius1[random(0, radius1.length - 1)],
				borderBottomRightRadius: radius1[random(0, radius1.length - 1)]
			});
			$(item).draggabilly({ containment: true });
			$(item).on('dragStart', e => {
				_index++;
				$(item).css({ zIndex: _index });
			});
		});
	}

	const encryption = str => window.btoa(unescape(encodeURIComponent(str)));
	const decrypt = str => decodeURIComponent(escape(window.atob(str)));

	/* 当前页的CID */
	const cid = $('.joe_detail').attr('data-cid');

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
						if (Joe.options.BaiduPush) {
							$('#Joe_Baidu_Record').html(`<a href="javascript:window.Joe.submit_baidu();" rel="noopener noreferrer nofollow" style="color: #F56C6C">检测失败，提交收录</a>`);
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
						$('#Joe_Baidu_Record').html(`<a href="javascript:window.Joe.submit_baidu();" rel="noopener noreferrer nofollow" style="color: #F56C6C">${res.data}，提交收录</a>`);
						return
					}
					const url = `https://ziyuan.baidu.com/linksubmit/url?sitename=${encodeURI(window.location.href)}`;
					$('#Joe_Baidu_Record').html(`<a target="_blank" href="${url}" rel="noopener noreferrer nofollow" style="color: #F56C6C">${res.data}，提交收录</a>`);
				}
			});
		}
	}

	/* 激活浏览功能 */
	{
		let viewsArr = localStorage.getItem(encryption('views')) ? JSON.parse(decrypt(localStorage.getItem(encryption('views')))) : [];
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
					const name = encryption('views');
					const val = encryption(JSON.stringify(viewsArr));
					localStorage.setItem(name, val);
				}
			});
		}
	}

	/* 激活随机样式 */
	{
		leavingListInit();
	}

	/** 用户留言标签 */
	{
		$('.joe_detail__leaving-list>li>.user>.nickname>a').attr('target', '_blank');
	}

	/** pjax重新激活留言列表 */
	{
		document.addEventListener('pjax:success', (options) => {
			if (options.pjax != "comment-pagination") return;
			leavingListInit();
		});
	}
};

Joe.DOMContentLoaded.leaving();
console.log('调用：Joe.DOMContentLoaded.leaving');

/* 写在load事件里，为了解决图片未加载完成，滚动距离获取会不准确的问题 */
window.addEventListener('load', function () {
	/* 判断地址栏是否有锚点链接，有则跳转到对应位置 */
	{
		const scroll = new URLSearchParams(location.search).get('scroll');
		if (scroll) {
			const height = $('.joe_header').height();
			let elementEL = null;
			if ($('#' + scroll).length > 0) {
				elementEL = $('#' + scroll);
			} else {
				elementEL = $('.' + scroll);
			}
			if (elementEL && elementEL.length > 0) {
				const top = elementEL.offset().top - height - 15;
				window.scrollTo({ top, behavior: 'smooth' });
			}
		}
	}
});
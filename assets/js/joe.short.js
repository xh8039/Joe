/* 获取直属子元素 */
function getChildren(el, className) {
	for (let item of el.children) if (item.className === className) return item;
	return null;
}
Joe.DOMContentLoaded = Joe.DOMContentLoaded ? Joe.DOMContentLoaded : {};
Joe.DOMContentLoaded.short = Joe.DOMContentLoaded.short ? Joe.DOMContentLoaded.short : () => {
	console.log('调用：Joe.DOMContentLoaded.short');
	$('.joe_detail__article p:empty').remove();

	if (!customElements.get('joe-mtitle')) customElements.define('joe-mtitle', class JoeMtitle extends HTMLElement {
		constructor() {
			super();
			let title = this.getAttribute('title') || '默认标题';
			this.outerHTML = `<div class="joe_mtitle"><span class="joe_mtitle__text">${title}</span></div>`;
		}
	});

	if (!customElements.get('joe-mp3')) customElements.define('joe-mp3', class JoeMp3 extends HTMLElement {
		constructor() {
			super();
			this.options = {
				name: this.getAttribute('name').trim(),
				url: this.getAttribute('url').trim(),
				theme: (this.getAttribute('theme') || '#1989fa').trim(),
				cover: this.getAttribute('cover').trim(),
				autoplay: this.getAttribute('autoplay') ? true : false,
				loop: this.getAttribute('loop').trim(),
				artist: this.getAttribute('artist').trim(),
				lrc: this.getAttribute('lrc') ? this.getAttribute('lrc').trim() : '[00:00.000] 暂无歌词',
				autotheme: this.getAttribute('autotheme').trim(),
				lrcType: this.getAttribute('lrcType').trim()
			};
			this.render();
		}
		render() {
			if (!this.options.url) return (this.innerHTML = '音频地址未填写！');
			this.style.display = 'block';
			// this.innerHTML = '<span style="display: block" class="_content"></span>';
			new MusicPlayer({
				container: this,
				theme: this.options.theme,
				autoplay: this.options.autoplay,
				loop: this.options.loop,
				preload: 'auto',
				lrcType: this.options.lrcType,
				autotheme: this.options.autotheme,
				storage: this.options.url,
				audio: [
					{
						url: this.options.url,
						name: this.options.name,
						cover: this.options.cover,
						artist: this.options.artist,
						lrc: this.options.lrc
					}
				]
			});
		}
	});

	if (!customElements.get('joe-music')) customElements.define('joe-music', class JoeMusic extends HTMLElement {
		constructor() {
			super();
			this.options = {
				id: this.getAttribute('id'),
				color: this.getAttribute('color') || '#1989fa',
				autoplay: this.getAttribute('autoplay') ? true : false,
				autotheme: this.getAttribute('autotheme'),
				loop: this.getAttribute('loop')
			};
			this.style.display = 'block';
			this.render();
		}
		render() {
			if (!this.options.id) return (this.outerHTML = '网易云歌曲ID未填写！');
			// this.innerHTML = '<span style="display: block" class="_content"></span>';
			fetch(`${Joe.BASE_API}?routeType=meting&server=netease&type=song&id=${this.options.id}`).then(async response => {
				const audio = await response.json();
				new MusicPlayer({
					container: this,
					lrcType: 1,
					theme: this.options.color,
					autoplay: this.options.autoplay,
					autotheme: this.options.autotheme,
					storage: this.options.id,
					loop: this.options.loop,
					preload: 'auto',
					audio
				});
			});
		}
	});

	if (!customElements.get('joe-mlist')) customElements.define('joe-mlist', class JoeMlist extends HTMLElement {
		constructor() {
			super();
			this.options = {
				id: this.getAttribute('id'),
				color: this.getAttribute('color') || '#1989fa',
				autoplay: this.getAttribute('autoplay') ? true : false,
				autotheme: this.getAttribute('autotheme'),
				loop: this.getAttribute('loop'),
				order: this.getAttribute('order')
			};
			this.style.display = 'block';
			this.render();
		}
		render() {
			if (!this.options.id) return (this.outerHTML = '网易云歌单ID未填写！');
			// this.outerHTML = '<div class="_content"></div>';
			fetch(`${Joe.BASE_API}?routeType=meting&server=netease&type=playlist&id=${this.options.id}`).then(async response => {
				const audio = await response.json();
				new MusicPlayer({
					container: this,
					lrcType: 3,
					theme: this.options.color,
					autoplay: this.options.autoplay,
					autotheme: this.options.autotheme,
					storage: this.options.id,
					loop: this.options.loop,
					order: this.options.order,
					preload: 'auto',
					audio
				});
			});
		}
	});

	if (!customElements.get('joe-abtn')) customElements.define('joe-abtn', class JoeAbtn extends HTMLElement {
		constructor() {
			super();
			this.options = {
				icon: this.getAttribute('icon') || '',
				color: this.getAttribute('color') || 'var(--theme)',
				href: this.getAttribute('href') || 'javascript:;',
				target: this.getAttribute('target') || '_self',
				radius: this.getAttribute('radius') || 'var(--radius-wrap)',
				content: this.getAttribute('content') || '多彩按钮'
			};
			this.outerHTML = `
					<a class="joe_abtn" style="background: ${this.options.color}; border-radius: ${this.options.radius}" href="${this.options.href}" target="${this.options.target}" rel="noopener noreferrer nofollow">
						<span class="joe_abtn__icon"><i class="${this.options.icon} fa"></i></span>
						<span class="joe_abtn__content">${this.options.content}</span>
					</a>
				`;
		}
	});

	if (!customElements.get('joe-anote')) customElements.define('joe-anote', class JoeAnote extends HTMLElement {
		constructor() {
			super();
			this.options = {
				icon: this.getAttribute('icon') || 'fa-download',
				href: this.getAttribute('href') || 'javascript:;',
				target: this.getAttribute('target') || '_self',
				type: /^secondary$|^success$|^warning$|^error$|^info$/.test(this.getAttribute('type')) ? this.getAttribute('type') : 'secondary',
				content: this.getAttribute('content') || '标签按钮'
			};
			this.outerHTML = `
					<a class="joe_anote ${this.options.type}" href="${this.options.href}" target="${this.options.target}" rel="noopener noreferrer nofollow">
						<span class="joe_anote__icon"><i class="fa ${this.options.icon}"></i></span>
						<span class="joe_anote__content">${this.options.content}</span>
					</a>
				`;
		}
	});

	if (!customElements.get('joe-dotted')) customElements.define('joe-dotted', class JoeDotted extends HTMLElement {
		constructor() {
			super();
			this.startColor = this.getAttribute('startColor') || '#ff6c6c';
			this.endColor = this.getAttribute('endColor') || '#1989fa';
			// this.style.display = 'block';
			this.outerHTML = `
					<div class="joe_dotted" style="background-image: repeating-linear-gradient(-45deg, ${this.startColor} 0, ${this.startColor} 20%, transparent 0, transparent 25%, ${this.endColor} 0, ${this.endColor} 45%, transparent 0, transparent 50%)"></div>
				`;
		}
	});

	if (!customElements.get('joe-cloud')) customElements.define('joe-cloud', class JoeCloud extends HTMLElement {
		constructor() {
			super();
			this.options = {
				type: this.getAttribute('type') || 'default',
				title: this.getAttribute('title') || '默认标题',
				url: this.getAttribute('url'),
				password: this.getAttribute('password')
			};
			const type = {
				default: '默认云盘',
				360: '360网盘',
				baidu: '百度云盘',
				tianyi: '天翼云盘',
				chengtong: '城通网盘',
				weiyun: '腾讯微云',
				quark: '夸克云盘',
				github: 'Github仓库',
				gitee: 'Gitee仓库',
				lanzou: '蓝奏云网盘',
			};
			this.style.display = 'block';
			this.innerHTML = `
					<span class="joe_cloud">
						<div class="joe_cloud__logo _${this.options.type}"></div>
						<div class="joe_cloud__describe">
							<div class="joe_cloud__describe-title">${this.options.title}</div>
							<div class="joe_cloud__describe-type">来源：${type[this.options.type] || '默认网盘'}${this.options.password ? ' | 提取码：' + this.options.password : ''}</div>
						</div>
						<a class="joe_cloud__btn" href="${this.options.url}" target="_blank" rel="noopener noreferrer nofollow">
							<i class="fa fa-download"></i>
						</a>
					</span>
				`;
		}
	});

	if (!customElements.get('joe-hide')) customElements.define('joe-hide', class JoeHide extends HTMLElement {
		constructor() {
			super();
			this.render();
		}
		render() {
			if (window.Joe?.CONTENT?.fields?.hide == 'pay') {
				let price = window.Joe.CONTENT.fields.price;
				let pay = `<i mobile-bottom="true" data-height="300" data-remote="${window.Joe.BASE_API}?routeType=pay_cashier_modal&cid=${window.Joe.CONTENT.cid}" data-toggle="RefreshModal" class="joe_hide__button">付费 ${price} 元</i>`;
				this.outerHTML = `<span class="joe_hide joe_hide_${this.style.display}">此处内容作者设置了 ${price > 0 ? pay : '<a href="javascript:window.Joe.scrollTo(\'.joe_comment\');" class="joe_hide__button">评论</a>'} 可见</span>`;
			} else if (window.Joe?.CONTENT?.fields?.hide == 'login') {
				this.outerHTML = `<span class="joe_hide joe_hide_${this.style.display}">此处内容作者设置了 <i class="joe_hide__button">登录</i> 可见</span>`;
				this.$button = this.querySelector('.joe_hide__button');
				const $login = document.querySelector('.header-login');
				if (!$login) return;
				this.$button.addEventListener('click', () => {
					$login.click();
				});
			} else {
				this.outerHTML = `<span class="joe_hide joe_hide_${this.style.display}">此处内容作者设置了 <a href="javascript:window.Joe.scrollTo(\'.joe_comment\');" class="joe_hide__button">回复</a> 可见</span>`;
			}
		}
	});

	if (!customElements.get('joe-card-default')) customElements.define('joe-card-default', class JoeCardDefault extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			this.options = {
				width: this.getAttribute('width') || '100%',
				label: this.getAttribute('label') || '卡片标题',
				content: _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '') || '卡片内容'
			};
			const htmlStr = `
				<div class="joe_card__default" style="width: ${this.options.width}">
					<div class="joe_card__default-title">${this.options.label}</div>
					<div class="joe_card__default-content">${this.options.content}</div>
				</div>
			`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				this.outerHTML = htmlStr;
			}
		}
	});

	if (!customElements.get('joe-message')) customElements.define('joe-message', class JoeMessage extends HTMLElement {
		constructor() {
			super();
			this.options = {
				type: /^success$|^info$|^warning$|^error$/.test(this.getAttribute('type')) ? this.getAttribute('type') : 'info',
				content: this.getAttribute('content') || '消息内容'
			};
			this.outerHTML = `
					<div class="joe_message ${this.options.type}">
						<span class="joe_message__icon"></span>
						<span class="joe_message__content">${this.options.content}</span>
					</div>
				`;
		}
	});

	if (!customElements.get('joe-progress')) customElements.define('joe-progress', class JoeProgress extends HTMLElement {
		constructor() {
			super();
			this.style.display = 'block';
			this.options = {
				percentage: /^\d{1,3}%$/.test(this.getAttribute('percentage')) ? this.getAttribute('percentage') : '50%',
				color: this.getAttribute('color') || '#ff6c6c'
			};
			this.outerHTML = `
				<span class="joe_progress">
					<div class="joe_progress__strip">
						<div class="joe_progress__strip-percent" style="width: ${this.options.percentage}; background: ${this.options.color};"></div>
					</div>
					<div class="joe_progress__percentage">${this.options.percentage}</div>
				</span>
			`;
		}
	});

	if (!customElements.get('joe-callout')) customElements.define('joe-callout', class JoeCallout extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			this.options = {
				color: this.getAttribute('color') || '#f0ad4e',
				content: _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '') || '标注内容'
			};
			const htmlStr = `
					<div class="joe_callout" style="border-left-color: ${this.options.color};">
						${this.options.content}
					</div>
				`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				this.outerHTML = htmlStr;
				// const span = document.createElement('span');
				// span.style.display = 'block';
				// span.className = '_content';
				// span.innerHTML = htmlStr;
				// this.appendChild(span);
				// this.remove();
			}
		}
	});

	if (!customElements.get('joe-card-describe')) customElements.define('joe-card-describe', class JoeCardDescribe extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			this.options = {
				title: this.getAttribute('title') || '卡片描述',
				content: _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '') || '卡片内容'
			};
			const htmlStr = `
					<div class="joe_card__describe">
						<div class="joe_card__describe-title">${this.options.title}</div>
						<div class="joe_card__describe-content">${this.options.content}</div>
					</div>
				`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				const span = document.createElement('span');
				span.style.display = 'block';
				span.style.paddingTop = '8px';
				span.className = '_content';
				span.innerHTML = htmlStr;
				this.appendChild(span);
			}
		}
	});

	if (!customElements.get('joe-card-list')) customElements.define('joe-card-list', class JoeCardList extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			let _innerHTML = _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '');
			let content = '';
			_innerHTML.replace(/{card-list-item}([\s\S]*?){\/card-list-item}/g, function ($0, $1) {
				content += `<div class="joe_card__list-item">${$1.trim().replace(/^(<br>)|(<br>)$/g, '')}</div>`;
			});
			let htmlStr = `<div class="joe_card__list">${content}</div>`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				const div = document.createElement('div');
				div.className = '_content';
				div.innerHTML = htmlStr;
				this.outerHTML = div.outerHTML;
			}
		}
	});

	if (!customElements.get('joe-alert')) customElements.define('joe-alert', class JoeAlert extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			this.options = {
				type: /^success$|^info$|^warning$|^error$/.test(this.getAttribute('type')) ? this.getAttribute('type') : 'info',
				content: _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '') || '警告提示'
			};
			const htmlStr = `
					<div class="joe_alert ${this.options.type}">
						${this.options.content}
					</div>
				`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				const span = document.createElement('span');
				span.style.display = 'block';
				span.className = '_content';
				span.innerHTML = htmlStr;
				this.appendChild(span);
			}
		}
	});

	if (!customElements.get('joe-timeline')) customElements.define('joe-timeline', class JoeTimeline extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			let _innerHTML = _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '');
			let content = '';
			_innerHTML.replace(/{timeline-item([^}]*)}([\s\S]*?){\/timeline-item}/g, function ($0, $1, $2) {
				content += `
					<div class="joe_timeline__item">
						<div class="joe_timeline__item-tail"></div>
						<div class="joe_timeline__item-circle" ${$1}></div>
						<div class="joe_timeline__item-content">${$2.trim().replace(/^(<br>)|(<br>)$/g, '')}</div>
					</div>
				`;
			});
			let htmlStr = `<div class="joe_timeline">${content}</div>`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				const div = document.createElement('div');
				div.className = '_content';
				div.innerHTML = htmlStr;
				this.appendChild(div);
			}
			this.querySelectorAll('.joe_timeline__item-circle').forEach((item, index) => {
				const color = item.getAttribute('color') || '#19be6b';
				item.style.borderColor = color;
			});
			this.outerHTML = getChildren(this, '_content').outerHTML;
		}
	});

	if (!customElements.get('joe-collapse')) customElements.define('joe-collapse', class JoeCollapse extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			let _innerHTML = _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '');
			let content = '';
			_innerHTML.replace(/{collapse-item([^}]*)}([\s\S]*?){\/collapse-item}/g, function ($0, $1, $2) {
				content += `
					<div class="joe_collapse__item" ${$1}>
						<div class="joe_collapse__item-head">
							<div class="joe_collapse__item-head--label"></div>
							<svg class="joe_collapse__item-head--icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path d="M7.406 7.828L12 12.422l4.594-4.594L18 9.234l-6 6-6-6z"/></svg>
						</div>
						<div class="joe_collapse__item-wrapper">
							<div class="joe_collapse__item-wrapper--content">${$2.trim().replace(/^(<br>)|(<br>)$/g, '')}</div>
						</div>
					</div>
				`;
			});
			let htmlStr = `<div class="joe_collapse">${content}</div>`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				const span = document.createElement('span');
				span.className = '_content';
				span.style.display = 'block';
				span.innerHTML = htmlStr;
				this.appendChild(span);
			}
			this.querySelectorAll('.joe_collapse__item').forEach(item => {
				const label = item.getAttribute('label') || '折叠标题';
				const head = getChildren(item, 'joe_collapse__item-head');
				const headLabel = getChildren(head, 'joe_collapse__item-head--label');
				headLabel.innerHTML = label;
				const wrapper = getChildren(item, 'joe_collapse__item-wrapper');
				const content = getChildren(wrapper, 'joe_collapse__item-wrapper--content');
				const open = item.getAttribute('open');
				if (open !== null) {
					item.classList.add('active');
					wrapper.style.maxHeight = 'none';
				}
				head.addEventListener('click', () => {
					wrapper.style.maxHeight = content.offsetHeight + 'px';
					let timer = setTimeout(() => {
						if (item.classList.contains('active')) {
							item.classList.remove('active');
							wrapper.style.maxHeight = 0;
						} else {
							item.classList.add('active');
							wrapper.style.maxHeight = content.offsetHeight + 'px';
						}
						clearTimeout(timer);
					}, 30);
				});
			});
		}
	});

	if (!customElements.get('joe-dplayer')) customElements.define('joe-dplayer', class JoeDplayer extends HTMLElement {
		constructor() {
			super();
			this.options = {
				src: this.getAttribute('src'),
				pic: this.getAttribute('pic'),
				theme: this.getAttribute('theme') ? this.getAttribute('theme') : (getComputedStyle(document.getElementsByTagName('body')[0]).getPropertyValue('--theme') ? getComputedStyle(document.getElementsByTagName('body')[0]).getPropertyValue('--theme') : getComputedStyle(document.documentElement).getPropertyValue('--theme')).trim(),
				autoplay: this.getAttribute('autoplay') == '1' ? this.getAttribute('autoplay') : 0,
				loop: this.getAttribute('loop') == '1' ? this.getAttribute('autoplay') : 0,
				screenshot: this.getAttribute('screenshot') == '1' ? this.getAttribute('autoplay') : 0,
				player: this.getAttribute('player')
			};
			this.style.display = 'block';
			this.render();
		}
		render() {
			if (this.options.src) {
				if (this.options.player == 'false' || !this.options.player) {
					function isM3U8(url) {
						try {
							// 检查路径是否以 .m3u8 结尾
							return url.trim().toLowerCase().endsWith('.m3u8');;
						} catch (e) {
							// 如果创建 URL 失败，返回 false
							return false;
						}
					}
					const videoPlayer = () => {
						let init = () => {
							new DPlayer({
								container: this, // 播放器容器元素
								autoplay: this.options.autoplay, // 视频自动播放
								theme: this.options.theme, // 主题色
								lang: 'zh-cn', // 可选值: 'en', 'zh-cn', 'zh-tw'
								preload: 'metadata', // 视频预加载，可选值: 'none', 'metadata', 'auto'
								loop: this.options.loop, // 视频循环播放
								screenshot: this.options.screenshot, // 开启截图，如果开启，视频和视频封面需要允许跨域
								airplay: true, // 在 Safari 中开启 AirPlay
								volume: 1, // 默认音量，请注意播放器会记忆用户设置，用户手动设置音量后默认音量即失效
								playbackSpeed: [2.00, 1.75, 1.50, 1.25, 1.00, 0.75, 0.50, 0.25], // 可选的播放速率，可以设置成自定义的数组
								video: {
									pic: this.options.pic,
									url: this.options.src
								}
							});
						}
						if (!window.DPlayer) {
							$.getScript(window.Joe.CDN_URL + 'dplayer/1.27.0/DPlayer.min.js', init);
						} else {
							init();
						}
					}
					if (isM3U8(this.options.src)) {
						window.Joe.loadJS(window.Joe.CDN_URL + 'hls.js/1.5.13/hls.min.js', videoPlayer);
					} else {
						videoPlayer();
					}
				} else {
					if (this.options.pic == decodeURIComponent(this.options.pic)) {
						this.options.pic = encodeURIComponent(this.options.pic);
					}
					// 如果url等于已经解码的url，说明url未编码
					if (this.options.src == decodeURIComponent(this.options.src)) {
						this.options.src = encodeURIComponent(this.options.src); // 对url进行编码
					}
					let options = {
						pic: this.options.pic,
						theme: encodeURIComponent(this.options.theme),
						autoplay: this.options.autoplay,
						loop: this.options.loop,
						screenshot: this.options.screenshot
					}
					let fnParams2Url = function (obj) {
						let array_url = [];
						let fnAdd = function (key, value) {
							return key + '=' + value;
						}
						for (var k in obj) {
							array_url.push(fnAdd(k, obj[k]));
						}
						return (array_url.join('&'));
					}
					let url = this.options.player + this.options.src + '&' + fnParams2Url(options);
					this.innerHTML = `<iframe allowfullscreen="true" class="joe_vplayer" src="${url}"></iframe>`;
				}
			} else {
				this.innerHTML = '播放地址未填写！';
			}
		}
	});

	if (!customElements.get('joe-bilibili')) customElements.define('joe-bilibili', class JoeBilibili extends HTMLElement {
		constructor() {
			super();
			this.bvid = this.getAttribute('bvid');
			this.page = Object.is(Number(this.getAttribute('page')), NaN) ? 1 : this.getAttribute('page');
			this.style.display = 'block';
			this.render();
		}
		render() {
			if (this.bvid) this.innerHTML = `<iframe allowfullscreen="true" class="joe_vplayer" src="//player.bilibili.com/player.html?bvid=${this.bvid}&page=${this.page}"></iframe>`;
			else this.innerHTML = 'Bvid未填写！';
		}
	});

	if (!customElements.get('joe-tabs')) customElements.define('joe-tabs', class JoeTabs extends HTMLElement {
		constructor() {
			super();
			const _temp = getChildren(this, '_temp');
			let _innerHTML = _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '');
			let navs = '';
			let contents = '';
			_innerHTML.replace(/{tabs-pane([^}]*)}([\s\S]*?){\/tabs-pane}/g, function ($0, $1, $2) {
				navs += `<div class="joe_tabs__head-item" ${$1}></div>`;
				contents += `<div style="display: none" class="joe_tabs__body-item" ${$1}>${$2.trim().replace(/^(<br>)|(<br>)$/g, '')}</div>`;
			});
			let htmlStr = `
				<div class="joe_tabs">
					<div class="joe_tabs__head">${navs}</div>
					<div class="joe_tabs__body">${contents}</div>
				</div>
			`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				this.innerHTML = htmlStr;
				// const span = document.createElement('span');
				// span.className = '_content';
				// span.style.display = 'block';
				// span.innerHTML = htmlStr;
				// this.appendChild(span);
			}
			this.querySelectorAll('.joe_tabs__head-item').forEach((item, index) => {
				const label = item.getAttribute('label');
				item.innerHTML = label;
				item.addEventListener('click', () => {
					this.querySelectorAll('.joe_tabs__head-item').forEach(_item => _item.classList.remove('active'));
					this.querySelectorAll('.joe_tabs__body-item').forEach(_item => (_item.style.display = 'none'));
					if (this.querySelector(`.joe_tabs__body-item[label="${label}"]`)) {
						this.querySelector(`.joe_tabs__body-item[label="${label}"]`).style.display = 'block';
					}
					item.classList.add('active');
				});
				if (index === 0) item.click();
			});
		}
	});

	if (!customElements.get('joe-gird')) customElements.define('joe-gird', class JoeGird extends HTMLElement {
		constructor() {
			super();
			this.options = {
				column: isNaN(this.getAttribute('column')) || !this.getAttribute('column') ? 3 : this.getAttribute('column'),
				gap: isNaN(this.getAttribute('gap')) || !this.getAttribute('gap') ? 15 : this.getAttribute('gap')
			};
			const _temp = getChildren(this, '_temp');
			let _innerHTML = _temp.innerHTML.trim().replace(/^(<br>)|(<br>)$/g, '');
			let contents = '';
			_innerHTML.replace(/{gird-item}([\s\S]*?){\/gird-item}/g, function ($0, $1) {
				contents += `<div class="joe_gird__item">${$1.trim().replace(/^(<br>)|(<br>)$/g, '')}</div>`;
			});
			let htmlStr = `<div class="joe_gird" style="gap: ${this.options.gap}px; grid-template-columns: repeat(${this.options.column}, 1fr);">${contents}</div>`;
			if (getChildren(this, '_content')) {
				getChildren(this, '_content').innerHTML = htmlStr;
			} else {
				const span = document.createElement('span');
				span.className = '_content';
				span.style.display = 'block';
				span.innerHTML = htmlStr;
				this.appendChild(span);
			}
		}
	});

	if (!customElements.get('joe-copy')) customElements.define('joe-copy', class JoeCopy extends HTMLElement {
		constructor() {
			super();
			this.options = {
				showText: this.getAttribute('showText') || '点击复制',
				copyText: this.getAttribute('copyText') || '默认文本'
			};
			this.innerHTML = `<span class="joe_copy" style="cursor: pointer; user-select: none;">${this.options.showText}</span>`;
			const button = getChildren(this, 'joe_copy');
			button.addEventListener('click', () => {
				Joe.clipboard(this.options.copyText);
			});
		}
	});

	$('.joe_detail__article p:empty').remove();
}
document.addEventListener(Joe.options.Turbolinks == 'on' ? 'turbolinks:load' : 'DOMContentLoaded', Joe.DOMContentLoaded.short, { once: true });
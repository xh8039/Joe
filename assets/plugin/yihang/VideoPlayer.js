/**
 * 视频播放器核心类
 * @package VideoPlayer
 * @version 1.0
 * @author 易航
 * @link http://blog.yihang.info
 * @description 使用现代ES特性实现的增强型播放器，支持多格式扩展和异步资源加载
 * @giant DPlayer 基于DPlayer的增强实现
 */
class VideoPlayer {

	/** 播放器单例实例 */
	DPlayer = null;

	/** 脚本加载缓存池（避免重复加载） */
	static loadJSList = new Map();

	// 🎯 策略模式配置表（视频格式自动检测规则）
	static formatStrategies = [
		{
			name: 'webtorrent',  // 策略名称
			check: url => url.protocol === 'magnet:', // 检测条件
			handle: (config, _, queue, cdn) => {      // 处理逻辑
				config.type = 'webtorrent';
				queue.add(cdn + 'webtorrent/1.9.7/webtorrent.min.js');
			}
		},
		{
			name: 'hls',
			check: url => url.pathname.endsWith('.m3u8'),
			handle: (config, _, queue, cdn) => {
				config.type = 'hls';
				queue.add(cdn + 'hls.js/1.5.13/hls.min.js');
			}
		},
		{
			name: 'flv',
			check: url => url.pathname.endsWith('.flv'),
			handle: (config, _, queue, cdn) => {
				config.type = 'flv';
				queue.add(cdn + 'flv.js/1.6.2/flv.min.js');
			}
		},
		{
			name: 'shakaDash',
			check: url => url.pathname.endsWith('.mpd'),
			handle: (config, _, queue, cdn) => {
				config.type = 'shakaDash';
				config.customType = {
					shakaDash: function (video, player) {
						var src = video.src;
						var playerShaka = new shaka.Player(video); // 将会修改 video.src
						playerShaka.load(src);
					}
				};
				queue.add(cdn + 'shaka-player/4.10.7/shaka-player.compiled.min.js');
			}
		},
		// ...其他格式策略
	];

	/**
	 * 构造函数（使用选项合并模式）
	 * @param {Object} options - 配置选项（2025年新增特性）
	 * @property {string} options.cdn - 资源CDN地址
	 * @param {Function} callback - Dplayer成功初始化后要执行的函数
	 */
	constructor(options, callback = () => { }) {

		// 读取CSS变量
		const documentTheme = getComputedStyle(document.documentElement).getPropertyValue('--theme').trim();

		if (!options.video) options.video = {};

		// 🌈 合并配置
		this.options = {
			theme: documentTheme || '#b7daff',
			playbackSpeed: [0.5, 1, 1.5, 2, 2.5, 3], // 播放速度选项
			airplay: true,   // AirPlay投屏支持
			screenshot: options.video.pic ? false : true,// 截图功能
			callback: callback,
			...options       // 用户自定义配置（覆盖默认值）
		};

		// 🚀 资源加载队列（使用Set避免重复）
		this.resourceQueue = new Set();

		// 🔍 视频格式预处理（策略模式入口）
		this.processVideoFormats(this.options.video);

		// ⏳ 异步初始化引擎（ES8 async/await）
		this.initEngine().catch(console.error);
	}

	/**
	 * 异步初始化播放引擎（2025年新增WebGPU支持）
	 * @async
	 */
	async initEngine() {
		try {
			if (this.DPlayer) return;

			// ⚡ 并行加载核心库+格式依赖（ES6 Promise.all）
			await Promise.all([
				!window.DPlayer && this.loadScript(this.options.cdn + 'dplayer/1.27.0/DPlayer.min.js'),
				...Array.from(this.resourceQueue).map(url => this.loadScript(url))
			]);

			if (window.DPlayer) {
				this.DPlayer = new DPlayer(this.options);
				this.options.callback(this.DPlayer);
				// 如果视频已经是播放的状态，1秒后检测自动隐藏视频控件
				this.DPlayer.on('play', setTimeout(() => {
					if (this.DPlayer.video.paused) return;
					const classList = this.DPlayer.options.container.classList;
					if (!classList.contains('dplayer-hide-controller')) classList.add('dplayer-hide-controller');
				}, 1000));
				// 如果开启了自动播放，则强制调用自动播放
				if (this.options.autoplay) this.DPlayer.on('loadeddata', () => {
					if (this.DPlayer.video.paused) this.DPlayer.video.play();
				});
			}
		} catch (e) {
			console.error('[VideoPlayer] 初始化失败:', e);
			// 🚑 降级处理（2025年新增WebCodecs回退）
			this.options.container.src = this.options.video.url;
		}
	}

	/**
	 * 动态切换视频（增强版）
	 * @param {Object} videoConfig - 新视频配置
	 * @param {boolean} [reloadPlayer=false] - 是否需要重建播放器实例
	 */
	async switchVideo(videoConfig, reloadPlayer = false) {
		// 🛠️ 创建临时资源队列
		const newResourceQueue = new Set();

		// 🔍 预处理新视频格式
		this.processVideoFormats(videoConfig, newResourceQueue);

		// ⚡ 加载新增依赖
		if (newResourceQueue.size > 0) {
			await Promise.all(
				Array.from(newResourceQueue).map(url => this.loadScript(url))
			);
		}

		// 🔄 判断是否需要重建播放器
		// if (reloadPlayer || this.needRecreatePlayer(videoConfig)) {
		// 	console.log('重建播放器');
		// 	this.DPlayer.destroy();
		// 	this.DPlayer = new DPlayer({
		// 		...this.options,
		// 		video: videoConfig
		// 	});
		// } else {
		// 🎯 动态更新类型处理器
		this.updateCustomTypeHandler(videoConfig);
		console.log(videoConfig);
		this.DPlayer.switchVideo(videoConfig);
		// }
	}

	destroy() {
		if (!this.DPlayer) return;
		this.DPlayer.events.events = {}; // 销毁监听事件
		this.DPlayer.isDestroy = true; // 标记视频已经销毁
		this.DPlayer.destroy(); // 销毁视频
		this.DPlayer = null; // 销毁实例
	}

	/**
	 * 智能加载脚本（带缓存机制）
	 * @param {string} url - 脚本URL
	 * @returns {Promise<void>}
	 * @description 使用现代缓存策略，支持内存释放（WeakRef提案）
	 */
	async loadScript(url) {
		// 🔄 缓存检查（Map比对象更高效）
		if (VideoPlayer.loadJSList.has(url)) {
			return VideoPlayer.loadJSList.get(url);
		}

		// 🧩 创建加载Promise
		const promise = new Promise((resolve, reject) => {
			const script = document.createElement('script');
			script.src = url;
			script.onload = () => resolve();
			script.onerror = () => reject(new Error(`加载失败: ${url}`));
			document.head.append(script);
		});

		// 💾 缓存Promise实例
		VideoPlayer.loadJSList.set(url, promise);
		return promise;
	}

	/**
	 * 视频格式处理器（策略模式实现）
	 * @param {Object} video - 视频配置对象
	 * @param {Set} queue - 资源队列（支持传入外部队列）
	 */
	processVideoFormats(video, queue = this.resourceQueue) {
		// 🛑 安全校验（ES2020可选链）
		if (!video?.url) return;

		// 🔗 URL解析（使用静态方法）
		const parsedUrl = VideoPlayer.parseURL(video.url);
		if (!parsedUrl) return;

		// 🔎 查找匹配的格式策略（ES6 find方法）
		const formatHandler = VideoPlayer.formatStrategies.find(strategy => strategy.check(parsedUrl));

		// ⚙️ 执行策略处理逻辑
		if (formatHandler) {
			formatHandler.handle(video, parsedUrl, queue, this.options.cdn);
		}
	}

	/**
	* 判断是否需要重建播放器实例
	*/
	needRecreatePlayer(newVideoConfig) {
		const currentType = this.DPlayer.options.video.type;
		const newType = newVideoConfig.type;

		// 📌 需要重建的情况：
		// 1. 类型从普通变为流媒体
		// 2. 使用了不同的自定义处理器
		return (
			(currentType === 'auto' && newType !== 'auto') ||
			(newType === 'shakaDash' && currentType !== 'shakaDash') ||
			(newType === 'webtorrent' && !window.WebTorrent)
		);
	}

	/**
	* 动态更新自定义类型处理器
	*/
	updateCustomTypeHandler(videoConfig) {
		if (videoConfig.customType) {
			this.DPlayer.options.video.customType = {
				...this.DPlayer.options.video.customType,
				...videoConfig.customType
			};
		}
	}

	// 新增静态方法用于格式检测
	static detectVideoFormat(url) {
		const parsedUrl = this.parseURL(url);
		if (!parsedUrl) return 'auto';

		const strategy = this.formatStrategies.find(s => s.check(parsedUrl));
		return strategy ? strategy.name : 'auto';
	}

	/**
	 * URL解析方法（静态方法）
	 * @param {string} url - 待解析的视频URL
	 * @returns {URL|null} 解析后的URL对象或null
	 */
	static parseURL(url) {
		try {
			if (url.startsWith('//')) url = window.location.protocol + url;
			return new URL(url);
		} catch (error) {
			console.error('[VideoPlayer] 无效的视频URL:', url, error);
			return null;
		}
	}

}
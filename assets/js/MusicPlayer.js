/**
 * @package MusicPlayer
 * @author 易航
 * @link http://blog.bri6.cn
 * @giant APlayer
*/
class MusicPlayer {

	constructor(options) {
		/* 播放器 */
		this.PLAYER = null;

		/* 播放器配置 */
		this.OPTIONS = options;

		return this.init();
	}

	/**
	 * 初始化播放器
	 */
	init() {
		// 优化全部音乐信息
		this.setMusic();

		// 创建音乐播放器
		this.createPlayer();

		// 监听播放器事件
		this.listen();

		return this.PLAYER;
	}

	/**
	 * 监听播放器事件
	 */
	listen() {
		// 监听音乐加载完毕
		this.PLAYER.on('loadeddata', () => {
			// 自动主题色
			this.OPTIONS['autotheme'] ? this.autoTheme(this.PLAYER.list.index) : null;
		});

		// 监听音乐加载失败
		this.PLAYER.on('error', () => {
			this.PLAYER.skipForward(); //切换到下一首音频
		});
	}

	/**
	 * 创建播放器
	 */
	createPlayer() {
		this.PLAYER = new APlayer(this.OPTIONS);
		this.PLAYER.play();
	}

	/**
	 * 优化音乐信息
	 * @return object
	 */
	setMusic() {
		let music = this.OPTIONS.audio;
		for (let key in music) {
			// 音频名称
			if (!music[key]['name']) {
				music[key]['name'] = music[key]['title'] ? music[key]['title'] : '歌曲'.key;
			}
			// 音频作者
			if (!music[key]['artist']) {
				music[key]['artist'] = music[key]['author'] ? music[key]['author'] : '无信息';
			}
			// 音频类型
			if (!music[key]['type']) {
				music[key]['type'] = 'auto';
			}
			// 音频封面
			if (!music[key]['cover']) {
				music[key]['cover'] = music[key]['pic'] ? music[key]['pic'] : 'http://cdn.bri6.cn/images/202208032036881.jpg';
			}
			// 音频歌词
			if (!music[key]['lrc']) {
				music[key]['lrc'] = '[00:00.000] 暂无歌词';
			}
		}
		this.OPTIONS.audio = music;
		return music;
	}

	/**
	 * 音乐播放器自动主题色
	 */
	autoTheme(index) {
		if (this.PLAYER.list.audios[index]) {
			if (!this.PLAYER.list.audios[index].theme) {
				let xhr = new XMLHttpRequest();
				xhr.open('GET', this.PLAYER.list.audios[index].cover, true);
				xhr.responseType = 'blob';
				xhr.send();
				xhr.onload = () => {
					var coverUrl = URL.createObjectURL(xhr.response);
					let image = new Image();
					image.onload = () => {
						let colorThief = new ColorThief();
						let color = colorThief.getColor(image);
						this.PLAYER.theme(`rgb(${color[0]}, ${color[1]}, ${color[2]})`, index);
						URL.revokeObjectURL(coverUrl)
					};
					image.src = coverUrl;
				}
			}
		}
	}

}
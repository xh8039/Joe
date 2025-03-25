/**
 * @package MusicPlayer
 * @version 1.2
 * @author 易航
 * @link http://blog.yihang.info
 * @giant APlayer
*/
class MusicPlayer {

	/* 播放器配置 */
	OPTIONS = {};

	/* 播放器 */
	APlayer = null;

	constructor(options) {

		this.OPTIONS = options;

		this.OPTIONS.userHasInteracted = false;

		// 优化全部音乐信息
		this.setMusic()

		// 创建音乐播放器
		this.createPlayer()

		// 监听播放器事件
		this.listen()

		// 换回之前播放的音乐
		this.initStorageMusic()

		return this;
	}

	/**
	 * 优化音乐信息
	 * @return object
	 */
	setMusic() {
		for (let key in this.OPTIONS.audio) {
			// 音频名称
			if (!this.OPTIONS.audio[key]['name']) this.OPTIONS.audio[key]['name'] = this.OPTIONS.audio[key]['title'] ? this.OPTIONS.audio[key]['title'] : '歌曲' + key
			// 音频作者
			if (!this.OPTIONS.audio[key]['artist']) this.OPTIONS.audio[key]['artist'] = this.OPTIONS.audio[key]['author'] ? this.OPTIONS.audio[key]['author'] : '无信息'
			// 音频歌词
			if (!this.OPTIONS.audio[key]['lrc']) this.OPTIONS.audio[key]['lrc'] = '[00:00.000] 暂无歌词'
			// 音频封面
			if (!this.OPTIONS.audio[key]['cover']) {
				this.OPTIONS.audio[key]['cover'] = this.OPTIONS.audio[key]['pic'] ? this.OPTIONS.audio[key]['pic'] : 'data:image/jpeg;base64,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'
			}
		}
		if (/windows phone|iphone|android/gi.test(window.navigator.userAgent) && this.OPTIONS.audio.length > 1 && this.OPTIONS.fixed == true) {
			let reserve = {
				name: '占位音频',
				artist: '解决 APlayer 缺少最后两个音频的BUG',
				url: '',
				cover: '',
				lrc: '[00:00.000] 暂无歌词',
			}
			this.OPTIONS.audio.push(reserve, reserve);
		}
		return this.OPTIONS.audio
	}

	/**
	 * 创建播放器
	 */
	createPlayer() {
		this.APlayer = new APlayer(this.OPTIONS)
	}

	/**
	 * 监听播放器事件
	 */
	listen() {
		if (this.OPTIONS.storage) {
			this.storageInterval = setInterval(() => this.storageMusic(), 1000);
			document.body.addEventListener('click', (event) => {
				if (event.target.tagName === 'A') this.storageMusic();
			}, { once: true });
			window.addEventListener('beforeunload', () => this.storageMusic(), { once: true });
		}
		if (this.OPTIONS.autoplay) {
			['click', 'keydown', 'mousedown'].forEach(event => {
				document.addEventListener(event, () => this.handleInteraction(event), { once: true });
			});
		}
		this.APlayer.on('loadeddata', () => {
			this.OPTIONS['autotheme'] ? this.autoTheme(this.APlayer.list.index) : null;
			if ('mediaSession' in navigator) {
				var music = this.APlayer.list.audios[this.APlayer.list.index];
				navigator.mediaSession.metadata = new MediaMetadata({
					title: music.name,
					artist: music.artist,
					artwork: [{ src: music.cover }]
				});
			}
		})
		this.APlayer.on('error', this.throttle(() => {
			console.log('监听：error');
			console.log(this.APlayer.list.audios[this.APlayer.list.index]);
			this.APlayer.skipForward()
			this.APlayer.seek(0)
		}, 5000));
	}

	/**
	 * 初始化存储音乐信息
	 */
	initStorageMusic() {
		if (!this.OPTIONS.storage) {
			return
		}
		let storage_music = this.getMusic(this.OPTIONS.storage);
		if (!storage_music) return
		let music = this.APlayer.list.audios[storage_music.index]
		if (!music) return
		if (
			(this.APlayer.list.audios.length != 1) &&
			(music['url'] != storage_music['url']) &&
			(music['artist'] != storage_music['artist'] && music['name'] != storage_music['name'])
		) {
			return
		}
		this.APlayer.list.switch(storage_music.index);
		var seek_loadedmetadata = true;
		this.APlayer.on('loadedmetadata', () => {
			if (!seek_loadedmetadata) return
			this.APlayer.seek(storage_music.time);
			var time = this.APlayer.audio.currentTime;
			if (time <= 0) {
				// alert('垃圾浏览器，音频的loadedmetadata事件都监控不准');
				setTimeout(() => this.APlayer.seek(storage_music.time), 50);
			}
			seek_loadedmetadata = false;
		})
	}

	handleInteraction(event) {
		if (this.OPTIONS.userHasInteracted) return;
		this.OPTIONS.userHasInteracted = true;
		if (this.APlayer.audio.paused) setTimeout(() => {
			this.APlayer.notice('自动播放音乐');
			this.APlayer.play();
		}, 100);
	}

	/**
	 * 获取存储的音乐信息
	 * @return object|null
	 */
	getMusic(storage = null) {
		if (!localStorage.getItem('music_player')) return null
		let data = JSON.parse(localStorage.getItem('music_player'));
		if (storage) return data[storage] ? data[storage] : null
		return data
	}

	/**
	 * 存储音乐
	 */
	storageMusic() {
		if (!this.OPTIONS.storage) return;
		if (!this.APlayer) return;
		let music = this.APlayer.list.audios[this.APlayer.list.index];
		music.time = this.APlayer.audio.currentTime;
		music.index = this.APlayer.list.index;
		let storage = this.getMusic();
		let data = (storage ? storage : new Object);
		data[this.OPTIONS.storage] = music;
		localStorage.setItem('music_player', JSON.stringify(data))
	}

	/**
	 * 音乐播放器自动主题色
	 */
	autoTheme(index) {
		if (!window.ColorThief) return;
		if (!this.APlayer.list.audios[index]) return;
		if (this.APlayer.list.audios[index].theme) return;
		let xhr = new XMLHttpRequest()
		xhr.open('GET', this.APlayer.list.audios[index].cover, true)
		xhr.responseType = 'blob'
		xhr.send()
		xhr.onload = () => {
			var coverUrl = URL.createObjectURL(xhr.response)
			let image = new Image()
			image.onload = () => {
				let colorThief = new ColorThief()
				let color = colorThief.getColor(image)
				this.APlayer.theme(`rgb(${color[0]}, ${color[1]}, ${color[2]})`, index)
				URL.revokeObjectURL(coverUrl)
			}
			image.src = coverUrl
		}
	}

	destroy() {
		if (this.OPTIONS.storage) this.storageMusic();
		if (this.storageInterval) clearInterval(this.storageInterval);
		this.APlayer.events.events = {};
		this.APlayer.isDestroy = true;
		this.APlayer.pause();
		this.APlayer.container.src = 'about:blank';
		this.APlayer.destroy();
		this.APlayer = null;
		this.OPTIONS = null;
	}

	/**
	 * 函数节流，时间戳方案
	 * @param {*} fn 
	 * @param {*} wait 
	 * @returns 
	 */
	throttle(fn, wait) {
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
}
/**
 * @package MusicPlayer
 * @version 1.1
 * @author 易航
 * @link http://blog.bri6.cn
 * @giant APlayer
*/
class MusicPlayer {

	constructor(options) {

		/* 播放器 */
		this.PLAYER = null

		/* 播放器配置 */
		this.OPTIONS = options

		return this.init()
	}

	/**
	 * 初始化播放器
	 */
	init() {
		// 优化全部音乐信息
		this.setMusic()

		// 创建音乐播放器
		this.createPlayer()

		// 监听播放器事件
		this.listen()

		// 换回之前播放的音乐
		this.initStorageMusic()

		return this.PLAYER
	}

	/**
	 * 监听播放器事件
	 */
	listen() {
		if (this.OPTIONS.storage) {
			setInterval(() => this.storageMusic(), 1000);
			document.body.addEventListener('click', (event) => {
				if (event.target.tagName === 'A') this.storageMusic();
			});
			window.addEventListener('beforeunload', () => this.storageMusic());
		}
		this.PLAYER.on('loadeddata', () => {
			this.OPTIONS['autotheme'] ? this.autoTheme(this.PLAYER.list.index) : null;
			if ('mediaSession' in navigator) {
				var music = this.PLAYER.list.audios[this.PLAYER.list.index];
				navigator.mediaSession.metadata = new MediaMetadata({
					title: music.name,
					artist: music.artist,
					artwork: [{ src: music.cover }]
				});
			}
		})
		this.PLAYER.on('error', this.throttle(() => {
			console.log('监听：error');
			console.log(this.PLAYER.list.audios[this.PLAYER.list.index]);
			this.PLAYER.skipForward()
			this.PLAYER.seek(0)
		}, 5000));
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

	/**
	 * 创建播放器
	 */
	createPlayer() {
		this.PLAYER = new APlayer(this.OPTIONS)
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
		let music = this.PLAYER.list.audios[storage_music.index]
		if (!music) return
		if (
			(this.PLAYER.list.audios.length != 1) &&
			(music['url'] != storage_music['url']) &&
			(music['artist'] != storage_music['artist'] && music['name'] != storage_music['name'])
		) {
			return
		}
		this.PLAYER.list.switch(storage_music.index);
		var seek_loadedmetadata = true;
		this.PLAYER.on('loadedmetadata', () => {
			if (!seek_loadedmetadata) return
			this.PLAYER.seek(storage_music.time);
			var time = this.PLAYER.audio.currentTime;
			if (time <= 0) {
				// alert('垃圾浏览器，音频的loadedmetadata事件都监控不准');
				setTimeout(() => this.PLAYER.seek(storage_music.time), 50);
			}
			seek_loadedmetadata = false;
		})
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
		let music = this.PLAYER.list.audios[this.PLAYER.list.index];
		music.time = this.PLAYER.audio.currentTime;
		music.index = this.PLAYER.list.index;
		let storage = this.getMusic();
		let data = (storage ? storage : new Object);
		data[this.OPTIONS.storage] = music;
		localStorage.setItem('music_player', JSON.stringify(data))
	}

	/**
	 * 音乐播放器自动主题色
	 */
	autoTheme(index) {
		if (!this.PLAYER.list.audios[index]) return;
		if (this.PLAYER.list.audios[index].theme) return;
		let xhr = new XMLHttpRequest()
		xhr.open('GET', this.PLAYER.list.audios[index].cover, true)
		xhr.responseType = 'blob'
		xhr.send()
		xhr.onload = () => {
			var coverUrl = URL.createObjectURL(xhr.response)
			let image = new Image()
			image.onload = () => {
				let colorThief = new ColorThief()
				let color = colorThief.getColor(image)
				this.PLAYER.theme(`rgb(${color[0]}, ${color[1]}, ${color[2]})`, index)
				URL.revokeObjectURL(coverUrl)
			}
			image.src = coverUrl
		}
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
				this.OPTIONS.audio[key]['cover'] = this.OPTIONS.audio[key]['pic'] ? this.OPTIONS.audio[key]['pic'] : 'https://shp.qpic.cn/collector/2136118039/6980eb0f-bff7-4b15-86a1-ef9f794358da/0'
			}
		}
		let reserve = {
			name: '占位音频',
			artist: '解决 APlayer 缺少最后两个音频的BUG',
			url: '',
			cover: '',
			lrc: '[00:00.000] 暂无歌词',
		}
		this.OPTIONS.audio.push(reserve, reserve);
		return this.OPTIONS.audio
	}
}
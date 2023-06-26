/**
 * @package MusicPlayer
 * @version 1.0
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
			setInterval(() => {
				this.storageMusic()
			}, 1000);
			document.getElementsByTagName('a').onclick = () => {
				this.storageMusic()
			}
			window.onbeforeunload = () => {
				this.storageMusic()
			}
		}
		this.PLAYER.on('loadeddata', () => {
			this.OPTIONS['autotheme'] ? this.autoTheme(this.PLAYER.list.index) : null
		})
		this.PLAYER.on('error', () => {
			this.PLAYER.skipForward()
			this.PLAYER.seek(0)
		})
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
		if (!storage_music) {
			return
		}
		let music = this.PLAYER.list.audios[storage_music.index]
		if (!music) {
			return
		}
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
			if (!seek_loadedmetadata) {
				return
			}
			this.PLAYER.seek(storage_music.time);
			var time = this.PLAYER.audio.currentTime;
			if (time <= 0) {
				// alert('垃圾浏览器，音频的loadedmetadata事件都监控不准');
				setTimeout(() => {
					this.PLAYER.seek(storage_music.time);
				}, 50);
			}
			seek_loadedmetadata = false;
		})
	}

	/**
	 * 获取存储的音乐信息
	 * @return object|null
	 */
	getMusic(storage = null) {
		if (localStorage.getItem('music_player')) {
			var data = JSON.parse(localStorage.getItem('music_player'));
			if (storage) {
				return data[storage] ? data[storage] : null
			}
			return data
		}
		return null
	}

	/**
	 * 存储音乐
	 */
	storageMusic() {
		if (!this.OPTIONS.storage) {
			return;
		}
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
		if (this.PLAYER.list.audios[index]) {
			if (!this.PLAYER.list.audios[index].theme) {
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
		}
	}

	/**
	 * 优化音乐信息
	 * @return object
	 */
	setMusic() {
		let music = this.OPTIONS.audio
		for (let key in music) {
			// 音频名称
			if (!music[key]['name']) {
				music[key]['name'] = music[key]['title'] ? music[key]['title'] : '歌曲'.key
			}
			// 音频作者
			if (!music[key]['artist']) {
				music[key]['artist'] = music[key]['author'] ? music[key]['author'] : '无信息'
			}
			// 音频歌词
			if (!music[key]['lrc']) {
				music[key]['lrc'] = '[00:00.000] 暂无歌词'
			}
			// 音频封面
			if (!music[key]['cover']) {
				music[key]['cover'] = music[key]['pic'] ? music[key]['pic'] : 'data:image/png;base64,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'
			}
		}
		this.OPTIONS.audio = music
		return music
	}
}
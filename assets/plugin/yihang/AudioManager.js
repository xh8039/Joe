class AudioManager {
	constructor(options = {}) {
		this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
		this.cache = new Map();
		this.base = options.base || window.location.href;
	}

	// 简化的预加载方法
	async preload(url) {
		if (!/^http[s]?\:\/\//.test(url)) url = this.base + url;
		if (this.cache.has(url)) return;

		try {
			const response = await fetch(url);
			const arrayBuffer = await response.arrayBuffer();
			const decoded = await this.audioContext.decodeAudioData(arrayBuffer);
			this.cache.set(url, decoded);
			// console.log(`[音频预加载完成] ${url}`);
		} catch (error) {
			console.error(`音频预加载失败: ${url}`, error);
		}
	}

	// 可靠的播放方法
	play(url, { volume = 1, loop = false } = {}) {
		if (!/^http[s]?\:\/\//.test(url)) url = this.base + url;
		if (!this.cache.has(url)) {
			console.warn('音频未预加载，启用紧急加载');
			return this._emergencyPlay(url);
		}

		const source = this.audioContext.createBufferSource();
		source.buffer = this.cache.get(url);
		source.loop = loop;

		const gainNode = this.audioContext.createGain();
		gainNode.gain.value = volume;

		source.connect(gainNode);
		gainNode.connect(this.audioContext.destination);

		source.start(0);
		// console.log(`[立即播放] ${url}`);

		return {
			stop: () => source.stop(),
			setVolume: (val) => gainNode.gain.setValueAtTime(val, this.audioContext.currentTime)
		};
	}

	// 降级方案保持不变
	async _emergencyPlay(url) {
		const audio = new Audio(url);
		audio.play().catch(err => console.error('紧急播放失败:', err));
		return audio;
	}
}
/**
 * @package 易航Feedback
 * @version 1.0
 * @author 易航
 * @link http://blog.bri6.cn
*/
;
window.YiHang = window.YiHang || {};

window.YiHang.Feedback = class {

	static list = []

	static defaultOptions = {
		title: '提示',
		content: '',
		background: '#ffffff',
		themeColor: '#409eff',
		maskClose: true,
		buttonColor: '#ffffff',
	}

	constructor(options = null) {
		if (options) return this.alert(options);
	}

	static options(options = {}) {
		this.defaultOptions = Object.assign({}, this.defaultOptions, options);
		return this;
	}

	static createElement(tag, className, innerHTML = '', styles = {}, attributes = {}) {
		const element = document.createElement(tag);
		if (className) element.className = className;
		if (innerHTML) element.innerHTML = innerHTML;
		Object.assign(element.style, styles);
		for (const key in attributes) {
			element.setAttribute(key, attributes[key]);
		}
		return element;
	}

	static createButtonGroup(buttons = [], close = []) {
		const buttonContainer = this.createElement('div', 'YiHangFeedbackAlertButtons');
		for (const index in buttons) {
			let button = buttons[index];
			const buttonElement = this.createElement('button', 'YiHangFeedbackAlertButton', button?.text || (Number.isInteger(index) ? '按钮' + index : index), {}, button.attributes || {});
			buttonElement.style.background = button.background || this.defaultOptions.themeColor;
			buttonElement.style.color = button.color || this.defaultOptions.buttonColor;
			buttonElement.classList.add(...(button.classList || []));
			buttonElement.onclick = () => {
				if (button.callback) button.callback();
				close.forEach(element => {
					this.close(element);
				});
			};
			buttonContainer.appendChild(buttonElement);
		}
		return buttonContainer;
	}

	static loading(options = { background: '#fff', color: null }) {
		const load = this.createElement('div', 'YiHangFeedbackLoading', `<svg viewBox="0 0 50 50"><circle style="stroke:${options.color || this.defaultOptions.themeColor}" cx="25" cy="25" r="20" fill="none"></circle></svg>`, {}, options?.attributes || {});
		const mask = this.createElement('div', 'YiHangFeedbackMask', '', { background: options.background });
		document.body.append(load, mask);
		this.list.push(load, mask);

		setTimeout(() => {
			load.classList.add('YiHangFeedbackLoadingShow');
			mask.classList.add('YiHangFeedbackMaskShow');
		}, 10);

		return this;
	}

	static alert(options = {}) {
		const mergedOptions = { ...this.defaultOptions, ...options };
		const alert = this.createElement('div', 'YiHangFeedbackAlert', '', { background: mergedOptions.background });
		const mask = this.createElement('div', 'YiHangFeedbackMask');

		const alertTitle = this.createElement('div', 'YiHangFeedbackAlertTitle', mergedOptions.title);
		alert.appendChild(alertTitle);

		const alertContent = this.createElement('div', 'YiHangFeedbackAlertContent', mergedOptions.content);
		alert.appendChild(alertContent);

		const alertButtons = this.createButtonGroup(mergedOptions.buttons, [alert, mask]);
		alert.appendChild(alertButtons);

		if (mergedOptions.maskClose) mask.onclick = () => {
			this.close(alert);
			this.close(mask);
		};

		document.body.append(alert, mask);
		this.list.push(alert, mask);

		setTimeout(() => {
			alert.classList.add('YiHangFeedbackAlertShow');
			mask.classList.add('YiHangFeedbackMaskShow');
		}, 10);

		return this;
	}

	static close(element) {
		if (!element) return false;
		element?.classList.remove('YiHangFeedbackAlertShow', 'YiHangFeedbackLoadingShow', 'YiHangFeedbackMaskShow');
		setTimeout(() => {
			element.remove();
			this.list = this.list.filter(item => item !== element);
		}, 150);
		return element;
	}

	static closeAll() {
		for (const key in this.list) {
			let Feedback = this.list[key];
			this.close(Feedback);
		}
	}
};
class TurboLinks {

	"use strict";

	pjax;

	loadJSList = [];

	documentScriptList = [];

	constructor(url, options) {
		var pjax = new Pjax(options);
		pjax._handleResponse = pjax.handleResponse;
		pjax.handleResponse = (responseText, request, href, options) => {

			const responseDocument = (new DOMParser()).parseFromString(responseText, 'text/html');

			const loadStyleList = responseDocument.head.querySelectorAll('style');
			loadStyleList.forEach(this.loadStyle);

			this.loadJSList = responseDocument.head.querySelectorAll('script');
			if (this.loadJSList.length < 1) return pjax._handleResponse(responseText, request, href, options);
			document.querySelectorAll('script[src]').forEach(element => {
				this.documentScriptList.push(element.src);
			});
			this.loadJSList.forEach(this.replaceJs);

		}
		pjax.loadUrl(url);
		this.pjax = pjax;
	}

	JsLoaded(element, index) {
		this.documentScriptList.push(element.src);
		if (index == (this.loadJSList.length - 1)) {
			console.log('所有JavaScript文件都已加载！');
			this.pjax._handleResponse(responseText, request, href, options);
		}
	}

	replaceJs(element, index) {
		let code = element.text || element.textContent || element.innerHTML || "";
		let script = document.createElement("script");

		if (code.match("document.write")) {
			if (console && console.log) console.log("脚本包含document.write。无法正确执行。代码已跳过", element);
			return false;
		}

		script.type = "text/javascript";
		if (element.id) script.id = element.id;

		if (element.src) {
			if ($(element).attr('data-turbolinks-permanent') != undefined) {
				if (this.documentScriptList.includes(element.src)) {
					JsLoaded(element, index);
					return true;
				}
			}
			script.src = element.src;
			script.async = false;
			script.addEventListener('load', () => {
				console.log('引入JS：' + element.src);
				JsLoaded(element, index);
			});
			script.addEventListener('error', () => {
				console.error('Error loading script:', element.src);
				JsLoaded(element, index);
			});
			// 强制同步加载外部JS
		}

		if (code !== "") {
			try {
				script.appendChild(document.createTextNode(code));
			} catch (e) {
				/* istanbul ignore next */
				// old IEs have funky script nodes
				script.text = code;
			}
		}

		let parent = document.querySelector("head") || document.documentElement;
		parent.appendChild(script);
		// 仅避免头部或身体标签污染
		if ((parent instanceof HTMLHeadElement || parent instanceof HTMLBodyElement) && parent.contains(script)) {
			parent.removeChild(script);
		}

		return true;
	}

	loadStyle(element, index) {
		let code = element.text || element.textContent || element.innerHTML || null;
		if (code) {
			console.log('引入CSS：'.code);
			let style = document.createElement('style');
			style.type = 'text/css';
			try {
				style.appendChild(document.createTextNode(code));
			} catch (ex) {
				style.styleSheet.cssText = code; // 兼容IE
			}
			document.head.appendChild(style);
		} else {
			let link = document.createElement('link');
			link.type = 'text/css';
			link.rel = 'stylesheet';
			link.href = element.href;
			document.head.appendChild(link);
			console.log('引入CSS：' + element.href);
		}
	}
}

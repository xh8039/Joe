class TurboLinks {

	"use strict";

	pjax;

	handleResponseParam = {};

	/** 本次需要加载的 JavaScript文件列表 */
	loadJSList = [];

	loadJSIndex = 1;

	/** 全局已经加载过的 JavaScript文件列表 */
	static documentScriptList = [];

	/** 全局已经加载过的 CSS 文件列表 */
	static documentCSSLinkList = [];

	constructor(url, selectors = [], options = {}) {
		options.pjax = options.pjax || 'TurboLinks';
		options.selectors = options.selectors || selectors;
		options.cacheBust = options.cacheBust || false;
		if (!options.elements) {
			var link_element = document.createElement('a');
			link_element.id = 'turbo-links-' + (+new Date());
			link_element.href = url;
			link_element.setAttribute('data-pjax-state', true);
			document.body.appendChild(link_element);
			options.elements = '#' + link_element.id;
		}
		var pjax = new Pjax(options);
		pjax._handleResponse = pjax.handleResponse;
		pjax.handleResponse = (responseText, request, href, options) => {
			this.handleResponseParam = { responseText, request, href, options };
			const responseDocument = (new DOMParser()).parseFromString(responseText, 'text/html');

			const loadStyleList = responseDocument.head.querySelectorAll('style');
			loadStyleList.forEach(this.loadStyle);

			document.querySelectorAll('link[rel="stylesheet"][href]').forEach(element => {
				TurboLinks.documentCSSLinkList.push(element.href);
			});
			const loadCSSList = responseDocument.head.querySelectorAll('link[rel="stylesheet"][href]');
			loadCSSList.forEach(this.loadCSSLink);

			this.loadJSList = responseDocument.head.querySelectorAll('script[src]');
			if (this.loadJSList.length < 1) return pjax._handleResponse(responseText, request, href, options);
			document.querySelectorAll('script[src]').forEach(element => {
				TurboLinks.documentScriptList.push(element.src);
			});
			this.loadJSList.forEach((element, index) => {
				this.replaceJs(element);
			});
		}
		pjax.loadUrl(url);
		this.pjax = pjax;
		document.addEventListener('pjax:success', (options) => {
			if (options.pjax != 'TurboLinks') return;
			document.dispatchEvent(new CustomEvent('turbolinks:load'));
		});
		link_element.remove();
	}

	JsLoaded(element) {
		TurboLinks.documentScriptList.push(element.src);
		if (this.loadJSIndex == this.loadJSList.length) {
			console.log('所有JavaScript文件都已加载！');
			return this.pjax._handleResponse(this.handleResponseParam.responseText, this.handleResponseParam.request, this.handleResponseParam.href, this.handleResponseParam.options);
		}
		this.loadJSIndex++;
	}

	replaceJs(element) {
		if (!this || !this instanceof TurboLinks) {
			console.log(this);
			console.error('请使用TurboLinks对象调用！');
			return false;
		}
		let code = element.text || element.textContent || element.innerHTML || "";
		let script = document.createElement("script");

		if (code.match("document.write")) {
			if (console && console.log) console.log("脚本包含document.write。无法正确执行。代码已跳过", element);
			return false;
		}

		script.type = "text/javascript";
		if (element.id) script.id = element.id;

		if (element.src) {
			if ($(element).attr('data-turbolinks-permanent') != undefined && TurboLinks.documentScriptList.includes(element.src)) {
				this.JsLoaded(element);
				return true;
			}
			script.src = element.src;
			script.async = false;
			script.addEventListener('load', () => {
				console.log('引入JS：' + element.src);
				this.JsLoaded(element);
			});
			script.addEventListener('error', () => {
				console.error('Error loading script:', element.src);
				this.JsLoaded(element);
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
		if (!code) return false;
		console.log('引入style：'.code);
		let style = document.createElement('style');
		style.type = 'text/css';
		try {
			style.appendChild(document.createTextNode(code));
		} catch (ex) {
			style.styleSheet.cssText = code; // 兼容IE
		}
		document.head.appendChild(style);
	}

	loadCSSLink(element, index) {
		if (!element.href) return false;
		if (TurboLinks.documentCSSLinkList.includes(element.href)) return false;
		TurboLinks.documentCSSLinkList.push(element.href);
		let link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = element.href;
		document.head.appendChild(link);
		console.log('引入CSS：' + element.href);
	}
}

class TurboLinks {

	"use strict";

	/** Pjax实例 */
	static pjax;

	/** 本次响应的的参数 */
	static handleResponseParam = {};

	/** 本次需要加载的 JavaScript文件列表 */
	static loadJSList = [];

	/** 当前正在加载的 JavaScript文件索引 */
	static loadJSIndex = 1;

	/** 全局已经加载过的 JavaScript文件列表 */
	static documentScriptList = [];

	/** 全局已经加载过的 CSS 文件列表 */
	static documentCSSLinkList = [];

	/** Pjax必须需要的链接元素 */
	static linkElement;

	/** Pjax原本的 handleResponse 方法 */
	static handleResponse;

	static start(selectors = [], options = {}) {
		document.dispatchEvent(new CustomEvent('turbolinks:load'));
		TurboLinks.createLink();
		options.pjax = options.pjax || 'TurboLinks';
		options.selectors = options.selectors || selectors;
		options.cacheBust = options.cacheBust || false;
		if (!options.elements) options.elements = '#' + TurboLinks.linkElement.id;
		TurboLinks.pjax = new Pjax(options);
		TurboLinks.handleResponse = TurboLinks.pjax.handleResponse;
		TurboLinks.pjax.handleResponse = (responseText, request, href, options) => {
			TurboLinks.handleResponseParam = { responseText, request, href, options };
			const responseDocument = (new DOMParser()).parseFromString(responseText, 'text/html');

			/** 加载style标签中的样式 */
			responseDocument.head.querySelectorAll('style').forEach(TurboLinks.loadStyle);

			/** 加载link标签中的CSS文件 */
			document.querySelectorAll('link[rel="stylesheet"][href]').forEach(element => {
				TurboLinks.documentCSSLinkList.push(element.href);
			});
			responseDocument.head.querySelectorAll('link[rel="stylesheet"][href]').forEach(TurboLinks.loadCSSLink);

			TurboLinks.loadJSList = responseDocument.head.querySelectorAll('script[src]');
			if (TurboLinks.loadJSList.length < 1) return TurboLinks.handleResponse(responseText, request, href, options);
			document.querySelectorAll('script[src]').forEach(element => {
				TurboLinks.documentScriptList.push(element.src);
			});
			responseDocument.head.querySelectorAll('script').forEach(element => {
				TurboLinks.replaceJs(element);
			});
		}
		document.addEventListener('pjax:start', (options) => {
			if (options.pjax != 'TurboLinks') return;
			document.dispatchEvent(new CustomEvent('turbolinks:start'));
		});
		document.addEventListener('pjax:success', (options) => {
			if (options.pjax != 'TurboLinks') return;
			document.dispatchEvent(new CustomEvent('turbolinks:load'));
		});
	}

	static createLink(url = null) {
		TurboLinks.linkElement = document.createElement('a');
		TurboLinks.linkElement.href = url;
		TurboLinks.linkElement.id = 'turbo-links-' + (+new Date());
		TurboLinks.linkElement.setAttribute('data-pjax-state', true);
		document.body.appendChild(TurboLinks.linkElement);
		console.log(TurboLinks.linkElement);
		return TurboLinks.linkElement;
	}

	static visit(url) {
		return TurboLinks.pjax.loadUrl(url);
	}

	static JsLoaded(element) {
		TurboLinks.documentScriptList.push(element.src);
		if (TurboLinks.loadJSIndex == TurboLinks.loadJSList.length) {
			console.log('所有JavaScript文件都已加载！');
			TurboLinks.loadJSList = [];
			TurboLinks.loadJSIndex = 1;
			return TurboLinks.handleResponse(TurboLinks.handleResponseParam.responseText, TurboLinks.handleResponseParam.request, TurboLinks.handleResponseParam.href, TurboLinks.handleResponseParam.options);
		}
		TurboLinks.loadJSIndex++;
	}

	static replaceJs(element) {
		let code = element.text || element.textContent || element.innerHTML || "";
		let script = document.createElement("script");

		if (code === "" && !element.src) return false;

		script.type = "text/javascript";
		if (element.id) script.id = element.id;

		// 强制同步加载外部JS
		if (element.src) {
			if ($(element).attr('data-turbolinks-permanent') != undefined && TurboLinks.documentScriptList.includes(element.src)) {
				TurboLinks.JsLoaded(element);
				return true;
			}
			script.src = element.src;
			script.async = false;
			script.addEventListener('load', () => {
				console.log('引入JS：' + element.src);
				TurboLinks.JsLoaded(element);
			});
			script.addEventListener('error', () => {
				console.error('Error loading script:', element.src);
				TurboLinks.JsLoaded(element);
			});
		}

		if (code !== "") {
			if (code.match("document.write")) {
				if (console && console.log) console.log("脚本包含document.write。无法正确执行。代码已跳过", element);
				return false;
			}
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
		if (code) console.log('执行JS：' + code);
		// 仅避免头部或身体标签污染
		if ((parent instanceof HTMLHeadElement || parent instanceof HTMLBodyElement) && parent.contains(script)) {
			parent.removeChild(script);
		}

		return true;
	}

	static loadStyle(element) {
		let code = element.text || element.textContent || element.innerHTML || null;
		if (!code || code == undefined) return false;
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

	static loadCSSLink(element) {
		let url = element.href;
		if (!url) return false;
		if (TurboLinks.documentCSSLinkList.includes(url)) return false;
		TurboLinks.documentCSSLinkList.push(url);
		let css = document.createElement('link');
		css.type = 'text/css';
		css.rel = 'stylesheet';
		css.href = url;
		document.head.appendChild(css);
		console.log('引入CSS：' + url);
	}
}

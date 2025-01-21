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
	// static documentCSSLinkList = [];

	/** Pjax必须需要的链接元素 */
	static linkElement;

	static start(selectors = [], options = {}) {
		document.dispatchEvent(new CustomEvent('turbolinks:load'));
		TurboLinks.createLink();
		options.pjax = options.pjax || 'TurboLinks';
		options.selectors = options.selectors || selectors;
		options.cacheBust = options.cacheBust || false;
		if (!options.elements) options.elements = '#' + TurboLinks.linkElement.id;
		TurboLinks.pjax = new Pjax(options);
		TurboLinks.pjax.originHandleResponse = TurboLinks.pjax.handleResponse;
		TurboLinks.pjax.handleResponse = (responseText, request, href, options) => {
			TurboLinks.handleResponseParam = { responseText, request, href, options };
			const responseDOM = (new DOMParser()).parseFromString(responseText, 'text/html');

			// 删除旧的style标签中的样式
			// document.head.querySelectorAll('style').forEach(element => element.remove());
			// 加载新的style标签中的样式
			responseDOM.head.querySelectorAll('style').forEach(TurboLinks.loadStyle);

			// 获取新的CSS文件列表
			const responseDOMCSSLinkList = [];
			responseDOM.head.querySelectorAll('link[rel="stylesheet"][href]').forEach(element => {
				responseDOMCSSLinkList[element.href] = element;
			});

			const repeatCSSList = [];

			// 删除旧的CSS文件列表，如果有和新的CSS文件列表重复的，则保留
			document.head.querySelectorAll('link[rel="stylesheet"][href]').forEach(element => {
				if (responseDOMCSSLinkList[element.href]) {
					repeatCSSList.push(element.href);
				} else {
					console.log('删除CSS：' + element.href);
					element.remove();
				}
			});

			console.log(repeatCSSList);

			// 删除旧的link标签中的CSS文件
			// document.head.querySelectorAll('link[rel="stylesheet"][href]').forEach(element => element.remove());
			// 加载新的link标签中的CSS文件
			console.log(responseDOMCSSLinkList)
			responseDOMCSSLinkList.forEach(url => {
				if (repeatCSSList.includes(url)) {
					console.log('跳过CSS：' + url);
					return;
				}
				TurboLinks.loadCSSLink(url);
			});

			// 获取新的文档中head标签内的JS文件列表
			TurboLinks.loadJSList = responseDOM.head.querySelectorAll('script[src]');
			// 如果没有则直接载入响应的HTML文本
			if (TurboLinks.loadJSList.length < 1) return TurboLinks.pjax.originHandleResponse(responseText, request, href, options);
			// 记录当前文档中的JS文件列表
			document.querySelectorAll('script[src]').forEach(element => TurboLinks.documentScriptList.push(element.src));
			// 先载入新的文档中的JS文件，再载入HTML文本
			responseDOM.head.querySelectorAll('script').forEach(element => TurboLinks.replaceJs(element));
		}
		document.addEventListener('pjax:send', (options) => {
			if (options.pjax != 'TurboLinks') return;
			document.dispatchEvent(new CustomEvent('turbolinks:send'));
		});
		document.addEventListener('pjax:success', (options) => {
			if (options.pjax != 'TurboLinks') return;
			document.dispatchEvent(new CustomEvent('turbolinks:load'));
		});
	}

	static unique(arr) {
		return Array.from(new Set(arr))
	}

	static createLink(url = null) {
		TurboLinks.linkElement = document.createElement('a');
		TurboLinks.linkElement.href = url;
		TurboLinks.linkElement.id = 'turbo-links-' + (+new Date());
		TurboLinks.linkElement.setAttribute('data-pjax-state', true);
		document.body.appendChild(TurboLinks.linkElement);
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
			return TurboLinks.pjax.originHandleResponse(TurboLinks.handleResponseParam.responseText, TurboLinks.handleResponseParam.request, TurboLinks.handleResponseParam.href, TurboLinks.handleResponseParam.options);
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
		if (!code) return false;
		console.log('引入style：' + code);
		let style = document.createElement('style');
		style.type = 'text/css';
		try {
			style.appendChild(document.createTextNode(code));
		} catch (ex) {
			style.styleSheet.cssText = code; // 兼容IE
		}
		document.head.appendChild(style);
	}

	static loadCSSLink(url) {
		// let url = element.href;
		if (!url) return false;
		// if (TurboLinks.documentCSSLinkList.includes(url)) return false;
		// TurboLinks.documentCSSLinkList.push(url);
		let css = document.createElement('link');
		css.type = 'text/css';
		css.rel = 'stylesheet';
		css.href = url;
		document.head.appendChild(css);
		console.log('引入CSS：' + url);
	}
}

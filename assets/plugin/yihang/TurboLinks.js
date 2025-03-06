/**
 * @package TurboLinks
 * @version 1.0
 * @author 易航
 * @link http://blog.yihang.info
 * @license: MIT
*/
class TurboLinks {

	"use strict";

	/** 是否开启调试模式 */
	static debug = false;

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

	/** 本次响应的 CSS 文件列表 */
	static responseDOMCSSLinkList = {};

	/** Pjax必须需要的链接元素 */
	static linkElement;

	static start(selectors = [], options = {}) {
		document.dispatchEvent(new CustomEvent('turbolinks:load'));
		TurboLinks.createLink();
		if (!Array.isArray(selectors)) options = selectors;
		options.pjax = options.pjax || 'TurboLinks';
		options.selectors = options.selectors || selectors;
		options.cacheBust = options.cacheBust || false;
		if (!options.elements) options.elements = '#' + TurboLinks.linkElement.id;
		TurboLinks.pjax = new Pjax(options);
		TurboLinks.pjax.originHandleResponse = TurboLinks.pjax.handleResponse;
		TurboLinks.pjax.handleResponse = (responseText, request, href, options) => {
			if (!responseText) {
				console.log(request);
				if (request.status == 0) {
					const url = new URL(href);
					if (url.protocol == location.protocol) {
						autolog.log('您似乎未连接到 Internet，请检查您的网络连接', 'error');
					} else {
						autolog.log(`对 '${href}' 的请求已被浏览器的 CORS 策略阻止，${location.protocol} 协议的网页无法请求 ${url.protocol} 协议的资源，网站管理员真是大傻春`, 'error');
					}
				} else {
					autolog.log('请求失败：' + (request.statusText || request.status), 'error');
				}
				document.dispatchEvent(new CustomEvent('turbolinks:complete'));
				setTimeout(() => {
					const message = `是否直接有刷访问 '${href}' ？`;
					if (window.layer) {
						layer.confirm(message, () => location.href = href);
					} else {
						window.confirm(message) ? location.href = href : null;
					}
				}, 1000);
				return;
			}

			TurboLinks.handleResponseParam = { responseText, request, href, options };
			const responseDOM = (new DOMParser()).parseFromString(responseText, 'text/html');

			// 删除旧的style标签中的样式
			// document.head.querySelectorAll('style').forEach(element => element.remove());
			// 加载新的style标签中的样式
			responseDOM.head.querySelectorAll('style').forEach(TurboLinks.loadStyle);

			// 获取新的CSS文件列表
			TurboLinks.responseDOMCSSLinkList = {};
			responseDOM.head.querySelectorAll('link[rel="stylesheet"][href]:not([data-turbolinks-permanent])').forEach(element => {
				TurboLinks.responseDOMCSSLinkList[element.href] = element;
			});
			const repeatCSSList = [];
			// 记录重复的CSS文件
			document.head.querySelectorAll('link[rel="stylesheet"][href]:not([data-turbolinks-permanent])').forEach(element => {
				if (TurboLinks.responseDOMCSSLinkList[element.href]) repeatCSSList.push(element.href);
			});
			// 加载新的link标签中的CSS文件
			for (let url in TurboLinks.responseDOMCSSLinkList) {
				if (repeatCSSList.includes(url)) {
					if (TurboLinks.debug) console.log('跳过CSS：' + url);
				} else {
					TurboLinks.loadCSSLink(url);
				}
			}

			// 获取新的文档中head标签内的JS文件列表
			TurboLinks.loadJSList = responseDOM.head.querySelectorAll('script[src]');
			// 如果没有则直接载入响应的HTML文本
			if (TurboLinks.loadJSList.length < 1) {
				document.dispatchEvent(new CustomEvent('turbolinks:complete', { detail: options }));
				return TurboLinks.pjax.originHandleResponse(responseText, request, href, options);
			}
			// 记录当前文档中的JS文件列表
			document.querySelectorAll('script[src]').forEach(element => TurboLinks.documentScriptList.push(element.src));
			// 先载入新的文档中的JS文件，再载入HTML文本
			responseDOM.head.querySelectorAll('script').forEach(element => TurboLinks.loadScript(element));
		}
		document.addEventListener('pjax:send', (options) => {
			if (options.pjax != 'TurboLinks') return;
			document.dispatchEvent(new CustomEvent('turbolinks:send', { detail: options }));
		});
		document.addEventListener('pjax:complete', (options) => {
			if (options.pjax != 'TurboLinks') return;
			// 去除重复的全局JS文件列表
			TurboLinks.documentScriptList = TurboLinks.unique(TurboLinks.documentScriptList);
			// 删除旧的CSS文件列表，如果有和新的CSS文件列表重复的，则保留
			document.head.querySelectorAll('link[rel="stylesheet"][href]:not([data-turbolinks-permanent])').forEach(element => {
				if (!TurboLinks.responseDOMCSSLinkList[element.href]) {
					if (TurboLinks.debug) console.log('删除CSS：' + element.href);
					element.remove();
				}
			});
		})
		document.addEventListener('pjax:success', (options) => {
			if (options.pjax != 'TurboLinks') return;
			document.dispatchEvent(new CustomEvent('turbolinks:load', { detail: options }));
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
		url = url.replace(/^https?:/i, location.protocol);
		TurboLinks.linkElement.href = url;
		return TurboLinks.pjax.loadUrl(url);
	}

	static JsLoaded(element) {
		TurboLinks.documentScriptList.push(element.src);
		if (TurboLinks.loadJSIndex == TurboLinks.loadJSList.length) {
			if (TurboLinks.debug) console.log('TurboLinks：所有JavaScript文件都已加载');
			TurboLinks.loadJSList = [];
			TurboLinks.loadJSIndex = 1;
			document.dispatchEvent(new CustomEvent('turbolinks:complete', { detail: this.pjax.options }));
			return TurboLinks.pjax.originHandleResponse(TurboLinks.handleResponseParam.responseText, TurboLinks.handleResponseParam.request, TurboLinks.handleResponseParam.href, TurboLinks.handleResponseParam.options);
		}
		TurboLinks.loadJSIndex++;
	}

	static loadScript(element) {
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
				if (TurboLinks.debug) console.log('引入JS：' + element.src);
				TurboLinks.JsLoaded(element);
			});
			script.addEventListener('error', () => {
				console.error('Error loading script:', element.src);
				TurboLinks.JsLoaded(element);
			});
		}

		if (code !== "") {
			if (code.match("document.write")) {
				if (console && console.log) console.log('脚本包含 document.write，无法正确执行，代码已跳过', element);
				return false;
			}
			try {
				script.appendChild(document.createTextNode(code));
			} catch (e) {
				/* istanbul 忽视 下一个 */
				// 旧的 IEs 有新的 script 节点
				script.text = code;
			}
		}

		let parent = document.querySelector("head") || document.documentElement;
		parent.appendChild(script);
		if (code && TurboLinks.debug) console.log('执行JS：' + code.replace(/\s/g, ' ').replace(/ +/g, ' ').trim());
		// 仅避免 head 或 body 标签污染
		if ((parent instanceof HTMLHeadElement || parent instanceof HTMLBodyElement) && parent.contains(script)) {
			parent.removeChild(script);
		}

		return true;
	}

	static loadStyle(element) {
		let code = element.text || element.textContent || element.innerHTML || null;
		if (!code) return false;
		if (TurboLinks.debug) console.log('引入style：' + code);
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
		if (!url) return false;
		let css = document.createElement('link');
		css.type = 'text/css';
		css.rel = 'stylesheet';
		css.href = url;
		document.head.appendChild(css);
		if (TurboLinks.debug) console.log('引入CSS：' + url);
	}
}

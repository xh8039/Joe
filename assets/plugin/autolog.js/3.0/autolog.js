
/**
 * @name: autolog.js
 * @author: Larry Zhu
 * @version: 3.0.0
 * @description: 轻量化的小弹窗
 * @license: MIT
 */
var autolog = (function () {

	"use strict";

	//#region src/index.ts
	const cssStr = `#autolog{display:flex;flex-direction:column;align-items:center;justify-content:flex-start;pointer-events:none;width:100vw;height:100vh;position:fixed;left:0;top:20px;z-index:9999999;cursor:pointer;transition:.2s}#autolog>span{pointer-events:auto;width:max-content;animation:fadein .4s;animation-delay:0s;border-radius:6px;padding:10px 20px;box-shadow:0 0 10px 6px rgba(0,0,0,.1);margin:4px;transition:.2s;z-index:9999999;font-size:14px;display:flex;align-items:center;justify-content:center;gap:4px;height:max-content;overflow:hidden;background-color:#fafafa}#autolog>span>span{max-width:50vw}#autolog span.hide{opacity:0;pointer-events:none;transform:translateY(-10px);height:0;padding:0;margin:0}#autolog>.autolog-warn{background-color:#fffaec;color:#e29505}#autolog>.autolog-error{background-color:#fde7e7;color:#d93025}#autolog>.autolog-info{background-color:#e6f7ff;color:#0e6eb8}#autolog>.autolog-success{background-color:#e9f7e7;color:#1a9e2c}#autolog>.autolog-{background-color:#fafafa;color:#333}#autolog>.autolog-load>.animate-turn{animation:MessageTurn 1s linear infinite;-webkit-animation:MessageTurn 1s linear infinite}@keyframes MessageTurn{0%{-webkit-transform:rotate(0deg)}25%{-webkit-transform:rotate(90deg)}50%{-webkit-transform:rotate(180deg)}75%{-webkit-transform:rotate(270deg)}100%{-webkit-transform:rotate(360deg)}}@keyframes fadein{0%{opacity:0;transform:translateY(-10px)}100%{opacity:1;transform:translateY(0)}}`;
	const svgIcons = {
		warn: `<svg t="1713405237257" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2387" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16"><path d="M934.4 770.133333L605.866667 181.333333C586.666667 147.2 550.4 128 512 128c-38.4 0-74.666667 21.333333-93.866667 53.333333L89.6 770.133333c-19.2 34.133333-19.2 76.8 0 110.933334S145.066667 938.666667 183.466667 938.666667h657.066666c38.4 0 74.666667-21.333333 93.866667-57.6 19.2-34.133333 19.2-76.8 0-110.933334z m-55.466667 81.066667c-8.533333 14.933333-23.466667 23.466667-38.4 23.466667H183.466667c-14.933333 0-29.866667-8.533333-38.4-23.466667-8.533333-14.933333-8.533333-34.133333 0-49.066667L473.6 213.333333c8.533333-12.8 23.466667-21.333333 38.4-21.333333s29.866667 8.533333 38.4 21.333333l328.533333 588.8c8.533333 14.933333 8.533333 32 0 49.066667z" fill="#e29505" p-id="2388"></path><path d="M512 746.666667m-42.666667 0a42.666667 42.666667 0 1 0 85.333334 0 42.666667 42.666667 0 1 0-85.333334 0Z" fill="#e29505" p-id="2389"></path><path d="M512 629.333333c17.066667 0 32-14.933333 32-32v-192c0-17.066667-14.933333-32-32-32s-32 14.933333-32 32v192c0 17.066667 14.933333 32 32 32z" fill="#e29505" p-id="2390"></path></svg>`,
		error: `<svg t="1713405212725" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1744" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16"><path d="M512 74.666667C270.933333 74.666667 74.666667 270.933333 74.666667 512S270.933333 949.333333 512 949.333333 949.333333 753.066667 949.333333 512 753.066667 74.666667 512 74.666667z m0 810.666666c-204.8 0-373.333333-168.533333-373.333333-373.333333S307.2 138.666667 512 138.666667 885.333333 307.2 885.333333 512 716.8 885.333333 512 885.333333z" fill="#d93025" p-id="1745"></path><path d="M657.066667 360.533333c-12.8-12.8-32-12.8-44.8 0l-102.4 102.4-102.4-102.4c-12.8-12.8-32-12.8-44.8 0-12.8 12.8-12.8 32 0 44.8l102.4 102.4-102.4 102.4c-12.8 12.8-12.8 32 0 44.8 6.4 6.4 14.933333 8.533333 23.466666 8.533334s17.066667-2.133333 23.466667-8.533334l102.4-102.4 102.4 102.4c6.4 6.4 14.933333 8.533333 23.466667 8.533334s17.066667-2.133333 23.466666-8.533334c12.8-12.8 12.8-32 0-44.8l-106.666666-100.266666 102.4-102.4c12.8-12.8 12.8-34.133333 0-46.933334z" fill="#d93025" p-id="1746"></path></svg>`,
		info: `<svg width="16" height="16" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><path d="M24 44C29.5228 44 34.5228 41.7614 38.1421 38.1421C41.7614 34.5228 44 29.5228 44 24C44 18.4772 41.7614 13.4772 38.1421 9.85786C34.5228 6.23858 29.5228 4 24 4C18.4772 4 13.4772 6.23858 9.85786 9.85786C6.23858 13.4772 4 18.4772 4 24C4 29.5228 6.23858 34.5228 9.85786 38.1421C13.4772 41.7614 18.4772 44 24 44Z" fill="#1890ff" stroke="#1890ff" stroke-width="4" stroke-linejoin="round"/><path fill-rule="evenodd" clip-rule="evenodd" d="M24 11C25.3807 11 26.5 12.1193 26.5 13.5C26.5 14.8807 25.3807 16 24 16C22.6193 16 21.5 14.8807 21.5 13.5C21.5 12.1193 22.6193 11 24 11Z" fill="#FFF"/><path d="M24.5 34V20H23.5H22.5" stroke="#FFF" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 34H28" stroke="#FFF" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
		success: `<svg t="1713405224326" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2225" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16"><path d="M512 74.666667C270.933333 74.666667 74.666667 270.933333 74.666667 512S270.933333 949.333333 512 949.333333 949.333333 753.066667 949.333333 512 753.066667 74.666667 512 74.666667z m0 810.666666c-204.8 0-373.333333-168.533333-373.333333-373.333333S307.2 138.666667 512 138.666667 885.333333 307.2 885.333333 512 716.8 885.333333 512 885.333333z" fill="#1a9e2c" p-id="2226"></path><path d="M701.866667 381.866667L448 637.866667 322.133333 512c-12.8-12.8-32-12.8-44.8 0-12.8 12.8-12.8 32 0 44.8l149.333334 149.333333c6.4 6.4 14.933333 8.533333 23.466666 8.533334s17.066667-2.133333 23.466667-8.533334l277.333333-277.333333c12.8-12.8 12.8-32 0-44.8-14.933333-12.8-36.266667-12.8-49.066666-2.133333z" fill="#1a9e2c" p-id="2227"></path></svg>`,
		load: `<svg class="animate-turn" width="16" height="16" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><path d="M4 24C4 35.0457 12.9543 44 24 44V44C35.0457 44 44 35.0457 44 24C44 12.9543 35.0457 4 24 4" stroke="#1890ff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/><path d="M36 24C36 17.3726 30.6274 12 24 12C17.3726 12 12 17.3726 12 24C12 30.6274 17.3726 36 24 36V36" stroke="#1890ff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
		x: `<svg class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16"><path d="M256 810.666667c-12.8 0-21.333333-4.266667-29.866667-12.8-17.066667-17.066667-17.066667-42.666667 0-59.733334l512-512c17.066667-17.066667 42.666667-17.066667 59.733334 0s17.066667 42.666667 0 59.733334l-512 512c-8.533333 8.533333-17.066667 12.8-29.866667 12.8zM768 810.666667c-12.8 0-21.333333-4.266667-29.866667-12.8l-512-512c-17.066667-17.066667-17.066667-42.666667 0-59.733334s42.666667-17.066667 59.733334 0l512 512c17.066667 17.066667 17.066667 42.666667 0 59.733334-8.533333 8.533333-17.066667 12.8-29.866667 12.8z" fill="#000000"/></svg>`,
		"": ""
	};
	const autolog = {
		log(text = "", type = "", time = 2500, autoClose = true) {
			if (typeof type === "number") {
				autoClose = time;
				time = type;
				type = "";
			} else if (typeof type === "boolean") {
				autoClose = type;
				type = "";
				time = 2500;
			} else if (typeof time === "boolean") {
				autoClose = time;
				time = 2500;
			}
			time += 900;
			const mainEl = getMainElement();
			let el = document.createElement("span");
			el.className = `autolog-${type.toString()}`;
			if (type.toString().startsWith("icon-")) el.innerHTML = `<span class="iconfont ${type.toString()}"></span> <span>${escapeHtml(text)}</span>`;
			else el.innerHTML = svgIcons[type] + `<span>${escapeHtml(text)}</span>`;
			if (!autoClose) el.innerHTML += getX(type);
			mainEl.appendChild(el);
			if (autoClose) {
				setTimeout(() => {
					el.classList.add("hide");
				}, time - 500);
				setTimeout(() => {
					mainEl.removeChild(el);
					el = null;
				}, time);
			} else el?.querySelector(".icon:last-child")?.addEventListener("click", () => {
				el.classList.add("hide");
				setTimeout(() => {
					mainEl.removeChild(el);
					el = null;
				}, 500);
			});
		},
		info(text = "", time = 2500, autoClose = true) {
			window.Joe && Joe?.AudioManager.play(`notification/WaterDay.ogg`);
			autolog.log(text, 'info', time, autoClose);
		},
		success(text = "", time = 2500, autoClose = true) {
			window.Joe && Joe?.AudioManager.play(`notification/WaterEvening.ogg`);
			autolog.log(text, 'success', time, autoClose);
		},
		warn(text = "", time = 2500, autoClose = true) {
			window.Joe && Joe?.AudioManager.play(`notification/WaterDropPreview.ogg`);
			"vibrate" in navigator && navigator.vibrate(200);
			autolog.log(text, 'warn', time, autoClose);
		},
		error(text = "", time = 2500, autoClose = true) {
			window.Joe && Joe?.AudioManager.play(`notification/SystemDelete.ogg`);
			"vibrate" in navigator && navigator.vibrate(200);
			autolog.log(text, 'error', time, autoClose);
		},
		load(text = "", time = 2500, autoClose = true) {
			autolog.log(text, 'load', time, autoClose);
		},
		create(options) {
			customIcons(options.svgIcons);
			return { log: autolog.log.bind(autolog) };
		}
	};
	const mtoastElementColor = {
		warn: "#e29505",
		error: "#d93025",
		info: "#0e6eb8",
		success: "#1a9e2c"
	};
	function getX(type) {
		const svgEl = svgIcons.x;
		const coloredSvgEl = svgEl.replace("fill=\"#000000\"", `fill="${mtoastElementColor[type]}"`);
		return coloredSvgEl;
	}
	function customIcons(icons) {
		Object.assign(svgIcons, icons);
	}
	function getMainElement() {
		let mainEl = document.querySelector("#autolog");
		if (!mainEl) {
			mainEl = document.createElement("div");
			mainEl.id = "autolog";
			document.body.appendChild(mainEl);
			const style = document.createElement("style");
			style.innerHTML = cssStr;
			document.head.insertBefore(style, document.head.firstChild);
		}
		return mainEl;
	}
	function escapeHtml(unsafe) {
		return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/\n/g, "</br>");
	}
	var src_default = autolog;

	//#endregion
	return src_default;
})();
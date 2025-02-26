class JoeEditor extends JoeAction {
	constructor() {
		super();
		this.plugins = [CodeMirror.classHighlightStyle, CodeMirror.history(), CodeMirror.bracketMatching(), CodeMirror.closeBrackets(), CodeMirror.drawSelection(), CodeMirror.highlightActiveLine(), CodeMirror.lineNumbers(), CodeMirror.highlightActiveLineGutter(), CodeMirror.highlightSelectionMatches()];
		this.keymaps = [
			{
				key: 'Tab',
				run: ({ state, dispatch }) => {
					if (state.selection.ranges.some(r => !r.empty)) return CodeMirror.indentMore({ state, dispatch });
					dispatch(state.update(state.replaceSelection('  ')));
					return true;
				},
				shift: CodeMirror.indentLess
			}
		];
		this._isPasting = false;
		this.init_ViewPort();
		this.init_Editor();
		this.init_Preview();
		this.init_Tools();
		this.init_Insert();
		this.init_AutoSave();
		this.init_AutoStorage();
		this.init_CleanFields();
	}

	/* 已测 √ */
	init_ViewPort() {
		if ($('meta[name="viewport"]').length > 0) $('meta[name="viewport"]').attr('content', 'width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover');
		else $('head').append('<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, shrink-to-fit=no, viewport-fit=cover">');
	}

	/* 已测 √ */
	init_Editor() {
		$('#text').before(`
				<div class="cm-container">
						<div class="cm-tools"></div>
						<div class="cm-mainer">
								<div class="cm-resize"></div>
								<div class="cm-preview"><div class="cm-preview-content joe_detail__article"></div></div>
			<div class="cm-autosave"></div>
						</div>
						<div class="cm-progress-left"></div>
						<div class="cm-progress-right"></div>
				</div>
		`);
		createPreviewHtml(null);
		let _temp = null;
		let _debounce = null;
		const cm = new CodeMirror.EditorView({
			state: CodeMirror.EditorState.create({
				doc: $('#text').val(),
				extensions: [
					...this.plugins,
					CodeMirror.markdown({
						base: CodeMirror.markdownLanguage,
						codeLanguages: CodeMirror.languages
					}),
					CodeMirror.keymap.of([...this.keymaps, ...CodeMirror.defaultKeymap, ...CodeMirror.commentKeymap, ...CodeMirror.historyKeymap, ...CodeMirror.closeBracketsKeymap,]),
					CodeMirror.EditorView.updateListener.of(update => {
						if (!update.docChanged) return;
						if (_temp !== update.state.doc.toString()) {
							_temp = update.state.doc.toString();
							clearTimeout(_debounce);
							_debounce = setTimeout(createPreviewHtml.bind(null, update.state.doc.toString()), 350);
						}
					}),
					CodeMirror.EditorView.domEventHandlers({
						paste: e => {
							const clipboardData = e.clipboardData;
							if (!clipboardData || !clipboardData.items) return;
							const items = clipboardData.items;
							if (!items.length) return;
							let blob = null;
							for (let i = 0; i < items.length; i++) {
								if (items[i].type.indexOf('image') !== -1) {
									e.preventDefault();
									blob = items[i].getAsFile();
									break;
								}
							}
							if (!blob) return;
							let api = window.JoeConfig.uploadAPI;
							if (!api) return;
							const cid = $('input[name="cid"]').val();
							cid && (api = api + '&cid=' + cid);
							if (this._isPasting) return;
							this._isPasting = true;
							const fileName = Date.now().toString(36) + '.png';
							let formData = new FormData();
							formData.append('name', fileName);
							formData.append('file', blob, fileName);
							$.ajax({
								url: api,
								method: 'post',
								data: formData,
								contentType: false,
								processData: false,
								dataType: 'json',
								xhr: () => {
									const xhr = $.ajaxSettings.xhr();
									if (!xhr.upload) return;
									xhr.upload.addEventListener(
										'progress',
										e => {
											let percent = (e.loaded / e.total) * 100;
											$('.cm-progress-left').width(percent / 2 + '%');
											$('.cm-progress-right').width(percent / 2 + '%');
										},
										false
									);
									return xhr;
								},
								success: res => {
									$('.cm-progress-left').width(0);
									$('.cm-progress-right').width(0);
									this._isPasting = false;
									const str = `${super._getLineCh(cm) ? '\n' : ''}![${res[1].title}](${res[0]})\n`;
									super._replaceSelection(cm, str);
									cm.focus();
								},
								error: () => {
									$('.cm-progress-left').width(0);
									$('.cm-progress-right').width(0);
									this._isPasting = false;
								}
							});
						},
						scroll: e => {
							if (!window.JoeConfig.canPreview) return;
							if (e && e.target && e.target.className === 'cm-scroller') {
								if (window.requestAnimationFrame) window.requestAnimationFrame(() => super._updateScroller(e.target, document.querySelector('.cm-preview')));
								else super._updateScroller(e.target, document.querySelector('.cm-preview'));
							}
						}
					})
				]
			})
		});
		window.CodeMirrorEditor = cm;
		$('.cm-mainer').prepend(cm.dom);
		$('#text')[0].form && $('#text')[0].form.addEventListener('submit', () => $('#text').val(cm.state.doc.toString()));
		this.cm = cm;
	}

	/* 已测 √ */
	init_Preview() {
		const move = (nowClientX, nowWidth, clientX) => {
			let moveX = nowClientX - clientX;
			let moveWidth = nowWidth + moveX;
			if (moveWidth <= 0) moveWidth = 0;
			if (moveWidth >= $('.cm-mainer').outerWidth() - 16) moveWidth = $('.cm-mainer').outerWidth() - 16;
			$('.cm-preview').width(moveWidth);
		};
		$('.cm-resize').on({
			mousedown: e => {
				e.preventDefault();
				e.stopPropagation();
				const nowWidth = $('.cm-preview').outerWidth();
				const nowClientX = e.clientX;
				$('.cm-preview').addClass('move');
				document.onmousemove = _e => {
					if (window.requestAnimationFrame) requestAnimationFrame(() => move(nowClientX, nowWidth, _e.clientX));
					else move(nowClientX, nowWidth, _e.clientX);
				};
				document.onmouseup = () => {
					document.onmousemove = null;
					document.onmouseup = null;
					$('.cm-preview').removeClass('move');
				};
				return false;
			},
			touchstart: e => {
				e.preventDefault();
				e.stopPropagation();
				const nowWidth = $('.cm-preview').outerWidth();
				const nowClientX = e.originalEvent.targetTouches[0].clientX;
				$('.cm-preview').addClass('move');
				document.ontouchmove = _e => {
					if (window.requestAnimationFrame) requestAnimationFrame(() => move(nowClientX, nowWidth, _e.targetTouches[0].clientX));
					else move(nowClientX, nowWidth, _e.targetTouches[0].clientX);
				};
				document.ontouchend = () => {
					document.ontouchmove = null;
					document.ontouchend = null;
					$('.cm-preview').removeClass('move');
				};
				return false;
			}
		});
	}

	/* 已测 √ */
	init_Tools() {
		tools.forEach(item => {
			if (item.type === 'title') {
				super.handleTitle(this.cm, item);
			} else {
				const el = $(`<div class="cm-tools-item" data-toggle="tooltip" data-placement="auto top" title="${item.title}">${item.innerHTML}</div>`);
				el.on('click', e => {
					e.preventDefault();
					switch (item.type) {
						case 'fullScreen':
							super.handleFullScreen(el);
							break;
						case 'publish':
							super.handlePublish();
							break;
						case 'undo':
							super.handleUndo(this.cm);
							break;
						case 'redo':
							super.handleRedo(this.cm);
							break;
						case 'time':
							super.handleTime(this.cm);
							break;
						case 'bold':
							super._insetAmboText(this.cm, '**');
							break;
						case 'italic':
							super._insetAmboText(this.cm, '*');
							break;
						case 'delete':
							super._insetAmboText(this.cm, '~~');
						case 'font-center':
							super._insetAmboText(this.cm, '<p align="center">', '</p>');
							break;
						case 'font-right':
							super._insetAmboText(this.cm, '<p align="right">', '</p>');
							break;
						case 'font-color':
							super._insetAmboText(this.cm, '<font color="red">', '</font>');
							break;
						case 'font-size':
							super._insetAmboText(this.cm, '<font size="5">', '</font>');
							break;
						case 'code-inline':
							super._insetAmboText(this.cm, '`');
							break;
						case 'indent':
							super.handleIndent(this.cm);
							break;
						case 'hr':
							super.handleHr(this.cm);
							break;
						case 'format':
							super.handleFormat(this.cm);
							break;
						case 'turndown':
							super.handleHtmlToMarkdown(this.cm);
							break;
						case 'clean':
							super.handleClean(this.cm);
							break;
						case 'ordered-list':
							super.handleOrdered(this.cm);
							break;
						case 'unordered-list':
							super.handleUnordered(this.cm);
							break;
						case 'quote':
							super.handleQuote(this.cm);
							break;
						case 'download':
							super.handleDownload(this.cm);
							break;
						case 'link':
							super.handleLink(this.cm);
							break;
						case 'image':
							super.handleImage(this.cm);
							break;
						case 'table':
							super.handleTable(this.cm);
							break;
						case 'code-block':
							super.handleCodeBlock(this.cm);
							break;
						case 'about':
							super.handleAbout();
							break;
						case 'character':
							super._createTableLists(this.cm, JoeConfig.characterAPI, '星星符号', '字符大全');
							break;
						case 'emoji':
							super._createTableLists(this.cm, JoeConfig.emojiAPI, '表情', '表情符号（需数据库支持）');
							break;
						case 'task-no':
							super.handleTask(this.cm, false);
							break;
						case 'task-yes':
							super.handleTask(this.cm, true);
							break;
						case 'netease-list':
							super.handleNetease(this.cm, true);
							break;
						case 'netease-single':
							super.handleNetease(this.cm, false);
							break;
						case 'bilibili':
							super.handleBilibili(this.cm);
							break;
						case 'dplayer-single':
							super.handleDplayer(this.cm);
							break;
						case 'dplayer-list':
							super.handleDplayerList(this.cm);
							break;
						case 'draft':
							super.handleDraft();
							break;
						case 'expression':
							super.handleExpression(this.cm);
							break;
						case 'mtitle':
							super.handleMtitle(this.cm);
							break;
						case 'html':
							super.handleHtml(this.cm);
							break;
						case 'abtn':
							super.handleAbtn(this.cm);
							break;
						case 'anote':
							super.handleAnote(this.cm);
							break;
						case 'dotted':
							super.handleDotted(this.cm);
							break;
						case 'hide':
							super.handleHide(this.cm);
							break;
						case 'card-default':
							super.handleCardDefault(this.cm);
							break;
						case 'message':
							super.handleMessage(this.cm);
							break;
						case 'progress':
							super.handleProgress(this.cm);
							break;
						case 'callout':
							super.handleCallout(this.cm);
							break;
						case 'mp3':
							super.handleMp3(this.cm);
							break;
						case 'tabs':
							super.handleTabs(this.cm);
							break;
						case 'card-list':
							super.handleCardList(this.cm);
							break;
						case 'timeline':
							super.handleTimeline(this.cm);
							break;
						case 'copy':
							super.handleCopy(this.cm);
							break;
						case 'card-describe':
							super.handleCardDescribe(this.cm);
							break;
						case 'lamp':
							super.handleLamp(this.cm);
							break;
						case 'collapse':
							super.handleCollapse(this.cm);
							break;
						case 'cloud':
							super.handleCloud(this.cm);
							break;
						case 'gird':
							super.handleGird(this.cm);
							break;
						case 'alert':
							super.handleAlert(this.cm);
							break;
						case 'iframe':
							super.handleIframe(this.cm);
							break;
						case 'preview':
							el.toggleClass('active');
							if (el.hasClass('active')) {
								window.JoeConfig.canPreview = true;
								this.loadFiles([
									Joe.THEME_URL + 'assets/css/joe.mode.css',
									Joe.THEME_URL + 'assets/css/joe.article.css',
									Joe.CDN_URL + 'font-awesome/4.7.0/css/font-awesome.css',
									Joe.CDN_URL + 'aplayer/1.10.1/APlayer.min.css',
									Joe.CDN_URL + 'aplayer/1.10.1/APlayer.min.js',
									Joe.CDN_URL + 'color-thief/2.3.2/color-thief.min.js',
									Joe.THEME_URL + 'assets/plugin/yihang/MusicPlayer.js',
									Joe.CDN_URL + 'prism-themes/1.9.0/' + JoeConfig.JPrismTheme,
									Joe.CDN_URL + 'prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.css',
									Joe.CDN_URL + 'prism/1.9.0/prism.min.js',
									Joe.CDN_URL + 'prism/1.9.0/plugins/autoloader/prism-autoloader.min.js',
									Joe.CDN_URL + 'prism/1.9.0/plugins/line-numbers/prism-line-numbers.min.js',
									Joe.THEME_URL + 'assets/js/joe.short.js',
								]).then(() => {
									createPreviewHtml(this.cm.state.doc.toString());
									super._updateScroller(document.querySelector('.cm-scroller'), document.querySelector('.cm-preview'));
								});
							} else {
								window.JoeConfig.canPreview = false;
								createPreviewHtml(this.cm.state.doc.toString());
							}
							break;
					}
				});
				$('.cm-tools').append(el);
			}
		});
		window.Joe.tooltip();
		document.addEventListener('keydown', (event) => {
			if (event.shiftKey && event.altKey && event.key.toLowerCase() === 'f') {
				event.preventDefault();
				// 你的代码在这里...
				console.log("Shift + Alt + F 按下！");
				super.handleFormat(this.cm);
			}
		});
	}

	/* 已测 √ */
	init_Insert() {
		Typecho.insertFileToEditor = (file, url, isImage) => {
			const str = `${super._getLineCh(this.cm) ? '\n' : ''}${isImage ? '!' : ''}[${file}](${url})\n`;
			super._replaceSelection(this.cm, str);
			this.cm.focus();
		};
	}

	/* 已测 √ */
	init_AutoSave() {
		if (window.JoeConfig.autoSave !== 1) return;
		const formEl = $('#text')[0].form;
		let cid = $(formEl).find('input[name="cid"]').val();
		/* 临时记录 */
		let _TempTimer = null;
		let _TempTitle = $(formEl).find('input[name="title"]').val();
		let _TempText = $(formEl).find('textarea[name="text"]').val();
		const saveFn = () => {
			$(formEl).find('input[name="cid"]').val(cid);
			$(formEl).find('textarea[name="text"]').val(this.cm.state.doc.toString());
			/* 创建新记录 */
			let _NewTempTitle = $(formEl).find('input[name="title"]').val();
			let _NewTempText = $(formEl).find('textarea[name="text"]').val();
			/* 若标题为空，则直接忽略 */
			if (_NewTempTitle.trim() === '') return;
			/* 若标题或内容发生改变，触发保存草稿 */
			if (_TempTitle !== _NewTempTitle || _TempText !== _NewTempText) {
				_TempTitle = _NewTempTitle;
				_TempText = _NewTempText;
				$('.cm-autosave').addClass('active');
				$.ajax({
					url: formEl.action,
					type: 'POST',
					data: $(formEl).serialize() + '&do=save',
					dataType: 'json',
					success: res => {
						cid = res.cid;
						_TempTimer = setTimeout(() => {
							$('.cm-autosave').removeClass('active');
							clearTimeout(_TempTimer);
						}, 1000);
					}
				});
			}
		};
		setInterval(saveFn, 5000);
	}

	init_CleanFields() {
		var form = document.querySelector('[name="write_post"]') || document.querySelector('[name="write_page"]');
		if (!form) return;
		form.addEventListener('submit', () => {
			let inputs = document.querySelectorAll('#custom-field tbody input');
			let textareas = document.querySelectorAll('#custom-field tbody textarea');
			let selects = document.querySelectorAll('#custom-field tbody select');
			let fields = [...inputs, ...textareas, ...selects];
			fields.forEach(field => {
				if (field.value == '') field.remove();
			});
		});
	}

	init_AutoStorage() {
		var form = document.querySelector('[name="write_post"]') || document.querySelector('[name="write_page"]');
		if (!form) return;

		function isEmptyString(string) {
			return (!string || string.trim() == '' || string.length == 0);
		}

		// 从本地存储加载数据并填充到表单的函数
		function loadFormData() {
			var localStorageformData = localStorage.getItem('form-data');
			if (localStorageformData) {
				const data = JSON.parse(localStorageformData);
				let formSlug = document.getElementById('slug')?.value;
				if (data.slug != formSlug) return;
				if (isEmptyString(data.text) && isEmptyString(data.title)) return;
				if (!window.confirm('检测到您于 ' + data.time + ' 有自动存储的未发布文章 [' + data.title + '] 是否为您恢复？')) return;
				// 遍历 data 对象并填充表单元素
				for (const key in data) {
					if (data.hasOwnProperty(key)) {
						const escapedName = key.replace(/\[/g, "\[");
						// console.log(escapedName);
						const element = document.querySelector(`[name="${escapedName}"]`);
						if (element) element.value = data[key];
					}
				}
				// 特别处理 CodeMirror 内容
				window.CodeMirrorEditor.dispatch({
					changes: {
						from: 0,
						to: window.CodeMirrorEditor.state.doc.length,
						insert: data.text
					}
				});
			}
		}
		loadFormData();

		function getCurrentTime() {
			const now = new Date();
			const year = now.getFullYear();
			const month = ("0" + (now.getMonth() + 1)).slice(-2); // 月份从 0 开始，需要加 1
			const day = ("0" + now.getDate()).slice(-2);
			const hours = ("0" + now.getHours()).slice(-2);
			const minutes = ("0" + now.getMinutes()).slice(-2);
			const seconds = ("0" + now.getSeconds()).slice(-2);
			return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
		}

		// 保存表单数据的函数
		function saveFormData() {
			var formData = new FormData(form);
			const data = {};
			for (const [key, value] of formData.entries()) {
				data[key] = value;
			}
			// 等编辑器内容同步
			setTimeout(() => {
				data.time = getCurrentTime();
				data.text = window.CodeMirrorEditor.state.doc.toString();
				// console.log(data);
				localStorage.setItem('form-data', JSON.stringify(data));
			}, 500);
		}

		// 获取文章内容的元素
		const contentElement = form; // 假设文章内容的元素是 `textarea`，并且有 `name="text"` 属性

		// 监听内容改变事件
		contentElement.addEventListener('input', saveFormData);

		// 监听input事件监听不到的按键操作
		contentElement.addEventListener('keydown', (event) => {
			// 监听 Ctrl + X, Ctrl + Z, Ctrl + Y, Ctrl + V
			if (event.ctrlKey && ['x', 'z', 'y', 'v'].includes(event.key)) {
				saveFormData();
			}
			// 监听 Backspace, Delete, Enter, Tab
			if (['Backspace', 'Delete', 'Enter', 'Tab'].includes(event.key)) {
				saveFormData();
			}
		});
		form.addEventListener('submit', () => localStorage.removeItem('form-data'));
	}
}

document.addEventListener('DOMContentLoaded', () => new JoeEditor());

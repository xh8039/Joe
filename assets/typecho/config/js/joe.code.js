document.addEventListener("DOMContentLoaded", function () {
    var codeInputArray = Array.from(document.querySelectorAll(".joe_content.joe_code>li>textarea"));

    window.CodeMirrorEditor = {};

    var formatCode = {
        htmlmixed: html_beautify,
        css: css_beautify,
        javascript: js_beautify
    };

    codeInputArray.forEach(codeInput => {
        var mode = codeInput.parentElement.parentElement.dataset.language;

        // 将 textarea 转换为 CodeMirror 编辑器实例
        CodeMirrorEditor[codeInput.name] = CodeMirror.fromTextArea(codeInput, {
            mode: mode,
            theme: "dracula",
            lineNumbers: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            extraKeys: {
                "Ctrl-Space": "autocomplete",
                "Shift-Alt-F": function (cm) {
                    let code = formatCode[mode](cm.getValue(), {
                        indent_size: 1,
                        indent_char: '	'
                    });
                    cm.setValue(code);
                },
                "Ctrl-/": 'toggleComment' // 绑定 Ctrl+? 快捷键
            }
        });


        // 监听输入事件
        CodeMirrorEditor[codeInput.name].on("inputRead", (cm, obj) => {
            cm.showHint({
                hint: CodeMirror.hint[mode], // 使用正确的方式获取自动完成函数
                completeSingle: false, // 不自动选择第一个匹配项
                closeOnUnfocus: true, // 失去焦点时关闭自动完成列表
                completeOnSingleClick: false, // 点击第一个建议项不会自动选择
                alignWithCursor: true // 建议列表与光标对齐
            });
        });
    });
});
import { EditorView, keymap, drawSelection, highlightActiveLine } from '@codemirror/view';
import { EditorState } from '@codemirror/state';
import { bracketMatching } from '@codemirror/matchbrackets';
import { closeBrackets, closeBracketsKeymap } from '@codemirror/closebrackets';
import { defaultKeymap, indentLess, indentMore } from '@codemirror/commands';
import { history, historyKeymap } from '@codemirror/history';
import { markdown, markdownLanguage } from '@codemirror/lang-markdown';
import { languages } from '@codemirror/language-data';
import { lineNumbers, highlightActiveLineGutter } from "@codemirror/gutter";
import { highlightSelectionMatches } from "@codemirror/search";
import { commentKeymap } from "@codemirror/comment";
import { classHighlightStyle } from '@codemirror/highlight';
import { undo, redo } from '@codemirror/history';
window.CodeMirror = {
    EditorView,
    keymap,
    drawSelection,
    highlightActiveLine,
    EditorState,
    bracketMatching,
    closeBrackets,
    closeBracketsKeymap,
    defaultKeymap,
    indentLess,
    indentMore,
    history,
    historyKeymap,
    markdown,
    markdownLanguage,
    languages,
    lineNumbers,
    highlightActiveLineGutter,
    highlightSelectionMatches,
    commentKeymap,
    classHighlightStyle,
    undo,
    redo
};
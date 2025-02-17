```markdown
# Pjax

[![构建状态](https://img.shields.io/travis/MoOx/pjax.svg)](https://travis-ci.org/MoOx/pjax)

> 轻松为任何网站启用快速 AJAX 导航（使用 pushState() + XHR）

Pjax 是一个**独立的 JavaScript 模块**，通过 [AJAX](https://developer.mozilla.org/en-US/docs/Web/Guide/AJAX)（XmlHttpRequest）和 [pushState()](https://developer.mozilla.org/en-US/docs/Web/Guide/API/DOM/Manipulating_the_browser_history) 实现快速页面浏览体验。

_它可以将传统网站（服务端生成或静态页面）的用户体验升级为类似单页应用的效果，尤其对低带宽用户友好。_

**不再需要整页刷新，不再需要重复加载资源。**

_Pjax 不依赖 jQuery 或其他库，完全基于原生 JavaScript 编写。_

---

## 安装

- **直接引入 CDN 文件**  
  完整版本：
  ```html
  <script src="https://cdn.jsdelivr.net/npm/pjax@VERSION/pjax.js"></script>
  ```
  压缩版本：
  ```html
  <script src="https://cdn.jsdelivr.net/npm/pjax@VERSION/pjax.min.js"></script>
  ```

- **通过 npm 安装**  
  ```shell
  npm install pjax
  ```
  **注意**：若使用此方式，需在 HTML 中手动引入：
  ```html
  <script src="./node_modules/pjax/pjax.js"></script>
  ```
  或使用 Webpack 等打包工具。

- **从源码构建**  
  ```shell
  git clone https://github.com/MoOx/pjax.git
  cd pjax
  npm install
  npm run build
  ```
  构建后引入：
  ```html
  <script src="./pjax.min.js"></script>
  ```

---

## 功能特性

- **单次 HTTP 请求 + `pushState()`**  
  通过 AJAX 加载页面内容，使用 `pushState()` 更新 URL，无需重新加载布局或资源（JS/CSS）。
- **智能回退**  
  不支持 `pushState()` 的浏览器自动回退为标准导航。
- **全面支持浏览器历史**  
  包括前进/后退按钮和键盘导航。
- **灵活替换**  
  支持同时替换多个 DOM 元素（如标题、元数据、导航栏等）。
- **轻量高效**  
  仅约 6KB（压缩后）。

---

## 工作原理

1. **监听链接点击**（默认为所有 `<a>` 标签）。
2. **拦截内部链接**，通过 AJAX 获取目标页面 HTML。
3. **验证 DOM 结构**，若符合条件则替换指定元素，否则回退为标准导航。
4. **更新 URL** 并保持资源（如图片、CSS、JS）不被重新加载。

---

## 快速示例

```javascript
var pjax = new Pjax({
  selectors: [
    "title",
    "meta[name=description]",
    ".the-header",
    ".the-content",
    ".the-sidebar",
  ]
})
```
此配置会让 Pjax 替换页面标题、元描述、头部、内容区和侧边栏。

---

## 与 jQuery-pjax 的区别

- 无 jQuery 依赖
- 支持多容器替换
- 内置 CSS 动画支持
- 所有方法均可重写

---

## 配置选项

### 核心选项

- `elements` (String): 触发 Pjax 的链接选择器，默认为 `"a[href], form[action]"`。
- `selectors` (Array): 需要替换的 DOM 元素选择器，如 `["title", ".content"]`。
- `switches` (Object): 自定义元素替换动画（见下文示例）。

### 高级选项

- `scrollTo` (数值 | 数组 | false): 页面切换后滚动位置。
- `analytics` (Function | Boolean): 统计代码处理（默认支持 Google Analytics）。
- `cacheBust` (Boolean): 是否添加时间戳绕过缓存。

---

## 动画示例

```javascript
var pjax = new Pjax({
  selectors: [".js-Pjax"],
  switches: {
    ".js-Pjax": Pjax.switches.sideBySide
  },
  switchesOptions: {
    ".js-Pjax": {
      classNames: {
        remove: "Animated Animated--reverse",
        add: "Animated",
        backward: "Animate--slideInRight",
        forward: "Animate--slideInLeft"
      }
    }
  }
})
```
结合 CSS 动画库（如 Animate.css）可实现平滑过渡效果。

---

## 事件监听

```javascript
document.addEventListener('pjax:send', showLoadingIndicator);
document.addEventListener('pjax:complete', hideLoadingIndicator);
```
常用事件：`pjax:send`（开始请求）、`pjax:success`（成功）、`pjax:error`（失败）。

---

## 常见问题

### Disqus 评论失效？

将 Disqus 代码包裹在 Pjax 容器内，并添加重置逻辑：
```html
<div class="js-Pjax">
  <script>
    if (!window.DISQUS) {
      // 初始化代码
    } else {
      DISQUS.reset(); // 重置 Disqus
    }
  </script>
</div>
```

---

## 参与贡献

- 欢迎提交 Pull Request 或 Star 支持。
- 提交前请确保通过测试（`npm test`）。

[完整更新日志](CHANGELOG.md) | [许可证](LICENSE)
```
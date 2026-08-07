# niRvana 开发环境恢复说明

> 恢复日期：2026-08-06（源文件反解）
> 官方库替换：2026-08-07
> 恢复方式：从生产压缩包 `assets/minify/app.min.css` / `app.min.js` 反解，第三方库替换为官方原版

## 恢复内容

丢失的 `assets/css/` 和 `assets/js/` 两个源目录已重建，`production.php` 开发模式
（`$is_production = false`）引用的 **10 个 CSS + 23 个 JS** 全部就位。

### assets/css/（10 个）

| 文件 | 来源 |
|------|------|
| bootstrap.min.css | **官方版原样** Bootstrap 3.3.5（cdnjs），未加注释 |
| bootstrap_xxs.css | **官方版原样** auipga/bootstrap-xxs（xxs 断点补丁），未加注释 |
| bootstrap_24.css | **官方版原样** bootstrap24 3.3.0（24 栅格完整版），未加注释 |
| bootstrap_xl.css | 官方 BootstrapXL 原样 + 末尾追加主题扩展 `col-xl-*_5`（48 类，xl 0.5 栅格，nav-main.php 使用） |
| pdmessage-my.css | [130566, 153987)，主题自定义（pandastudio 生态） |
| fontawesome.css | **官方版原样** Font Awesome Free 6.5.1（cdnjs all.min.css），未加注释 |
| jv-element.css | [268861, 284516)，主题自定义（pandastudio 生态） |
| user-center-login.css | [406311, 407472)，主题自定义（pandastudio 生态） |
| style.css | 三段合并：白天 [284516,406311) + 响应式 [407507,419365) + 夜间 [421803, 结尾)，主题主样式 |
| highlightjs.css | [419365, 421803)，highlight.js 主题**定制版**（背景色 #22323a 等主题定制） |

> 各文件头部均保留了 app.min.css 中的物理区间注释，可对照验证。

### assets/js/（23 个）

| 文件 | 类型 | 说明 |
|------|------|------|
| jquery-2.1.0.min.js | 第三方**官方版** | jQuery 2.1.0（code.jquery.com） |
| jquery.mobile.custom.min.js | 第三方**官方版** | jQuery Mobile 1.4.5 Download Builder 定制（Core + Events + Button + Fieldcontain），与生产构成 100% token 匹配（含被强制勾选的 Page Creation 超集） |
| jquery-ui-custom-drag.min.js | 第三方**官方版** | jQuery UI 1.12.1 Draggable 定制（官方 Builder 生成，含 widget/mouse/position/data/focusable/keycode/scroll-parent） |
| jquery.qrcode.min.js | 第三方**官方版** | jquery-qrcode（jeromeetienne） |
| bootstrap.min.js | 第三方**官方版** | Bootstrap 3.3.5 |
| color-thief.js | 第三方**官方版** | Color Thief 2.0.1 |
| stackblur.min.js | 第三方**官方版** | flozz/StackBlur |
| circleMagic.min.js | 第三方**官方版** | circleMagic.js 1.0.0（FreAK19） |
| mustache.min.js | 第三方**官方版** | mustache.js 2.3.2 |
| masonry.pkgd.min.js | 第三方**官方版** | Masonry 4.2.2 |
| highlight.min.js | 第三方**官方版** | highlight.js 9.18.5（生产语言集为其子集，官方版语言更多，功能超集） |
| highlightjs-line-numbers.js | 第三方**官方版** | highlightjs-line-numbers 2.9.1（src 非压缩版） |
| pdmessage.js / jquery.vue.js / user-center-login.js / pandaSlider.js / pandaTab.js / jv-element.js / theme.js / jQuery.forceCache.js / jquery.imgcomplete.js / jquery.custom-scrollbars.js | 自定义 | 从生产包反解并美化，可直接编辑 |
| pandaHooks.js | 占位 | 生产包中无对应代码 |

## 重要说明与限制

1. **变量名与注释**：app.min.js 是深度压缩（作用域合并 + 局部变量重命名），自定义文件
   反解后**局部变量为 a/b/c，注释丢失**。函数逻辑、字符串、结构完整，可编辑但命名不还原。

2. **IIFE 补括号**：部分提取段原为逗号表达式元素，已补 `()` 包裹为独立可加载脚本
   （如 `(function(e){...})(jQuery)`）。文件头已注明。

3. **pandaHooks.js 为占位**：生产包 app.min.js 全文检索无 `pandaHooks` 相关代码，
   该文件在打包时未进入 bundle。创建占位避免开发模式 404。如需 hooks 功能需自行补充。

4. **jQuery 双重加载**：development 模式 `wp_enqueue_script('jquery')`（WP 自带）后又手动
   加载 `jquery-2.1.0.min.js`，会加载两个 jQuery——这是 production.php 原有设计，
   本次恢复未改动。

5. **外部依赖**：`pandastudio_framework` 全局由 `wp_localize_script('pf_restapi', ...)`
   注入（`pandastudio_framework/config_framework.php`），需在真实 WP 页面环境才有值。

6. **jquery.mobile.custom.min.js**：用官方 **Download Builder**（jquerymobile.com/download-builder/，
   branch 1.4.5）重新生成，勾选 Core（部分）+ Events（全部）+ Forms（Button/Fieldcontain）+
   Utilities（matchMedia/nojs）。与生产段 token 重叠 100%（0 缺失），Page Creation 为强制勾选
   带来的超集，无破坏导航组件（Navigation/Transitions 未包含）。

7. **官方库版本差异**：官方库替换后与生产压缩包内的版本**功能等价**（highlight 为
   超集，多出 go/kotlin/typescript 等语言；mobile 含 Page Creation 超集）。重新合并压缩
   无法字节级还原生产 app.min.*，属预期。

## 验证结果

- ✅ production.php 引用的 10 CSS + 23 JS 全部存在
- ✅ 23 个 JS 全部通过语法解析（acorn）
- ✅ 10 个 CSS 括号配对全部通过
- ✅ jsdom 模拟浏览器按 production.php 顺序加载 23 个 JS 全部成功
- ✅ 官方库全局齐全：jQuery 2.1.0 / Bootstrap 3.3.5 / jQuery UI draggable / Masonry /
     highlight.js+lineNumbers / ColorThief / circleMagic / qrcode / mustache / StackBlur
- ✅ 自定义全局齐全：PdMessage / jQVue / $.fn.{pandaSlider,pandaTab,imgcomplete,custom_scrollbar}
- ✅ theme.js 授权水印正常输出

## 使用

1. 本地 WordPress 安装本主题目录
2. 将 `production.php` 中 `$is_production` 改为 `false` 即进入开发模式（逐文件加载）
3. 改完源码后如需发布，用构建工具合并压缩回 `assets/minify/app.min.css/js`
   （压缩方式与生产版本存在差异，无法字节级还原）

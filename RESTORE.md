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
| jquery-2.1.0.min.js | 第三方**官方版** | jQuery 2.1.0（code.jquery.com） ~~2026-08-07 已删除~~ |
| jquery.mobile.custom.min.js | 第三方**官方版** | jQuery Mobile 1.4.5 Download Builder 定制（Core + Events + Button + Fieldcontain），与生产构成 100% token 匹配（含被强制勾选的 Page Creation 超集） ~~2026-08-07 已删除~~ |
| jquery-ui-custom-drag.min.js | 第三方**官方版** | jQuery UI 1.12.1 Draggable 定制（官方 Builder 生成，含 widget/mouse/position/data/focusable/keycode/scroll-parent） ~~2026-08-07 已删除~~ |
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
   本次恢复未改动。**（2026-08-07 前台现代化已消除，见文末）**

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
3. 改完源码后如需发布，重建生产 bundle：
   `node C:/Users/Michael/Desktop/nirjs/dev-tools/build-minify.js`
   （自动按 production.php 顺序合并压缩到 `assets/minify/app.min.js`；
   压缩方式与原始生产版本存在差异，无法字节级还原）

## 2026-08-07 前台现代化（jQuery 3 单一化）

在恢复基础上对前台做了渐进式现代化，开发与生产模式行为一致：

1. **消除双 jQuery**：不再手动加载 `vendor/jquery-2.1.0.min.js`，前台统一使用 WP 核心
   jQuery 3.x。
2. **移除 jQuery Mobile**：`vendor/jquery.mobile.custom.min.js` 不再加载。`pandaSlider.js`
   的触摸滑动（原 `swipeleft/swiperight`）改用 **Pointer Events** 自实现：仅触摸/笔触生效
   （鼠标不触发）、水平位移 ≥30px 且横向主导才翻页（与原 jQuery Mobile 阈值一致），
   `.showBox` 设置 `touch-action: pan-y` 避免手势被浏览器抢占。
3. **移除 jQuery UI**：`vendor/jquery-ui-custom-drag.min.js` 不再加载。`jquery.custom-scrollbars.js`
   的滚动条拖拽（原 `draggable`）改用 **Pointer Events** 自实现：`setPointerCapture` 跟随
   拖拽、钳制在轨道范围（0 ~ 轨道高 − 滑块高），与 `containment: "parent"` 行为等价。
4. **重建生产 bundle**：`assets/minify/app.min.js` 用 terser 重建，不再内嵌 jQuery 2.1.0 /
   jQuery Mobile / jQuery UI，仅依赖 WP 核心 jQuery（369KB → 254KB）。

> 说明：被移除的 3 个官方库（jquery-2.1.0 / jquery.mobile.custom / jquery-ui-custom-drag）
> 已于 2026-08-07 从 `assets/js/vendor/` 删除（git 历史可恢复）。
> 原生产包内嵌的 jQuery 2.1.0 会覆盖 WP 核心 jQuery 3，这正是此前 jQuery Mobile / UI
> 依赖 2.1.0 运行时正常的根因；替换为 Pointer Events 后二者一并移除。

### 现代化验证结果

- ✅ dev 模式 19 个 JS 在 jQuery 3.7.1 下全部加载成功、无异常
- ✅ 全局齐全：PdMessage / jQVue / $.fn.{pandaSlider,pandaTab,custom_scrollbar,imgcomplete} /
     Masonry / hljs / ColorThief / Mustache / StackBlur
- ✅ pandaSlider 触摸左滑/右滑翻页、纵向滑动不翻页、<30px 不翻页、鼠标拖拽不触发（jsdom 行为测试）
- ✅ custom_scrollbar 拖拽 thumb 联动 scrollTop、超限钳制、释放后恢复同步（jsdom 行为测试）
- ✅ 重建后 app.min.js 无 jQuery 2.1.0 / Mobile / UI 残留，加载成功、全局齐全

### 后续修复与第三方升级（2026-08-07 实机回归）

在 VMware Linux + WP 7.0 实机回归中发现并修复：

5. **dev 模式脚本入队**：开发模式不再用 `wp_head` 手动 echo `<script>`（与 WP 队列无依赖
   保证），改为 `wp_enqueue_script` 逐个入队并声明依赖 `jquery`；脚本列表收敛为 `$dev_scripts`
   数组（构建脚本同步解析，单一来源）。
6. **全局 `$` 兼容层**：WP 核心 jQuery 运行在 noConflict 模式，不提供全局 `$`；主题脚本
   历史上依赖内嵌 jQuery 2.1.0 提供的 `$`，移除后 `forceCache.js`/`theme.js` 报错。注入
   `wp_add_inline_script('jquery', 'window.$ = window.jQuery;')`（dev 与生产都加）。
7. **清理弃用事件简写**：`pandaSlider/pandaTab/theme/jv-element/user-center-login` 中
   `.click()/.hover()/.keyup()/.scroll()/.resize()/.load()/.focus()/.change()/.mousedown()/.mouseup()`
   等 54 处改为 `.on()` / `.trigger()`；其中 `.load()`（jQuery 3 已移除）改为 `.on('load')`。
8. **升级 Bootstrap 3.3.5 → 3.4.1**：官方 jQuery 3 支持、视觉一致（bundle 254 → 258KB）。

### 已知无害警告（未处理，功能正常）

- **bootstrap scrollspy 的 `$.isFunction()` 警告**：jQuery Migrate 由**其他插件**加载（主题无
  引用）。migrate 会拦截 `$.isFunction` 发"deprecated"建议，而 `$.isFunction` 在 jQuery 3.7
  原生存在、功能正常。消除需改加载 migrate 的插件或动 bootstrap 源码，不建议。
- **highlight.js 9 EOL 提示**：9.18.5 功能正常。升级到 11 会导致 token class 变化（如 `console`
  从 `hljs-built_in` 变 `hljs-variable`）造成视觉差异，且行号插件 3.x 无稳定分发，故保留 9。

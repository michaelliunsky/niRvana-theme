# Block 重写规格基线

从 `build/index.js`（ed6b6fe 格式化版）与 `index.php` / `functions.php` 提取。
**重写硬约束：block 名、attributes 键、save 输出格式必须与旧版一致**，否则已发布文章无法编辑/渲染。

## 前台渲染依赖（functions.php shortcode）

| shortcode | 处理器 | 输入 |
|---|---|---|
| `[tip type= display=]` | shortCodeTips | type 默认 info |
| `[modal id= btn_type= btn_label= title= close_label= href_label= href=]` | shortCodeModal | id 必须有 |
| `[dropdown id= btn_type= btn_label=]` | shortCodeDropdown | 内部 `[li href=]` |
| `[collapse id= btn_type= btn_label=]` | shortCodeCollapse | id 必须有 |
| `[download]` / `[reply2down]` | download_with_licence / reply_to_down | content=下载链接 |
| `[need_reply]` | need_reply | 需评论可见 |

## Block 清单

### tips — 提示框
- attributes: `content`, `typeClass`
- save: `[tip type="info" display="inlineBlock"]content[/tip]`（typeClass 去 "tip" 前缀取颜色；inlineBlock → display 属性）
- edit: 颜色选择(info/success/worning/error) + 全宽开关 + RichText

### single — 文章展示（动态）
- attributes: `post_id`(number), `align`(string)
- render: `pandastudio_block_render_single`(index.php)，按 post_type 输出卡片

### collapse — 折叠内容
- attributes: `id`, `btn_type`, `btn_label`
- save: `[collapse id=".." btn_type=".." btn_label=".."]InnerBlocks[/collapse]`
- edit: 颜色选择 + 按钮名 + InnerBlocks；id 缺失时在 edit 生成

### dropdown — 下拉菜单
- attributes: `id`, `btn_type`, `btn_label`, `lists`
- save: `[dropdown id btn_type btn_label]` + 每项 `[li href]label[/li]` + `[/dropdown]`
- edit: 颜色/按钮名 + 列表项编辑

### download — 下载按钮
- attributes: `href`, `need_reply`
- save: `[reply2down]href[/reply2down]`（need_reply=true）或 `[download]href[/download]`
- edit: 链接 + 需评论开关

### gallery — 轮播图（动态）
- attributes: `images`(object)
- render: `pandastudio_block_render_gallery`(index.php)，bootstrap carousel

### modal — 模态框
- attributes: `id`, `btn_type`, `btn_label`, `title`, `close_label`, `href_label`, `href`
- save: `[modal id btn_type btn_label title close_label href_label href]InnerBlocks[/modal]`
- edit: 各字段 TextControl + InnerBlocks

### needreply — 回复可见
- attributes: `{}`（无）
- save: `[need_reply]InnerBlocks[/need_reply]`
- edit: 提示 + InnerBlocks

### title — 小标题
- attributes: `content`, `titleClass`, `titleInnerTag`
- save: `<div><div class="{titleClass}"><{titleInnerTag}>{content}</{titleInnerTag}></div></div>`（直接 HTML）
- edit: 样式 ButtonGroup + tag 选择 + RichText

### bilibili — 哔哩哔哩视频
- attributes: `iframe`
- save: `<div class="bilibili_video_wrap"><figure dangerouslySetInnerHTML>{iframe}</figure></div>`（直接 HTML）
- edit: iframe 嵌入代码 TextControl

### ~~mark — 标记（RichText format）~~ 已移除（2026-08-11）
- 原 `registerFormatType`（`pandastudio/mark`，`span.pandastudio-mark`）已删除，不再注册
- 替代：官方 `core/text-color`（Highlight，输出 `<mark>`）；原 `.pandastudio-mark[inline-block=true]`
  圆角块样式已嫁接到 `mark[style*="background"]`（见 `assets/css/style.css` 与 blocks `src/style.scss`）
- 旧文章残留的 `<span class="pandastudio-mark">` 不再有样式（兼容性有意忽略）

## 构建产物契约
- `index.php` 加载 `build/index.js`（依赖 wp-blocks/wp-element/wp-editor）+ `build/style.css`
- 重写后用 `@wordpress/scripts` 输出需对齐这两个文件名（或同步改 index.php）

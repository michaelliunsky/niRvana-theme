![niRvana](https://cdn.jsdelivr.net/gh/michaelliunsky/cdn@master/screenshot.png)

**简体中文** | [繁體中文](README_tw.md) | [English](README_en.md) | [Russian](README_ru.md)

# niRvana-theme

niRvana · 轻拟物 WordPress 主题

typecho 版本 : [github.com/michaelliunsky/typecho-theme-niRvana](https://github.com/michaelliunsky/typecho-theme-niRvana)

[![GitHub release](https://img.shields.io/github/v/release/michaelliunsky/niRvana-theme?color=%235e72e4&style=for-the-badge)](https://github.com/michaelliunsky/niRvana-theme/releases) [![GitHub All Releases](https://img.shields.io/github/downloads/michaelliunsky/niRvana-theme/total?style=for-the-badge)](https://github.com/michaelliunsky/niRvana-theme/releases) [![GitHub](https://img.shields.io/github/license/michaelliunsky/niRvana-theme?color=blue&style=for-the-badge)](https://github.com/michaelliunsky/niRvana-theme/blob/master/LICENSE)

[![Author](https://img.shields.io/badge/author-michaelliunsky-yellow?style=for-the-badge)](https://github.com/michaelliunsky) [![GitHub stars](https://img.shields.io/github/stars/michaelliunsky/niRvana-theme?color=ff69b4&style=for-the-badge)](https://github.com/michaelliunsky/niRvana-theme/stargazers)

[![GitHub last commit](https://img.shields.io/github/last-commit/michaelliunsky/niRvana-theme?style=flat-square)](https://github.com/michaelliunsky/niRvana-theme/commits/master) [![GitHub Release Date](https://img.shields.io/github/release-date/michaelliunsky/niRvana-theme?style=flat-square)](https://github.com/michaelliunsky/niRvana-theme/releases) ![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/michaelliunsky/niRvana-theme?style=flat-square)

# 主题基本特性

- **HTML5、CSS3** - 使用标准语言编写，支持 IE10 以上浏览器
- **响应式** - 在桌面、平板、手机端均以最佳状态显示
- **回复下载** - 可强制要求用户评论文章后才提供下载地址
- **内容回复可见** - 指定某些内容需要读者评论后才可查看，让读者与你互动（可设置为要求用户注册登录并评论后才显示某些内容）
- **侧边栏小工具** - 边栏数量可自定义、完全使用 WP 官方的小工具模型，开发了更多适合本主题的小工具
- **语音朗读** - 使用百度语音合成技术来为您阅读文章
- **评论表情** - 本主题自带评论表情功能，无需插件在后台即可设置
- **不刷新加载** - 全局提供 ajax 加载文章
- **打赏** - 允许通过多种途径打赏，如：支付宝二维码、微信二维码、Paypal 链接
- **点赞** - 每篇文章均提供点赞功能，可展示访客最喜欢的文章列表（后台可以修改点赞数据）
- **生成封面二维码** - 每篇文章均可生成二维码用于分享到微信等社交平台
- **说说功能** - 简短说说功能，随时随地记录您的想法

# 安装

环境要求：**PHP8.3 兼容最佳**

在 [Release](https://github.com/michaelliunsky/niRvana-theme/releases) 页面下载 .zip 文件，在 WordPress 后台 "主题" 页面上传并安装。

**主题自带评论邮件回复，如果您需要评论回复自动邮件提醒功能，请安装 WP-SMTP 插件，或自行配置邮箱发送，如果不需要，则不需要采取操作**

# 文档

[作者博客中的文档 : https://blog.mkliu.top/](https://blog.mkliu.top/135.html)

# Demo

[blog.mkliu.top](https://blog.mkliu.top/)

# 注意

niRvana 使用 [GPL V3.0](https://github.com/michaelliunsky/niRvana-theme/blob/main/LICENSE) 协议开源，请遵守此协议进行二次开发等。

您**必须在页脚保留 niRvana 主题的名称及作者链接**，否则请不要使用 niRvana 主题。

# 更新日志

## 20250709 v5.0RC4 **重要修复，请务必更新！**

- **修复**：**PHP 8.4+ 报错问题**
- **修复**：**文章页目录识别失败严重问题**
- **修复**：**当点赞数为0时评论并点赞致命错误的问题**
- **修复**：**主题边栏小工具错误的严重问题**
- **修复**：restapi_init 不正确的问题
- **升级**：主题更新程序

## 20250626 v5.0RC3

- **修复**：wp_enqueue_scripts钩子使用不正确的问题
- **更新**：版权日期

## 20240404 v5.0RC2 **重要功能性修复，请务必更新！**

- **重要修复**：**首页图片不取主题色的问题**
- **升级**：主题更新程序

## 20240209 v5.0RC1 **更新后需要进入设置->固定链接->点击保存更改，否则无法访问页面。**

- **题外话**：**在除夕夜发布的 RC 版本预示着 v5.0 开发迈入新台阶。祝各位龙行龘龘，前程朤朤。龙年快乐！**
- **删除**：自动添加页面 HTML 后缀功能。**注意**因此导致**更新后需要进入设置->固定链接->点击保存更改，否则无法访问页面。**
- **性能优化**：更改了 Gravatar 为更快镜像源
- **更新**：fontawesome 版本
- **更新**：**全新适配 PHP8.3**

## 20231022 v5.0beta14

- **性能优化**：更改了 Gravatar 为更快镜像源
- **修复**：后台“主题设置”页面“语音合成”部分拖拽 Slider 按钮层不被左侧菜单浮动遮罩的 BUG

## 20231006 v5.0beta13

- **性能优化**：修复滚动图片 DOM 元素不断增加导致 JS 内存无法回收的严重问题
- **修复**：“轻博客”内容包含百分号时，脚本报错导致边栏不加载的问题
- **增加**：版权自动警告

## 20231003 v5.0beta12

- 修复了手机端 COPY CODE 不显示问题
- 删除了评论无用的引用，链接键

## 20230910 v5.0beta11

- 修复了代码高亮显示不正确问题
- 增加了桌面端设备代码一键复制功能

## 20230829 v5.0beta10 **成功兼容了 PHP8.2，请务必更新！**

- 支持 php8+
- 修复评论表情预览大小过大的 bug

## 20230828 v5.0beta9

- 修复了回复可见失效的 bug
- 删除 rainbow 彩虹字体功能
- 添加了加密文章的 CSS 优化

## 20230824 v5.0beta8

- 修复了评论 pre,img 标签失效问题
- 修复了评论图片输入框

## 20230818 v5.0beta7

- **紧急修复 BUG**
- 升级了主题更新程序

## 20230816 v5.0beta6

- 添加了主题自动更新功能

## 20230816 v5.0beta5 重大更新，请务必升级

- **主题紧急适配 wordpress6.3 版本**,修复 Gutenberg block
- 主题前端后台所有 fontawesome 全部升级至 6.4.2
- fontawesome gutenberg 显示问题修复
- 修复小工具问题
- 主题代码细节优化

## 20230813 v5.0beta4 压缩文件

- 压缩前端加载文件 JS,CSS

## 20230807 v5.0beta3 大优化更新

- 删去反馈不好功能：复制提示，修复复制问题
- 删去反馈不好功能：首页提示语
- 综合所有小插件
- 优化百度语音
- 优化整合并适配了主题的夜间模式 CSS

## 20230721 v5.0beta2-hotfix 紧急修复重要 BUG

- **紧急修复 BUG**

## 20230718 v5.0beta2 虽然是 v5.0 beta 版，但修复了重要 BUG，请务必更新！

- 重写了主题 JS
- 修复了评论 BUG
- 优化提示

## 20230627 v5.0beta1

- 修改摘要字数显示
- 修复一些 php8.X 中的错误
- 压缩一些文件缩小体积
- 更改版权网址

## 20230227 v4.2.2

- 主题兼容了 WEBP 格式
- 添加主题图片灯箱

## 20230209 v4.2.1 修复重要 BUG！请务必更新

- **修复了边栏重要 BUG**
- 修改主题设置数据
- 修复了提示样式
- 修复提示显示不正常的问题
- 去除无用文件

## 20230206 v4.2.0 重大升级，请务必更新！

- **重写主题所有代码，规范化代码，方便开发者编辑。**

## 20221215 v4.1.3

- 主题自带**fontawesome 升级到六版本**
- **复制时显示版权提示框**
- 天气部件优化

## 20221127 v4.1.2

- 网站欢迎语对话框切换为**只有首页显示**
- 修复后台图标重复问题
- 修复若干小 BUG

## 20221113 v4.1.1

- 修复某些迷惑提示
- 修复**评论不能正常代码高亮问题**
- 最近不会有太大的更新

## 20221106 v4.1.0 重大升级，请尽量更新！

- **移除主题所有插件依赖，直接安装不会有任何报错，优化其他 bug，可以在虚拟主机上完美使用。**
- 评论打字**粒子爆炸效果**
- 新增**归档页面**
- 优化**夜间模式 css**，完美适配
- 文章显示**字数以及预计阅读时长**
- 后台登录页面**采用必应每日一图为背景**

## 20221028 v4.0.2

- 全局 rainbow 彩虹字特效，在文章或评论中插入

```html
<font class="rainbow">输入内容</font>
```

即可有**彩虹扫光字体**效果

- 增加**MD 回复**功能
- 评论可插入**图片，代码，引用文字，链接**
- 添加**简短说说功能，随时随地记录您的想法**
- 添加**右下角弹窗欢迎功能，显示地点，天气情况**

## 20221026 v4.0.1

- 删除所有 eval 加密，增强主题兼容性。
- 继续优化代码，修复若干 BUG

## 20221025 v4.0.0

- 新主题发布
- 本主题由 pandastudio 不再维护的 niRvana 主题开发而成，版权归 michaelliunsky 所有。

# 捐赠

如果你觉得 niRvana 主题不错，可以请我一杯咖啡来支持我的开发。

![微信捐赠码](https://cdn.jsdelivr.net/gh/michaelliunsky/cdn@master/wechat.jpg)
![支付宝捐赠码](https://cdn.jsdelivr.net/gh/michaelliunsky/cdn@master/alipay.jpg)

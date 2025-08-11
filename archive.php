<?php

global $wp_query;
$taxonomy = $wp_query->tax_query->queries[0]["taxonomy"];
switch ($taxonomy) {
    case 'gallery-category':
        include('assets/template/archive-gallery.php');
        break;
    case 'gallery-tag':
        include('assets/template/tag-gallery.php');
        break;
    case 'faq-category':
        wp_die('警告：FAQ类型不支持直接预览，请新增页面，选择“常见问题”模板！');
        break;
    case 'favlinks-category':
        wp_die('警告：友情链接类型不支持直接预览，请新增页面，选择“友情链接”模板！友情链接必须隶属于某个分类才可以显示，请务必设置友链的分类！');
        break;
    default:
        do_action('modify_custom_taxonomy', $taxonomy);
        break;
}

<?php
get_header();
if (have_posts()) {
    while (have_posts()) {
        the_post();
        $post_type = get_post_type();
        switch ($post_type) {
            case 'gallery':
                locate_template('assets/template/single-gallery.php', true, true);
                break;
            case 'faq':
                wp_die('警告：FAQ类型不支持直接预览，请前往“主题设置”页面添加！');
                break;
            case 'favlinks':
                wp_die('警告：友情链接类型不支持直接预览，请新增页面，选择“友情链接”模板！友情链接必须隶属于某个分类才可以显示，请务必设置友链的分类！');
                break;
            case 'post':
            case 'microblog':
                locate_template('assets/template/single-post.php', true, true);
                break;
            default:
                do_action('modify_single_type', $post_type);
                break;
        }
    }
    wp_reset_postdata();
}
get_footer();

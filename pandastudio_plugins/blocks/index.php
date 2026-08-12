<?php

add_theme_support('align-wide');
add_filter(
    'block_categories_all',
    function ($categories, $post) {
        return array_merge(
            array(
                array(
                    'slug'  => 'pandastudio-block-category',
                    'title' => 'PANDA Studio UI 样式',
                    'icon'  => 'dashicons-admin-appearance',
                ),
            ),
            $categories
        );
    },
    10,
    2
);
add_filter(
    'allowed_block_types_all',
    function ($allowed_block_types, $editor_context) {
        $widget_blocks = array(
            'pandastudio/user-info',
            'pandastudio/tag-cloud',
            'pandastudio/microblog',
            'pandastudio/hotposts',
        );
        $content_blocks = array(
            'pandastudio/title',
            'pandastudio/tips',
            'pandastudio/download',
            'pandastudio/collapse',
            'pandastudio/dropdown',
            'pandastudio/modal',
            'pandastudio/gallery',
            'pandastudio/bilibili',
            'pandastudio/single',
            'pandastudio/needreply',
        );

        if (true === $allowed_block_types) {
            $allowed_block_types = array_keys(WP_Block_Type_Registry::get_instance()->get_all_registered());
        }
        if (is_array($allowed_block_types)) {
            $is_widgets_context = in_array($editor_context->name, array('core/edit-widgets', 'core/customize-widgets'), true);
            $exclude = $is_widgets_context ? $content_blocks : $widget_blocks;
            return array_values(array_diff($allowed_block_types, $exclude));
        }
        return $allowed_block_types;
    },
    10,
    2
);
add_action(
    'init',
    function () {
        $asset = require __DIR__ . '/build/index.asset.php';
        wp_register_script(
            'pandastudio-blocks',
            get_stylesheet_directory_uri() . '/pandastudio_plugins/blocks/build/index.js',
            $asset['dependencies'],
            $asset['version']
        );
        wp_register_style(
            'pandastudio-block-styles',
            get_stylesheet_directory_uri() . '/pandastudio_plugins/blocks/build/style-index.css',
            false,
            filemtime(__DIR__ . '/build/style-index.css')
        );
        wp_register_style(
            'pandastudio-font-awesome',
            get_stylesheet_directory_uri() . '/pandastudio_framework/assets/css/font-awesome.css',
            false,
            filemtime(get_template_directory() . '/pandastudio_framework/assets/css/font-awesome.css')
        );
        $render_callbacks = array(
            'single'    => 'pandastudio_block_render_single',
            'gallery'   => 'pandastudio_block_render_gallery',
            'microblog' => 'pandastudio_block_render_microblog',
            'hotposts'  => 'pandastudio_block_render_hotposts',
        );
        $metadata_blocks = array( 'tips', 'single', 'collapse', 'dropdown', 'download', 'gallery', 'modal', 'needreply', 'title', 'bilibili', 'user-info', 'tag-cloud', 'microblog', 'hotposts' );
        foreach ($metadata_blocks as $name) {
            $args = array(
                'editor_script' => 'pandastudio-blocks',
            );
            if (isset($render_callbacks[$name])) {
                $args['render_callback'] = $render_callbacks[$name];
            }
            register_block_type_from_metadata(
                __DIR__ . '/build/blocks/' . $name . '/block.json',
                $args
            );
        }
    }
);
add_action(
    'enqueue_block_assets',
    function () {
        wp_enqueue_style('pandastudio-block-styles');
        if (is_admin()) {
            wp_enqueue_style('pandastudio-font-awesome');
        }
    }
);
add_action(
    'enqueue_block_editor_assets',
    function () {
        $meta_asset = require __DIR__ . '/build/meta-panel.asset.php';
        wp_enqueue_script(
            'nirvana-meta-panel',
            get_stylesheet_directory_uri() . '/pandastudio_plugins/blocks/build/meta-panel.js',
            $meta_asset['dependencies'],
            $meta_asset['version']
        );
        wp_enqueue_style(
            'nirvana-admin-settings',
            get_stylesheet_directory_uri() . '/pandastudio_framework/assets/css/admin.css',
            array(),
            filemtime(get_template_directory() . '/pandastudio_framework/assets/css/admin.css')
        );
    }
);
function pandastudio_block_render_single($attributes)
{
    $id = isset($attributes['post_id']) ? $attributes['post_id'] : null;
    if (! $id) {
        return '<p><i class="fas fa-exclamation-triangle"></i> ' . _t8("文章展示模块：请设置文章ID") . '</p>';
    }
    $post_type = get_post_type($id);
    $class     = 'wp-block-pandastudio-single';
    if (isset($attributes['align'])) {
        $class .= " align{$attributes['align']}";
    }
    switch ($post_type) {
        case 'post':
            $single_post = get_post($id);
            $coverImg    = get_the_post_thumbnail_url($id);
            $href        = get_the_permalink($id);
            $title       = get_the_title($id);
            $date        = get_the_time('Y-n-j', $id);
            $likes       = get_post_meta($single_post->ID, 'bigfa_ding', true) ? get_post_meta($single_post->ID, 'bigfa_ding', true) : "0";
            $comments    = $single_post->comment_count;
            $result      = "
<div class='" . $class . "' posttype='post'><div class='single-wrapper'><a class='cover' style='background-image:url(" . $coverImg . ")' href='" . $href . "'></a><div class='single-meta'><a class='post-title' href='" . $href . "'><h4>" . $title . "</h4></a><div class='summary'><span class='date'><i class='far fa-clock pandastudio-icons-clock'></i>
" . $date . "
</span><span class='likes'><i class='fas fa-heart pandastudio-icons-heart'></i>
" . $likes . "
</span><span class='comments'><i class='fas fa-comments pandastudio-icons-comment'></i>
" . $comments . "
</span></div></div></div></div>
";
            break;
        case 'gallery':
            $single_post         = get_post($id);
            $meta_gallery_images = get_post_meta($id, "gallery_images", true);
            $coverImg            = $meta_gallery_images ? $meta_gallery_images[0] : '';
            $href                = get_the_permalink($id);
            $title               = get_the_title($id);
            $date                = get_the_time('Y-n-j', $id);
            $likes               = get_post_meta($single_post->ID, 'bigfa_ding', true) ? get_post_meta($single_post->ID, 'bigfa_ding', true) : "0";
            $comments            = $single_post->comment_count;
            $result              = "
<div class='" . $class . "' posttype='post'><div class='single-wrapper'><a class='cover' style='background-image:url(" . $coverImg . ")' href='" . $href . "'></a><div class='single-meta'><a class='post-title' href='" . $href . "'><h4>" . $title . "</h4></a><div class='summary'><span class='date'><i class='far fa-clock'></i>
" . $date . "
</span><span class='likes'><i class='fas fa-heart'></i>
" . $likes . "
</span><span class='comments'><i class='fas fa-comments'></i>
" . $comments . "
</span></div></div></div></div>
";
            break;
        default:
            $result = '<p><i class="fas fa-exclamation-triangle"></i> ' . _t8("{{1}}类型不支持使用模块展示！", $post_type) . '</p>';
            break;
    }
    return $result;
}
function pandastudio_block_render_gallery($attributes)
{
    $id         = wp_unique_id('bootstrap-carousel-');
    $images     = isset($attributes["images"]) ? $attributes["images"] : [];
    $indicators = "";
    $items      = "";
    for ($i = 0; $i < count($images); $i++) {
        $indicators = $i == 0 ?
            $indicators . '<li data-target="#' . $id . '" data-slide-to="' . $i . '" class="active"></li>
'
            :
            $indicators . '<li data-target="#' . $id . '" data-slide-to="' . $i . '"></li>
';
        $items = $i == 0 ?
            $items . '<div class="item active"><div class="img_wrapper"><img src="' . $images[ $i ] . '"></div></div>'
            :
            $items . '<div class="item"><div class="img_wrapper"><img src="' . $images[ $i ] . '"></div></div>';
    }
    $result = '
<div id="' . $id . '" class="gallery carousel slide" data-ride="carousel"><!-- Indicators --><ol class="carousel-indicators">
' . $indicators . '
</ol><!-- Wrapper for slides --><div class="carousel-inner" role="listbox">
' . $items . '
</div><!-- Controls --><a class="left carousel-control" href="#' . $id . '" role="button" data-slide="prev"><i class="fas fa-angle-left glyphicon-chevron-left"></i><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#' . $id . '" role="button" data-slide="next"><i class="fas fa-angle-right glyphicon-chevron-right"></i><span class="sr-only">Next</span></a></div>
';
    return $result;
}
function pandastudio_block_render_microblog($attributes)
{
    $title  = isset($attributes['title']) ? $attributes['title'] : '';
    $number = isset($attributes['number']) ? max(1, intval($attributes['number'])) : 5;

    $html = '<div class="wp-block-pandastudio-microblog pf_microblog">';
    if ($title) {
        $html .= '<h2 class="widgettitle">' . esc_html($title) . '</h2>';
    }
    $html .= '<ul>';
    $query = new WP_Query(array(
        'post_type'      => 'microblog',
        'posts_per_page' => $number,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ));
    while ($query->have_posts()) {
        $query->the_post();
        $html .= '<li>';
        $html .= '<div class="date silver-color"><span>' . esc_html(get_the_time('Y-n-j H:i')) . '</span></div>';
        $html .= '<div class="main"><p>' . wp_kses_post(get_the_content()) . '</p></div>';
        $html .= '</li>';
    }
    wp_reset_postdata();
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}
function pandastudio_block_render_hotposts($attributes)
{
    $title  = isset($attributes['title']) ? $attributes['title'] : '';
    $filter = isset($attributes['filter']) ? $attributes['filter'] : 'most_likes';
    $number = isset($attributes['number']) ? max(1, intval($attributes['number'])) : 5;

    $query_args = array(
        'post_password'       => '',
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $number,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    );
    if ('most_likes' === $filter) {
        $query_args['meta_key'] = 'bigfa_ding';
        $query_args['orderby']  = 'meta_value_num';
    } else {
        $query_args['orderby'] = 'comment_count';
    }

    $html = '<div class="wp-block-pandastudio-hotposts pf_hotposts">';
    if ($title) {
        $html .= '<h2 class="widgettitle">' . esc_html($title) . '</h2>';
    }
    $html .= '<ul>';
    $query = new WP_Query($query_args);
    while ($query->have_posts()) {
        $query->the_post();
        $id        = get_the_ID();
        $thumb     = get_the_post_thumbnail_url($id) ?: '';
        $permalink = get_the_permalink($id);
        $title_text = get_the_title($id);
        $likes      = (int) get_post_meta($id, 'bigfa_ding', true);
        $comments   = (int) get_post($id)->comment_count;
        $cover_style = $thumb ? " style='background-image:url(" . esc_url($thumb) . ")'" : '';
        $html .= '<li>';
        $html .= "<div class='single-wrapper clearfix'>";
        $html .= "<a class='cover' href='" . esc_url($permalink) . "'" . $cover_style . "></a>";
        $html .= "<div class='meta'>";
        $html .= "<a class='post-title' href='" . esc_url($permalink) . "'><h4>" . wp_kses_post($title_text) . "</h4></a>";
        $html .= "<div class='summary'><span class='likes'><i class='fas fa-heart'></i>" . $likes . "</span>";
        $html .= "<span class='comments'><i class='fas fa-comments'></i>" . $comments . "</span></div>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= '</li>';
    }
    wp_reset_postdata();
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}
function _t8($arg)
{
    $arguments = is_array($arg) ? $arg : func_get_args();
    $express   = $arguments[0];
    for ($i = 1; $i < count($arguments); $i++) {
        $express = str_replace("{{" . $i . "}}", $arguments[ $i ], $express);
    }
    $express = preg_replace('/{{(.+?)}}/', '', $express);
    return $express;
}

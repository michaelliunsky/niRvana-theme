<?php

if (function_exists('register_block_type')) {
    add_theme_support('align-wide');
    add_filter('block_categories_all', function ($categories, $post) {
        return array_merge(
            $categories,
            array(
array(
'slug' => 'pandastudio-block-category','title' => 'PANDA Studio UI 样式','icon'  => 'dashicons-admin-appearance',),)
        );
    }, 10, 2);
    add_action('init', function () {
        wp_register_script(
            'pandastudio-blocks',
            get_stylesheet_directory_uri().'/pandastudio_plugins/blocks/build/index.js',
            array('wp-blocks', 'wp-element', 'wp-editor'),
            filemtime(__DIR__.'/build/index.js')
        );
        $blockNames = ['tips'];
        foreach ($blockNames as $key => $name) {
            register_block_type('pandastudio/'.$name, array(
            'editor_script' => 'pandastudio-blocks',));
        }register_block_type('pandastudio/single', array(
        'editor_script' => 'pandastudio-block-single','attributes' => array(
        'post_id' => array(
        'type' => 'number',),'align' => array(
        'type' => 'string','enum' => array( 'center', 'left', 'right', 'wide', 'full', '' ),)
        ),'render_callback' => 'pandastudio_block_render_single',));
        register_block_type('pandastudio/gallery', array(
        'editor_script' => 'pandastudio-block-gallery','attributes' => array(
        'images' => array(
        'type' => 'object', 				)
        ),'render_callback' => 'pandastudio_block_render_gallery',));
    });
    add_action('enqueue_block_editor_assets', function () {
        wp_enqueue_style(
            'pandastudio-block-styles',
            get_stylesheet_directory_uri().'/pandastudio_plugins/blocks/build/style.css',
            false,
            filemtime(__DIR__.'/build/style.css'),
            'all'
        );
    });
}function pandastudio_block_render_single($attributes)
{
    $id = isset($attributes['post_id']) ? $attributes['post_id'] : null;
    if (!$id) {
        return '<p><i class="fas fa-exclamation-triangle"></i> '._t8("文章展示模块：请设置文章ID").'</p>';
    }$post_type = get_post_type($id);
    $class = 'wp-block-pandastudio-single';
    if (isset($attributes['align'])) {
        $class .= " align{$attributes['align']}";
    }switch ($post_type) {
        case 'post':
            $single_post = get_post($id);
            $coverImg = get_the_post_thumbnail_url($id);
            $href = get_the_permalink($id);
            $title = get_the_title($id);
            $date = get_the_time('Y-n-j', $id);
            $likes = get_post_meta($single_post->ID, 'bigfa_ding', true) ? get_post_meta($single_post->ID, 'bigfa_ding', true) : "0" ;
            $comments = $single_post->comment_count;
            $result = "
<div class='".$class."' posttype='post'><div class='single-wrapper'><a class='cover' style='background-image:url(".$coverImg.")' href='".$href."'></a><div class='single-meta'><a class='post-title' href='".$href."'><h4>".$title."</h4></a><div class='summary'><span class='date'><i class='far fa-clock pandastudio-icons-clock'></i>
".$date."
</span><span class='likes'><i class='fas fa-heart pandastudio-icons-heart'></i>
".$likes."
</span><span class='comments'><i class='fas fa-comments pandastudio-icons-comment'></i>
".$comments."
</span></div></div></div></div>
";
            break;
        case 'gallery':
            $single_post = get_post($id);
            $meta_gallery_images = get_post_meta($id, "gallery_images", true);
            $coverImg = $meta_gallery_images ? $meta_gallery_images[0] : '';
            $href = get_the_permalink($id);
            $title = get_the_title($id);
            $date = get_the_time('Y-n-j', $id);
            $likes = get_post_meta($single_post->ID, 'bigfa_ding', true) ? get_post_meta($single_post->ID, 'bigfa_ding', true) : "0" ;
            $comments = $single_post->comment_count;
            $result = "
<div class='".$class."' posttype='post'><div class='single-wrapper'><a class='cover' style='background-image:url(".$coverImg.")' href='".$href."'></a><div class='single-meta'><a class='post-title' href='".$href."'><h4>".$title."</h4></a><div class='summary'><span class='date'><i class='far fa-clock'></i>
".$date."
</span><span class='likes'><i class='fas fa-heart'></i>
".$likes."
</span><span class='comments'><i class='fas fa-comments'></i>
".$comments."
</span></div></div></div></div>
";
            break;
        default:
            $result = '<p><i class="fas fa-exclamation-triangle"></i> '._t8("{{1}}类型不支持使用模块展示！", $post_type).'</p>';
            break;
    }return $result;
}function pandastudio_block_render_gallery($attributes)
{
    $id = "bootstrap-carousel-".rand();
    $images = isset($attributes["images"]) ? $attributes["images"] : [];
    $indicators = "";
    $items = "";
    for ($i = 0; $i < count($images); $i++) {
        $indicators = $i == 0 ?
        $indicators.'<li data-target="#'.$id.'" data-slide-to="'.$i.'" class="active"></li>
'
        :
        $indicators.'<li data-target="#'.$id.'" data-slide-to="'.$i.'"></li>
';
        $items = $i == 0 ?
        $items.'<div class="item active"><div class="img_wrapper"><img src="'.$images[$i].'"></div></div>'
        :
        $items.'<div class="item"><div class="img_wrapper"><img src="'.$images[$i].'"></div></div>';
    }$result = '
<div id="'.$id.'" class="gallery carousel slide" data-ride="carousel"><!-- Indicators --><ol class="carousel-indicators">
'.$indicators.'
</ol><!-- Wrapper for slides --><div class="carousel-inner" role="listbox">
'.$items.'
</div><!-- Controls --><a class="left carousel-control" href="#'.$id.'" role="button" data-slide="prev"><i class="fas fa-angle-left glyphicon-chevron-left"></i><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#'.$id.'" role="button" data-slide="next"><i class="fas fa-angle-right glyphicon-chevron-right"></i><span class="sr-only">Next</span></a></div>
';
    return $result;
}function _t8($arg)
{
    $arguments = is_array($arg) ? $arg : func_get_args();
    $express = $arguments[0];
    for ($i = 1; $i < count($arguments); $i++) {
        $express = str_replace("{{".$i."}}", $arguments[$i], $express);
    }$express = preg_replace('/{{(.+?)}}/', '', $express);
    return $express;
}function _t8n($arg)
{
    $arguments = is_array($arg) ? $arg : func_get_args();
    $single = $arguments[0];
    $multiple = $arguments[1];
    $number = $arguments[2];
    $newArgs = [];
    $express = $number <= 1 ? $single : $multiple;
    $newArgs[] = $express;
    for ($i = 2; $i < count($arguments); $i++) {
        $newArgs[] = $arguments[$i];
    }return _t8($newArgs);
}function _et8($arg)
{
    $arguments = is_array($arg) ? $arg : func_get_args();
    echo _t8($arguments);
}function _et8n($arg)
{
    $arguments = is_array($arg) ? $arg : func_get_args();
    echo _t8n($arguments);
}

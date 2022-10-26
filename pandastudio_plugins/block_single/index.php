<?php
function register_pandastudio_block_single() {wp_register_script('pandastudio-block-single',get_stylesheet_directory_uri().'/pandastudio_plugins/block_single/block.build.js',array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ));register_block_type( 'pandastudio/single', array('editor_script' => 'pandastudio-block-single','attributes' => array('post_id' => array('type' => 'number',),'align' => array('type' => 'string','enum' => array( 'center', 'left', 'right', 'wide', 'full', '' ),)),'render_callback' => 'pandastudio_block_render_single',) );}function pandastudio_block_render_single($attributes) {$id = $attributes['post_id'];if (!$id) {return '<p><i class="fas fa-exclamation-triangle"></i> 文章展示模块：请设置文章ID！</p>';}$post_type = get_post_type($id);$class = 'wp-block-pandastudio-single';if ( $attributes['align'] ) {$class .= " align{$attributes['align']}";}switch ($post_type) {case 'post':
$single_post = get_post($id);$coverImg = get_the_post_thumbnail_url($id);$href = get_the_permalink($id);$title = get_the_title($id);$date = get_the_time('Y-n-j',$id);$likes = get_post_meta($single_post->ID,'bigfa_ding',true) ? get_post_meta($single_post->ID,'bigfa_ding',true) : "0" ;$comments = $single_post->comment_count;$result = "
<div class='".$class."' posttype='post'><div class='single-wrapper'><a class='cover' style='background-image:url(".$coverImg.")' href='".$href."'></a><div class='single-meta'><a class='post-title' href='".$href."'><h4>".$title."</h4></a><div class='summary'><span class='date'><i class='far fa-clock'></i>
".$date."
</span><span class='likes'><i class='fas fa-heart'></i>
".$likes."
</span><span class='comments'><i class='fas fa-comments'></i>
".$comments."
</span></div></div></div></div>
";break;case 'gallery':
$single_post = get_post($id);$meta_gallery_images = get_post_meta($id, "gallery_images", true);$coverImg = $meta_gallery_images ? $meta_gallery_images[0] : '';$href = get_the_permalink($id);$title = get_the_title($id);$date = get_the_time('Y-n-j',$id);$likes = get_post_meta($single_post->ID,'bigfa_ding',true) ? get_post_meta($single_post->ID,'bigfa_ding',true) : "0" ;$comments = $single_post->comment_count;$result = "
<div class='".$class."' posttype='post'><div class='single-wrapper'><a class='cover' style='background-image:url(".$coverImg.")' href='".$href."'></a><div class='single-meta'><a class='post-title' href='".$href."'><h4>".$title."</h4></a><div class='summary'><span class='date'><i class='far fa-clock'></i>
".$date."
</span><span class='likes'><i class='fas fa-heart'></i>
".$likes."
</span><span class='comments'><i class='fas fa-comments'></i>
".$comments."
</span></div></div></div></div>
";break;default:
$result = '<p><i class="fas fa-exclamation-triangle"></i> '.$post_type.'类型不支持使用模块展示！</p>';break;}return $result;}if (function_exists('register_block_type')) {add_action( 'init', 'register_pandastudio_block_single' );}?><?php
?>
<?php

function register_pandastudio_block_gallery()
{
    wp_register_script('pandastudio-block-gallery', get_stylesheet_directory_uri().'/pandastudio_plugins/block_gallery/block.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ));
    register_block_type('pandastudio/gallery', array('editor_script' => 'pandastudio-block-gallery','attributes' => array('images' => array('type' => 'array',)),'render_callback' => 'pandastudio_block_render_gallery',));
}function pandastudio_block_render_gallery($attributes)
{
    $id = "bootstrap-carousel-".rand();
    $images = $attributes["images"];
    $indicators = "";
    $items = "";
    for ($i=0; $i < count($images); $i++) {
        $indicators = $i==0 ?
        $indicators.'<li data-target="#'.$id.'" data-slide-to="'.$i.'" class="active"></li>'
        :
        $indicators.'<li data-target="#'.$id.'" data-slide-to="'.$i.'"></li>';
        $items = $i==0 ?
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
}if (function_exists('register_block_type')) {
    add_action('init', 'register_pandastudio_block_gallery');
}

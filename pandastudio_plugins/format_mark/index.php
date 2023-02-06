<?php

function register_pandastudio_block_format_mark()
{
    wp_register_script('pandastudio-block-format-mark-js', get_stylesheet_directory_uri().'/pandastudio_plugins/format_mark/block.build.js', array( 'wp-rich-text' ));
}add_action('init', 'register_pandastudio_block_format_mark');
function register_pandastudio_block_format_mark_enqueue_assets_editor()
{
    wp_enqueue_script('pandastudio-block-format-mark-js');
}add_action('enqueue_block_editor_assets', 'register_pandastudio_block_format_mark_enqueue_assets_editor');

<?php

function register_pandastudio_block_format_description()
{
    wp_register_script('pandastudio-block-format-description-js', get_stylesheet_directory_uri().'/pandastudio_plugins/format_description/block.build.js', array( 'wp-rich-text' ));
}add_action('init', 'register_pandastudio_block_format_description');
function register_pandastudio_block_format_description_enqueue_assets_editor()
{
    wp_enqueue_script('pandastudio-block-format-description-js');
}add_action('enqueue_block_editor_assets', 'register_pandastudio_block_format_description_enqueue_assets_editor');

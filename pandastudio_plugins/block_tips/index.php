<?php

function register_pandastudio_block_tips()
{
    wp_register_script('pandastudio-block-tips', get_stylesheet_directory_uri().'/pandastudio_plugins/block_tips/block.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ));
    register_block_type('pandastudio/tips', array('editor_script' => 'pandastudio-block-tips',));
}if (function_exists('register_block_type')) {
    add_action('init', 'register_pandastudio_block_tips');
}

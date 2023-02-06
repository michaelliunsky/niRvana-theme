<?php

function register_pandastudio_block_dropdown()
{
    wp_register_script('pandastudio-block-dropdown', get_stylesheet_directory_uri().'/pandastudio_plugins/block_dropdown/block.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ));
    register_block_type('pandastudio/dropdown', array('editor_script' => 'pandastudio-block-dropdown',));
}if (function_exists('register_block_type')) {
    add_action('init', 'register_pandastudio_block_dropdown');
}

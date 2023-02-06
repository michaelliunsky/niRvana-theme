<?php

function register_pandastudio_block_collapse()
{
    wp_register_script('pandastudio-block-collapse', get_stylesheet_directory_uri().'/pandastudio_plugins/block_collapse/block.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ));
    register_block_type('pandastudio/collapse', array('editor_script' => 'pandastudio-block-collapse',));
}if (function_exists('register_block_type')) {
    add_action('init', 'register_pandastudio_block_collapse');
}

<?php

function register_pandastudio_block_modal()
{
    wp_register_script('pandastudio-block-modal', get_stylesheet_directory_uri().'/pandastudio_plugins/block_modal/block.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' ));
    register_block_type('pandastudio/modal', array('editor_script' => 'pandastudio-block-modal',));
}if (function_exists('register_block_type')) {
    add_action('init', 'register_pandastudio_block_modal');
}

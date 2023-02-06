<?php
function nirvana_block_editor_styles()
{
    wp_enqueue_style('nirvana-block-editor-styles', get_theme_file_uri('/pandastudio_plugins/block_editor_styles/css/style-editor-bondle.css'), false, false, 'all');
    wp_enqueue_style('nirvana-fontawesome-font-styles', get_theme_file_uri('/pandastudio_plugins/block_editor_styles/css/fontawesome.css'), false, false, 'all');
}add_action('enqueue_block_editor_assets', 'nirvana_block_editor_styles');
function pandastudio_block_category($categories, $post)
{
    return array_merge($categories, array(array('slug' => 'pandastudio-block-category','title' => 'PANDA Studio UI 样式','icon'  => 'dashicons-admin-appearance',),));
}if (function_exists('register_block_type')) {
    add_theme_support('align-wide');
    add_filter('block_categories', 'pandastudio_block_category', 10, 2);
}?><?php
?>
<?php
function pandastudio_image_border_radius_and_shadow_script() {wp_enqueue_script('pandastudio-image-script',get_stylesheet_directory_uri().'/pandastudio_plugins/block_image/index.js',array( 'wp-blocks'));}add_action( 'enqueue_block_editor_assets', 'pandastudio_image_border_radius_and_shadow_script' );
?>
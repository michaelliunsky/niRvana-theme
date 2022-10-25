<?php

function register_pandastudio_block_youku() {
	wp_register_script(
		'pandastudio-block-youku',
		get_stylesheet_directory_uri().'/pandastudio_plugins/block_youku/block.build.js',
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'underscore' )
	);

	wp_register_style(
		'pandastudio-block-youku',
		get_stylesheet_directory_uri().'/pandastudio_plugins/block_youku/editor-style.css',
		array( 'wp-edit-blocks' )
	);

	register_block_type( 'pandastudio/youku', array(
		'editor_script' => 'pandastudio-block-youku',
		'editor_style'  => 'pandastudio-block-youku',
	) );
}


if (function_exists('register_block_type')) {
	add_action( 'init', 'register_pandastudio_block_youku' );
}

add_action( 'init', function() use($theme_version, $theme_uri) {
	wp_register_style(
		'pandastudio-block-frontend-youku',
		get_stylesheet_directory_uri().'/pandastudio_plugins/block_youku/style.css',
		array(),
		$theme_version
	);  
	if ( !is_admin() && !is_login_page() ) {
		wp_enqueue_style( 'pandastudio-block-frontend-youku' );  
	}
}, 0);

?>
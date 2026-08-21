<!DOCTYPE html>
<html <?php language_attributes() ?> >

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<?php if ( _opt( 'enable_dark_mode' ) ) : ?>
	<script>
	(function(){try{var p=localStorage.getItem('nirvana-theme');if(p!=='light'&&p!=='dark'&&p!=='system')p='system';var d=p==='dark'||(p==='system'&&window.matchMedia('(prefers-color-scheme: dark)').matches);document.documentElement.classList.toggle('night',d);document.documentElement.style.colorScheme=d?'dark':'light';}catch(e){}})();
	</script>
	<?php endif; ?>
	<link rel='stylesheet' id='rpi-css' href='<?php echo get_stylesheet_directory_uri(); ?>/extend/css/style.css'>
	<?php wp_head(); ?>
</head>

<body class="black-color">
	<?php wp_body_open(); ?>
	<div id="wrapper" <?php body_class(); ?>>
		<?php include('assets/template/nav-main.php');?>
		<div id="main">

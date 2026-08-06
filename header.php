<!DOCTYPE html>
<html <?php language_attributes() ?> >

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
	<link rel='stylesheet' id='rpi-css' href='<?php echo get_stylesheet_directory_uri(); ?>/extend/css/style.css'>
	<?php wp_head(); ?>
</head>

<body class="black-color<?php echo (isset($_COOKIE['night']) && $_COOKIE['night'] === '1') ? ' night' : ''; ?>">
	<?php wp_body_open(); ?>
	<div id="wrapper" <?php body_class(); ?>>
		<?php include('assets/template/nav-main.php');?>
		<div id="main">
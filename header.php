<!DOCTYPE html>
<html <?php language_attributes() ?> >

<head>
    <title><?php wp_title('&#8211;', true, 'right'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,viewport-fit=cover" />
    <meta name="renderer" content="webkit"><?php wp_head(); ?>
    <link rel='stylesheet' id='rpi-css' href='<?php echo get_stylesheet_directory_uri(); ?>/extend/css/style.css' type='text/css' media='all' />
</head>

<body class="black-color <?php echo (isset($_COOKIE['night']) && $_COOKIE['night'] == '1' ? 'night' : ''); ?>">
    <div id="wrapper" class="<?php sandbox_body_class() ?> niRvana"><?php include('assets/template/nav-main.php');?>
        <div id="main">
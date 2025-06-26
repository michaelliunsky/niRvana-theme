<?php
$is_production = true;
$theme_uri = get_stylesheet_directory_uri();
$theme_version = wp_get_theme()->get('Version');
function is_login_page()
{
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}
if ($is_production) {
    add_action('wp_enqueue_scripts', function () use ($theme_version, $theme_uri) {
        wp_register_script(
            'niRvana',
            $theme_uri . '/assets/minify/app.min.js',
            array('jquery'),
            $theme_version
        );
        wp_register_style(
            'niRvana',
            $theme_uri . '/assets/minify/app.min.css',
            array(),
            $theme_version
        );
        if (!is_admin() && !is_login_page()) {
            wp_enqueue_script('niRvana');
            wp_enqueue_style('niRvana');
        }
    });
} else {
    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_script('jquery');
    });
    add_action('wp_head', function () use ($theme_uri) {
        echo '
<link rel="stylesheet" href="'.$theme_uri.'/assets/css/bootstrap.min.css"><link rel="stylesheet" href="'.$theme_uri.'/assets/css/bootstrap_xxs.css"><link rel="stylesheet" href="'.$theme_uri.'/assets/css/bootstrap_24.css"><link rel="stylesheet" href="'.$theme_uri.'/assets/css/bootstrap_xl.css"><link rel="stylesheet" href="'.$theme_uri.'/assets/css/notyf.min.css"><link rel="stylesheet" href="'.$theme_uri.'/assets/css/fontawesome.css"><link rel="stylesheet" href="'.$theme_uri.'/assets/css/jv-element.css"><link rel="stylesheet" href="'.$theme_uri.'/assets/css/style.css"><script src="'.$theme_uri.'/assets/js/jquery-2.1.0.min.js"></script><script src="'.$theme_uri.'/assets/js/jQuery.forceCache.js"></script><script src="'.$theme_uri.'/assets/js/jquery.mobile.custom.min.js"></script><script src="'.$theme_uri.'/assets/js/jquery-ui-custom-drag.min.js"></script><script src="'.$theme_uri.'/assets/js/jquery.custom-scrollbars.js"></script><script src="'.$theme_uri.'/assets/js/jquery.qrcode.min.js"></script><script src="'.$theme_uri.'/assets/js/notyf.min.js"></script><script src="'.$theme_uri.'/assets/js/bootstrap.min.js"></script><script src="'.$theme_uri.'/assets/js/color-thief.js"></script><script src="'.$theme_uri.'/assets/js/stackblur.min.js"></script><script src="'.$theme_uri.'/assets/js/circleMagic.min.js"></script><script src="'.$theme_uri.'/assets/js/mustache.min.js"></script><script src="'.$theme_uri.'/assets/js/pandaSlider.js"></script><script src="'.$theme_uri.'/assets/js/pandaTab.js"></script><script src="'.$theme_uri.'/assets/js/jquery.vue.js"></script><script src="'.$theme_uri.'/assets/js/jv-element.js"></script><script src="'.$theme_uri.'/assets/js/masonry.pkgd.min.js"></script><script src="'.$theme_uri.'/assets/js/jquery.imgcomplete.js"></script><script src="'.$theme_uri.'/assets/js/theme.js"></script>
';
    });
}
?>
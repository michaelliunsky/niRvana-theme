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
            // WP 核心 jQuery 无冲突模式不提供全局 $, 为主题脚本提供别名 (与旧版内嵌 jQuery 行为一致)
            wp_add_inline_script('jquery', 'window.$ = window.jQuery;');
        }
    });
} else {
    // 开发模式: 逐文件加载. 全部脚本依赖 WP 核心 jQuery 3, 由 wp_enqueue_script 队列保证顺序.
    $dev_scripts = array(
        'assets/js/jQuery.forceCache.js',
        'assets/js/jquery.custom-scrollbars.js',
        'assets/js/vendor/jquery.qrcode.min.js',
        'assets/js/pdmessage.js',
        'assets/js/vendor/bootstrap.min.js',
        'assets/js/vendor/color-thief.js',
        'assets/js/vendor/stackblur.min.js',
        'assets/js/vendor/circleMagic.min.js',
        'assets/js/vendor/mustache.min.js',
        'assets/js/pandaSlider.js',
        'assets/js/pandaTab.js',
        'assets/js/jquery.vue.js',
        'assets/js/jv-element.js',
        'assets/js/user-center-login.js',
        'assets/js/vendor/masonry.pkgd.min.js',
        'assets/js/jquery.imgcomplete.js',
        'assets/js/vendor/highlight.min.js',
        'assets/js/vendor/highlightjs-line-numbers.js',
        'assets/js/theme.js'
    );
    $dev_styles = array(
        'assets/css/vendor/bootstrap.min.css',
        'assets/css/vendor/bootstrap_xxs.css',
        'assets/css/vendor/bootstrap_24.css',
        'assets/css/vendor/bootstrap_xl.css',
        'assets/css/pdmessage-my.css',
        'assets/css/fontawesome.css',
        'assets/css/jv-element.css',
        'assets/css/user-center-login.css',
        'assets/css/style.css',
        'assets/css/highlightjs.css'
    );
    add_action('wp_enqueue_scripts', function () use ($theme_version, $theme_uri, $dev_scripts, $dev_styles) {
        wp_enqueue_script('jquery');
        // WP 核心 jQuery 无冲突模式不提供全局 $, 为主题脚本提供别名
        wp_add_inline_script('jquery', 'window.$ = window.jQuery;');
        foreach ($dev_scripts as $i => $file) {
            wp_enqueue_script('nirvana-dev-' . $i, $theme_uri . '/' . $file, array('jquery'), $theme_version);
        }
        foreach ($dev_styles as $i => $file) {
            wp_enqueue_style('nirvana-dev-css-' . $i, $theme_uri . '/' . $file, array(), $theme_version);
        }
    });
}

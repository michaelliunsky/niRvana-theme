<?php
add_action('rest_api_init', function () {
    register_rest_route('pandastudio/framework', '/get_option/', array(
        'methods' => 'POST',
        'callback' => 'get_option_by_RestAPI',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));
    register_rest_route('pandastudio/framework', '/update_option/', array(
        'methods' => 'POST',
        'callback' => 'update_option_by_RestAPI',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));
    register_rest_route('pandastudio/framework', '/wp_query/', array(
        'methods' => 'POST',
        'callback' => 'wp_query_by_RestAPI',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));
});

function get_option_by_RestAPI($data)
{
    $dataArray = json_decode($data->get_body(), true);
    if (!is_array($dataArray) || count($dataArray) < 1) {
        return array('error' => '数据格式不正确或为空！');
    }
    $return = array();
    foreach ($dataArray as $option_name => $value) {
        $return[$option_name] = get_option($option_name) ? get_option($option_name) : "";
    }
    return $return;
}

function update_option_by_RestAPI($data)
{
    if (current_user_can('manage_options')) {
        $dataArray = json_decode($data->get_body(), true);
        foreach ($dataArray as $option_name => $value) {
            update_option($option_name, $value);
        }
        return array('state' => true);
    } else {
        return array('state' => false, 'error' => 'PANDA Studio framework 无法执行此操作，原因：您没有进行此操作的权限');
    }
}

function wp_query_by_RestAPI($data)
{
    $dataArray = json_decode($data->get_body(), true);
    if (current_user_can('manage_options')) {
        $keyword = $dataArray['keyword'];
        if (gettype($keyword) == 'integer') {
            $args = array('post_type' => 'any', 'p' => $keyword);
        } else {
            $args = array('post_type' => 'any', 's' => $keyword);
        }
        $query = new WP_Query($args);
        $result = array();
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $result[] = array('label' => get_the_title(), 'value' => get_the_ID());
            }
            wp_reset_postdata();
        }
        return $result;
    } else {
        return array('label' => '无权限', 'value' => '0');
    }
}

add_action('admin_menu', 'add_option_json_page');
function add_option_json_page()
{
    add_menu_page(
        'setting',
        '主题设置',
        'manage_options',
        'pandastudio_framework_options',
        'pandastudio_framework_create_json_option_page',
        'dashicons-admin-customizer',
        60
    );
}

add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'toplevel_page_pandastudio_framework_options') {
        return;
    }
    wp_enqueue_media();
    $admin_asset = require get_template_directory() . '/pandastudio_plugins/blocks/build/admin.asset.php';
    wp_enqueue_script(
        'nirvana-admin-settings',
        get_stylesheet_directory_uri() . '/pandastudio_plugins/blocks/build/admin.js',
        $admin_asset['dependencies'],
        $admin_asset['version']
    );
    wp_enqueue_style(
        'nirvana-admin-settings',
        get_stylesheet_directory_uri() . '/pandastudio_framework/assets/css/admin.css',
        array(),
        wp_get_theme()->get('Version')
    );
    wp_localize_script('nirvana-admin-settings', 'nirvanaSettings', array(
        'blogName' => get_bloginfo('name'),
    ));
});

function pandastudio_framework_create_json_option_page()
{
    echo '<div id="nirvana-settings-root" class="wrap"></div>';
}

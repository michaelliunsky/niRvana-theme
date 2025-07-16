<?php

function pf_framework_enqueue_scripts()
{
    wp_register_script('pf_restapi', '');
    $pf_api_translation_array = array(
        'route' => esc_url_raw(rest_url()),
        'blog_name' => get_bloginfo('name'),
        'nonce' => wp_create_nonce('wp_rest'),
        'home' => home_url()
    );
    $theme = wp_get_theme();
    $pf_api_translation_array['theme'] = array(
        'uri' => $theme->get('ThemeURI'),
        'author_uri' => $theme->get('AuthorURI'),
        'name' => $theme->get('Name'),
        'version' => $theme->get('Version'),
        'route' => get_stylesheet_directory_uri()
    );
    if (is_admin()) {
        global $wpdb;
        $request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
        $request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
        $request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
        $request .= " ORDER BY term_id asc";
        $categorys = $wpdb->get_results($request);
        $categorySelector = array();
        foreach ($categorys as $category) {
            $categorySelector[] = array('label' => $category->name, 'value' => $category->term_id );
        }
        $pf_api_translation_array['categorySelector'] = $categorySelector;
    }
    $current_user = wp_get_current_user();
    $pf_api_translation_array['current_user'] = array(
        'logged_in' => is_user_logged_in(),
        'name' => $current_user->user_login,
        'email' => $current_user->user_email,
        'id' => $current_user->ID
    );
    $pf_api_translation_array = apply_filters('modify_pandastudio_translation_array', $pf_api_translation_array);
    wp_localize_script('pf_restapi', 'pandastudio_framework', $pf_api_translation_array);
    wp_enqueue_script('pf_restapi');
}
add_action('wp_enqueue_scripts', 'pf_framework_enqueue_scripts');
add_action('admin_enqueue_scripts', 'pf_framework_enqueue_scripts');
add_action('rest_api_init', function () {
    register_rest_route('pandastudio/framework', '/get_option_json/', array(
        'methods' => 'get',
        'callback' => 'get_option_json_by_RestAPI',
        'permission_callback' => '__return_true',
    ));
});
function get_option_json_by_RestAPI()
{
    $option_json_file = file_get_contents("option.json", 1);
    if (strlen($option_json_file) > 10) {
        $option_json = json_decode($option_json_file, true);
    } else {
        $option_json = array();
    }$option_json = apply_filters('modify_pandastudio_options', $option_json);
    return $option_json;
}add_action('rest_api_init', function () {
    register_rest_route('pandastudio/framework', '/get_posttype_and_meta_json/', array(
        'methods' => 'get',
        'callback' => 'get_posttype_and_meta_json_by_RestAPI',
        'permission_callback' => '__return_true',
    ));
});
function get_posttype_and_meta_json_by_RestAPI()
{
    $posttype_and_meta_json_file = file_get_contents("posttype_and_meta.json", 1);
    if (strlen($posttype_and_meta_json_file) > 10) {
        $posttype_and_meta_json = json_decode($posttype_and_meta_json_file, true);
    } else {
        $posttype_and_meta_json = array();
    }$posttype_and_meta_json = apply_filters('modify_pandastudio_posttype_and_meta', $posttype_and_meta_json);
    return $posttype_and_meta_json;
}$posttype_and_meta_file = file_get_contents("posttype_and_meta.json", 1);
if (strlen($posttype_and_meta_file) > 10) {
    $posttype_and_meta = get_posttype_and_meta_json_by_RestAPI();
    $myPostTypes = $posttype_and_meta['myPostTypes'];
    $meta_tabs = $posttype_and_meta['meta'];
    $meta_screens = array();
    foreach ($meta_tabs as $tab) {
        foreach ($tab['screen'] as $screen) {
            array_push($meta_screens, $screen);
        }
    }include_once('assets/template/posttype_json.php');
    include_once('assets/template/meta_rest.php');
}$option_file = file_get_contents("option.json", 1);
if (strlen($option_file) > 10) {
    include_once('assets/template/option_rest.php');
}

<?php

function add_baiduAudio_js()
{
    echo '<script src="' . get_stylesheet_directory_uri() . '/pandastudio_plugins/baiduAudio/baiduAudio.js"></script>';
    echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/pandastudio_plugins/baiduAudio/baiduAudio.css"/>';
}
add_action('wp_footer', 'add_baiduAudio_js');
add_filter('modify_pandastudio_translation_array', 'add_baiduAudio_translation_array');
function add_baiduAudio_translation_array($translation_array)
{
    $bd_response = get_cache('bd_audio_tok');
    if ($bd_response == false) {
        $bd_key = 'fKTeA2Gju6WsXMHWXvouDitG';
        $bd_secret = 'U01XiwxeOqd4YnYXGggCyveOtgfR9OyN';
        $bd_url = 'https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=' . $bd_key . '&client_secret=' . $bd_secret;
        $bd_response = file_get_contents($bd_url);
        $bd_response = json_decode($bd_response, true);
        if ($bd_response['access_token']) {
            set_cache('bd_audio_tok', ['access_token' => $bd_response['access_token'], 'session_key' => $bd_response['session_key']], $bd_response[expires_in] * 0.9);
        }
    }
    $translation_array['baiduAudio'] = array_merge(get_cache('bd_audio_tok'), array(	'spd' => get_option('baidu_ai_audio_spd') ? get_option('baidu_ai_audio_spd') : 5,'pit' => get_option('baidu_ai_audio_pit') ? get_option('baidu_ai_audio_pit') : 5,'per' => get_option('baidu_ai_audio_per') ? get_option('baidu_ai_audio_per') : 0,'enable' => get_option('baidu_ai_audio_enable') == 'checked'
            ));
    return $translation_array;
}
add_filter('modify_pandastudio_options', 'add_baiduAudio_to_options');
function add_baiduAudio_to_options($options)
{
    $new_file = file_get_contents("baiduAudio_option.json", 1);
    $new_arr = json_decode($new_file, true);
    $newOptions = array_merge($options, $new_arr);
    return $newOptions;
}

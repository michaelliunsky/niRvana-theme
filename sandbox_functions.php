<?php
/*
This file is part of SANDBOX.
SANDBOX is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.
SANDBOX is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with SANDBOX. If not, see http://www.gnu.org/licenses/.
*/
function sandbox_globalnav()
{
    if ($menu = str_replace(array( "\r", "\n", "\t" ), '', wp_list_pages('title_li=&sort_column=menu_order&echo=0'))) {
        $menu = '<ul>' . $menu . '</ul>';
    }$menu = '<div id="menu">' . $menu . "</div>\n";
    echo apply_filters('globalnav_menu', $menu);
}function sandbox_body_class($print = true)
{
    global $wp_query, $current_user;
    $c = array('wordpress');
    sandbox_date_classes(time(), $c);
    is_front_page() ? $c[] = 'home' : null;
    is_home() ? $c[] = 'blog' : null;
    is_archive() ? $c[] = 'archive' : null;
    is_date() ? $c[] = 'date' : null;
    is_search() ? $c[] = 'search' : null;
    is_paged() ? $c[] = 'paged' : null;
    is_attachment() ? $c[] = 'attachment' : null;
    is_404() ? $c[] = 'four04' : null;
    if (is_single()) {
        $postID = $wp_query->post->ID;
        the_post();
        $c[] = 'single postid-' . $postID;
        if (isset($wp_query->post->post_date)) {
            sandbox_date_classes(mysql2date('U', $wp_query->post->post_date), $c, 's-');
        }if ($cats = get_the_category()) {
            foreach ($cats as $cat) {
                $c[] = 's-category-' . $cat->slug;
            }
        }if ($tags = get_the_tags()) {
            foreach ($tags as $tag) {
                $c[] = 's-tag-' . $tag->slug;
            }
        }if (is_attachment()) {
            $mime_type = get_post_mime_type();
            $mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
            $c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace($mime_prefix, "", "$mime_type");
        }$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
        rewind_posts();
    } elseif (is_author()) {
        $author = $wp_query->get_queried_object();
        $c[] = 'author';
        $c[] = 'author-' . $author->user_nicename;
    } elseif (is_category()) {
        $cat = $wp_query->get_queried_object();
        $c[] = 'category';
        $c[] = 'category-' . $cat->slug;
    } elseif (is_tag()) {
        $tags = $wp_query->get_queried_object();
        $c[] = 'tag';
        $c[] = 'tag-' . $tags->slug;
    } elseif (is_page()) {
        $pageID = $wp_query->post->ID;
        $page_children = wp_list_pages("child_of=$pageID&echo=0");
        the_post();
        $c[] = 'page pageid-' . $pageID;
        $c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
        if ($page_children) {
            $c[] = 'page-parent';
        }if ($wp_query->post->post_parent) {
            $c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
        }if (is_page_template()) {
            $c[] = 'page-template page-template-' . str_replace('.php', '-php', get_post_meta($pageID, '_wp_page_template', true));
        }rewind_posts();
    } elseif (is_search()) {
        the_post();
        if (have_posts()) {
            $c[] = 'search-results';
        } else {
            $c[] = 'search-no-results';
        }rewind_posts();
    }if ($current_user->ID) {
        $c[] = 'loggedin';
    }if ((($page = $wp_query->get('paged')) || ($page = $wp_query->get('page'))) && $page > 1) {
        $page = intval($page);
        $c[] = 'paged-' . $page;
        if (is_single()) {
            $c[] = 'single-paged-' . $page;
        } elseif (is_page()) {
            $c[] = 'page-paged-' . $page;
        } elseif (is_category()) {
            $c[] = 'category-paged-' . $page;
        } elseif (is_tag()) {
            $c[] = 'tag-paged-' . $page;
        } elseif (is_date()) {
            $c[] = 'date-paged-' . $page;
        } elseif (is_author()) {
            $c[] = 'author-paged-' . $page;
        } elseif (is_search()) {
            $c[] = 'search-paged-' . $page;
        }
    }$c = join(' ', apply_filters('body_class', $c));
    return $print ? print($c) : $c;
}function sandbox_post_class($print = true)
{
    global $post, $sandbox_post_alt;
    $c = array( 'hentry', "p$sandbox_post_alt", $post->post_type, $post->post_status );
    $c[] = 'author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
    foreach ((array) get_the_category() as $cat) {
        $c[] = 'category-' . $cat->slug;
    }if (get_the_tags() == null) {
        $c[] = 'untagged';
    } else {
        foreach ((array) get_the_tags() as $tag) {
            $c[] = 'tag-' . $tag->slug;
        }
    }if ($post->post_password) {
        $c[] = 'protected';
    }sandbox_date_classes(mysql2date('U', $post->post_date), $c);
    if (++$sandbox_post_alt % 2) {
        $c[] = 'alt';
    }$c = join(' ', apply_filters('post_class', $c));
    return $print ? print($c) : $c;
}$sandbox_post_alt = 1;
function sandbox_comment_class($print = true)
{
    global $comment, $post, $sandbox_comment_alt;
    $c = array( $comment->comment_type );
    if ($comment->comment_type == 'comment') {
        $c[] = "c$sandbox_comment_alt";
    } else {
        $c[] = "t$sandbox_comment_alt";
    }if ($comment->user_id > 0) {
        $user = get_userdata($comment->user_id);
        $c[] = 'byuser comment-author-' . sanitize_title_with_dashes(strtolower($user->user_login));
        if ($comment->user_id === $post->post_author) {
            $c[] = 'bypostauthor';
        }
    }sandbox_date_classes(mysql2date('U', $comment->comment_date), $c, 'c-');
    if (++$sandbox_comment_alt % 2) {
        $c[] = 'alt';
    }$c = join(' ', apply_filters('comment_class', $c));
    return $print ? print($c) : $c;
}function sandbox_date_classes($t, &$c, $p = '')
{
    $t = $t + (get_option('gmt_offset') * 3600);
    $c[] = $p . 'y' . gmdate('Y', $t);
    $c[] = $p . 'm' . gmdate('m', $t);
    $c[] = $p . 'd' . gmdate('d', $t);
    $c[] = $p . 'h' . gmdate('H', $t);
}function sandbox_cats_meow($glue)
{
    $current_cat = single_cat_title('', false);
    $separator = "\n";
    $cats = explode($separator, get_the_category_list($separator));
    foreach ($cats as $i => $str) {
        if (strstr($str, ">$current_cat<")) {
            unset($cats[$i]);
            break;
        }
    }if (empty($cats)) {
        return false;
    }return trim(join($glue, $cats));
}function sandbox_tag_ur_it($glue)
{
    $current_tag = single_tag_title('', '', false);
    $separator = "\n";
    $tags = explode($separator, get_the_tag_list("", "$separator", ""));
    foreach ($tags as $i => $str) {
        if (strstr($str, ">$current_tag<")) {
            unset($tags[$i]);
            break;
        }
    }if (empty($tags)) {
        return false;
    }return trim(join($glue, $tags));
}function sandbox_commenter_link()
{
    $commenter = get_comment_author_link();
    if (ereg('<a[^>]* class=[^>]+>', $commenter)) {
        $commenter = ereg_replace('(<a[^>]* class=[\'"]?)', '\\1url ', $commenter);
    } else {
        $commenter = ereg_replace('(<a )/', '\\1class="url "', $commenter);
    }$avatar_email = get_comment_author_email();
    $avatar_size = apply_filters('avatar_size', '32');
    $avatar = str_replace("class='avatar", "class='photo avatar", get_avatar($avatar_email, $avatar_size));
    echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
}function sandbox_gallery($attr)
{
    global $post;
    if (isset($attr['orderby'])) {
        $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
        if (!$attr['orderby']) {
            unset($attr['orderby']);
        }
    }extract(shortcode_atts(array('orderby' => 'menu_order ASC, ID ASC','id' => $post->ID,'itemtag' => 'dl','icontag' => 'dt','captiontag' => 'dd','columns' => 3,'size' => 'thumbnail',), $attr));
    $id = intval($id);
    $orderby = addslashes($orderby);
    $attachments = get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");
    if (empty($attachments)) {
        return null;
    }if (is_feed()) {
        $output = "\n";
        foreach ($attachments as $id => $attachment) {
            $output .= wp_get_attachment_link($id, $size, true) . "\n";
        }return $output;
    }$listtag = tag_escape($listtag);
    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $output = apply_filters('gallery_style', "\n" . '<div class="gallery">', 9);
    foreach ($attachments as $id => $attachment) {
        $img_lnk = get_attachment_link($id);
        $img_src = wp_get_attachment_image_src($id, $size);
        $img_src = $img_src[0];
        $img_alt = $attachment->post_excerpt;
        if ($img_alt == null) {
            $img_alt = $attachment->post_title;
        }$img_rel = apply_filters('gallery_img_rel', 'attachment');
        $img_class = apply_filters('gallery_img_class', 'gallery-image');
        $output .= "\n\t" . '<' . $itemtag . ' class="gallery-item gallery-columns-' . $columns .'">';
        $output .= "\n\t\t" . '<' . $icontag . ' class="gallery-icon"><a href="' . $img_lnk . '" title="' . $img_alt . '" rel="' . $img_rel . '"><img src="' . $img_src . '" alt="' . $img_alt . '" class="' . $img_class . ' attachment-' . $size . '" /></a></' . $icontag . '>';
        if ($captiontag && trim($attachment->post_excerpt)) {
            $output .= "\n\t\t" . '<' . $captiontag . ' class="gallery-caption">' . $attachment->post_excerpt . '</' . $captiontag . '>';
        }$output .= "\n\t" . '</' . $itemtag . '>';
        if ($columns > 0 && ++$i % $columns == 0) {
            $output .= "\n</div>\n" . '<div class="gallery">';
        }
    }$output .= "\n</div>\n";
    return $output;
}function widget_sandbox_search($args)
{
    extract($args);
    $options = get_option('widget_sandbox_search');
    $title = empty($options['title']) ? __('Search', 'sandbox') : esc_attr($options['title']);
    $button = empty($options['button']) ? __('Find', 'sandbox') : esc_attr($options['button']);?><?php echo $before_widget ?><?php echo $before_title ?><label for="s"><?php echo $title ?></label><?php echo $after_title ?>
<form id="searchform" class="blog-search" method="get" action="<?php echo esc_url(home_url()) ?>">
    <div><input id="s" name="s" type="text" class="text" value="<?php the_search_query() ?>" size="10" tabindex="1" /><input type="submit" class="button" value="<?php echo $button ?>" tabindex="2" /></div>
</form><?php echo $after_widget ?><?php
}function widget_sandbox_search_control()
{
    $options = $newoptions = get_option('widget_sandbox_search');
    if ($_POST['search-submit']) {
        $newoptions['title'] = strip_tags(stripslashes($_POST['search-title']));
        $newoptions['button'] = strip_tags(stripslashes($_POST['search-button']));
    }if ($options != $newoptions) {
        $options = $newoptions;
        update_option('widget_sandbox_search', $options);
    }$title = esc_attr($options['title']);
    $button = esc_attr($options['button']);?>
<p><label for="search-title"><?php _e('Title:', 'sandbox') ?> <input class="widefat" id="search-title" name="search-title" type="text" value="<?php echo $title; ?>" /></label></p>
<p><label for="search-button"><?php _e('Button Text:', 'sandbox') ?> <input class="widefat" id="search-button" name="search-button" type="text" value="<?php echo $button; ?>" /></label></p><input type="hidden" id="search-submit" name="search-submit" value="1" /><?php
}function widget_sandbox_meta($args)
{
    extract($args);
    $options = get_option('widget_meta');
    $title = empty($options['title']) ? __('Meta', 'sandbox') : esc_attr($options['title']);?><?php echo $before_widget; ?><?php echo $before_title . $title . $after_title; ?>
<ul><?php wp_register() ?>
    <li><?php wp_loginout() ?></li><?php wp_meta() ?>
</ul><?php echo $after_widget; ?><?php
}function widget_sandbox_rsslinks($args)
{
    extract($args);
    $options = get_option('widget_sandbox_rsslinks');
    $title = empty($options['title']) ? __('RSS Links', 'sandbox') : esc_attr($options['title']);?><?php echo $before_widget; ?><?php echo $before_title . $title . $after_title; ?>
<ul>
    <li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo _wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('Posts RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All posts', 'sandbox') ?></a></li>
    <li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo _wp_specialchars(bloginfo('name'), 1) ?> <?php _e('Comments RSS feed', 'sandbox'); ?>" rel="alternate" type="application/rss+xml"><?php _e('All comments', 'sandbox') ?></a></li>
</ul><?php echo $after_widget; ?><?php
}function widget_sandbox_rsslinks_control()
{
    $options = $newoptions = get_option('widget_sandbox_rsslinks');
    if ($_POST['rsslinks-submit']) {
        $newoptions['title'] = strip_tags(stripslashes($_POST['rsslinks-title']));
    }if ($options != $newoptions) {
        $options = $newoptions;
        update_option('widget_sandbox_rsslinks', $options);
    }$title = esc_attr($options['title']);?>
<p><label for="rsslinks-title"><?php _e('Title:', 'sandbox') ?> <input class="widefat" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p><input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" /><?php
}function sandbox_widgets_init()
{
    if (!function_exists('register_sidebars')) {
        return;
    }$p = array('before_widget' => "\n\t\t\t" . '<li id="%1$s" class="widget %2$s">','after_widget' => "\n\t\t\t</li>\n",'before_title' => "\n\t\t\t\t". '<h3 class="widgettitle">','after_title' => "</h3>\n"
    );
    register_sidebars(2, $p);
}load_theme_textdomain('sandbox');
add_filter('archive_meta', 'wptexturize');
add_filter('archive_meta', 'convert_smilies');
add_filter('archive_meta', 'convert_chars');
add_filter('archive_meta', 'wpautop');?>
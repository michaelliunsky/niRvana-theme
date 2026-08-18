<?php

foreach ($myPostTypes['posttypes'] as $postType) {
    add_action(
        'init',
        function () use ($postType) {
            $post_type_name = __($postType['name'], 'niRvana');
            register_post_type($postType['type'], array(
                'labels' => array(
                    'name' => $post_type_name,
                    'singular_name' => $post_type_name,
                    'add_new' => sprintf(__('添加%s', 'niRvana'), $post_type_name),
                    'add_new_item' => sprintf(__('添加%s', 'niRvana'), $post_type_name),
                    'edit' => __('编辑', 'niRvana'),
                    'edit_item' => sprintf(__('编辑%s', 'niRvana'), $post_type_name),
                    'new_item' => sprintf(__('新%s', 'niRvana'), $post_type_name),
                    'view' => __('查看', 'niRvana'),
                    'view_item' => sprintf(__('查看%s', 'niRvana'), $post_type_name),
                    'search_items' => sprintf(__('搜索%s', 'niRvana'), $post_type_name),
                    'not_found' => sprintf(__('未找到%s', 'niRvana'), $post_type_name),
                    'not_found_in_trash' => sprintf(__('回收站未找到%s', 'niRvana'), $post_type_name),
                    'parent' => sprintf(__('%s父分类', 'niRvana'), $post_type_name)
                ),
                'public' => true,
                'menu_position' => 36,
                'supports' => $postType['supports'],
                'show_in_rest' => $postType['show_in_rest'] ?? false,
                'taxonomies' => array(),
                'menu_icon' => $postType['menu_icon'],
                'has_archive' => true
            ));
        }
    );
    add_filter('manage_'.$postType['type'].'_posts_columns', function ($columns) use ($postType) {
        foreach ($postType['unset_columns'] as $columnName) {
            unset($columns[$columnName]);
        }
        $new_columns = array();
        foreach ($postType['new_columns'] as $singleColumn) {
            $new_columns[$singleColumn['name']] = __($singleColumn['display'], 'niRvana');
        }
        $columns = array_merge($columns, $new_columns);

        if (isset($columns['tags'])) {
            $tags = $columns['tags'];
            unset($columns['tags']);
            $columns = array_merge($columns, $new_columns, array('tags'=>$tags));
        }
        if (isset($columns['date'])) {
            $date = $columns['date'];
            unset($columns['date']);
            $columns = array_merge($columns, $new_columns, array('date'=>$date));
        }
        return $columns;
    });
    if (!empty($postType['allow_categorys'])) {
        add_action('init', function () use ($postType) {
            register_taxonomy($postType['type'].'-category', $postType['type'], array(
                'labels' => array(
                    'name' => sprintf(__('%s分类目录', 'niRvana'), __($postType['name'], 'niRvana')),
                    'add_new_item' => __('添加新分类目录', 'niRvana'),
                    'new_item_name' => __('分类名称', 'niRvana')
                ),
                'show_ui' => true,
                'show_in_rest' => true,
                'show_tagcloud' => false,
                'hierarchical' => true,
            ));
        });
    }
    if (!empty($postType['allow_tags'])) {
        add_action('init', function () use ($postType) {
            register_taxonomy($postType['type'].'-tag', $postType['type'], array(
                'labels' => array(
                    'name' => sprintf(__('%s标签', 'niRvana'), __($postType['name'], 'niRvana')),
                    'add_new_item' => __('添加新标签', 'niRvana'),
                    'new_item_name' => __('标签名称', 'niRvana')
                ),
                'show_ui' => true,
                'show_in_rest' => true,
                'show_tagcloud' => true,
                'hierarchical' => false,
            ));
        });
    }
    if (!empty($postType['custom_taxonomies'])) {
        foreach ($postType['custom_taxonomies'] as $item) {
            add_action('init', function () use ($postType, $item) {
                register_taxonomy($item['taxonomy'], $postType['type'], array(
                    'labels' => array(
                        'name' => __($item['label_name'], 'niRvana'),
                        'add_new_item' => __($item['label_add_new_item'], 'niRvana'),
                        'new_item_name' => __($item['label_new_item_name'], 'niRvana')
                    ),
                    'show_ui' => $item['show_ui'],
                    'show_in_rest' => true,
                    'show_tagcloud' => $item['show_tagcloud'],
                    'hierarchical' => $item['hierarchical'],
                ));
            });
        }
    }
};
function custom_columns($column, $post_id)
{
    global $myPostTypes;
    $fillColumns = array();
    foreach ($myPostTypes['columns'] as $singleColumn) {
        $fillColumns[$singleColumn['name']] = array(
            'display' => $singleColumn['display'],
            'meta' => $singleColumn['meta'],
        );
    }
    if (!isset($fillColumns[$column])) {
        return;
    }
    $meta = get_post_meta($post_id, $fillColumns[$column]['meta'], true);
    switch ($fillColumns[$column]['display']) {
        case 'meta':
            echo $meta;
            break;
        case 'picture':
            echo '<div style="width:80px;height:80px;border-radius:4px;background:url('.$meta.') no-repeat center center / cover"></div>';
            break;
        case 'href':
            echo '<a href="'.$meta.'" target="_blank">'.$meta.'</a>';
            break;
        case 'taxonomy':
            $post = get_post($post_id);
            $post_type = $post->post_type;
            $term_list = get_the_term_list($post->ID, $post_type.'-category', '', '、', '');
            if ($term_list && !is_wp_error($term_list)) {
                $the_tags = apply_filters('the_tags', $term_list, '', '、', '', $post->ID);
                echo $the_tags;
            }
            break;
        case 'tag':
            $post = get_post($post_id);
            $post_type = $post->post_type;
            $term_list = get_the_term_list($post->ID, $post_type.'-tag', '', '、', '');
            if ($term_list && !is_wp_error($term_list)) {
                $the_tags = apply_filters('the_tags', $term_list, '', '、', '', $post->ID);
                echo $the_tags;
            }
            break;
        case 'pictures':
            foreach ((array) $meta as $pic) {
                echo '<div style="width:80px;height:80px;border-radius:4px;background:url('.$pic.') no-repeat center center / cover;display:inline-block;margin-right:5px"></div>';
            }
            break;
        case 'content':
            echo get_post_field('post_content', $post_id);
            break;
        default:
            break;
    }
}
add_action('manage_posts_custom_column', 'custom_columns', 10, 2);

<?php

error_reporting(0);
foreach ($myPostTypes['posttypes'] as $postType) {
    add_action(
        'init',
        function () use ($postType) {
            register_post_type($postType['type'], array('labels' => array('name' => $postType['name'],'singular_name' => ''.$postType['name'],'add_new' => '添加'.$postType['name'],'add_new_item' => '添加'.$postType['name'],'edit' => '编辑','edit_item' => '编辑'.$postType['name'],'new_item' => '新'.$postType['name'],'view' => '查看','view_item' => '查看'.$postType['name'],'search_items' => '搜索'.$postType['name'],'not_found' => '未找到'.$postType['name'],'not_found_in_trash' => '回收站未找到'.$postType['name'],'parent' => $postType['name'].'父分类'
            ),'public' => true,'menu_position' => 36,'supports' => $postType['supports'],'show_in_rest' => $postType['show_in_rest'],'taxonomies' => array(),'menu_icon' => $postType['menu_icon'],'has_archive' => true
            ));
        }
    );
    add_filter('manage_'.$postType['type'].'_posts_columns', function ($columns) use ($postType) {
        foreach ($postType['unset_columns'] as $columnName) {
            unset($columns[$columnName]);
        }
        $new_columns = array();
        foreach ($postType['new_columns'] as $singleColumn) {
            $new_columns[$singleColumn['name']] = $singleColumn['display'];
        }$columns = array_merge($columns, $new_columns);
        if (isset($columns['tags'])) {
            $tags = $columns['tags'];
            unset($columns['tags']);
            $columns = array_merge($columns, $new_columns, array('tags' => $tags));
        }
        if ($columns['date']) {
            $date = $columns['date'];
            unset($columns['date']);
            $columns = array_merge($columns, $new_columns, array('date' => $date));
        }return $columns;
    });
    if ($postType['allow_categorys']) {
        add_action('init', function () use ($postType) {
            register_taxonomy($postType['type'].'-category', $postType['type'], array('labels' => array('name' => $postType['name'].'分类目录','add_new_item' => '添加新分类目录','new_item_name' => '分类名称'
            ),'show_ui' => true,'show_in_rest' => true,'show_tagcloud' => false,'hierarchical' => true,));
        });
    }if (isset($postType['allow_tags'])) {
        add_action('init', function () use ($postType) {
            register_taxonomy($postType['type'].'-tag', $postType['type'], array('labels' => array('name' => $postType['name'].'标签','add_new_item' => '添加新标签','new_item_name' => '标签名称'
            ),'show_ui' => true,'show_in_rest' => true,'show_tagcloud' => true,'hierarchical' => false,));
        });
    }if ($postType['custom_taxonomies']) {
        foreach ($postType['custom_taxonomies'] as $item) {
            add_action('init', function () use ($postType, $item) {
                register_taxonomy($item['taxonomy'], $postType['type'], array('labels' => array('name' => $item['label_name'],'add_new_item' => $item['label_add_new_item'],'new_item_name' => $item['label_new_item_name']
                ),'show_ui' => $item['show_ui'],'show_in_rest' => true,'show_tagcloud' => $item['show_tagcloud'],'hierarchical' => $item['hierarchical'],));
            });
        }
    }
};
function custom_columns($column, $post_id)
{
    global $myPostTypes;
    $fillColumns = array();
    foreach ($myPostTypes['columns'] as $singleColumn) {
        $fillColumns[$singleColumn['name']] = array('display' => $singleColumn['display'],'meta' => $singleColumn['meta'],'eval' => $singleColumn['eval'],);
    }$meta = get_post_meta($post_id, $fillColumns[$column]['meta'], true);
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
            if (get_the_term_list($post->ID, $post_type.'-category')) {
                $the_tags = apply_filters('the_tags', get_the_term_list($post->ID, $post_type.'-category', '', '、', ''), '', '、', '', $post->ID);
                if (! is_wp_error($the_tags)) {
                    echo $the_tags;
                }
            }break;
        case 'tag':
            $post = get_post($post_id);
            $post_type = $post->post_type;
            if (get_the_term_list($post->ID, $post_type.'-tag')) {
                $the_tags = apply_filters('the_tags', get_the_term_list($post->ID, $post_type.'-tag', '', '、', ''), '', '、', '', $post->ID);
                if (! is_wp_error($the_tags)) {
                    echo $the_tags;
                }
            }break;
        case 'eval':
            eval($fillColumns[$column]['eval']);
            break;
        default:
    }
}add_action('manage_posts_custom_column', 'custom_columns', 10, 2);

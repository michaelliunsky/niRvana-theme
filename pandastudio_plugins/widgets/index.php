<?php
function unregister_default_widgets()
{
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Nav_Menu_Widget');
    unregister_widget('WP_Widget_Search');
    unregister_widget('WP_Widget_Categories');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_RSS');
    unregister_widget('WP_Widget_Calendar');
    unregister_widget('WP_Widget_Links');
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Tag_Cloud');
    unregister_widget('WP_Widget_Media_Video');
    unregister_widget('WP_Widget_Text');
    unregister_widget('WP_Widget_Media_Audio');
    unregister_widget('WP_Widget_Media_Gallery');
    unregister_widget('WP_Widget_Media_Image');
}add_action('widgets_init', 'unregister_default_widgets');
add_action('widgets_init', 'pf_user_info_load_widgets');
function pf_user_info_load_widgets()
{
    register_widget('pf_user_info_Widget');
}class pf_user_info_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array( 'classname' => 'pf_user_info', 'description' => '展示博主基本信息');
        $control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'pf_user_info-widget' );
        parent::__construct('pf_user_info-widget', '博主信息', $widget_ops, $control_ops);
    }public function widget($args, $instance)
    {
        extract($args);
        $name = apply_filters('widget_title', $instance['name']);
        $description = $instance['description'];
        $img = $instance['img'];
        echo $before_widget;?>
<div class="authorImg" style="background-image: url(<?php echo $img; ?>);"></div>
<div class="meta">
    <div class="name"><?php echo $name; ?></div>
    <div class="description"><?php echo $description; ?></div>
</div><?php
        echo $after_widget;
    }public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['name'] = $new_instance['name'];
        $instance['description'] = $new_instance['description'];
        $instance['img'] = $new_instance['img'];
        return $instance;
    }public function exchangeQuotes($str)
    {
        if (gettype($str) == 'string') {
            $str = str_replace('"', '&quot;', $str);
            $str = str_replace("'", '&apos;', $str);
        }return $str;
    }public function form($instance)
    {
        $defaults = array('name' => '', 'description' => '', 'img' => '');
        $instance = wp_parse_args((array) $instance, $defaults);
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }?>
<p><label for="<?php echo $this->get_field_id('name'); ?>">昵称：</label><input id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" value="<?php echo $this->exchangeQuotes($instance['name']); ?>" style="width:100%;" type="text" /></p>
<p><label for="<?php echo $this->get_field_id('description'); ?>">描述：</label><input id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" value="<?php echo $this->exchangeQuotes($instance['description']); ?>" style="width:100%;" type="text" /></p>
<p><label for="<?php echo $this->get_field_id('img'); ?>" style="display:block;">头像：</label><input id="<?php echo $this->get_field_id('img'); ?>" name="<?php echo $this->get_field_name('img'); ?>" value="<?php echo $this->exchangeQuotes($instance['img']); ?>" style="width: calc(100% - 50px);vertical-align: top;" type="text" /><a id="<?php echo $this->get_field_name('img'); ?>" class="upload-custom-img button" href="#"><span class="dashicons dashicons-upload" style="vertical-align: middle;"></span></a></p>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var button_id;
        jQuery(document).on('click', '.upload-custom-img', function(event) {
            button_id = jQuery(this).attr('id');
            event.preventDefault();
            var _this = this;
            if (window.pf_image_upload_modal) {
                window.pf_image_upload_modal.open();
                return;
            }
            window.pf_image_upload_modal = wp.media({
                title: '上传',
                button: {
                    text: '插入',
                },
                multiple: false
            });
            window.pf_image_upload_modal.on('select', function() {
                attachment = window.pf_image_upload_modal.state().get('selection').first().toJSON();
                jQuery("input[name='" + button_id + "']").val(attachment.url);
                window.pf_image_upload_modal.close();
                jQuery("input[name='" + button_id + "']").parentsUntil('form').siblings('.widget-control-actions').find('.widget-control-save').removeAttr('disabled').trigger('click');
            });
            window.pf_image_upload_modal.open();
        });
    });
</script><?php
    }
}
add_action('widgets_init', 'pf_tag_cloud_load_widgets');
function pf_tag_cloud_load_widgets()
{
    register_widget('pf_tag_cloud_Widget');
}class pf_tag_cloud_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array( 'classname' => 'pf_tag_cloud', 'description' => '可用于：自定义标签、链接、社交信息展示等');
        $control_ops = array( 'width' => 500, 'height' => 350, 'id_base' => 'pf_tag_cloud-widget' );
        parent::__construct('pf_tag_cloud-widget', '标签与链接', $widget_ops, $control_ops);
    }public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $tags = $instance['tags'];
        $display_type = $instance['display_type'];
        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }echo '<ul class="'.$display_type.'">';
        foreach ($tags as $tag) {
            echo '<li><a href="'.$tag['url'].'" target="'.$tag['target'].'">'.$tag['text'].'</a></li>';
        }echo '</ul>';
        echo $after_widget;
    }public function reorder_array($array)
    {
        $new_arr = array();
        foreach ($array as $item) {
            $new_arr[] = $item;
        }return $new_arr;
    }public function exchangeQuotes($str)
    {
        if (gettype($str) == 'string') {
            $str = str_replace('"', '&quot;', $str);
            $str = str_replace("'", '&apos;', $str);
        }return $str;
    }public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['display_type'] = strip_tags($new_instance['display_type']);
        $instance['tags'] = $this->reorder_array($new_instance['tags']);
        return $instance;
    }public function form($instance)
    {
        $defaults = array( 
            'title' => '',
            'display_type' => 'tag',
            'tags' => array(array('text' => '','url' => '','target' => '_blank'))
        );
        $instance = wp_parse_args((array) $instance, $defaults);?>
<p><label for="<?php echo $this->get_field_id('title'); ?>">标题：</label><input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" type="text" /></p>
<p><label for="<?php echo $this->get_field_id('display_type'); ?>">显示形式：</label><select id="<?php echo $this->get_field_id('display_type'); ?>" name="<?php echo $this->get_field_name('display_type'); ?>" class="widefat" style="width:100%;">
        <option <?php selected($instance['display_type'], 'tag'); ?> value="tag">标签</option>
        <option <?php selected($instance['display_type'], 'link'); ?> value="link">链接</option>
        <option <?php selected($instance['display_type'], 'navigator'); ?> value="navigator">垂直导航</option>
    </select></p>
<p><label for="<?php echo $this->get_field_id('tags'); ?>">标签：</label>
<table class="widefat fixed striped" style="margin-top: -12px" filedname="<?php echo $this->get_field_name('tags'); ?>">
    <thead>
        <th field="text">标签</th>
        <th field="url">地址</th>
        <th field="special:target" width="100">打开方式</th>
        <th width="40px">操作</th>
    </thead>
    <tbody><?php
if (count($instance['tags']) > 0) {
    foreach ($instance['tags'] as $i => $val) {?>
        <tr rowid="<?php echo $i; ?>">
            <td><input name="<?php echo $this->get_field_name('tags').'['.$i.']'.'[text]'; ?>" value="<?php echo $this->exchangeQuotes($val['text']);?>" style="width:100%;" type="text" /></td>
            <td><input name="<?php echo $this->get_field_name('tags').'['.$i.']'.'[url]'; ?>" value="<?php echo $this->exchangeQuotes($val['url']);?>" style="width:100%;" type="text" /></td>
            <td><select name="<?php echo $this->get_field_name('tags').'['.$i.']'.'[target]'; ?>" class="widefat" style="width:100%;">
                    <option <?php selected($val['target'], '_self'); ?> value="_self">当前窗口</option>
                    <option <?php selected($val['target'], '_blank'); ?> value="_blank">新窗口</option>
                </select></td>
            <td><a class="del_table_row button" href="#"><span class="dashicons dashicons-trash" style="vertical-align: middle;"></span></a></td>
        </tr><?php
    }
}?>
    </tbody>
</table><a class="add_table_row button" style="margin-top: 4px;">新标签</a>
<p style="font-size: 12px;color: #aaa;">* 地址：格式为URL格式（https://www.abc.com/）可留空</p>
</p>
<script>
    jQuery(document).ready(function() {
        jQuery(document).off('click', '.del_table_row');
        jQuery(document).on('click', '.del_table_row', function(event) {
            event.preventDefault();
            var save_btn = jQuery(this).parentsUntil('form').siblings('.widget-control-actions').find('.widget-control-save');
            jQuery(this).parents('tr').remove();
            save_btn.removeAttr('disabled').trigger('click');
        });
        jQuery(document).off('click', '.add_table_row');
        jQuery(document).on('click', '.add_table_row', function(event) {
            event.preventDefault();
            var table = jQuery(this).siblings('table'),
                filedname = table.attr('filedname');
            textfields = table.find('th[field]'),
                max_index = parseInt(table.find('tr').last().attr('rowid')) + 1 || 0,
                tableRow = jQuery('<tr rowid = ' + max_index + '>');
            for (var i = 0; i < textfields.length; i++) {
                var field = jQuery(textfields[i]).attr('field');
                switch (field) {
                    case 'special:target':
                        var input = jQuery('<select name="' + filedname + '[' + max_index + ']' + '[target]" class="widefat" style="width:100%;"><option value="_self">当前窗口</option><option value="_blank">新窗口</option></select>');
                        break;
                    default:
                        var input = jQuery('<input name="' + filedname + '[' + max_index + ']' + '[' + field + ']" value="" style="width:100%;" type="text"/>');
                        break;
                }
                var td = jQuery('<td>');
                td.append(input);
                tableRow.append(td);
            }
            tableRow.append('<td><a class="del_table_row button" href="#"><span class="dashicons dashicons-trash" style="vertical-align: middle;"></span></a></td>');
            table.find('tbody').append(tableRow);
        });
    });
</script><?php
    }
}
add_action('widgets_init', 'pf_microblog_load_widgets');
function pf_microblog_load_widgets()
{
    register_widget('pf_microblog_Widget');
}class pf_microblog_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array( 'classname' => 'pf_microblog', 'description' => '轻博客展示');
        $control_ops = array( 'width' => 200, 'height' => 200, 'id_base' => 'pf_microblog-widget' );
        parent::__construct('pf_microblog-widget', '轻博客', $widget_ops, $control_ops);
    }public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $number = $instance['number'];
        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }echo '<ul>';
        wp_reset_query();
        $args = array(
        'post_type' => 'microblog',
        'posts_per_page' => intval($number),
        );
        query_posts($args);
        while (have_posts()) {
            the_post();?>
<li>
    <div class="date silver-color"><span><?php echo get_the_time('Y-n-j H:i'); ?></span></div>
    <div class="main">
        <p><?php the_content(); ?></p>
    </div>
</li><?php
        }echo '</ul>';
        echo $after_widget;
        wp_reset_query();
    }public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = intval($new_instance['number']) ? intval($new_instance['number']) : 1;
        return $instance;
    }public function form($instance)
    {
        $defaults = array('title' => '', 'number' => '5');
        $instance = wp_parse_args((array) $instance, $defaults);?>
<p><label for="<?php echo $this->get_field_id('title'); ?>">标题：</label><input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" type="text" /></p>
<p><label for="<?php echo $this->get_field_id('number'); ?>">显示轻博客的数量：</label><input id="<?php echo $this->get_field_id('number'); ?>" class="tiny-text" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" type="number" step="1" min="1" size="3" /></p><?php
    }
}
add_action('widgets_init', 'pf_hotposts_load_widgets');
function pf_hotposts_load_widgets()
{
    register_widget('pf_hotposts_Widget');
}class pf_hotposts_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array( 'classname' => 'pf_hotposts', 'description' => '展示评论最多/点赞最多的文章');
        $control_ops = array( 'width' => 200, 'height' => 200, 'id_base' => 'pf_hotposts-widget' );
        parent::__construct('pf_hotposts-widget', '热门文章展示', $widget_ops, $control_ops);
    }public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $filter = $instance['filter'];
        $number = $instance['number'];
        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }echo '<ul>';
        wp_reset_query();
        if ($filter == 'most_likes') {
            $args = array(
            'post_password' => '',
            'post_type' => 'post',
            'post_status' => 'publish', 				'meta_query' => array(
            array(
            'key' => 'bigfa_ding',
            'type' => 'NUMERIC'
            ),
            ),
            'orderby' => array(
            'bigfa_ding' => 'DESC',
            ),
            'posts_per_page' => $number,
            'ignore_sticky_posts' => true 			);
        }if ($filter == 'most_comments') {
            $args = array(
            'post_password' => '',
            'post_type' => 'post',
            'post_status' => 'publish', 				'caller_get_posts' => 1, 				'orderby' => 'comment_count', 				'posts_per_page' => $number,
            'ignore_sticky_posts' => true 	        );
        }query_posts($args);
        while (have_posts()) {
            the_post();
            $id = get_the_ID();?>
<li>
    <div class='single-wrapper clearfix'><a class='cover' style='background-image:url(<?php the_post_thumbnail_url(); ?>)' href='<?php the_permalink(); ?>'></a>
        <div class='meta'><a class='post-title' href='<?php the_permalink(); ?>'>
                <h4><?php the_title(); ?></h4>
            </a>
            <div class='summary'><span class='likes'><i class='fas fa-heart'></i><?php echo(
                get_post_meta($id, 'bigfa_ding', true)
                ?
                get_post_meta($id, 'bigfa_ding', true)
                :
                "0"); ?></span><span class='comments'><i class='fas fa-comments'></i><?php echo get_post($id)->comment_count; ?></span></div>
        </div>
    </div>
</li><?php
        }echo '</ul>';
        echo $after_widget;
        wp_reset_query();
    }public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['filter'] = $new_instance['filter'];
        $instance['number'] = intval($new_instance['number']) ? intval($new_instance['number']) : 1;
        return $instance;
    }public function form($instance)
    {
        $defaults = array('title' => '', 'number' => '5','filter' => 'most_likes');
        $instance = wp_parse_args((array) $instance, $defaults);?>
<p><label for="<?php echo $this->get_field_id('title'); ?>">标题：</label><input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" type="text" /></p>
<p><label for="<?php echo $this->get_field_id('filter'); ?>">文章筛选：</label><select id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" class="widefat" style="width:100%;">
        <option <?php selected($instance['filter'], 'most_likes'); ?> value="most_likes">点赞最多</option>
        <option <?php selected($instance['filter'], 'most_comments'); ?> value="most_comments">评论最多</option>
    </select></p>
<p><label for="<?php echo $this->get_field_id('number'); ?>">显示文章的数量：</label><input id="<?php echo $this->get_field_id('number'); ?>" class="tiny-text" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" type="number" step="1" min="1" size="3" /></p><?php
    }
}
?>
<?php

declare(strict_types=1);

add_action('widgets_init', function (): void {
    $to_unregister = [
        'WP_Widget_Pages',
        'WP_Nav_Menu_Widget',
        'WP_Widget_Search',
        'WP_Widget_Categories',
        'WP_Widget_Recent_Posts',
        'WP_Widget_Archives',
        'WP_Widget_RSS',
        'WP_Widget_Calendar',
        'WP_Widget_Links',
        'WP_Widget_Recent_Comments',
        'WP_Widget_Tag_Cloud',
        'WP_Widget_Media_Video',
        'WP_Widget_Text',
        'WP_Widget_Media_Audio',
        'WP_Widget_Media_Gallery',
        'WP_Widget_Media_Image',
    ];
    foreach ($to_unregister as $w) {
        if (class_exists($w)) {
            unregister_widget($w);
        }
    }
});

add_action('widgets_init', function (): void {
    register_widget(PF_User_Info_Widget::class);
    register_widget(PF_Tag_Cloud_Widget::class);
    register_widget(PF_Microblog_Widget::class);
    register_widget(PF_Hotposts_Widget::class);
});

final class PF_User_Info_Widget extends WP_Widget
{
    private $defaults = [
        'name' => '',
        'description' => '',
        'img' => '',
    ];

    public function __construct()
    {
        parent::__construct(
            'pf_user_info-widget',
            '博主信息',
            [
                'classname' => 'pf_user_info',
                'description' => '展示博主基本信息',
            ],
            [
                'width' => 200,
                'height' => 350,
            ]
        );
    }

    public function widget($args, $instance)
    {
        $before_widget = $args['before_widget'] ?? '';
        $after_widget  = $args['after_widget'] ?? '';
        $name          = !empty($instance['name']) ? $instance['name'] : '';
        $description   = $instance['description'] ?? '';
        $img           = $instance['img'] ?? '';

        echo $before_widget;
        $img_attr = $img ? ' style="background-image: url(' . esc_url($img) . ');"' : '';
        echo '<div class="authorImg"' . $img_attr . '></div>';
        echo '<div class="meta">';
        echo '<div class="name">' . esc_html($name) . '</div>';
        echo '<div class="description">' . wp_kses_post($description) . '</div>';
        echo '</div>';
        echo $after_widget;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['name'] = isset($new_instance['name']) ? sanitize_text_field($new_instance['name']) : '';
        $instance['description'] = isset($new_instance['description']) ? wp_kses_post($new_instance['description']) : '';
        $instance['img'] = isset($new_instance['img']) ? esc_url_raw($new_instance['img']) : '';
        return $instance;
    }

    private function esc_attr_for_input($value): string
    {
        return is_string($value) ? esc_attr($value) : '';
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, $this->defaults);
        if (! did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }
        $name_id = $this->get_field_id('name');
        $name_name = $this->get_field_name('name');
        $desc_id = $this->get_field_id('description');
        $desc_name = $this->get_field_name('description');
        $img_id = $this->get_field_id('img');
        $img_name = $this->get_field_name('img');
        $name_val = $this->esc_attr_for_input($instance['name']);
        $desc_val = $this->esc_attr_for_input($instance['description']);
        $img_val = $this->esc_attr_for_input($instance['img']);
        ?>
<p>
	<label for="<?php echo $name_id; ?>">昵称：</label>
	<input id="<?php echo $name_id; ?>"
		name="<?php echo $name_name; ?>"
		value="<?php echo $name_val; ?>" style="width:100%;"
		type="text" />
</p>
<p>
	<label for="<?php echo $desc_id; ?>">描述：</label>
	<input id="<?php echo $desc_id; ?>"
		name="<?php echo $desc_name; ?>"
		value="<?php echo $desc_val; ?>" style="width:100%;"
		type="text" />
</p>
<p>
	<label for="<?php echo $img_id; ?>"
		style="display:block;">头像：</label>
	<input id="<?php echo $img_id; ?>"
		name="<?php echo $img_name; ?>"
		value="<?php echo $img_val; ?>"
		style="width: calc(100% - 50px);vertical-align: top;" type="text" />
	<a class="upload-custom-img button" href="#"
		data-field-name="<?php echo $img_name; ?>">
		<span class="dashicons dashicons-upload" style="vertical-align: middle;"></span>
	</a>
</p>
<script type="text/javascript">
	(function($) {
		$(document).off('click', '.upload-custom-img')
			.on('click', '.upload-custom-img', function(event) {
				event.preventDefault();
				var button = $(this);
				var fieldName = button.data('field-name');
				var inputField = $('input[name="' + fieldName + '"]');
				var frame = wp.media({
					title: '上传',
					button: {
						text: '插入'
					},
					multiple: false
				});
				frame.on('select', function() {
					var attachment = frame.state().get('selection').first().toJSON();
					inputField.val(attachment.url);
					var saveBtn = inputField.closest('.widget-content')
						.siblings('.widget-control-actions')
						.find('.widget-control-save');
					saveBtn.removeAttr('disabled').trigger('click');
					frame.close();
				});
				frame.open();
			});
	})(jQuery);
</script>
<?php
    }
}

final class PF_Tag_Cloud_Widget extends WP_Widget
{
    private $defaults = [
        'title' => '',
        'display_type' => 'tag',
        'tags' => [
            [
                'text' => '',
                'url' => '',
                'target' => '_blank',
            ],
        ],
    ];

    public function __construct()
    {
        parent::__construct(
            'pf_tag_cloud-widget',
            '标签与链接',
            [
                'classname' => 'pf_tag_cloud',
                'description' => '可用于：自定义标签、链接、社交信息展示等',
            ],
            [
                'width' => 500,
                'height' => 350,
            ]
        );
    }

    public function widget($args, $instance)
    {
        $before_widget = $args['before_widget'] ?? '';
        $after_widget  = $args['after_widget'] ?? '';
        $before_title  = $args['before_title'] ?? '';
        $after_title   = $args['after_title'] ?? '';
        $title         = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $tags          = is_array($instance['tags']) ? $instance['tags'] : [];
        $display_type  = $instance['display_type'] ?? 'tag';

        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }
        $class = esc_attr($display_type);
        echo '<ul class="' . $class . '">';
        foreach ($tags as $tag) {
            $text = isset($tag['text']) ? wp_kses_post($tag['text']) : '';
            $url = isset($tag['url']) && $tag['url'] !== '' ? esc_url($tag['url']) : '#';
            $target = isset($tag['target']) && $tag['target'] === '_self' ? '_self' : '_blank';
            echo '<li><a href="' . $url . '" target="' . esc_attr($target) . '">' . $text . '</a></li>';
        }
        echo '</ul>';
        echo $after_widget;
    }

    private function reorder_array(array $array): array
    {
        $new = [];
        foreach ($array as $item) {
            $new[] = $item;
        }
        return $new;
    }

    private function esc_attr_for_input($value): string
    {
        return is_string($value) ? esc_attr($value) : '';
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $instance['display_type'] = isset($new_instance['display_type']) ? strip_tags($new_instance['display_type']) : 'tag';
        $instance['tags'] = isset($new_instance['tags']) && is_array($new_instance['tags'])
            ? $this->reorder_array($new_instance['tags'])
            : [];
        return $instance;
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, $this->defaults);
        if (!is_array($instance['tags']) || empty($instance['tags'])) {
            $instance['tags'] = $this->defaults['tags'];
        }
        $title_id = $this->get_field_id('title');
        $title_name = $this->get_field_name('title');
        $display_id = $this->get_field_id('display_type');
        $display_name = $this->get_field_name('display_type');
        $tags_name = $this->get_field_name('tags');
        $title_val = $this->esc_attr_for_input($instance['title']);
        ?>
<p>
	<label for="<?php echo $title_id; ?>">标题：</label>
	<input id="<?php echo $title_id; ?>"
		name="<?php echo $title_name; ?>"
		value="<?php echo $title_val; ?>" style="width:100%;"
		type="text" />
</p>
<p>
	<label for="<?php echo $display_id; ?>">显示形式：</label>
	<select id="<?php echo $display_id; ?>"
		name="<?php echo $display_name; ?>" class="widefat"
		style="width:100%;">
		<option <?php selected($instance['display_type'], 'tag'); ?>
			value="tag">标签</option>
		<option <?php selected($instance['display_type'], 'link'); ?>
			value="link">链接</option>
		<option <?php selected($instance['display_type'], 'navigator'); ?>
			value="navigator">垂直导航</option>
	</select>
</p>
<p>
	<label
		for="<?php echo $this->get_field_id('tags'); ?>">标签：</label>
<table class="widefat fixed striped" style="margin-top: -12px"
	fieldname="<?php echo esc_attr($tags_name); ?>">
	<thead>
		<th field="text">标签</th>
		<th field="url">地址</th>
		<th field="special:target" width="100">打开方式</th>
		<th width="40px">操作</th>
	</thead>
	<tbody>
		<?php foreach ($instance['tags'] as $i => $val): ?>
		<tr rowid="<?php echo (int) $i; ?>">
			<td>
				<input
					name="<?php echo $tags_name . '[' . $i . '][text]'; ?>"
					value="<?php echo $this->esc_attr_for_input($val['text'] ?? ''); ?>"
					style="width:100%;" type="text" />
			</td>
			<td>
				<input
					name="<?php echo $tags_name . '[' . $i . '][url]'; ?>"
					value="<?php echo $this->esc_attr_for_input($val['url'] ?? ''); ?>"
					style="width:100%;" type="text" />
			</td>
			<td>
				<select
					name="<?php echo $tags_name . '[' . $i . '][target]'; ?>"
					class="widefat" style="width:100%;">
					<option <?php selected($val['target'] ?? '_self', '_self'); ?>
						value="_self">当前窗口</option>
					<option <?php selected($val['target'] ?? '_blank', '_blank'); ?>
						value="_blank">新窗口</option>
				</select>
			</td>
			<td>
				<a class="del_table_row button" href="#"><span class="dashicons dashicons-trash"
						style="vertical-align: middle;"></span></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<a class="add_table_row button" style="margin-top: 4px;">新标签</a>
<p style="font-size: 12px;color: #aaa;">* 地址：格式为URL格式（https://www.abc.com/）可留空</p>
</p>
<script>
	(function($) {
		$(document).off('click', '.del_table_row')
			.on('click', '.del_table_row', function(e) {
				e.preventDefault();
				var save_btn = $(this).closest('.widget-content')
					.siblings('.widget-control-actions')
					.find('.widget-control-save');
				$(this).closest('tr').remove();
				save_btn.removeAttr('disabled').trigger('click');
			});
		$(document).off('click', '.add_table_row')
			.on('click', '.add_table_row', function(e) {
				e.preventDefault();
				var table = $(this).siblings('table'),
					fieldname = table.attr('fieldname'),
					textfields = table.find('th[field]'),
					last = table.find('tbody tr').last(),
					max_index = last.length ? (parseInt(last.attr('rowid'), 10) + 1) : 0,
					tableRow = $('<tr rowid="' + max_index + '">');
				for (var i = 0; i < textfields.length; i++) {
					var field = $(textfields[i]).attr('field');
					var input;
					switch (field) {
						case 'special:target':
							input = $('<select name="' + fieldname + '[' + max_index +
								'][target]" class="widefat" style="width:100%;">' +
								'<option value="_self">当前窗口</option>' +
								'<option value="_blank">新窗口</option>' +
								'</select>');
							break;
						default:
							input = $('<input name="' + fieldname + '[' + max_index + '][' +
								field + ']" value="" style="width:100%;" type="text"/>');
							break;
					}
					var td = $('<td>').append(input);
					tableRow.append(td);
				}
				tableRow.append(
					'<td><a class="del_table_row button" href="#"><span class="dashicons dashicons-trash" style="vertical-align: middle;"></span></a></td>'
				);
				table.find('tbody').append(tableRow);
				var save_btn = $(this).closest('.widget-content')
					.siblings('.widget-control-actions')
					.find('.widget-control-save');
				save_btn.removeAttr('disabled').trigger('click');
			});
	})(jQuery);
</script>
<?php
    }
}

final class PF_Microblog_Widget extends WP_Widget
{
    private $defaults = [
        'title' => '',
        'number' => '5',
    ];

    public function __construct()
    {
        parent::__construct(
            'pf_microblog-widget',
            '轻博客',
            [
                'classname' => 'pf_microblog',
                'description' => '轻博客展示',
            ],
            [
                'width' => 200,
                'height' => 200,
            ]
        );
    }

    public function widget($args, $instance)
    {
        $before_widget = $args['before_widget'] ?? '';
        $after_widget  = $args['after_widget'] ?? '';
        $before_title  = $args['before_title'] ?? '';
        $after_title   = $args['after_title'] ?? '';
        $title         = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $number        = isset($instance['number']) ? max(1, intval($instance['number'])) : 5;

        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }
        echo '<ul>';
        $query = new WP_Query([
            'post_type' => 'microblog',
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'no_found_rows' => true,
        ]);
        while ($query->have_posts()) {
            $query->the_post();
            echo '<li>';
            echo '<div class="date silver-color"><span>' . esc_html(get_the_time('Y-n-j H:i')) . '</span></div>';
            echo '<div class="main"><p>' . wp_kses_post(get_the_content()) . '</p></div>';
            echo '</li>';
        }
        wp_reset_postdata();
        echo '</ul>';
        echo $after_widget;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $instance['number'] = isset($new_instance['number']) ? max(1, intval($new_instance['number'])) : 1;
        return $instance;
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, $this->defaults);
        $title_id = $this->get_field_id('title');
        $title_name = $this->get_field_name('title');
        $number_id = $this->get_field_id('number');
        $number_name = $this->get_field_name('number');
        ?>
<p>
	<label for="<?php echo $title_id; ?>">标题：</label>
	<input id="<?php echo $title_id; ?>"
		name="<?php echo $title_name; ?>"
		value="<?php echo esc_attr($instance['title']); ?>"
		style="width:100%;" type="text" />
</p>
<p>
	<label for="<?php echo $number_id; ?>">显示轻博客的数量：</label>
	<input id="<?php echo $number_id; ?>" class="tiny-text"
		name="<?php echo $number_name; ?>"
		value="<?php echo esc_attr($instance['number']); ?>"
		type="number" step="1" min="1" size="3" />
</p>
<?php
    }
}

final class PF_Hotposts_Widget extends WP_Widget
{
    private $defaults = [
        'title' => '',
        'number' => '5',
        'filter' => 'most_likes',
    ];

    public function __construct()
    {
        parent::__construct(
            'pf_hotposts-widget',
            '热门文章展示',
            [
                'classname' => 'pf_hotposts',
                'description' => '展示评论最多/点赞最多的文章',
            ],
            [
                'width' => 200,
                'height' => 200,
            ]
        );
    }

    public function widget($args, $instance)
    {
        $before_widget = $args['before_widget'] ?? '';
        $after_widget  = $args['after_widget'] ?? '';
        $before_title  = $args['before_title'] ?? '';
        $after_title   = $args['after_title'] ?? '';
        $title         = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $filter        = $instance['filter'] ?? 'most_likes';
        $number        = isset($instance['number']) ? max(1, intval($instance['number'])) : 5;

        echo $before_widget;
        if ($title) {
            echo $before_title . $title . $after_title;
        }
        echo '<ul>';
        if ('most_likes' === $filter) {
            $query_args = [
                'post_password' => '',
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $number,
                'meta_key' => 'bigfa_ding',
                'orderby' => 'meta_value_num',
                'ignore_sticky_posts' => true,
                'no_found_rows' => true,
            ];
        } else {
            $query_args = [
                'post_password' => '',
                'post_type' => 'post',
                'post_status' => 'publish',
                'orderby' => 'comment_count',
                'posts_per_page' => $number,
                'ignore_sticky_posts' => true,
                'no_found_rows' => true,
            ];
        }

        $query = new WP_Query($query_args);
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $thumb = get_the_post_thumbnail_url($id) ?: '';
            $permalink = get_the_permalink($id);
            $title_text = get_the_title($id);
            $likes = (int) get_post_meta($id, 'bigfa_ding', true);
            $comments = (int) get_post($id)->comment_count;
            echo '<li>';
            echo "<div class='single-wrapper clearfix'>";
            $cover_style = $thumb ? " style='background-image:url(" . esc_url($thumb) . ")'" : '';
            echo "<a class='cover' href='" . esc_url($permalink) . "'" . $cover_style . "></a>";
            echo "<div class='meta'>";
            echo "<a class='post-title' href='" . esc_url($permalink) . "'><h4>" . wp_kses_post($title_text) . "</h4></a>";
            echo "<div class='summary'><span class='likes'><i class='fas fa-heart'></i>" . $likes . "</span>";
            echo "<span class='comments'><i class='fas fa-comments'></i>" . $comments . "</span></div>";
            echo "</div>";
            echo "</div>";
            echo '</li>';
        }
        wp_reset_postdata();
        echo '</ul>';
        echo $after_widget;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? strip_tags($new_instance['title']) : '';
        $instance['filter'] = isset($new_instance['filter']) ? $new_instance['filter'] : 'most_likes';
        $instance['number'] = isset($new_instance['number']) ? max(1, intval($new_instance['number'])) : 1;
        return $instance;
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, $this->defaults);
        $title_id = $this->get_field_id('title');
        $title_name = $this->get_field_name('title');
        $filter_id = $this->get_field_id('filter');
        $filter_name = $this->get_field_name('filter');
        $number_id = $this->get_field_id('number');
        $number_name = $this->get_field_name('number');
        ?>
<p>
	<label for="<?php echo $title_id; ?>">标题：</label>
	<input id="<?php echo $title_id; ?>"
		name="<?php echo $title_name; ?>"
		value="<?php echo esc_attr($instance['title']); ?>"
		style="width:100%;" type="text" />
</p>
<p>
	<label for="<?php echo $filter_id; ?>">文章筛选：</label>
	<select id="<?php echo $filter_id; ?>"
		name="<?php echo $filter_name; ?>" class="widefat"
		style="width:100%;">
		<option <?php selected($instance['filter'], 'most_likes'); ?>
			value="most_likes">点赞最多</option>
		<option <?php selected($instance['filter'], 'most_comments'); ?>
			value="most_comments">评论最多</option>
	</select>
</p>
<p>
	<label for="<?php echo $number_id; ?>">显示文章的数量：</label>
	<input id="<?php echo $number_id; ?>" class="tiny-text"
		name="<?php echo $number_name; ?>"
		value="<?php echo esc_attr($instance['number']); ?>"
		type="number" step="1" min="1" size="3" />
</p>
<?php
    }
}

?>
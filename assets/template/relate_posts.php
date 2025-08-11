<div class="relate-posts clearfix">
	<div class="post-model"><i class="far fa-star"></i>相关文章</div>
	<div class="posts clearfix">
		<?php
$post_num = 4;
		$exclude_id = array($post->ID);
		$posttags = get_the_tags();
		$i = 0;
		if ($posttags) {
		    $tags = '';
		    foreach ($posttags as $tag) {
		        $tags .= $tag->term_id . ',';
		    }$args = array('post_status' => 'publish','tag__in' => explode(',', $tags),'post__not_in' => $exclude_id,'orderby' => 'comment_date','posts_per_page' => $post_num,'ignore_sticky_posts' => true
);
		    query_posts($args);
		    while (have_posts()) {
		        the_post();
		        include('postlist_relate.php');
		        $exclude_id[] = $post->ID;
		        $i++;
		    }wp_reset_query();
		}if ($i < $post_num) {
		    $cats = wp_get_post_categories($post->ID);
		    $args = array('category__in' => array($cats[0]),'post__not_in' => $exclude_id,'orderby' => 'comment_date','posts_per_page' => $post_num - $i,'ignore_sticky_posts' => true
		    );
		    query_posts($args);
		    while (have_posts()) {
		        the_post();
		        include('postlist_relate.php');
		        $i++;
		    }wp_reset_query();
		}if ($i == 0) {
		    echo '<div style="margin-left: 40px;color: #ccc;">暂无相关文章！</div>';
		}?>
	</div>
</div>
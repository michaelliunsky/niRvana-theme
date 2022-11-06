<?php
function zww_archives_list() {
	if ( !$output = get_option( 'zww_db_cache_archives_list' ) ) {
		$output = '<div id="archives"><p>[<a id="al_expand_collapse" href="#">全部展开/收缩</a>] <em>(注: 点击月份可以展开)</em></p>';
		$args = array(
			'post_type' => 'post', //如果你有多个 post type，可以这样 array('post', 'product', 'news') 
			'posts_per_page' => -1, //全部 posts
			'ignore_sticky_posts' => 1 //忽略 sticky posts
		);
		$the_query = new WP_Query( $args );
		$posts_rebuild = array();
		$year = $mon = 0;
		while ( $the_query->have_posts() ): $the_query->the_post();
		$post_year = get_the_time( 'Y' );
		$post_mon = get_the_time( 'm' );
		$post_day = get_the_time( 'd' );
		if ( $year != $post_year )$year = $post_year;
		if ( $mon != $post_mon )$mon = $post_mon;
		$posts_rebuild[ $year ][ $mon ][] = '<li>' . get_the_time( 'd日: ' ) . '<a href="' . get_permalink() . '">' . get_the_title() . '</a> <em>(' . get_comments_number( '0', '1', '%' ) . ')</em></li>';
		endwhile;
		wp_reset_postdata();
		foreach ( $posts_rebuild as $key_y => $y ) {
			$output .= '<h3 class="al_year">' . $key_y . ' 年</h3><ul >'; //输出年份
			foreach ( $y as $key_m => $m ) {
				$posts = '';
				$i = 0;
				foreach ( $m as $p ) {
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
		update_option( 'zww_db_cache_archives_list', $output );
	}
	echo $output;
}
 
function clear_db_cache_archives_list() {
	update_option( 'zww_db_cache_archives_list', '' ); // 清空 zww_archives_list
}
add_action( 'save_post', 'clear_db_cache_archives_list' ); // 新发表文章/修改文章时
?>
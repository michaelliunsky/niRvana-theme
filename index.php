<?php
/*
Template Name: 默认首页
*/
get_header();
?>
<?php
$excludeIDs = get_option( 'index_newes_exclude' );
$minius_excludeIDs = array();
if ( $excludeIDs ) {
	foreach ( $excludeIDs as $id ) {
		$minius_excludeIDs[] = '-' . (string) $id;
	}
}
$cpids = array();
if ( get_option( 'headLine_random' ) != 'checked' ) {
	$carouselPostIDs = get_option( 'headLine' );
	if ( gettype( $carouselPostIDs ) == 'array' ) {
		foreach ( $carouselPostIDs as $post ) {
			$cpids[] = $post['post'];
		}
	}
} else {
	$args = array(
		'posts_per_page'      => 7,
		'orderby'             => 'rand',
		'cat'                 => $minius_excludeIDs,
		'ignore_sticky_posts' => true,
	);
	$carouselsID_query = new WP_Query();
	$carouselsID_query->query( $args );
	while ( $carouselsID_query->have_posts() ) {
		$carouselsID_query->the_post();
		$cpids[] = $post->ID;
	}
	wp_reset_query();
}
get_topSlider( $cpids, _opt( 'frontpage_carousels_type' ) );
?>
<div class="container postListsModel">
	<div class="row">
		<?php
		if ( _opt( 'frontpage_hide_sidebar' ) == 'checked' ) {
			$leftClass = 'col-xs-12 no-sidebar';
			$rightClass = 'hidden';
		} else {
			$leftClass = 'col-md-9 col-lg-9_5';
			$rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';
		}
		?>
		<div class="<?php echo $leftClass; ?>">
			<?php
			global $nav_category_list_type;
			$nav_category_list_type = _opt( 'frontpage_postlist_type', 'lists' );
			include 'assets/template/nav-category.php';
			?>
			<div class="col-xs-12">
				<div class="row">
					<?php $listType = _opt( 'frontpage_postlist_type', 'lists' ); ?>
					<div class="row postLists <?php echo $listType; ?> <?php echo ( _opt( 'enable_post_list_waterfall' ) ? 'waterfall' : '' ); ?>" height-to="sidebar">
						<?php
						$newest_num = intval( get_option( 'frontpage_postlist_newest_num' ) );
						$like_num = intval( get_option( 'frontpage_postlist_like_num' ) );
						$comment_num = intval( get_option( 'frontpage_postlist_comment_num' ) );
						$random_num = intval( get_option( 'frontpage_postlist_random_num' ) );
						$ids = array();
						if ( $newest_num > 0 ) {
							$args = array(
								'post_type'      => 'post',
								'cat'            => $minius_excludeIDs,
								'post_status'    => 'publish',
								'post__not_in'   => $ids, // 排除已筛选的文章
								'posts_per_page' => $newest_num,
							);
							$query_posts = new WP_Query();
							$query_posts->query( $args );
							$i = $newest_num;
							while ( $query_posts->have_posts() && $i > 0 ) {
								$query_posts->the_post();
								$ids[] = $post->ID;
								$i--;
							}
							wp_reset_query();
						}
						if ( $like_num > 0 ) {
							$args = array(
								'post_type'           => 'post',
								'cat'                 => $minius_excludeIDs,
								'post_status'         => 'publish',
								'post__not_in'        => $ids, // 排除已筛选的文章
								'posts_per_page'      => $like_num,
								'ignore_sticky_posts' => true,
								'meta_query'          => array(
									array(
										'key'  => 'bigfa_ding',
										'type' => 'NUMERIC',
									),
								),
								'orderby'             => array(
									'bigfa_ding' => 'DESC',
								),
							);
							$query_posts = new WP_Query();
							$query_posts->query( $args );
							while ( $query_posts->have_posts() ) {
								$query_posts->the_post();
								$ids[] = $post->ID;
							}
							wp_reset_query();
						}
						if ( $comment_num > 0 ) {
							$args = array(
								'post_type'           => 'post',
								'cat'                 => $minius_excludeIDs,
								'post_status'         => 'publish',
								'post__not_in'        => $ids, // 排除已筛选的文章
								'posts_per_page'      => $comment_num,
								'orderby'             => 'comment_count',
								'ignore_sticky_posts' => true,
							);
							$query_posts = new WP_Query();
							$query_posts->query( $args );
							while ( $query_posts->have_posts() ) {
								$query_posts->the_post();
								$ids[] = $post->ID;
							}
							wp_reset_query();
						}
						if ( $random_num > 0 ) {
							$args = array(
								'post_type'           => 'post',
								'cat'                 => $minius_excludeIDs,
								'post_status'         => 'publish',
								'post__not_in'        => $ids, // 排除已筛选的文章
								'posts_per_page'      => $random_num,
								'orderby'             => 'rand',
								'ignore_sticky_posts' => true,
							);
							$query_posts = new WP_Query();
							$query_posts->query( $args );
							while ( $query_posts->have_posts() ) {
								$query_posts->the_post();
								$ids[] = $post->ID;
							}
							wp_reset_query();
						}
						$newestIds = array();
						$otherIds = array();
						for ( $i = 0; $i < count( $ids ); $i++ ) {
							if ( $i < $newest_num ) {
								$newestIds[] = $ids[ $i ];
							} else {
								$otherIds[] = $ids[ $i ];
							}
						}
						shuffle( $otherIds );
						$ids = array_merge( $newestIds, $otherIds );
						global $post;
						foreach ( $ids as $id ) {
							$post = get_post( $id );
							setup_postdata( $post );
							?>
							<div class="col-xxs-6 col-xs-4 col-lg-3 post-card-wrapper">
								<?php include 'assets/template/postlist-post.php'; ?>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<div class="row postLists">
					<?php
					if ( _opt( 'index_time_url' ) ) {
						echo '<div class="col-xs-12 readMore"><a href="' . _opt( 'index_time_url' ) . '">
' . _opt( 'read_more_text', '阅读更多' ) . '
<i class="fas fa-chevron-down"></i></a></div>';
					}
					?>
				</div>
			</div>
		</div>
		<div class="<?php echo $rightClass; ?>">
			<div class="row">
				<div class="sidebar">
					<div manual-template="sidebarMenu"></div>
					<div manual-template="sidebar" height-from="postLists"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="long-model-wrapper">
	<?php
	$index_group = _opt( 'index_group', array() );
	foreach ( $index_group as $group ) {
		$catIDs = array();
		if ( $group['cats'] ) {
			foreach ( $group['cats'] as $id_num ) {
				$catIDs[] = (string) $id_num;
			}
		}
		$post_number = intval( $group['num'] ) ? intval( $group['num'] ) : 6;
		$args = array(
			'post_type'           => 'post',
			'cat'                 => $catIDs,
			'orderby'             => 'rand',
			'posts_per_page'      => $post_number,
			'ignore_sticky_posts' => true,
		);
		$query_posts = new WP_Query();
		$query_posts->query( $args );
		$post_num_class = 'posts-' . $group['num'];
		?>
		<div class="long-model <?php echo $post_num_class; ?>">
			<div class="container">
				<span class="model-title"><?php echo $group['name']; ?></span>
				<?php
				if ( $group['href'] ) {
					?>
					<a class="more" href="<?php echo $group['href']; ?>"><?php echo ( $group['more'] ? $group['more'] : '阅读更多' ); ?><i class="fas fa-angle-right"></i></a>
					<?php
				}
				?>
				<div class="row postLists <?php echo $group['type']; ?>">
					<?php
					while ( $query_posts->have_posts() ) {
						$query_posts->the_post();
						?>
						<div class="col-xxs-6 col-xs-4 col-md-3 post-card-wrapper">
							<?php include 'assets/template/postlist-post.php'; ?>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>
<?php get_footer(); ?>
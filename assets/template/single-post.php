<?php
/*
single: post
*/
$post_id = get_the_ID();
$head_img = get_post_meta( $post_id, '日志头图', true );
$type     = $head_img ? _opt( 'single_title_width_headImg_type', 'single-coverflow' ) : _opt( 'single_title_widthout_headImg_type', 'single-coverflow' );
get_topSlider( array( $post_id ), $type );
?>
<div class="container postListsModel">
	<div class="row">
		<?php
		if ( _opt( 'is_single_post_hide_sidebar' ) ) {
			$left_class  = 'col-xs-12 no-sidebar';
			$right_class = 'hidden';
		} else {
			$left_class  = 'col-md-9 col-lg-9_5';
			$right_class = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';
		}
		?>
		<div class="<?php echo $left_class; ?>">
			<div class="col-xs-12">
				<div class="row postLists">
					<div class="toggle_sidebar" @click="this.single_toggle_sidebar()" data-toggle="tooltip" data-placement="auto top" title="切换边栏">
						<i class="fas fa-angle-right"></i>
					</div>
					<div class="article_wrapper post clearfix">
						<div class="meta">
							<?php do_action( 'pf-post-meta-start' ); ?>
							<span class="inline-block">
								<i class="fas fa-user"></i><?php echo esc_html( get_the_author() ); ?> · <?php echo esc_html( get_the_date( 'n月j日 · Y年' ) ); ?>
							</span>
							<?php do_action( 'pf-post-meta-before-tag' ); ?>
							<?php
							$tag_list = get_the_tag_list( '', ' · ', '' );
							if ( $tag_list && ! is_wp_error( $tag_list ) ) :
								?>
								<span class="inline-block">
									<i class="fas fa-tag"></i><?php echo wp_kses_post( $tag_list ); ?>
								</span>
							<?php endif; ?>
							<span class="inline-block">
								<i class="fas fa-clock"></i><?php echo esc_html( count_words_read_time() ); ?>
							</span>
							<?php do_action( 'pf-post-meta-end' ); ?>
						</div>
						<article class="clearfix">
							<?php the_content(); ?>
						</article>
						<?php locate_template( 'assets/template/share-modal.php', true, true ); ?>
					</div>
					<?php
					if ( _opt( 'show_relate_posts' ) ) {
						locate_template( 'assets/template/relate_posts.php', true, true );
					}
					comments_template();
					?>
				</div>
			</div>
		</div>
		<div class="<?php echo $right_class; ?>">
			<div class="row">
				<div class="sidebar sidebar-affix">
					<div manual-template="sidebarMenu"></div>
					<div manual-template="sidebar"></div>
				</div>
			</div>
		</div>
	</div>
</div>

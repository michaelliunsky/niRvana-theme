<?php
/*
single: gallery
*/
$post_id = get_the_ID();
$type = _opt( 'single_gallery_type', 'gallery-imageflow' );
get_gallery_slider( $post_id, $type );
?>
<div class="container postListsModel">
	<div class="row">
		<?php
		if ( _opt( 'is_single_gallery_hide_sidebar' ) ) {
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
							$term_list = get_the_term_list( $post_id, 'gallery-tag', '', ' · ', '' );
							if ( $term_list && ! is_wp_error( $term_list ) ) :
								?>
								<span class="inline-block">
									<i class="fas fa-tag"></i><?php echo wp_kses_post( $term_list ); ?>
								</span>
							<?php endif; ?>
							<?php do_action( 'pf-post-meta-end' ); ?>
						</div>
						<article class="clearfix">
							<div class="title_style_01 gallery_title">
								<h1><?php the_title(); ?></h1>
							</div>
							<?php the_content(); ?>
						</article>
						<?php locate_template( 'assets/template/share-modal.php', true, true ); ?>
					</div>
					<?php comments_template(); ?>
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

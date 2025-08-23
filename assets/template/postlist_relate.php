<div class="single-post-container col-lg-3 col-sm-4 col-xs-6">
	<div class="single-post relate">
		<a href="<?php the_permalink(); ?>">
			<div class="thumb_img" style="background-image:url('<?php the_post_thumbnail_url(); ?>');">
				<div class="meta flex-center">
					<h3><?php the_title(); ?></h3>
					<span class="date"><?php the_time( 'n月j日 · Y年' ); ?></span>
				</div>
			</div>
		</a>
	</div>
</div>
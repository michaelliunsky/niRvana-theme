<?php
global $carousels_attrs, $carousels_contents;
?>
<div class="container coverflow-wrapper dark-slider gallery">
	<div pandaSlider id="coverflow" prev-text='<i class="fas fa-arrow-left"></i>' next-text='<i class="fas fa-arrow-right"></i>' view="1" class="unfull gallery" type="image" allow-anchor-click <?php echo $carousels_attrs; ?> style="background-color: <?php _eopt( 'gallery_background_color', 'transparent' ); ?>">
		<?php
		foreach ( $carousels_contents as $item ) {
			?>
			<div class="page" post-id="<?php echo $item['id']; ?>">
				<a class="container flex-row-middle flex-center coverflow" href="<?php echo $item['full_img']; ?>" target="_blank"><img src="<?php echo $item['full_img']; ?>" style="max-height: 100%;max-width: 100%;"></a>
			</div>
			<?php
		}
		?>
	</div>
</div>
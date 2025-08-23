<div class="container coverflow-wrapper">
	<div pandaSlider id="coverflow" prev-text='<i class="fas fa-arrow-left"></i>'
		next-text='<i class="fas fa-arrow-right"></i>' view="0" class="unfull">
		<?php
		foreach ( $carousels_contents as $item ) {
			?>
			<div class="page"
				post-id="<?php echo $item['id']; ?>">
				<div class="container flex-row-middle coverflow">
					<div class="content flex-column-middle">
						<div class="category">
							<?php echo $item['category']; ?>
						</div>
						<h1><?php echo $item['title']; ?>
						</h1>
					</div>
					<div class="image flex-center">
						<div class="card"
							style="background-image: url(<?php echo $item['cover_img']; ?>);">
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
</div>
<?php
$sidebars = _opt( 'sidebars', array() );
$sidebarContent = is_single() || is_page() ? get_the_naved_contentnav( get_the_content() ) : false;
?>
<script type="text/html" jQVue-template="sidebarMenu">
	<div pandaTab class="sidebarMenu" active-class=".active" sub-trigger="click" sub-class=".sub-menu">
		<ul class="menu">
			<?php
			if ( $sidebarContent ) {
				?>
				<li data-id="sidebar-0" show-on-single show-on-page><a data-toggle="tooltip" data-placement="auto top" title="<?php _eopt( 'bookmark_tip', '文章目录' ); ?>"><i class="fas fa-bookmark"></i></a></li>
				<?php
			}
			?>
			<?php
			for ( $i = 0; $i < count( $sidebars ); $i++ ) {
				$dataId = 'sidebar-' . ( $i + 1 );
				$showString = '';
				if ( $sidebars[ $i ]['show_on'] ) {
					foreach ( $sidebars[ $i ]['show_on'] as $item ) {
						$showString .= ' show-on-' . $item;
					}
				}
				?>
				<li data-id="<?php echo $dataId; ?>" <?php echo $showString; ?>><a data-toggle="tooltip" data-placement="auto top" title="<?php echo $sidebars[ $i ]['name']; ?>"><?php echo $sidebars[ $i ]['icon'] ? $sidebars[ $i ]['icon'] : '　'; ?></a></li>
				<?php
			}
			?>
		</ul>
	</div>
</script>
<script type="text/html" jQVue-template="sidebar">
	<?php
	if ( $sidebarContent ) {
		?>
		<aside data-id="sidebar-0" class="sidebar" style="display: none;">
			<h2 class="widgettitle"><?php _eopt( 'bookmark_tip', '文章目录' ); ?></h2>
			<?php echo $sidebarContent; ?>
		</aside>
		<?php
	}
	?>
	<?php
	for ( $i = 0; $i < count( $sidebars ); $i++ ) {
		$dataId = 'sidebar-' . ( $i + 1 );
		?>
		<aside data-id="<?php echo $dataId; ?>" class="sidebar" style="display: none;">
			<ul class="widget-list">
				<?php
				if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $dataId ) ) :
				endif;
				?>
			</ul>
		</aside>
		<?php
	}
	?>
</script>
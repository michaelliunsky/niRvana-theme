<?php
global $nav_category_list_type;
?>
<div class="categoryNav-wrapper">
	<div pandaTab class="categoryNav" active-class=".current-menu-item,.current-menu-ancestor" sub-class=".sub-menu" prev-text='<i class="fa fa-angle-left" aria-hidden="true"></i>' next-text='<i class="fa fa-angle-right" aria-hidden="true"></i>' sub-trigger="" auto-scrolling>
		<?php
		if ( has_nav_menu( 'categoryNav' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'categoryNav',
					'container'      => false,
				)
			);
		} else {
			?>
			<ul class="menu"><li><a><?php printf( esc_html__( '请在后台添加菜单并指派到“%s”', 'niRvana' ), esc_html__( '文章分类菜单', 'niRvana' ) ); ?></a></li></ul>
			<?php
		}
		?>
	</div>
</div>
<div class="display-switcher-wrapper">
	<div pandaTab class="display-switcher" active-class=".active" sub-trigger="click" sub-class=".sub-menu">
		<ul class="menu">
			<li class="card <?php echo ( $nav_category_list_type == 'cards' ? 'active' : '' ); ?>"><a data-toggle="tooltip" data-placement="auto top" title="<?php echo esc_attr__( '卡片', 'niRvana' ); ?>"><i class="fas fa-th"></i></a></li>
			<li class="list <?php echo ( $nav_category_list_type == 'lists' ? 'active' : '' ); ?>"><a data-toggle="tooltip" data-placement="auto top" title="<?php echo esc_attr__( '列表', 'niRvana' ); ?>"><i class="fas fa-list"></i></a></li>
		</ul>
	</div>
</div>

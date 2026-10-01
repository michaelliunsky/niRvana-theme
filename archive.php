<?php
if ( is_tax( 'gallery-category' ) ) {
    include 'assets/template/archive-gallery.php';
    return;
}
if ( is_tax( 'gallery-tag' ) ) {
    include 'assets/template/tag-gallery.php';
    return;
}
if ( is_tax( 'faq-category' ) ) {
    wp_die( '警告：FAQ类型不支持直接预览，请前往“主题设置”页面添加！' );
}
if ( is_tax( 'favlinks-category' ) ) {
    wp_die( '警告：友情链接类型不支持直接预览，请新增页面，选择“友情链接”模板！友情链接必须隶属于某个分类才可以显示，请务必设置友链的分类！' );
}
$show_type = _opt( 'frontpage_postlist_type' );
$show_type = $show_type ? $show_type : 'lists';
global $nav_category_list_type;
$nav_category_list_type = $show_type === 'cards-tag' ? 'cards' : $show_type;
get_header();
?>
<div class="container postListsModel">
	<div class="row">
		<?php
		if ( _opt( 'category_hide_sidebar' ) == 'checked' ) {
			$leftClass = 'col-xs-12 no-sidebar';
			$rightClass = 'hidden';
		} else {
			$leftClass = 'col-md-9 col-lg-9_5';
			$rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';
		}
		?>
		<div class="<?php echo $leftClass; ?>">
			<?php include 'assets/template/nav-category.php'; ?>
			<div class="col-xs-12">
				<div class="row">
					<div class="row postLists <?php echo $show_type === 'cards-tag' ? 'cards-tag cards' : $show_type; ?> <?php echo ( _opt( 'enable_post_list_waterfall' ) ? 'waterfall' : '' ); ?>" height-to="sidebar">
						<?php
						while ( have_posts() ) {
							the_post();
							?>
							<div class="col-xxs-6 col-xs-4 col-sm-6 col-md-4 col-lg-3 post-card-wrapper">
								<?php include $show_type === 'cards-tag' ? 'assets/template/postlist-post-thumbnailtag.php' : 'assets/template/postlist-post.php'; ?>
							</div>
							<?php
						}
						?>
					</div>
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
<?php
echo wp_nav(
	$p = 2,
	$showSummary = false,
	$showPrevNext = true,
	$style = 'menu',
	$container = "panda_pagi' pandaTab active-class='.active' sub-class='.sub-menu' sub-trigger='' auto-scrolling='"
);
?>
<?php get_footer(); ?>

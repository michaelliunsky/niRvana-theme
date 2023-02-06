<?php
get_header();?><?php
$cpids = array();
if (_opt('disable_cat_carousels') != "checked") {
    global $wp_query;
    $cat = get_query_var('cat');
    $is_fixed = false;
    $fixed_cat_carousels = _opt('fixed_cat_carousels', array());
    foreach ($fixed_cat_carousels as $fixed_cc) {
        if ($fixed_cc['cat'] == $cat) {
            $cpids[] = intval($fixed_cc['post']);
            $is_fixed = true;
        }
    }if (!$is_fixed) {
        $args = array(
        'cat' => $cat,'posts_per_page' => 5,'orderby' => 'rand','ignore_sticky_posts' => true
        );
        $carouselsID_query = new WP_Query();
        $carouselsID_query->query($args);
        while ($carouselsID_query->have_posts()) {
            $carouselsID_query->the_post();
            if (strstr(_opt('category_carousels_type'), 'imageflow')) {
                if (get_post_meta($post->ID, "分类slider图片地址", true)) {
                    $cpids[] = $post->ID;
                }
            } else {
                $cpids[] = $post->ID;
            }
        }wp_reset_query();
    }
}get_topSlider($cpids, _opt('category_carousels_type'));?>
<div class="container postListsModel">
    <div class="row"><?php
if (_opt('category_hide_sidebar')=='checked') {
    $leftClass = 'col-xs-12 no-sidebar';
    $rightClass = 'hidden';
} else {
    $leftClass = 'col-md-9 col-lg-9_5';
    $rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';
}?>
        <div class="<?php echo $leftClass; ?>"><?php
global $nav_category_list_type;
$cat = get_query_var('cat');
$cat_modes = get_option('categorys_show_mode');
$show_type = 'lists';
foreach ($cat_modes as $cat_mode) {
    if ($cat_mode['cat'] == $cat) {
        $show_type = $cat_mode['type'];
    }
}$nav_category_list_type = $show_type;
include('assets/template/nav-category.php');?>
            <div class="col-xs-12">
                <div class="row">
                    <div class="row postLists <?php echo $show_type; ?> <?php echo(_opt('enable_post_list_waterfall') ? 'waterfall' : ''); ?>" height-to="sidebar"><?php
global $wp_query;
while (have_posts()) {
    the_post();?>
                        <div class="col-xxs-6 col-xs-4 col-sm-6 col-md-4 col-lg-3 post-card-wrapper"><?php include('assets/template/postlist-post.php'); ?></div><?php
}?>
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
</div><?php echo wp_nav(
        $p = 2,
        $showSummary = false,
        $showPrevNext = true,
        $style = 'menu',
        $container = "panda_pagi' pandaTab active-class='.active' sub-class='.sub-menu' sub-trigger='' auto-scrolling='"
    );?><?php get_footer(); ?>
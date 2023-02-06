<?php
/*
Template Name: 全部文章时间排序
*/
get_header();?><?php
$excludeIDs = get_option('index_newes_exclude');
$minius_excludeIDs = array();
if ($excludeIDs) {
    foreach ($excludeIDs as $id) {
        $minius_excludeIDs[] = '-'.(string)$id;
    }
}
$cpids = array();
if (get_option('headLine_random') != "checked") {
    $carouselPostIDs = get_option("headLine");
    if (gettype($carouselPostIDs) == "array") {
        foreach ($carouselPostIDs as $post) {
            $cpids[] = $post["post"];
        }
    }
} else {
    $args = array(
    'posts_per_page' => 7,
    'orderby' => 'rand',
    'cat' => $minius_excludeIDs,
    'ignore_sticky_posts' => true
    );
    $carouselsID_query = new WP_Query();
    $carouselsID_query->query($args);
    while ($carouselsID_query->have_posts()) {
        $carouselsID_query->the_post();
        $cpids[] = $post->ID;
    }
    wp_reset_query();
}
get_topSlider($cpids, _opt('frontpage_carousels_type'));?>
<div class="container postListsModel">
    <div class="row"><?php
if (_opt('category_hide_sidebar')=='checked') {
    $leftClass = 'col-xs-12 no-sidebar';
    $rightClass = 'hidden';
} else {
    $leftClass = 'col-md-9 col-lg-9_5';
    $rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';
}
?>
        <div class="<?php echo $leftClass; ?>"><?php
global $nav_category_list_type;
$frontpage_postlist_type = _opt('frontpage_postlist_type');
$show_type = $frontpage_postlist_type ? $frontpage_postlist_type : 'lists';
$nav_category_list_type = $show_type;
include('assets/template/nav-category.php');?>
            <div class="col-xs-12">
                <div class="row">
                    <div class="row postLists <?php echo $show_type; ?> <?php echo(_opt('enable_post_list_waterfall') ? 'waterfall' : ''); ?>" height-to="sidebar"><?php
wp_reset_query();
if (get_query_var('paged')) {
    $paged = get_query_var('paged');
} elseif (get_query_var('page')) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$args = array(
'post_type'=> 'post',
'paged'=> $paged
);
query_posts($args);
while (have_posts()) {
    the_post();?>
                        <div class="col-xxs-6 col-xs-4 col-sm-6 col-md-4 col-lg-3 post-card-wrapper"><?php include('assets/template/postlist-post.php'); ?></div><?php
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
</div><?php echo wp_nav(
    $p = 2,
    $showSummary = false,
    $showPrevNext = true,
    $style = 'menu',
    $container = "panda_pagi' pandaTab active-class='.active' sub-class='.sub-menu' sub-trigger='' auto-scrolling='"
);?><?php get_footer(); ?>
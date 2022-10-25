<?php
/*
相册标签列表
*/
get_header();?><?php
$frontpage_carousels_type = _opt('frontpage_carousels_type');$type = strstr($frontpage_carousels_type, 'full') ? 'single-imageflow-full':'single-imageflow';$content = array("slider_img" => get_post_meta($pid, "分类slider图片地址", true),"head_img" => get_post_meta($pid, "日志头图", true),"title" => single_tag_title('',false),"category" => 'Tag · 标签'
);?><div class="container postListsModel"><div class="row"><?php
if (_opt('category_hide_sidebar')=='checked') {$leftClass = 'col-xs-12 no-sidebar';$rightClass = 'hidden';} else {$leftClass = 'col-sm-8 col-md-9 col-lg-9_5';$rightClass = 'col-sm-4 col-md-3 col-lg-2_5 hidden-xs';}?><div class="<?php echo $leftClass; ?>"><?php
global $nav_category_list_type;$frontpage_postlist_type = _opt('frontpage_postlist_type');$show_type = $frontpage_postlist_type ? $frontpage_postlist_type : 'lists';$nav_category_list_type = $show_type;include('nav-category.php');?><div class="col-xs-12"><div class="row"><h2 class="widgettitle tag-title"><i class='fa fa-tags' aria-hidden='true'></i> <?php echo single_tag_title('',false); ?></h2></div><div class="row"><div class="row postLists <?php echo $show_type; ?> waterfall" height-to="sidebar"><?php
global $wp_query;while( have_posts() ){the_post();?><div class="col-xxs-6 col-xs-4 col-sm-6 col-md-4 col-lg-3 post-card-wrapper"><?php include('postlist-post.php'); ?></div><?php
}?></div></div></div></div><div class="<?php echo $rightClass; ?>"><div class="row"><div class="sidebar"><div manual-template="sidebarMenu"></div><div manual-template="sidebar" height-from="postLists"></div></div></div></div></div></div><?php echo wp_nav($p = 2 ,$showSummary = false,$showPrevNext = true,$style = 'menu',$container = "panda_pagi' pandaTab active-class='.active' sub-class='.sub-menu' sub-trigger='' auto-scrolling='"
);?><?php get_footer(); ?>
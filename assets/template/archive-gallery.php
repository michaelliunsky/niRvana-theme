<?php
get_header();?><div class="container postListsModel no-display-switcher"><div class="row"><?php
if (_opt('gallery_hide_sidebar')=='checked') {$leftClass = 'col-xs-12 no-sidebar';$rightClass = 'hidden';} else {$leftClass = 'col-sm-8 col-md-9 col-lg-9_5';$rightClass = 'col-sm-4 col-md-3 col-lg-2_5 hidden-xs';}?><div class="<?php echo $leftClass; ?>"><?php
include('nav-category.php');?><div class="col-xs-12"><div class="row"><div class="row postLists cards waterfall" height-to="sidebar"><?php
global $wp_query;while( have_posts() ){the_post();?><div class="col-xxs-6 col-xs-4 col-sm-6 col-md-4 col-lg-3 post-card-wrapper"><?php include('postlist-post.php'); ?></div><?php
}?></div></div></div></div><div class="<?php echo $rightClass; ?>"><div class="row"><div class="sidebar"><div manual-template="sidebarMenu"></div><div manual-template="sidebar" height-from="postLists"></div></div></div></div></div></div><?php echo wp_nav($p = 2 ,$showSummary = false,$showPrevNext = true,$style = 'menu',$container = "panda_pagi' pandaTab active-class='.active' sub-class='.sub-menu' sub-trigger='' auto-scrolling='"
);?><?php get_footer(); ?>
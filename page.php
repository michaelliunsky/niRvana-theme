<?php
/*
页面
*/
get_header();?><?php
$frontpage_carousels_type = _opt('frontpage_carousels_type');$type = strstr($frontpage_carousels_type, 'full') ? 'single-imageflow-full':'single-imageflow';get_topSlider(array($post->ID),$type);?><div class="container postListsModel"><div class="row"><?php
if (_opt('is_single_post_hide_sidebar')) {$leftClass = 'col-xs-12 no-sidebar';$rightClass = 'hidden';} else {$leftClass = 'col-md-9 col-lg-9_5';$rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';}?><div class="<?php echo $leftClass; ?>"><div class="col-xs-12"><div class="row postLists"><div class="toggle_sidebar" @click="this.single_toggle_sidebar()" data-toggle="tooltip" data-placement="auto top" title="切换边栏"><i class="fas fa-angle-right"></i></div><div class="article_wrapper post clearfix page"><article class="clearfix"><?php
the_content();?></article></div><?php comments_template(); ?></div></div></div><div class="<?php echo $rightClass; ?>"><div class="row"><div class="sidebar sidebar-affix"><div manual-template="sidebarMenu"></div><div manual-template="sidebar"></div></div></div></div></div></div><?php get_footer(); ?>
<?php
/*
single: gallery
*/
get_header();?><?php
$type = _opt('single_gallery_type','gallery-imageflow');get_gallery_slider($post->ID,$type);?><div class="container postListsModel"><div class="row"><?php
if (_opt('is_single_gallery_hide_sidebar')) {$leftClass = 'col-xs-12 no-sidebar';$rightClass = 'hidden';} else {$leftClass = 'col-md-9 col-lg-9_5';$rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';}?><div class="<?php echo $leftClass; ?>"><div class="col-xs-12"><div class="row postLists"><div class="toggle_sidebar" @click="this.single_toggle_sidebar()" data-toggle="tooltip" data-placement="auto top" title="切换边栏"><i class="fas fa-angle-right"></i></div><div class="article_wrapper post clearfix"><div class="meta"><?php do_action('pf-post-meta-start'); ?><span class="inline-block"><i class="fas fa-user"></i><?php echo get_the_author(); ?> · <?php the_time('n月j日 · Y年') ?></span><?php do_action('pf-post-meta-before-tag'); ?><?php
if (get_the_term_list( $post->ID, 'gallery-tag')) {?><span class="inline-block"><i class="fas fa-tag"></i><?php
$the_tags = apply_filters( 'the_tags', get_the_term_list( $post->ID, 'gallery-tag', '', ' · ', '' ), '', ' · ', '', $post->ID );if ( ! is_wp_error( $the_tags ) ) {echo $the_tags;}?></span><?php
}?><?php do_action('pf-post-meta-end'); ?></div><article class="clearfix"><div class="title_style_01 gallery_title"><h1><?php the_title(); ?></h1></div><?php
the_content();?></article><?php include('share-modal.php'); ?></div><?php comments_template(); ?></div></div></div><div class="<?php echo $rightClass; ?>"><div class="row"><div class="sidebar sidebar-affix"><div manual-template="sidebarMenu"></div><div manual-template="sidebar"></div></div></div></div></div></div><?php get_footer(); ?>
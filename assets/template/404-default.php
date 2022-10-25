<?php get_header(); ?><div class="container four04"><?php
if (_opt('404页面图片')) {?><a href="<?php echo home_url(); ?>"><img src="<?php _eopt('404页面图片'); ?>" /></a><?php
} else {?><hr><hr><hr><hr><h1>404</h1><hr><hr><hr><hr><?php
}?></div><?php get_footer(); ?>
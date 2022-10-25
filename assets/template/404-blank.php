<!DOCTYPE html><html <?php language_attributes() ?> ><head><meta charset="utf8"><title><?php wp_title( '-', true, 'right' ); echo _wp_specialchars( get_bloginfo('name'), 1 ) ?></title><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" ><meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,viewport-fit=cover"/><meta name="renderer" content="webkit"></head><body><div class="four04"><?php
if (_opt('404页面图片')) {?><a href="<?php echo home_url(); ?>"><img src="<?php _eopt('404页面图片'); ?>" /></a><?php
} else {?><hr><hr><hr><hr><h1>404</h1><hr><hr><hr><hr><?php
}?></div><style>
html,body {background: <?php _eopt('404_background','#fff'); ?>
}body {margin: 0;}.four04 {height: 100vh;display: flex;align-items: center;justify-content: center;}.four04 img {max-width: 96vw;max-height: 96vh;}</style></body></html>
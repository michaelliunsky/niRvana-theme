<?php
$like_num = _meta('bigfa_ding') ? _meta('bigfa_ding') : '0';?>
<div class="share-modal">
    <?php do_action('pf-share-modal-start'); ?><?php
if (_meta('打赏')) {?><a class="dashang" data-target="#dashang"
        data-toggle="modal"><i class="fas fa-yen-sign" data-toggle="tooltip" data-placement="auto top"
            title="打赏"></i></a><?php
}do_action('pf-share-modal-before-share');
if (_opt('show_share_posts')) {?><a class="toWeibo"
        @click="this.shareToWeibo()"><i class="fab fa-weibo" data-toggle="tooltip" data-placement="auto top"
            title="分享到微博"></i></a><a class="toWechat"
        @click="this.shareToWechat('<?php the_post_thumbnail_url(); ?>','<?php echo get_the_description($post->ID) ?>')"><i
            class="fab fa-weixin" data-toggle="tooltip" data-placement="auto top"
            title="分享到微信"></i></a><?php
}?><?php do_action('pf-share-modal-before-favorite'); ?><a
        @click="this.ding(<?php the_ID(); ?>)" class="post-like like favorite <?php if (isset($_COOKIE['bigfa_ding_'.$post->ID])) {
            echo 'done';
        }?>"><i
            class="<?php echo(isset($_COOKIE['bigfa_ding_'.$post->ID]) ? 'fas' : 'far');?> fa-heart"
            data-toggle="tooltip" data-placement="auto top" title="点赞"></i><span
            class="count number"><?php echo $like_num;?></span></a><?php do_action('pf-share-modal-end'); ?>
</div>
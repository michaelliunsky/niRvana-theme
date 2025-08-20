<?php
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
    die( 'Please do not load this page directly. Thanks.' );
}

if ( post_password_required() ) {
    echo '<div id="comments">';
    echo '<div class="nopassword">' . __( 'This post is protected. Enter the password to view any comments.', 'sandbox' ) . '</div>';
    echo '</div><!-- .comments -->';
    return;
}

if ( comments_open() || get_comments_number() > 0 ) : ?>
<div id="comments">
    <div class="post-model"><i class="far fa-comment-alt"></i><?php echo esc_html( get_comments_number() ); ?> 条回应</div>

    <!-- 评论输入框 -->
    <?php
    if ( comments_open() ) {
        if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) : ?>
            <div class="tip">
                <p>必须<a href="<?php echo esc_url( wp_registration_url() ); ?>" style="color:#fff;font-weight: bold;"> 注册 </a>为本站用户，
                <a href="<?php echo esc_url( wp_login_url() ); ?>" style="color:#fff;font-weight: bold;"> 登录 </a>后才可以发表评论！</p>
            </div>
        <?php else : ?>
            <div id="respond" class="comment-respond clearfix">
                <?php
                $cmt_face_arr  = (array) get_faces_from_dir();
                $cmt_face_imgs = '';

                foreach ( $cmt_face_arr as $face ) {
                    $cmt_face_fmt = 'png';
                    if ( isset( $face['type'] ) && $face['type'] === 'g' ) {
                        $cmt_face_fmt = 'gif';
                    }

                    $img_src   = esc_url( get_stylesheet_directory_uri() . '/faces/' . ( isset( $face['name'] ) ? $face['name'] : '' ) . '.' . $cmt_face_fmt );
                    $type_attr = isset( $face['type'] ) ? $face['type'] : '';
                    $name_attr = isset( $face['name'] ) ? $face['name'] : '';
                    $cmt_face_imgs .= '<img src="' . $img_src . '" @click="this.addCommentFace(\'[face ' . esc_attr( $type_attr ) . '=' . esc_attr( $name_attr ) . ']\')">';
                }
                ?>
                <div class="popover_faces hidden">
                    <?php echo $cmt_face_imgs; ?>
                </div>

                <div class="popover_loggedin hidden">
                    <div style="text-align: right; width: 100px;">
                        <a>取消</a>
                        <a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="primary"><i class="fas fa-check"></i> 确定</a>
                    </div>
                </div>

                <div class="popover_guests hidden">
                    <p>
                        <label><i class="fas fa-user"></i></label>
                        <input v-model="comment_author" class="comment-input" placeholder="昵称">
                    </p>
                    <p>
                        <label><i class="fas fa-envelope"></i></label>
                        <input v-model="comment_email" class="comment-input" placeholder="邮箱">
                    </p>
                    <p>
                        <label><i class="fas fa-globe-americas"></i></label>
                        <input v-model="comment_url" class="comment-input" placeholder="网站">
                    </p>
                </div>

                <form method="post" id="cmt_form" class="comment-form clear-float">
                    <div class="comment-form-comment">
                        <textarea
                            id="comment"
                            name="comment"
                            rows="5"
                            aria-required="true"
                            placeholder="<?php echo esc_attr( _eopt( 'comment_area_placeholder', '请输入...' ) ); ?>"
                            v-model="comment_text"
                            class="comment-input"
                        ></textarea>
                    </div>

                    <input name="author" v-model="comment_author" class="hidden">
                    <input name="email" v-model="comment_email" class="hidden">
                    <input name="url" v-model="comment_url" class="hidden">

                    <p class="form-meta">
                        <?php
                        do_action( 'pf_comment_form_before_face' );

                        if ( count( $cmt_face_arr ) > 0 ) {
                            echo '<a id="comment_faces_toggle" tabindex="0"><i class="far fa-smile-wink" data-toggle="tooltip" title="表情"></i></a>';
                        }

                        do_action( 'pf_comment_form_after_face' );
                        ?>
                        <a
                            class="comment-meta nick-name guests"
                            tabindex="0"
                            v-show="!this.is_user_loggedin"
                            :html="this.comment_author ? this.comment_author.replace(/ /g, '') != '' ? this.comment_author : '昵称' : '昵称'"
                        ></a>
                        <a class="comment-meta nick-name loggedin" tabindex="0" v-show="this.is_user_loggedin">
                            <?php
                            global $current_user;
                            wp_get_current_user();
                            echo esc_html( $current_user->user_login );
                            ?>
                        </a>
                        <span class="big_fa_ding" data-toggle="tooltip" title="同时点赞">
                            <input name="big_fa_ding" type="checkbox" v-model="comment_ding" class="jv-switcher">
                        </span>
                    </p>

                    <p class="form-submit">
                        <?php echo get_cancel_comment_reply_link( '<i class="fas fa-ban"></i> 取消回复' ); ?>
                        <button name="submit" class="submit-comment" value="提交评论" @click="this.submit_comments(event);"><i class="fas fa-paper-plane"></i> 提交评论</button>

                        <?php
                        comment_id_fields();
                        do_action( 'comment_form', isset( $post->ID ) ? $post->ID : 0 );
                        ?>
                    </p>
                </form>
            </div>
        <?php endif;
    }
    ?>

    <!-- 评论列表 -->
    <?php if ( comments_open() || get_comments_number() > 0 ) : ?>
    <div id="comments-list" class="comments">
        <ol class="commentlist">
            <?php wp_list_comments( array( 'type' => 'comment', 'callback' => 'mytheme_comment' ) ); ?>
        </ol>

        <?php
        if ( get_option( 'page_comments' ) ) {
            $comment_pages = paginate_comments_links( array( 'echo' => 0 ) );
            if ( $comment_pages ) : ?>
                <div id="commentnavi">
                    <?php echo $comment_pages; ?>
                </div>
            <?php endif;
        }
        ?>
    </div>
    <?php endif; ?>

</div><!-- #comments -->
<?php endif; ?>

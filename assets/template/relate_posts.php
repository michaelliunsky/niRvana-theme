<div class="relate-posts clearfix">
    <div class="post-model"><i class="far fa-star"></i>相关文章</div>
    <div class="posts clearfix">
        <?php
        $post_num = 4;
        $exclude_id = array( $post->ID );
        $posttags = get_the_tags();
        $i = 0;
        if ( $posttags ) {
            $tags = array();
            foreach ( $posttags as $tag ) {
                $tags[] = $tag->term_id;
            }
            $args = array(
                'post_status'         => 'publish',
                'tag__in'             => $tags,
                'post__not_in'        => $exclude_id,
                'orderby'             => 'comment_date',
                'posts_per_page'      => $post_num,
                'ignore_sticky_posts' => true,
            );
            $tag_query = new WP_Query( $args );
            if ( $tag_query->have_posts() ) {
                while ( $tag_query->have_posts() ) {
                    $tag_query->the_post();
                    include 'postlist_relate.php';
                    $exclude_id[] = get_the_ID();
                    $i++;
                }
                wp_reset_postdata();
            }
        }
        if ( $i < $post_num ) {
            $cats = wp_get_post_categories( $post->ID );
            $primary_cat = ! empty( $cats ) ? array( $cats[0] ) : array();
            if ( ! empty( $primary_cat ) ) {
                $args = array(
                    'category__in'        => $primary_cat,
                    'post__not_in'        => $exclude_id,
                    'orderby'             => 'comment_date',
                    'posts_per_page'      => $post_num - $i,
                    'ignore_sticky_posts' => true,
                );
                $cat_query = new WP_Query( $args );
                if ( $cat_query->have_posts() ) {
                    while ( $cat_query->have_posts() ) {
                        $cat_query->the_post();
                        include 'postlist_relate.php';
                        $i++;
                    }
                    wp_reset_postdata();
                }
            }
        }
        if ( $i == 0 ) {
            echo '<div style="margin-left: 40px;color: #ccc;">暂无相关文章！</div>';
        }
        ?>
    </div>
</div>
<?php
$posttype = get_post_type();
switch ( $posttype ) {
    case 'post':
        $thumbnail = get_the_post_thumbnail_url();
        $tags      = get_the_tags();
        break;
    case 'gallery':
        $gallery_images = get_post_meta( get_the_id(), 'gallery_images', true );
        $gallery_images = $gallery_images ? $gallery_images : array();
        switch ( get_option( 'gallery_thumbnail' ) ) {
            case 'first':
                $thumbnail = $gallery_images[0];
                break;
            case 'last':
                $thumbnail = $gallery_images[ count( $gallery_images ) - 1 ];
                break;
            default:
                $thumbnail = count( $gallery_images ) > 0 ? $gallery_images[ array_rand( $gallery_images, 1 ) ] : '';
                break;
        }
        $tags = get_the_terms( get_the_ID(), 'gallery-tag' );
        break;
    default:
        $thumbnail = '';
        break;
}
?>
<div class="card">
    <a href="<?php the_permalink(); ?>" class="cover"
       style="background-image: url(<?php echo $thumbnail; ?>);"
       showas='padding'></a>
    <a href="<?php the_permalink(); ?>" showas='img'>
        <img src="<?php echo $thumbnail; ?>" class="cover" alt="">
    </a>
    <div class="meta">
        <div class="date">
            <?php the_time( 'n月j日 · Y年' ); ?>
        </div>
        <h2>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="tags">
            <?php
            if ( $tags ) {
                foreach ( $tags as $tag ) {
                    $name     = $tag->name;
                    $colorInt = string_to_int8( $name );
                    echo '<a class="color-' . $colorInt . '">' . $name . '</a>' . "\n";
                }
            } else {
                echo '<a class="color-0">无标签</a>';
            }
            ?>
        </div>
        <div class="summary">
            <?php do_action( 'pf-post-card-meta-start' ); ?>
            <span class="likes">
                <i class="fas fa-heart"></i>
                <?php
                $like = get_post_meta( $post->ID, 'bigfa_ding', true ) ? get_post_meta( $post->ID, 'bigfa_ding', true ) : '0';
                echo $like;
                ?>
            </span>
            <?php do_action( 'pf-post-card-meta-before-comments' ); ?>
            <span class="comments">
                <i class="fas fa-comments"></i>
                <?php echo $post->comment_count; ?>
            </span>
            <?php do_action( 'pf-post-card-meta-end' ); ?>
        </div>
        <article class="description">
            <?php echo get_the_description( $post->ID ); ?>
        </article>
    </div>
</div>
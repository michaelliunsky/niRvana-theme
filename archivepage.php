<?php
/**
 * Template Name: 归档页面
 */
get_header();
?>

<?php
$frontpage_carousels_type = _opt( 'frontpage_carousels_type' );
$type                    = strstr( $frontpage_carousels_type, 'full' ) ? 'single-imageflow-full' : 'single-imageflow';
get_topSlider( array( $post->ID ), $type );
?>
<div class="container postListsModel">
    <div class="row">
        <style>
            #main-archivest {
                margin: 20px 0;
            }

            .m-title {
                text-align: center;
                border-bottom: solid 1px #ccc;
            }

            .al_mon {
                font-weight: bold;
            }

            #archives ul li {
                list-style-type: none;
            }

            #archives ul > li {
                padding: 0 0 8px 5px;
                border-left: solid 1px #ccc;
                padding-left: 20px;
                background-repeat: no-repeat;
            }

            #archives ul > li > ul {
                margin: 0;
                padding: 0;
            }

            #archives ul > li > ul > li {
                list-style-type: none;
                padding-left: 20px;
                background-repeat: no-repeat;
                border-left: dashed 1px #ccc;
            }
        </style>
        <?php
        if ( _opt( 'is_single_post_hide_sidebar' ) ) {
            $leftClass  = 'col-xs-12 no-sidebar';
            $rightClass = 'hidden';
        } else {
            $leftClass  = 'col-md-9 col-lg-9_5';
            $rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';
        }
        ?>

        <div class="<?php echo $leftClass; ?>">
            <div class="col-xs-12">
                <div class="row postLists">
                    <div class="toggle_sidebar"
                         @click="this.single_toggle_sidebar()"
                         data-toggle="tooltip"
                         data-placement="auto top"
                         title="切换边栏">
                        <i class="fas fa-angle-right"></i>
                    </div>
                    <div class="article_wrapper post clearfix page">
                        <article class="clearfix">
                            <div id="archives">
                                <?php niRvana_archives_list(); ?>
                            </div>
                        </article>
                    </div>
                    <?php comments_template(); ?>
                </div>
            </div>
        </div>
        <div class="<?php echo $rightClass; ?>">
            <div class="row">
                <div class="sidebar sidebar-affix">
                    <div manual-template="sidebarMenu"></div>
                    <div manual-template="sidebar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    ( function( $ ) {
        $( function() {
            var $archives = $( '#archives' ),
                $months   = $archives.find( '.al_mon' ),
                $lists    = $archives.find( '.al_post_list' );
            $lists.hide().first().show();
            $months.css( 'cursor', 's-resize' ).on( 'click', function() {
                $( this ).next().slideToggle( 400 );
            } );
            $( '#al_expand_collapse' ).on( 'click', function( e ) {
                e.preventDefault();
                var isCollapsed = ! $( this ).data( 'status' );
                $( this ).data( 'status', isCollapsed );
                $lists.each( function( i ) {
                    var speed = Math.max( 100 - i * 10, 0 );
                    $( this ).stop( true )[ isCollapsed ? 'slideDown' : 'slideUp' ]( speed );
                } );
            } );
        } );
    } )( jQuery );
</script>
<?php
get_footer();
?>
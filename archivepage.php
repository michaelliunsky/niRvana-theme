<?php
/**
 * Template Name: 归档页面
 */
get_header(); ?>
<?php
$frontpage_carousels_type = _opt('frontpage_carousels_type');
$type = strstr($frontpage_carousels_type, 'full') ? 'single-imageflow-full' : 'single-imageflow';
get_topSlider(array($post->ID), $type);?>
<div class="container postListsModel">
	<div class="row">
		<style>
			#main-archivest {
				;
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

			#archives ul>li {
				padding: 0 0 8px 5px;
				border-left: solid 1px #ccc;
				padding-left: 20px;
				background-repeat: no-repeat;
			}

			#archives ul>li>ul {
				margin: 0;
				padding: 0;
			}

			#archives ul>li>ul>li {
				list-style-type: none;
				padding-left: 20px;
				background-repeat: no-repeat;
				border-left: dashed 1px #ccc;
			}
		</style>
		<?php
if (_opt('is_single_post_hide_sidebar')) {
    $leftClass = 'col-xs-12 no-sidebar';
    $rightClass = 'hidden';
} else {
    $leftClass = 'col-md-9 col-lg-9_5';
    $rightClass = 'col-md-3 col-lg-2_5 hidden-xs hidden-sm';
}?>
		<div class="<?php echo $leftClass; ?>">
			<div class="col-xs-12">
				<div class="row postLists">
					<div class="toggle_sidebar" @click="this.single_toggle_sidebar()" data-toggle="tooltip" data-placement="auto top" title="切换边栏"><i class="fas fa-angle-right"></i></div>
					<div class="article_wrapper post clearfix page">
						<article class="clearfix">
							<div id="archives"><?php zww_archives_list(); ?></div>
						</article>
					</div><?php comments_template(); ?>
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
</div><?php get_footer(); ?>
<script type="text/javascript">
	(function($, window) {
		$(function() {
			var $a = $('#archives'),
				$m = $('.al_mon', $a),
				$l = $('.al_post_list', $a),
				$l_f = $('.al_post_list:first', $a);
			$l.hide();
			$l_f.show();
			$m.css('cursor', 's-resize').on('click', function() {
				$(this).next().slideToggle(400);
			});
			var animate = function(index, status, s) {
				if (index > $l.length) {
					return;
				}
				if (status == 'up') {
					$l.eq(index).slideUp(s, function() {
						animate(index + 1, status, (s - 10 < 1) ? 0 : s - 10);
					});
				} else {
					$l.eq(index).slideDown(s, function() {
						animate(index + 1, status, (s - 10 < 1) ? 0 : s - 10);
					});
				}
			};
			$('#al_expand_collapse').on('click', function(e) {
				e.preventDefault();
				if ($(this).data('s')) {
					$(this).data('s', '');
					animate(0, 'up', 100);
				} else {
					$(this).data('s', 1);
					animate(0, 'down', 100);
				}
			});
		});
	})(jQuery, window);
</script>
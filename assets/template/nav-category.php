<?php
global $nav_category_list_type;?><div class="categoryNav-wrapper"><div
pandaTab
class="categoryNav"
active-class=".current-menu-item,.current-menu-ancestor"
sub-class=".sub-menu"
prev-text='<i class="fa fa-angle-left" aria-hidden="true"></i>'
next-text='<i class="fa fa-angle-right" aria-hidden="true"></i>'
sub-trigger=""
auto-scrolling
><?php
if (has_nav_menu( 'categoryNav' )) {wp_nav_menu( array( 'theme_location' => 'categoryNav','container' => false ) );} else {echo '
<ul class="menu"><li><a>请在后台添加菜单并指派到“文章分类菜单”</a></li></ul>
';}?></div></div><div class="display-switcher-wrapper"><div pandaTab class="display-switcher" active-class=".active" sub-trigger="click" sub-class=".sub-menu"><ul class="menu"><li class="card <?php echo ($nav_category_list_type == 'cards' ? 'active' : ''); ?>"><a data-toggle="tooltip" data-placement="auto top" title="卡片"><i class="fas fa-th"></i></a></li><li class="list <?php echo ($nav_category_list_type == 'lists' ? 'active' : ''); ?>"><a data-toggle="tooltip" data-placement="auto top" title="列表"><i class="fas fa-list"></i></a></li></ul></div></div>
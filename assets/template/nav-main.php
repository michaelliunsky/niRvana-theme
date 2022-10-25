<div class="main-nav"><div class="container"><div class="row"><div class="col-sm-12 col-md-3 logo"><a href="<?php echo home_url(); ?>"><img src="<?php _eopt('logo'); ?>" /></a></div><div class="col-sm-12 col-md-8 col-xl-8_5 menu-wrap"><div class="row"><div class="menuLeft"><div
pandaTab
class="topNav"
active-class=".current-menu-item,.current-menu-ancestor"
sub-class=".sub-menu"
prev-text='<i class="fa fa-angle-left" aria-hidden="true"></i>'
next-text='<i class="fa fa-angle-right" aria-hidden="true"></i>'
sub-trigger=""
auto-scrolling
><?php
if (has_nav_menu( 'topNav' )) {wp_nav_menu( array( 'theme_location' => 'topNav','container' => false ) );} else {echo '
<ul class="menu"><li><a>请在后台添加菜单并指派到“主菜单”</a></li></ul>
';}?></div></div><div class="searchRight visible-xs visible-sm"><div class="normal_searchBtn" @click="this.show_global_search()"><i class="fas fa-search"></i></div></div></div></div><div class="col-md-1 col-xl-0_5 hidden-xs hidden-sm"><div class="normal_searchBtn" data-toggle="tooltip" data-placement="auto bottom" title="<?php _eopt('tooltip_search','') ?>" @click="this.show_global_search()"><i class="fas fa-search"></i></div></div>
<div class="mobileNavMenuBtn visible-xs visible-sm"><div class="menuIcon" @click="this.toggle_mobile_menu()"><div class="bread bread1"></div><div class="bread bread2"></div><div class="bread bread3"></div></div><div class="sidebar" @click="this.toggle_mobile_sidebar()"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"><path id="sidebar" data-name="sidebar" class="cls-1" d="M19,20H3a3,3,0,0,1-3-3V5A3,3,0,0,1,3,2H19a3,3,0,0,1,3,3V17A3,3,0,0,1,19,20ZM13,3H3A2,2,0,0,0,1,5V17a2,2,0,0,0,2,2H13V3Zm8,2a2,2,0,0,0-2-2H14V19h5a2,2,0,0,0,2-2V5Zm-6,7h5v1H15V12Zm0-3h5v1H15V9Zm0-3h5V7H15V6Z"/></svg></div></div></div></div></div>
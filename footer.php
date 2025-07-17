<footer id="footer">
    <div class="container"><?php if (get_option('footer版权文本')) {
        echo '<div class="copyright silver-color">'.get_option('footer版权文本').'</div>';
    }?>
        <div class="beian <?php echo(_opt('cn_record_breakline') ? 'breakline' : ''); ?>"
            :template="beian"></div>
        <div class="copyright silver-color">Theme <strong>niRvana</strong> By <a href="https://blog.mkliu.top/"
                target="_blank"><span>michaelliunsky</span></a></div>
        <script>
            <?php global $pf_dirty_selector;
    if (count($pf_dirty_selector) > 0) {
        echo "pandastudio_framework.article_dirty_selector = ['".implode("','", $pf_dirty_selector)."']";
    }?>
        </script>
        <style>
            <?php echo implode(",", $pf_dirty_selector);?>
                {
                display: inline !important;
                font-size: 0 !important;
                line-height: 0 !important;
                float: left;
            }

            <?php echo "p+".implode(",p+", $pf_dirty_selector);
    echo ',';
    echo "h2+".implode(",h2+", $pf_dirty_selector);
    echo ',';
    echo "h3+".implode(",h3+", $pf_dirty_selector);
    echo ',';?>
                {
                display: none !important;
            }
        </style>
    </div>
</footer>
</div>
<div class="mobile_sidebar visible-xs visible-sm">
    <div manual-template="sidebarMenu"></div>
    <div manual-template="sidebar"></div>
</div>
</div>
<div class="fullscreen_search"></div>
<div class="footer-scripts"><?php get_sidebar(); ?><script type="text/html"
        jQVue-template="fullscreen_search">
        <div class="container">
            <div class="close_btn" @click="this.hide_global_search()"><i class="fas fa-times"></i></div>
            <div class="global_search_form">
                <div class="searchbox clearfix"><input type="search" v-model="global_search_query"
                        placeholder="请输入..."><span class="button" @click="this.global_search()"><span class="icon"><i
                                class="fas fa-search"></i></span></span></div>
                <p class="advanced"><span @click="this.global_search_toggle_advanced()">高级选项 <i
                            class="fas fa-angle-down"></i></span></p>
                <p class="checkbox-group" style="display: none;"><input type="checkbox" v-model="global_search_post"
                        class="jv-checkbox" label="文章"><input type="checkbox" v-model="global_search_gallery"
                        class="jv-checkbox" label="相册"><input type="checkbox" v-model="global_search_prod_title"
                        class="jv-checkbox" label="仅检索标题"></p>
            </div>
        </div>
        <div class="container" style="max-width: 1170px !important;">
            <div class="waterfall postLists cards row"></div>
        </div>
    </script>
    <script type="text/html" jQVue-template="beian">
        <?php $records = _opt('cn_record', array());
    foreach ($records as $rec) {
        $href = $rec['href'] ? " href='".$rec['href']."' target='_blank'" : "";
        $image = $rec['image'] ? "<img src='".$rec['image']."' />" : "";
        echo "<a".$href.">".$image."<span>".$rec['number']."</span></a>";
    }?>
    </script>
    <script type="text/html" jQVue-template="post_list">
        {{#data}}<div class="col-xxs-6 col-xs-4 col-md-3 post-card-wrapper">
            <div class="card">
                {{#thumbnail}}<a href="{{href}}" class="cover" style="background-image: url({{thumbnail}});"
                    showas='padding'></a><a href="{{href}}" showas='img'><img src="{{thumbnail}}" class="cover"
                        alt=""></a>
                {{/thumbnail}}<div class="meta">
                    <div class="date">{{date}}</div>
                    <h2><a href="{{href}}">{{title}}</a></h2>
                    <div class="tags">
                        {{#tags}}<a class="color-{{color}}">{{tag}}</a>
                        {{/tags}}</div>
                    <div class="summary"><span class="likes"><i class="fas fa-heart"></i>
                            {{like}}</span><span class="comments"><i class="fas fa-comments"></i>
                            {{comment}}</span></div>
                </div>
            </div>
        </div>
        {{/data}}{{^data}}<p class="nodata">未找到相关内容！</p>
        {{/data}}
    </script>
    <script type="text/html" jQVue-template="dashang">
        <div class="modal fade" id="dashang" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal"
                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
                            <?php _eopt('dashangTitle', '打赏'); ?>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <p><?php _eopt('dashangText', ''); ?>
                        </p>
                        <div>
                            <ul class="nav nav-tabs" role="tablist"><?php $dashang_Imgs = _opt('dashang_Imgs', array());
    for ($i = 0; $i < count($dashang_Imgs); $i++) {
        $is_active = $i == 0 ? ' class="active"' : '';
        if ($dashang_Imgs[$i]['href']) {
            echo '<li role="presentation"><a href="'.$dashang_Imgs[$i]['href'].'" target="_blank">'.$dashang_Imgs[$i]['name'].'</a></li>';
        } else {
            echo '<li role="presentation"'.$is_active.'><a href="#dashang_'.$i.'" aria-controls="dashang_'.$i.'" role="tab" data-toggle="tab">'.$dashang_Imgs[$i]['name'].'</a></li>';
        }
    }?></ul>
                            <div class="tab-content"><?php for ($i = 0; $i < count($dashang_Imgs); $i++) {
                                $is_active = $i == 0 ? ' active' : '';
                                echo '
<div role="tabpanel" class="tab-pane'.$is_active.'" id="dashang_'.$i.'"><p class="dashangImg_wrapper"><img src="'.$dashang_Imgs[$i]['qrcode'].'" class="dashangImg"></p></div>
';
                            }?></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-primary"
                            data-dismiss="modal">关闭</button></div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/html" jQVue-template="chatTemplate">
        <div class="chatList clearfix server">
            <div class="headimg"></div>
            <div class="chat_pop"><?php _eopt('faq_dafault_content', '欢迎使用FAQ检索服务，请输入关键词开始检索');
    $faq_dafault_list = _opt('faq_dafault_list');
    if ($faq_dafault_list) {
        if (count($faq_dafault_list) > 0) {
            ?><ol><?php foreach ($faq_dafault_list as $item) {
                echo '<li><span @click="this.send_chat_message(\''.$item['list'].'\')">'.$item['list'].'</span></li>';
            }?></ol><?php }
        }?></div>
        </div>
        {{#chat_msg}}<div class="chatList clearfix {{user}}">
            <div class="headimg"></div>
            <div class="chat_pop">
                {{content}}</div>
        </div>
        {{/chat_msg}}
    </script>
    <script type="text/html" jQVue-template="floatTools">
        <div class="floatTools">
            <?php if (_opt('enable_backToTop')) {?><div
                class="tool-button backToTop unavailable" @click="this.backToTop()"><i class="fas fa-rocket"></i></div>
            <?php }if (_opt('enable_assistance')) {?><div
                class="tool-button assistance" @click="this.showAssistance()"><i class="fas fa-headset"></i></div>
            <?php }?><div id="colorSwitch" onclick="switchNightMode()"
                data-description="色彩模式" data-placement="left" class="tool-button pandastudio_format_description"><i
                    class="colorSwitch fas fa-moon"></i></div>
        </div>
    </script>
    <script type="text/html" jQVue-template="assistance_wrapper">
        <div id="chatTemplate"></div>
        <div class="clearfix chat_form"><input type="text" class="chat_input" v-model="chat_input"
                placeholder="请输入检索关键词..."><button @click="this.send_chat_message()">搜索</button></div>
    </script>
    <script type="text/html" jQVue-template="wechatCoverWrapper">
        <div class="container">
            <div class="close_btn" @click="this.hide_wechat_cover()"><i class="fas fa-times"></i></div>
        </div>
        <div class="wechat-cover">
            <div class="renderCoverImg">
                <div class="cover"><img src="{{thumbImg}}">
                    <div class="title">{{title}}</div>
                </div>
                <div class="meta">
                    <div class="description">{{description}}</div>
                    <div class="qrcode"></div>
                    <div class="muted">扫描二维码阅读更多内容</div>
                </div>
            </div>
            <div class="wait"><span>封面生成中...</span></div>
        </div>
    </script><?php wp_footer() ?><?php if (get_option('custom_javascript')) {
        echo "<script>try{"._opt('custom_javascript')."}catch(e){console.log(e)}</script>";
    }if (get_option('custom_css')) {
        echo "<style>"._opt('custom_css')."</style>";
    }?>
</div>
<div class="pageLoader flex-row-middle flex-center">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
</div>
<div :template="dashang"></div>
<div id="assistance" class="unavailable"></div>
</body>
<script type='text/javascript'
    src='<?php echo get_stylesheet_directory_uri(); ?>/extend/js/custom.js'
    id='custom-js'></script>

</html>
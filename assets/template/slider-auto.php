<?php
global $carousels_attrs;?>
<div class="container coverflow-wrapper">
    <div pandaSlider id="coverflow" prev-text='<i class="fas fa-arrow-left"></i>'
        next-text='<i class="fas fa-arrow-right"></i>' view="1" class="unfull" type="auto" allow-anchor-click <?php echo $carousels_attrs; ?>><?php
foreach ($carousels_contents as $item) {?>
        <div class="page <?php echo($item['head_img'] ? 'hasHeadImg' : ''); ?>"
            post-id="<?php echo $item['id']; ?>"
            headImg="<?php echo $item['head_img']; ?>">
            <div class="container flex-row-middle coverflow">
                <div class="content flex-column-middle">
                    <div class="category">
                        <?php echo $item['category']; ?>
                    </div>
                    <h2><?php echo $item['title']; ?>
                    </h2>
                    <div class="read-more"><a
                            href="<?php echo $item['href']; ?>"><i
                                class="fa fa-arrow-right" aria-hidden="true"></i>
                            阅读全文</a></div>
                </div>
                <div class="image flex-center"><a class="card"
                        href="<?php echo $item['href']; ?>"
                        style="background-image: url(<?php echo $item['cover_img']; ?>);"></a>
                </div>
            </div>
        </div><?php
}?>
    </div>
</div>
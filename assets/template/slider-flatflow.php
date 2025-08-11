<?php
global $carousels_attrs;?>
<div class="overflow-hidden relative">
    <div class="container">
        <div pandaSlider id="flatflow" prev-text='<i class="fas fa-arrow-left"></i>'
            next-text='<i class="fas fa-arrow-right"></i>' view="3" allow-anchor-click <?php echo $carousels_attrs; ?>><?php
foreach ($carousels_contents as $item) {?>
            <div class="page"
                post-id="<?php echo $item['id']; ?>">
                <a class="cover"
                    style="background-image:url(<?php echo $item['cover_img']; ?>);"
                    href="<?php echo $item['href']; ?>"></a>
                <div class="title">
                    <?php echo $item['category']; ?>
                </div>
                <h2 class="description">
                    <?php echo $item['title']; ?>
                </h2>
            </div><?php
}?>
        </div>
    </div>
</div>
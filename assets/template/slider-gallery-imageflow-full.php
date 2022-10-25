<?php
global $carousels_attrs,$carousels_contents;$showBox_contain = _meta('showBox_contain') ? 'contain' : 'cover';?><div class="coverflow-wrapper dark-slider gallery"><div pandaSlider id="coverflow" prev-text='<i class="fas fa-arrow-left"></i>' next-text='<i class="fas fa-arrow-right"></i>' view="1" class="gallery" type="image" allow-anchor-click <?php echo $carousels_attrs; ?> background-size="<?php echo $showBox_contain; ?>" style="background-color: <?php _eopt('gallery_background_color','transparent'); ?>"><div class="gallery-bgsize-toggle" data-toggle="tooltip" data-placement="auto left" title="" @click="this.gallery_bgsize_toggle()" data-original-title="图片填充/自适应"><i class="fas fa-expand"></i></div><?php
foreach ($carousels_contents as $item) {?><div class="page" post-id="<?php echo $item['id']; ?>" style="background-image: url(<?php echo $item['full_img']; ?>);"><a
class="container flex-row-middle coverflow"
href="<?php echo $item['full_img']; ?>"
target="_blank"
></a></div><?php
}?></div></div>
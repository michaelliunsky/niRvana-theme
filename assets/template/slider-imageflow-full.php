<?php
global $carousels_attrs,$carousels_contents;$safe_carousels = array();for ($i=0; $i < count($carousels_contents); $i++) {if ($carousels_contents[$i]['slider_img']) {$safe_carousels[] = $carousels_contents[$i];}}?><div class="coverflow-wrapper dark-slider"><div pandaSlider id="coverflow" prev-text='<i class="fas fa-arrow-left"></i>' next-text='<i class="fas fa-arrow-right"></i>' view="1" type="image" allow-anchor-click <?php echo $carousels_attrs; ?>><?php
foreach ($safe_carousels as $item) {?><div class="page" post-id="<?php echo $item['id']; ?>" style="background-image: url(<?php echo $item['slider_img']; ?>);"><a
class="container flex-row-middle coverflow"
href="<?php echo $item['full_img']; ?>"
target="_blank"
></a></div><?php
}?></div></div>
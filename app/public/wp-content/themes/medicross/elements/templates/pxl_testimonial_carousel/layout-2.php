<?php
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');
$col_xxl = $widget->get_setting('col_xxl', '');
if($col_xxl == 'inherit') {
    $col_xxl = $col_xl;
}
$slides_to_scroll = $widget->get_setting('slides_to_scroll');
$arrows = $widget->get_setting('arrows', false);  
$pagination = $widget->get_setting('pagination', false);
$pagination_type = $widget->get_setting('pagination_type', 'bullets');
$pause_on_hover = $widget->get_setting('pause_on_hover', false);
$autoplay = $widget->get_setting('autoplay', false);
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite', false);  
$speed = $widget->get_setting('speed', '500');
$drap = $widget->get_setting('drap', false);  
$opts = [
    'slide_direction'               => 'horizontal',
    'slide_percolumn'               => 1, 
    'slide_mode'                    => 'slide', 
    'slides_to_show'                => (int)$col_xl,
    'slides_to_show_xxl'            => (int)$col_xxl, 
    'slides_to_show_lg'             => (int)$col_lg, 
    'slides_to_show_md'             => (int)$col_md, 
    'slides_to_show_sm'             => (int)$col_sm, 
    'slides_to_show_xs'             => (int)$col_xs, 
    'slides_to_scroll'              => (int)$slides_to_scroll,
    'arrow'                         => (bool)$arrows,
    'pagination'                    => (bool)$pagination,
    'pagination_type'               => $pagination_type,
    'autoplay'                      => (bool)$autoplay,
    'pause_on_hover'                => (bool)$pause_on_hover,
    'pause_on_interaction'          => true,
    'delay'                         => (int)$autoplay_speed,
    'loop'                          => (bool)$infinite,
    'speed'                         => (int)$speed
];
$widget->add_render_attribute( 'carousel', [
    'class'         => 'pxl-swiper-container',
    'dir'           => is_rtl() ? 'rtl' : 'ltr',
    'data-settings' => wp_json_encode($opts)
]);
if(isset($settings['testimonial']) && !empty($settings['testimonial']) && count($settings['testimonial'])): ?>
    <div class="pxl-swiper-slider pxl-testimonial-carousel pxl-testimonial-carousel2 <?php echo esc_attr($settings['style']); ?>" <?php if($drap !== false) : ?>data-cursor-drap="<?php echo esc_html('DRAG', 'medicross'); ?>"<?php endif; ?>>
        <div class="pxl-carousel-inner">

            <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <div class="pxl-swiper-wrapper">
                    <?php foreach ($settings['testimonial'] as $key => $value):
                        $title = isset($value['title']) ? $value['title'] : '';
                        $position = isset($value['position']) ? $value['position'] : '';
                        $desc = isset($value['desc']) ? $value['desc'] : '';
                        $image = isset($value['image']) ? $value['image'] : '';
                        ?>
                        <div class="pxl-swiper-slide">
                            <div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                                <div class="pxl-item--desc el-empty"><?php echo pxl_print_html($desc); ?></div>
                                <div class="pxl-item--holder pxl-flex-middle">
                                    <?php if(!empty($image['id'])) { 
                                        $img = pxl_get_image_by_size( array(
                                            'attach_id'  => $image['id'],
                                            'thumb_size' => '90x90',
                                            'class' => 'no-lazyload',
                                        ));
                                        $thumbnail = $img['thumbnail'];?>
                                        <div class="pxl-item--avatar ">
                                            <?php echo wp_kses_post($thumbnail); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="pxl-item--meta">
                                        <h3 class="pxl-item--title el-empty"><?php echo pxl_print_html($title); ?></h3>
                                        <div class="pxl-item--position el-empty"><?php echo pxl_print_html($position); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php if($pagination !== false || $arrows !== false): ?>
            <div class="pxl-swiper-bottom ">
                <?php if($pagination !== false): ?>
                    <div class="pxl-swiper-dots style-1"></div>
                <?php endif; ?>
                <?php if($arrows !== false): ?>
                    <div class="pxl-wrap-arrow pxl-flex-middle">
                        <div class="pxl-swiper-arrow pxl-swiper-arrow-prev"  style="transform:scalex(-1);">
                            <?php if ($settings['style']==''): ?>
                             <i class="flaticon flaticon-next"></i> 
                         <?php endif ?>
                         <?php if ($settings['style']!=''): ?>
                             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 753.2 476.2" style="enable-background:new 0 0 753.2 476.2;" xml:space="preserve">
                                <polygon points="622.6,107.5 601.4,128.7 695.8,223.1 277,223.1 277,253.1 695.8,253.1 601.4,347.5 622.6,368.7 753.2,238.1 "/>
                                <rect y="223.1" width="283.9" height="33"/>
                            </svg>
                        <?php endif ?>
                    </div>
                    <div class="pxl-swiper-arrow pxl-swiper-arrow-next">
                        <?php if ($settings['style']==''): ?>
                         <i class="flaticon flaticon-next"></i> 
                     <?php endif ?>
                     <?php if ($settings['style']!=''): ?>
                         <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 753.2 476.2" style="enable-background:new 0 0 753.2 476.2;" xml:space="preserve">
                            <polygon points="622.6,107.5 601.4,128.7 695.8,223.1 277,223.1 277,253.1 695.8,253.1 601.4,347.5 622.6,368.7 753.2,238.1 "/>
                            <rect y="223.1" width="283.9" height="33"/>
                        </svg>
                    <?php endif ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

</div>
<?php endif; ?>

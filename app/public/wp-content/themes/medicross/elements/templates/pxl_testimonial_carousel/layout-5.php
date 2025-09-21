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
    <div class="pxl-swiper-slider pxl-testimonial-carousel pxl-testimonial-carousel5" <?php if($drap !== false) : ?>data-cursor-drap="<?php echo esc_html('DRAG', 'medicross'); ?>"<?php endif; ?>>
        <div class="pxl-carousel-inner">

            <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <div class="pxl-swiper-wrapper">
                    <?php foreach ($settings['testimonial'] as $key => $value):
                        $title = isset($value['title']) ? $value['title'] : '';
                        $position = isset($value['position']) ? $value['position'] : '';
                        $desc = isset($value['desc']) ? $value['desc'] : '';
                        $image = isset($value['image']) ? $value['image'] : '';
                        $star = isset($value['star']) ? $value['star'] : '';
                        ?>
                        <div class="pxl-swiper-slide">
                            <div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                                <div class="info-testimonial">
                                    <?php if(!empty($image['id'])) { 
                                        $img = pxl_get_image_by_size( array(
                                            'attach_id'  => $image['id'],
                                            'thumb_size' => '57x57',
                                            'class' => 'no-lazyload',
                                        ));
                                        $thumbnail = $img['thumbnail'];?>
                                        <div class="pxl-item--avatar ">
                                            <?php echo wp_kses_post($thumbnail); ?>
                                        </div>
                                    <?php } ?>
                                    <div class="info">
                                        <h3 class="pxl-item--title">
                                            <?php echo pxl_print_html($title); ?>
                                            <div class="pxl-item--star pxl-item--<?php echo esc_attr($star); ?>">
                                                <svg  width="800px" version="1.1" id="Capa_1" viewBox="0 0 53.867 53.867">
                                                    <polygon  points="26.934,1.318 35.256,18.182 53.867,20.887 40.4,34.013 43.579,52.549 26.934,43.798 10.288,52.549 13.467,34.013 0,20.887 18.611,18.182 "/>
                                                </svg>
                                                <svg  width="800px" version="1.1" id="Capa_1" viewBox="0 0 53.867 53.867">
                                                    <polygon  points="26.934,1.318 35.256,18.182 53.867,20.887 40.4,34.013 43.579,52.549 26.934,43.798 10.288,52.549 13.467,34.013 0,20.887 18.611,18.182 "/>
                                                </svg>
                                                <svg  width="800px" version="1.1" id="Capa_1" viewBox="0 0 53.867 53.867">
                                                    <polygon  points="26.934,1.318 35.256,18.182 53.867,20.887 40.4,34.013 43.579,52.549 26.934,43.798 10.288,52.549 13.467,34.013 0,20.887 18.611,18.182 "/>
                                                </svg>
                                                <svg  width="800px" version="1.1" id="Capa_1" viewBox="0 0 53.867 53.867">
                                                    <polygon  points="26.934,1.318 35.256,18.182 53.867,20.887 40.4,34.013 43.579,52.549 26.934,43.798 10.288,52.549 13.467,34.013 0,20.887 18.611,18.182 "/>
                                                </svg>
                                                <svg  width="800px" version="1.1" id="Capa_1" viewBox="0 0 53.867 53.867">
                                                    <polygon  points="26.934,1.318 35.256,18.182 53.867,20.887 40.4,34.013 43.579,52.549 26.934,43.798 10.288,52.549 13.467,34.013 0,20.887 18.611,18.182 "/>
                                                </svg>
                                            </div>
                                        </h3>
                                        <p class="pxl-item--position"><?php echo pxl_print_html($position); ?></p>
                                    </div>
                                </div>
                                <div class="pxl-item--desc ft-theme-default"><?php echo pxl_print_html($desc); ?></div>
                                <span class="icon-quote">
                                    <span class="quote">
                                        <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 746.34 523.25"><path d="M304.21,573.88q-71,0-127.77-57.8T119.66,344.7q0-129.76,70-211.93T389.39,50.63q46.64,0,73,6.08V150q-28.43-4-73-4.06-69,0-111.54,46.65-40.61,40.59-46.65,107.49,26.34-32.43,85.18-32.45,60.86,0,103.43,41.57t42.6,108.51q0,69-44.62,112.56T304.21,573.88Zm403.59,0q-71,0-127.76-57.8T523.25,344.7q0-129.76,70-211.93T793,50.63q44.58,0,73,6.08V150q-28.43-4-73-4.06-69,0-111.55,46.65-40.59,40.59-46.64,107.49,26.33-32.43,85.18-32.45,60.84,0,103.43,41.57T866,417.72q0,69-44.62,112.56T707.8,573.88Z" transform="translate(-119.66 -50.63)"/></svg>
                                    </span>
                                </span>
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
                            <i class="flaticon flaticon-next"></i>
                        </div>
                        <div class="pxl-swiper-arrow pxl-swiper-arrow-next">
                            <i class="flaticon flaticon-next"></i>
                        </div>
                    </div> 
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>

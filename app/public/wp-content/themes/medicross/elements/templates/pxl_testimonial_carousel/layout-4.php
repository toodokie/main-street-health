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
$opts_thumb = [
    'slide_direction'               => 'horizontal',
    'slides_to_show'                => 'auto', 
    'slide_mode'                    => 'slide',
    'loop'                          => false,
];

$widget->add_render_attribute( 'thumb', [
    'class'         => 'pxl-swiper-thumbs',
    'data-settings' => wp_json_encode($opts_thumb)
]);

$widget->add_render_attribute( 'carousel', [
    'class'         => 'pxl-swiper-container',
    'dir'           => is_rtl() ? 'rtl' : 'ltr',
    'data-settings' => wp_json_encode($opts)
]);
if(isset($settings['testimonial']) && !empty($settings['testimonial']) && count($settings['testimonial'])): ?>
    <div class="pxl-swiper-slider pxl-testimonial-carousel pxl-testimonial-carousel4" <?php if($drap !== false) : ?>data-cursor-drap="<?php echo esc_html('drag', 'medicross'); ?>"<?php endif; ?>>
        <div class="pxl-carousel-inner">

            <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <div class="pxl-swiper-wrapper">
                    <?php foreach ($settings['testimonial'] as $key => $value):
                        $desc = isset($value['desc']) ? $value['desc'] : '';
                        ?>
                        <div class="pxl-swiper-slide">
                            <div class="pxl-item--inner wow <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                                <div class="pxl-item--desc el-empty"><?php echo pxl_print_html($desc); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div <?php pxl_print_html($widget->get_render_attribute_string( 'thumb' )); ?>>
                <div class="pxl-swiper-wrapper">
                    <?php foreach ($settings['testimonial'] as $key => $value_top):
                        $title = isset($value_top['title']) ? $value_top['title'] : '';
                        $position = isset($value_top['position']) ? $value_top['position'] : '';
                        $image = isset($value_top['image']) ? $value_top['image'] : '';
                        ?>
                        <div class="pxl-swiper-slide">
                            <div class="pxl-item--inner">
                                <div class="pxl-wrap-image">
                                    <?php if(!empty($image['id'])) { 
                                        $img = pxl_get_image_by_size( array(
                                            'attach_id'  => $image['id'],
                                            'thumb_size' => 'full',
                                            'class' => 'no-lazyload',
                                        ));
                                        $thumbnail = $img['thumbnail'];?>
                                        <div class="pxl-item--image">
                                            <?php echo wp_kses_post($thumbnail); ?>
                                        </div>
                                    <?php } ?>
                                    <span class="quote">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21" height="17" viewBox="0 0 21 17">
                                            <image id="quote" width="21" height="17" xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAARCAYAAAAyhueAAAABhElEQVQ4jYWUzSttYRTGf0eHnBBySMnXRMlAmZgQmd3JHfgj7sTcxMBEMiHJUCmZKhMTMsDkDi6JgVI33SSlayAfJfFoHe9mtZ2991Nr77d3/Xpa795rvTlJJCgH1AAPSUBMdcC9bVUkAEPAIbAZzNPUAWwA50BzibNKXVjlM5Le9KFjSa0xxse4pLvA3kgaLp08ZrimL81LyqcY/pL0GuhdScUo56FZZziVYmbxwxluSary+WgxGINyKYb1kq4Ca+/GOBMtdlyVfRlVTjl2ohxjj04HHWcYWlw4/luVFnlgzLVLNbANpf1TYAn46/LdQFdYW0+uAg3AJbAC7EUtNa1kPUkadVWMpLCmSeOs+StTGrsArDkmjTXNAb1mep0BtgMDYZ3F2vT9NNODDJAw16Yz4DaLNdOTzw9cXi/AUci8AcsZpr+jH9DjZjiuhVjLFCQdJbB/JFV4uF/SvgOeJS3GRzBEk6T1wESy+W+zfLn7tAi0AP+Ax4yj1oar7wb4X9oB3gFg3qJODoU8FgAAAABJRU5ErkJggg=="/>
                                        </svg>
                                    </span>
                                </div>

                                <div class="pxl-item--meta">
                                    <h3 class="pxl-item--title el-empty"><?php echo pxl_print_html($title); ?></h3>
                                    <div class="pxl-item--position el-empty"><?php echo pxl_print_html($position); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php if($pagination !== false || $arrows !== false): ?>
            <div class="pxl-swiper-bottom pxl-flex-middle">
                <?php if($pagination !== false): ?>
                    <div class="pxl-swiper-dots style-1"></div>
                <?php endif; ?>
                <?php if($arrows !== false): ?>
                    <div class="pxl-wrap-arrow pxl-flex-middle">
                        <div class="pxl-swiper-arrow pxl-swiper-arrow-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" x="0" y="0" viewBox="0 0 1560 1560" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g transform="matrix(1,0,0,1,4.999999999999545,4.547473508864641e-13)"><path d="M1524 811.8H36c-17.7 0-32-14.3-32-32s14.3-32 32-32h1410.7l-194.2-194.2c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l248.9 248.9c9.2 9.2 11.9 22.9 6.9 34.9-5 11.9-16.7 19.7-29.6 19.7z" fill="#121c27" opacity="1" data-original="#000000"></path><path d="M1274.8 1061c-8.2 0-16.4-3.1-22.6-9.4-12.5-12.5-12.5-32.8 0-45.3l249.2-249.2c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-249.2 249.2c-6.3 6.3-14.5 9.4-22.7 9.4z" fill="#121c27" opacity="1" data-original="#000000"></path></g></svg>
                        </div>
                        <div class="pxl-swiper-arrow pxl-swiper-arrow-next">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="36" x="0" y="0" viewBox="0 0 1560 1560" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g transform="matrix(1,0,0,1,4.999999999999545,4.547473508864641e-13)"><path d="M1524 811.8H36c-17.7 0-32-14.3-32-32s14.3-32 32-32h1410.7l-194.2-194.2c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l248.9 248.9c9.2 9.2 11.9 22.9 6.9 34.9-5 11.9-16.7 19.7-29.6 19.7z" fill="#121c27" opacity="1" data-original="#000000"></path><path d="M1274.8 1061c-8.2 0-16.4-3.1-22.6-9.4-12.5-12.5-12.5-32.8 0-45.3l249.2-249.2c12.5-12.5 32.8-12.5 45.3 0s12.5 32.8 0 45.3l-249.2 249.2c-6.3 6.3-14.5 9.4-22.7 9.4z" fill="#121c27" opacity="1" data-original="#000000"></path></g></svg>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

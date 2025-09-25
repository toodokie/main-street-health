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
?>
<?php if(isset($settings['team']) && !empty($settings['team']) && count($settings['team'])):
$image_size = !empty($settings['img_size']) ? $settings['img_size'] : 'full'; ?>

<style>
/* Team Carousel Layout 1 - Complete Navigation Redesign */
.pxl-team-carousel1 .pxl-item--desc {
    word-wrap: break-word !important;
    word-break: normal !important;
    white-space: normal !important;
    overflow-wrap: break-word !important;
    hyphens: none !important;
    line-height: 1.6 !important;
    text-align: center !important;
}

/* Hide the original arrow wrap */
.pxl-team-carousel1 .pxl-swiper-arrow-wrap {
    display: none !important;
}

/* Create new combined navigation container */
.pxl-team-carousel1 .pxl-navigation-combined {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 20px !important;
    margin: 30px 0 0 0 !important;
    position: relative !important;
}

/* Style arrows for inline layout */
.pxl-team-carousel1 .pxl-nav-arrow {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50% !important;
    background: #A8C8A3 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    border: none !important;
    outline: none !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

.pxl-team-carousel1 .pxl-nav-arrow:hover {
    background: #5CB3CC !important;
    transform: scale(1.05) !important;
}

.pxl-team-carousel1 .pxl-nav-arrow i {
    color: #fff !important;
    font-size: 16px !important;
}

/* Only modify pagination position when inside combined navigation */
.pxl-team-carousel1 .pxl-navigation-combined .pxl-swiper-dots {
    position: relative !important;
    bottom: auto !important;
    margin: 0 !important;
}

/* Center all text content */
.pxl-team-carousel1 .pxl-item--meta {
    text-align: center !important;
}

.pxl-team-carousel1 .pxl-item--title {
    text-align: center !important;
}

.pxl-team-carousel1 .pxl-item--position {
    text-align: center !important;
}

/* Hide the share icon that appears on hover */
.pxl-team-carousel1 .pxl-social--wrap .fa-share-alt {
    display: none !important;
}

/* Optional: Hide the entire social wrap if you want to remove all social icons */
/* .pxl-team-carousel1 .pxl-social--wrap {
    display: none !important;
} */
</style>

<div class="pxl-swiper-slider pxl-team pxl-team-carousel1 " <?php if($drap !== false) : ?>data-cursor-drap="<?php echo esc_html('DRAG', 'medicross'); ?>"<?php endif; ?>>
    <div class="pxl-carousel-inner">
        <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
            <div class="pxl-swiper-wrapper">
                <?php foreach ($settings['team'] as $key => $value):
                    $title = isset($value['title']) ? $value['title'] : '';
                    $position = isset($value['position']) ? $value['position'] : '';
                    $desc = isset($value['desc']) ? $value['desc'] : '';
                    $image = isset($value['image']) ? $value['image'] : '';
                    $link_key = $widget->get_repeater_setting_key( 'item_link', 'value', $key );
                    if ( ! empty( $value['item_link']['url'] ) ) {
                        $widget->add_render_attribute( $link_key, 'href', $value['item_link']['url'] );

                        if ( $value['item_link']['is_external'] ) {
                            $widget->add_render_attribute( $link_key, 'target', '_blank' );
                        }

                        if ( $value['item_link']['nofollow'] ) {
                            $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                        }
                    }
                    $link_attributes = $widget->get_render_attribute_string( $link_key );
                    $social = isset($value['social']) ? $value['social'] : '';
                    if(!empty($image['id'])) { ?>
                        <div class="pxl-swiper-slide">
                            <div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                                <?php if(!empty($image['id'])) { 
                                    $img = pxl_get_image_by_size( array(
                                        'attach_id'  => $image['id'],
                                        'thumb_size' => $image_size,
                                        'class' => 'no-lazyload',
                                    ));
                                    $thumbnail = $img['thumbnail'];
                                    $thumbnail_url = $img['url'];
                                    ?>
                                    <div class="pxl-item--image" style="background-image:url(<?php echo esc_url($thumbnail_url); ?>);">
                                        <a <?php echo implode( ' ', [ $link_attributes ] ); ?>>
                                        </a>
                                        <?php if(!empty($social)): ?>
                                            <div class="pxl-social--wrap">
                                                <div class="pxl-social">
                                                    <?php  $team_social = json_decode($social, true); ?>
                                                    <?php foreach ($team_social as $value): ?>
                                                        <a href="<?php echo esc_url($value['url']); ?>" target="_blank"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                                    <?php endforeach; ?>
                                                    <?php /* Share icon removed - uncomment line below to restore */ ?>
                                                    <?php /* <a ><i class="fas fa-share-alt"></i></a> */ ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php } ?>
                                <div class="pxl-item--holder ">
                                    <div class="pxl-item--meta pxl-flex-grow ">
                                        <h3 class="pxl-item--title">    
                                            <a <?php echo implode( ' ', [ $link_attributes ] ); ?>><?php echo pxl_print_html($title); ?></a>
                                        </h3>
                                        <div class="pxl-item--position"><?php echo pxl_print_html($position); ?></div>
                                        <div class="pxl-item--desc"><?php echo pxl_print_html($desc); ?></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if($arrows !== false && $pagination !== false): ?>
            <!-- Combined Navigation: Left Arrow - Dots - Right Arrow -->
            <div class="pxl-navigation-combined">
                <button class="pxl-nav-arrow pxl-nav-prev" onclick="this.closest('.pxl-team-carousel1').querySelector('.pxl-swiper-arrow-prev').click()">
                    <i class="flaticon flaticon-next" style="transform:scaleX(-1);"></i>
                </button>

                <div class="pxl-swiper-dots style-1"></div>

                <button class="pxl-nav-arrow pxl-nav-next" onclick="this.closest('.pxl-team-carousel1').querySelector('.pxl-swiper-arrow-next').click()">
                    <i class="flaticon flaticon-next"></i>
                </button>
            </div>
        <?php elseif($arrows !== false): ?>
            <!-- Arrows Only - Centered Style -->
            <div class="pxl-navigation-combined">
                <button class="pxl-nav-arrow pxl-nav-prev" onclick="this.closest('.pxl-team-carousel1').querySelector('.pxl-swiper-arrow-prev').click()">
                    <i class="flaticon flaticon-next" style="transform:scaleX(-1);"></i>
                </button>

                <button class="pxl-nav-arrow pxl-nav-next" onclick="this.closest('.pxl-team-carousel1').querySelector('.pxl-swiper-arrow-next').click()">
                    <i class="flaticon flaticon-next"></i>
                </button>
            </div>
        <?php elseif($pagination !== false): ?>
            <!-- Pagination Only -->
            <div class="pxl-swiper-dots style-1"></div>
        <?php endif; ?>

        <!-- Hidden original arrows for functionality -->
        <div style="display: none;">
            <div class="pxl-swiper-arrow pxl-swiper-arrow-prev"></div>
            <div class="pxl-swiper-arrow pxl-swiper-arrow-next"></div>
        </div>
    </div>
</div>
<?php endif; ?>

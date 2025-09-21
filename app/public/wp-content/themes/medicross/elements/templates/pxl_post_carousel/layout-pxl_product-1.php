<?php
$html_id = pxl_get_element_id($settings);
$select_post_by = $widget->get_setting('select_post_by', '');
$source = $post_ids = [];
if($select_post_by === 'post_selected'){
    $post_ids = $widget->get_setting('source_'.$settings['post_type'].'_post_ids', '');
}else{
    $source  = $widget->get_setting('source_'.$settings['post_type'], '');
}
$orderby = $widget->get_setting('orderby', 'date');
$order = $widget->get_setting('order', 'desc');
$limit = $widget->get_setting('limit', 6);
$settings['layout']    = $settings['layout_'.$settings['post_type']];
extract(pxl_get_posts_of_grid('pxl_product', [
    'source' => $source,
    'orderby' => $orderby,
    'order' => $order,
    'limit' => $limit,
    'post_ids' => $post_ids,
]));

$pxl_animate = $widget->get_setting('pxl_animate', '');
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');
$col_xxl = $widget->get_setting('col_xxl', '');
if($col_xxl == 'inherit') {
    $col_xxl = $col_xl;
}
$slides_to_scroll = $widget->get_setting('slides_to_scroll', '');

$arrows = $widget->get_setting('arrows', false);
$pagination = $widget->get_setting('pagination', false);
$pagination_type = $widget->get_setting('pagination_type', 'bullets');
$pause_on_hover = $widget->get_setting('pause_on_hover', false);
$autoplay = $widget->get_setting('autoplay', false);
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite', false);
$speed = $widget->get_setting('speed', '500');
$center = $widget->get_setting('center', false);
$drap = $widget->get_setting('drap', false);

$img_size = $widget->get_setting('img_size');
$show_excerpt = $widget->get_setting('show_excerpt');
$num_words = $widget->get_setting('num_words');
$show_button = $widget->get_setting('show_button');
$button_text = $widget->get_setting('button_text');

$opts = [
    'slide_direction'               => 'horizontal',
    'slide_percolumn'               => '1', 
    'slide_mode'                    => 'slide', 
    'slides_to_show'                => $col_xl, 
    'slides_to_show_xxl'            => $col_xxl, 
    'slides_to_show_lg'             => $col_lg, 
    'slides_to_show_md'             => $col_md, 
    'slides_to_show_sm'             => $col_sm, 
    'slides_to_show_xs'             => $col_xs, 
    'slides_to_scroll'              => $slides_to_scroll,
    'arrow'                         => $arrows,
    'pagination'                    => $pagination,
    'pagination_type'               => $pagination_type,
    'autoplay'                      => $autoplay,
    'pause_on_hover'                => $pause_on_hover,
    'pause_on_interaction'          => 'true',
    'delay'                         => $autoplay_speed,
    'loop'                          => $infinite,
    'speed'                         => $speed,
    'center'                        => $center,
    'drap'                          => $drap
];

$widget->add_render_attribute( 'carousel', [
    'class'         => 'pxl-swiper-container pxl-product-carousel pxl-product-carousel-1',
    'dir'           => is_rtl() ? 'rtl' : 'ltr',
    'data-settings' => wp_json_encode($opts)
]);

if(is_array($posts) && count($posts) <= 0){
    echo '<div class="pxl-no-post-grid">'.esc_html__('No Products Found', 'medicross'). '</div>';
    return;
} ?>

<div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
    <div class="pxl-carousel-inner">
        <div class="pxl-swiper-wrapper swiper-wrapper">
            <?php
            foreach ($posts as $key => $post):
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => get_post_thumbnail_id( $post->ID ),
                    'thumb_size' => $img_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                $product_categories = get_the_terms( $post->ID, 'pxl-product-category' );
                $excerpt = get_the_excerpt( $post->ID );
                $show_excerpt = !empty($show_excerpt) ? $show_excerpt : 'true';
                $num_words = !empty($num_words) ? $num_words : 25;
                if(!empty($excerpt) && $show_excerpt == 'true') {
                    $excerpt = wp_trim_words( $excerpt, $num_words, null );
                }
                ?>
                <div class="pxl-swiper-slide swiper-slide">
                    <div class="grid-item-inner <?php echo esc_attr($pxl_animate); ?>">
                        <div class="pxl-item--inner pxl-product-item pxl-product-item-1">
                            <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)) : ?>
                                <div class="pxl-item--featured">
                                    <div class="pxl-item--image pxl-cursor--cta">
                                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="pxl-item--body">
                                <?php if(!empty($product_categories) && is_array($product_categories)): ?>
                                    <div class="pxl-item--category">
                                        <?php $category_name = [];
                                        foreach($product_categories as $category) {
                                            $category_name[] = $category->name;
                                        }
                                        echo implode(', ', $category_name); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="pxl-item--title">
                                    <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_attr(get_the_title($post->ID)); ?></a>
                                </h3>
                                
                                <?php if($show_excerpt == 'true'): ?>
                                    <div class="pxl-item--content">
                                        <?php echo wp_kses_post($excerpt); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($show_button == 'true' && !empty($button_text)) : ?>
                                    <div class="pxl-item--readmore">
                                        <a class="btn-readmore" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                            <span><?php echo pxl_print_html($button_text); ?></span>
                                            <i class="flaticon flaticon-next"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if($arrows !== 'false'): ?>
        <div class="pxl-swiper-arrow pxl-swiper-arrow-next"><i class="caseicon-angle-arrow-right"></i></div>
        <div class="pxl-swiper-arrow pxl-swiper-arrow-prev"><i class="caseicon-angle-arrow-left"></i></div>
    <?php endif; ?>
    <?php if($pagination !== 'false'): ?>
        <div class="pxl-swiper-dots"></div>
    <?php endif; ?>
</div>
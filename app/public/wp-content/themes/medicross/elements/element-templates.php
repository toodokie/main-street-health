<?php 
use Elementor\Embed;
if(!function_exists('medicross_get_post_grid')){
    function medicross_get_post_grid($posts = [], $settings = []){ 
        if (empty($posts) || !is_array($posts) || empty($settings) || !is_array($settings)) {
            return false;
        }
        switch ($settings['layout']) {
            case 'post-1':
            medicross_get_post_grid_layout1($posts, $settings);
            break;

            case 'post-2':
            medicross_get_post_grid_layout2($posts, $settings);
            break;

            case 'portfolio-1':
            medicross_get_portfolio_grid_layout1($posts, $settings);
            break;

            case 'portfolio-2':
            medicross_get_portfolio_grid_layout2($posts, $settings);
            break;

            case 'portfolio-3':
            medicross_get_portfolio_grid_layout3($posts, $settings);
            break;

            case 'service-1':
            medicross_get_service_grid_layout1($posts, $settings);
            break;

            case 'service-2':
            medicross_get_service_grid_layout2($posts, $settings);
            break;

            case 'service-3':
            medicross_get_service_grid_layout3($posts, $settings);
            break;

            case 'industries-1':
            medicross_get_industries_grid_layout1($posts, $settings);
            break;

            case 'pxl_product-1':
            medicross_get_product_grid_layout1($posts, $settings);
            break;

            case 'service-2':
            medicross_get_service_grid_layout2($posts, $settings);
            break;

            default:
            return false;
            break;
        }
    }
}

// Start Post Grid
//--------------------------------------------------
function medicross_get_post_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '370x418';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = ''; ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)):
                    $img_id = get_post_thumbnail_id($post->ID);
                    $img          = pxl_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $images_size
                    ) );
                    $thumbnail    = $img['thumbnail']; 
                    ?>
                    <div class="pxl-post--meta pxl-flex-middle">
                        <?php if($show_author == 'true'): ?>
                            <div class="pxl-item--author"><span>
                                <?php echo get_avatar( get_the_author_meta( 'ID' ), 'thumbnail' ); ?>
                                <?php echo esc_html__('by','medicross') ?> <?php the_author_posts_link(); ?>
                            </div>
                        <?php endif; ?>

                        <?php if($show_date == 'true'): ?>
                            <div class="post-date">
                                <?php echo get_the_date('d F Y', $post->ID)  ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="pxl-post--featured hover-imge-effect2">
                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                            <?php echo wp_kses_post($thumbnail); ?>
                        </a>
                        <?php if($show_category == 'true'): ?>
                            <div class="pxl-post--category">
                                <?php the_terms( $post->ID, 'category', '', ' ' ); ?>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>


                <h3 class="pxl-post--title title-hover-line"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a></h3>
                <?php if($show_excerpt == 'true'): ?>
                    <div class="pxl-post--content">
                        <?php if($show_excerpt == 'true'): ?>
                            <?php
                            echo wp_trim_words( $post->post_excerpt, $num_words, null );
                            ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if($show_button == 'true') : ?>
                    <div class="pxl-post--button">
                        <a class="btn--readmore" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                            <span class="btn--text">
                                <?php if(!empty($button_text)) {
                                    echo esc_attr($button_text);
                                } else {
                                    echo esc_html__('Continue Reading', 'medicross');
                                } ?>
                            </span>
                            <i class="flaticon flaticon-next"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    endforeach;
endif;
}

function medicross_get_post_grid_layout2($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '746x334';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = ''; 
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-inner-content <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="content-col-1 col-custom">
                        <h3 class="pxl-post--title "><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a></h3>
                        <div class="date"><?php echo get_the_date('d F y', $post->ID)  ?></div>
                        <div class="pxl-meta-bottom  d-flex">
                            <div class="author"><?php echo esc_html__('by','medicross') ?><span> <?php the_author_posts_link(); ?></span> / &nbsp</div>
                            <div class="pxl-post--category">
                                <?php the_terms( $post->ID, 'category', '', ' , ' ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="content-col-2 col-custom">
                        <div class="pxl-post--content">
                            <?php
                            echo wp_trim_words( $post->post_excerpt, $num_words, null );
                            ?>
                        </div>
                        <div class="pxl-post--button">
                            <a class="btn--readmore" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                <span class="btn--text">
                                    <?php if(!empty($button_text)) {
                                        echo pxl_print_html($button_text);
                                    } else {
                                        echo esc_html__('continue reading', 'medicross');
                                    } ?>
                                </span>
                                <i class="flaticon flaticon-next"></i>
                            </a>
                        </div>
                    </div>
                    <div class="content-col-3 col-custom">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)):
                        $img_id = get_post_thumbnail_id($post->ID);
                        $img          = pxl_get_image_by_size( array(
                            'attach_id'  => $img_id,
                            'thumb_size' => $images_size
                        ) );
                        $thumbnail    = $img['thumbnail']; 
                        ?>
                        <div class="pxl-post--featured ">
                            <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    endforeach;
endif;
}
// End Post Grid
//--------------------------------------------------

// Start Portfolio Grid
//--------------------------------------------------
function medicross_get_portfolio_grid_layout1($posts = [], $settings = []){ 
    extract($settings);

    $images_size = !empty($img_size) ? $img_size : '600x610';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                if($img_id) {
                    $img = pxl_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $images_size,
                        'class' => 'no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];
                } else {
                    $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
                }  ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                        <div class="pxl-post--featured ">
                            <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                                <?php echo wp_kses_post($thumbnail); ?>
                            </a>    
                        </div>
                        <div class="pxl-front">
                            <?php if($show_category == 'true'): ?>
                                <div class="pxl-post--category">
                                    <?php the_terms( $post->ID, 'portfolio-category', '', ' - ' ); ?>
                                </div>
                            <?php endif; ?>
                            <h5 class="pxl-post--title">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                            </h5>
                        </div>
                        <div class="pxl-post--holder">
                            <?php if($show_category == 'true'): ?>
                                <div class="pxl-post--category">
                                    <?php the_terms( $post->ID, 'portfolio-category', '', ' - ' ); ?>
                                </div>
                            <?php endif; ?>
                            <h5 class="pxl-post--title">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                            </h5>
                            <?php if ( !empty($pxl_icon['value']) ) : ?>
                                <div class="pxl-item--icon">
                                    <?php \Elementor\Icons_Manager::render_icon( $settings['pxl_icon'], [ 'aria-hidden' => 'true', 'class' => '' ], 'i' ); ?>
                                </div>
                            <?php endif; ?>
                            <?php if($show_excerpt == 'true'): ?>
                                <div class="pxl-post--content">
                                    <?php if($show_excerpt == 'true'): ?>
                                        <?php
                                        echo wp_trim_words( $post->post_excerpt, $num_words, null );
                                        ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <a class="btn-readmore" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                                <i class="bootstrap-icons bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;
    endif;
}
function medicross_get_portfolio_grid_layout2  ($posts = [], $settings = []){ 
    extract($settings);

    $images_size = !empty($img_size) ? $img_size : '600x610';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                if($img_id) {
                    $img = pxl_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $images_size,
                        'class' => 'no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];
                } else {
                    $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
                }  ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                        <div class="pxl-post--featured hover-imge-effect3"><?php echo wp_kses_post($thumbnail); ?></div>
                        <div class="pxl-post--holder">
                            <?php if($show_category == 'true'): ?>
                                <div class="pxl-post--category">
                                    <?php the_terms( $post->ID, 'portfolio-category', '', ' - ' ); ?>
                                </div>
                            <?php endif; ?>
                            <h5 class="pxl-post--title">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                                    <?php echo esc_attr(get_the_title($post->ID)); ?>
                                </a>
                            </h5>
                            <?php if($show_excerpt == 'true'): ?>
                                <div class="pxl-post--content">
                                    <?php if($show_excerpt == 'true'): ?>
                                        <?php
                                        echo wp_trim_words( $post->post_excerpt, $num_words, null );
                                        ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <a class="btn--readmore" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                <span class="btn--text">
                                    <?php if(!empty($button_text)) {
                                        echo pxl_print_html($button_text);
                                    } else {
                                        echo esc_html__('Read more', 'medicross');
                                    } ?>
                                </span>
                                <i class="flaticon flaticon-next"></i>
                            </a>
                        </div>
                        
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;
    endif;
}

function medicross_get_portfolio_grid_layout3($posts = [], $settings = []){ 
    extract($settings);

    $images_size = !empty($img_size) ? $img_size : 'full';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xxl-{$col_xxl} col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            $client = get_post_meta($post->ID, 'client', true);
            $date_finish = get_post_meta($post->ID, 'date_finish', true);
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xxl-{$col_xxl} col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                if($img_id) {
                    $img = pxl_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $images_size,
                        'class' => 'no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];
                } else {
                    $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
                }  ?>
                <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                    <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                        <div class="pxl-post--holder">
                            <?php if($show_category == 'true'): ?>
                                <div class="pxl-post--category">
                                    <?php the_terms( $post->ID, 'portfolio-category', '', ' - ' ); ?>
                                </div>
                            <?php endif; ?>
                            <h5 class="pxl-post--title">
                                <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                                    <?php echo esc_attr(get_the_title($post->ID)); ?>
                                </a>
                            </h5>
                            <?php if($show_excerpt == 'true'): ?>
                                <div class="pxl-post--content">
                                    <?php if($show_excerpt == 'true'): ?>
                                        <?php
                                        echo wp_trim_words( $post->post_excerpt, $num_words, null );
                                        ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pxl-post--featured hover-imge-effect3">
                            <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                                <?php echo wp_kses_post($thumbnail); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;
    endif;
}


// End Portfolio Grid
//--------------------------------------------------

// Start Service Grid
//--------------------------------------------------
function medicross_get_service_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    $images_size = !empty($img_size) ? $img_size : 'full';
    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';
            $img_id = get_post_thumbnail_id($post->ID);
            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            $service_icon_type = get_post_meta($post->ID, 'service_icon_type', true);
            $service_icon_font = get_post_meta($post->ID, 'service_icon_font', true);
            $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true);
            
            if($img_id) {
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }  ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-post--featured">
                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                            <?php echo wp_kses_post($thumbnail); ?>
                            <?php if($service_icon_type == 'icon' && !empty($service_icon_font)) : ?>
                                <span class="pxl-post--icon">
                                    <i class="<?php echo esc_attr($service_icon_font); ?>"></i>
                                </span>
                            <?php endif; ?>
                            <?php if($service_icon_type == 'image' && !empty($service_icon_img)) : 
                                $icon_img = pxl_get_image_by_size( array(
                                    'attach_id'  => $service_icon_img['id'],
                                    'thumb_size' => 'full',
                                ));
                                $icon_thumbnail = $icon_img['thumbnail'];
                                ?>
                                <span class="pxl-post--icon">
                                    <?php echo wp_kses_post($icon_thumbnail); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="pxl-holder-content">
                        <h3 class="pxl-post--title">
                            <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                        </h3>

                        <?php if($show_excerpt == 'true'): ?>
                            <div class="pxl-post--content">
                                <?php if($show_excerpt == 'true'): ?>
                                    <?php
                                    echo wp_trim_words( $post->post_excerpt, 20, null );
                                    ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if($show_button == 'true') : ?>
                            <div class="pxl-post--readmore">
                                <a class="btn-readmore" href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                    <span><?php if(!empty($button_text)) {
                                        echo esc_attr($button_text);
                                    } else {
                                        echo esc_html__('Read More', 'medicross');
                                    } ?></span>
                                    <i class="flaticon flaticon-next"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach;
    endif;
}
function medicross_get_service_grid_layout2($posts = [], $settings = []){ 
    extract($settings);
    $images_size = !empty($img_size) ? $img_size : 'full';
    if (is_array($posts)):
        $count_p = 1;
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';
            $img_id = get_post_thumbnail_id($post->ID);
            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            $service_icon_type = get_post_meta($post->ID, 'service_icon_type', true);
            $service_icon_font = get_post_meta($post->ID, 'service_icon_font', true);
            $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true); 

            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <span class="count-post">
                        <?php if ($count_p < 10) {
                            echo pxl_print_html('0'.$count_p++.'.');
                        } else {
                            echo pxl_print_html($count_p++.'.');
                        } ?>
                    </span>
                    <?php 
                    // Icon fix for pxl_product posts
                    if ($post->post_type === 'pxl_product') {
                        $product_icon_type = get_post_meta($post->ID, '_pxl_product_icon_type', true);
                        $product_icon_img = get_post_meta($post->ID, '_pxl_product_icon_img', true);
                        if ($product_icon_type === 'image' && $product_icon_img) {
                            $service_icon_type = 'image';
                            $service_icon_img = ['id' => intval($product_icon_img)];
                        }
                    }
                    ?>
                    <?php if($service_icon_type == 'icon' && !empty($service_icon_font)) : ?>
                        <span class="pxl-post--icon">
                            <i class="<?php echo esc_attr($service_icon_font); ?>"></i>
                        </span>
                    <?php endif; ?>
                    <?php if($service_icon_type == 'image' && !empty($service_icon_img)) : 
                        $icon_img = pxl_get_image_by_size( array(
                            'attach_id'  => $service_icon_img['id'],
                            'thumb_size' => 'full',
                        ));
                        $icon_thumbnail = $icon_img['thumbnail'];
                        ?>
                        <span class="pxl-post--icon">
                            <?php echo wp_kses_post($icon_thumbnail); ?>
                        </span>
                    <?php endif; ?>
                    <?php if(isset($post_type) && $post_type==='pxl_product' && (empty($service_icon_type) || $service_icon_type==='none')) : ?>
                        <?php $term_icon = function_exists('msh_pxl_product_term_icon') ? msh_pxl_product_term_icon($post->ID) : null; ?>
                        <?php if ($term_icon && $term_icon['type']==='icon' && !empty($term_icon['font'])) : ?>
                            <span class="pxl-post--icon">
                                <i class="<?php echo esc_attr($term_icon['font']); ?>"></i>
                            </span>
                        <?php elseif ($term_icon && $term_icon['type']==='image' && !empty($term_icon['id'])) : ?>
                            <?php $timg = pxl_get_image_by_size( array('attach_id'=>$term_icon['id'],'thumb_size'=>'full') ); $tthumb = $timg['thumbnail']; ?>
                            <span class="pxl-post--icon">
                                <?php echo wp_kses_post($tthumb); ?>
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php 
                    // Get title tag from settings for pxl_product
                    $title_tag = 'h3'; // default
                    if (isset($post_type) && $post_type === 'pxl_product' && !empty($product_title_tag)) {
                        $title_tag = $product_title_tag;
                    }
                    ?>
                    <<?php echo esc_attr($title_tag); ?> class="pxl-post--title">
                        <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                    </<?php echo esc_attr($title_tag); ?>>
                    <?php if($show_excerpt == 'true'): ?>
                        <div class="pxl-post--content">
                            <?php if($show_excerpt == 'true'): ?>
                                <?php
                                echo wp_trim_words( $post->post_excerpt, $num_words, null );
                                ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($show_button == 'true') : ?>
                        <div class="pxl-post--readmore">
                            <a class="btn-readmore" href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                <span><?php if(!empty($button_text)) {
                                    echo esc_attr($button_text);
                                } else {
                                    echo esc_html__('Read More', 'medicross');
                                } ?></span>
                                <i class="flaticon flaticon-next"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if ($custom_box=='true'): ?>
            <div class="custom-box <?php echo esc_attr($item_class . ' ' . $filter_class); ?>" >
                <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s" style="background-image: url(<?php echo esc_url($image_custom_box['url']); ?>);">
                    <div class="wrap-content">
                        <h4 class="title-box">
                            <?php echo pxl_print_html($custom_text); ?>
                        </h4>
                        <?php if (!empty($button_text_box)): ?>
                            <a class="button-box btn btn-glossy" href="<?php echo esc_url($button_text_link); ?>">
                                <?php echo pxl_print_html($button_text_box); ?>
                                <i class="flaticon flaticon-next"></i>
                            </a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
    <?php endif;
}
function medicross_get_service_grid_layout3($posts = [], $settings = []){ 
    extract($settings);
    $images_size = !empty($img_size) ? $img_size : 'full';
    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';
            $img_id = get_post_thumbnail_id($post->ID);
            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            $service_icon_type = get_post_meta($post->ID, 'service_icon_type', true);
            $service_icon_font = get_post_meta($post->ID, 'service_icon_font', true);
            $service_icon_img = get_post_meta($post->ID, 'service_icon_img', true); 
            if($img_id) {
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }  ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-post--featured">
                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                            <?php echo wp_kses_post($thumbnail); ?>
                        </a>
                    </div>
                    <div class="pxl-holder-content">
                        <h3 class="pxl-post--title">
                            <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                        </h3>

                        <?php if($show_excerpt == 'true'): ?>
                            <div class="pxl-post--content">
                                <?php if($show_excerpt == 'true'): ?>
                                    <?php
                                    echo wp_trim_words( $post->post_excerpt, $num_words, null );
                                    ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if($show_button == 'true') : ?>
                            <div class="pxl-post--readmore">
                                <a class="btn-readmore" href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                    <span><?php if(!empty($button_text)) {
                                        echo esc_attr($button_text);
                                    } else {
                                        echo esc_html__('Read More', 'medicross');
                                    } ?></span>
                                    <i class="flaticon flaticon-next"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if($service_icon_type == 'icon' && !empty($service_icon_font)) : ?>
                        <span class="pxl-post--icon">
                            <i class="<?php echo esc_attr($service_icon_font); ?>"></i>
                        </span>
                    <?php endif; ?>
                    <?php if($service_icon_type == 'image' && !empty($service_icon_img)) : 
                        $icon_img = pxl_get_image_by_size( array(
                            'attach_id'  => $service_icon_img['id'],
                            'thumb_size' => 'full',
                        ));
                        $icon_thumbnail = $icon_img['thumbnail'];
                        ?>
                        <span class="pxl-post--icon">
                            <?php echo wp_kses_post($icon_thumbnail); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach;
    endif;
}

//--------------------------------------------------
function medicross_get_industries_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    $images_size = !empty($img_size) ? $img_size : 'full';
    if (is_array($posts)):
        $count_pos = 1;
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';
            $img_id = get_post_thumbnail_id($post->ID);
            $industries_external_link = get_post_meta($post->ID, 'industries_external_link', true);
            $position = get_post_meta($post->ID, 'position', true);
            if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                if($img_id) {
                    $img = pxl_get_image_by_size( array(
                        'attach_id'  => $img_id,
                        'thumb_size' => $images_size,
                        'class' => 'no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];
                } else {
                    $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
                } 
            endif;?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-post--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-post--featured hover-imge-effect3">
                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>">   
                            <?php echo wp_kses_post($thumbnail); ?>
                        </a>
                    </div>
                    <h3 class="pxl-post--title">
                        <a href="<?php if(!empty($industries_external_link)) { echo esc_url($industries_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a>
                    </h3>
                    <p class="position">
                        <?php echo pxl_print_html($position); ?>
                    </p>
                </div>
            </div>
        <?php endforeach;
    endif;
}
// End Service Grid
//-------------------------------------------------

// Start Product Grid
//--------------------------------------------------
function medicross_get_product_grid_layout1($posts = [], $settings = []){ 
    extract($settings);

    $images_size = !empty($img_size) ? $img_size : '557x600';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == 'col-66') {
                    $col_xl_m = '66-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == 'col-66') {
                    $col_lg_m = '66-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-product-inner">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): 
                        $img_id = get_post_thumbnail_id($post->ID);
                        $img = medicross_get_image_by_size( array(
                            'attach_id'  => $img_id,
                            'thumb_size' => $images_size,
                            'class' => 'no-lazyload',
                        ));
                        $thumbnail = $img['thumbnail'];
                        ?>
                        <div class="pxl-product-header">
                            <a class="pxl-product-details" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="pxl-product-content">
                        <div class="pxl-product-meta">
                            <h5 class="pxl-product-title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a></h5>
                            <?php if($show_excerpt == 'true' && !empty($num_words)): ?>
                                <div class="pxl-product-excerpt">
                                    <?php echo wp_trim_words( $post->post_excerpt, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if($show_button == 'true'): ?>
                        <div class="pxl-product-buttons">
                            <div class="pxl-product-readmore">
                                <a class="btn btn-secondary" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                    <?php echo !empty($button_text) ? esc_html($button_text) : esc_html__('Learn More', 'medicross'); ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        endforeach;
    endif;
}



add_action( 'wp_ajax_medicross_load_more_post_grid', 'medicross_load_more_post_grid' );
add_action( 'wp_ajax_nopriv_medicross_load_more_post_grid', 'medicross_load_more_post_grid' );
function medicross_load_more_post_grid(){
    try{
        if(!isset($_POST['settings'])){
            throw new Exception(__('Something went wrong while requesting. Please try again!', 'medicross'));
        }

        $settings = isset($_POST['settings']) ? $_POST['settings'] : null;

        $source = isset($settings['source']) ? $settings['source'] : '';
        $term_slug = isset($settings['term_slug']) ? $settings['term_slug'] : '';
        if( !empty($term_slug) && $term_slug !='*'){
            $term_slug = str_replace('.', '', $term_slug);
            $source = [$term_slug.'|'.$settings['tax'][0]]; 
        }
        if( isset($_POST['handler_click']) && sanitize_text_field(wp_unslash( $_POST[ 'handler_click' ] )) == 'filter'){
            set_query_var('paged', 1);
            $settings['paged'] = 1;
        }elseif( isset($_POST['handler_click']) && sanitize_text_field(wp_unslash( $_POST[ 'handler_click' ] )) == 'select_orderby'){
            set_query_var('paged', 1);
            $settings['paged'] = 1;
        }else{
            set_query_var('paged', (int)$settings['paged']);
        }

        extract(pxl_get_posts_of_grid($settings['post_type'], [
            'source'      => $source,
            'orderby'     => isset($settings['orderby'])?$settings['orderby']:'date',
            'order'       => isset($settings['order']) ? ($settings['orderby'] == 'title' ? 'asc' : sanitize_text_field($settings['order']) ) : 'desc',
            'limit'       => isset($settings['limit'])?$settings['limit']:'6',
            'post_ids'    => isset($settings['post_ids'])?$settings['post_ids']: [],
            'post_not_in' => isset($settings['post_not_in'])?$settings['post_not_in']: [],
        ],
        $settings['tax']
    ));

        ob_start();
        if( isset($settings['wg_type']) && $settings['wg_type'] == 'post-list'){
            medicross_get_post_list($posts, $settings);
        }else{
            medicross_get_post_grid($posts, $settings);
        }
        $html = ob_get_clean();

        $pagin_html = '';
        if( isset($settings['pagination_type']) && $settings['pagination_type'] == 'pagination' ){ 
            ob_start();
            medicross()->page->get_pagination( $query,  true );
            $pagin_html = ob_get_clean();
        }

        $result_count = '';
        if( isset($settings['show_toolbar']) && $settings['show_toolbar'] == 'show' ){ 
            ob_start();
            if( (int)$settings['paged'] == 0){
                $limit_start = 1;
                $limit_end = ( (int)$settings['limit'] >= $total ) ? $total : (int)$settings['limit'];
            }else{
                $limit_start = (((int)$settings['paged'] - 1 ) * (int)$settings['limit']) + 1;
                $limit_end = (int)$settings['paged'] * (int)$settings['limit'];
                $limit_end = ( $limit_end >= $total ) ? $total : $limit_end;
            }
            if( isset($settings['pagination_type']) && $settings['pagination_type'] == 'loadmore' ){ 
                printf(
                    '<span class="result-count">%1$s %2$s %3$s %4$s %5$s</span>',
                    esc_html__('Showing','medicross'),
                    '1-'.$limit_end,
                    esc_html__('of','medicross'),
                    $total,
                    esc_html__('results','medicross')
                );
            }else{
                printf(
                    '<span class="result-count">%1$s %2$s %3$s %4$s %5$s</span>',
                    esc_html__('Showing','medicross'),
                    $limit_start.'-'.$limit_end,
                    esc_html__('of','medicross'),
                    $total,
                    esc_html__('results','medicross')
                );
            }

            $result_count = ob_get_clean();
        }

        wp_send_json(
            array(
                'status' => true,
                'message' => esc_attr__('Load Successfully!', 'medicross'),
                'data' => array(
                    'html' => $html,
                    'pagin_html' => $pagin_html,
                    'paged' => $settings['paged'],
                    'posts' => $posts,
                    'max' => $max,
                    'result_count' => $result_count,
                ),
            )
        );
    }
    catch (Exception $e){
        wp_send_json(array('status' => false, 'message' => $e->getMessage()));
    }
    die;
}

function medicross_get_post_list($posts = [], $settings = []){ 
    if (empty($posts) || !is_array($posts) || empty($settings) || !is_array($settings)) {
        return;
    }
    extract($settings);

    switch ($settings['layout']) {
        case 'post-list-1':
        medicross_get_post_list_layout1($posts, $settings);
        break;

        default:
        return false;
        break;
    }
}
function medicross_get_post_list_layout1($posts = [], $settings = []){
    extract($settings); 
    foreach ($posts as $key => $post):

        if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)){
            $img_id = get_post_thumbnail_id($post->ID);
            if($img_id){
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            }else{  
                $thumbnail = get_the_post_thumbnail($post->ID, $img_size);
            }
        }else{
            $thumbnail = '';
        }

        $author = get_user_by('id', $post->post_author);
        $readmore_text = !empty($readmore_text) ? $readmore_text : esc_html__('Continue Reading', 'medicross');
        $date_format = get_option('date_format');

        $data_settings = '';
        $animate_cls = '';
        if ( !empty( $item_animation ) ) {
            $animate_cls = ' pxl-animate pxl-invisible animated-'.$item_animation_duration;
            $data_animation =  json_encode([
                'animation'      => $item_animation,
                'animation_delay' => (float)$item_animation_delay
            ]);
            $data_settings = 'data-settings="'.esc_attr($data_animation).'"';
        }

        
        $flag = false;
        $post_format = get_post_format($post->ID) == false ? 'format-standard' : 'format-'.get_post_format($post->ID);
        ?>
        <div class="<?php echo esc_attr('list-item w-100 '. $post_format); ?> <?php echo esc_attr($animate_cls) ?>" <?php pxl_print_html($data_settings); ?>>
            <div class="grid-item-inner item-inner-wrap row  <?php echo esc_attr($post_format) ?>">
                <?php
                if (has_post_format('quote', $post->ID)){
                    $quote_text = get_post_meta( $post->ID, 'featured-quote-text', true );
                    $quote_cite = get_post_meta( $post->ID, 'featured-quote-cite', true );
                    ?>
                    <div class="col-12">
                        <div class="quote-wrap">
                            <div class="quote-inner-wrap">

                                <div class="link-inner ">
                                    <div class="link-icon">
                                     <span>“</span>
                                 </div>
                                 <div class="content-right">
                                    <div class="item-post-metas ">
                                        <div class="meta-inner  align-items-center">
                                            <?php if($show_date == 'true') : ?>
                                                <span class="post-date">
                                                    <?php echo get_the_date('d M', $post->ID); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if( $show_category == 'true' ) : ?>
                                                <span class="meta-item post-category  d-flex">
                                                    <?php the_terms( $post->ID, 'category', '', ', ', '' ); ?>
                                                </span>   
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a class="quote-text" href="<?php echo esc_url( get_permalink($post->ID)); ?>"><?php echo esc_html($quote_text);?></a>
                                </div>
                            </div>
                            <div class="quote-footer ">
                                <div class="quote-cite "><?php echo esc_html($quote_cite);?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } elseif (has_post_format('link', $post->ID)){
                $link_url = get_post_meta( $post->ID , 'featured-link-url', true );
                $link_text = get_post_meta( $post->ID , 'featured-link-text', true );
                ?>
                <div class="col-12">
                    <div class="link-wrap">
                        <div class="link-inner-wrap">
                            <div class="link-inner ">
                                <div class="link-icon">
                                    <a href="<?php echo esc_url( $link_url); ?>">
                                        <svg version="1.1" id="Glyph" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                        <path d="M192.5,240.5c20.7-21,56-23,79,0h0.2c6.4,6.4,11,14.2,13.8,22.6c6.7-1.1,12.6-4,17.1-8.5l22.1-21.9
                                        c-5-9.6-11.4-18.4-19-26.2c-42-41.1-106.9-40-147.2,0l-80,80c-40.6,40.9-40.6,106.3,0,147.2c40.9,40.6,106.3,40.6,147.2,0l75.4-75.4
                                        c-22,3.6-43.1,1.6-62.7-5.3l-46.7,46.6c-21.1,21.3-57.9,21.3-79.2,0c-21.8-21.8-21.8-57.3,0-79C113.9,318.9,197.8,235.1,192.5,240.5
                                        L192.5,240.5z"/>
                                        <path d="M319.5,271.5c-21,21.3-56.3,22.7-79,0c-0.2,0-0.2,0-0.2,0c-6.4-6.4-11-14.2-13.8-22.6c-6.7,1.1-12.6,4-17.1,8.5l-22.1,21.9
                                        c5,9.6,11.4,18.4,19,26.2c42,41.1,106.9,40,147.2,0l80-80c40.6-40.9,40.6-106.3,0-147.2c-40.9-40.6-106.3-40.6-147.2,0L211,153.8
                                        c22-3.6,43.1-1.6,62.7,5.3l46.7-46.6c21.1-21.3,57.9-21.3,79.2,0c21.8,21.8,21.8,57.3,0,79C398.1,193.1,314.2,276.9,319.5,271.5
                                        L319.5,271.5z"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="content-right">
                                <div class="item-post-metas ">
                                    <div class="meta-inner  align-items-center">
                                        <?php if($show_date == 'true') : ?>
                                            <span class="post-date">
                                                <?php echo get_the_date('d M', $post->ID); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if( $show_category == 'true' ) : ?>
                                            <span class="meta-item post-category  d-flex">
                                                <?php the_terms( $post->ID, 'category', '', ', ', '' ); ?>
                                            </span>   
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <h3 class="link-title"><a href="<?php echo esc_url( $link_url); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title($post->ID); ?></a></h3>
                            </div>
                        </div>
                        <div class="link-footer">
                            <a class="link-text" target="_blank" href="<?php echo esc_url( $link_url); ?>"><?php echo esc_html($link_text);?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php  
        }else{
            if ( !empty( $thumbnail )){
                $flag = true;
                $featured_video = get_post_meta( $post->ID, 'featured-video-url', true );
                $audio_url = get_post_meta( $post->ID, 'featured-audio-url', true ); 
                ?>
                <div class="item-featured col-lg-5">
                    <div class="post-image <?php echo esc_attr('scale-hover') ?>">
                        <?php echo wp_kses_post($thumbnail); ?>       
                        <?php if (has_post_format('audio', $post->ID)) {  
                            $audio = get_post_meta( $post->ID, 'featured-audio-url', true );
                            ?>  
                            <a class="btn-volumn" href="<?php echo esc_url($audio); ?>" target="_blank"><i class="fas fa-volume"></i></a>
                        <?php } ?>

                        <?php if (has_post_format('video', $post->ID)) {  
                            $video = get_post_meta( $post->ID, 'featured-video-url', true );
                            ?>  
                            <a class="video-play-button pxl-action-popup" href="<?php echo esc_url($video); ?>">
                                <i class="caseicon-play1"></i>
                            </a>

                        <?php } ?>
                        <?php
                        if($show_date == 'true') : ?>
                            <div class="post-date">
                                <span class="day"><?php echo get_the_date('d', $post->ID); ?></span>
                                <span class="month"><?php echo get_the_date('M', $post->ID); ?></span>
                            </div>
                        <?php endif; ?>
                    </div> 
                </div>
                <?php
            }else{
                if (has_post_format('video', $post->ID)){
                    $flag = true;
                    global $wp_embed;
                    $featured_video = get_post_meta( $post->ID, 'featured-video-url', true );
                    if (!empty($featured_video)) {
                        echo '<div class="item-featured col-lg-5">';
                        echo '<div class="feature-video">';
                        echo do_shortcode($wp_embed->autoembed($featured_video));
                        echo '</div>';
                        echo '</div>';
                    }
                }elseif(has_post_format('audio', $post->ID)){

                    $flag = true;
                    global $wp_embed;
                    $audio_url = get_post_meta( $post->ID, 'featured-audio-url', true );
                    if (!empty($audio_url)) {
                        echo '<div class="item-featured col-lg-5">';
                        echo '<div class="feature-audio">';
                        echo do_shortcode($wp_embed->autoembed($audio_url));
                        echo '</div>';
                        echo '</div>';
                    }
                }
            }
            ?>
            <?php $col_cls = ($flag = true) ? 'col-lg-7' : 'col'; ?>
            <div class="wrap-item-content <?php echo esc_attr($col_cls) ?>">
                <div class="item-content">
                    <?php
                    if ($show_author == 'true' || $show_category == 'true' || $show_comment == 'true' ){
                        ?>
                        <div class="item-post-metas">
                            <div class="meta-inner d-flex-wrap align-items-center">
                                <?php if( $show_author == 'true' ) : ?>
                                    <span class="meta-item post-author d-flex">
                                        <span class="icon-post"><i class="bi bi-person-fill"></i></span>
                                        <span>
                                            <?php esc_html_e('By','medicross')?> <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>"><?php echo esc_html($author->display_name); ?></a>
                                        </span>
                                    </span>
                                <?php endif; ?>
                                <?php if( $show_category == 'true' ) : ?>
                                    <span class="meta-item post-category  d-flex">
                                        <span class="icon-post"><i class="bi bi-tag-fill"></i></span>
                                        <span><?php the_terms( $post->ID, 'category', '', ', ', '' ); ?></span>
                                    </span>   
                                </span>
                            <?php endif; ?>
                            <?php if($show_comment == 'true') : ?>
                                <span class="post-comments">
                                    <a class="meta-item post-comment-count" href="<?php echo get_comments_link($post->ID); ?>#comments">
                                        <span class="icon-post"><i class="bi bi-chat-dots-fill"></i></span>
                                        <?php
                                        echo comments_number(
                                            '<span class="cmt-count">0</span> '.esc_html__('Comments', 'medicross'),
                                            '<span class="cmt-count">1</span> '.esc_html__('Comment', 'medicross'),
                                            '<span class="cmt-count">%</span> '.esc_html__('Comments', 'medicross'),
                                            $post->ID
                                        ); 
                                    ?></a>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <h3 class="item-title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo pxl_print_html(get_the_title($post->ID)); ?></a></h3>
                <?php if($show_excerpt == 'true'): ?>
                    <div class="item-excerpt">
                        <?php
                        if(!empty($post->post_excerpt)){
                            echo wp_trim_words( $post->post_excerpt, $num_words, null );
                        } else{
                            $content = strip_shortcodes( $post->post_content );
                            $content = apply_filters( 'the_content', $content );
                            $content = str_replace(']]>', ']]&gt;', $content);
                            echo wp_trim_words( $content, $num_words, null );
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <?php 
                if($show_readmore == 'true' || $post_share == 'true') : ?>
                    <div class="blog-post-footer  align-items-center justify-content-between">
                        <?php if( $show_readmore == 'true'): ?>
                            <div class="post-readmore ">
                                <a class="btn btn-glossy" href="<?php echo esc_url( get_permalink($post->ID)); ?>">
                                    <span class="pxl-button-text"><?php echo medicross_html($readmore_text); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" style="transform: scalex(-1); height:auto; fill:#fff;" id="Layer_2" height="16" viewBox="0 0 24 24" width="16" data-name="Layer 2"><path d="m22 11h-17.586l5.293-5.293a1 1 0 1 0 -1.414-1.414l-7 7a1 1 0 0 0 0 1.414l7 7a1 1 0 0 0 1.414-1.414l-5.293-5.293h17.586a1 1 0 0 0 0-2z"></path></svg>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php 
                        if(($settings['post_share'] == 'true') ):
                            ?>
                            <div class="post-shares">
                                <span class="label">
                                    <i class="fas fa-share-alt"></i>
                                    <?php echo esc_html__('Share','medicross') ?>
                                </span>
                                <div class="social-share">
                                    <div class="social ">
                                        <a class="pxl-icon icon-facebook fab fa-facebook" title="<?php echo esc_attr__('Facebook', 'medicross'); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink($post->ID)); ?>"></a>
                                        <a class="pxl-icon icon-twitter fab fa-twitter" title="<?php echo esc_attr__('Twitter', 'medicross'); ?>" target="_blank" href="https://twitter.com/intent/tweet?original_referer=<?php echo urldecode(home_url('/')); ?>&url=<?php echo urlencode(get_permalink($post->ID)); ?>&text=<?php echo get_the_title($post->ID);?>%20"></a>
                                        <a class="pxl-icon icon-linkedin fab fa-linkedin-in" title="<?php echo esc_attr__('Linkedin', 'medicross'); ?>" target="_blank" href="https://www.linkedin.com/cws/share?url=<?php echo urlencode(get_permalink($post->ID));?>"></a>
                                        <a href="javascript:void(0);" class="skype-share pxl-icon fab fa-skype" data-href="<?php echo urlencode(get_permalink($post->ID)); ?>" data-lang="en-US" data-text="<?php echo get_the_title($post->ID); ?>"></a> 
                                    </div>
                                </div>

                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</div>
<?php
endforeach; 
}

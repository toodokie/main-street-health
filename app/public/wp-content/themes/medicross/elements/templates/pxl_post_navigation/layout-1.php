<?php
if($settings['type'] === 'navigation') :
    global $post;
    $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
    $next     = get_adjacent_post( false, '', false );
    if ( ! $next && ! $previous ) {
        return;
    }
    $next_post = get_next_post();
    $previous_post = get_previous_post();
    if( !empty($next_post) || !empty($previous_post) ) { ?>
        <div class="pxl-post-navigation">
            <div class="pxl--item item--prev pxl-navigation-btn--wrap pxl-navigation--prev">
                <?php if ( is_a( $previous_post , 'WP_Post' ) && get_the_title( $previous_post->ID ) != '') { ?>
                    <a class="pxl-icon-link pxl-arrow--prev" href="<?php echo esc_url(get_permalink( $previous_post->ID )); ?>">
                        <span class="pxl-item-icon">
                            <i class="far fa-arrow-left"></i>
                        </span>
                        <?php echo esc_html__('PREVIOUS PROJECT','medicross'); ?>
                    </a>
                <?php } ?>
            </div>
            <div class="pxl--item pxl--item-grid">
                <a href= "<?php echo esc_url($settings['link_grid_page']); ?>">
                    <span class="bl bl1"></span>
                    <span class="bl bl2"></span>
                    <span class="bl bl3"></span>
                    <span class="bl bl4"></span>
                </a>
            </div>
            <?php if ( is_a( $next_post , 'WP_Post' ) && get_the_title( $next_post->ID ) != '') { ?>
                <div class="pxl--item item--next pxl-navigation-btn--wrap pxl-navigation--next ">
                    <a class="pxl-icon-link pxl-arrow--next" href="<?php echo esc_url(get_permalink( $next_post->ID )); ?>">
                        <?php echo esc_html__('NEXT PROJECT','medicross'); ?>
                        <span class="pxl-item-icon">
                            <i class="flaticon flaticon-next"></i>
                        </span>
                    </a>
                </div>
            <?php } ?>

        </div>
    <?php } 
endif;?>

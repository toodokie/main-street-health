<div class="pxl-team-box pxl-team-box1 <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
    <div class="pxl-item--inner">
        <div class="pxl-item--images">
            <?php if(!empty($settings['image']['id'])) : 
                $img  = pxl_get_image_by_size( array(
                    'attach_id'  => $settings['image']['id'],
                    'thumb_size' => 'full',
                ) );
                $thumbnail    = $img['thumbnail'];
                ?>
                <div class="pxl-item--img">
                    <?php echo wp_kses_post($thumbnail); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="box-right">
            <h5 class="pxl-item--title"><?php echo pxl_print_html($settings['title']); ?></h5>
            <div class="pxl-item--position"><?php echo pxl_print_html($settings['pos']); ?></div>
            <?php if(!empty($settings['image_sig']['id'])) : 
                $img2  = pxl_get_image_by_size( array(
                    'attach_id'  => $settings['image_sig']['id'],
                    'thumb_size' => 'full',
                ) );
                $thumbnail2    = $img2['thumbnail'];
                ?>
                <div class="pxl-item--img-signature">
                    <?php echo wp_kses_post($thumbnail2); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="pxl-icon-box pxl-icon-box2 <?php echo esc_attr($settings['pxl_animate']); ?> <?php echo esc_attr($settings['style']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
    <?php if ( ! empty( $settings['item_link']['url'] ) ) {
        $widget->add_render_attribute( 'item_link2', 'href', $settings['item_link']['url'] );

        if ( $settings['item_link']['is_external'] ) {
            $widget->add_render_attribute( 'item_link2', 'target', '_blank' );
        }

        if ( $settings['item_link']['nofollow'] ) {
            $widget->add_render_attribute( 'item_link2', 'rel', 'nofollow' );
        } 
    }?>
    <?php if (!empty($settings['bg_image']['id']) ) : ?>
        <?php $img_icon2  = pxl_get_image_by_size( array(
            'attach_id'  => $settings['bg_image']['id'],
            'thumb_size' => 'full',
        ) );
        $thumbnail    = $img_icon2['url']; ?>
    <?php endif; ?>
    <div class="pxl-item--inner" style="background-image:url(<?php echo esc_url($thumbnail); ?>);">
        <?php if ( $settings['icon_type'] == 'icon' && !empty($settings['pxl_icon']['value']) ) : ?>
            <div class="pxl-item--icon">
                <?php \Elementor\Icons_Manager::render_icon( $settings['pxl_icon'], [ 'aria-hidden' => 'true', 'class' => '' ], 'i' ); ?>
            </div>
        <?php endif; ?>
        <?php if ( $settings['icon_type'] == 'image' && !empty($settings['icon_image']['id']) ) : ?>
            <div class="pxl-item--icon">
                <?php $img_icon  = pxl_get_image_by_size( array(
                    'attach_id'  => $settings['icon_image']['id'],
                    'thumb_size' => 'full',
                ) );
                $thumbnail_icon    = $img_icon['thumbnail'];
                echo pxl_print_html($thumbnail_icon); ?>
            </div>
        <?php endif; ?>
        <<?php echo esc_attr($settings['title_tag']); ?> class="pxl-item--title el-empty"><?php echo pxl_print_html($settings['title']); ?></<?php echo esc_attr($settings['title_tag']); ?>>
        <?php if (!empty($settings['button_text'])) : ?>
           <a class="pxl-item--button" <?php pxl_print_html($widget->get_render_attribute_string( 'item_link2' )); ?>>
            <?php echo pxl_print_html($settings['button_text']); ?>
            <i class="flaticon flaticon-next"></i>
        </a>
    <?php endif ?>
</div>
</div>
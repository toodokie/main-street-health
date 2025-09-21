<div class="pxl-icon-box pxl-icon-box4 <?php echo esc_attr($settings['pxl_animate']); ?> <?php echo esc_attr($settings['style']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
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
        $thumbnail    = $img_icon2['thumbnail']; ?>
    <?php endif; ?>
    <div class="pxl-item--inner" >
        <div class="mask-dental">
            <?php echo pxl_print_html($thumbnail); ?>
        </div>
        <div class="icon--button">
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
            <a class="pxl-item--button" <?php pxl_print_html($widget->get_render_attribute_string( 'item_link2' )); ?>>
                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512"><g><path d="m7 18c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l10-10c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-10 10c-.2.2-.4.3-.7.3z"/></g><g><path d="m17 17c-.6 0-1-.4-1-1v-8h-8c-.6 0-1-.4-1-1s.4-1 1-1h9c.6 0 1 .4 1 1v9c0 .6-.4 1-1 1z"/></g></svg>
            </a>
        </div>
        <<?php echo esc_attr($settings['title_tag']); ?> class="pxl-item--title el-empty"><?php echo pxl_print_html($settings['title']); ?></<?php echo esc_attr($settings['title_tag']); ?>>
        <div class="pxl-item--description el-empty"><?php echo pxl_print_html($settings['desc']); ?></div>
    </div>
</div>
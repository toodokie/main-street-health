<?php

if ( ! empty( $settings['button_link']['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $settings['button_link']['url'] );

    if ( $settings['button_link']['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $settings['button_link']['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}

if ( ! empty( $settings['link_download']['url'] ) ) {
    $widget->add_render_attribute( 'button2', 'href', $settings['link_download']['url'] );

    if ( $settings['link_download']['is_external'] ) {
        $widget->add_render_attribute( 'button2', 'target', '_blank' );
    }

    if ( $settings['link_download']['nofollow'] ) {
        $widget->add_render_attribute( 'button2', 'rel', 'nofollow' );
    }
}

?>
<div class="pxl-pricing pxl-pricing1 <?php echo esc_attr($settings['pxl_animate'].' '.$settings['popular']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
    <div class="content-inner">
        <?php if(!empty($settings['title_box'])) : ?>
            <h5 class="pxl-item--title-box el-empty">
                <?php if ($settings['popular']== 'is-popular'): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M208 32c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 140.9 122-70.4c15.3-8.8 34.9-3.6 43.7 11.7l16 27.7c8.8 15.3 3.6 34.9-11.7 43.7L352 256l122 70.4c15.3 8.8 20.6 28.4 11.7 43.7l-16 27.7c-8.8 15.3-28.4 20.6-43.7 11.7L304 339.1 304 480c0 17.7-14.3 32-32 32l-32 0c-17.7 0-32-14.3-32-32l0-140.9L86 409.6c-15.3 8.8-34.9 3.6-43.7-11.7l-16-27.7c-8.8-15.3-3.6-34.9 11.7-43.7L160 256 38 185.6c-15.3-8.8-20.5-28.4-11.7-43.7l16-27.7C51.1 98.8 70.7 93.6 86 102.4l122 70.4L208 32z"/></svg>
                <?php endif ?>
                <?php echo pxl_print_html($settings['title_box']); ?>
            </h5>
        <?php endif; ?>
        <?php if (!empty($settings['price']) ) : ?>
            <div class="pxl-item--price">
                <?php echo pxl_print_html($settings['price']); ?>
                <?php if (!empty($settings['time']) ) : ?>
                    <span class="time"><?php echo pxl_print_html($settings['time']); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($settings['desc'])) : ?>
            <p class="pxl-item-description el-empty"><?php echo pxl_print_html($settings['desc']); ?></p>
        <?php endif; ?>


        <?php if(isset($settings['feature']) && !empty($settings['feature']) && count($settings['feature'])): ?>
        <div class="pxl-item--feature ">
            <?php foreach ($settings['feature'] as $key => $value): ?>
                <div class="<?php echo esc_attr($value['active']); ?> d-flex">
                    <div class="content">
                        <?php if ($value['active']== 'is-active'): ?>
                            <svg version="1.1" id="fi_447147" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                <path d="M504.502,75.496c-9.997-9.998-26.205-9.998-36.204,0L161.594,382.203L43.702,264.311c-9.997-9.998-26.205-9.997-36.204,0
                                c-9.998,9.997-9.998,26.205,0,36.203l135.994,135.992c9.994,9.997,26.214,9.99,36.204,0L504.502,111.7
                                C514.5,101.703,514.499,85.494,504.502,75.496z"></path>
                            </svg>
                        <?php endif ?>
                        <?php if ($value['active'] != 'is-active'): ?>
                            <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" id="fi_10412365"><path d="m6.81 25.19a2 2 0 0 0 2.83 0l6.36-6.36 6.36 6.36a2 2 0 0 0 2.83-2.83l-6.36-6.36 6.36-6.36a2 2 0 0 0 -2.83-2.83l-6.36 6.36-6.36-6.36a2 2 0 0 0 -2.83 2.83l6.36 6.36-6.36 6.36a2 2 0 0 0 0 2.83z"></path></svg>
                        <?php endif ?>
                        <?php echo pxl_print_html($value['feature_text'])?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


    <?php if(!empty($settings['button_text'])) : ?>
        <div class="pxl-item--button">
            <a class="btn-see" <?php pxl_print_html($widget->get_render_attribute_string( 'button' )); ?>>
                <span><?php echo pxl_print_html($settings['button_text']); ?> </span>
                <i class="flaticon flaticon-next"></i>
            </a>
        </div>
    <?php endif; ?>
</div>
</div>
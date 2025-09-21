<div class="pxl-history">
    <?php
    if(isset($settings['history']) && !empty($settings['history']) && count($settings['history'])): ?>
        <div class="pxl-year">
            <span class="scroll-back" ><i class="flaticon-next" style="transform:scalex(-1);"></i></span>
            <div class="pxl-wrap-date">
                <?php 
                $count = 1;
                ?>
                <?php
                foreach ($settings['history'] as $key => $history):
                   $date = isset($history['date']) ? $history['date'] : '';
                   ?>
                   <span class="pxl-item-date item-<?php echo esc_attr($count++); ?>">
                    <?php echo pxl_print_html($history['date']); ?>
                </span>
            <?php endforeach; ?>
        </div>
        <span class="scroll-next"><i class="flaticon-next"></i></span>
    </div>
    <div class="pxl-content">
        <?php 
        $count2 = 1;
        ?>
        <?php foreach ($settings['history'] as $key => $history):
            $image = isset($history['image']) ? $history['image'] : '';
            $text = isset($history['text']) ? $history['text'] : '';
            $decs = isset($history['decs']) ? $history['decs'] : '';
            ?>
            <div class="entry-body item-<?php echo esc_attr($count2++); ?> ">
                <?php if(!empty($image['id'])) { 
                    $img = pxl_get_image_by_size( array(
                        'attach_id'  => $image['id'],
                        'thumb_size' => 'full',
                        'class' => 'no-lazyload',
                    ));
                    $thumbnail = $img['thumbnail'];?>
                    <div class="pxl-item--image " >
                        <?php echo wp_kses_post($thumbnail); ?>
                    </div>
                <?php } ?>
                <div class="wrap-content ">
                    <h4 class="title"><?php echo pxl_print_html($history['text']); ?></h4>
                    <div class="desc"><?php echo pxl_print_html($history['decs']); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

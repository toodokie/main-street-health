<div class="pxl-section-scale">
    <div class="pxl-section-sticky">
        <div class="pxl-section-slide is-100-vh">
        	<div class="pxl-shape-background is-on-image">
        		<?php if(!empty($settings['shape_1']['id'])) : 
				    $img  = pxl_get_image_by_size( array(
				        'attach_id'  => $settings['shape_1']['id'],
				        'thumb_size' => 'full',
				    ) );
				    $thumbnail    = $img['thumbnail']; ?>
				    <div class="pxl-sticker-shape is-shape-1 is-rotate">
				    	<?php echo wp_kses_post($thumbnail); ?>
				    </div>
				<?php endif; ?>

				<?php if(!empty($settings['shape_2']['id'])) : 
				    $img2  = pxl_get_image_by_size( array(
				        'attach_id'  => $settings['shape_2']['id'],
				        'thumb_size' => 'full',
				    ) );
				    $thumbnail2    = $img2['thumbnail']; ?>
				    <div class="pxl-sticker-shape is-shape-2 is-rotate">
				    	<?php echo wp_kses_post($thumbnail2); ?>
				    </div>
				<?php endif; ?>
            </div>
            <div class="pxl-sticky-mask">
            	<div class="pxl-sticky-parallax">
            		<video loop autoplay>
			            <source src="<?php echo esc_url($settings['bg_video']); ?>" type="video/mp4">
			        </video>
            	</div>
                <div class="pxl-section-overlay"></div>
            </div>
        </div>
    </div>
</div>
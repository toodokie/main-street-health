<div class="pxl-info-box1 ">
	<?php if(!empty($settings['image']['id'])) :
		$img = pxl_get_image_by_size( array(
			'attach_id'  => $settings['image']['id'],
			'thumb_size' => '500x500',
		));
		$thumbnail = $img['thumbnail']; ?>
		<div class="pxl-item--image">
			<?php echo pxl_print_html($thumbnail); ?>
		</div>
	<?php endif; ?>
	<div class="info">
		<?php if (!empty($settings['title'])) { ?>
			<h5 class="pxl-title"><?php echo pxl_print_html($settings['title']); ?></h5>
		<?php } ?>
		<?php if (!empty($settings['desc'])) { ?>
			<p class="pxl-desc"><?php echo pxl_print_html($settings['desc']); ?></p>
		<?php } ?>
	</div>
</div>
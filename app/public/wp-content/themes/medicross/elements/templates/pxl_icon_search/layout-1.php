<?php if($settings['search_type'] == 'popup') : ?>
	<div class="pxl-search-popup-button pxl-cursor--cta <?php echo esc_attr($settings['style']); ?>">
		<?php if(!empty($settings['pxl_icon']['value'])) {
			\Elementor\Icons_Manager::render_icon( $settings['pxl_icon'], [ 'aria-hidden' => 'true', 'class' => '' ], 'i' );
		} else  if ( !empty($settings['image']['id']) ) { 
		$image_size = !empty($settings['img_size']) ? $settings['img_size'] : 'full';
		$img  = pxl_get_image_by_size( array(
			'attach_id'  => $settings['image']['id'],
			'thumb_size' => $image_size,
		) );
		$thumbnail    = $img['thumbnail'];
		$thumbnail_url    = $img['url'];
		?>
		<?php echo wp_kses_post($thumbnail);}
		else{ ?>
			<i class="flaticon flaticon-search"></i>
		<?php } ?>
	</div>

	<?php add_action( 'pxl_anchor_target', 'medicross_hook_anchor_search'); ?>
<?php endif; ?>

<?php if($settings['search_type'] == 'form') : ?>
	<form role="search" method="get" class="pxl-widget-searchform" action="<?php echo esc_url(home_url( '/' )); ?>">
		<div class="searchform-wrap">
			<input type="text" placeholder="<?php if(!empty($settings['email_placefolder'])) { echo esc_attr($settings['email_placefolder']); } else { esc_attr_e('Search...', 'medicross'); } ?>" name="s" class="search-field" />
			<button type="submit" class="search-submit"><i class="flaticon flaticon-search"></i></button>
		</div>
	</form>
<?php endif; ?>

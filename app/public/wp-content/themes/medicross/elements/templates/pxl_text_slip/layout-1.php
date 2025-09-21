<div class="pxl-text-slip pxl-text-slip1">
	<div class="pxl-item--container">
		<div class="pxl-item--inner  <?php echo esc_attr($settings['text_effect'])?>" <?php if(!empty($settings['effect_speed'])) { ?>style="animation-duration:<?php echo esc_attr($settings['effect_speed']); ?>ms"<?php } ?>>
			<?php if(isset($settings['items']) && !empty($settings['items']) && count($settings['items'])): ?>
			<?php foreach ($settings['items'] as $key => $value):
				$text = isset($value['text']) ? $value['text'] : '';
				$icon_key = $widget->get_repeater_setting_key( 'pxl_icon', 'icons', $key );
				$widget->add_render_attribute( $icon_key, [
					'class' => $value['pxl_icon'],
					'aria-hidden' => 'true',
				] );
				$is_new = \Elementor\Icons_Manager::is_migration_allowed();
				?>
				<<?php echo esc_attr($settings['text_tag']); ?> class="pxl-item--text">					
				<span class="pxl-text-backdrop"><?php echo pxl_print_html($text); ?></span>
				<?php if(!empty($value['pxl_icon'])){
					\Elementor\Icons_Manager::render_icon( $value['pxl_icon'], [ 'aria-hidden' => 'true' ], 'i' );
				} ?>
				</<?php echo esc_attr($settings['text_tag']); ?>>
			<?php endforeach; ?>
		<?php endif; ?>	
	</div>
</div>
</div>

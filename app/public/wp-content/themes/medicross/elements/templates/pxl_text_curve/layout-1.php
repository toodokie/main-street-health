<div class="pxl-text-curve <?php echo esc_attr($settings['custom_font']); ?>">
	<svg viewBox="0 0 538 250" width="538" height="250">
		<defs>
			<path id="congGấpDoi" d="M 30,50 Q 269,220 508,50" fill="none" stroke="lightgray" />
		</defs>
		<text font-size="24" fill="#1a1a1a">
			<textPath href="#congGấpDoi" startOffset="50%" text-anchor="middle">
				<?php echo wp_kses_post($settings['text']); ?>	
			</textPath>
		</text>
	</svg>
</div>
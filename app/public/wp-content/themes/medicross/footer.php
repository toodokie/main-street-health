<?php
/**
 * @package Case-Themes
 */
?>
		</div><!-- #main -->
		<?php
		if (!is_404()) {
            medicross()->footer->getFooter(); 
        } ?>
		<?php do_action( 'pxl_anchor_target') ?>
		</div><!-- #wapper -->
	<?php wp_footer(); ?>
	</body>
</html>

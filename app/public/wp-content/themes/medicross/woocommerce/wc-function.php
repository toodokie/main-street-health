<?php

//Custom products layout on archive page
add_filter( 'loop_shop_columns', 'medicross_loop_shop_columns', 20 ); 
function medicross_loop_shop_columns() {
	$columns = isset($_GET['product-column']) ? sanitize_text_field($_GET['product-column']) : medicross()->get_theme_opt('products_columns', 3);
	return $columns;
}


// Change number of products that are displayed per page (shop page)
add_filter( 'loop_shop_per_page', 'medicross_loop_shop_per_page', 20 );
function medicross_loop_shop_per_page( $limit ) {
	$limit = isset($_GET['product-limit']) ? sanitize_text_field($_GET['product-limit']) : medicross()->get_theme_opt('product_per_page', 9);
	return $limit;
}

if(!function_exists('medicross_woocommerce_catalog_result')){
    // remove
	
    // add back
	add_action('woocommerce_before_shop_loop','medicross_woocommerce_catalog_result', 20);
	add_action('medicross_woocommerce_catalog_ordering', 'woocommerce_catalog_ordering');
	add_action('medicross_woocommerce_result_count', 'woocommerce_result_count');
	function medicross_woocommerce_catalog_result(){
		$columns = isset($_GET['col']) ? sanitize_text_field($_GET['col']) : medicross()->get_theme_opt('products_columns', '2');
		$display_type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : medicross()->get_theme_opt('shop_display_type', 'grid');
		$active_grid = 'active';
		$active_list = '';
		if( $display_type == 'list' ){
			$active_list = $display_type == 'list' ? 'active' : '';
			$active_grid = '';
		}
		?>
		<div class="pxl-shop-topbar-wrap ">
			<div class="text-heading number-result">
				<?php do_action('medicross_woocommerce_result_count'); ?>
			</div>
			<div class="pxl-view-layout-wrap ">
				<div class="woocommerce-topbar-ordering">
					<?php woocommerce_catalog_ordering(); ?>
					<ul class="pxl-view-layout d-flex align-items-center">
						<li class="view-icon view-list <?php echo esc_attr($active_grid) ?>">
							<a href="javascript:void(0);" class="pxl-ttip tt-top-left" data-cls="products columns-<?php echo esc_attr($columns);?>" data-col="grid">
								<span></span>
								<span></span>
								<span></span>
								<span></span>
							</a>
						</li>
						<li class="view-icon view-grid <?php echo esc_attr($active_list) ?>">
							<a href="javascript:void(0);" class="pxl-ttip tt-top-left" data-cls="products shop-view-list" data-col="list">
								<span></span>
								<span></span>
								<span></span>
							</a></li>
						</ul>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/* Remove result count & product ordering & item product category..... */
	function medicross_cwoocommerce_remove_function() {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10, 0 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5, 0 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10, 0 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10, 0 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10, 0 );
		remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

		remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_sharing', 50 );
	}
	add_action( 'init', 'medicross_cwoocommerce_remove_function' );

	/* Product Category */
//add_action( 'woocommerce_before_shop_loop', 'medicross_woocommerce_nav_top', 2 );
	function medicross_woocommerce_nav_top() { ?>
		<div class="woocommerce-topbar">
			<div class="woocommerce-result-count pxl-pr-20">
				<?php woocommerce_result_count(); ?>
			</div>
			<div class="woocommerce-topbar-ordering">
				<?php woocommerce_catalog_ordering(); ?>
			</div>
		</div>
	<?php }

	add_filter( 'woocommerce_after_shop_loop_item', 'medicross_woocommerce_product' );
	function medicross_woocommerce_product() {
		global $product;
		$product_id = $product->get_id();
		$shop_featured_img_size = medicross()->get_theme_opt('shop_featured_img_size');
		?>
		<div class="woocommerce-product-inner">
			<?php if (has_post_thumbnail()) {
				$img  = pxl_get_image_by_size( array(
					'attach_id'  => get_post_thumbnail_id($product_id),
					'thumb_size' => $shop_featured_img_size,
				) );
				$thumbnail    = $img['thumbnail'];
				$thumbnail_url    = $img['url']; ?>
				<div class="woocommerce-product-header">
					<a class="woocommerce-product-details" href="<?php the_permalink(); ?>">
						<?php if(!empty($shop_featured_img_size)) { echo wp_kses_post($thumbnail); } else { woocommerce_template_loop_product_thumbnail(); } ?>
					</a>
					<div class="woocommerce-product--buttons">
						<?php if ( ! $product->managing_stock() && ! $product->is_in_stock() ) { ?>
						<?php } else { ?>
							<div class="woocommerce-add-to-cart">
								<div class="woocommerce-product-meta">
									<?php if (class_exists('WPCleverWoosw')) { ?>
										<div class="woocommerce-wishlist">
											<?php echo do_shortcode('[woosw id="'.esc_attr( $product->get_id() ).'"]'); ?>
										</div>
									<?php } ?>
									<?php if ( ! $product->managing_stock() && ! $product->is_in_stock() ) { ?>
									<?php } else { ?>
										<div class="woocommerce-add-to-cart">
											<?php woocommerce_template_loop_add_to_cart(); ?>
										</div>
									<?php } ?>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
				<div class="woocommerce-product-content">
					<?php woocommerce_template_loop_price(); ?>

					<h4 class="woocommerce-product--title">
						<a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
					</h4>
					<div class="woocommerce-product--rating">
						<?php woocommerce_template_loop_rating(); ?>
						<?php if (class_exists('WPCleverWoosw')) { ?>
						<div class="woocommerce-wishlist" style="display: none;">
							<?php echo do_shortcode('[woosw id="'.esc_attr( $product->get_id() ).'"]'); ?>
						</div>
						<?php } ?>
					</div>
					<div class="woocommerce-product--excerpt" style="display: none;">
						<?php woocommerce_template_single_excerpt(); ?>
					</div>
					<div class="woocommerce-add-to--cart list-v" style="display: none;">
						<?php woocommerce_template_loop_add_to_cart(); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php }

	/* Replace text Onsale */
	add_filter('woocommerce_sale_flash', 'medicross_custom_sale_text', 10, 3);
	function medicross_custom_sale_text($text, $post, $_product)
	{
		return '<span class="onsale">' . esc_html__( 'Sale!', 'medicross' ) . '</span>';
	}
	/* Removes the "shop" title on the main shop page */
	function medicross_hide_page_title()
	{
		return false;
	}
	add_filter('woocommerce_show_page_title', 'medicross_hide_page_title');

	// /* Replace text Onsale */
	// add_filter('woocommerce_sale_flash', 'medicross_custom_sale_text', 10, 3);
	// function medicross_custom_sale_text($text, $post, $_product)
	// {
	// 	$regular_price = get_post_meta( get_the_ID(), '_regular_price', true);
	// 	$sale_price = get_post_meta( get_the_ID(), '_sale_price', true);

	// 	$product_sale = '';
	// 	if(!empty($sale_price)) {
	// 		$product_sale = intval( ( (intval($regular_price) - intval($sale_price)) / intval($regular_price) ) * 100);
	// 		return '<span class="onsale">' .$product_sale. '%</span>';
	// 	}
	// }

	add_action( 'woocommerce_before_single_product_summary', 'medicross_woocommerce_single_summer_start', 0 );
	function medicross_woocommerce_single_summer_start() { ?>
		<?php echo '<div class="woocommerce-summary-wrap row">'; ?>
	<?php }

	add_action( 'woocommerce_before_add_to_cart_quantity', 'custom_before_quantity_input_field', 25 );
	function custom_before_quantity_input_field() { ?>
		<?php echo '<div class="quantity-label">' . esc_html__( 'Quantity', 'medicross' ) . '</div>'; ?>
	<?php } 

	add_action( 'woocommerce_single_product_summary', 'custom_after_quantity_input_field', 15 );
	function custom_after_quantity_input_field() {
		global $product;
		?>
		<div class="wooc-product-meta">
			<?php if (class_exists('WPCleverWoosw')) { ?>
				<?php echo do_shortcode('[woosw id="'.esc_attr( $product->get_id() ).'"]'); ?>
			<?php } ?>
		</div>
		<?php
	}

	add_action( 'woocommerce_after_single_product_summary', 'medicross_woocommerce_single_summer_end', 5 );
	function medicross_woocommerce_single_summer_end() { ?>
		<?php echo '</div></div>'; ?>
	<?php }

	/* Checkout Page*/
	add_action( 'woocommerce_checkout_before_order_review_heading', 'medicross_checkout_before_order_review_heading_start', 5 );
	function medicross_checkout_before_order_review_heading_start() { ?>
		<?php echo '<div class="pxl-order-review-right"><div class="pxl-order-review-inner">'; ?>
	<?php }

	add_action( 'woocommerce_checkout_after_order_review', 'medicross_checkout_after_order_review_end', 5 );
	function medicross_checkout_after_order_review_end() { ?>
		<?php echo '</div></div>'; ?>
	<?php }


	add_action( 'woocommerce_single_product_summary', 'medicross_woocommerce_sg_product_title', 9 );
	function medicross_woocommerce_sg_product_title() { 
		global $product; 
		$product_title = medicross()->get_theme_opt( 'product_title', false ); 
		if($product_title ) : ?>
			<div class="woocommerce-sg-product-title">
				<?php woocommerce_template_single_title(); ?>
			</div>
		<?php endif; }

		add_action( 'woocommerce_single_product_summary', 'medicross_woocommerce_sg_product_rating', 11 );
		function medicross_woocommerce_sg_product_rating() { global $product; ?>
			<div class="woocommerce-sg-product-rating">
				<?php woocommerce_template_single_rating(); ?>
			</div>
		<?php }

		add_action( 'woocommerce_single_product_summary', 'medicross_woocommerce_sg_product_price', 0 );
		function medicross_woocommerce_sg_product_price() { ?>
			<div class="woocommerce-sg-product-price">
				<?php woocommerce_template_single_price(); ?>
			</div>
		<?php }

		add_action( 'woocommerce_single_product_summary', 'medicross_woocommerce_sg_product_meta', 30 );
		function medicross_woocommerce_sg_product_meta() { ?>
			<div class="woocommerce-sg-product-meta">
				<h3 class="label-info-product"><?php echo esc_html__('Info Product','medicross') ?></h3>
				<?php woocommerce_template_single_meta(); ?>
			</div>
		<?php }


		add_action( 'woocommerce_single_product_summary', 'medicross_woocommerce_sg_product_excerpt', 20 );
		function medicross_woocommerce_sg_product_excerpt() { ?>
			<div class="woocommerce-sg-product-excerpt">
				<?php woocommerce_template_single_excerpt(); ?>
			</div>
		<?php }

		add_action( 'woocommerce_single_product_summary', 'medicross_woocommerce_sg_social_share', 40 );
		function medicross_woocommerce_sg_social_share() { 
			$product_social_share = medicross()->get_theme_opt( 'product_social_share', false );
			if($product_social_share) : ?>
				<div class="woocommerce-social-share">
					<span><i class="fas fa-share-alt"></i></span>
					<label class="pxl-mr-25"><?php echo esc_html__('Share:', 'medicross'); ?></label>
					<a class="fb-social pxl-mr-10" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="caseicon-facebook"></i></a>
					<a class="tw-social pxl-mr-10" target="_blank" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>%20"><i class="caseicon-twitter"></i></a>
					<a class="pin-social pxl-mr-10" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&description=<?php the_title(); ?>%20"><i class="caseicon-pinterest"></i></a>
					<a class="lin-social pxl-mr-10" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>%20"><i class="caseicon-linkedin"></i></a>
				</div>
			<?php endif; }

			/* Product Single: Gallery */
			add_action( 'woocommerce_before_single_product_summary', 'medicross_woocommerce_single_gallery_start', 0 );
			function medicross_woocommerce_single_gallery_start() { ?>
				<?php echo '<div class="woocommerce-gallery col-xl-6 col-lg-6 col-md-6"><div class="woocommerce-gallery-inner">'; ?>
			<?php }
			add_action( 'woocommerce_before_single_product_summary', 'medicross_woocommerce_single_gallery_end', 30 );
			function medicross_woocommerce_single_gallery_end() { ?>
				<?php echo '</div></div><div class="woocommerce-summary-inner col-xl-6 col-lg-6 col-md-6">'; ?>
			<?php }

			/* Ajax update cart item */
			add_filter('woocommerce_add_to_cart_fragments', 'medicross_woo_mini_cart_item_fragment');
			function medicross_woo_mini_cart_item_fragment( $fragments ) {
				global $woocommerce;
				ob_start();
				?>
				<div class="widget_shopping_cart">
					<div class="widget_shopping_head">
						<div class="pxl-item--close pxl-close pxl-cursor--cta"></div>
						<div class="widget_shopping_title">
							<?php echo esc_html__( 'Cart', 'medicross' ); ?> <span class="widget_cart_counter">(<?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count, 'medicross' ), WC()->cart->cart_contents_count ); ?>)</span>
						</div>
					</div>
					<div class="widget_shopping_cart_content">
						<?php
						$cart_is_empty = sizeof( $woocommerce->cart->get_cart() ) <= 0;
						?>
						<ul class="cart_list product_list_widget">

							<?php if ( ! WC()->cart->is_empty() ) : ?>

							<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
								$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
								$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

								if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

									$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
									$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
									$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
									?>
									<li>
										<?php if(!empty($thumbnail)) : ?>
											<div class="cart-product-image">
												<a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>">
													<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
												</a>
											</div>
										<?php endif; ?>
										<div class="cart-product-meta">
											<h3><a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>"><?php echo esc_html($product_name); ?></a></h3>
											<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
											<?php
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove_from_cart_button pxl-close" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_attr__( 'Remove this item', 'medicross' ),
												esc_attr( $product_id ),
												esc_attr( $cart_item_key ),
												esc_attr( $_product->get_sku() )
											), $cart_item_key );
											?>
										</div>	
									</li>
									<?php
								}
							}
							?>

						<?php else : ?>

							<li class="empty">
								<i class="caseicon-shopping-cart-alt"></i>
								<span><?php esc_html_e( 'Your cart is empty', 'medicross' ); ?></span>
								<a class="btn btn-shop" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php echo esc_html__('Browse Shop', 'medicross'); ?></a>
							</li>

						<?php endif; ?>

					</ul><!-- end product list -->
				</div>
				<?php if ( ! WC()->cart->is_empty() ) : ?>
				<div class="widget_shopping_cart_footer">
					<p class="total"><strong><?php esc_html_e( 'Subtotal', 'medicross' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

					<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

					<p class="buttons">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-shop wc-forward"><?php esc_html_e( 'View Cart', 'medicross' ); ?></a>
						<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn checkout wc-forward"><?php esc_html_e( 'Checkout', 'medicross' ); ?></a>
					</p>
				</div>
			<?php endif; ?>
		</div>
		<?php
		$fragments['div.widget_shopping_cart'] = ob_get_clean();
		return $fragments;
	}

	/* Ajax update cart total number */

	add_filter( 'woocommerce_add_to_cart_fragments', 'medicross_woocommerce_sidebar_cart_count_number' );
	function medicross_woocommerce_sidebar_cart_count_number( $fragments ) {
		ob_start();
		?>
		<span class="widget_cart_counter">(<?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'medicross' ), WC()->cart->cart_contents_count ); ?>)</span>
		<?php

		$fragments['span.widget_cart_counter'] = ob_get_clean();

		return $fragments;
	}

	add_filter( 'woocommerce_output_related_products_args', 'medicross_related_sub', 20 );
	function medicross_related_sub() {
		echo '<div class="heading-related">';

		echo '<h3 class="title-related">';
		echo esc_html__('Related Products','medicross');
		echo '</h3>';

		echo '</div>';
	}

	add_filter( 'woocommerce_output_related_products_args', 'medicross_related_products_args', 20 );
	function medicross_related_products_args( $args ) {
		$args['posts_per_page'] = 3;
		$args['columns'] = 3;
		return $args;
	}

	/* Pagination Args */
	function medicross_filter_woocommerce_pagination_args( $array ) { 
		$array['end_size'] = 1;
		$array['mid_size'] = 1;
		return $array; 
	}; 
	add_filter( 'woocommerce_pagination_args', 'medicross_filter_woocommerce_pagination_args', 10, 1 ); 

	/* Flex Slider Arrow */
	add_filter( 'woocommerce_single_product_carousel_options', 'medicross_update_woo_flexslider_options' );
	function medicross_update_woo_flexslider_options( $options ) {
		$options['directionNav'] = true;
		return $options;
	}

	/* Single Thumbnail Size */
	$single_img_size = medicross()->get_theme_opt('single_img_size');
	if(!empty($single_img_size['width']) && !empty($single_img_size['height'])) {
		add_filter('woocommerce_get_image_size_single', function ($size) {
			$single_img_size = medicross()->get_theme_opt('single_img_size');
			$single_img_size_width = preg_replace('/[^0-9]/', '', $single_img_size['width']);
			$single_img_size_height = preg_replace('/[^0-9]/', '', $single_img_size['height']);
			$size['width'] = $single_img_size_width;
			$size['height'] = $single_img_size_height;
			$size['crop'] = 1;
			return $size;
		});
	}
	add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
		$size['width'] = 600;
		$size['height'] = 600;
		$size['crop'] = 1;
		return $size;
	});

	add_filter('woocommerce_get_image_size_thumbnail', function ($size) {
		$size['width'] = 700;
		$size['height'] = 700;
		$size['crop'] = 1;
		return $size;
	});

	/* Custom Text Add to cart - Single product */
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'medicross_add_to_cart_button_text_single' ); 
	function medicross_add_to_cart_button_text_single() {
		echo '<i class="caseicon-shopping-cart pxl-mr-12"></i>'.esc_html__('Add to Cart', 'medicross');
	}
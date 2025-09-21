<?php
/**
 * Single Product Template
 * @package Medicross
 */
get_header(); ?>
<div class="container">
    <div class="row">
        <div id="pxl-content-area" class="col-12">
            <main id="pxl-content-main">
                <?php while ( have_posts() ) {
                    the_post(); ?>
                    <article id="pxl-post-<?php the_ID(); ?>" <?php post_class('pxl-product-single'); ?>>
                        <div class="product-content-wrapper">
                            <div class="row">
                                <div class="col-lg-6 product-image-area">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="product-featured-image">
                                            <?php the_post_thumbnail('large'); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Display product gallery if you have ACF or custom fields
                                    $gallery = get_post_meta(get_the_ID(), 'product_gallery', true);
                                    if(!empty($gallery)): ?>
                                        <div class="product-gallery">
                                            <?php foreach($gallery as $image_id): ?>
                                                <div class="gallery-item">
                                                    <?php echo wp_get_attachment_image($image_id, 'medium'); ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-lg-6 product-info-area">
                                    <h1 class="product-title"><?php the_title(); ?></h1>
                                    
                                    <?php 
                                    // Display product categories
                                    $categories = wp_get_post_terms(get_the_ID(), 'pxl-product-category');
                                    if(!empty($categories)): ?>
                                        <div class="product-categories">
                                            <?php foreach($categories as $cat): ?>
                                                <span class="product-category"><?php echo esc_html($cat->name); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="product-description">
                                        <?php the_content(); ?>
                                    </div>
                                    
                                    <?php 
                                    // Product specifications or features
                                    $features = get_post_meta(get_the_ID(), 'product_features', true);
                                    if(!empty($features)): ?>
                                        <div class="product-features">
                                            <h3><?php echo esc_html__('Features & Benefits', 'medicross'); ?></h3>
                                            <?php echo wp_kses_post($features); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Contact Form Integration -->
                                    <div class="product-inquiry-form">
                                        <h3><?php echo esc_html__('Request Information', 'medicross'); ?></h3>
                                        <p><?php echo esc_html__('Interested in this product or device? Fill out the form below and our team will contact you.', 'medicross'); ?></p>
                                        
                                        <?php 
                                        // You can use Contact Form 7 shortcode here
                                        $product_form_id = get_post_meta(get_the_ID(), 'product_contact_form', true);
                                        if(!empty($product_form_id)) {
                                            echo do_shortcode('[contact-form-7 id="' . $product_form_id . '" title="Product Inquiry"]');
                                        } else {
                                            // Default form if no specific form is set
                                            if(shortcode_exists('contact-form-7')) {
                                                // Replace with your actual Contact Form 7 ID
                                                echo do_shortcode('[contact-form-7 id="YOUR_FORM_ID" title="Product Inquiry"]');
                                            } else {
                                                ?>
                                                <div class="default-contact-info">
                                                    <p><?php echo esc_html__('Please contact us for more information about this product or device:', 'medicross'); ?></p>
                                                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary"><?php echo esc_html__('Contact Us', 'medicross'); ?></a>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php 
                        // Additional product information sections
                        $specifications = get_post_meta(get_the_ID(), 'product_specifications', true);
                        if(!empty($specifications)): ?>
                            <div class="product-specifications">
                                <h3><?php echo esc_html__('Specifications', 'medicross'); ?></h3>
                                <?php echo wp_kses_post($specifications); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        wp_link_pages( array(
                            'before'      => '<div class="page-links">',
                            'after'       => '</div>',
                            'link_before' => '<span>',
                            'link_after'  => '</span>',
                        ) ); ?>
                    </article><!-- #post -->
                    
                    <?php 
                    // Related Products
                    $related_args = array(
                        'post_type' => 'pxl_product',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'rand'
                    );
                    
                    $related_products = new WP_Query($related_args);
                    
                    if($related_products->have_posts()): ?>
                        <div class="related-products">
                            <h3><?php echo esc_html__('Related Products & Devices', 'medicross'); ?></h3>
                            <div class="row">
                                <?php while($related_products->have_posts()): $related_products->the_post(); ?>
                                    <div class="col-md-4">
                                        <div class="related-product-item">
                                            <?php if(has_post_thumbnail()): ?>
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('medium'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <div class="excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></div>
                                            <a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html__('Learn More', 'medicross'); ?></a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                    
                    <?php if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                } ?>
            </main>
        </div>
    </div>
</div>
<?php get_footer();
<?php
/**
 * Product Category Taxonomy Template
 * @package Medicross
 */

get_header();
$medicross_sidebar = medicross()->get_sidebar_args(['type' => 'blog', 'content_col'=> '8']);
$current_term = get_queried_object(); ?>
<div class="container">
    <div class="row <?php echo esc_attr($medicross_sidebar['wrap_class']) ?>" >
        <div id="pxl-content-area" class="<?php echo esc_attr($medicross_sidebar['content_class']) ?>">
            <main id="pxl-content-main">
                <div class="pxl-products-archive pxl-products-taxonomy">
                    <?php if ( have_posts() ) : ?>
                        <header class="archive-header">
                            <h1 class="archive-title">
                                <?php echo esc_html($current_term->name); ?>
                                <span class="category-subtitle"><?php echo esc_html__('Products & Devices', 'medicross'); ?></span>
                            </h1>
                            <?php if (!empty($current_term->description)): ?>
                                <div class="archive-description">
                                    <p><?php echo esc_html($current_term->description); ?></p>
                                </div>
                            <?php endif; ?>
                        </header>

                        <?php 
                        // Product Categories Filter - Show other categories
                        $categories = get_terms(array(
                            'taxonomy' => 'pxl-product-category',
                            'hide_empty' => true,
                        ));
                        
                        if(!empty($categories) && !is_wp_error($categories) && count($categories) > 1): ?>
                            <div class="product-categories-filter">
                                <div class="filter-label"><?php echo esc_html__('Browse Categories:', 'medicross'); ?></div>
                                <ul class="category-filters">
                                    <li><a href="<?php echo get_post_type_archive_link('pxl_product'); ?>" class="filter-all"><?php echo esc_html__('All Products & Devices', 'medicross'); ?></a></li>
                                    <?php foreach($categories as $category): 
                                        $current_class = ($category->term_id == $current_term->term_id) ? 'current-category' : '';
                                    ?>
                                        <li><a href="<?php echo get_term_link($category); ?>" class="<?php echo esc_attr($current_class); ?>"><?php echo esc_html($category->name); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="products-grid">
                            <div class="row">
                                <?php while ( have_posts() ) : the_post(); ?>
                                    <div class="col-lg-4 col-md-6 product-item-wrap">
                                        <article id="post-<?php the_ID(); ?>" <?php post_class('product-item'); ?>>
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div class="product-image">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('medium'); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="product-content">
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
                                                
                                                <h2 class="product-title">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h2>
                                                
                                                <div class="product-excerpt">
                                                    <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                                </div>
                                                
                                                <div class="product-actions">
                                                    <a href="<?php the_permalink(); ?>" class="btn btn-primary product-learn-more">
                                                        <?php echo esc_html__('Learn More', 'medicross'); ?>
                                                    </a>
                                                    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline-secondary product-contact">
                                                        <?php echo esc_html__('Get Info', 'medicross'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <?php 
                        // Pagination
                        medicross()->page->get_pagination(); ?>
                        
                    <?php else : ?>
                        <div class="no-products-found">
                            <h2><?php echo esc_html__('No Products & Devices in This Category', 'medicross'); ?></h2>
                            <p><?php echo esc_html__('Sorry, but we don\'t have any products or devices in this category at the moment.', 'medicross'); ?></p>
                            <a href="<?php echo get_post_type_archive_link('pxl_product'); ?>" class="btn btn-primary">
                                <?php echo esc_html__('View All Products & Devices', 'medicross'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Call to Action Section -->
                    <div class="products-cta-section">
                        <div class="cta-content">
                            <h3><?php echo esc_html__('Need Custom Solutions?', 'medicross'); ?></h3>
                            <p><?php echo esc_html__('We offer customized medical products and solutions tailored to your specific needs. Contact our team for consultation.', 'medicross'); ?></p>
                            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-lg">
                                <?php echo esc_html__('Contact Our Team', 'medicross'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php if ($medicross_sidebar['sidebar_class']) : ?>
            <div id="pxl-sidebar-area" class="<?php echo esc_attr($medicross_sidebar['sidebar_class']) ?>">
                <div class="pxl-sidebar-sticky">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer();
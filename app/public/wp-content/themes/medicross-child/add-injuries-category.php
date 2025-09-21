<?php
/**
 * Add Injuries Category to Conditions Post Type
 */

// Create Injuries category on theme activation/init
add_action('init', function() {
    // First ensure the taxonomy exists for the portfolio/conditions post type
    register_taxonomy_for_object_type('portfolio-category', 'portfolio');
    
    // Check if Injuries category already exists
    $term_exists = term_exists('injuries', 'portfolio-category');
    
    if (!$term_exists) {
        // Create the Injuries category
        $result = wp_insert_term(
            'Injuries', // Term name
            'portfolio-category', // Taxonomy
            array(
                'description' => 'Medical conditions related to injuries and trauma',
                'slug' => 'injuries',
            )
        );
        
        if (!is_wp_error($result)) {
            // Category created successfully
            set_transient('injuries_category_created', true, 60);
        }
    }
}, 20);

// Show admin notice when category is created
add_action('admin_notices', function() {
    if (get_transient('injuries_category_created')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Success!</strong> "Injuries" category has been created for Conditions. You can now assign conditions to this category.</p>
        </div>
        <?php
        delete_transient('injuries_category_created');
    }
});

// Also add it for regular posts if needed
add_action('init', function() {
    $post_term_exists = term_exists('injuries', 'category');
    
    if (!$post_term_exists) {
        wp_insert_term(
            'Injuries', // Term name
            'category', // Regular post category
            array(
                'description' => 'Blog posts about injuries and trauma',
                'slug' => 'injuries',
            )
        );
    }
}, 20);
<?php
/**
 * Register Reviews Custom Post Type
 * For managing testimonials and reviews centrally
 */

if (!defined('ABSPATH')) exit;

// Register Custom Post Type for Reviews
function msh_register_reviews_post_type() {

    $labels = array(
        'name'                  => _x('Reviews', 'Post Type General Name', 'medicross-child'),
        'singular_name'         => _x('Review', 'Post Type Singular Name', 'medicross-child'),
        'menu_name'             => __('Reviews', 'medicross-child'),
        'name_admin_bar'        => __('Review', 'medicross-child'),
        'archives'              => __('Review Archives', 'medicross-child'),
        'attributes'            => __('Review Attributes', 'medicross-child'),
        'parent_item_colon'     => __('Parent Review:', 'medicross-child'),
        'all_items'             => __('All Reviews', 'medicross-child'),
        'add_new_item'          => __('Add New Review', 'medicross-child'),
        'add_new'               => __('Add New', 'medicross-child'),
        'new_item'              => __('New Review', 'medicross-child'),
        'edit_item'             => __('Edit Review', 'medicross-child'),
        'update_item'           => __('Update Review', 'medicross-child'),
        'view_item'             => __('View Review', 'medicross-child'),
        'view_items'            => __('View Reviews', 'medicross-child'),
        'search_items'          => __('Search Reviews', 'medicross-child'),
        'not_found'             => __('No reviews found', 'medicross-child'),
        'not_found_in_trash'    => __('No reviews found in Trash', 'medicross-child'),
        'featured_image'        => __('Client Photo', 'medicross-child'),
        'set_featured_image'    => __('Set client photo', 'medicross-child'),
        'remove_featured_image' => __('Remove client photo', 'medicross-child'),
        'use_featured_image'    => __('Use as client photo', 'medicross-child'),
        'insert_into_item'      => __('Insert into review', 'medicross-child'),
        'uploaded_to_this_item' => __('Uploaded to this review', 'medicross-child'),
        'items_list'            => __('Reviews list', 'medicross-child'),
        'items_list_navigation' => __('Reviews list navigation', 'medicross-child'),
        'filter_items_list'     => __('Filter reviews list', 'medicross-child'),
    );

    $args = array(
        'label'                 => __('Review', 'medicross-child'),
        'description'           => __('Client testimonials and reviews', 'medicross-child'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => false, // Don't create public pages
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-star-filled',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Enable Gutenberg editor
    );

    register_post_type('msh_review', $args);
}
add_action('init', 'msh_register_reviews_post_type', 0);

// Register Custom Taxonomy for Review Categories (optional)
function msh_register_review_categories() {

    $labels = array(
        'name'                       => _x('Review Categories', 'Taxonomy General Name', 'medicross-child'),
        'singular_name'              => _x('Review Category', 'Taxonomy Singular Name', 'medicross-child'),
        'menu_name'                  => __('Categories', 'medicross-child'),
        'all_items'                  => __('All Categories', 'medicross-child'),
        'parent_item'                => __('Parent Category', 'medicross-child'),
        'parent_item_colon'          => __('Parent Category:', 'medicross-child'),
        'new_item_name'              => __('New Category Name', 'medicross-child'),
        'add_new_item'               => __('Add New Category', 'medicross-child'),
        'edit_item'                  => __('Edit Category', 'medicross-child'),
        'update_item'                => __('Update Category', 'medicross-child'),
        'view_item'                  => __('View Category', 'medicross-child'),
        'separate_items_with_commas' => __('Separate categories with commas', 'medicross-child'),
        'add_or_remove_items'        => __('Add or remove categories', 'medicross-child'),
        'choose_from_most_used'      => __('Choose from the most used', 'medicross-child'),
        'popular_items'              => __('Popular Categories', 'medicross-child'),
        'search_items'               => __('Search Categories', 'medicross-child'),
        'not_found'                  => __('Not Found', 'medicross-child'),
        'no_terms'                   => __('No categories', 'medicross-child'),
        'items_list'                 => __('Categories list', 'medicross-child'),
        'items_list_navigation'      => __('Categories list navigation', 'medicross-child'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
    );

    register_taxonomy('review_category', array('msh_review'), $args);
}
add_action('init', 'msh_register_review_categories', 0);

// Add custom meta boxes for review details
function msh_add_review_meta_boxes() {
    add_meta_box(
        'msh_review_details',
        __('Review Details', 'medicross-child'),
        'msh_review_details_callback',
        'msh_review',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'msh_add_review_meta_boxes');

// Meta box callback function
function msh_review_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('msh_review_details_nonce', 'msh_review_details_nonce');

    // No additional fields needed - we're using:
    // - Title field for Client Name
    // - Editor for Review Text
    // - Featured Image for Client Photo
    ?>

    <style>
        .msh-review-instructions {
            background: #f1f1f1;
            padding: 15px;
            border-left: 4px solid #5CB3CC;
            margin-bottom: 20px;
        }
        .msh-review-instructions h4 {
            margin-top: 0;
        }
    </style>

    <div class="msh-review-instructions">
        <h4><?php _e('How to add a review:', 'medicross-child'); ?></h4>
        <ul>
            <li><strong>Title:</strong> Enter the client's name</li>
            <li><strong>Content:</strong> Enter the review text in the editor below</li>
            <li><strong>Featured Image:</strong> Upload the client's photo (use the Featured Image panel on the right)</li>
        </ul>
    </div>

    <?php
}

// Save meta box data (simplified - only saves nonce for validation)
function msh_save_review_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['msh_review_details_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['msh_review_details_nonce'], 'msh_review_details_nonce')) {
        return;
    }

    // No additional fields to save - using built-in title, content, and featured image
}
add_action('save_post', 'msh_save_review_meta');

// Customize admin columns for reviews
function msh_review_admin_columns($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        if ($key == 'title') {
            $new_columns[$key] = __('Client Name', 'medicross-child');
        } else {
            $new_columns[$key] = $value;
        }
    }

    return $new_columns;
}
add_filter('manage_msh_review_posts_columns', 'msh_review_admin_columns');
?>
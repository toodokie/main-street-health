<?php
/**
 * Register Injuries Custom Post Type
 * Creates a new post type for Injuries, similar to Services and Conditions
 */

// Register Injuries Post Type
add_action('init', function() {
    // Flush rewrite rules on activation
    if (get_option('injuries_flush_rewrite') !== 'done') {
        flush_rewrite_rules();
        update_option('injuries_flush_rewrite', 'done');
    }
    
    $labels = array(
        'name'                  => _x('Injuries', 'Post Type General Name', 'medicross-child'),
        'singular_name'         => _x('Injury', 'Post Type Singular Name', 'medicross-child'),
        'menu_name'             => __('Injuries', 'medicross-child'),
        'name_admin_bar'        => __('Injury', 'medicross-child'),
        'archives'              => __('Injury Archives', 'medicross-child'),
        'attributes'            => __('Injury Attributes', 'medicross-child'),
        'parent_item_colon'     => __('Parent Injury:', 'medicross-child'),
        'all_items'             => __('All Injuries', 'medicross-child'),
        'add_new_item'          => __('Add New Injury', 'medicross-child'),
        'add_new'               => __('Add New', 'medicross-child'),
        'new_item'              => __('New Injury', 'medicross-child'),
        'edit_item'             => __('Edit Injury', 'medicross-child'),
        'update_item'           => __('Update Injury', 'medicross-child'),
        'view_item'             => __('View Injury', 'medicross-child'),
        'view_items'            => __('View Injuries', 'medicross-child'),
        'search_items'          => __('Search Injury', 'medicross-child'),
        'not_found'             => __('Not found', 'medicross-child'),
        'not_found_in_trash'    => __('Not found in Trash', 'medicross-child'),
        'featured_image'        => __('Featured Image', 'medicross-child'),
        'set_featured_image'    => __('Set featured image', 'medicross-child'),
        'remove_featured_image' => __('Remove featured image', 'medicross-child'),
        'use_featured_image'    => __('Use as featured image', 'medicross-child'),
        'insert_into_item'      => __('Insert into injury', 'medicross-child'),
        'uploaded_to_this_item' => __('Uploaded to this injury', 'medicross-child'),
        'items_list'            => __('Injuries list', 'medicross-child'),
        'items_list_navigation' => __('Injuries list navigation', 'medicross-child'),
        'filter_items_list'     => __('Filter injuries list', 'medicross-child'),
    );
    
    $args = array(
        'label'                 => __('Injury', 'medicross-child'),
        'description'           => __('Injuries and trauma-related conditions', 'medicross-child'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields', 'page-attributes'),
        'taxonomies'            => array(),
        'hierarchical'          => true, // Like pages - can have parent/child
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6, // Below Posts
        'menu_icon'             => 'dashicons-healing', // Medical/healing icon
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true, // Enable Gutenberg editor
        'rewrite'               => array('slug' => 'injuries', 'with_front' => false),
    );
    
    register_post_type('injury', $args);
    
    // Register Injury Categories taxonomy
    $tax_labels = array(
        'name'              => _x('Injury Categories', 'taxonomy general name', 'medicross-child'),
        'singular_name'     => _x('Injury Category', 'taxonomy singular name', 'medicross-child'),
        'search_items'      => __('Search Injury Categories', 'medicross-child'),
        'all_items'         => __('All Injury Categories', 'medicross-child'),
        'parent_item'       => __('Parent Injury Category', 'medicross-child'),
        'parent_item_colon' => __('Parent Injury Category:', 'medicross-child'),
        'edit_item'         => __('Edit Injury Category', 'medicross-child'),
        'update_item'       => __('Update Injury Category', 'medicross-child'),
        'add_new_item'      => __('Add New Injury Category', 'medicross-child'),
        'new_item_name'     => __('New Injury Category Name', 'medicross-child'),
        'menu_name'         => __('Injury Categories', 'medicross-child'),
    );
    
    $tax_args = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'injury-category'),
        'show_in_rest'      => true,
    );
    
    register_taxonomy('injury-category', array('injury'), $tax_args);
    
}, 10);

// Add theme support for Injuries
add_filter('medicross_post_types', function($posttypes) {
    $posttypes['injury'] = array(
        'status' => true,
        'item_name'  => 'Injury',
        'items_name' => 'Injuries',
        'args'       => array(
            'rewrite' => array(
                'slug' => 'injuries',
            ),
        ),
    );
    return $posttypes;
}, 20);

// Add to Elementor if needed
add_action('elementor/init', function() {
    // Make Injuries available in Elementor
    add_post_type_support('injury', 'elementor');
});

// Show admin notice
add_action('admin_notices', function() {
    if (get_transient('injuries_post_type_created')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Success!</strong> "Injuries" post type has been created! Remember to go to <a href="<?php echo admin_url('options-permalink.php'); ?>">Settings > Permalinks</a> and click Save to update URLs.</p>
        </div>
        <?php
        delete_transient('injuries_post_type_created');
    }
});

// Set transient on first load
if (!get_option('injuries_post_type_registered')) {
    set_transient('injuries_post_type_created', true, 60);
    update_option('injuries_post_type_registered', true);
}
<?php
/**
 * Simple Injuries Post Type Registration with Theme Integration
 */

// Register the post type with basic settings
function msh_register_injuries_post_type() {
    register_post_type('injury', [
        'labels' => [
            'name' => 'Injuries',
            'singular_name' => 'Injury',
            'menu_name' => 'Injuries',
            'add_new' => 'Add New Injury',
            'add_new_item' => 'Add New Injury',
            'edit_item' => 'Edit Injury',
            'all_items' => 'All Injuries',
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-plus-alt',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes'],
        'rewrite' => ['slug' => 'injuries'],
        'show_in_menu' => true,
        'menu_position' => 5,
        'show_in_rest' => true,
    ]);
    
    // Register Injury Categories taxonomy
    register_taxonomy('injury-category', 'injury', [
        'labels' => [
            'name' => 'Injury Categories',
            'singular_name' => 'Injury Category',
            'menu_name' => 'Injury Categories',
        ],
        'hierarchical' => true,
        'public' => true,
        'rewrite' => ['slug' => 'injury-category'],
    ]);
    
    // Force flush
    flush_rewrite_rules();
}
add_action('init', 'msh_register_injuries_post_type', 5);

// IMPORTANT: Register with Medicross theme system
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

// Register Injury taxonomy with theme
add_filter('medicross_taxonomies', function($taxonomies) {
    $taxonomies['injury-category'] = array(
        'status'     => true,
        'post_type'  => array('injury'),
        'taxonomy'   => 'Injury Categories',
        'taxonomies' => 'Injury Categories',
        'args'       => array(
            'rewrite' => array(
                'slug' => 'injury-category'
            ),
        ),
        'labels'     => array()
    );
    return $taxonomies;
}, 20);

// Add Injuries to theme's post type support for Elementor
add_filter('pxl_add_posttype_support', function($post_types) {
    $post_types[] = 'injury';
    return $post_types;
});

// Add Injuries to theme search results
add_action('pre_get_posts', function($query) {
    if ($query->is_main_query() && $query->is_search() && !is_admin()) {
        $post_types = $query->get('post_type');
        if (empty($post_types)) {
            $post_types = array('post', 'page', 'service', 'portfolio', 'injury');
            $query->set('post_type', $post_types);
        }
    }
});

// Make Injuries available in theme widgets (like Post Grid)
add_filter('pxl_post_type_options', function($options) {
    $options['injury'] = esc_html__('Injuries', 'medicross-child');
    return $options;
});

// Allow editing Injuries with Elementor
add_filter('elementor/cpt_support', function($post_types){
    if (!in_array('injury', $post_types, true)) {
        $post_types[] = 'injury';
    }
    return $post_types;
});
add_filter('elementor_cpt_support', function($post_types){
    if (!in_array('injury', $post_types, true)) {
        $post_types[] = 'injury';
    }
    return $post_types;
});

// Ensure Elementor setting persists in WP options (for the UI toggle)
add_action('admin_init', function(){
    $opt = get_option('elementor_cpt_support');
    if (is_array($opt)) {
        if (!in_array('injury', $opt, true)) {
            $opt[] = 'injury';
            update_option('elementor_cpt_support', $opt);
        }
    } else if (false === $opt) {
        update_option('elementor_cpt_support', ['post','page','injury']);
    }
});

// Add Injuries to theme's archive redirect system if needed
add_action('template_redirect', function() {
    if (is_post_type_archive('injury')) {
        $archive_injury_link = get_theme_mod('archive_injury_link');
        if ($archive_injury_link) {
            wp_redirect(get_permalink($archive_injury_link), 301);
            exit();
        }
    }
});

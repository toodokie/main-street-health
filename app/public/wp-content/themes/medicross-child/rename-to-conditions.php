<?php
/**
 * Rename Portfolio/Case Studies to Conditions
 * This works with both the theme's portfolio and any renamed version
 */

// Method 1: Check what post types exist and rename them
add_action('init', function() {
    // Check if portfolio post type exists and rename it
    global $wp_post_types;
    
    if (isset($wp_post_types['portfolio'])) {
        $labels = &$wp_post_types['portfolio']->labels;
        $labels->name = 'Conditions';
        $labels->singular_name = 'Condition';
        $labels->add_new = 'Add New Condition';
        $labels->add_new_item = 'Add New Condition';
        $labels->edit_item = 'Edit Condition';
        $labels->new_item = 'New Condition';
        $labels->view_item = 'View Condition';
        $labels->view_items = 'View Conditions';
        $labels->search_items = 'Search Conditions';
        $labels->not_found = 'No conditions found';
        $labels->not_found_in_trash = 'No conditions found in Trash';
        $labels->all_items = 'All Conditions';
        $labels->menu_name = 'Conditions';
        $labels->name_admin_bar = 'Condition';
    }
}, 100);

// Method 2: Also rename via theme filters if they exist
add_filter('medicross_post_types', function($posttypes) {
    if (isset($posttypes['portfolio'])) {
        $posttypes['portfolio']['item_name'] = 'Condition';
        $posttypes['portfolio']['items_name'] = 'Conditions';
        $posttypes['portfolio']['args']['labels'] = [
            'name' => 'Conditions',
            'singular_name' => 'Condition',
            'menu_name' => 'Conditions',
            'add_new' => 'Add New Condition',
            'add_new_item' => 'Add New Condition',
            'edit_item' => 'Edit Condition',
            'new_item' => 'New Condition',
            'view_item' => 'View Condition',
            'view_items' => 'View Conditions',
            'search_items' => 'Search Conditions',
            'not_found' => 'No conditions found',
            'not_found_in_trash' => 'No conditions found in Trash',
            'all_items' => 'All Conditions',
            'name_admin_bar' => 'Condition',
        ];
        $posttypes['portfolio']['args']['rewrite']['slug'] = 'conditions';
    }
    return $posttypes;
}, 20);

// Method 3: Change the slug via rewrite rules
add_filter('register_post_type_args', function($args, $post_type) {
    if ($post_type === 'portfolio') {
        $args['rewrite']['slug'] = 'conditions';
        $args['labels']['name'] = 'Conditions';
        $args['labels']['singular_name'] = 'Condition';
        $args['labels']['menu_name'] = 'Conditions';
    }
    return $args;
}, 10, 2);

// Method 4: Rename taxonomies too
add_action('init', function() {
    global $wp_taxonomies;
    
    if (isset($wp_taxonomies['portfolio-category'])) {
        $labels = &$wp_taxonomies['portfolio-category']->labels;
        $labels->name = 'Condition Categories';
        $labels->singular_name = 'Condition Category';
        $labels->menu_name = 'Condition Categories';
    }
}, 100);

// Admin notice disabled to avoid repeated alerts.

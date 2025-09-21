<?php
/**
 * Rename Portfolio to Conditions
 * Add this to functions.php or run it as a standalone file
 */

// Method 1: Via Theme Options Filter (Cleanest)
add_filter('medicross_theme_options', function($options) {
    // Rename Portfolio to Conditions
    $options['portfolio_name'] = 'Conditions';
    $options['portfolio_slug'] = 'conditions';
    $options['portfolio_categorie_name'] = 'Condition Categories';
    $options['portfolio_categorie_slug'] = 'condition-category';
    
    return $options;
});

// Method 2: Direct Filter on Post Type Registration
add_filter('medicross_post_types', function($posttypes) {
    if (isset($posttypes['portfolio'])) {
        // Rename the post type
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
        ];
        $posttypes['portfolio']['args']['rewrite']['slug'] = 'conditions';
    }
    return $posttypes;
}, 20); // Priority 20 to run after theme

// Method 3: Rename the taxonomy too
add_filter('medicross_taxonomies', function($taxonomies) {
    if (isset($taxonomies['portfolio-category'])) {
        $taxonomies['portfolio-category']['taxonomy'] = 'Condition Categories';
        $taxonomies['portfolio-category']['taxonomies'] = 'Condition Categories';
        $taxonomies['portfolio-category']['args']['labels'] = [
            'name' => 'Condition Categories',
            'singular_name' => 'Condition Category',
            'menu_name' => 'Condition Categories',
        ];
        $taxonomies['portfolio-category']['args']['rewrite']['slug'] = 'condition-category';
    }
    return $taxonomies;
}, 20);

// IMPORTANT: After activating this, go to Settings > Permalinks and click Save
echo "Portfolio renamed to Conditions! Now go to Settings > Permalinks and click Save to update URLs.";
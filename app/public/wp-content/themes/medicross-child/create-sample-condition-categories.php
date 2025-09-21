<?php
/**
 * Create Sample Condition Categories
 * Run this once to create sample categories for testing nav menus
 */

add_action('wp_loaded', function() {
    // Only run this if we're in admin and the taxonomy exists
    if (!is_admin() || !taxonomy_exists('portfolio-category')) {
        return;
    }
    
    // Check if categories already exist
    $existing = get_terms(array(
        'taxonomy' => 'portfolio-category',
        'hide_empty' => false,
        'count' => true
    ));
    
    if (!empty($existing)) {
        return; // Categories already exist
    }
    
    // Create sample condition categories
    $categories = [
        'Orthopedic Conditions' => 'Conditions related to bones, joints, and muscles',
        'Neurological Conditions' => 'Conditions affecting the nervous system',
        'Cardiovascular Conditions' => 'Heart and blood vessel related conditions',
        'Respiratory Conditions' => 'Lung and breathing related conditions',
        'Digestive Conditions' => 'Stomach and digestive system conditions'
    ];
    
    foreach ($categories as $name => $description) {
        $term = wp_insert_term(
            $name,
            'portfolio-category',
            array(
                'description' => $description,
                'slug' => sanitize_title($name)
            )
        );
        
        if (!is_wp_error($term)) {
            error_log("Created condition category: $name");
        }
    }
});

// Add admin notice to show this file can be removed after running
add_action('admin_notices', function() {
    if (current_user_can('manage_options')) {
        $existing = get_terms(array(
            'taxonomy' => 'portfolio-category',
            'hide_empty' => false
        ));
        
        if (!empty($existing)) {
            echo '<div class="notice notice-success"><p><strong>Sample Condition Categories Created!</strong> You can now see them in Appearance > Menus. You may remove this file: create-sample-condition-categories.php</p></div>';
        }
    }
});
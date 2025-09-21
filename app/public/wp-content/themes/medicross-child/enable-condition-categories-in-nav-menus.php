<?php
/**
 * Enable Condition Categories (portfolio-category) in Nav Menus
 * Fix: Condition categories exist but don't show in Appearance > Menus
 */

// Force enable portfolio-category taxonomy for nav menus
add_action('init', function() {
    global $wp_taxonomies;
    if (isset($wp_taxonomies['portfolio-category'])) {
        $wp_taxonomies['portfolio-category']->show_in_nav_menus = true;
    }
}, 999); // Very late priority to override theme settings

// Also hook into taxonomy registration
add_action('registered_taxonomy', function($taxonomy, $object_type, $args) {
    if ($taxonomy === 'portfolio-category') {
        global $wp_taxonomies;
        if (isset($wp_taxonomies['portfolio-category'])) {
            $wp_taxonomies['portfolio-category']->show_in_nav_menus = true;
        }
    }
}, 10, 3);

// Additional safety net for admin area
add_action('admin_init', function() {
    global $wp_taxonomies;
    if (isset($wp_taxonomies['portfolio-category'])) {
        $wp_taxonomies['portfolio-category']->show_in_nav_menus = true;
    }
});
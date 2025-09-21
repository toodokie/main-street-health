<?php
/**
 * Add Injuries Post Type to Theme Widgets
 */

// Add Injuries to the Post Grid widget and other widgets
add_action('elementor/widgets/widgets_registered', function() {
    // This runs after widgets are registered, allowing us to modify them
}, 999);

// Filter to add Injuries to post type options in widgets
add_filter('pxl_get_post_type_options', function($options) {
    $options['injury'] = esc_html__('Injuries', 'medicross-child');
    return $options;
}, 20);

// Add Injuries to the supported post types for Post Grid widget
add_filter('pxl_post_grid_supported_types', function($types) {
    $types[] = 'injury';
    return $types;
}, 20);

// Hook into widget registration to add Injuries support
add_action('elementor/element/pxl_post_grid/section_settings/before_section_start', function($element) {
    // Get the post_type control
    $control = $element->get_controls('post_type');
    if ($control && isset($control['options'])) {
        $control['options']['injury'] = esc_html__('Injuries', 'medicross-child');
        $element->update_control('post_type', $control);
    }
}, 10);

// Alternative method: Modify the widget controls directly
add_action('elementor/element/before_section_start', function($element, $section_id, $args) {
    if ('pxl_post_grid' !== $element->get_name()) {
        return;
    }
    
    if ('section_settings' !== $section_id) {
        return;
    }
    
    // Add injury to post type options
    add_filter('pxl_get_post_type_options', function($options) {
        $options['injury'] = esc_html__('Injuries', 'medicross-child');
        return $options;
    });
}, 10, 3);

// Add layout options for Injuries
add_filter('pxl_get_post_layouts', function($layouts, $post_type) {
    if ($post_type === 'injury') {
        $layouts = [
            'injury-1' => esc_html__('Layout 1', 'medicross-child'),
            'injury-2' => esc_html__('Layout 2', 'medicross-child'),
            'injury-3' => esc_html__('Layout 3', 'medicross-child'),
        ];
    }
    return $layouts;
}, 10, 2);

// Make sure Injuries shows up in the Case Addons post type list
add_filter('case_addons_post_types', function($post_types) {
    if (!in_array('injury', $post_types)) {
        $post_types[] = 'injury';
    }
    return $post_types;
}, 20);

// Override the hardcoded post types in the Post Grid widget
add_action('init', function() {
    // Check if the constant or global variable exists that defines supported types
    if (defined('PXL_POST_GRID_TYPES')) {
        $types = PXL_POST_GRID_TYPES;
        if (!in_array('injury', $types)) {
            $types[] = 'injury';
            define('PXL_POST_GRID_TYPES_NEW', $types);
        }
    }
}, 1);
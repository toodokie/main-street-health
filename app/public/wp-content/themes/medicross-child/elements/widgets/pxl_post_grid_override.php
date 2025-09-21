<?php
/**
 * Override Post Grid Widget to include Injuries
 */

// This hooks early to modify the widget before it's registered
add_action('init', function() {
    // Add filter to modify the post types when the widget loads
    add_filter('pxl_post_type_options', function($options) {
        $options['injury'] = esc_html__('Injuries', 'medicross-child');
        return $options;
    });
}, 5);

// Modify the Post Grid widget configuration
add_action('elementor/widgets/widgets_registered', function($widgets_manager) {
    // Get the original widget
    $widget = $widgets_manager->get_widget_types('pxl_post_grid');
    
    if ($widget) {
        // We need to unregister and re-register with modifications
        $widgets_manager->unregister_widget_type('pxl_post_grid');
        
        // Create our modified version
        class PXL_Post_Grid_Modified extends \Elementor\Widget_Base {
            // This is a placeholder - the actual widget would need full implementation
            public function get_name() {
                return 'pxl_post_grid';
            }
            
            public function get_title() {
                return esc_html__('Case Post Grid', 'medicross');
            }
            
            public function get_icon() {
                return 'eicon-posts-grid';
            }
            
            public function get_categories() {
                return ['pxltheme-core'];
            }
        }
        
        // Re-register with modifications
        // Note: This is complex and may need the full widget code
    }
}, 999);

// Simpler approach: Add to the global variable that defines supported types
add_action('plugins_loaded', function() {
    // Modify the supported post types array if it exists
    global $pt_supports;
    if (isset($pt_supports) && is_array($pt_supports)) {
        if (!in_array('injury', $pt_supports)) {
            $pt_supports[] = 'injury';
        }
    }
}, 1);

// Add support by modifying the theme's post type registration
add_filter('case-addons/post-types/supported', function($types) {
    if (!in_array('injury', $types)) {
        $types[] = 'injury';
    }
    return $types;
});
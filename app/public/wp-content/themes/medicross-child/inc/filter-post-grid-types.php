<?php
/**
 * Add Injuries to Post Grid without copying the widget
 */

// Method 1: Hook into the widget before it loads and modify the supported types
add_action('wp_loaded', function() {
    // Modify the global variable that controls post types
    if (isset($GLOBALS['pt_supports'])) {
        if (!in_array('injury', $GLOBALS['pt_supports'])) {
            $GLOBALS['pt_supports'][] = 'injury';
        }
    }
}, 1);

// Method 2: Use output buffering to modify widget content
add_action('elementor/widgets/widgets_registered', function() {
    // Start output buffering to capture and modify widget output
    add_filter('pxl_get_post_type_options', function($options) {
        if (!isset($options['injury'])) {
            $options['injury'] = esc_html__('Injuries', 'medicross-child');
        }
        return $options;
    });
});

// Method 3: Directly modify the widget control after registration
add_action('elementor/element/pxl_post_grid/section_settings/after_section_start', function($element) {
    // Get existing control
    $control = $element->get_controls('post_type');
    if ($control && isset($control['options'])) {
        // Add injury option
        $control['options']['injury'] = esc_html__('Injuries', 'medicross-child');
        // Update the control
        $element->update_control('post_type', $control);
    }
});

// Method 4: Use JavaScript to add the option after page load
add_action('elementor/editor/after_enqueue_scripts', function() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Wait for Elementor to load
        if (typeof elementor !== 'undefined') {
            elementor.hooks.addFilter('panel/elements/regionViews', function(views) {
                // This is complex and may not work reliably
                return views;
            });
        }
    });
    </script>
    <?php
});
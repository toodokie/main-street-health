<?php
/**
 * Directly modify Post Grid widget registration to include Injuries
 */

// DISABLED: Widget unregistering approach - causes fatal errors
// add_action('elementor/widgets/widgets_registered', function($widgets_manager) {
//     // This approach causes class loading issues
// }, 999);

// Alternative approach: Hook into the case-addons plugin loading
add_action('plugins_loaded', function() {
    
    // Override the global variable before widgets load
    if (function_exists('pxl_add_custom_widget')) {
        global $pt_supports;
        $pt_supports = ['post','portfolio','service','industries','pxl_product','injury'];
        
        // Also try to modify any existing definitions
        if (defined('PXL_POST_TYPES')) {
            $new_types = PXL_POST_TYPES;
            if (!in_array('injury', $new_types)) {
                $new_types[] = 'injury';
                // Can't redefine constants, but we can override the global
            }
        }
    }
    
}, 1);

// Filter approach - intercept when post type options are requested
add_filter('pxl_get_post_type_options', function($options) {
    error_log('Post type options called: ' . print_r($options, true));
    $options['injury'] = esc_html__('Injuries', 'medicross-child');
    return $options;
}, 999);

// Last resort: JavaScript injection to add the option after load
add_action('elementor/editor/after_enqueue_scripts', function() {
    ?>
    <script>
    console.log('Trying to add Injuries to post grid options');
    
    // Wait for Elementor to fully load
    jQuery(document).ready(function($) {
        setTimeout(function() {
            // Try to find and modify the post type select
            $('select[data-setting="post_type"]').each(function() {
                var $select = $(this);
                if ($select.find('option[value="injury"]').length === 0) {
                    $select.append('<option value="injury">Injuries</option>');
                    console.log('Added Injuries option to post type select');
                }
            });
        }, 2000);
    });
    </script>
    <?php
});
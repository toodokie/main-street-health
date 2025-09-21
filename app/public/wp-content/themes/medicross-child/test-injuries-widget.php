<?php
/**
 * Test if Injuries Grid widget is loaded
 * Visit yoursite.com/?test_injuries_widget=1 as admin to check
 */

add_action('init', function() {
    if (isset($_GET['test_injuries_widget']) && current_user_can('administrator')) {
        echo '<pre>';
        echo "Testing MSH Injuries Grid Widget...\n\n";
        
        if (class_exists('MSH_Injuries_Grid_Widget')) {
            echo "✅ MSH_Injuries_Grid_Widget class EXISTS!\n";
            
            $widget = new MSH_Injuries_Grid_Widget();
            echo "Widget name: " . $widget->get_name() . "\n";
            echo "Widget title: " . $widget->get_title() . "\n";
            echo "Widget icon: " . $widget->get_icon() . "\n";
            echo "Widget categories: " . implode(', ', $widget->get_categories()) . "\n";
        } else {
            echo "❌ MSH_Injuries_Grid_Widget class NOT FOUND\n";
        }
        
        if (did_action('elementor/loaded')) {
            echo "\n✅ Elementor is loaded\n";
        } else {
            echo "\n❌ Elementor not loaded\n";
        }
        
        if (function_exists('msh_register_elementor_widgets')) {
            echo "✅ msh_register_elementor_widgets function exists\n";
        } else {
            echo "❌ msh_register_elementor_widgets function not found\n";
        }
        
        echo '</pre>';
        exit;
    }
}, 999);
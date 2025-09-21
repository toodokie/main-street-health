<?php
/**
 * Test MSH Injury Cards Widget Loading
 * Visit yoursite.com/?test_injury_cards=1 as admin
 */

add_action('init', function() {
    if (isset($_GET['test_injury_cards']) && current_user_can('administrator')) {
        
        echo '<pre>';
        echo "=== MSH INJURY CARDS WIDGET TEST ===\n\n";
        
        // Check if file exists
        $widget_file = get_stylesheet_directory() . '/inc/elementor/msh-injury-cards.php';
        if (file_exists($widget_file)) {
            echo "✅ Widget file exists: $widget_file\n";
        } else {
            echo "❌ Widget file does not exist\n";
            exit;
        }
        
        // Check if Elementor is loaded
        if (did_action('elementor/loaded')) {
            echo "✅ Elementor is loaded\n";
        } else {
            echo "❌ Elementor is not loaded\n";
        }
        
        // Include the widget file manually for testing
        require_once $widget_file;
        
        // Check if class exists
        if (class_exists('MSH_Injury_Cards_Widget')) {
            echo "✅ MSH_Injury_Cards_Widget class EXISTS!\n";
            
            // Try to instantiate
            try {
                $widget = new MSH_Injury_Cards_Widget();
                echo "✅ Widget can be instantiated\n";
                echo "Widget name: " . $widget->get_name() . "\n";
                echo "Widget title: " . $widget->get_title() . "\n";
                echo "Widget categories: " . implode(', ', $widget->get_categories()) . "\n";
            } catch (Exception $e) {
                echo "❌ Error instantiating widget: " . $e->getMessage() . "\n";
            }
        } else {
            echo "❌ MSH_Injury_Cards_Widget class does not exist\n";
        }
        
        // Check if it's actually registered with Elementor
        if (class_exists('\Elementor\Plugin')) {
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
            $registered_widgets = $widgets_manager->get_widget_types();
            
            echo "\n=== ELEMENTOR REGISTRATION CHECK ===\n";
            
            if (isset($registered_widgets['msh_injury_cards'])) {
                echo "✅ Widget IS registered with Elementor!\n";
                $widget_instance = $registered_widgets['msh_injury_cards'];
                echo "Registered name: " . $widget_instance->get_name() . "\n";
                echo "Registered title: " . $widget_instance->get_title() . "\n";
            } else {
                echo "❌ Widget is NOT registered with Elementor\n";
                echo "Available MSH widgets:\n";
                foreach ($registered_widgets as $name => $widget) {
                    if (strpos($name, 'msh') !== false) {
                        echo "- $name (" . $widget->get_title() . ")\n";
                    }
                }
            }
        }
        
        echo '</pre>';
        exit;
    }
});
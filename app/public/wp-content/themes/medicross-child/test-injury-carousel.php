<?php
/**
 * Test MSH Injury Carousel Widget Loading
 * Visit yoursite.com/?test_injury_carousel=1 as admin
 */

add_action('init', function() {
    if (isset($_GET['test_injury_carousel']) && current_user_can('administrator')) {
        
        echo '<pre>';
        echo "=== MSH INJURY CAROUSEL WIDGET TEST ===\n\n";
        
        // Check if file exists
        $widget_file = get_stylesheet_directory() . '/inc/elementor/msh-injury-carousel.php';
        if (file_exists($widget_file)) {
            echo "✅ Widget file exists: $widget_file\n";
        } else {
            echo "❌ Widget file does not exist\n";
            exit;
        }
        
        // Include the widget file manually for testing
        require_once $widget_file;
        
        // Check if class exists
        if (class_exists('MSH_Injury_Carousel_Widget')) {
            echo "✅ MSH_Injury_Carousel_Widget class EXISTS!\n";
            
            // Try to instantiate
            try {
                $widget = new MSH_Injury_Carousel_Widget();
                echo "✅ Widget can be instantiated\n";
                echo "Widget name: " . $widget->get_name() . "\n";
                echo "Widget title: " . $widget->get_title() . "\n";
                echo "Widget categories: " . implode(', ', $widget->get_categories()) . "\n";
            } catch (Exception $e) {
                echo "❌ Error instantiating widget: " . $e->getMessage() . "\n";
            }
        } else {
            echo "❌ MSH_Injury_Carousel_Widget class does not exist\n";
        }
        
        // Check Elementor registration
        if (class_exists('\Elementor\Plugin')) {
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
            $registered_widgets = $widgets_manager->get_widget_types();
            
            echo "\n=== ELEMENTOR REGISTRATION CHECK ===\n";
            
            if (isset($registered_widgets['msh_injury_carousel'])) {
                echo "✅ Carousel Widget IS registered with Elementor!\n";
            } else {
                echo "❌ Carousel Widget is NOT registered with Elementor\n";
            }
        }
        
        echo '</pre>';
        exit;
    }
});
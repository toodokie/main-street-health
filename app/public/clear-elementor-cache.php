<?php
// Temporary script to clear Elementor cache
require_once 'wp-config.php';
require_once 'wp-load.php';

if (class_exists('Elementor\Plugin')) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "Elementor cache cleared successfully!\n";

    // Also regenerate widgets
    if (method_exists(\Elementor\Plugin::$instance->widgets_manager, 'get_widget_types')) {
        \Elementor\Plugin::$instance->widgets_manager->init_widgets();
        echo "Elementor widgets reinitialized!\n";
    }
} else {
    echo "Elementor plugin not found or not active.\n";
}

// Clean up - delete this file
unlink(__FILE__);
?>
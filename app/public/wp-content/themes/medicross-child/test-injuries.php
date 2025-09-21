<?php
/**
 * Test if Injuries post type is registered
 * Visit yoursite.com/?test_injuries=1 as admin to check
 */

add_action('init', function() {
    if (isset($_GET['test_injuries']) && current_user_can('administrator')) {
        echo '<pre>';
        echo "Checking post types...\n\n";
        
        $post_types = get_post_types([], 'objects');
        
        foreach ($post_types as $post_type) {
            if (in_array($post_type->name, ['injury', 'service', 'portfolio', 'condition'])) {
                echo "Found: " . $post_type->name . " (" . $post_type->label . ")\n";
            }
        }
        
        if (post_type_exists('injury')) {
            echo "\n✅ Injury post type EXISTS!\n";
        } else {
            echo "\n❌ Injury post type NOT FOUND\n";
        }
        
        echo "\n\nAll custom post types:\n";
        foreach ($post_types as $post_type) {
            if (!in_array($post_type->name, ['post', 'page', 'attachment', 'revision', 'nav_menu_item'])) {
                echo "- " . $post_type->name . " (" . $post_type->label . ")\n";
            }
        }
        
        echo '</pre>';
        exit;
    }
}, 999);
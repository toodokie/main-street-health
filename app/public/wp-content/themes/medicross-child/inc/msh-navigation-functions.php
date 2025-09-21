<?php
/**
 * MSH Navigation Helper Functions
 * Easy functions to render navigation in templates
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render MSH Navigation with default menus
 * Automatically looks for menus named "Primary Navigation" and "Secondary Navigation"
 */
function msh_render_navigation() {
    // Try to find menus by name first
    $primary_menu = wp_get_nav_menu_object('Primary Navigation');
    $secondary_menu = wp_get_nav_menu_object('Secondary Navigation');
    
    // Fallback to any menu if named menus don't exist
    if (!$primary_menu || !$secondary_menu) {
        $all_menus = wp_get_nav_menus();
        if (empty($all_menus)) {
            echo '<!-- No WordPress menus found. Please create menus in Appearance → Menus -->';
            return;
        }
        
        // Use first available menu as fallback
        if (!$primary_menu && isset($all_menus[0])) {
            $primary_menu = $all_menus[0];
        }
        if (!$secondary_menu && isset($all_menus[1])) {
            $secondary_menu = $all_menus[1];
        } elseif (!$secondary_menu && isset($all_menus[0])) {
            $secondary_menu = $all_menus[0];
        }
    }
    
    // Render using widget class
    if (class_exists('MSH_Navigation_Widget')) {
        $widget = new MSH_Navigation_Widget();
        $instance = array(
            'primary_menu' => $primary_menu ? $primary_menu->term_id : '',
            'secondary_menu' => $secondary_menu ? $secondary_menu->term_id : ''
        );
        
        $widget->widget(array('before_widget' => '', 'after_widget' => ''), $instance);
    }
}

/**
 * Render MSH Navigation with specific menu IDs
 */
function msh_render_navigation_with_menus($primary_menu_id = '', $secondary_menu_id = '') {
    if (class_exists('MSH_Navigation_Widget')) {
        $widget = new MSH_Navigation_Widget();
        $instance = array(
            'primary_menu' => $primary_menu_id,
            'secondary_menu' => $secondary_menu_id
        );
        
        $widget->widget(array('before_widget' => '', 'after_widget' => ''), $instance);
    }
}

/**
 * Helper function to create default menus with sample content
 * Runs once to set up initial menus
 */
function msh_create_default_menus() {
    // Check if menus already exist
    if (wp_get_nav_menu_object('Primary Navigation') && wp_get_nav_menu_object('Secondary Navigation')) {
        return; // Menus already exist
    }
    
    // Create Primary Navigation menu
    $primary_menu_id = wp_create_nav_menu('Primary Navigation');
    if (!is_wp_error($primary_menu_id)) {
        // Add sample items to primary menu
        wp_update_nav_menu_item($primary_menu_id, 0, array(
            'menu-item-title' => 'About Us',
            'menu-item-url' => home_url('/about_us/'),
            'menu-item-status' => 'publish'
        ));
        
        wp_update_nav_menu_item($primary_menu_id, 0, array(
            'menu-item-title' => 'Medical Professional Resources',
            'menu-item-url' => home_url('/professional/'),
            'menu-item-status' => 'publish'
        ));
        
        wp_update_nav_menu_item($primary_menu_id, 0, array(
            'menu-item-title' => 'Blog',
            'menu-item-url' => home_url('/blog/'),
            'menu-item-status' => 'publish'
        ));
        
        wp_update_nav_menu_item($primary_menu_id, 0, array(
            'menu-item-title' => 'Contact',
            'menu-item-url' => home_url('/contact-us/'),
            'menu-item-status' => 'publish'
        ));
    }
    
    // Create Secondary Navigation menu
    $secondary_menu_id = wp_create_nav_menu('Secondary Navigation');
    if (!is_wp_error($secondary_menu_id)) {
        // Add Services & Therapies with children
        $services_id = wp_update_nav_menu_item($secondary_menu_id, 0, array(
            'menu-item-title' => 'Services & Therapies',
            'menu-item-url' => home_url('/services-therapies/'),
            'menu-item-description' => 'Personalized rehabilitation journeys designed around your unique recovery needs and professional demands.',
            'menu-item-status' => 'publish'
        ));
        
        // Add children to Services
        $services_children = array(
            'Physiotherapy' => '/physiotherapy/',
            'Massage Therapy' => '/massage-therapy/',
            'Chiropractic Care' => '/chiropractic-care/',
            'Acupuncture' => '/acupuncture/',
            'Custom Orthotics' => '/custom-orthotics/',
            'Specialized Treatments' => '/specialized-treatments/'
        );
        
        foreach ($services_children as $title => $url) {
            wp_update_nav_menu_item($secondary_menu_id, 0, array(
                'menu-item-title' => $title,
                'menu-item-url' => home_url($url),
                'menu-item-parent-id' => $services_id,
                'menu-item-status' => 'publish'
            ));
        }
        
        // Add other top-level items (simplified for initial setup)
        $other_items = array(
            'Conditions' => array('url' => '/conditions/', 'desc' => 'Comprehensive treatment for various conditions affecting your daily life and work performance.'),
            'Injury Care' => array('url' => '/injury-care/', 'desc' => 'Specialized care for workplace injuries, motor vehicle accidents, and sports-related injuries with comprehensive support.'),
            'First Responder' => array('url' => '/first-responder/', 'desc' => 'Specialized programs for first responders and occupational health with industry-specific solutions.'),
            'Products' => array('url' => '/products/', 'desc' => 'High-quality therapeutic products and equipment to support your recovery and wellness journey.'),
            'Patient Resources & Coverage' => array('url' => '/patient-resources-coverage/', 'desc' => 'Complete guide to insurance coverage, benefits, and patient resources for all treatment options.')
        );
        
        foreach ($other_items as $title => $data) {
            wp_update_nav_menu_item($secondary_menu_id, 0, array(
                'menu-item-title' => $title,
                'menu-item-url' => home_url($data['url']),
                'menu-item-description' => $data['desc'],
                'menu-item-status' => 'publish'
            ));
        }
    }
}

/**
 * Initialize default menus on theme activation
 */
function msh_navigation_init() {
    // Only run once
    if (!get_option('msh_default_menus_created')) {
        msh_create_default_menus();
        update_option('msh_default_menus_created', true);
    }
}
add_action('after_setup_theme', 'msh_navigation_init');

/**
 * Admin notice to help users understand the new system
 */
function msh_navigation_admin_notice() {
    $screen = get_current_screen();
    if ($screen->id !== 'nav-menus') return;
    
    ?>
    <div class="notice notice-info">
        <p>
            <strong>MSH Navigation System:</strong> 
            Your site now uses WordPress menus for navigation content. 
            Edit "Primary Navigation" for the top bar and "Secondary Navigation" for dropdown menus. 
            Use the Description field for dropdown descriptions.
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'msh_navigation_admin_notice');
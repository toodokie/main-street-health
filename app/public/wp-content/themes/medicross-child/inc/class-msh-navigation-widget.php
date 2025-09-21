<?php
/**
 * MSH Combined Navigation Widget
 * Generates both primary and secondary navigation from WordPress menus
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Navigation_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'msh_navigation_widget',
            __('MSH Navigation', 'medicross-child'),
            array(
                'description' => __('Combined primary and secondary navigation using WordPress menus', 'medicross-child'),
                'classname' => 'msh-navigation-widget'
            )
        );
    }

    /**
     * Widget display on frontend
     */
    public function widget($args, $instance) {
        $primary_menu = !empty($instance['primary_menu']) ? $instance['primary_menu'] : '';
        $secondary_menu = !empty($instance['secondary_menu']) ? $instance['secondary_menu'] : '';
        
        echo $args['before_widget'];
        
        // Render Primary Navigation
        if ($primary_menu && wp_get_nav_menu_object($primary_menu)) {
            $this->render_primary_nav($primary_menu);
        }
        
        // Render Secondary Navigation  
        if ($secondary_menu && wp_get_nav_menu_object($secondary_menu)) {
            $this->render_secondary_nav($secondary_menu);
        }
        
        echo $args['after_widget'];
    }

    /**
     * Render Primary Navigation (Top Bar)
     */
    private function render_primary_nav($menu_id) {
        $menu_items = wp_get_nav_menu_items($menu_id);
        if (!$menu_items) return;
        
        // Get logo
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        ?>
        
        <nav class="top-nav" role="navigation" aria-label="<?php _e('Top Navigation', 'medicross-child'); ?>">
            <div class="top-nav-content">
                <!-- Logo (Left) -->
                <div class="site-branding">
                    <?php if (has_custom_logo() && $logo): ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                            <img src="<?php echo esc_url($logo[0]); ?>" class="custom-logo" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                        </a>
                    <?php else: ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

                <!-- Main Navigation Links (Center) -->
                <div class="main-nav-links">
                    <ul class="nav-menu">
                        <?php foreach ($menu_items as $item): ?>
                            <?php if ($item->menu_item_parent == 0): // Top level items only ?>
                                <li>
                                    <a href="<?php echo esc_url($item->url); ?>">
                                        <?php echo esc_html($item->title); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Book Appointment Button (Right) -->
                <div class="book-appointment-section">
                    <a href="/book-appointment" class="btn-primary">
                        Book Appointment
                    </a>
                </div>

                <!-- Nav Accessibility Toolbar (Mobile) -->
                <div class="nav-accessibility-toolbar">
                    <button class="high-contrast-toggle" title="<?php _e('Toggle High Contrast', 'medicross-child'); ?>" aria-label="<?php _e('Toggle High Contrast Mode', 'medicross-child'); ?>">
                        A
                    </button>
                    <button class="font-size-toggle" title="<?php _e('Toggle Large Text', 'medicross-child'); ?>" aria-label="<?php _e('Toggle Large Text Size', 'medicross-child'); ?>">
                        A+
                    </button>
                </div>
                
                <!-- Mobile Menu Toggle -->
                <button class="nav-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php _e('Toggle Navigation', 'medicross-child'); ?>">
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                    <span class="nav-toggle-bar"></span>
                    <span class="sr-only"><?php _e('Menu', 'medicross-child'); ?></span>
                </button>
            </div>
            
            <!-- Mobile Menu Content -->
            <div class="mobile-menu-content">
                <!-- Close Button -->
                <button class="mobile-menu-close" aria-label="<?php _e('Close Menu', 'medicross-child'); ?>">
                    <span class="close-line"></span>
                    <span class="close-line"></span>
                </button>
                
                <!-- Mobile Secondary Nav Items (will be populated by secondary nav function) -->
                <div class="mobile-secondary-menu" id="mobile-secondary-placeholder">
                    <!-- Populated by render_mobile_secondary_nav() -->
                </div>
                
                <!-- Separator -->
                <hr class="mobile-menu-separator">
                
                <!-- Mobile Top Nav Items -->
                <div class="mobile-top-menu">
                    <?php foreach ($menu_items as $item): ?>
                        <?php if ($item->menu_item_parent == 0): ?>
                            <a href="<?php echo esc_url($item->url); ?>">
                                <?php echo esc_html($item->title); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                
                <!-- Mobile Action Buttons -->
                <div class="mobile-action-buttons">
                    <a href="/book-appointment" class="btn-primary mobile-book-btn">
                        Book Appointment
                    </a>
                    <a href="/new-patient-form" class="btn-sage mobile-patient-form-btn">
                        New Patient Form
                    </a>
                </div>
            </div>
        </nav>
        
        <?php
    }

    /**
     * Render Secondary Navigation (Dropdown Menu Bar)
     */
    private function render_secondary_nav($menu_id) {
        $menu_items = wp_get_nav_menu_items($menu_id);
        if (!$menu_items) return;
        
        // Group menu items by parent
        $menu_tree = array();
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent == 0) {
                $menu_tree[$item->ID] = array(
                    'item' => $item,
                    'children' => array()
                );
            }
        }
        
        // Add children to parents
        foreach ($menu_items as $item) {
            if ($item->menu_item_parent != 0 && isset($menu_tree[$item->menu_item_parent])) {
                $menu_tree[$item->menu_item_parent]['children'][] = $item;
            }
        }
        ?>
        
        <nav class="secondary-nav" role="navigation" aria-label="<?php _e('Secondary Navigation', 'medicross-child'); ?>">
            <div class="secondary-nav-content">
                <ul class="secondary-nav-menu">
                    <?php foreach ($menu_tree as $menu_id => $menu_data): ?>
                        <li>
                            <a href="<?php echo esc_url($menu_data['item']->url); ?>" data-dropdown="<?php echo sanitize_title($menu_data['item']->title); ?>">
                                <?php echo esc_html($menu_data['item']->title); ?>
                                <?php if (!empty($menu_data['children'])): ?>
                                    <span class="chevron">
                                        <span class="chevron-line"></span>
                                        <span class="chevron-line"></span>
                                    </span>
                                <?php endif; ?>
                            </a>
                            
                            <?php if (!empty($menu_data['children'])): ?>
                                <div class="dropdown-menu" id="<?php echo sanitize_title($menu_data['item']->title); ?>-dropdown">
                                    <div class="dropdown-content">
                                        <?php 
                                        // Get description from menu item description field
                                        $description = $menu_data['item']->description;
                                        if ($description): ?>
                                            <div class="dropdown-description">
                                                <?php echo wp_kses_post($description); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="service-links">
                                            <?php foreach ($menu_data['children'] as $child): ?>
                                                <a href="<?php echo esc_url($child->url); ?>">
                                                    <?php echo esc_html($child->title); ?>
                                                    <span class="arrow">→</span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                        
                                        <!-- Footer link to parent page -->
                                        <div class="dropdown-footer">
                                            <a href="<?php echo esc_url($menu_data['item']->url); ?>">
                                                Visit <?php echo esc_html($menu_data['item']->title); ?> page
                                                <span class="arrow">→</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>
        
        <?php
        
        // Render mobile version
        $this->render_mobile_secondary_nav($menu_tree);
    }

    /**
     * Render Mobile Secondary Navigation
     */
    private function render_mobile_secondary_nav($menu_tree) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mobileContainer = document.getElementById('mobile-secondary-placeholder');
            if (mobileContainer) {
                mobileContainer.innerHTML = <?php echo json_encode($this->get_mobile_secondary_html($menu_tree)); ?>;
            }
        });
        </script>
        <?php
    }

    /**
     * Generate Mobile Secondary Navigation HTML
     */
    private function get_mobile_secondary_html($menu_tree) {
        $html = '';
        foreach ($menu_tree as $menu_id => $menu_data) {
            $slug = sanitize_title($menu_data['item']->title);
            $html .= '<div class="mobile-menu-item">';
            $html .= '<a href="#" data-mobile-dropdown="' . $slug . '">';
            $html .= esc_html($menu_data['item']->title);
            if (!empty($menu_data['children'])) {
                $html .= '<span class="mobile-chevron">';
                $html .= '<span class="chevron-line"></span>';
                $html .= '<span class="chevron-line"></span>';
                $html .= '</span>';
            }
            $html .= '</a>';
            
            if (!empty($menu_data['children'])) {
                $html .= '<div class="mobile-dropdown" id="' . $slug . '-mobile-dropdown">';
                foreach ($menu_data['children'] as $child) {
                    $html .= '<a href="' . esc_url($child->url) . '">' . esc_html($child->title) . '</a>';
                }
                $html .= '<div class="dropdown-footer">';
                $html .= '<a href="' . esc_url($menu_data['item']->url) . '">Visit ' . esc_html($menu_data['item']->title) . ' page</a>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        return $html;
    }

    /**
     * Widget backend form
     */
    public function form($instance) {
        $primary_menu = !empty($instance['primary_menu']) ? $instance['primary_menu'] : '';
        $secondary_menu = !empty($instance['secondary_menu']) ? $instance['secondary_menu'] : '';
        
        $menus = wp_get_nav_menus();
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('primary_menu')); ?>">
                <?php _e('Primary Navigation Menu:', 'medicross-child'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('primary_menu')); ?>" name="<?php echo esc_attr($this->get_field_name('primary_menu')); ?>">
                <option value=""><?php _e('Select Menu', 'medicross-child'); ?></option>
                <?php foreach ($menus as $menu): ?>
                    <option value="<?php echo esc_attr($menu->term_id); ?>" <?php selected($primary_menu, $menu->term_id); ?>>
                        <?php echo esc_html($menu->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('secondary_menu')); ?>">
                <?php _e('Secondary Navigation Menu:', 'medicross-child'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('secondary_menu')); ?>" name="<?php echo esc_attr($this->get_field_name('secondary_menu')); ?>">
                <option value=""><?php _e('Select Menu', 'medicross-child'); ?></option>
                <?php foreach ($menus as $menu): ?>
                    <option value="<?php echo esc_attr($menu->term_id); ?>" <?php selected($secondary_menu, $menu->term_id); ?>>
                        <?php echo esc_html($menu->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <p>
            <small>
                <strong><?php _e('Instructions:', 'medicross-child'); ?></strong><br>
                1. Create menus in Appearance → Menus<br>
                2. For dropdown descriptions, use the "Description" field in menu items<br>
                3. Child menu items will appear as dropdown links<br>
                4. Your existing CSS styling will be preserved
            </small>
        </p>
        
        <?php
    }

    /**
     * Widget update/save
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['primary_menu'] = (!empty($new_instance['primary_menu'])) ? sanitize_text_field($new_instance['primary_menu']) : '';
        $instance['secondary_menu'] = (!empty($new_instance['secondary_menu'])) ? sanitize_text_field($new_instance['secondary_menu']) : '';
        return $instance;
    }
}

/**
 * Register the widget
 */
function msh_register_navigation_widget() {
    register_widget('MSH_Navigation_Widget');
}
add_action('widgets_init', 'msh_register_navigation_widget');
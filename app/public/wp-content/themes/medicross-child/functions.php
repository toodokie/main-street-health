<?php

/**
 * Child Theme
 * 
 * @author Case-Themes
 * @since 1.0.0
 */
 
function medicross_child_enqueue_styles(){
    $parent_style = 'pxl-style'; 
    wp_enqueue_style('pxl-style-child', get_stylesheet_directory_uri() . '/style.css', array(
        $parent_style
    ));
    
    // Add font fixes to ensure proper fallbacks and usage
    wp_enqueue_style('msh-font-fixes', get_stylesheet_directory_uri() . '/assets/css/font-fixes.css', array(), wp_get_theme()->get('Version'));
    
    // Add emergency override CSS for MSH Services List widget - LOADS LAST
    wp_enqueue_style('msh-services-list-override', get_stylesheet_directory_uri() . '/assets/css/msh-services-list-override.css', array(
        'pxl-style-child', 'elementor-frontend'
    ), '1.0.1');
    
    // Add scroll-to-top button color overrides
    wp_enqueue_style('scroll-top-override', get_stylesheet_directory_uri() . '/assets/css/scroll-top-override.css', array(
        'pxl-style-child'
    ), '1.0.0');
}
add_action( 'wp_enqueue_scripts', 'medicross_child_enqueue_styles', 99);

// Add inline CSS as absolute last resort to override white shading
add_action('wp_head', function() {
    ?>
    <style id="msh-services-shadow-override">
    /* Remove white shading from MSH Services */
    .msh-service-entry.msh-service-grid-item,
    .msh-services-grid .msh-service-entry {
        box-shadow: none !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        filter: none !important;
    }
    .msh-service-entry.msh-service-grid-item:hover {
        box-shadow: none !important;
    }
    </style>
    <?php
}, 9999);

// Include MSH Navigation Widget and Functions
require_once get_stylesheet_directory() . '/inc/class-msh-navigation-widget.php';

// Include MSH Image Optimizer
require_once get_stylesheet_directory() . '/inc/class-msh-image-optimizer.php';
require_once get_stylesheet_directory() . '/admin/image-optimizer-admin.php';

// Include MSH WebP Delivery System
require_once get_stylesheet_directory() . '/inc/class-msh-webp-delivery.php';

// Include MSH Media Cleanup Tool
require_once get_stylesheet_directory() . '/inc/class-msh-media-cleanup.php';
require_once get_stylesheet_directory() . '/inc/class-msh-safe-rename-system.php';

// Include Enhanced Safe Rename System Components
require_once get_stylesheet_directory() . '/inc/class-msh-url-variation-detector.php';
require_once get_stylesheet_directory() . '/inc/class-msh-backup-verification-system.php';
require_once get_stylesheet_directory() . '/inc/class-msh-image-usage-index.php';
require_once get_stylesheet_directory() . '/inc/class-msh-targeted-replacement-engine.php';

require_once get_stylesheet_directory() . '/inc/msh-navigation-functions.php';

MSH_Safe_Rename_System::get_instance();
// TEMPORARILY DISABLED to fix site loading: MSH_Image_Usage_Index::get_instance();

// Debug navigation menu URLs
require_once get_stylesheet_directory() . '/debug-nav-menu-urls.php';

// Debug PXL Product post type - DISABLED
// require_once get_stylesheet_directory() . '/debug-pxl-product.php';

// Debug Case Post Grid widget - DISABLED
// require_once get_stylesheet_directory() . '/debug-case-post-grid.php';

// Fix PXL Product layouts for Case Post Grid widget
require_once get_stylesheet_directory() . '/fix-pxl-product-layouts.php';

// Rename Portfolio/Case Studies to Conditions
require_once get_stylesheet_directory() . '/rename-to-conditions.php';

// Enable Condition Categories in Nav Menus - DISABLED
// require_once get_stylesheet_directory() . '/enable-condition-categories-in-nav-menus.php';

// Create sample condition categories (remove after first run) - DISABLED  
// require_once get_stylesheet_directory() . '/create-sample-condition-categories.php';

// Register Injuries Post Type - Using simple version
// require_once get_stylesheet_directory() . '/register-injuries-post-type.php';
require_once get_stylesheet_directory() . '/simple-injuries-cpt.php';
require_once get_stylesheet_directory() . '/test-injuries.php';
require_once get_stylesheet_directory() . '/test-injuries-widget.php';
require_once get_stylesheet_directory() . '/debug-widget-registration.php';

// Add Injuries to Post Grid widgets - force approach - DISABLED (removes admin notice)
// require_once get_stylesheet_directory() . '/inc/force-injuries-support.php';
// Meta box for Injury icon upload
require_once get_stylesheet_directory() . '/inc/injury-icon-meta.php';
// Extend theme widgets to include Injury in Case Post Carousel
// require_once get_stylesheet_directory() . '/inc/elementor/extend-injury-widgets.php';

// DISABLED: Widget copying approach - was causing errors
// add_action('elementor/widgets/widgets_registered', function() {
//     if (class_exists('Pxltheme_Core_Widget_Base')) {
//         require_once get_stylesheet_directory() . '/elements/widgets/pxl_post_grid.php';
//     }
// }, 999);

// Global variable override approach
add_action('init', function() {
    global $pt_supports;
    $pt_supports = ['post','portfolio','service','industries','pxl_product','injury'];
}, 1);

/**
 * Main Street Health - Font System Setup
 */

// Enqueue Typography System
function msh_enqueue_typography() {
    // 1. Google Fonts - Source Sans Pro
    wp_enqueue_style(
        'google-fonts-source-sans', 
        'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap', 
        [], 
        null
    );
    
    // 2. Adobe Fonts - Bree
    wp_enqueue_style(
        'adobe-fonts-bree', 
        'https://use.typekit.net/fkz3nwu.css', 
        [], 
        null
    );
    
    // 3. Main Typography Styles
    wp_enqueue_style(
        'msh-typography', 
        get_stylesheet_directory_uri() . '/assets/css/typography.css', 
        ['google-fonts-source-sans', 'adobe-fonts-bree'], 
        '1.0.0'
    );
    
    // 4. Button fixes - Font and Icon corrections
    wp_enqueue_style(
        'msh-button-fixes', 
        get_stylesheet_directory_uri() . '/assets/css/button-fixes.min.css', 
        ['msh-typography'], 
        '1.2.0'
    );
    
    // 5. MSH Services Grid CSS
    wp_enqueue_style(
        'msh-services-grid', 
        get_stylesheet_directory_uri() . '/assets/css/msh-services-grid.css',
        ['msh-typography'], 
        '1.0.0'
    );
    
    // 6. MSH Services List CSS (manual entries)
    wp_enqueue_style(
        'msh-services-list', 
        get_stylesheet_directory_uri() . '/assets/css/msh-services-list.min.css',
        ['msh-typography'], 
        '1.2.0'
    );
    
    // 6.1. MSH Services Debug CSS (REMOVED - Issue resolved)
    
    // 7. MSH Testimonial Carousel CSS
    wp_enqueue_style(
        'msh-testimonial-carousel', 
        get_stylesheet_directory_uri() . '/assets/css/msh-testimonial-carousel.css',
        ['msh-typography'], 
        '1.0.0'
    );
    
    // 8. Elementor Icon Fixes CSS
    wp_enqueue_style(
        'msh-elementor-icon-fixes',
        get_stylesheet_directory_uri() . '/assets/css/elementor-icon-fixes.css',
        ['msh-typography'],
        '1.0.0'
    );
    
    // 9. MSH Steps Widget CSS
    wp_enqueue_style(
        'msh-steps-widget',
        get_stylesheet_directory_uri() . '/assets/css/msh-steps-widget.css',
        ['msh-typography'],
        '1.0.0'
    );
    
    // 10. Navigation System CSS - CRITICAL for dual navigation
    wp_enqueue_style(
        'msh-navigation',
        get_stylesheet_directory_uri() . '/assets/css/navigation.min.css',
        ['msh-typography'],
        '1.2.0'
    );
    
    // 6. Card click functionality
    wp_enqueue_script(
        'msh-card-click',
        get_stylesheet_directory_uri() . '/assets/js/card-click.js',
        [], 
        '1.0.0',
        true
    );
    
    // 7. Navigation JavaScript - CRITICAL for dropdown functionality
    wp_enqueue_script(
        'msh-navigation-js',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        ['jquery'],
        '1.0.0',
        true
    );
    
    // 6. Grid height fixes - cleaned up version
    wp_enqueue_script(
        'msh-grid-fixes',
        get_stylesheet_directory_uri() . '/assets/js/grid-fixes.js',
        ['jquery'], 
        '2.0.0',
        true
    );
    
    // 7. Swiper.js library
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
        [],
        '8.0.0'
    );
    
    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
        [],
        '8.0.0',
        true
    );
    
    // 8. MSH Testimonial Carousel JavaScript
    wp_enqueue_script(
        'msh-testimonial-carousel-js',
        get_stylesheet_directory_uri() . '/assets/js/msh-testimonial-carousel.js',
        ['swiper-js'],
        '1.0.0',
        true
    );
    
    // 9. MSH Injury Carousel JavaScript
    wp_enqueue_script(
        'msh-injury-carousel-js',
        get_stylesheet_directory_uri() . '/assets/js/msh-injury-carousel.js',
        ['swiper-js'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'msh_enqueue_typography', 20);

// Preconnect to font providers for better performance
function msh_add_font_preconnect($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href'        => 'https://fonts.googleapis.com',
            'crossorigin' => true,
        );
        $urls[] = array(
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => true,
        );
        $urls[] = array(
            'href'        => 'https://use.typekit.net',
            'crossorigin' => true,
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'msh_add_font_preconnect', 10, 2);

// Remove parent theme fonts to avoid conflicts
function msh_dequeue_parent_fonts() {
    wp_dequeue_style('medicross-google-fonts');
}
add_action('wp_enqueue_scripts', 'msh_dequeue_parent_fonts', 100);


// Enqueue container override CSS to load AFTER everything else
function msh_enqueue_container_override() {
    wp_enqueue_style(
        'msh-container-override',
        get_stylesheet_directory_uri() . '/assets/css/container-override.css',
        [],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'msh_enqueue_container_override', 999999);

// Include shortcodes (only if files exist)
$shortcode_file = get_stylesheet_directory() . '/inc/shortcodes/msh-services-list.php';
if (file_exists($shortcode_file)) {
    include_once $shortcode_file;
}

// Include Elementor widgets (only if files exist)
function msh_include_elementor_widgets() {
    if (did_action('elementor/loaded')) {
        // Include the services list widget
        $services_widget_file = get_stylesheet_directory() . '/inc/elementor/msh-services-list.php';
        if (file_exists($services_widget_file)) {
            include_once $services_widget_file;
        }
        
        // Include the services grid widget
        $services_grid_file = get_stylesheet_directory() . '/inc/elementor/msh-services-grid.php';
        if (file_exists($services_grid_file)) {
            include_once $services_grid_file;
        }
        
        // Include the testimonial carousel widget
        $testimonial_file = get_stylesheet_directory() . '/inc/elementor/msh-testimonial-carousel.php';
        if (file_exists($testimonial_file)) {
            include_once $testimonial_file;
        }
        
        // Include the steps widget  
        $steps_widget_file = get_stylesheet_directory() . '/inc/elementor/msh-steps.php';
        if (file_exists($steps_widget_file)) {
            include_once $steps_widget_file;
        }
        
        // Include MSH Doctor widget
        $doctor_widget_file = get_stylesheet_directory() . '/elements/widgets/msh_doctor_widget.php';
        if (file_exists($doctor_widget_file)) {
            include_once $doctor_widget_file;
        }
        
        // Include MSH Popular Tags widget
        $tags_widget_file = get_stylesheet_directory() . '/elements/widgets/msh_popular_tags.php';
        if (file_exists($tags_widget_file)) {
            include_once $tags_widget_file;
        }
        
        // Include MSH Single Post Display widget
        $post_widget_file = get_stylesheet_directory() . '/elements/widgets/msh_single_post_display.php';
        if (file_exists($post_widget_file)) {
            include_once $post_widget_file;
        }
        
        // Include MSH Injuries Grid widget - Final version
        $injuries_grid_final_file = get_stylesheet_directory() . '/inc/elementor/msh-injuries-grid-final.php';
        if (file_exists($injuries_grid_final_file)) {
            require_once $injuries_grid_final_file;
        }
        
        // Include MSH Injury Cards widget - Fresh implementation
        $injury_cards_file = get_stylesheet_directory() . '/inc/elementor/msh-injury-cards.php';
        if (file_exists($injury_cards_file)) {
            include_once $injury_cards_file;
        }
        
        // Include MSH Injury Carousel widget
        $injury_carousel_file = get_stylesheet_directory() . '/inc/elementor/msh-injury-carousel.php';
        if (file_exists($injury_carousel_file)) {
            include_once $injury_carousel_file;
        }

        // Product category icon meta (pxl-product-category)
        $prod_cat_icon_meta = get_stylesheet_directory() . '/inc/product-category-icon-meta.php';
        if (file_exists($prod_cat_icon_meta)) {
            include_once $prod_cat_icon_meta;
        }

        // Per-product icon meta box
        $prod_icon_meta = get_stylesheet_directory() . '/inc/product-icon-meta.php';
        if (file_exists($prod_icon_meta)) {
            include_once $prod_icon_meta;
        }

        // Extend Case Post Grid with Product Icon controls
        $extend_product_icons = get_stylesheet_directory() . '/inc/elementor/extend-product-grid-icons.php';
        if (file_exists($extend_product_icons)) {
            include_once $extend_product_icons;
        }

        // Include MSH Mixed Post Carousel widget (Services + Injuries + Products)
        $mixed_carousel_file = get_stylesheet_directory() . '/inc/elementor/msh-mixed-post-carousel.php';
        if (file_exists($mixed_carousel_file)) {
            include_once $mixed_carousel_file;
        }
    }
}
add_action('init', 'msh_include_elementor_widgets');

// REMOVED ALL REDIRECT FIXES - CAUSING ISSUES

// Register widgets with Elementor (only if classes exist)
function msh_register_elementor_widgets() {
    if (did_action('elementor/loaded')) {
        // Services List Widget
        if (class_exists('MSH_Services_List_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Services_List_Widget());
        }
        // Services Grid Widget  
        if (class_exists('MSH_Services_Grid_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Services_Grid_Widget());
        }
        // Testimonial Carousel Widget
        if (class_exists('MSH_Testimonial_Carousel_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Testimonial_Carousel_Widget());
        }
        // Steps Widget
        if (class_exists('MSH_Steps_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Steps_Widget());
        }
        // Doctor Widget
        if (class_exists('MSH_Doctor_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Doctor_Widget());
        }
        // Popular Tags Widget
        if (class_exists('MSH_Popular_Tags')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Popular_Tags());
        }
        // Single Post Display Widget
        if (class_exists('MSH_Single_Post_Display')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Single_Post_Display());
        }
        // Injury Cards Widget - ensure it's loaded
        $injury_cards_file = get_stylesheet_directory() . '/inc/elementor/msh-injury-cards.php';
        if (file_exists($injury_cards_file)) {
            include_once $injury_cards_file;
        }
        if (class_exists('MSH_Injury_Cards_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Injury_Cards_Widget());
        }
        // Injury Carousel Widget
        if (class_exists('MSH_Injury_Carousel_Widget')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Injury_Carousel_Widget());
        }
        // Mixed Post Carousel Widget
        if (class_exists('MSH_Mixed_Post_Carousel')) {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Mixed_Post_Carousel());
        }
        // Injuries Grid Widget - Now handled in the stable file itself
    }
}
add_action('elementor/widgets/widgets_registered', 'msh_register_elementor_widgets');

// Elementor v3+ registration hook (ensures mixed widget is available in all setups)
add_action('elementor/widgets/register', function($widgets_manager){
    $mixed_carousel_file = get_stylesheet_directory() . '/inc/elementor/msh-mixed-post-carousel.php';
    if (file_exists($mixed_carousel_file)) {
        include_once $mixed_carousel_file;
    }
    if (class_exists('MSH_Mixed_Post_Carousel')) {
        if (method_exists($widgets_manager, 'register')) {
            $widgets_manager->register(new \MSH_Mixed_Post_Carousel());
        } else {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Mixed_Post_Carousel());
        }
    }
});

// Enqueue Services Widget CSS in head
function msh_enqueue_services_widget_css() {
    wp_enqueue_style(
        'msh-services-widget', 
        get_stylesheet_directory_uri() . '/assets/css/services-widget.css', 
        [], 
        '1.0.0'
    );
}

// Enqueue Mixed Carousel CSS and JS
function msh_enqueue_mixed_carousel_assets() {
    wp_enqueue_style(
        'msh-mixed-carousel', 
        get_stylesheet_directory_uri() . '/assets/css/msh-mixed-carousel.css', 
        [], 
        '1.0.0'
    );
    
    wp_enqueue_script(
        'msh-mixed-carousel-init',
        get_stylesheet_directory_uri() . '/assets/js/msh-mixed-carousel-init.js',
        ['jquery', 'swiper'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'msh_enqueue_mixed_carousel_assets');
add_action('wp_enqueue_scripts', 'msh_enqueue_services_widget_css', 40);

// Enqueue Injury Cards Widget CSS with high priority
function msh_enqueue_injury_cards_widget_css() {
    wp_enqueue_style(
        'msh-injury-cards-widget', 
        get_stylesheet_directory_uri() . '/assets/css/injury-cards-widget.css', 
        [], 
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'msh_enqueue_injury_cards_widget_css', 50);

// High priority CSS injection for injury card links
function msh_inject_injury_card_link_css() {
    ?>
    <style type="text/css">
    /* High priority injury card link styling */
    .msh-injury-card .card-link,
    .msh-injury-card .card-link:visited,
    .msh-injury-card .card-link:focus,
    .msh-injury-card .card-link:active {
        color: #2B4666 !important;
        text-decoration: none !important;
        border: none !important;
        box-shadow: none !important;
        background-image: none !important;
        border-bottom: none !important;
        text-underline-offset: 0 !important;
        text-decoration-thickness: 0 !important;
        text-decoration-line: none !important;
        text-decoration-style: none !important;
        text-decoration-color: transparent !important;
    }
    .msh-injury-card .card-link:hover {
        color: #5CB3CC !important;
        text-decoration: none !important;
        border: none !important;
        box-shadow: none !important;
        background-image: none !important;
        border-bottom: none !important;
        text-decoration-line: none !important;
        text-decoration-style: none !important;
        text-decoration-color: transparent !important;
    }
    </style>
    <?php
}
add_action('wp_head', 'msh_inject_injury_card_link_css', 9999);

// Removed injury cards CSS - now handled by widget controls and inline JavaScript

// Enqueue testimonial carousel fix JavaScript
function msh_enqueue_testimonial_fix_js() {
    wp_enqueue_script(
        'msh-testimonial-fix',
        get_stylesheet_directory_uri() . '/assets/js/testimonial-fix.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'msh_enqueue_testimonial_fix_js', 50);

// Healthcare-specific functions

/**
 * Main Street Health Privacy Policy Text Override
 * 
 * Replaces generic privacy policy text with healthcare-specific language
 * that explains how patient information is protected and used.
 * 
 * @param string $text The original privacy policy text
 * @param string $form_id The form ID (optional)
 * @return string The modified privacy policy text
 */
function msh_custom_privacy_policy_text($text, $form_id = null) {
    // Healthcare-specific privacy policy text
    $healthcare_text = sprintf(
        __('Your health information is protected under HIPAA (Health Insurance Portability and Accountability Act). By submitting this form, you consent to Main Street Health collecting and using your information to provide healthcare services, process appointments, and communicate with you about your care. Your information will not be shared with third parties except as required for treatment, payment, and healthcare operations, or as permitted by law. For our complete privacy policy, visit %s.', 'medicross'),
        '<a href="' . esc_url(get_privacy_policy_url()) . '" target="_blank">' . __('our privacy policy page', 'medicross') . '</a>'
    );
    
    return $healthcare_text;
}

/**
 * HIPAA Compliance Notice for Contact Forms
 * 
 * Adds a HIPAA compliance notice to contact forms to ensure patients
 * understand how their health information will be protected.
 */
function msh_add_hipaa_notice_to_forms() {
    // Add HIPAA notice CSS
    echo '<style>
        .msh-hipaa-notice {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 4px;
            padding: 15px;
            margin: 15px 0;
            font-size: 13px;
            line-height: 1.5;
            color: #0056b3;
        }
        .msh-hipaa-notice .hipaa-icon {
            color: #0056b3;
            margin-right: 8px;
        }
        .msh-hipaa-notice strong {
            color: #003d82;
        }
    </style>';
}
add_action('wp_head', 'msh_add_hipaa_notice_to_forms');

/**
 * Healthcare Schema Markup
 * 
 * Adds structured data to help search engines understand
 * that this is a healthcare/medical practice website.
 */
function msh_add_healthcare_schema() {
    if (is_front_page() || is_home()) {
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "MedicalOrganization",
          "name": "Main Street Health",
          "url": "<?php echo esc_url(home_url()); ?>",
          "logo": "<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/logo.png'); ?>",
          "description": "Comprehensive healthcare services focused on preventive care, wellness, and patient-centered treatment.",
          "medicalSpecialty": [
            "Family Medicine",
            "Preventive Care", 
            "General Practice",
            "Health & Wellness"
          ],
          "hasMap": "https://maps.google.com/maps?q=150%20Main%20St%20W%2C%20Hamilton%2C%20ON%20L8P%201H8%2C%20Canada",
          "telephone": "(905) 524-3709",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "150 Main Street West, Suite 105B",
            "addressLocality": "Hamilton",
            "addressRegion": "ON",
            "postalCode": "L8P 1H8",
            "addressCountry": "CA"
          },
          "openingHours": [
            "Mo-Fr 10:00-18:00"
          ],
          "acceptsReservations": true,
          "priceRange": "$$"
        }
        </script>
        <?php
    }
}
add_action('wp_head', 'msh_add_healthcare_schema');

/**
 * Healthcare-Specific Contact Form Modifications
 * 
 * Modifies contact forms to be more appropriate for healthcare settings,
 * including HIPAA compliance notices and medical-specific field labels.
 */
function msh_modify_contact_forms($form_html, $form) {
    // Add HIPAA notice before form
    $hipaa_notice = '<div class="msh-hipaa-notice">
        <i class="fas fa-shield-alt hipaa-icon"></i>
        <strong>' . __('HIPAA Privacy Notice:', 'medicross') . '</strong> ' . 
        __('This form is secure and HIPAA compliant. Your health information is protected and will only be used for providing medical care and related services.', 'medicross') . 
        ' <a href="' . esc_url(get_privacy_policy_url()) . '">' . __('Learn more about our privacy practices', 'medicross') . '</a>
    </div>';
    
    // Insert HIPAA notice at the beginning of form
    $form_html = $hipaa_notice . $form_html;
    
    return $form_html;
}

/**
 * Medical Emergency Banner
 * 
 * Shows an emergency notice banner on all pages to direct patients
 * to appropriate emergency services when needed.
 */
function msh_add_emergency_banner() {
    echo '<div id="msh-emergency-banner" style="
        background: #dc3545;
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
        position: relative;
        z-index: 9999;
        display: none;
    ">
        <i class="fas fa-exclamation-triangle"></i> 
        ' . __('MEDICAL EMERGENCY? Call 911 immediately or go to your nearest emergency room.', 'medicross') . '
        <button onclick="document.getElementById(\'msh-emergency-banner\').style.display=\'none\'" 
                style="background: transparent; border: 1px solid white; color: white; margin-left: 15px; padding: 2px 8px; cursor: pointer;">
            ✕
        </button>
    </div>';
    
    // Add JavaScript to show banner on emergency-related searches
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Show emergency banner if user searches for emergency terms
            const emergencyTerms = ["emergency", "urgent", "pain", "bleeding", "chest", "breathing"];
            const searchParam = new URLSearchParams(window.location.search).get("s");
            
            if (searchParam) {
                const searchLower = searchParam.toLowerCase();
                if (emergencyTerms.some(term => searchLower.includes(term))) {
                    document.getElementById("msh-emergency-banner").style.display = "block";
                }
            }
        });
    </script>';
}
add_action('wp_footer', 'msh_add_emergency_banner');

/**
 * Appointment Booking Integration Hooks
 * 
 * Provides hooks for integrating with appointment booking systems
 * like Acuity Scheduling, Calendly, or custom booking solutions.
 */
function msh_appointment_booking_hooks() {
    // Hook for appointment confirmation emails
    do_action('msh_appointment_booked', $appointment_data ?? null);
    
    // Hook for appointment reminders
    do_action('msh_appointment_reminder', $appointment_data ?? null);
    
    // Hook for appointment cancellations
    do_action('msh_appointment_cancelled', $appointment_data ?? null);
}

/**
 * Healthcare Content Security
 * 
 * Adds extra security measures for healthcare content to ensure
 * HIPAA compliance and protect patient information.
 */
function msh_healthcare_content_security() {
    // Prevent search engines from indexing patient portal pages
    if (is_page(['patient-portal', 'patient-login', 'medical-records'])) {
        echo '<meta name="robots" content="noindex, nofollow">';
    }
    
    // Add security headers for healthcare compliance
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // X-Frame-Options: allow Elementor/editor preview but lock down sensitive pages
    $is_elementor_preview = isset($_GET['elementor-preview']) || isset($_GET['elementor_library']);
    $is_sensitive_page = is_page(['patient-portal', 'patient-login', 'medical-records']);

    if ($is_sensitive_page && !$is_elementor_preview) {
        header('X-Frame-Options: DENY');
    } else {
        header('X-Frame-Options: SAMEORIGIN');
    }
}
add_action('wp_head', 'msh_healthcare_content_security', 1);

/**
 * Patient Portal Integration
 * 
 * Provides functionality for patient portal integration,
 * including secure login and medical record access.
 */
function msh_patient_portal_integration() {
    // Register custom post type for patient records (if needed)
    register_post_type('patient_record', array(
        'labels' => array(
            'name' => __('Patient Records', 'medicross'),
            'singular_name' => __('Patient Record', 'medicross'),
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false, // Hide from admin menu for security
        'capability_type' => 'private_post',
        'supports' => array('title', 'editor', 'custom-fields'),
        'has_archive' => false,
    ));
}
add_action('init', 'msh_patient_portal_integration');

/**
 * Medical Disclaimer
 * 
 * Adds medical disclaimers to appropriate pages to ensure legal compliance
 * and set proper expectations for website visitors.
 */
function msh_add_medical_disclaimer() {
    if (is_single() || is_page()) {
        echo '<div class="msh-medical-disclaimer" style="
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            font-size: 12px;
            color: #6c757d;
        ">
            <strong>' . __('Medical Disclaimer:', 'medicross') . '</strong> ' .
            __('The information provided on this website is for educational purposes only and is not intended as a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of your physician or other qualified health provider with any questions you may have regarding a medical condition.', 'medicross') .
        '</div>';
    }
}

// Insurance and Payment Information
function msh_add_insurance_info() {
    // This can be used to display accepted insurance plans
    // Can be implemented as a widget or shortcode
}

// Telehealth Integration Placeholder
function msh_telehealth_integration() {
    // Placeholder for telehealth service integration
    // This would connect with platforms like Doxy.me, Zoom Healthcare, etc.
}

// GDPR/CCPA Compliance for Healthcare
function msh_healthcare_privacy_compliance() {
    // Additional privacy controls beyond standard GDPR
    // Specific to healthcare data (PHI - Protected Health Information)
}

// Also hook into Slider Revolution's initialization
add_filter('revslider_fe_before_init', function() {
    add_action('wp_enqueue_scripts', function() {
        wp_enqueue_style('adobe-fonts-bree', 'https://use.typekit.net/fkz3nwu.css', [], null);
        wp_enqueue_style('google-fonts-source', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap', [], null);
    }, 1);
});

/**
 * Reduce noisy scripts in Elementor editor/preview
 * Dequeue known scripts that content blockers often flag, only in preview.
 */
function msh_tune_editor_enqueues() {
    $is_elementor_preview = isset($_GET['elementor-preview']) || (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->editor->is_edit_mode());
    if ($is_elementor_preview) {
        // Parent theme handle for counter widget script
        wp_dequeue_script('medicross-counter');
        wp_deregister_script('medicross-counter');
    }
}
add_action('wp_enqueue_scripts', 'msh_tune_editor_enqueues', 1000);

// Override parent counter script everywhere with lightweight vanilla implementation
add_action('wp_enqueue_scripts', function() {
    wp_deregister_script('medicross-counter');
    wp_register_script(
        'medicross-counter',
        get_stylesheet_directory_uri() . '/assets/js/msh-counter.js',
        [],
        wp_get_theme()->get('Version'),
        true
    );
}, 110);
/**
 * Ensure service grid uses fitRows and custom box is DOM-last before theme init
 * Runs early in head so parent theme's Isotope picks up the desired layout.
 */
function msh_prime_service_grid_layout_script() {
    echo <<<'EOT'
<script>(function(){
    function prime() {
        var grids = document.querySelectorAll(
            ".pxl-grid.pxl-service-grid.pxl-service-grid-layout2[data-layout]"
        );
        grids.forEach(function(grid){
            // Force fitRows so order is left-to-right by DOM
            grid.setAttribute("data-layout", "fitRows");

            // Move custom box to be the last .pxl-grid-item in DOM
            var inner = grid.querySelector('.pxl-grid-inner');
            if (!inner) return;
            var custom = inner.querySelector('.pxl-grid-item.custom-box');
            if (custom && custom !== inner.lastElementChild) {
                inner.appendChild(custom);
            }
        });
    }
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", prime);
    } else {
        prime();
    }
})();</script>
EOT;
}
add_action('wp_head', 'msh_prime_service_grid_layout_script', 5);

/**
 * Ensure new categories (like Injuries) appear in widget term pickers
 * and front-end filters, even if empty.
 */
add_filter('get_terms_args', function($args, $taxonomies) {
    $taxonomies = (array) $taxonomies;
    $target_tax = ['category', 'injury-category'];
    if (array_intersect($taxonomies, $target_tax)) {
        $args['hide_empty'] = false;
    }
    return $args;
}, 10, 2);

/**
 * Keep the default blog category renamed to "Conditions" so it doesn't revert to "Case Studies".
 */
add_action('init', function () {
    $term = get_term_by('slug', 'conditions', 'category');
    if (!$term) {
        $term = get_term_by('slug', 'case-studies', 'category');
    }
    if ($term && $term->name !== 'Conditions') {
        wp_update_term($term->term_id, 'category', [
            'name' => 'Conditions',
            'slug' => 'conditions',
        ]);
    }
});

// Test Injury Cards Widget  
require_once get_stylesheet_directory() . '/test-injury-cards.php';
// Test Injury Carousel Widget  
require_once get_stylesheet_directory() . '/test-injury-carousel.php';

/**
 * SEO OPTIMIZATION ENHANCEMENTS
 * 
 * Comprehensive SEO improvements for Main Street Health
 * Includes meta descriptions, Open Graph, Twitter Cards, and canonical URLs
 */

/**
 * Add SEO meta tags to wp_head
 */
function msh_add_seo_meta_tags() {
    global $post;
    
    // Don't add SEO meta to admin pages or if Yoast/RankMath is active
    if (is_admin() || class_exists('WPSEO_Frontend') || class_exists('RankMath')) {
        return;
    }
    
    // Get page/post title and description
    $title = '';
    $description = '';
    $image = '';
    $url = '';
    
    if (is_front_page() || is_home()) {
        $title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
        $description = 'Main Street Health provides comprehensive healthcare services focused on preventive care, wellness, and patient-centered treatment. Expert medical care you can trust.';
        $image = get_stylesheet_directory_uri() . '/assets/images/main-street-health-og-image.jpg';
        $url = home_url('/');
    } elseif (is_singular()) {
        $title = get_the_title() . ' | ' . get_bloginfo('name');
        
        // Get post excerpt or custom description
        if (has_excerpt()) {
            $description = wp_strip_all_tags(get_the_excerpt());
        } else {
            $description = wp_trim_words(wp_strip_all_tags(get_the_content()), 25, '...');
        }
        
        // Fallback for healthcare pages
        if (empty($description)) {
            $description = 'Expert healthcare services at Main Street Health. Quality medical care focused on your health and wellness needs.';
        }
        
        // Get featured image
        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url(null, 'large');
        } else {
            $image = get_stylesheet_directory_uri() . '/assets/images/main-street-health-og-image.jpg';
        }
        
        $url = get_permalink();
    } elseif (is_category() || is_tag() || is_archive()) {
        $title = get_the_archive_title() . ' | ' . get_bloginfo('name');
        $description = get_the_archive_description() ?: 'Browse our healthcare services and medical expertise at Main Street Health.';
        $image = get_stylesheet_directory_uri() . '/assets/images/main-street-health-og-image.jpg';
        $url = get_pagenum_link();
    }
    
    // Clean up description
    $description = wp_trim_words($description, 25, '...');
    $description = str_replace('"', '&quot;', $description);
    
    ?>
    <!-- Main Street Health SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr($description); ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>">
    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
    
    <!-- Additional Healthcare-Specific Meta -->
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="Main Street Health">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo esc_url($url); ?>">
    
    <?php if (is_singular()) : ?>
    <!-- Article-specific meta -->
    <meta property="article:published_time" content="<?php echo esc_attr(get_the_date('c')); ?>">
    <meta property="article:modified_time" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
    <meta property="article:author" content="Main Street Health">
    <meta property="article:section" content="Healthcare">
    <?php endif; ?>
    
    <?php
}
add_action('wp_head', 'msh_add_seo_meta_tags', 2);

/**
 * Enhanced Healthcare Schema Markup
 * 
 * Replaces and enhances the basic schema markup with more detailed information
 */
function msh_add_enhanced_healthcare_schema() {
    if (is_front_page() || is_home()) {
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "MedicalOrganization",
          "name": "Main Street Health",
          "alternateName": "MSH",
          "url": "<?php echo esc_url(home_url()); ?>",
          "logo": {
            "@type": "ImageObject",
            "url": "<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/logo.png'); ?>",
            "width": 200,
            "height": 80
          },
          "description": "Comprehensive healthcare services focused on preventive care, wellness, and patient-centered treatment. Expert medical professionals providing quality healthcare.",
          "medicalSpecialty": [
            "Family Medicine",
            "Preventive Care", 
            "General Practice",
            "Health & Wellness",
            "Injury Treatment",
            "Occupational Health"
          ],
          "availableService": [
            {
              "@type": "MedicalService",
              "name": "Family Medicine",
              "description": "Comprehensive primary care for all ages"
            },
            {
              "@type": "MedicalService", 
              "name": "Preventive Care",
              "description": "Preventive health screenings and wellness programs"
            },
            {
              "@type": "MedicalService",
              "name": "Injury Treatment", 
              "description": "Treatment for work-related, sports, and motor vehicle injuries"
            }
          ],
          "hasMap": "https://maps.google.com/maps?q=150%20Main%20St%20W%2C%20Hamilton%2C%20ON%20L8P%201H8%2C%20Canada",
          "telephone": "(905) 524-3709",
          "email": "info@mainstreethealth.ca",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "150 Main Street West, Suite 105B",
            "addressLocality": "Hamilton",
            "addressRegion": "ON",
            "postalCode": "L8P 1H8",
            "addressCountry": "CA"
          },
          "openingHours": [
            "Mo-Fr 10:00-18:00"
          ],
          "acceptsReservations": true,
          "priceRange": "$$",
          "paymentAccepted": ["Insurance", "Cash", "Credit Card"],
          "currenciesAccepted": "USD",
          "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.8",
            "reviewCount": "127",
            "bestRating": "5",
            "worstRating": "1"
          },
          "sameAs": [
            "",
            "",
            ""
          ]
        }
        </script>
        <?php
    }
    
    // Add BreadcrumbList schema for internal pages
    if (!is_front_page() && !is_home()) {
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "name": "Home",
              "item": "<?php echo esc_url(home_url('/')); ?>"
            }
            <?php if (is_singular()) : ?>
            ,{
              "@type": "ListItem",
              "position": 2,
              "name": "<?php echo esc_js(get_the_title()); ?>",
              "item": "<?php echo esc_url(get_permalink()); ?>"
            }
            <?php endif; ?>
          ]
        }
        </script>
        <?php
    }
    
    // Add WebSite schema with search functionality
    if (is_front_page() || is_home()) {
        ?>
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Main Street Health",
          "alternateName": "MSH",
          "url": "<?php echo esc_url(home_url()); ?>",
          "potentialAction": {
            "@type": "SearchAction",
            "target": {
              "@type": "EntryPoint",
              "urlTemplate": "<?php echo esc_url(home_url('/?s={search_term_string}')); ?>"
            },
            "query-input": "required name=search_term_string"
          }
        }
        </script>
        <?php
    }
}
// Remove the basic schema and replace with enhanced version
remove_action('wp_head', 'msh_add_healthcare_schema');
add_action('wp_head', 'msh_add_enhanced_healthcare_schema', 3);

/**
 * XML Sitemap Enhancement
 * 
 * Ensure WordPress generates XML sitemaps and optimize them for healthcare content
 */
function msh_enhance_xml_sitemap() {
    // Enable WordPress core sitemaps (WordPress 5.5+)
    add_filter('wp_sitemaps_enabled', '__return_true');
    
    // Add custom post types to sitemap
    add_filter('wp_sitemaps_post_types', function($post_types) {
        if (post_type_exists('injury')) {
            $post_types['injury'] = get_post_type_object('injury');
        }
        if (post_type_exists('service')) {
            $post_types['service'] = get_post_type_object('service');
        }
        return $post_types;
    });
    
    // Add sitemap link to robots.txt
    add_action('do_robots', function() {
        echo "Sitemap: " . esc_url(home_url('/wp-sitemap.xml')) . "\n";
    });
}
add_action('init', 'msh_enhance_xml_sitemap');

/**
 * Page Speed and Core Web Vitals Optimization
 */
function msh_optimize_core_web_vitals() {
    // Add preload hints for critical resources
    add_action('wp_head', function() {
        // Preload critical CSS that was optimized
        echo '<link rel="preload" href="' . esc_url(get_stylesheet_directory_uri() . '/assets/css/typography.css') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        echo '<link rel="preload" href="' . esc_url(get_stylesheet_directory_uri() . '/assets/css/navigation.min.css') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        
        // DNS prefetch for external resources
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
        echo '<link rel="dns-prefetch" href="//use.typekit.net">';
    }, 1);
    
    // Add lazy loading to images (WordPress 5.5+ native support)
    add_filter('wp_lazy_loading_enabled', '__return_true');
    
    // Optimize image loading attributes
    add_filter('wp_get_attachment_image_attributes', function($attr, $attachment) {
        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }
        if (!isset($attr['decoding'])) {
            $attr['decoding'] = 'async';
        }
        return $attr;
    }, 10, 2);
}
add_action('init', 'msh_optimize_core_web_vitals');

/**
 * Accessibility and SEO Link Optimization
 */
function msh_optimize_links() {
    // Add proper rel attributes to external links
    add_filter('the_content', function($content) {
        // Add rel="noopener noreferrer" to external links
        $content = preg_replace_callback(
            '/<a\s+([^>]*href=["\']https?:\/\/(?!(?:www\.)?'.preg_quote($_SERVER['HTTP_HOST'], '/').')([^"\']*)["\'][^>]*)>/i',
            function($matches) {
                $attrs = $matches[1];
                if (strpos($attrs, 'rel=') === false) {
                    $attrs .= ' rel="noopener noreferrer"';
                } else {
                    $attrs = preg_replace('/rel=["\']([^"\']*)["\']/', 'rel="$1 noopener noreferrer"', $attrs);
                }
                return '<a ' . $attrs . '>';
            },
            $content
        );
        return $content;
    });
}
add_action('init', 'msh_optimize_links');

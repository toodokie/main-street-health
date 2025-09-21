<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip Links for Accessibility -->
<a class="skip-link screen-reader-text" href="#main"><?php _e('Skip to main content', 'medicross-child'); ?></a>
<a class="skip-link screen-reader-text" href="#navigation"><?php _e('Skip to navigation', 'medicross-child'); ?></a>

<!-- Accessibility Toolbar -->
<div class="accessibility-toolbar">
    <button class="high-contrast-toggle" title="<?php _e('Toggle High Contrast', 'medicross-child'); ?>" aria-label="<?php _e('Toggle High Contrast Mode', 'medicross-child'); ?>">
        A
    </button>
    <button class="font-size-toggle" title="<?php _e('Toggle Large Text', 'medicross-child'); ?>" aria-label="<?php _e('Toggle Large Text Size', 'medicross-child'); ?>">
        A+
    </button>
</div>

<div id="pxl-wapper" class="pxl-wapper">
    <header id="masthead" class="site-header">
        
        <!-- Main Navigation Container -->
        <div class="container">
        <nav class="top-nav" role="navigation" aria-label="<?php _e('Top Navigation', 'medicross-child'); ?>">
            <div class="top-nav-content">
                    <!-- Logo (Left) -->
                    <div class="site-branding">
                        <?php 
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        
                        if (has_custom_logo() && $logo): ?>
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
                            <li><a href="/about_us/">ABOUT US</a></li>
                            <li><a href="/professional/">MEDICAL PROFESSIONAL RESOURCES</a></li>
                            <li><a href="/blog/">BLOG</a></li>
                            <li><a href="/contact-us/">CONTACT</a></li>
                        </ul>
                    </div>

                    <!-- Book Appointment Button (Right) -->
                    <div class="book-appointment-section">
                        <a href="/book-appointment" class="btn-primary">
                            Book Appointment
                        </a>
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
                
                <!-- Mobile Secondary Nav Items -->
                <div class="mobile-secondary-menu">
                    <div class="mobile-menu-item">
                        <a href="#" data-mobile-dropdown="services">
                            Services & Therapies
                            <span class="mobile-chevron">
                                <span class="chevron-line"></span>
                                <span class="chevron-line"></span>
                            </span>
                        </a>
                        <div class="mobile-dropdown" id="services-mobile-dropdown">
                            <a href="/physiotherapy">Physiotherapy</a>
                            <a href="/massage-therapy">Massage Therapy</a>
                            <a href="/chiropractic-care">Chiropractic Care</a>
                            <a href="/acupuncture">Acupuncture</a>
                            <a href="/custom-orthotics">Custom Orthotics</a>
                            <a href="/specialized-treatments">Specialized Treatments</a>
                            <div class="dropdown-footer">
                                <a href="/services-therapies">Visit Services & Therapies page</a>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-item">
                        <a href="#" data-mobile-dropdown="conditions">
                            Conditions
                            <span class="mobile-chevron">
                                <span class="chevron-line"></span>
                                <span class="chevron-line"></span>
                            </span>
                        </a>
                        <div class="mobile-dropdown" id="conditions-mobile-dropdown">
                            <a href="/back-neck-pain">Back & Neck Pain</a>
                            <a href="/sprains-strains">Sprains & Strains</a>
                            <a href="/post-surgical-rehabilitation">Post-Surgical Rehabilitation</a>
                            <a href="/chronic-pain">Chronic Pain</a>
                            <a href="/sports-injuries">Sports Injuries</a>
                            <a href="/neurological-conditions">Neurological Conditions</a>
                            <div class="dropdown-footer">
                                <a href="/conditions">Visit Conditions page</a>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-item">
                        <a href="#" data-mobile-dropdown="injury">
                            Injury Care
                            <span class="mobile-chevron">
                                <span class="chevron-line"></span>
                                <span class="chevron-line"></span>
                            </span>
                        </a>
                        <div class="mobile-dropdown" id="injury-mobile-dropdown">
                            <a href="/workplace-injuries">Workplace Injuries</a>
                            <a href="/wsib-claims">WSIB Claims</a>
                            <a href="/return-to-work-programs">Return-to-Work Programs</a>
                            <a href="/motor-vehicle-accidents">Motor Vehicle Accidents</a>
                            <a href="/sports-injury-programs">Sport-Specific Programs</a>
                            <a href="/return-to-play-protocols">Return-to-Play Protocols</a>
                            <div class="dropdown-footer">
                                <a href="/injury-care">Visit Injury Care page</a>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-item">
                        <a href="#" data-mobile-dropdown="responder">
                            First Responder
                            <span class="mobile-chevron">
                                <span class="chevron-line"></span>
                                <span class="chevron-line"></span>
                            </span>
                        </a>
                        <div class="mobile-dropdown" id="responder-mobile-dropdown">
                            <a href="/first-responder-programs">First Responder Programs</a>
                            <a href="/medical-professionals">Medical Professionals</a>
                            <a href="/firefighters">Firefighters</a>
                            <a href="/police">Police</a>
                            <a href="/paramedics">Paramedics</a>
                            <a href="/occupational-health">Occupational Health</a>
                            <div class="dropdown-footer">
                                <a href="/first-responder">Visit First Responder page</a>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-item">
                        <a href="#" data-mobile-dropdown="products">
                            Products
                            <span class="mobile-chevron">
                                <span class="chevron-line"></span>
                                <span class="chevron-line"></span>
                            </span>
                        </a>
                        <div class="mobile-dropdown" id="products-mobile-dropdown">
                            <a href="/braces-supports">Braces & Supports</a>
                            <a href="/therapeutic-equipment">Therapeutic Equipment</a>
                            <a href="/home-exercise-tools">Home Exercise Tools</a>
                            <a href="/ergonomic-products">Ergonomic Products</a>
                            <a href="/pain-relief-products">Pain Relief Products</a>
                            <a href="/recovery-aids">Recovery Aids</a>
                            <div class="dropdown-footer">
                                <a href="/products">Visit Products page</a>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-item">
                        <a href="#" data-mobile-dropdown="resources">
                            Patient Resources & Coverage
                            <span class="mobile-chevron">
                                <span class="chevron-line"></span>
                                <span class="chevron-line"></span>
                            </span>
                        </a>
                        <div class="mobile-dropdown" id="resources-mobile-dropdown">
                            <a href="/wsib-coverage">WSIB Coverage</a>
                            <a href="/mva-insurance">MVA Insurance</a>
                            <a href="/extended-health-benefits">Extended Health Benefits</a>
                            <a href="/insurance-providers">Insurance Providers</a>
                            <a href="/forms-downloads">Forms & Downloads</a>
                            <a href="/patient-education">Patient Education</a>
                            <div class="dropdown-footer">
                                <a href="/patient-resources-coverage">Visit Patient Resources & Coverage page</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Separator -->
                <hr class="mobile-menu-separator">
                
                <!-- Mobile Top Nav Items -->
                <div class="mobile-top-menu">
                    <a href="/about_us/">About Us</a>
                    <a href="/professional/">Medical Professional Resources</a>
                    <a href="/contact-us/">Contact</a>
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

        <!-- Secondary Navigation -->
        <nav class="secondary-nav" role="navigation" aria-label="<?php _e('Secondary Navigation', 'medicross-child'); ?>">
            <div class="secondary-nav-content">
                    <ul class="secondary-nav-menu">
                        <li>
                            <a href="#" data-dropdown="services">
                                Services & Therapies
                                <span class="chevron">
                                    <span class="chevron-line"></span>
                                    <span class="chevron-line"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu" id="services-dropdown">
                                <div class="dropdown-content">
                                    <div class="dropdown-description">
                                        Personalized rehabilitation journeys designed around your unique recovery needs and professional demands.
                                    </div>
                                    <div class="service-links">
                                        <a href="/physiotherapy">
                                            Physiotherapy
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/massage-therapy">
                                            Massage Therapy
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/chiropractic-care">
                                            Chiropractic Care
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/acupuncture">
                                            Acupuncture
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/custom-orthotics">
                                            Custom Orthotics
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/specialized-treatments">
                                            Specialized Treatments
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                    <div class="dropdown-footer">
                                        <a href="/services-therapies">
                                            Visit Services & Therapies page
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" data-dropdown="conditions">
                                Conditions
                                <span class="chevron">
                                    <span class="chevron-line"></span>
                                    <span class="chevron-line"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu" id="conditions-dropdown">
                                <div class="dropdown-content">
                                    <div class="dropdown-description">
                                        Comprehensive treatment for various conditions affecting your daily life and work performance.
                                    </div>
                                    <div class="service-links">
                                        <a href="/back-neck-pain">
                                            Back & Neck Pain
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/sprains-strains">
                                            Sprains & Strains
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/post-surgical-rehabilitation">
                                            Post-Surgical Rehabilitation
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/chronic-pain">
                                            Chronic Pain
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/sports-injuries">
                                            Sports Injuries
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/neurological-conditions">
                                            Neurological Conditions
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                    <div class="dropdown-footer">
                                        <a href="/conditions">
                                            Visit Conditions page
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" data-dropdown="injury">
                                Injury Care
                                <span class="chevron">
                                    <span class="chevron-line"></span>
                                    <span class="chevron-line"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu" id="injury-dropdown">
                                <div class="dropdown-content">
                                    <div class="dropdown-description">
                                        Specialized care for workplace injuries, motor vehicle accidents, and sports-related injuries with comprehensive support.
                                    </div>
                                    <div class="service-links">
                                        <a href="/workplace-injuries">
                                            Workplace Injuries
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/wsib-claims">
                                            WSIB Claims
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/return-to-work-programs">
                                            Return-to-Work Programs
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/motor-vehicle-accidents">
                                            Motor Vehicle Accidents
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/sports-injury-programs">
                                            Sport-Specific Programs
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/return-to-play-protocols">
                                            Return-to-Play Protocols
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                    <div class="dropdown-footer">
                                        <a href="/injury-care">
                                            Visit Injury Care page
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" data-dropdown="responder">
                                First Responder
                                <span class="chevron">
                                    <span class="chevron-line"></span>
                                    <span class="chevron-line"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu" id="responder-dropdown">
                                <div class="dropdown-content">
                                    <div class="dropdown-description">
                                        Specialized programs for first responders and occupational health with industry-specific solutions.
                                    </div>
                                    <div class="service-links">
                                        <a href="/first-responder-programs">
                                            First Responder Programs
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/medical-professionals">
                                            Medical Professionals
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/firefighters">
                                            Firefighters
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/police">
                                            Police
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/paramedics">
                                            Paramedics
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/occupational-health">
                                            Occupational Health
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                    <div class="dropdown-footer">
                                        <a href="/first-responder">
                                            Visit First Responder page
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" data-dropdown="products">
                                Products
                                <span class="chevron">
                                    <span class="chevron-line"></span>
                                    <span class="chevron-line"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu" id="products-dropdown">
                                <div class="dropdown-content">
                                    <div class="dropdown-description">
                                        High-quality therapeutic products and equipment to support your recovery and wellness journey.
                                    </div>
                                    <div class="service-links">
                                        <a href="/braces-supports">
                                            Braces & Supports
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/therapeutic-equipment">
                                            Therapeutic Equipment
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/home-exercise-tools">
                                            Home Exercise Tools
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/ergonomic-products">
                                            Ergonomic Products
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/pain-relief-products">
                                            Pain Relief Products
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/recovery-aids">
                                            Recovery Aids
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                    <div class="dropdown-footer">
                                        <a href="/products">
                                            Visit Products page
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" data-dropdown="resources">
                                Patient Resources & Coverage
                                <span class="chevron">
                                    <span class="chevron-line"></span>
                                    <span class="chevron-line"></span>
                                </span>
                            </a>
                            <div class="dropdown-menu" id="resources-dropdown">
                                <div class="dropdown-content">
                                    <div class="dropdown-description">
                                        Complete guide to insurance coverage, benefits, and patient resources for all treatment options.
                                    </div>
                                    <div class="service-links">
                                        <a href="/wsib-coverage">
                                            WSIB Coverage
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/mva-insurance">
                                            MVA Insurance
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/extended-health-benefits">
                                            Extended Health Benefits
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/insurance-providers">
                                            Insurance Providers
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/forms-downloads">
                                            Forms & Downloads
                                            <span class="arrow">→</span>
                                        </a>
                                        <a href="/patient-education">
                                            Patient Education
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                    <div class="dropdown-footer">
                                        <a href="/patient-resources-coverage">
                                            Visit Patient Resources & Coverage page
                                            <span class="arrow">→</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
            </div>
        </nav>
        </div>

        <!-- Breadcrumbs -->
        <?php if (!is_front_page()): ?>
        <div class="breadcrumb-container">
            <div class="container">
                <nav class="breadcrumbs" aria-label="<?php _e('Breadcrumb Navigation', 'medicross-child'); ?>">
                    <?php
                    if (function_exists('yoast_breadcrumb')) {
                        yoast_breadcrumb('<div class="breadcrumb">', '</div>');
                    } else {
                        // Custom breadcrumb implementation
                        echo '<div class="breadcrumb">';
                        echo '<a href="' . home_url('/') . '">' . __('Home', 'medicross-child') . '</a>';
                        
                        if (is_singular('msh_service')) {
                            echo ' > <a href="/services">' . __('Services', 'medicross-child') . '</a>';
                            echo ' > ' . get_the_title();
                        } elseif (is_singular('msh_team_member')) {
                            echo ' > <a href="/team">' . __('Our Team', 'medicross-child') . '</a>';
                            echo ' > ' . get_the_title();
                        } elseif (is_singular('msh_program')) {
                            echo ' > <a href="/programs">' . __('Programs', 'medicross-child') . '</a>';
                            echo ' > ' . get_the_title();
                        } elseif (is_page()) {
                            echo ' > ' . get_the_title();
                        }
                        echo '</div>';
                    }
                    ?>
                </nav>
            </div>
        </div>
        <?php endif; ?>
        
    </header><!-- #masthead -->

    <div id="pxl-main">
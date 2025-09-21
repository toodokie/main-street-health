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
        
        <!-- MSH Navigation System (WordPress Menu Driven) -->
        <div class="container">
            <?php 
            // Render navigation using WordPress menus
            if (function_exists('msh_render_navigation')) {
                msh_render_navigation();
            } else {
                // Fallback message if functions not loaded
                echo '<!-- MSH Navigation functions not available -->';
            }
            ?>
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
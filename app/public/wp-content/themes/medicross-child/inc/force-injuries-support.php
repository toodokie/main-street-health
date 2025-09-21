<?php
/**
 * Force Injuries support in Post Grid widget
 */

// Method 1: Override the files that get cached by Elementor
add_action('wp_loaded', function() {
    $dir = WP_CONTENT_DIR . '/uploads/elementor-widget/';
    if (is_dir($dir)) {
        foreach (glob($dir . '*.php') as $cached_widget_file) {
            $content = @file_get_contents($cached_widget_file);
            if (!$content) continue;
            if (strpos($content, 'pxl_post_carousel') === false && strpos($content, 'pxl_post_grid') === false) continue;
            if (strpos($content, "'injury'") !== false) continue;
            $new_content = str_replace(
                "['service','post','portfolio','industries','pxl_product']",
                "['service','post','portfolio','industries','pxl_product','injury']",
                $content
            );
            $new_content = str_replace(
                "['post','portfolio','service','industries','pxl_product']",
                "['post','portfolio','service','industries','pxl_product','injury']",
                $new_content
            );
            if ($new_content !== $content) {
                @file_put_contents($cached_widget_file, $new_content);
            }
        }
    }
}, 1);

// Method 2: Hook into file system operations
add_filter('wp_filesystem_method', function($method) {
    // When Elementor writes widget cache files, intercept and modify
    add_action('shutdown', function() {
        $dir = WP_CONTENT_DIR . '/uploads/elementor-widget/';
        if (is_dir($dir)) {
            foreach (glob($dir . '*.php') as $cached_widget_file) {
                $content = @file_get_contents($cached_widget_file);
                if (!$content) continue;
                if (strpos($content, 'pt_supports') === false) continue;
                if (strpos($content, "'injury'") !== false) continue;
                $new_content = str_replace(
                    "\$pt_supports = ['service','post','portfolio','industries','pxl_product'];",
                    "\$pt_supports = ['service','post','portfolio','industries','pxl_product','injury'];",
                    $content
                );
                $new_content = str_replace(
                    "\$pt_supports = ['post','portfolio','service','industries','pxl_product'];",
                    "\$pt_supports = ['post','portfolio','service','industries','pxl_product','injury'];",
                    $new_content
                );
                if ($new_content !== $content) {
                    @file_put_contents($cached_widget_file, $new_content);
                }
            }
        }
    });
    
    return $method;
});

// Method 3: Delete the cached file to force regeneration with our modifications
add_action('admin_init', function() {
    if (isset($_GET['force_widget_regen']) && current_user_can('administrator')) {
        $files = [
            WP_CONTENT_DIR . '/uploads/elementor-widget/class-pxl-post-grid.php',
            WP_CONTENT_DIR . '/uploads/elementor-widget/class-pxl-post-carousel.php',
        ];
        $deleted = false;
        foreach ($files as $f) {
            if (file_exists($f)) { unlink($f); $deleted = true; }
        }
        if ($deleted) {
            wp_redirect(admin_url());
            exit;
        }
    }
});

// Add admin notice with instructions
add_action('admin_notices', function() {
    $cached_widget_file = WP_CONTENT_DIR . '/uploads/elementor-widget/class-pxl-post-grid.php';
    if (file_exists($cached_widget_file)) {
        $content = file_get_contents($cached_widget_file);
        if (strpos($content, "'injury'") === false) {
            ?>
            <div class="notice notice-info">
                <p>
                    <strong>Injuries Post Type:</strong> 
                    To make Injuries available in Post Grid widgets, 
                    <a href="<?php echo admin_url('admin.php?force_widget_regen=1'); ?>">click here to regenerate Elementor widget cache</a>
                    or go to Elementor → Tools → Regenerate CSS & Data.
                </p>
            </div>
            <?php
        }
    }
});

<?php
/**
 * Fix PXL Product Layouts for Case Post Grid Widget
 * Add missing layout options for Products & Devices using direct edit to theme file
 */

// Since function override doesn't work, let's directly modify the theme file
add_action('init', function() {
    // Get the theme file path
    $theme_functions_file = get_template_directory() . '/elements/element-functions.php';
    
    if (file_exists($theme_functions_file)) {
        $content = file_get_contents($theme_functions_file);
        
        // Check if pxl_product case is missing
        if (strpos($content, "case 'pxl_product':") === false) {
            // Find the position to insert our case (before case 'post':)
            $insert_before = "        case 'post':";
            $insertion_point = strpos($content, $insert_before);
            
            if ($insertion_point !== false) {
                $pxl_product_case = "        // ADD MISSING PXL_PRODUCT CASE
        case 'pxl_product':
        \$option_layouts = [
            'pxl_product-1' => [
                'label' => esc_html__( 'Layout 1', 'medicross' ),
                'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_post_grid/portfolio-layout1.jpg'
            ],
        ];
        break;

";
                
                $new_content = substr_replace($content, $pxl_product_case, $insertion_point, 0);
                
                // Write the modified content back (with backup)
                $backup_file = $theme_functions_file . '.backup-' . date('Y-m-d-H-i-s');
                copy($theme_functions_file, $backup_file);
                file_put_contents($theme_functions_file, $new_content);
                
                error_log('MSH: Added pxl_product case to theme functions. Backup created: ' . $backup_file);
            }
        }
    }
}, 1);
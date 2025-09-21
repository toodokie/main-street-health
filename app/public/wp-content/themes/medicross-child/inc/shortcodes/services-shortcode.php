<?php
/**
 * Services Link List Shortcode
 * Fallback solution that always works
 */

function msh_services_shortcode($atts) {
    $atts = shortcode_atts(array(
        'width' => '100%',
        'services' => 'Physiotherapy|/physiotherapy,Chiropractic Care|/chiropractic-care,Massage Therapy|/massage-therapy,Acupuncture|/acupuncture,Custom Orthotics|/custom-orthotics',
        'show_arrow' => 'yes',
        'service_color' => '#2D3E4E',
        'learn_more_color' => '#051B2D',
        'hover_color' => '#218E9C',
        'divider_color' => '#E9EFE8',
        'divider_height' => '2px',
    ), $atts);
    
    // Parse services
    $services_list = array();
    if (!empty($atts['services'])) {
        $services_array = explode(',', $atts['services']);
        foreach ($services_array as $service) {
            $parts = explode('|', trim($service));
            if (count($parts) == 2) {
                $services_list[] = array(
                    'name' => trim($parts[0]),
                    'url' => trim($parts[1])
                );
            }
        }
    }
    
    if (empty($services_list)) {
        return '<p>No services configured.</p>';
    }
    
    $show_arrow = $atts['show_arrow'] === 'yes';
    $widget_id = 'msh-services-' . uniqid();
    
    ob_start();
    ?>
    
    <style>
    #<?php echo $widget_id; ?> {
        width: <?php echo esc_attr($atts['width']); ?>;
        max-width: 100%;
    }
    #<?php echo $widget_id; ?> .service-link-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0;
        text-decoration: none !important;
        border-bottom: <?php echo esc_attr($atts['divider_height']); ?> solid <?php echo esc_attr($atts['divider_color']); ?>;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    #<?php echo $widget_id; ?> .service-link-row:hover {
        background-color: rgba(233, 239, 232, 0.3);
        text-decoration: none !important;
    }
    #<?php echo $widget_id; ?> .service-link-row:focus,
    #<?php echo $widget_id; ?> .service-link-row:active,
    #<?php echo $widget_id; ?> .service-link-row:visited {
        text-decoration: none !important;
        border: none !important;
        box-shadow: none !important;
        background-image: none !important;
    }
    /* Remove underlines from all text elements */
    #<?php echo $widget_id; ?> a,
    #<?php echo $widget_id; ?> a:hover,
    #<?php echo $widget_id; ?> a:focus,
    #<?php echo $widget_id; ?> a:active,
    #<?php echo $widget_id; ?> a:visited,
    #<?php echo $widget_id; ?> .service-name,
    #<?php echo $widget_id; ?> .learn-more {
        text-decoration: none !important;
        border-bottom: none !important;
        box-shadow: none !important;
        background-image: none !important;
    }
    #<?php echo $widget_id; ?> .service-name {
        font-size: 18px;
        font-weight: 600;
        color: <?php echo esc_attr($atts['service_color']); ?>;
        font-family: 'Source Sans Pro', sans-serif;
        text-decoration: none !important;
    }
    #<?php echo $widget_id; ?> .learn-more {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: <?php echo esc_attr($atts['learn_more_color']); ?>;
        font-family: 'Source Sans Pro', sans-serif;
        transition: color 0.3s ease;
        text-decoration: none !important;
    }
    #<?php echo $widget_id; ?> .service-link-row:hover .learn-more {
        color: <?php echo esc_attr($atts['hover_color']); ?>;
        text-decoration: none !important;
    }
    #<?php echo $widget_id; ?> .service-link-row:hover .service-name {
        text-decoration: none !important;
    }
    #<?php echo $widget_id; ?> .arrow-icon {
        width: 20px;
        height: 20px;
        transition: transform 0.3s ease, color 0.3s ease;
    }
    #<?php echo $widget_id; ?> .service-link-row:hover .arrow-icon {
        transform: translateX(4px);
        color: <?php echo esc_attr($atts['hover_color']); ?>;
    }
    </style>
    
    <div class="msh-services-link-list" id="<?php echo $widget_id; ?>">
        <?php foreach ($services_list as $service): ?>
            <a href="<?php echo esc_url($service['url']); ?>" class="service-link-row">
                <span class="service-name"><?php echo esc_html($service['name']); ?></span>
                <span class="learn-more">
                    Learn more
                    <?php if ($show_arrow): ?>
                    <svg class="arrow-icon" viewBox="0 0 24 24" fill="none">
                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
    
    <?php
    return ob_get_clean();
}
add_shortcode('msh_services', 'msh_services_shortcode');
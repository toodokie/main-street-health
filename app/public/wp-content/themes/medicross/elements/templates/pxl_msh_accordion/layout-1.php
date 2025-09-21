<?php
// Enqueue MSH Accordion assets
wp_enqueue_style('pxl-msh-accordion');
wp_enqueue_script('pxl-msh-accordion');

$html_id = pxl_get_element_id($settings);
$accordion_items = $widget->get_setting('accordion_items', []);
$layout_style = $widget->get_setting('layout_style', 'medical-clean');
$animation_speed = (int) $widget->get_setting('animation_speed', 300);
$animation_preset = $widget->get_setting('animation', '');
$close_others_setting = $widget->get_setting('close_others', 'yes');
$allow_multiple_open = $widget->get_setting('allow_multiple_open', '');
$start_state = $widget->get_setting('start_state', '');
$first_item_open = $widget->get_setting('first_item_open', 'no');
$toggle_position = $widget->get_setting('toggle_position', 'right');
$title_tag = $widget->get_setting('title_tag', 'h3');
$show_dividers = $widget->get_setting('show_dividers', 'no');
$toggle_shape = $widget->get_setting('toggle_shape', 'soft-square');
$enable_hover = $widget->get_setting('enable_hover', 'yes');
$image_hover_effect = $widget->get_setting('image_hover_effect', 'none');

// Optional scroll behavior
$scroll_into_view = $widget->get_setting('scroll_into_view', '');
$scroll_offset = (int) $widget->get_setting('scroll_offset', 0);

// Apply animation preset if provided
if ($animation_preset === 'none') {
    $animation_speed = 0;
} elseif ($animation_preset === 'fast') {
    $animation_speed = 200;
} elseif ($animation_preset === 'medium') {
    $animation_speed = 300;
} elseif ($animation_preset === 'slow') {
    $animation_speed = 500;
}

// Determine final close others behavior (allow_multiple_open overrides)
$close_others = ($allow_multiple_open === 'yes') ? 'no' : $close_others_setting;

// If no items, still render a minimal wrapper so the widget is visible (admin sees a hint)
$has_items = !empty($accordion_items);

$widget_id = $widget->get_id();

// Register widget areas for accordion items (if using Widget Area content type)
foreach ($accordion_items as $index => $item) {
    $content_type = $item['content_type'];

    if (in_array($content_type, ['widgets', 'mixed'])) {
        $area_id = !empty($item['widget_area_id']) ? sanitize_title($item['widget_area_id']) : 'msh_accordion_' . $widget_id . '_item_' . $index;

        // Register the sidebar immediately (ensure single registration)
        global $wp_registered_sidebars;
        $is_elementor_ajax = function_exists('wp_doing_ajax') && wp_doing_ajax() && isset($_POST['action']) && strpos(sanitize_text_field(wp_unslash($_POST['action'])), 'elementor') !== false;
        if (!isset($wp_registered_sidebars[$area_id]) && !$is_elementor_ajax) {
            $sidebar_name = 'MSH Accordion - ' . $item['item_title'] . ' - Item ' . ($index + 1);
            register_sidebar(array(
                'name'          => $sidebar_name,
                'id'            => $area_id,
                'before_widget' => '<div class="pxl-accordion-widget-item">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="pxl-accordion-widget-title">',
                'after_title'   => '</h4>',
            ));
        }
    }
}

$wrapper_classes = [
    'pxl-msh-accordion',
    'pxl-msh-accordion-style-' . $layout_style,
    'toggle-' . $toggle_position,
];

// Hover class toggle
$wrapper_classes[] = ($enable_hover === 'yes') ? 'msh-hover-enabled' : 'msh-hover-disabled';

// Debug: Output style and item count (admin only)
if (current_user_can('administrator')) {
    echo '<!-- MSH Accordion Layout Style: ' . $layout_style . '; Items: ' . ($has_items ? count($accordion_items) : 0) . ' -->';
}

$wrapper_attributes = [
    'id' => $html_id,
    'class' => implode(' ', $wrapper_classes),
    'data-animation-speed' => $animation_speed,
    'data-close-others' => $close_others,
    'data-first-open' => $first_item_open,
    'data-start-state' => $start_state,
    'data-scroll-into-view' => $scroll_into_view,
    'data-scroll-offset' => $scroll_offset,
    'data-image-hover' => $image_hover_effect,
];
?>

<div id="<?php echo esc_attr($html_id); ?>" class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" data-animation-speed="<?php echo esc_attr($animation_speed); ?>" data-close-others="<?php echo esc_attr($close_others); ?>" data-first-open="<?php echo esc_attr($first_item_open); ?>" data-start-state="<?php echo esc_attr($start_state); ?>" data-scroll-into-view="<?php echo esc_attr($scroll_into_view); ?>" data-scroll-offset="<?php echo esc_attr($scroll_offset); ?>" data-image-hover="<?php echo esc_attr($image_hover_effect); ?>">
    <?php if (!$has_items) : ?>
        <?php if (current_user_can('administrator')) : ?>
            <div class="pxl-accordion-no-widgets"><p><?php esc_html_e('MSH Accordion: No items configured.', 'medicross'); ?></p></div>
        <?php endif; ?>
    <?php else : ?>
    <?php $opened_once = false; foreach ($accordion_items as $index => $item) :
        $item_id = !empty($item['item_id']) ? sanitize_title($item['item_id']) : 'accordion-item-' . $index;
        $default_open = isset($item['default_open']) && $item['default_open'] === 'yes';
        // Determine initial open state
        $is_first_open = false;
        if ($start_state === 'first') {
            $is_first_open = ($index === 0);
        } elseif ($start_state === 'by_anchor') {
            $is_first_open = false; // handled by JS
        } else {
            if ($default_open) {
                $is_first_open = true;
            } elseif ($index === 0 && $first_item_open === 'yes') {
                $is_first_open = true;
            }
        }
        if ($close_others === 'yes') {
            if ($opened_once) { $is_first_open = false; }
            if ($is_first_open) { $opened_once = true; }
        }
        
        $item_classes = [
            'pxl-accordion-item',
            $is_first_open ? 'active' : 'collapsed'
        ];
        
        $content_type = $item['content_type'];
        $area_id = !empty($item['widget_area_id']) ? sanitize_title($item['widget_area_id']) : 'msh_accordion_' . $widget_id . '_item_' . $index;
    ?>
        <div class="<?php echo implode(' ', $item_classes); ?>" data-item-id="<?php echo esc_attr($item_id); ?>">
            <div class="pxl-accordion-header" id="<?php echo esc_attr($item_id); ?>-header" role="button" tabindex="0" aria-expanded="<?php echo $is_first_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr($item_id); ?>-content">
                <div class="pxl-accordion-header-content">
                    <div class="pxl-accordion-text">
                        <<?php echo esc_attr($title_tag); ?> class="pxl-accordion-title"><?php echo esc_html($item['item_title']); ?></<?php echo esc_attr($title_tag); ?>>
                        <?php if (!empty($item['item_description'])) : ?>
                            <p class="pxl-accordion-description"><?php echo esc_html($item['item_description']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="pxl-accordion-toggle toggle-shape-<?php echo esc_attr($toggle_shape); ?>">
                        <span class="pxl-accordion-icon-plus"></span>
                    </div>
                </div>
            </div>
            
            <div class="pxl-accordion-content" id="<?php echo esc_attr($item_id); ?>-content" role="region" aria-labelledby="<?php echo esc_attr($item_id); ?>-header" style="<?php echo !$is_first_open ? 'display: none;' : ''; ?>">
                <div class="pxl-accordion-content-inner">
                    <?php
                    // Render content based on type
                    switch ($content_type) :
                        case 'widgets':
                            if (is_active_sidebar($area_id)) {
                                echo '<div class="pxl-accordion-widget-area">';
                                dynamic_sidebar($area_id);
                                echo '</div>';
                            } else {
                                echo '<div class="pxl-accordion-no-widgets">';
                                echo '<p>' . sprintf(
                                    esc_html__('Add widgets to "%s" in Appearance → Widgets', 'medicross'),
                                    'MSH Accordion - ' . $item['item_title'] . ' - Item ' . ($index + 1)
                                ) . '</p>';
                                echo '</div>';
                            }
                            break;
                            
                        case 'shortcode':
                            if (!empty($item['shortcode_content'])) {
                                echo '<div class="pxl-accordion-shortcode-content">';
                                echo do_shortcode(wp_kses_post($item['shortcode_content']));
                                echo '</div>';
                            }
                            break;
                            
                        case 'editor':
                            if (!empty($item['editor_content'])) {
                                echo '<div class="pxl-accordion-editor-content">';
                                echo wp_kses_post($item['editor_content']);
                                echo '</div>';
                            }
                            break;
                            
                        case 'mixed':
                            // Widget area first
                            if (is_active_sidebar($area_id)) {
                                echo '<div class="pxl-accordion-widget-area">';
                                dynamic_sidebar($area_id);
                                echo '</div>';
                            }
                            
                            // Then shortcode content
                            if (!empty($item['shortcode_content'])) {
                                echo '<div class="pxl-accordion-shortcode-content">';
                                echo do_shortcode(wp_kses_post($item['shortcode_content']));
                                echo '</div>';
                            }
                            break;
                        case 'template':
                            if (!empty($item['template_id']) && class_exists('Elementor\\Plugin')) {
                                $template_id = (int) $item['template_id'];
                                if ($template_id > 0) {
                                    $content = Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id);
                                    echo '<div class="pxl-accordion-template-content">';
                                    echo $content;
                                    echo '</div>';
                                }
                            }
                            break;
                    endswitch;
                    
                    // Render button if enabled
                    if (!empty($item['show_button']) && $item['show_button'] === 'yes' && !empty($item['button_text'])) {
                        $button_url = $item['button_url'] ?? [];
                        $button_text = esc_html($item['button_text']);
                        $url = !empty($button_url['url']) ? esc_url($button_url['url']) : '#';
                        $target = !empty($button_url['is_external']) && $button_url['is_external'] ? '_blank' : '';
                        $nofollow = !empty($button_url['nofollow']) && $button_url['nofollow'] ? 'nofollow' : '';
                        
                        $button_attributes = [];
                        if ($target) $button_attributes[] = 'target="' . $target . '"';
                        if ($nofollow) $button_attributes[] = 'rel="' . $nofollow . '"';
                        
                        echo '<div class="pxl-accordion-button-wrapper">';
                        echo '<a href="' . $url . '" class="pxl-accordion-button"' . (!empty($button_attributes) ? ' ' . implode(' ', $button_attributes) : '') . '>' . $button_text . '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <?php 
        // Add divider between items (not after last item)
        if ($show_dividers === 'yes' && $index < count($accordion_items) - 1) : ?>
            <div class="pxl-accordion-divider"></div>
        <?php endif; ?>
        
    <?php endforeach; endif; ?>
</div>

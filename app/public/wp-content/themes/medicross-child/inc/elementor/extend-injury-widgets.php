<?php
/**
 * Extend theme widgets (Case Post Carousel) to support Injury post type
 */

// Add Injury to post_type selector on Case Post Carousel
// Try multiple hook timings to cover theme variations
add_action('elementor/element/pxl_post_carousel/layout_section/after_section_start', function($element) {
    $control = $element->get_controls('post_type');
    if ($control && isset($control['options'])) {
        $control['options']['injury'] = esc_html__('Injuries', 'medicross-child');
        $element->update_control('post_type', $control);
    }
}, 20);
add_action('elementor/element/pxl_post_carousel/layout_section/before_section_end', function($element) {
    $control = $element->get_controls('post_type');
    if ($control && isset($control['options'])) {
        $control['options']['injury'] = esc_html__('Injuries', 'medicross-child');
        $element->update_control('post_type', $control);
    }
}, 20);
add_action('elementor/element/pxl_post_carousel/layout_section/after_section_end', function($element) {
    $control = $element->get_controls('post_type');
    if ($control && isset($control['options'])) {
        $control['options']['injury'] = esc_html__('Injuries', 'medicross-child');
        $element->update_control('post_type', $control);
    }
}, 20);

// Add Injury term and ID selectors to Source section (to mirror theme behavior)
add_action('elementor/element/pxl_post_carousel/section_source/after_section_start', function($element) {
    if (!function_exists('pxl_get_grid_term_options') || !function_exists('medicross_list_post')) {
        // If parent helpers missing, fall back to WP functions
        $tax_options = [];
        $terms = get_terms(['taxonomy' => 'injury-category', 'hide_empty' => false]);
        foreach ($terms as $t) { $tax_options[$t->slug] = $t->name; }
        $post_options = [];
        $posts = get_posts(['post_type' => 'injury', 'numberposts' => -1]);
        foreach ($posts as $p) { $post_options[$p->ID] = $p->post_title; }
    } else {
        $tax_options = pxl_get_grid_term_options('injury', ['injury-category']);
        $post_options = medicross_list_post('injury', false);
    }

    // Terms control (when selecting by terms)
    $element->add_control(
        'source_injury',
        [
            'label' => esc_html__('Select Term of Injuries', 'medicross-child'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $tax_options,
            'condition' => [
                'post_type' => 'injury',
                'select_post_by' => 'term_selected',
            ],
        ]
    );

    // Posts control (when selecting specific posts)
    $element->add_control(
        'source_injury_post_ids',
        [
            'label' => esc_html__('Select Injury posts', 'medicross-child'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $post_options,
            'label_block' => true,
            'condition' => [
                'post_type' => 'injury',
                'select_post_by' => 'post_selected',
            ],
        ]
    );
}, 20);

// Provide layout options for Injuries by reusing Service layouts
add_action('elementor/element/pxl_post_carousel/layout_section/after_section_start', function($element) {
    // Add a layout control for injuries that points to service layouts
    $service_layout_options = [
        'service-1' => [
            'label' => esc_html__('Layout 1', 'medicross-child'),
            'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_post_carousel/service-layout1.jpg'
        ],
        'service-3' => [
            'label' => esc_html__('Layout 3', 'medicross-child'),
            'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_post_carousel/service-layout3.jpg'
        ],
    ];

    $element->add_control(
        'layout_injury',
        [
            'label'    => esc_html__('Select Template of Injuries', 'medicross-child'),
            'type'     => 'layoutcontrol',
            'default'  => 'service-1',
            'options'  => $service_layout_options,
            'prefix_class' => 'pxl-post-layout-',
            'condition' => [
                'post_type' => ['injury']
            ]
        ]
    );
}, 30);

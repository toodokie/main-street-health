<?php
// Add product icon controls to Case Post Grid so pxl_product can show an icon like Services
add_action('elementor/element/pxl_post_grid/tab_grid/after_section_start', function($element){
    // Add under Grid tab, visible when post_type = pxl_product and layout_service = service-2
    $element->add_control('product_icon_heading', [
        'label' => __('Products & Devices Icon', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::HEADING,
        'condition' => [ 'post_type' => 'pxl_product' ],
    ]);
    $element->add_control('product_icon_type', [
        'label' => __('Icon Type', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::SELECT,
        'options' => [ 'none'=>__('None','medicross-child'), 'icon'=>__('Font Icon','medicross-child'), 'image'=>__('Image','medicross-child') ],
        'default' => 'none',
        'condition' => [ 'post_type' => 'pxl_product' ],
    ]);
    $element->add_control('product_icon_font', [
        'label' => __('Font Icon Class (e.g., flaticon-heart)', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::TEXT,
        'condition' => [ 'post_type' => 'pxl_product', 'product_icon_type' => 'icon' ],
    ]);
    $element->add_control('product_icon_img', [
        'label' => __('Icon Image', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::MEDIA,
        'condition' => [ 'post_type' => 'pxl_product', 'product_icon_type' => 'image' ],
    ]);
    
    // Hide Custom Box control - this stays in Grid tab as it's functional
    $element->add_control('hide_custom_box', [
        'label' => __('Hide Custom Box', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __('Yes', 'medicross-child'),
        'label_off' => __('No', 'medicross-child'),
        'return_value' => 'yes',
        'default' => '',
        'condition' => [ 'post_type' => 'pxl_product' ],
        'selectors' => [
            '{{WRAPPER}} .pxl-grid-item.custom-box' => 'display: none !important;',
        ],
    ]);
}, 20);

// Add styling controls to Style tab for Products & Devices
add_action('elementor/element/pxl_post_grid/section_style_title/after_section_end', function($element){
    
    $element->start_controls_section('product_styling_section', [
        'label' => __('Products & Devices Styling', 'medicross-child'),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'conditions' => [
            'terms' => [
                [
                    'terms' => [
                        ['name' => 'post_type', 'operator' => '==', 'value' => 'pxl_product']
                    ]
                ]
            ],
        ],
    ]);
    
    // Title Tag Control
    $element->add_control('product_title_tag', [
        'label' => __('Title Tag', 'medicross-child'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
        ],
        'default' => 'h3',
    ]);
    
    // Text Color Controls
    $element->add_control('product_title_color', [
        'label' => __('Title Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-title-color: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--title a' => 'color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_title_hover_color', [
        'label' => __('Title Hover Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-title-hover-color: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--title a:hover, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner:hover .pxl-post--title a' => 'color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_content_color', [
        'label' => __('Content Text Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-content-color: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--content' => 'color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_readmore_color', [
        'label' => __('Read More Link Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-readmore-color: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--readmore a, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--readmore .btn-readmore, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .btn-readmore' => 'color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_readmore_hover_color', [
        'label' => __('Read More Hover Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-readmore-hover-color: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--readmore a:hover, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner:hover .pxl-post--readmore a, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .btn-readmore:hover, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner:hover .btn-readmore' => 'color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_card_background', [
        'label' => __('Card Background Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-card-background: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner' => 'background-color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_card_hover_background', [
        'label' => __('Card Hover Background', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-card-hover-background: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner:hover' => 'background-color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_button_hover_color', [
        'label' => __('Button Hover Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-button-hover-color: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .btn-readmore:hover, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner:hover .btn-readmore' => 'color: {{VALUE}} !important;',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .btn-readmore:hover i, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner:hover .btn-readmore i' => 'color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_icon_hover_background', [
        'label' => __('Icon Hover Background', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-icon-hover-background: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--icon:hover, {{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--inner:hover .pxl-post--icon' => 'background-color: {{VALUE}} !important;',
        ],
    ]);

    $element->add_control('product_icon_color', [
        'label' => __('Icon Color', 'medicross-child'),
        'type'  => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}}' => '--product-icon-color: {{VALUE}};',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--icon i' => 'color: {{VALUE}} !important;',
            '{{WRAPPER}} .pxl-grid[data-post-type="pxl_product"] .pxl-post--icon svg' => 'fill: {{VALUE}} !important;',
        ],
    ]);
    
    $element->end_controls_section();
    
}, 20);

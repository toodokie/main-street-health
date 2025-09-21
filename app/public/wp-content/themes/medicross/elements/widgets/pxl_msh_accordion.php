<?php
$msh_templates = function_exists('medicross_get_templates_option') ? medicross_get_templates_option('widget', []) : [];
pxl_add_custom_widget(
    array(
        'name' => 'pxl_msh_accordion',
        'title' => esc_html__('MSH Accordion', 'medicross'),
        'icon' => 'eicon-accordion',
        'categories' => array('pxltheme-core'),
        'scripts' => [
            'pxl-msh-accordion',
        ],
        'styles' => [
            'pxl-msh-accordion',
        ],
        'params' => array(
            'sections' => array(
                // Layout Section
                array(
                    'name' => 'layout_section',
                    'label' => esc_html__('Layout', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'layout_style',
                            'label' => esc_html__('Layout Style', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'medical-clean' => esc_html__('Medical Clean', 'medicross'),
                                'medical-card' => esc_html__('Medical Card', 'medicross'),
                                'medical-minimal' => esc_html__('Medical Minimal', 'medicross'),
                            ],
                            'default' => 'medical-clean',
                        ),
                        array(
                            'name' => 'start_state',
                            'label' => esc_html__('Start State', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => esc_html__('None (Respect per-item)', 'medicross'),
                                'first' => esc_html__('Open First', 'medicross'),
                                'by_anchor' => esc_html__('Open By Anchor (#hash)', 'medicross'),
                            ],
                            'default' => '',
                        ),
                        array(
                            'name' => 'animation_speed',
                            'label' => esc_html__('Animation Speed (ms)', 'medicross'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 300,
                            'min' => 100,
                            'max' => 1000,
                            'step' => 50,
                        ),
                        array(
                            'name' => 'animation',
                            'label' => esc_html__('Animation', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => esc_html__('Custom (ms)', 'medicross'),
                                'none' => esc_html__('None', 'medicross'),
                                'fast' => esc_html__('Fast (200ms)', 'medicross'),
                                'medium' => esc_html__('Medium (300ms)', 'medicross'),
                                'slow' => esc_html__('Slow (500ms)', 'medicross'),
                            ],
                            'default' => '',
                        ),
                        array(
                            'name' => 'close_others',
                            'label' => esc_html__('Close Others When Opening', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'yes',
                        ),
                        array(
                            'name' => 'allow_multiple_open',
                            'label' => esc_html__('Allow Multiple Open', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => '',
                            'description' => esc_html__('Overrides "Close Others" when enabled.', 'medicross'),
                        ),
                        array(
                            'name' => 'first_item_open',
                            'label' => esc_html__('Open First Item by Default', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'no',
                        ),
                        array(
                            'name' => 'scroll_into_view',
                            'label' => esc_html__('Scroll Into View On Open', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => '',
                        ),
                        array(
                            'name' => 'scroll_offset',
                            'label' => esc_html__('Scroll Offset (px)', 'medicross'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 0,
                            'min' => 0,
                            'max' => 400,
                            'step' => 5,
                            'condition' => [ 'scroll_into_view' => 'yes' ],
                        ),
                    ),
                ),

                // Accordion Items Section
                array(
                    'name' => 'items_section',
                    'label' => esc_html__('Accordion Items', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'accordion_items',
                            'label' => esc_html__('Accordion Items', 'medicross'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'item_title',
                                    'label' => esc_html__('Title', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => esc_html__('Accordion Title', 'medicross'),
                                    'placeholder' => esc_html__('Enter title', 'medicross'),
                                    'label_block' => true,
                                    'dynamic' => [ 'active' => true ],
                                ),
                                array(
                                    'name' => 'item_description',
                                    'label' => esc_html__('Description (Closed State)', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'default' => esc_html__('Description text that appears when accordion is closed...', 'medicross'),
                                    'placeholder' => esc_html__('Enter description', 'medicross'),
                                    'rows' => 3,
                                    'dynamic' => [ 'active' => true ],
                                ),
                                array(
                                    'name' => 'content_type',
                                    'label' => esc_html__('Content Type', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'widgets' => esc_html__('Widget Area', 'medicross'),
                                        'shortcode' => esc_html__('Shortcode', 'medicross'),
                                        'editor' => esc_html__('Rich Text Editor', 'medicross'),
                                        'mixed' => esc_html__('Widget Area + Shortcode', 'medicross'),
                                        'template' => esc_html__('Saved Template', 'medicross'),
                                    ],
                                    'default' => 'widgets',
                                ),
                                array(
                                    'name' => 'widget_area_id',
                                    'label' => esc_html__('Widget Area ID', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => '',
                                    'placeholder' => esc_html__('Auto-generated if empty', 'medicross'),
                                    'description' => esc_html__('Unique ID for widget area. Leave empty to auto-generate.', 'medicross'),
                                    'condition' => [
                                        'content_type' => ['widgets', 'mixed'],
                                    ],
                                ),
                                array(
                                    'name' => 'template_id',
                                    'label' => esc_html__('Select Template', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => $msh_templates,
                                    'condition' => [
                                        'content_type' => 'template',
                                    ],
                                ),
                                array(
                                    'name' => 'shortcode_content',
                                    'label' => esc_html__('Shortcode Content', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'placeholder' => esc_html__('Enter shortcodes or HTML content', 'medicross'),
                                    'rows' => 5,
                                    'condition' => [
                                        'content_type' => ['shortcode', 'mixed'],
                                    ],
                                    'dynamic' => [ 'active' => true ],
                                ),
                                array(
                                    'name' => 'editor_content',
                                    'label' => esc_html__('Rich Text Content', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                                    'default' => '',
                                    'condition' => [
                                        'content_type' => 'editor',
                                    ],
                                    'dynamic' => [ 'active' => true ],
                                ),
                                array(
                                    'name' => 'default_open',
                                    'label' => esc_html__('Start Open', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::SWITCHER,
                                    'default' => '',
                                ),
                                array(
                                    'name' => 'item_id',
                                    'label' => esc_html__('Item ID', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'placeholder' => esc_html__('unique-id-for-linking', 'medicross'),
                                    'description' => esc_html__('Optional ID for direct linking (#anchor)', 'medicross'),
                                ),
                                array(
                                    'name' => 'show_button',
                                    'label' => esc_html__('Show Button', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::SWITCHER,
                                    'default' => '',
                                    'return_value' => 'yes',
                                    'separator' => 'before',
                                ),
                                array(
                                    'name' => 'button_text',
                                    'label' => esc_html__('Button Text', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'default' => esc_html__('Learn More', 'medicross'),
                                    'condition' => [
                                        'show_button' => 'yes',
                                    ],
                                ),
                                array(
                                    'name' => 'button_url',
                                    'label' => esc_html__('Button Link', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::URL,
                                    'placeholder' => esc_html__('https://your-link.com', 'medicross'),
                                    'default' => [
                                        'url' => '',
                                        'is_external' => false,
                                        'nofollow' => false,
                                    ],
                                    'condition' => [
                                        'show_button' => 'yes',
                                    ],
                                ),
                            ),
                            'default' => [
                                [
                                    'item_title' => esc_html__('Wrist Braces', 'medicross'),
                                    'item_description' => esc_html__('Main Street Health offers a wide array of functional, fashionable hand and wrist supports. Our selection includes flexible, adjustable wrist support options as well as rigid immobilizers.', 'medicross'),
                                    'content_type' => 'widgets',
                                ],
                                [
                                    'item_title' => esc_html__('Ankle Braces', 'medicross'),
                                    'item_description' => esc_html__('Support wraps and stability braces to prevent sprains, reduce swelling, and provide protection post-injury or surgery — especially useful during sports or work.', 'medicross'),
                                    'content_type' => 'widgets',
                                ],
                            ],
                            'title_field' => '{{{ item_title }}}',
                        ),
                    ),
                ),

                // Header Styling Section
                array(
                    'name' => 'header_styling_section',
                    'label' => esc_html__('Header Styling', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'enable_hover',
                            'label' => esc_html__('Enable Hover Highlight', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'yes',
                        ),
                        array(
                            'name' => 'title_tag',
                            'label' => esc_html__('Title HTML Tag', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'h3',
                            'options' => [
                                'h1' => 'H1',
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'div' => 'DIV',
                            ],
                            'description' => esc_html__('Select the HTML heading tag for accordion titles', 'medicross'),
                        ),
                        array(
                            'name' => 'header_bg_color',
                            'label' => esc_html__('Background Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-header' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'header_bg_hover',
                            'label' => esc_html__('Background Hover', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .msh-hover-enabled .pxl-accordion-header:hover' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'header_border',
                            'label' => esc_html__('Border', 'medicross'),
                            'type' => \Elementor\Group_Control_Border::get_type(),
                            'selector' => '{{WRAPPER}} .pxl-accordion-item',
                        ),
                        array(
                            'name' => 'header_padding',
                            'label' => esc_html__('Padding', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'default' => [
                                'top' => '30',
                                'right' => '30',
                                'bottom' => '30',
                                'left' => '30',
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'medicross'),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'selector' => '{{WRAPPER}} .pxl-accordion-title',
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#2c3e50',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_hover_color',
                            'label' => esc_html__('Title Hover Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .msh-hover-enabled .pxl-accordion-header:hover .pxl-accordion-title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'description_typography',
                            'label' => esc_html__('Description Typography', 'medicross'),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'selector' => '{{WRAPPER}} .pxl-accordion-description',
                        ),
                        array(
                            'name' => 'description_color',
                            'label' => esc_html__('Description Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#666666',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-description' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'description_hover_color',
                            'label' => esc_html__('Description Hover Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .msh-hover-enabled .pxl-accordion-header:hover .pxl-accordion-description' => 'color: {{VALUE}};',
                            ],
                        ),
                    ),
                ),

                // Content Styling Section
                array(
                    'name' => 'content_styling_section',
                    'label' => esc_html__('Content Styling', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'content_bg_color',
                            'label' => esc_html__('Background Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#f8f9fa',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'content_bg_gradient',
                            'label' => esc_html__('Background Gradient', 'medicross'),
                            'type' => \Elementor\Group_Control_Background::get_type(),
                            'control_type' => 'group',
                            'types' => ['gradient'],
                            'selector' => '{{WRAPPER}} .pxl-accordion-content',
                            'description' => esc_html__('Gradient will override the background color above', 'medicross'),
                        ),
                        
                        // Active/Open State Styling
                        array(
                            'name' => 'active_state_heading',
                            'label' => esc_html__('Active/Open State Styling', 'medicross'),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'before',
                        ),
                        array(
                            'name' => 'active_content_bg_color',
                            'label' => esc_html__('Active Content Background', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-msh-accordion .pxl-accordion-item.active .pxl-accordion-content' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'active_content_border_width',
                            'label' => esc_html__('Active Content Border Width', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 10,
                                ],
                            ],
                            'default' => [
                                'size' => 0,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-msh-accordion .pxl-accordion-item.active .pxl-accordion-content' => 'border-width: {{SIZE}}px !important; border-style: solid !important;',
                            ],
                        ),
                        array(
                            'name' => 'active_content_border_color',
                            'label' => esc_html__('Active Content Border Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b82f6',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-msh-accordion .pxl-accordion-item.active .pxl-accordion-content' => 'border-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'active_content_border_radius',
                            'label' => esc_html__('Active Content Border Radius', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-msh-accordion .pxl-accordion-item.active .pxl-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important; overflow: hidden !important;',
                            ],
                        ),
                        array(
                            'name' => 'active_content_box_shadow',
                            'label' => esc_html__('Active Content Box Shadow', 'medicross'),
                            'type' => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-msh-accordion .pxl-accordion-item.active .pxl-accordion-content',
                        ),
                        
                        // Image Styling
                        array(
                            'name' => 'image_styling_heading',
                            'label' => esc_html__('Image Styling', 'medicross'),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'before',
                        ),
                        array(
                            'name' => 'image_max_width',
                            'label' => esc_html__('Image Max Width', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%'],
                            'range' => [
                                'px' => [
                                    'min' => 50,
                                    'max' => 800,
                                ],
                                '%' => [
                                    'min' => 10,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => '%',
                                'size' => 100,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content img' => 'max-width: {{SIZE}}{{UNIT}} !important; height: auto !important;',
                            ],
                        ),
                        array(
                            'name' => 'image_alignment',
                            'label' => esc_html__('Image Alignment', 'medicross'),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'left' => [
                                    'title' => esc_html__('Left', 'medicross'),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__('Center', 'medicross'),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__('Right', 'medicross'),
                                    'icon' => 'eicon-text-align-right',
                                ],
                            ],
                            'default' => 'left',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content img' => 'display: block; margin-left: {{VALUE}}; margin-right: {{VALUE}};',
                            ],
                            'selectors_dictionary' => [
                                'left' => '0',
                                'center' => 'auto',
                                'right' => 'auto 0 auto auto',
                            ],
                        ),
                        array(
                            'name' => 'image_border_radius',
                            'label' => esc_html__('Image Border Radius', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'image_box_shadow',
                            'label' => esc_html__('Image Box Shadow', 'medicross'),
                            'type' => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-accordion-content img',
                        ),
                        array(
                            'name' => 'image_margin',
                            'label' => esc_html__('Image Margin', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em'],
                            'default' => [
                                'top' => '10',
                                'right' => '0',
                                'bottom' => '10', 
                                'left' => '0',
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'image_hover_effect',
                            'label' => esc_html__('Hover Effect', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'none' => esc_html__('None', 'medicross'),
                                'scale' => esc_html__('Scale Up', 'medicross'),
                                'fade' => esc_html__('Fade', 'medicross'),
                                'lift' => esc_html__('Lift Up', 'medicross'),
                            ],
                            'default' => 'none',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content img' => 'transition: all 0.3s ease !important;',
                            ],
                        ),
                        
                        // Link Styling
                        array(
                            'name' => 'link_styling_heading',
                            'label' => esc_html__('Link Styling', 'medicross'),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'before',
                        ),
                        array(
                            'name' => 'link_color',
                            'label' => esc_html__('Link Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b82f6',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content a' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_hover_color',
                            'label' => esc_html__('Link Hover Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#1d4ed8',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content a:hover' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_decoration',
                            'label' => esc_html__('Text Decoration', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'none' => esc_html__('None', 'medicross'),
                                'underline' => esc_html__('Underline', 'medicross'),
                                'overline' => esc_html__('Overline', 'medicross'),
                                'line-through' => esc_html__('Line Through', 'medicross'),
                            ],
                            'default' => 'underline',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content a' => 'text-decoration: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_hover_decoration',
                            'label' => esc_html__('Hover Text Decoration', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'none' => esc_html__('None', 'medicross'),
                                'underline' => esc_html__('Underline', 'medicross'),
                                'overline' => esc_html__('Overline', 'medicross'),
                                'line-through' => esc_html__('Line Through', 'medicross'),
                            ],
                            'default' => 'none',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content a:hover' => 'text-decoration: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_font_weight',
                            'label' => esc_html__('Font Weight', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'normal' => esc_html__('Normal', 'medicross'),
                                'bold' => esc_html__('Bold', 'medicross'),
                                '100' => esc_html__('100', 'medicross'),
                                '200' => esc_html__('200', 'medicross'),
                                '300' => esc_html__('300', 'medicross'),
                                '400' => esc_html__('400', 'medicross'),
                                '500' => esc_html__('500', 'medicross'),
                                '600' => esc_html__('600', 'medicross'),
                                '700' => esc_html__('700', 'medicross'),
                                '800' => esc_html__('800', 'medicross'),
                                '900' => esc_html__('900', 'medicross'),
                            ],
                            'default' => 'normal',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content a' => 'font-weight: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_transition',
                            'label' => esc_html__('Hover Animation Speed', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'ms' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 50,
                                ],
                            ],
                            'default' => [
                                'unit' => 'ms',
                                'size' => 300,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content a' => 'transition: all {{SIZE}}ms ease !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_font_size',
                            'label' => esc_html__('Font Size', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', 'em', 'rem'],
                            'range' => [
                                'px' => [
                                    'min' => 10,
                                    'max' => 32,
                                    'step' => 1,
                                ],
                                'em' => [
                                    'min' => 0.5,
                                    'max' => 3,
                                    'step' => 0.1,
                                ],
                                'rem' => [
                                    'min' => 0.5,
                                    'max' => 3,
                                    'step' => 0.1,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                                'size' => 16,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content a' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                            ],
                        ),
                        
                        // Button Styling
                        array(
                            'name' => 'button_styling_heading',
                            'label' => esc_html__('Button Styling', 'medicross'),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'before',
                        ),
                        array(
                            'name' => 'button_typography',
                            'label' => esc_html__('Typography', 'medicross'),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'selector' => '{{WRAPPER}} .pxl-accordion-button',
                        ),
                        array(
                            'name' => 'button_text_color',
                            'label' => esc_html__('Text Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'button_text_hover_color',
                            'label' => esc_html__('Text Hover Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button:hover' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'button_background_color',
                            'label' => esc_html__('Background Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#007bff',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'button_hover_background_color',
                            'label' => esc_html__('Background Hover Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#0056b3',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button:hover' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'button_border',
                            'label' => esc_html__('Border', 'medicross'),
                            'type' => \Elementor\Group_Control_Border::get_type(),
                            'selector' => '{{WRAPPER}} .pxl-accordion-button',
                        ),
                        array(
                            'name' => 'button_border_radius',
                            'label' => esc_html__('Border Radius', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'default' => [
                                'top' => '5',
                                'right' => '5',
                                'bottom' => '5',
                                'left' => '5',
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'button_padding',
                            'label' => esc_html__('Padding', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'default' => [
                                'top' => '12',
                                'right' => '24',
                                'bottom' => '12',
                                'left' => '24',
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'button_margin',
                            'label' => esc_html__('Margin', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'default' => [
                                'top' => '20',
                                'right' => '0',
                                'bottom' => '0',
                                'left' => '0',
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'button_transition',
                            'label' => esc_html__('Hover Animation Speed', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'ms' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 50,
                                ],
                            ],
                            'default' => [
                                'unit' => 'ms',
                                'size' => 300,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-button' => 'transition: all {{SIZE}}ms ease !important;',
                            ],
                        ),
                        
                        array(
                            'name' => 'content_padding',
                            'label' => esc_html__('Padding', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', 'em', '%'],
                            'default' => [
                                'top' => '30',
                                'right' => '30',
                                'bottom' => '30',
                                'left' => '30',
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'content_typography',
                            'label' => esc_html__('Typography', 'medicross'),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'selector' => '{{WRAPPER}} .pxl-accordion-content',
                        ),
                        array(
                            'name' => 'content_color',
                            'label' => esc_html__('Text Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#333333',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-content' => 'color: {{VALUE}};',
                            ],
                        ),
                    ),
                ),

                // Toggle Styling Section
                array(
                    'name' => 'toggle_styling_section',
                    'label' => esc_html__('Toggle Icon Styling', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'toggle_color',
                            'label' => esc_html__('Icon Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#4a5568',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-icon-plus:before' => 'background-color: {{VALUE}} !important;',
                                '{{WRAPPER}} .pxl-accordion-icon-plus:after' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'toggle_hover_color',
                            'label' => esc_html__('Hover Icon Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b82f6',
                            'selectors' => [
                                '{{WRAPPER}} .msh-hover-enabled .pxl-accordion-item:not(.active) .pxl-accordion-header:hover .pxl-accordion-icon-plus:before' => 'background-color: {{VALUE}};',
                                '{{WRAPPER}} .msh-hover-enabled .pxl-accordion-item:not(.active) .pxl-accordion-header:hover .pxl-accordion-icon-plus:after' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'toggle_shape',
                            'label' => esc_html__('Button Shape', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'soft-square',
                            'options' => [
                                'none' => esc_html__('None (No Background)', 'medicross'),
                                'square' => esc_html__('Square', 'medicross'),
                                'soft-square' => esc_html__('Soft Square', 'medicross'),
                                'circle' => esc_html__('Circle', 'medicross'),
                            ],
                        ),
                        array(
                            'name' => 'toggle_size',
                            'label' => esc_html__('Button Size', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 24,
                                    'max' => 48,
                                ],
                            ],
                            'default' => [
                                'size' => 32,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-toggle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'toggle_bg_color',
                            'label' => esc_html__('Button Background', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'condition' => [
                                'toggle_shape!' => 'none',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-toggle' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'toggle_border_color',
                            'label' => esc_html__('Button Border Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#e0e4e7',
                            'condition' => [
                                'toggle_shape!' => 'none',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-toggle' => 'border-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'toggle_active_bg_color',
                            'label' => esc_html__('Active Background', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#3b82f6',
                            'condition' => [
                                'toggle_shape!' => 'none',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-item.active .pxl-accordion-toggle' => 'background-color: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'toggle_active_color',
                            'label' => esc_html__('Active Icon Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#ffffff',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-item.active .pxl-accordion-icon-plus:before' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'toggle_position',
                            'label' => esc_html__('Position', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'right' => esc_html__('Right', 'medicross'),
                                'left' => esc_html__('Left', 'medicross'),
                            ],
                            'default' => 'right',
                        ),
                    ),
                ),

                // Divider/Separator Section
                array(
                    'name' => 'divider_section',
                    'label' => esc_html__('Divider/Separator Style', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'show_dividers',
                            'label' => esc_html__('Show Dividers Between Items', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'no',
                            'return_value' => 'yes',
                            'description' => esc_html__('Add visual separators between accordion items', 'medicross'),
                        ),
                        array(
                            'name' => 'divider_style',
                            'label' => esc_html__('Divider Style', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'solid',
                            'options' => [
                                'solid' => esc_html__('Solid', 'medicross'),
                                'dashed' => esc_html__('Dashed', 'medicross'),
                                'dotted' => esc_html__('Dotted', 'medicross'),
                                'double' => esc_html__('Double', 'medicross'),
                                'groove' => esc_html__('Groove', 'medicross'),
                                'ridge' => esc_html__('Ridge', 'medicross'),
                            ],
                            'condition' => [
                                'show_dividers' => 'yes',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-divider' => 'border-bottom-style: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'divider_color',
                            'label' => esc_html__('Divider Color', 'medicross'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#e9ecef',
                            'condition' => [
                                'show_dividers' => 'yes',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-divider' => 'border-bottom-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'divider_width',
                            'label' => esc_html__('Divider Thickness', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'default' => [
                                'size' => 1,
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 1,
                                    'max' => 10,
                                ],
                            ],
                            'condition' => [
                                'show_dividers' => 'yes',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'divider_spacing',
                            'label' => esc_html__('Divider Spacing', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'default' => [
                                'size' => 20,
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'condition' => [
                                'show_dividers' => 'yes',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-divider' => 'margin: {{SIZE}}{{UNIT}} 0;',
                            ],
                        ),
                    ),
                ),

                // Spacing Section
                array(
                    'name' => 'spacing_section',
                    'label' => esc_html__('Spacing', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'items_gap',
                            'label' => esc_html__('Items Gap', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 50,
                                ],
                            ],
                            'default' => [
                                'size' => 0,
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'border_radius',
                            'label' => esc_html__('Border Radius', 'medicross'),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => ['px', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    get_template_directory() . '/elements/templates/pxl_msh_accordion/'
);

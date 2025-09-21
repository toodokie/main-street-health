<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_banner_box',
        'title' => esc_html__('Case Call Help', 'medicross'),
        'icon' => 'eicon-posts-ticker',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_layout',
                    'label' => esc_html__('Layout', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'medicross' ),
                            'type' => 'layoutcontrol',
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_banner_box/layout1.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'title_banner',
                            'label' => esc_html__('Title', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'sub_title_number',
                            'label' => esc_html__('SubTitle Phone Number', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'phone_number',
                            'label' => esc_html__('Phone Number', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'phone_link',
                            'label' => esc_html__('Phone Link', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::URL,
                            'description' => 'tel:Phone Number'
                        ),
                        array(
                            'name' => 'description',
                            'label' => esc_html__('Description', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                        ),
                        array(
                            'name' => 'button_text',
                            'label' => esc_html__('Button Text', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                        array(
                            'name' => 'item_link',
                            'label' => esc_html__('Item Link', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::URL,
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('General', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__( 'Style', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-1' => 'Style 1',
                                'style-2' => 'Style 2',
                            ],
                            'default' => 'style-1',
                        ),
                        array(
                            'name' => 'p_image',
                            'label' => esc_html__('Background Image Box Phone', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'ct_hv-r',
                            'label' => esc_html__('Content Hover Space', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px','%' ],
                            'range' => [
                                'px' => [
                                    'min' => -300,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner .hover-content' => 'transform: translatex({{SIZE}}{{UNIT}});',
                            ],
                        ),
                        array(
                            'name' => 'color_p',
                            'label' => esc_html__('Primary Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner .top-content , {{WRAPPER}} .pxl-banner .wrap--phone' => 'background-color: {{VALUE}} !important;',
                                '{{WRAPPER}} .pxl-banner .btn' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'color_s',
                            'label' => esc_html__('Secondary Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner  .hover-content' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'color_bdb',
                            'label' => esc_html__('Border Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner  .hover-content' => 'border-color: {{VALUE}} !important;',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_title',
                    'label' => esc_html__('Title Phone', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'color_tl',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner .subtitle-phone' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'tile_typography',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-banner .subtitle-phone',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_phone',
                    'label' => esc_html__('Phone', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'color_phone',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner .phone-number' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'phone_tp',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-banner .phone-number',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_des',
                    'label' => esc_html__('Description', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'color_desc',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner .desc' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'desc_tp',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-banner .desc',
                        ),
                    ),
                ),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
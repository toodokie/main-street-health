<?php
// Register Button Widget
pxl_add_custom_widget(
    array(
        'name' => 'icon_cart',
        'title' => esc_html__('Case Shop Cart', 'medicross' ),
        'icon' => 'eicon-cart-medium',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Source Settings', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-default' => 'Default',
                                'style-box' => 'Box',
                            ],
                            'default' => 'style-default',
                        ),
                        array(
                            'name' => 'icon_image_type',
                            'label' => esc_html__('Icon Image Type', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'img' => 'Image',
                                'ic' => 'Icon',
                            ],
                            'default' => 'img',
                        ),
                        array(
                            'name' => 'pxl_icon',
                            'label' => esc_html__('Icon', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'condition' => [
                                'icon_image_type' => ['ic'],
                            ],
                        ),
                        array(
                            'name' => 'image',
                            'label' => esc_html__( 'Icon Image', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'condition' => [
                                'icon_image_type' => ['img'],
                            ],
                        ),
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Icon Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-cart-sidebar-button svg' => 'fill: {{VALUE}};',
                            ],
                        ),

                        array(
                            'name' => 'bd_icon_color',
                            'label' => esc_html__('Border Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button ' => 'border-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_color_hover',
                            'label' => esc_html__('Icon Color Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button:hover i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-cart-sidebar-button:hover svg' => 'fill: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'size',
                            'label' => esc_html__('Icon Size', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .pxl-cart-sidebar-button svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'box_color',
                            'label' => esc_html__('Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style-box'],
                            ],
                        ),
                        array(
                            'name' => 'box_color_hv',
                            'label' => esc_html__('Box Color Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style-box'],
                            ],
                        ),
                        array(
                            'name' => 'box_height',
                            'label' => esc_html__('Box Height', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'condition' => [
                                'style' => ['style-box'],
                            ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'box_width',
                            'label' => esc_html__('Box Width', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-cart-sidebar-button' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style-box'],
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
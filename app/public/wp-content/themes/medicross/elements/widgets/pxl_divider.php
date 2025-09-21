<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_divider',
        'title' => esc_html__('Case Divider', 'medicross' ),
        'icon' => 'eicon-divider',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'divider_color',
                            'label' => esc_html__('Background Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-el-divider' => 'background-color: {{VALUE}};',
                            ],
                        ),

                        array(
                            'name' => 'dot_color',
                            'label' => esc_html__('column Hover Background Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '.elementor-column:hover .pxl-el-divider' => 'background-color: {{VALUE}} !important;',
                            ],
                        ),
                        
                        array(
                            'name' => 'divider_width',
                            'label' => esc_html__('Width', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 10000,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-el-divider' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'divider_height',
                            'label' => esc_html__('Height', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 10000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-el-divider' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),
                medicross_widget_animation_settings(),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
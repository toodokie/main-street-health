<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_text_slip',
        'title' => esc_html__('BR Title Slip', 'medicross' ),
        'icon' => 'eicon-heading',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'items',
                            'label' => esc_html__('Content', 'medicross'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'text',
                                    'label' => esc_html__('Text', 'medicross' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'pxl_icon',
                                    'label' => esc_html__('Icon', 'medicross' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                ),
                            ),
                            'title_field' => '{{{ text }}}',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_text',
                    'label' => esc_html__('Text', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'text_tag',
                            'label' => esc_html__('HTML Tag', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'h1' => 'H1',
                                'h2' => 'H2',
                                'h3' => 'H3',
                                'h4' => 'H4',
                                'h5' => 'H5',
                                'h6' => 'H6',
                                'div' => 'div',
                                'span' => 'span',
                                'p' => 'p',
                            ],
                            'default' => 'h3',
                        ),
                        array(
                            'name' => 'background_color',
                            'label' => esc_html__('Background Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-slip .pxl-item--container' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'text_color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-slip .pxl-item--text' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-text-slip .pxl-item--text svg path' => 'fill: {{VALUE}};',
                                '{{WRAPPER}} .pxl-text-slip .pxl-item--text i' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'text_typography',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-text-slip .pxl-item--text',
                        ),
                        array(
                            'name' => 'text_effect',
                            'label' => esc_html__('Effect', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'no-effect' => 'None',
                                'pxl-slide-to-left' => 'Slide Right To Left',
                                'pxl-slide-to-right' => 'Slide Left To Right',
                            ],
                            'default' => 'no-effect',
                        ),
                        array(
                            'name' => 'effect_speed',
                            'label' => esc_html__('Effect Speed', 'medicross'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'description' => 'Default: 10000 - Unit: ms',
                            'condition' => [
                                'text_effect' => ['pxl-slide-to-left', 'pxl-slide-to-right'],
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
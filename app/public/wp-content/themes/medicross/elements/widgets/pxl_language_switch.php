<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_language_switch',
        'title' => esc_html__('Case Language Switch', 'medicross'),
        'icon' => 'eicon-kit-parts',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'language',
                            'label' => esc_html__('Language', 'medicross'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'name',
                                    'label' => esc_html__('Name', 'medicross' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'rows' => 10,
                                    'show_label' => false,
                                ),
                                array(
                                    'name' => 'link',
                                    'label' => esc_html__('Link', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::URL,
                                    'label_block' => true,
                                ),
                            ),
                            'title_field' => '{{{ name }}}',
                        ),
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'default',
                            'options' => [
                                'default' => esc_html__('Default', 'medicross' ),
                                'style-2' => esc_html__('Style 2', 'medicross' ),
                                'style-3' => esc_html__('Style 3', 'medicross' ),
                                'style-4' => esc_html__('Style 4', 'medicross' ),
                            ],
                        ),
                        array(
                            'name' => 'color',
                            'label' => esc_html__('Color Text', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .language-first' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'tt_typography',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .language-first',
                        ),
                    ),
                ),
                medicross_widget_animation_settings(),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
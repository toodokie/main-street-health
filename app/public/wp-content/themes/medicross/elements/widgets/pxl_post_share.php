<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_post_share',
        'title' => esc_html__( 'BR Share Post', 'medicross' ),
        'icon' => 'eicon-tabs',
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_layout',
                    'label' => esc_html__('Content', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'share_fb',
                            'label' => esc_html__('Facebook', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'share_tw',
                            'label' => esc_html__('Twitter / X', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'share_linked',
                            'label' => esc_html__('Linkedin', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                        array(
                            'name' => 'share_skype',
                            'label' => esc_html__('Pinterest', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_tm',
                    'label' => esc_html__('Style', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'typography',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-post-share a',
                        ),
                        array(
                            'name' => 'color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-post-share a' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'color_hv',
                            'label' => esc_html__('Color Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-post-share a:hover' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                    ),
                ),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
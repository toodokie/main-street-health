<?php
// Register Text Editor
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
pxl_add_custom_widget(
    array(
        'name' => 'pxl_text_curve',
        'title' => esc_html__('Case Text Curve', 'medicross'),
        'icon' => 'eicon-text',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Text Editor', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'text',
                            'label' => esc_html__('Text', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_text',
                    'label' => esc_html__( 'Text', 'medicross' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'text_color',
                            'label' => esc_html__( 'Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-curve textPath' => 'fill: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'text_typography',
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'label' => esc_html__( 'Typography', 'medicross' ),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-text-curve textPath',
                        ),
                        array(
                            'name' => 'custom_font',
                            'label' => esc_html__('Custom Font Family', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => 'Default',
                                'ft-gt' => 'GT Walsheim Pro',
                            ],
                            'default' => '',
                        ),
                    ),
                ),
                medicross_widget_animation_settings(),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
<?php
// Register Text Editor
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
pxl_add_custom_widget(
    array(
        'name' => 'pxl_text_editor',
        'title' => esc_html__('Case Text Editor', 'medicross'),
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
                            'name' => 'text_ed',
                            'label' => '',
                            'type' => Controls_Manager::WYSIWYG,
                            'default' => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'medicross' ),
                            'description' => 'Create Highlight text width shortcode: [pxl_highlight text="Text Demo"]',
                        ),
                        array(
                          'name' => 'align',
                            'label' => esc_html__( 'Alignment', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'medicross' ),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'medicross' ),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'medicross' ),
                                    'icon' => 'eicon-text-align-right',
                                ],
                                'justify' => [
                                    'title' => esc_html__( 'Justified', 'medicross' ),
                                    'icon' => 'eicon-text-align-justify',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-editor' => 'text-align: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 't_width',
                            'label' => esc_html__('Max Width', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px', '%' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-editor .pxl-item--inner' => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
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
                                '{{WRAPPER}} .pxl-text-editor , {{WRAPPER}} .pxl-text-editor p' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'text_typography',
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'label' => esc_html__( 'Typography', 'medicross' ),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-text-editor , {{WRAPPER}} .pxl-text-editor p',
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
                array(
                    'name' => 'section_style_quote',
                    'label' => esc_html__( 'Block Quote', 'medicross' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'textb_color',
                            'label' => esc_html__( 'Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-editor blockquote, {{WRAPPER}} .pxl-text-editor blockquote p' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'textb_typography',
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'label' => esc_html__( 'Typography', 'medicross' ),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-text-editor blockquote, {{WRAPPER}} .pxl-text-editor blockquote p',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_link',
                    'label' => esc_html__( 'Link', 'medicross' ),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style_hv',
                            'label' => esc_html__('Style Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'default',
                            'options' => [
                                'default' => esc_html__('Default', 'medicross' ),
                                'underline' => esc_html__('Underline', 'medicross' ),
                            ],
                        ),
                        array(
                            'name' => 'link_color',
                            'label' => esc_html__( 'Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-editor a' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'link_color_hover',
                            'label' => esc_html__( 'Color Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-editor a:hover' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-text-editor.underline a:hover' => 'text-decoration: underline {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'link_typography',
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'label' => esc_html__( 'Typography', 'medicross' ),
                            'selector' => '{{WRAPPER}} .pxl-text-editor a',
                            'control_type' => 'group',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_title_highlight',
                    'label' => esc_html__('Highlight', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'highlight_color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-editor .pxl-text--highlight' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'highlight_box_color',
                            'label' => esc_html__('Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-editor .pxl-text--highlight' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'highlight_typography',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-text-editor .pxl-text--highlight',
                        ),
                    ),
                ),
                medicross_widget_animation_settings(),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
<?php
$templates = medicross_get_templates_option('tab', []) ;
pxl_add_custom_widget(
    array(
        'name' => 'pxl_tabs',
        'title' => esc_html__( 'BR Tabs', 'medicross' ),
        'icon' => 'eicon-tabs',
        'categories' => array('pxltheme-core'),
        'scripts' => array(
            'medicross-tabs'
        ),
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
                                    'image' => get_template_directory_uri() . '/elements/templates/pxl_tabs/layout-image/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/templates/pxl_tabs/layout-image/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/templates/pxl_tabs/layout-image/layout3.jpg'
                                ],
                                '4' => [
                                    'label' => esc_html__('Layout 4', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/templates/pxl_tabs/layout-image/layout4.jpg'
                                ],
                                '5' => [
                                    'label' => esc_html__('Layout 5', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/templates/pxl_tabs/layout-image/layout5.jpg'
                                ],
                                '6' => [
                                    'label' => esc_html__('Layout 6', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/templates/pxl_tabs/layout-image/layout6.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'tab_content',
                    'label' => esc_html__( 'Tabs', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'title_box',
                            'label' => esc_html__('Title Box', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'default' => esc_html__('Text', 'medicross'),
                            'condition' => ['layout' => '2'], 
                        ),
                        array(
                            'name' => 'tab_active',
                            'label' => esc_html__( 'Active Tab', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 1,
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'tabs',
                            'label' => esc_html__( 'Content', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'pxl_icon_tab',
                                    'label' => esc_html__('Icon', 'medicross' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                ),
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__( 'Title', 'medicross' ),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'content_type',
                                    'label' => esc_html__('Content Type', 'medicross'),
                                    'type' => 'select',
                                    'options' => [
                                        'df' => esc_html__( 'Default', 'medicross' ),
                                        'template' => esc_html__( 'From Template Builder', 'medicross' )
                                    ],
                                    'default' => 'df' 
                                ),
                                array(
                                    'name' => 'desc',
                                    'label' => esc_html__( 'Content', 'medicross' ),
                                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                                    'condition' => ['content_type' => 'df'] 
                                ),
                                array(
                                    'name' => 'content_template',
                                    'label' => esc_html__('Select Templates', 'medicross'),
                                    'type' => 'select',
                                    'options' => $templates,
                                    'default' => 'df',
                                    'description' => 'Add new tab template: "<a href="' . esc_url( admin_url( 'edit.php?post_type=pxl-template' ) ) . '" target="_blank">Click Here</a>"',
                                    'condition' => ['content_type' => 'template'] 
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_title_b',
                    'label' => esc_html__('Title Box', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'condition' => ['layout' => '2'], 
                    'controls' => array_merge(
                        array(
                            array(
                                'name' => 'title_b_color',
                                'label' => esc_html__(' Color', 'medicross' ),
                                'type' => \Elementor\Controls_Manager::COLOR,
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-tabs .title-box' => 'color: {{VALUE}};',
                                ],
                            ),
                            array(
                                'name' => 'titleb_typography',
                                'label' => esc_html__('Typography', 'medicross' ),
                                'type' => \Elementor\Group_Control_Typography::get_type(),
                                'control_type' => 'group',
                                'selector' => '{{WRAPPER}} .pxl-tabs .title-box',
                            ),
                        )
                    ),
                ),
                array(
                    'name' => 'tab_style',
                    'label' => esc_html__( 'Style', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-default' => 'Default',
                                'style-2' => 'Style 2',
                            ],
                            'default' => 'style-default',
                        ),
                        array(
                            'name' => 'right_space',
                            'label' => esc_html__('Space Right Content', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-tabs--content ' => 'right: {{SIZE}}{{UNIT}} ;',
                            ],
                        ),
                        array(
                            'name' => 'top_space',
                            'label' => esc_html__('Space Top Content', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-tabs--content ' => 'top: {{SIZE}}{{UNIT}} ;',
                            ],
                        ),
                        array(
                            'name' => 'tab_effect',
                            'label' => esc_html__('Effect', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'tab-effect-slide' => 'Slide',
                                'tab-effect-fade' => 'Fade',
                            ],
                            'default' => 'tab-effect-slide',
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-tabs .pxl-tabs--title > .pxl-item--title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_active_color',
                            'label' => esc_html__('Title Active Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-tabs .pxl-tabs--title > .pxl-item--title.active' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_box_color_w',
                            'label' => esc_html__('Title Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-tabs .pxl-tabs--title > .pxl-tabs--title' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'btn_color',
                            'label' => esc_html__('Background Button Color Active', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-tabs .pxl-tabs--title > .pxl-item--title.active' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-tabs .pxl-tabs--title > .pxl-item--title',
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'content_color',
                            'label' => esc_html__('Content Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-tabs .pxl-item--content' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'content_typography',
                            'label' => esc_html__('Content Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-tabs .pxl-item--content',
                        ),
                    ),
),
medicross_widget_animation_settings(),
),
),
),
medicross_get_class_widget_path()
);
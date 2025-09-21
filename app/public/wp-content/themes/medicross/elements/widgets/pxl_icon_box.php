<?php
// Register Icon Box Widget
pxl_add_custom_widget(
    array(
        'name' => 'pxl_icon_box',
        'title' => esc_html__('Case Icon Box', 'medicross' ),
        'icon' => 'eicon-icon-box',
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
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_icon_box/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_icon_box/layout2.jpg'
                                ],
                                '3' => [
                                    'label' => esc_html__('Layout 3', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_icon_box/layout3.jpg'
                                ],
                                '4' => [
                                    'label' => esc_html__('Layout 4', 'medicross' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_icon_box/layout4.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'bg_image',
                            'label' => esc_html__( 'Background Image', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'condition' => [
                                'layout' => ['2','4'],
                            ],
                        ),
                        array(
                            'name' => 'number',
                            'label' => esc_html__('Number', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'condition' => [
                                'layout' => '5',
                            ],
                        ),
                        array(
                            'name' => 'title',
                            'label' => esc_html__('Title', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'desc',
                            'label' => esc_html__('Description', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'rows' => 10,
                            'show_label' => false,
                            'condition' => [
                                'layout' => ['1','3','4'],
                            ],
                        ),

                        array(
                            'name' => 'desc2',
                            'label' => esc_html__('Description 2', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXTAREA,
                            'rows' => 10,
                            'show_label' => false,
                            'condition' => [
                                'layout' => '6',
                            ],
                        ),

                        array(
                            'name' => 'lists',
                            'label' => esc_html__('List', 'medicross'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'condition' => [
                                'layout' => '6',
                            ],
                            'controls' => array(
                                array(
                                    'name' => 'content',
                                    'label' => esc_html__('Content', 'medicross' ),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'rows' => 10,
                                    'show_label' => false,
                                ),
                            ),
                            'title_field' => '{{{ content }}}',
                        ),
                        array(
                            'name' => 'button_text',
                            'label' => esc_html__('Button Text', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => esc_html__('View More', 'medicross'),
                            'condition' => [
                                'layout' => '2',
                            ],
                        ),
                        array(
                            'name' => 'item_link',
                            'label' => esc_html__('Item Link', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::URL,
                            'condition' => [
                                'layout' => ['2','1','4'],
                            ],
                        ),
                        array(
                            'name' => 'icon_type',
                            'label' => esc_html__('Icon Type', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'icon' => 'Icon',
                                'image' => 'Image',
                            ],
                            'default' => 'icon',
                        ),
                        array(
                            'name' => 'pxl_icon',
                            'label' => esc_html__('Icon', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'condition' => [
                                'icon_type' => 'icon',
                            ],
                        ),
                        array(
                            'name' => 'icon_image',
                            'label' => esc_html__( 'Icon Image', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'condition' => [
                                'icon_type' => 'image',
                            ],
                        ),
                        array(
                            'name' => 'wg_max_width',
                            'label' => esc_html__('Widget Max Width', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box' => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'wg_max_height',
                            'label' => esc_html__('Widget Max Height', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'condition' => [
                                'layout' => '3',
                            ],
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--inner' => 'max-height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_general',
                    'label' => esc_html__('General', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'bg_color',
                            'label' => esc_html__('Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'item_padding',
                            'label' => esc_html__('Box Padding', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                            'control_type' => 'responsive',
                        ),
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-1' => 'Style 1',
                                'style-2' => 'Style 2 ',
                            ],
                            'default' => 'style-1',
                            'condition' => [
                                'layout' => ['1','2'],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_title',
                    'label' => esc_html__('Title', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'title_tag',
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
                            'default' => 'h5',
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--title,{{WRAPPER}} .pxl-icon-box .pxl-item--title a' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-icon-box .pxl-item--title,{{WRAPPER}} .pxl-icon-box .pxl-item--title a',
                        ),
                        array(
                            'name' => 'title_top_spacer',
                            'label' => esc_html__('Top Spacer', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--title' => 'margin-top: {{SIZE}}{{UNIT}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'title_bottom_spacer',
                            'label' => esc_html__('Bottom Spacer', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--title' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_desc',
                    'label' => esc_html__('Description', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'desc_color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--description' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'desc_typography',
                            'label' => esc_html__('Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-icon-box .pxl-item--description',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_icon',
                    'label' => esc_html__('Icon', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'animate_hover',
                            'label' => esc_html__('Animation Icon', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'ani1',
                            'options' => [
                                'ani1' => esc_html__('Style 1', 'medicross' ),
                                'ani2' => esc_html__('Style 2', 'medicross' ),
                                'ani3' => esc_html__('Off', 'medicross' ),
                            ],
                            'condition' => [
                                'layout' => '1',
                            ],
                        ),
                        array(
                            'name' => 'roate_icon',
                            'label' => esc_html__('Icon Rotate', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'range' => [
                                'deg' => [
                                    'min' => -360,
                                    'max' => 360,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-item--icon i ,{{WRAPPER}} .pxl-item--icon svg ,{{WRAPPER}} .pxl-item--icon img' => 'transform:rotate({{SIZE}}deg);',
                            ],
                        ),
                        array(
                            'name' => 'space_pd',
                            'label' => esc_html__('Icon Space', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                            'control_type' => 'responsive',
                        ),
                        array(
                            'name' => 'rdspace_pd',
                            'label' => esc_html__('Border Radius', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-item--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],

                            'condition' => [
                                'style' => 'style-2',
                            ],
                            'control_type' => 'responsive',
                        ),
                        array(
                            'name' => 'style_icon_cl',
                            'label' => esc_html__('Style Icon', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'ic' => 'Icon',
                                'svg' => 'Svg',
                            ],
                            'default' => 'ic',
                        ),
                        array(
                            'name' => 'style_svg_cl',
                            'label' => esc_html__('Style SVG', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'fill' => 'Fill',
                                'stroke' => 'Stroke',
                            ],
                            'condition' => [
                                'style_icon_cl' => 'svg',
                            ],
                        ),
                        array(
                            'name' => 'bgicolor',
                            'label' => esc_html__('Background Icon Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon' => 'background-color: {{VALUE}};border-color:{{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'bgicolor_hv',
                            'label' => esc_html__('Background Icon Color (Hover)', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box:hover .pxl-item--icon' => 'background-color: {{VALUE}};border-color:{{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon i' => 'color: {{VALUE}};text-fill-color: {{VALUE}};-webkit-text-fill-color: {{VALUE}};background-image: none;',
                            ],
                            'condition' => [
                                'icon_type' => 'icon',
                                'style_icon_cl' => 'ic',
                            ],
                        ),
                        array(
                            'name' => 'icon_color_hover',
                            'label' => esc_html__('Color Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box:hover .pxl-item--icon i' => 'color: {{VALUE}};text-fill-color: {{VALUE}};-webkit-text-fill-color: {{VALUE}};background-image: none;',
                            ],
                            'condition' => [
                                'icon_type' => 'icon',
                                'style_icon_cl' => 'ic',
                            ],
                        ),
                        array(
                            'name' => 'icon_fill_color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon svg path' => 'fill: {{VALUE}} ;',
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon svg polygon' => 'fill: {{VALUE}} ;',
                            ],
                            'condition' => [
                                'style_svg_cl' => 'fill',
                                'style_icon_cl' => 'svg',
                            ],
                        ),
                        array(
                            'name' => 'icon_fill_color_hv',
                            'label' => esc_html__('Color Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box:hover .pxl-item--icon svg path' => 'fill: {{VALUE}} ;',
                                '{{WRAPPER}} .pxl-icon-box:hover .pxl-item--icon svg polygon' => 'fill: {{VALUE}} ;',
                            ],
                            'condition' => [
                                'style_svg_cl' => 'fill',
                                'style_icon_cl' => 'svg',
                            ],
                        ),
                        array(
                            'name' => 'icon_stroke_color',
                            'label' => esc_html__('Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon svg' => 'stroke: {{VALUE}} ;',
                            ],
                            'condition' => [
                                'style_svg_cl' => 'stroke',
                                'style_icon_cl' => 'svg',
                            ],
                        ),
                        array(
                            'name' => 'icon_stroke_color_hv',
                            'label' => esc_html__('Color Hover', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box:hover .pxl-item--icon svg' => 'stroke: {{VALUE}} ;',
                            ],
                            'condition' => [
                                'style_svg_cl' => 'stroke',
                                'style_icon_cl' => 'svg',
                            ],
                        ),
                        array(
                            'name' => 'icon_font_size',
                            'label' => esc_html__('Size', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'icon_type' => 'icon',
                            ],
                        ),

                        array(
                            'name' => 'space_r',
                            'label' => esc_html__('Space Right ', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--inner' => 'column-gap: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                                'style' => ['style-2'],
                            ],
                        ),

                        array(
                            'name' => 'space_t',
                            'label' => esc_html__('Space Top ', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--inner .pxl-item--icon i,{{WRAPPER}} .pxl-icon-box .pxl-item--inner .pxl-item--icon svg,{{WRAPPER}} .pxl-icon-box .pxl-item--inner .pxl-item--icon img' => 'margin-top: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'layout' => ['1'],
                                'style' => ['style-2'],
                            ],
                        ),
                        array(
                            'name' => 'box_wh',
                            'label' => esc_html__('Box Width/Height', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon' => 'width: {{SIZE}}{{UNIT}};min-width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
                            ],
                            'condition' => [
                                'layout' => ['1','8'],
                                'style' => ['style-2'],
                            ],
                        ),
                        array(
                            'name' => 'icon_box_color',
                            'label' => esc_html__('Box Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'layout' => '1',
                            ],
                        ),
                        array(
                            'name' => 'icon_box_min_width',
                            'label' => esc_html__('Box Min Width', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon' => 'min-width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'icon_type' => 'image',
                            ],
                        ),
                        array(
                            'name' => 'icon_img_max_height',
                            'label' => esc_html__('Image Max Height', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-icon-box .pxl-item--icon img' => 'max-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'icon_type' => 'image',
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
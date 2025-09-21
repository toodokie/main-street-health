<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_gallery_scroll',
        'title' => esc_html__('Case Gallery Scroll', 'medicross'),
        'icon' => 'eicon-image-before-after',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(        
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'gallery',
                            'label' => esc_html__('Gallery', 'medicross'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'imgs',
                                    'label' => esc_html__('Images', 'medicross'),
                                    'type' => \Elementor\Controls_Manager::GALLERY,
                                    'label_block' => true,
                                ),
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                        array(
                            'name' => 'img_size',
                            'label' => esc_html__('Image Size', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Default: 370x300 (Width x Height)).',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_title',
                    'label' => esc_html__('Title', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-gallery-scroll .pxl-item--title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'medicross' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-gallery-scroll .pxl-item--title',
                        ),
                    ),
                ),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
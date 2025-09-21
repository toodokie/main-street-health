<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_section_scale',
        'title' => esc_html__('Case Section Scale', 'medicross' ),
        'icon' => 'eicon-animation',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'bg_type',
                            'label' => esc_html__('Background Type', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'img' => 'Image',
                                'video' => 'Video',
                            ],
                            'default' => 'img',
                        ),
                        array(
                            'name' => 'bg_img',
                            'label' => esc_html__('Background Image', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                            'condition' => [
                                'bg_type' => ['img'],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-section-scale .pxl-sticky-parallax' => 'background-image: url( {{URL}} );',
                            ],  
                        ),
                        array(
                            'name' => 'bg_img_position',
                            'label' => esc_html__( 'Background Image Position', 'medicross' ),
                            'type'         => \Elementor\Controls_Manager::SELECT,
                            'options'      => array(
                                ''              => esc_html__( 'Default', 'medicross' ),
                                'center center' => esc_html__( 'Center Center', 'medicross' ),
                                'center left'   => esc_html__( 'Center Left', 'medicross' ),
                                'center right'  => esc_html__( 'Center Right', 'medicross' ),
                                'top center'    => esc_html__( 'Top Center', 'medicross' ),
                                'top left'      => esc_html__( 'Top Left', 'medicross' ),
                                'top right'     => esc_html__( 'Top Right', 'medicross' ),
                                'bottom center' => esc_html__( 'Bottom Center', 'medicross' ),
                                'bottom left'   => esc_html__( 'Bottom Left', 'medicross' ),
                                'bottom right'  => esc_html__( 'Bottom Right', 'medicross' ),
                                'initial'       =>  esc_html__( 'Custom', 'medicross' ),
                            ),
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-section-scale .pxl-sticky-parallax' => 'background-position: {{VALUE}};',
                            ],
                            'condition' => [
                                'bg_type' => 'img',
                                'bg_img[url]!' => '',
                            ]        
                        ),
                        array(
                            'name' => 'bg_img_size',
                            'label' => esc_html__( 'Background Image Size', 'medicross' ),
                            'type'         => \Elementor\Controls_Manager::SELECT,
                            'hide_in_inner' => true,
                            'options'      => array(
                                ''              => esc_html__( 'Default', 'medicross' ),
                                'auto' => esc_html__( 'Auto', 'medicross' ),
                                'cover'   => esc_html__( 'Cover', 'medicross' ),
                                'contain'  => esc_html__( 'Contain', 'medicross' ),
                            ),
                            'default'      => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-section-scale .pxl-sticky-parallax' => 'background-size: {{VALUE}};',
                            ],
                            'condition' => [
                                'bg_type' => 'img',
                                'bg_img[url]!' => '',
                            ]       
                        ),
                        array(
                            'name' => 'bg_video',
                            'label' => esc_html__('Video Link', 'medicross'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'bg_type' => ['video'],
                            ],
                            'description' => 'Video file (mp4 is recommended).'
                        ),
                        array(
                            'name' => 'overlay_color',
                            'label' => esc_html__('Overlay Color', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-section-scale .pxl-section-overlay' => 'background-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'shape_1',
                            'label' => esc_html__('Shape 1', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'shape_2',
                            'label' => esc_html__('Shape 2', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                    ),
                ),
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
<?php 
/* Start Section */
add_action( 'elementor/element/section/section_structure/after_section_end', 'medicross_add_custom_section_controls' ); 
// add_action( 'elementor/element/section/section_structure/after_section_end', 'medicross_add_custom_section_overlay_color' ); 
// add_action( 'elementor/element/section/section_structure/after_section_end', 'medicross_add_custom_section_overlay_img' ); 
// add_action( 'elementor/element/section/section_structure/after_section_end', 'medicross_add_custom_section_divider' ); 
// add_action( 'elementor/element/section/section_structure/after_section_end', 'medicross_add_custom_section_particles' );
// add_action( 'elementor/element/section/section_structure/after_section_end', 'medicross_add_custom_section_effect_image' );
function medicross_add_custom_section_controls( \Elementor\Element_Base $element) {
     
    $element->start_controls_section(
        'section_pxl',
        [
            'label' => esc_html__( 'Medicross General Settings', 'medicross' ),
            'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
        ]
    );

    if ( get_post_type( get_the_ID()) === 'pxl-template' && get_post_meta( get_the_ID(), 'template_type', true ) === 'header-mobile') {
          
        $element->add_control(
            'pxl_header_type',
            [
                'label' => esc_html__( 'Header Type', 'medicross' ),
                'type'  => \Elementor\Controls_Manager::SELECT,
                'hide_in_inner' => true,
                'options'      => array(
                    ''          => esc_html__( 'Select Type', 'medicross' ),
                    'sticky'      => esc_html__( 'Header Sticky', 'medicross' ),
                ),
                'default'      => '',
            ]
        );
    }

    $element->add_control(
        'row_scroll_fixed',
        [
            'label'   => esc_html__( 'Column Fixed', 'medicross' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                'none'        => esc_html__( 'No', 'medicross' ),
                'fixed'   => esc_html__( 'Yes', 'medicross' ),
            ),
            'prefix_class' => 'pxl-row-scroll-',
            'default'      => 'none',      
        ]
    );

    $element->add_control(
        'row_zoom_point',
        [
            'label'   => esc_html__( 'Zoom Point', 'medicross' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'description' => 'Background color displayed on scroll.',
            'options' => array(
                'false'        => esc_html__( 'No', 'medicross' ),
                'true'   => esc_html__( 'Yes', 'medicross' ),
            ),
            'default'      => 'false',
            'prefix_class' => 'pxl-zoom-point-',      
        ]
    );

    $element->add_control(
        'row_zoom_point_color',
        [
            'label' => esc_html__('Zoom Point - Color', 'medicross' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pxl-zoom-point .pxl-item--overlay' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'row_zoom_point' => ['true'],
            ],
        ]
    );

    $element->add_control(
        'row_zoom_point_img',
        [
            'label' => esc_html__( 'Zoom Point - Image', 'medicross' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'hide_in_inner' => true,
            'condition' => [
                'row_zoom_point' => ['true'],
            ],
        ]
    );

    $element->add_control(
        'pxl_section_overflow',
        [
            'label'   => esc_html__( 'Overflow', 'medicross' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                'visible'        => esc_html__( 'Visible', 'medicross' ),
                'hidden'   => esc_html__( 'Hidden', 'medicross' ),
            ),
            'default'      => 'visible', 
            'separator' => 'after',
            'prefix_class' => 'pxl-section-overflow-'     
        ]
    );


    $element->add_control(
        'pxl_parallax_bg_img',
        [
            'label' => esc_html__( 'Parallax Background Image', 'medicross' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'hide_in_inner' => true,
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'background-image: url( {{URL}} );',
            ],
        ]
    );
    $element->add_responsive_control(
        'pxl_parallax_bg_position',
        [
            'label' => esc_html__( 'Background Position', 'medicross' ),
            'type'         => \Elementor\Controls_Manager::SELECT,
            'hide_in_inner' => true,
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
            'default'      => '',
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'background-position: {{VALUE}};',
            ],
            'condition' => [
                'pxl_parallax_bg_img[url]!' => ''
            ]        
        ]
    );
     
    $element->add_responsive_control(
        'pxl_parallax_bg_pos_custom_x',
        [
            'label' => esc_html__( 'X Position', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,  
            'hide_in_inner' => true,
            'size_units' => [ 'px', 'em', '%', 'vw' ],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -800,
                    'max' => 800,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'background-position: {{SIZE}}{{UNIT}} {{pxl_parallax_bg_pos_custom_y.SIZE}}{{pxl_parallax_bg_pos_custom_y.UNIT}}',
            ],
            'condition' => [
                'pxl_parallax_bg_position' => [ 'initial' ],
                'pxl_parallax_bg_img[url]!' => '',
            ],
        ]
    );
    $element->add_responsive_control(
        'pxl_parallax_bg_pos_custom_y',
        [
            'label' => esc_html__( 'Y Position', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,  
            'hide_in_inner' => true,
            'size_units' => [ 'px', 'em', '%', 'vw' ],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -800,
                    'max' => 800,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'background-position: {{pxl_parallax_bg_pos_custom_x.SIZE}}{{pxl_parallax_bg_pos_custom_x.UNIT}} {{SIZE}}{{UNIT}}',
            ],

            'condition' => [
                'pxl_parallax_bg_position' => [ 'initial' ],
                'pxl_parallax_bg_img[url]!' => '',
            ],
        ]
    );
    $element->add_responsive_control(
        'pxl_parallax_bg_size',
        [
            'label' => esc_html__( 'Background Size', 'medicross' ),
            'type'         => \Elementor\Controls_Manager::SELECT,
            'hide_in_inner' => true,
            'options'      => array(
                ''              => esc_html__( 'Default', 'medicross' ),
                'auto' => esc_html__( 'Auto', 'medicross' ),
                'cover'   => esc_html__( 'Cover', 'medicross' ),
                'contain'  => esc_html__( 'Contain', 'medicross' ),
                'initial'    => esc_html__( 'Custom', 'medicross' ),
            ),
            'default'      => '',
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'background-size: {{VALUE}};',
            ],
            'condition' => [
                'pxl_parallax_bg_img[url]!' => ''
            ]        
        ]
    );
    $element->add_responsive_control(
        'pxl_parallax_bg_size_custom',
        [
            'label' => esc_html__( 'Background Width', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,  
            'hide_in_inner' => true,
            'size_units' => [ 'px', 'em', '%', 'vw' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default' => [
                'size' => 100,
                'unit' => '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'background-size: {{SIZE}}{{UNIT}} auto',
            ],
            'condition' => [
                'pxl_parallax_bg_size' => [ 'initial' ],
                'pxl_parallax_bg_img[url]!' => '',
            ],
        ]
    );
    $element->add_control(
        'pxl_parallax_pos_popover_toggle',
        [
            'label' => esc_html__( 'Parallax Background Position', 'medicross' ),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off' => esc_html__( 'Default', 'medicross' ),
            'label_on' => esc_html__( 'Custom', 'medicross' ),
            'return_value' => 'yes',
            'condition'     => [
                'pxl_parallax_bg_img[url]!' => ''
            ] 
        ]
    );
    $element->start_popover();
        $element->add_responsive_control(
            'pxl_parallax_pos_left',
            [
                'label' => esc_html__( 'Left', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-section-bg-parallax' => 'left: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_responsive_control(
            'pxl_parallax_pos_top',
            [
                'label' => esc_html__( 'Top', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-section-bg-parallax' => 'top: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        ); 
        $element->add_responsive_control(
            'pxl_parallax_pos_right',
            [
                'label' => esc_html__( 'Right', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-section-bg-parallax' => 'right: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_responsive_control(
            'pxl_parallax_pos_bottom',
            [
                'label' => esc_html__( 'Bottom', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-section-bg-parallax' => 'bottom: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        ); 
    $element->end_popover();

    $element->add_control(
        'pxl_parallax_effect_popover_toggle',
        [
            'label' => esc_html__( 'Parallax Background Effect', 'medicross' ),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off' => esc_html__( 'Default', 'medicross' ),
            'label_on' => esc_html__( 'Custom', 'medicross' ),
            'return_value' => 'yes',
            'condition'     => [
                'pxl_parallax_bg_img[url]!' => ''
            ] 
        ]
    );
    $element->start_popover();
        $element->add_control(
            'pxl_parallax_bg_img_effect_x',
            [
                'label' => esc_html__( 'TranslateX', 'medicross' ).' (-80)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_y',
            [
                'label' => esc_html__( 'TranslateY', 'medicross' ).' (-80)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_z',
            [
                'label' => esc_html__( 'TranslateZ', 'medicross' ).' (-80)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_rotate_x',
            [
                'label' => esc_html__( 'Rotate X', 'medicross' ).' (30)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_rotate_y',
            [
                'label' => esc_html__( 'Rotate Y', 'medicross' ).' (30)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_rotate_z',
            [
                'label' => esc_html__( 'Rotate Z', 'medicross' ).' (30)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_scale_x',
            [
                'label' => esc_html__( 'Scale X', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        ); 
        $element->add_control(
            'pxl_parallax_bg_img_effect_scale_y',
            [
                'label' => esc_html__( 'Scale Y', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_scale_z',
            [
                'label' => esc_html__( 'Scale Z', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_img_effect_scale',
            [
                'label' => esc_html__( 'Scale', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_parallax_bg_from_scroll_custom',
            [
                'label' => esc_html__( 'Scroll From (px)', 'medicross' ).' (350) from offset top',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
    $element->end_popover(); 
    $element->add_group_control(
        \Elementor\Group_Control_Css_Filter::get_type(),
        [
            'name'      => 'pxl_section_parallax_img_css_filter',
            'selector' => '{{WRAPPER}} .pxl-section-bg-parallax',
            'condition'     => [
                'pxl_parallax_bg_img[url]!' => ''
            ] 
        ]
    );
    $element->add_responsive_control(
        'pxl_section_parallax_opacity',
        [
            'label'      => esc_html__( 'Parallax Opacity (0 - 100)', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                ]
            ],
            'default'    => [
                'unit' => '%'
            ],
            'laptop_default' => [
                'unit' => '%',
            ],
            'tablet_extra_default' => [
                'unit' => '%',
            ],
            'tablet_default' => [
                'unit' => '%',
            ],
            'mobile_extra_default' => [
                'unit' => '%',
            ],
            'mobile_default' => [
                'unit' => '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'opacity: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pxl_parallax_bg_img[url]!' => ''
            ] 
        ]
    );

    $element->add_control(
        'full_content_with_space',
        [
          'label' => esc_html__( 'Full Content with space from?', 'medicross' ),
          'type'         => \Elementor\Controls_Manager::SELECT,
                'prefix_class' => 'pxl-full-content-with-space-',
                'options'      => array(
                    'none'    => esc_html__( 'None', 'medicross' ),
                    'start'   => esc_html__( 'Start', 'medicross' ),
                    'end'     => esc_html__( 'End', 'medicross' ),
                ),
                'default'      => 'none',
                'condition' => [
                    'layout' => 'full_width'
                ]
        ]
    );
       
    $element->add_control(
        'pxl_container_width',
        [
                'label' => esc_html__('Container Width', 'medicross'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1200,
                'condition' => [
                  'layout' => 'full_width',
                    'full_content_with_space!' => 'none'
                ]           
        ]
    );
    
    $element->add_control(
        'pxl_parallax_bg_img2',
        [
            'label' => esc_html__( 'Parallax Background Image Style 2', 'medicross' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'hide_in_inner' => true,
            'selectors' => [
                '{{WRAPPER}} .pxl-section-bg-parallax' => 'background-image: url( {{URL}} );',
            ],
      ]
  ); 
    $element->end_controls_section();
};
add_filter( 'pxl_section_start_render', 'medicross_custom_section_start_render', 10, 3 );
function medicross_custom_section_start_render($html, $settings, $el){
    if(!empty($settings['pxl_parallax_bg_img2']['url'])){
        $html .= '<div class="pxl-section-bg-parallax"></div>';
    }
    return $html;
}

// function medicross_add_custom_section_overlay_color( \Elementor\Element_Base $element) {
     
//     $element->start_controls_section(
//         'section_overlay_color',
//         [
//             'label' => esc_html__( 'Medicross Overlay Color', 'medicross' ),
//             'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
//         ]
//     );
 
//     $element->add_control(
//         'pxl_color_offset',
//         [
//             'label'   => esc_html__( 'Overlay Color', 'medicross' ),
//             'type'    => \Elementor\Controls_Manager::SELECT,
//             'options' => array(
//                 'none'        => esc_html__( 'No', 'medicross' ),
//                 'full'   => esc_html__( 'Full', 'medicross' ),
//                 'skew'   => esc_html__( 'Skew', 'medicross' ),
//                 'scroll'   => esc_html__( 'Scroll', 'medicross' ),
//             ),
//             'prefix_class' => 'pxl-bg-color-',
//             'default'      => 'none',
//         ]
//     );

//     $element->add_responsive_control(
//         'overlay_left_space',
//         [
//             'label' => esc_html__('Overlay Left Space', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'control_type' => 'responsive',
//             'size_units' => [ 'px' ],
//             'range' => [
//                 'px' => [
//                     'min' => 0,
//                     'max' => 3000,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}}.pxl-bg-color-full .pxl-section-overlay-color' => 'left: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_color_offset' => ['full'],
//             ],
//         ]
//     );

//     $element->add_responsive_control(
//         'overlay_right_space',
//         [
//             'label' => esc_html__('Overlay Right Space', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'control_type' => 'responsive',
//             'size_units' => [ 'px' ],
//             'range' => [
//                 'px' => [
//                     'min' => 0,
//                     'max' => 3000,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}}.pxl-bg-color-full .pxl-section-overlay-color' => 'right: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_color_offset' => ['full'],
//             ],
//         ]
//     );

//     $element->add_control(
//         'offset_color',
//         [
//             'label' => esc_html__('Overlay Color', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::COLOR,
//             'selectors' => [
//                 '{{WRAPPER}}.pxl-bg-color-full .pxl-section-overlay-color, {{WRAPPER}}.pxl-bg-color-full .pxl-section-overlay-color, {{WRAPPER}}.pxl-bg-color-skew .pxl-section-overlay-color, {{WRAPPER}}.pxl-bg-color-scroll .pxl-section-overlay-color' => 'background-color: {{VALUE}};',
//             ],
//             'condition' => [
//                 'pxl_color_offset' => ['full','skew','scroll'],
//             ],
//         ]
//     );

//     $element->add_control(
//         'overlay_broder_radius',
//         [
//             'label' => esc_html__('Overlay Border Radius', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::DIMENSIONS,
//             'control_type' => 'responsive',
//             'selectors' => [
//                 '{{WRAPPER}}.pxl-bg-color-full .pxl-section-overlay-color, {{WRAPPER}}.pxl-bg-color-full .pxl-section-overlay-color' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_color_offset' => ['full'],
//             ],
//         ]
//     );

//     $element->add_control(
//         'overlay_space_sc',
//         [
//             'label' => esc_html__('Overlay Space', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::DIMENSIONS,
//             'control_type' => 'responsive',
//             'condition' => [
//                 'pxl_color_offset' => ['scroll'],
//             ],
//         ]
//     );
//     $element->add_control(
//         'overlay_broder_radius_sc',
//         [
//             'label' => esc_html__('Overlay Border Radius', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::DIMENSIONS,
//             'control_type' => 'responsive',
//             'condition' => [
//                 'pxl_color_offset' => ['scroll'],
//             ],
//         ]
//     );
      
//     $element->end_controls_section();
// };

// function medicross_add_custom_section_overlay_img( \Elementor\Element_Base $element) {
     
//     $element->start_controls_section(
//         'section_overlay_img',
//         [
//             'label' => esc_html__( 'Medicross Overlay Image', 'medicross' ),
//             'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
//         ]
//     );
 
//     $element->add_control(
//         'pxl_overlay_display',
//         [
//             'label'   => esc_html__( 'Overlay Image', 'medicross' ),
//             'type'    => \Elementor\Controls_Manager::SELECT,
//             'options' => array(
//                 'none'        => esc_html__( 'No', 'medicross' ),
//                 'image'   => esc_html__( 'Yes', 'medicross' ),
//             ),
//             'prefix_class' => 'pxl-section-overlay-',
//             'default'      => 'none',
//         ]
//     );

//     $element->add_control(
//         'pxl_overlay_img',
//         [
//             'label'   => esc_html__( 'Select Image 1', 'medicross' ),
//             'type'    => \Elementor\Controls_Manager::MEDIA,
//             'condition' => [
//                 'pxl_overlay_display' => ['image'],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageLeft .bg-image' => 'background-image: url( {{URL}} );',
//             ],
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_position',
//         [
//             'label' => esc_html__( 'Background Position - Image 1', 'medicross' ),
//             'type'         => \Elementor\Controls_Manager::SELECT,
//             'hide_in_inner' => true,
//             'options'      => array(
//                 ''              => esc_html__( 'Default', 'medicross' ),
//                 'center center' => esc_html__( 'Center Center', 'medicross' ),
//                 'center left'   => esc_html__( 'Center Left', 'medicross' ),
//                 'center right'  => esc_html__( 'Center Right', 'medicross' ),
//                 'top center'    => esc_html__( 'Top Center', 'medicross' ),
//                 'top left'      => esc_html__( 'Top Left', 'medicross' ),
//                 'top right'     => esc_html__( 'Top Right', 'medicross' ),
//                 'bottom center' => esc_html__( 'Bottom Center', 'medicross' ),
//                 'bottom left'   => esc_html__( 'Bottom Left', 'medicross' ),
//                 'bottom right'  => esc_html__( 'Bottom Right', 'medicross' ),
//                 'initial'       =>  esc_html__( 'Custom', 'medicross' ),
//             ),
//             'default'      => '',
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageLeft .bg-image' => 'background-position: {{VALUE}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_img[url]!' => ''
//             ]     
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_left_broder_radius',
//         [
//             'label' => esc_html__('Border Radius - Image 1', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::DIMENSIONS,
//             'control_type' => 'responsive',
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageLeft .bg-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_img[url]!' => ''
//             ]
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_left_margin',
//         [
//             'label' => esc_html__('Margin - Image 1', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::DIMENSIONS,
//             'control_type' => 'responsive',
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageLeft' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_img[url]!' => ''
//             ]
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_width_left',
//         [
//             'label' => esc_html__('Width Image 1', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'control_type' => 'responsive',
//             'size_units' => [ '%', 'px' ],
//             'range' => [
//                 'px' => [
//                     'min' => 0,
//                     'max' => 1920,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageLeft' => 'width: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_display' => ['image'],
//                 'pxl_overlay_img[url]!' => ''
//             ],  
//         ]
//     );

//     $element->add_control(
//         'pxl_overlay_img2',
//         [
//             'label'   => esc_html__( 'Select Image 2', 'medicross' ),
//             'type'    => \Elementor\Controls_Manager::MEDIA,
//             'condition' => [
//                 'pxl_overlay_display' => ['image'],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageRight .bg-image' => 'background-image: url( {{URL}} );',
//             ],
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_right_position',
//         [
//             'label' => esc_html__( 'Background Position - Image 2', 'medicross' ),
//             'type'         => \Elementor\Controls_Manager::SELECT,
//             'hide_in_inner' => true,
//             'options'      => array(
//                 ''              => esc_html__( 'Default', 'medicross' ),
//                 'center center' => esc_html__( 'Center Center', 'medicross' ),
//                 'center left'   => esc_html__( 'Center Left', 'medicross' ),
//                 'center right'  => esc_html__( 'Center Right', 'medicross' ),
//                 'top center'    => esc_html__( 'Top Center', 'medicross' ),
//                 'top left'      => esc_html__( 'Top Left', 'medicross' ),
//                 'top right'     => esc_html__( 'Top Right', 'medicross' ),
//                 'bottom center' => esc_html__( 'Bottom Center', 'medicross' ),
//                 'bottom left'   => esc_html__( 'Bottom Left', 'medicross' ),
//                 'bottom right'  => esc_html__( 'Bottom Right', 'medicross' ),
//                 'initial'       =>  esc_html__( 'Custom', 'medicross' ),
//             ),
//             'default'      => '',
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageRight .bg-image' => 'background-position: {{VALUE}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_img2[url]!' => ''
//             ]      
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_right_broder_radius',
//         [
//             'label' => esc_html__('Border Radius - Image 2', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::DIMENSIONS,
//             'control_type' => 'responsive',
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageRight .bg-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_img2[url]!' => ''
//             ]
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_right_margin',
//         [
//             'label' => esc_html__('Margin - Image 2', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::DIMENSIONS,
//             'control_type' => 'responsive',
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageRight' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_img2[url]!' => ''
//             ]
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_width_right',
//         [
//             'label' => esc_html__('Width Image 2', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'control_type' => 'responsive',
//             'size_units' => [ '%', 'px' ],
//             'range' => [
//                 'px' => [
//                     'min' => 0,
//                     'max' => 1920,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageRight' => 'width: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'pxl_overlay_display' => ['image'],
//                 'pxl_overlay_img2[url]!' => ''
//             ],  
//         ]
//     );

//     $element->add_responsive_control(
//         'pxl_overlay_img_offset_left',
//         [
//             'label' => esc_html__('Offset Left - Image 2', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'control_type' => 'responsive',
//             'size_units' => [ 'px' ],
//             'range' => [
//                 'px' => [
//                     'min' => 0,
//                     'max' => 10000,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-overlay--image.pxl-overlay--imageRight .bg-image' => 'margin-left: -{{SIZE}}{{UNIT}}; width: calc(100% + {{SIZE}}{{UNIT}});',
//             ],
//             'condition' => [
//                 'pxl_overlay_img2[url]!' => ''
//             ],  
//         ]
//     );
      
//     $element->end_controls_section();
// };

// function medicross_add_custom_section_divider( \Elementor\Element_Base $element) {
     
//     $element->start_controls_section(
//         'section_divider',
//         [
//             'label' => esc_html__( 'Medicross Divider', 'medicross' ),
//             'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
//         ]
//     );
 
//     $element->add_control(
//         'row_divider',
//         [
//             'label'   => esc_html__( 'Divider', 'medicross' ),
//             'type'    => \Elementor\Controls_Manager::SELECT,
//             'options' => array(
//                 ''        => esc_html__( 'None', 'medicross' ),
//                 'rounded-top'   => esc_html__( 'Rounded Top', 'medicross' ),
//                 'angle-top'   => esc_html__( 'Angle Top Left', 'medicross' ),
//                 'angle-top-right'   => esc_html__( 'Angle Top Right', 'medicross' ),
//                 'angle-bottom-left'   => esc_html__( 'Angle Bottom Left', 'medicross' ),
//                 'angle-bottom'   => esc_html__( 'Angle Bottom Right', 'medicross' ),
//                 'angle-top-bottom'   => esc_html__( 'Angle Top & Bottom Right', 'medicross' ),
//                 'angle-top-bottom-left'   => esc_html__( 'Angle Top & Bottom Left', 'medicross' ),
//                 'wave-animation-top'   => esc_html__( 'Wave Animation Top', 'medicross' ),
//                 'wave-animation-bottom'   => esc_html__( 'Wave Animation Bottom 1', 'medicross' ),
//                 'wave-animation-bottom2'   => esc_html__( 'Wave Animation Bottom 2', 'medicross' ),
//                 'curved-top'   => esc_html__( 'Curved Top', 'medicross' ),
//                 'curved-bottom'   => esc_html__( 'Curved Bottom', 'medicross' ),
//                 'vertical1'   => esc_html__( 'Divider Vertical', 'medicross' ),
//                 'curved-arrow'   => esc_html__( 'Curved Arrow', 'medicross' ),
//                 'curved-arrow-inner-top'   => esc_html__( 'Curved Arrow Inner Top', 'medicross' ),
//                 'curved-arrow-inner-bottom'   => esc_html__( 'Curved Arrow Inner Bottom', 'medicross' ),
//                 'divider-border'   => esc_html__( 'Divider Broder Top/Bottom', 'medicross' ),
//             ),
//             'prefix_class' => 'pxl-row-divider-active pxl-row-divider-',
//             'default'      => '',
//         ]
//     );

//     $element->add_control(
//         'divider_color',
//         [
//             'label' => esc_html__('Divider Color', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::COLOR,
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-row-angle, {{WRAPPER}} .pxl-wave-parallax > use, {{WRAPPER}} .pxl-curved-arrow, {{WRAPPER}} .pxl-curved-arrow-inner-top, {{WRAPPER}} .pxl-curved-arrow-inner-bottom' => 'fill: {{VALUE}} !important;',
//                 '{{WRAPPER}} .pxl-divider-vertical > div' => 'background-color: {{VALUE}} !important;',
//             ],
//         ]
//     );

//     $element->add_responsive_control(
//         'divider_height',
//         [
//             'label' => esc_html__('Divider Height', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'control_type' => 'responsive',
//             'size_units' => [ 'px' ],
//             'range' => [
//                 'px' => [
//                     'min' => 0,
//                     'max' => 3000,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-row-angle, {{WRAPPER}} .pxl-section-waves' => 'height: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'row_divider!' => ['vertical1','curved-arrow','curved-arrow-inner-top','curved-arrow-inner-bottom','divider-border'],
//             ],     
//         ]
//     );
      
//     $element->end_controls_section();
// };

// function medicross_add_custom_section_particles( \Elementor\Element_Base $element) {
     
//     $element->start_controls_section(
//         'section_particles',
//         [
//             'label' => esc_html__( 'Medicross Particles', 'medicross' ),
//             'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
//         ]
//     );

//     $element->add_control(
//         'row_particles_display',
//         [
//             'label'   => esc_html__( 'Particles', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SWITCHER,
//             'default' => 'false',  
//         ]
//     );

//     $element->add_control(
//         'number',
//         [
//                 'label' => esc_html__('Number', 'medicross'),
//                 'type' => \Elementor\Controls_Manager::NUMBER,
//                 'default' => 4,      
//                 'condition' => [
//                     'row_particles_display' => ['yes'],
//                 ],     
//         ]
//     );

//     $element->add_control(
//         'size',
//         [
//                 'label' => esc_html__('Size', 'medicross'),
//                 'type' => \Elementor\Controls_Manager::NUMBER,
//                 'default' => 3, 
//                 'condition' => [
//                     'row_particles_display' => ['yes'],
//                 ],           
//         ]
//     );

//     $element->add_control(
//         'size_random',
//         [
//                 'label' => esc_html__('Size Random', 'medicross'),
//                 'type' => \Elementor\Controls_Manager::SWITCHER,
//                 'default' => 'false',
//                 'condition' => [
//                     'row_particles_display' => ['yes'],
//                 ],   
//         ]
//     );

//     $element->add_control(
//         'move_direction',
//         [
//             'label'   => esc_html__( 'Move Direction', 'medicross' ),
//             'type'    => \Elementor\Controls_Manager::SELECT,
//             'options' => array(
//                 'none'        => esc_html__( 'None', 'medicross' ),
//                 'top'        => esc_html__( 'Top', 'medicross' ),
//                 'top-right'        => esc_html__( 'Top Right', 'medicross' ),
//                 'right'        => esc_html__( 'Right', 'medicross' ),
//                 'bottom-right'        => esc_html__( 'Bottom Right', 'medicross' ),
//                 'bottom'        => esc_html__( 'Bottom', 'medicross' ),
//                 'bottom-left'        => esc_html__( 'Bottom Left', 'medicross' ),
//                 'left'        => esc_html__( 'Left', 'medicross' ),
//                 'top-left'        => esc_html__( 'Top Left', 'medicross' ),
//             ),
//             'default'      => 'none',
//             'condition' => [
//                 'row_particles_display' => ['yes'],
//             ],  
//         ]
//     );

//     $repeater = new \Elementor\Repeater();
//     $repeater->add_control(
//         'particle_color', 
//         [
//             'label' => esc_html__('Color', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::COLOR,
//         ]
//     );
//     $element->add_control(
//         'particle_color_item',
//         [
//             'label' => esc_html__('Color', 'medicross'),
//             'type' => \Elementor\Controls_Manager::REPEATER,
//             'fields' => $repeater->get_controls(),
//             'default' => [],
//             'condition' => [
//                 'row_particles_display' => ['yes'],
//             ],    
//         ]
//     );
      
//     $element->end_controls_section();
// };

// function medicross_add_custom_section_effect_image( \Elementor\Element_Base $element) {
     
//     $element->start_controls_section(
//         'section_effect_image',
//         [
//             'label' => esc_html__( 'Medicross Effect Images', 'medicross' ),
//             'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
//         ]
//     );

//     $repeater_img = new \Elementor\Repeater();

//     $repeater_img->add_control(
//         'item_image', 
//         [
//             'label' => esc_html__('Image', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::MEDIA,
//         ]
//     );

//     $repeater_img->add_control(
//         'image_position', 
//         [
//             'label' => esc_html__('Image Position', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SELECT,
//             'options' => [
//                 'p-top-left' => 'Top Left',
//                 'p-top-right' => 'Top Right',
//                 'p-bottom-left' => 'Bottom Left',
//                 'p-bottom-right' => 'Bottom Right',
//             ],
//             'default' => 'p-top-left',
//         ]
//     );

//     $repeater_img->add_control(
//         'image_position_top', 
//         [
//             'label' => esc_html__('Top Position', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'size_units' => [ 'px', '%' ],
//             'control_type' => 'responsive',
//             'default' => [
//                 'size' => 0,
//                 'unit' => '%',
//             ],
//             'range' => [
//                 '%' => [
//                     'min' => 0,
//                     'max' => 100,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-section-effect-images {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'image_position' => ['p-top-left', 'p-top-right'],
//             ],
//         ]
//     );

//     $repeater_img->add_control(
//         'image_position_left', 
//         [
//             'label' => esc_html__('Left Position', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'size_units' => [ 'px', '%' ],
//             'control_type' => 'responsive',
//             'default' => [
//                 'size' => 0,
//                 'unit' => '%',
//             ],
//             'range' => [
//                 '%' => [
//                     'min' => 0,
//                     'max' => 100,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-section-effect-images {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'image_position' => ['p-top-left', 'p-bottom-left'],
//             ],
//         ]
//     );

//     $repeater_img->add_control(
//         'image_position_bottom', 
//         [
//             'label' => esc_html__('Bottom Position', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'size_units' => [ 'px', '%' ],
//             'control_type' => 'responsive',
//             'default' => [
//                 'size' => 0,
//                 'unit' => '%',
//             ],
//             'range' => [
//                 '%' => [
//                     'min' => 0,
//                     'max' => 100,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-section-effect-images {{CURRENT_ITEM}}' => 'bottom: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'image_position' => ['p-bottom-left', 'p-bottom-right'],
//             ],
//         ]
//     );

//     $repeater_img->add_control(
//         'image_position_right', 
//         [
//             'label' => esc_html__('Right Position', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SLIDER,
//             'size_units' => [ 'px', '%' ],
//             'control_type' => 'responsive',
//             'default' => [
//                 'size' => 0,
//                 'unit' => '%',
//             ],
//             'range' => [
//                 '%' => [
//                     'min' => 0,
//                     'max' => 100,
//                 ],
//             ],
//             'selectors' => [
//                 '{{WRAPPER}} .pxl-section-effect-images {{CURRENT_ITEM}}' => 'right: {{SIZE}}{{UNIT}};',
//             ],
//             'condition' => [
//                 'image_position' => ['p-top-right', 'p-bottom-right'],
//             ],
//         ]
//     );

//     $repeater_img->add_control(
//         'effect_image', 
//         [
//             'label' => esc_html__('Image Effect', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SELECT,
//             'options' => [
//                 '' => 'None',
//                 'pxl-image-spin' => 'Spin',
//                 'pxl-image-bounce' => 'Bounce',
//                 'slide-up-down' => 'Slide Up Down',
//                 'slide-top-to-bottom' => 'Slide Top To Bottom ',
//                 'pxl-image-effect2' => 'Slide Bottom To Top ',
//                 'slide-right-to-left' => 'Slide Right To Left ',
//                 'slide-left-to-right' => 'Slide Left To Right ',
//                 'pxl-parallax-scroll' => 'Parallax Scroll',
//                 'pxl-parallax-hover' => 'Parallax Hover',
//             ],
//             'default' => '',
//         ]
//     );
    
//     $repeater_img->add_control(
//         'parallax_scroll_type', 
//         [
//             'label' => esc_html__('Parallax Scroll Type', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::SELECT,
//             'options' => [
//                 'y' => 'Effect Y',
//                 'x' => 'Effect X',
//                 'z' => 'Effect Z',
//             ],
//             'default' => 'y',
//             'condition' => [
//                 'effect_image' => 'pxl-parallax-scroll',
//             ],
//         ]
//     );

//     $repeater_img->add_control(
//         'parallax_scroll_value', 
//         [
//             'label' => esc_html__('Parallax Scroll Value', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::TEXT,
//             'default' => '80',
//             'description' => esc_html__('Enter number.', 'medicross' ),
//             'condition' => [
//                 'effect_image' => 'pxl-parallax-scroll',
//             ],
//         ]
//     );

//     $repeater_img->add_control(
//         'parallax_hover_value', 
//         [
//             'label' => esc_html__('Parallax Hover Value', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::TEXT,
//             'default' => '40',
//             'description' => esc_html__('Enter number.', 'medicross' ),
//             'condition' => [
//                 'effect_image' => 'pxl-parallax-hover',
//             ],
//         ]
//     );

//     $repeater_img->add_control(
//         'image_display', 
//         [
//             'label' => esc_html__('Hide on Screen <= 1400px', 'medicross'),
//             'type' => \Elementor\Controls_Manager::SWITCHER,
//             'default' => 'false',
//         ]
//     );

//     $repeater_img->add_control(
//         'image_display_md', 
//         [
//             'label' => esc_html__('Hide on Screen <= 1200px', 'medicross'),
//             'type' => \Elementor\Controls_Manager::SWITCHER,
//             'default' => 'false',
//         ]
//     );

//     $repeater_img->add_control(
//         'image_display_sm', 
//         [
//             'label' => esc_html__('Hide on Screen <= 767px', 'medicross'),
//             'type' => \Elementor\Controls_Manager::SWITCHER,
//             'default' => 'false',
//         ]
//     );

//     $element->add_control(
//         'row_effect_images',
//         [
//             'label'   => esc_html__( 'Images', 'medicross' ),
//             'type' => \Elementor\Controls_Manager::REPEATER,
//             'fields' => $repeater_img->get_controls(),
//             'default' => [],
//         ]
//     );
      
//     $element->end_controls_section();
// };

/* End Section */

/* Start Column */
add_action( 'elementor/element/column/layout/after_section_end', 'medicross_add_custom_columns_controls' ); 
function medicross_add_custom_columns_controls( \Elementor\Element_Base $element) {
    $element->start_controls_section(
        'columns_pxl',
        [
            'label' => esc_html__( 'Medicross General Settings', 'medicross' ),
            'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
        ]
    );

    $element->add_control(
        'col_content_align',
        [
            'label'   => esc_html__( 'Column Content Align', 'medicross' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                ''           => esc_html__( 'Default', 'medicross' ),
                'start'           => esc_html__( 'Start', 'medicross' ),
                'center'           => esc_html__( 'Center', 'medicross' ),
                'end'           => esc_html__( 'End', 'medicross' ),
            ),
            'default' => '',
            'prefix_class' => 'pxl-col-align-'
        ]
    );

    $element->add_control(
        'col_sticky',
        [
            'label'   => esc_html__( 'Column Sticky', 'medicross' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => array(
                'none'           => esc_html__( 'No', 'medicross' ),
                'sticky' => esc_html__( 'Yes', 'medicross' ),
            ),
            'default' => 'none',
            'prefix_class' => 'pxl-column-'
        ]
    );

    $element->add_control(
        'col_sticky_offset_top',
        [
            'label' => esc_html__( 'Sticky Offset Top', 'medicross' ),
            'type' => 'text',
            'description' => esc_html__('Enter number.', 'medicross' ),
            'default'  => '30',
            'selectors' => [
                '{{WRAPPER}}.pxl-column-sticky' => 'top: {{VALUE}}'.'px',
            ],
            'condition' => [
                'col_sticky' => 'sticky'
            ]
        ]
    );

    $element->add_control(
        'pxl_column_parallax_bg_img',
        [
            'label' => esc_html__( 'Parallax Background Image', 'medicross' ),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'hide_in_inner' => true,
            'selectors' => [
                '{{WRAPPER}} .pxl-column-bg-parallax' => 'background-image: url( {{URL}} );',
            ],
        ]
    );
    $element->add_responsive_control(
        'pxl_column_parallax_bg_position',
        [
            'label' => esc_html__( 'Background Position', 'medicross' ),
            'type'         => \Elementor\Controls_Manager::SELECT,
            'hide_in_inner' => true,
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
            'default'      => '',
            'selectors' => [
                '{{WRAPPER}} .pxl-column-bg-parallax' => 'background-position: {{VALUE}};',
            ],
            'condition' => [
                'pxl_column_parallax_bg_img[url]!' => ''
            ]        
        ]
    );
     
    $element->add_responsive_control(
        'pxl_column_parallax_bg_pos_custom_x',
        [
            'label' => esc_html__( 'X Position', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,  
            'hide_in_inner' => true,
            'size_units' => [ 'px', 'em', '%', 'vw' ],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -800,
                    'max' => 800,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-column-bg-parallax' => 'background-position: {{SIZE}}{{UNIT}} {{pxl_column_parallax_bg_pos_custom_y.SIZE}}{{pxl_column_parallax_bg_pos_custom_y.UNIT}}',
            ],
            'condition' => [
                'pxl_column_parallax_bg_position' => [ 'initial' ],
                'pxl_column_parallax_bg_img[url]!' => '',
            ],
        ]
    );
    $element->add_responsive_control(
        'pxl_column_parallax_bg_pos_custom_y',
        [
            'label' => esc_html__( 'Y Position', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,  
            'hide_in_inner' => true,
            'size_units' => [ 'px', 'em', '%', 'vw' ],
            'default' => [
                'unit' => 'px',
                'size' => 0,
            ],
            'range' => [
                'px' => [
                    'min' => -800,
                    'max' => 800,
                ],
                'em' => [
                    'min' => -100,
                    'max' => 100,
                ],
                '%' => [
                    'min' => -100,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-column-bg-parallax' => 'background-position: {{pxl_column_parallax_bg_pos_custom_x.SIZE}}{{pxl_column_parallax_bg_pos_custom_x.UNIT}} {{SIZE}}{{UNIT}}',
            ],

            'condition' => [
                'pxl_column_parallax_bg_position' => [ 'initial' ],
                'pxl_column_parallax_bg_img[url]!' => '',
            ],
        ]
    );
    $element->add_responsive_control(
        'pxl_column_parallax_bg_size',
        [
            'label' => esc_html__( 'Background Size', 'medicross' ),
            'type'         => \Elementor\Controls_Manager::SELECT,
            'hide_in_inner' => true,
            'options'      => array(
                ''              => esc_html__( 'Default', 'medicross' ),
                'auto' => esc_html__( 'Auto', 'medicross' ),
                'cover'   => esc_html__( 'Cover', 'medicross' ),
                'contain'  => esc_html__( 'Contain', 'medicross' ),
                'initial'    => esc_html__( 'Custom', 'medicross' ),
            ),
            'default'      => '',
            'selectors' => [
                '{{WRAPPER}} .pxl-column-bg-parallax' => 'background-size: {{VALUE}};',
            ],
            'condition' => [
                'pxl_column_parallax_bg_img[url]!' => ''
            ]        
        ]
    );
    $element->add_responsive_control(
        'pxl_column_parallax_bg_size_custom',
        [
            'label' => esc_html__( 'Background Width', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,  
            'hide_in_inner' => true,
            'size_units' => [ 'px', 'em', '%', 'vw' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 1000,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'vw' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default' => [
                'size' => 100,
                'unit' => '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-column-bg-parallax' => 'background-size: {{SIZE}}{{UNIT}} auto',
            ],
            'condition' => [
                'pxl_column_parallax_bg_size' => [ 'initial' ],
                'pxl_column_parallax_bg_img[url]!' => '',
            ],
        ]
    );
    $element->add_control(
        'pxl_column_parallax_pos_popover_toggle',
        [
            'label' => esc_html__( 'Parallax Background Position', 'medicross' ),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off' => esc_html__( 'Default', 'medicross' ),
            'label_on' => esc_html__( 'Custom', 'medicross' ),
            'return_value' => 'yes',
            'condition'     => [
                'pxl_column_parallax_bg_img[url]!' => ''
            ] 
        ]
    );
    $element->start_popover();
        $element->add_responsive_control(
            'pxl_column_parallax_pos_left',
            [
                'label' => esc_html__( 'Left', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-column-bg-parallax' => 'left: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_responsive_control(
            'pxl_column_parallax_pos_top',
            [
                'label' => esc_html__( 'Top', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-column-bg-parallax' => 'top: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        ); 
        $element->add_responsive_control(
            'pxl_column_parallax_pos_right',
            [
                'label' => esc_html__( 'Right', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-column-bg-parallax' => 'right: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_responsive_control(
            'pxl_column_parallax_pos_bottom',
            [
                'label' => esc_html__( 'Bottom', 'medicross' ).' (50px) px,%,vw,auto',
                'type' => 'text',
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .pxl-column-bg-parallax' => 'bottom: {{VALUE}}',
                ],
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        ); 
    $element->end_popover();

    $element->add_control(
        'pxl_column_parallax_effect_popover_toggle',
        [
            'label' => esc_html__( 'Parallax Background Effect', 'medicross' ),
            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
            'label_off' => esc_html__( 'Default', 'medicross' ),
            'label_on' => esc_html__( 'Custom', 'medicross' ),
            'return_value' => 'yes',
            'condition'     => [
                'pxl_column_parallax_bg_img[url]!' => ''
            ] 
        ]
    );
    $element->start_popover();
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_x',
            [
                'label' => esc_html__( 'TranslateX', 'medicross' ).' (-80)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_y',
            [
                'label' => esc_html__( 'TranslateY', 'medicross' ).' (-80)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_z',
            [
                'label' => esc_html__( 'TranslateZ', 'medicross' ).' (-80)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_rotate_x',
            [
                'label' => esc_html__( 'Rotate X', 'medicross' ).' (30)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_rotate_y',
            [
                'label' => esc_html__( 'Rotate Y', 'medicross' ).' (30)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_rotate_z',
            [
                'label' => esc_html__( 'Rotate Z', 'medicross' ).' (30)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_scale_x',
            [
                'label' => esc_html__( 'Scale X', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        ); 
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_scale_y',
            [
                'label' => esc_html__( 'Scale Y', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_scale_z',
            [
                'label' => esc_html__( 'Scale Z', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_img_effect_scale',
            [
                'label' => esc_html__( 'Scale', 'medicross' ).' (1.2)',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
        $element->add_control(
            'pxl_column_parallax_bg_from_scroll_custom',
            [
                'label' => esc_html__( 'Scroll From (px)', 'medicross' ).' (350) from offset top',
                'type' => 'text',
                'default' => '',
                'condition'     => [
                    'pxl_column_parallax_bg_img[url]!' => ''
                ] 
            ]
        );
    $element->end_popover(); 
    $element->add_group_control(
        \Elementor\Group_Control_Css_Filter::get_type(),
        [
            'name'      => 'pxl_column_parallax_img_css_filter',
            'selector' => '{{WRAPPER}} .pxl-column-bg-parallax',
            'condition'     => [
                'pxl_column_parallax_bg_img[url]!' => ''
            ] 
        ]
    );
    $element->add_responsive_control(
        'pxl_column_parallax_opacity',
        [
            'label'      => esc_html__( 'Parallax Opacity (0 - 100)', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ '%' ],
            'range' => [
                '%' => [
                    'min' => 1,
                    'max' => 100,
                ]
            ],
            'default'    => [
                'unit' => '%'
            ],
            'laptop_default' => [
                'unit' => '%',
            ],
            'tablet_extra_default' => [
                'unit' => '%',
            ],
            'tablet_default' => [
                'unit' => '%',
            ],
            'mobile_extra_default' => [
                'unit' => '%',
            ],
            'mobile_default' => [
                'unit' => '%',
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-column-bg-parallax' => 'opacity: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
                'pxl_column_parallax_bg_img[url]!' => ''
            ] 
        ]
    );

    $element->add_control(
        'pxl_column_overflow_hidden',
        [
            'label' => esc_html__('Overflow Hidden', 'medicross'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => esc_html__( 'Yes', 'medicross' ),
            'label_off' => esc_html__( 'No', 'medicross' ),
            'return_value' => 'yes',
            'default' => 'no',
            'separator' => 'after',
            'prefix_class' => 'pxl-column-overflow-hidden-'
        ]
    );

    $element->end_controls_section();
}

/* End Column */

add_action( 'elementor/element/after_add_attributes', 'medicross_custom_el_attributes', 10, 1 );
function medicross_custom_el_attributes($el){
    if( 'section' !== $el->get_name() ) {
        return;
    }
    $settings = $el->get_settings();

    $pxl_container_width = !empty($settings['pxl_container_width']) ? (int)$settings['pxl_container_width'] : 1200;

    if( isset( $settings['stretch_section']) && $settings['stretch_section'] == 'section-stretched') 
        $pxl_container_width = $pxl_container_width - 30;

    $pxl_container_width = $pxl_container_width.'px';

    if ( isset( $settings['full_content_with_space'] ) && $settings['full_content_with_space'] === 'start' ) {
       
        $el->add_render_attribute( '_wrapper', 'style', 'padding-left: calc( (100% - '.$pxl_container_width.')/2);');
    }
    if ( isset( $settings['full_content_with_space'] ) && $settings['full_content_with_space'] === 'end' ) {
       
          $el->add_render_attribute( '_wrapper >', 'style', 'padding-right: calc( (100% - '.$pxl_container_width.')/2);');
    }
    if( 'section' == $el->get_name() ) {
        if ( isset( $settings['pxl_header_type'] ) && !empty($settings['pxl_header_type'] ) ) {
            $el->add_render_attribute( '_wrapper', 'class', 'pxl-header-'.$settings['pxl_header_type']);
        }
    }
}

add_filter( 'pxl-custom-section/before-render', 'medicross_custom_section_before_render', 10, 3 );
function medicross_custom_section_before_render($html ,$settings, $el) {

    if(!empty($settings['pxl_parallax_bg_img']['url'])){
        $effects = [];
        if(!empty($settings['pxl_parallax_bg_img_effect_x'])){
            $effects['x'] = (int)$settings['pxl_parallax_bg_img_effect_x'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_y'])){
            $effects['y'] = (int)$settings['pxl_parallax_bg_img_effect_y'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_z'])){
            $effects['z'] = (int)$settings['pxl_parallax_bg_img_effect_z'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_rotate_x'])){
            $effects['rotateX'] = (float)$settings['pxl_parallax_bg_img_effect_rotate_x'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_rotate_y'])){
            $effects['rotateY'] = (float)$settings['pxl_parallax_bg_img_effect_rotate_y'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_rotate_z'])){
            $effects['rotateZ'] = (float)$settings['pxl_parallax_bg_img_effect_rotate_z'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_scale'])){
            $effects['scale'] = (float)$settings['pxl_parallax_bg_img_effect_scale'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_scale_x'])){
            $effects['scaleX'] = (float)$settings['pxl_parallax_bg_img_effect_scale_x'];
        }
        if(!empty($settings['pxl_parallax_bg_img_effect_scale_y'])){
            $effects['scaleY'] = (float)$settings['pxl_parallax_bg_img_effect_scale_y'];
        }
        if(!empty($settings['pxl_parallax_bg_from_scroll_custom'])){
            $effects['from-scroll-custom'] = (int)$settings['pxl_parallax_bg_from_scroll_custom'];
        }
         
        $data_parallax = json_encode($effects);
        $html .= '<div class="pxl-section-bg-parallax" data-parallax="'.esc_attr($data_parallax).'"></div>';
    }

    // if(!empty($settings['row_divider'])) {
    //     if($settings['row_divider'] == 'angle-top' || $settings['row_divider'] == 'angle-bottom' || $settings['row_divider'] == 'angle-top-right' || $settings['row_divider'] == 'angle-bottom-left') {
    //         $html .=  '<svg class="pxl-row-angle" style="fill:#ffffff" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none" height="130px"><path stroke="" stroke-width="0" d="M0 100 L100 0 L200 100"></path></svg>';
    //     }
    //     if($settings['row_divider'] == 'angle-top-bottom' || $settings['row_divider'] == 'angle-top-bottom-left') {
    //         $html .=  '<svg class="pxl-row-angle pxl-row-angle-top" style="fill:#ffffff" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none" height="130px"><path stroke="" stroke-width="0" d="M0 100 L100 0 L200 100"></path></svg><svg class="pxl-row-angle pxl-row-angle-bottom" style="fill:#ffffff" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 100 100" version="1.1" preserveAspectRatio="none" height="130px"><path stroke="" stroke-width="0" d="M0 100 L100 0 L200 100"></path></svg>';
    //     }
    //     if($settings['row_divider'] == 'wave-animation-top' || $settings['row_divider'] == 'wave-animation-bottom') {
    //         $html .=  '<svg class="pxl-row-angle" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1440 150" fill="#fff"><path d="M 0 26.1978 C 275.76 83.8152 430.707 65.0509 716.279 25.6386 C 930.422 -3.86123 1210.32 -3.98357 1439 9.18045 C 2072.34 45.9691 2201.93 62.4429 2560 26.198 V 172.199 L 0 172.199 V 26.1978 Z"><animate repeatCount="indefinite" fill="freeze" attributeName="d" dur="10s" values="M0 25.9086C277 84.5821 433 65.736 720 25.9086C934.818 -3.9019 1214.06 -5.23669 1442 8.06597C2079 45.2421 2208 63.5007 2560 25.9088V171.91L0 171.91V25.9086Z; M0 86.3149C316 86.315 444 159.155 884 51.1554C1324 -56.8446 1320.29 34.1214 1538 70.4063C1814 116.407 2156 188.408 2560 86.315V232.317L0 232.316V86.3149Z; M0 53.6584C158 11.0001 213 0 363 0C513 0 855.555 115.001 1154 115.001C1440 115.001 1626 -38.0004 2560 53.6585V199.66L0 199.66V53.6584Z; M0 25.9086C277 84.5821 433 65.736 720 25.9086C934.818 -3.9019 1214.06 -5.23669 1442 8.06597C2079 45.2421 2208 63.5007 2560 25.9088V171.91L0 171.91V25.9086Z"></animate></path></svg>';
    //     }
    //     if($settings['row_divider'] == 'wave-animation-bottom2') {
    //         $pxl_uniqid = uniqid();
    //         $html .=  '<svg class="pxl-section-waves pxl-section-waves1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto"><defs><path id="pxl-gentle-wave-'.$pxl_uniqid.'" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" /></defs><g class="pxl-wave-parallax"><use xlink:href="#pxl-gentle-wave-'.$pxl_uniqid.'" x="48" y="0" /><use xlink:href="#pxl-gentle-wave-'.$pxl_uniqid.'" x="48" y="3" /><use xlink:href="#pxl-gentle-wave-'.$pxl_uniqid.'" x="48" y="5" /><use xlink:href="#pxl-gentle-wave-'.$pxl_uniqid.'" x="48" y="7" /></g></svg>';
    //     }
    //     if($settings['row_divider'] == 'curved-top' || $settings['row_divider'] == 'curved-bottom') {
    //         $html .=  '<svg class="pxl-row-angle" xmlns="http://www.w3.org/2000/svg" width="100%" viewBox="0 0 1920 128" version="1.1" preserveAspectRatio="none" style="fill:#ffffff"><path stroke-width="0" d="M-1,126a3693.886,3693.886,0,0,1,1921,2.125V-192H-7Z"></path></svg>';
    //     }

    //     if($settings['row_divider'] == 'curved-arrow') {
    //         $html .=  '<svg class="pxl-curved-arrow pxl-curved-arrow-top" data-name="shape" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 954.78 402.26"><path d="M.74,402.26h0Z"/><path d="M954.78,402.26h0Z"/><path d="M477.39,0C402.6,395.71,19.71,402.18.74,402.26H954C935.08,402.18,552.18,395.71,477.39,0Z"/></svg><svg class="pxl-curved-arrow pxl-curved-arrow-bottom" data-name="shape" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 954.78 402.26"><path d="M.74,402.26h0Z"/><path d="M954.78,402.26h0Z"/><path d="M477.39,0C402.6,395.71,19.71,402.18.74,402.26H954C935.08,402.18,552.18,395.71,477.39,0Z"/></svg>';
    //     }

    //     if($settings['row_divider'] == 'curved-arrow-inner-top') {
    //         $html .=  '<svg class="pxl-curved-arrow-inner-top" data-name="shape" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 954.78 402.26"><path d="M.74,402.26h0Z"/><path d="M954.78,402.26h0Z"/><path d="M477.39,0C402.6,395.71,19.71,402.18.74,402.26H954C935.08,402.18,552.18,395.71,477.39,0Z"/></svg>';
    //     }

    //     if($settings['row_divider'] == 'curved-arrow-inner-bottom') {
    //         $html .=  '<svg class="pxl-curved-arrow-inner-bottom" data-name="shape" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 954.78 402.26"><path d="M.74,402.26h0Z"/><path d="M954.78,402.26h0Z"/><path d="M477.39,0C402.6,395.71,19.71,402.18.74,402.26H954C935.08,402.18,552.18,395.71,477.39,0Z"/></svg>';
    //     }

    //     if($settings['row_divider'] == 'vertical1') {
    //         $html .=  '<div class="pxl-divider-vertical"><div class="pxl-section-line1"></div><div class="pxl-section-line2"></div><div class="pxl-section-line3"></div><div class="pxl-section-line4"></div><div class="pxl-section-line5"></div><div class="pxl-section-line6"></div></div>';
    //     }

    //     if($settings['row_divider'] == 'divider-border') {
    //         $html .=  '<div class="pxl-divider-border"><div class="pxl-divider-border-top"></div><div class="pxl-divider-border-bottom"></div></div>';
    //     }
    // }

    // if($settings['pxl_color_offset'] == 'full' || $settings['pxl_color_offset'] == 'skew' || $settings['pxl_color_offset'] == 'scroll' ) {
    //     $html .=  '<div class="pxl-section-overlay-color overlay-type-'.$settings['pxl_color_offset'].'" data-space-top="'.$settings['overlay_space_sc']['top'].'" data-space-left="'.$settings['overlay_space_sc']['left'].'" data-space-right="'.$settings['overlay_space_sc']['right'].'" data-space-bottom="'.$settings['overlay_space_sc']['bottom'].'" data-radius-top="'.$settings['overlay_broder_radius_sc']['top'].'" data-radius-right="'.$settings['overlay_broder_radius_sc']['right'].'" data-radius-bottom="'.$settings['overlay_broder_radius_sc']['bottom'].'" data-radius-left="'.$settings['overlay_broder_radius_sc']['left'].'"></div>';
    // }

    // if( $settings['row_zoom_point'] == 'true' ) {
    //     $html .=  '<div class="pxl-zoom-point-wrap">';
    //         $html .= '<div class="pxl-zoom-point" data-offset="250" data-scale-mount="30"><div class="pxl-item--overlay" data-scroll-zoom=""></div></div>';
    //         if(!empty($settings['row_zoom_point_img']['url'])) {
    //             $html .= '<div class="pxl-zoom--image"><img src="'.$settings['row_zoom_point_img']['url'].'" /></div>';
    //         }
    //     $html .=  '</div>';
    // }

    // if($settings['pxl_overlay_display'] == 'image' && !empty($settings['pxl_overlay_img']['url'])) {
    //     $html .=  '<div class="pxl-overlay--image pxl-overlay--imageLeft"><div class="bg-image"></div></div>';
    // }

    // if($settings['pxl_overlay_display'] == 'image' && $settings['pxl_overlay_img2']['url']) {
    //     $html .=  '<div class="pxl-overlay--image pxl-overlay--imageRight"><div class="bg-image"></div></div>';
    // }

    // if(!empty($settings['row_particles_display']) && $settings['row_particles_display'] == 'yes') {
    //     wp_enqueue_script('particles-background');
    //     $s_random = '';
    //     if($settings['size_random'] == 'yes') {
    //         $s_random = 'true';
    //     } else {
    //         $s_random = 'false';
    //     }
    //     $colors = [];
    //     foreach($settings['particle_color_item'] as $values) {
    //         $colors[] = $values['particle_color'];
    //     }
    //     if(empty($colors)) {
    //         $colors = ["#b73490","#006b41","#cd3000","#608ecb","#ffb500","#6e4e00","#6b541b","#305686","#00ffb4","#8798ff","#0044c1"];
    //     }
    //     $el->add_render_attribute( 'color', 'data-color', json_encode($colors) );
    //     $html .= '<div id="pxl-row-particles-'.uniqid().'" class="pxl-row-particles" data-number="'.$settings['number'].'" data-size="'.$settings['size'].'" data-size-random="'.$s_random.'" data-move-direction="'.$settings['move_direction'].'" '.$el->get_render_attribute_string( 'color' ).'></div>';
    // }

    if(isset($settings['row_effect_images']) && !empty($settings['row_effect_images']) && count($settings['row_effect_images'])):
        $html .= '<div class="pxl-section-effect-images">';
            foreach ($settings['row_effect_images'] as $key => $value):
                $item_image = isset($value['item_image']) ? $value['item_image'] : '';
                $effect_image = isset($value['effect_image']) ? $value['effect_image'] : '';
                $image_display = isset($value['image_display']) ? $value['image_display'] : '';
                $image_display_md = isset($value['image_display_md']) ? $value['image_display_md'] : '';
                $image_display_sm = isset($value['image_display_sm']) ? $value['image_display_sm'] : '';
                $parallax_scroll_type = isset($value['parallax_scroll_type']) ? $value['parallax_scroll_type'] : '';
                $parallax_scroll_value = isset($value['parallax_scroll_value']) ? $value['parallax_scroll_value'] : '';
                $hidde_class = '';
                if($image_display !== 'false') {
                   $hidde_class = 'pxl-hide-sr-lg';
                }
                $hidde_class_md = '';
                if($image_display_md !== 'false') {
                   $hidde_class_md = 'pxl-hide-sr-md';
                }
                $hidde_class_sm = '';
                if($image_display_sm !== 'false') {
                   $hidde_class_sm = 'pxl-hide-sr-sm';
                }
                $effects = [];
                if($parallax_scroll_type == 'y' && !empty($parallax_scroll_value)){
                    $effects['y'] = (int)$parallax_scroll_value;
                }
                if($parallax_scroll_type == 'x' && !empty($parallax_scroll_value)){
                    $effects['x'] = (int)$parallax_scroll_value;
                }
                if($parallax_scroll_type == 'z' && !empty($parallax_scroll_value)){
                    $effects['z'] = (int)$parallax_scroll_value;
                }
                $data_parallax = json_encode($effects);

                wp_enqueue_script( 'pxl-parallax-move-mouse');
                $parallax_hover_value = isset($value['parallax_hover_value']) ? $value['parallax_hover_value'] : '';

                $html .= '<img data-parallax-value="'.esc_attr($parallax_hover_value).'" data-parallax="'.esc_attr($data_parallax).'" class="pxl-item--image elementor-repeater-item-'.$value['_id'].' '.$effect_image.' '.$hidde_class.' '.$hidde_class_md.' '.$hidde_class_sm.'" src="'.$item_image['url'].'" />';
            endforeach;
        $html .= '</div>';
    endif;

    return $html;

}

add_filter( 'pxl-custom-column/before-render', 'medicross_custom_column_before_render', 10, 3 );
function medicross_custom_column_before_render($html, $settings, $el){
    if(!empty($settings['pxl_column_parallax_bg_img']['url'])){
        $effects = [];
        if(!empty($settings['pxl_column_parallax_bg_img_effect_x'])){
            $effects['x'] = (int)$settings['pxl_column_parallax_bg_img_effect_x'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_y'])){
            $effects['y'] = (int)$settings['pxl_column_parallax_bg_img_effect_y'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_z'])){
            $effects['z'] = (int)$settings['pxl_column_parallax_bg_img_effect_z'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_rotate_x'])){
            $effects['rotateX'] = (float)$settings['pxl_column_parallax_bg_img_effect_rotate_x'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_rotate_y'])){
            $effects['rotateY'] = (float)$settings['pxl_column_parallax_bg_img_effect_rotate_y'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_rotate_z'])){
            $effects['rotateZ'] = (float)$settings['pxl_column_parallax_bg_img_effect_rotate_z'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_scale'])){
            $effects['scale'] = (float)$settings['pxl_column_parallax_bg_img_effect_scale'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_scale_x'])){
            $effects['scaleX'] = (float)$settings['pxl_column_parallax_bg_img_effect_scale_x'];
        }
        if(!empty($settings['pxl_column_parallax_bg_img_effect_scale_y'])){
            $effects['scaleY'] = (float)$settings['pxl_column_parallax_bg_img_effect_scale_y'];
        }
        if(!empty($settings['pxl_column_parallax_bg_from_scroll_custom'])){
            $effects['from-scroll-custom'] = (int)$settings['pxl_column_parallax_bg_from_scroll_custom'];
        }
         
        $data_parallax = json_encode($effects);
        $html .= '<div class="pxl-column-bg-parallax" data-parallax="'.esc_attr($data_parallax).'"></div>';
    }
    return $html;
}
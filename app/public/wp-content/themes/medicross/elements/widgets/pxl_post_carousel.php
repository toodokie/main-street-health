<?php
$pt_supports = ['service','post','portfolio','industries','pxl_product','injury'];
pxl_add_custom_widget(
    array(
        'name' => 'pxl_post_carousel',
        'title' => esc_html__('Case Post Carousel', 'medicross' ),
        'icon' => 'eicon-posts-carousel',
        'categories' => array('pxltheme-core'),
        'scripts' => array(
            'swiper',
            'pxl-swiper',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name'     => 'layout_section',
                    'label'    => esc_html__( 'Layout', 'medicross' ),
                    'tab'      => 'layout',
                    'controls' => array_merge(
                        array(
                            array(
                                'name'     => 'post_type',
                                'label'    => esc_html__( 'Select Post Type', 'medicross' ),
                                'type'     => 'select',
                                'multiple' => true,
                                'options'  => medicross_get_post_type_options($pt_supports),
                                'default'  => 'post'
                            ) 
                        ),
                        medicross_get_post_carousel_layout($pt_supports)
                    ),
                ),
                array(
                    'name' => 'section_source',
                    'label' => esc_html__('Source', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                    'controls' => array_merge(
                        array(
                            array(
                                'name'     => 'select_post_by',
                                'label'    => esc_html__( 'Select posts by', 'medicross' ),
                                'type'     => 'select',
                                'multiple' => true,
                                'options'  => [
                                    'term_selected' => esc_html__( 'Terms selected', 'medicross' ),
                                    'post_selected' => esc_html__( 'Posts selected ', 'medicross' ),
                                ],
                                'default'  => 'term_selected'
                            ) 
                        ),
                        medicross_get_grid_term_by_post_type($pt_supports, ['custom_condition' => ['select_post_by' => 'term_selected']]),
                        medicross_get_grid_ids_by_post_type($pt_supports, ['custom_condition' => ['select_post_by' => 'post_selected']]),
                        array(
                            array(
                                'name' => 'orderby',
                                'label' => esc_html__('Order By', 'medicross' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'default' => 'date',
                                'options' => [
                                    'date' => esc_html__('Date', 'medicross' ),
                                    'ID' => esc_html__('ID', 'medicross' ),
                                    'author' => esc_html__('Author', 'medicross' ),
                                    'title' => esc_html__('Title', 'medicross' ),
                                    'rand' => esc_html__('Random', 'medicross' ),
                                ],
                            ),
                            array(
                                'name' => 'order',
                                'label' => esc_html__('Sort Order', 'medicross' ),
                                'type' => \Elementor\Controls_Manager::SELECT,
                                'default' => 'desc',
                                'options' => [
                                    'desc' => esc_html__('Descending', 'medicross' ),
                                    'asc' => esc_html__('Ascending', 'medicross' ),
                                ],
                            ),
                            array(
                                'name' => 'limit',
                                'label' => esc_html__('Total items', 'medicross' ),
                                'type' => \Elementor\Controls_Manager::NUMBER,
                                'default' => '6',
                            ),
                        )
                    ),
                ),
                array(
                    'name' => 'section_carousel',
                    'label' => esc_html__('Carousel', 'medicross'),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                    'controls' => array(
                        array(
                            'name' => 'pxl_animate',
                            'label' => esc_html__('Medicross Animate', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => medicross_widget_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'col_xs',
                            'label' => esc_html__('Columns: Screen <= 575', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '1',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                                'auto' => 'auto',
                            ],
                        ),
                        array(
                            'name' => 'col_sm',
                            'label' => esc_html__('Columns: Screen <= 767', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '2',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                                'auto' => 'auto',
                            ],
                        ),
                        array(
                            'name' => 'col_md',
                            'label' => esc_html__('Columns: Screen <= 991', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '2',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                                'auto' => 'auto',
                            ],
                        ),
                        array(
                            'name' => 'col_lg',
                            'label' => esc_html__('Columns: Screen <= 1199', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                'auto' => 'auto',
                            ],
                        ),
                        array(
                            'name' => 'col_xl',
                            'label' => esc_html__('Columns: Screen => 1200', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                'auto' => 'auto',
                            ],
                        ),
                        array(
                            'name' => 'col_xxl',
                            'label' => esc_html__('Columns: Screen => 1600', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                'auto' => 'auto',
                            ],
                        ),

                        array(
                            'name' => 'slides_to_scroll',
                            'label' => esc_html__('Slides Scroll', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '1',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'arrows',
                            'label' => esc_html__('Show Arrows', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => false,
                        ),
                        array(
                            'name' => 'colorar',
                            'label' => esc_html__('Background Arrow Color', 'graviton' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-swiper-slider .pxl-swiper-arrow-wrap .pxl-swiper-arrow ' => 'background-color: {{VALUE}} !important;',
                                '{{WRAPPER}} .pxl-swiper-slider .pxl-swiper-slide .pxl-post--inner .btn--readmore i ' => 'color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'arrows' => 'true'
                            ]
                        ),
                        array(
                            'name' => 'pagination',
                            'label' => esc_html__('Show Pagination', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => false,
                        ),
                        array(
                            'name' => 'pagination_type',
                            'label' => esc_html__('Pagination Type', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'bullets',
                            'options' => [
                                'bullets' => 'Bullets',
                                'fraction' => 'Fraction',
                                'progressbar' => 'Progressbar',
                            ],
                            'condition' => [
                                'pagination' => 'true'
                            ]
                        ),
                        array(
                            'name' => 'style-pa',
                            'label' => esc_html__('Alignment Bullets', 'medicross' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'center',
                            'options' => [
                                'left' => 'Left',
                                'center' => 'Center',
                                'right' => 'Right',
                            ],
                            'conditions' => [
                                'pagination_type' => 'bullets',
                                'relation' => 'or',
                                'terms' => [
                                    [
                                        'terms' => [
                                            ['name' => 'post_type', 'operator' => '==', 'value' => 'industries'],
                                            ['name' => 'layout_industries', 'operator' => 'in', 'value' => ['industries-1']]
                                        ]
                                    ],
                                    [
                                        'terms' => [
                                            ['name' => 'post_type', 'operator' => '==', 'value' => 'portfolio'],
                                            ['name' => 'layout_portfolio', 'operator' => 'in', 'value' => ['portfolio-1']]
                                        ]
                                    ],
                                ],
                            ],

                        ),
                        array(
                            'name' => 'pause_on_hover',
                            'label' => esc_html__('Pause on Hover', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                        ),
                        array(
                            'name' => 'autoplay',
                            'label' => esc_html__('Autoplay', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => false,
                        ),
                        array(
                            'name' => 'autoplay_speed',
                            'label' => esc_html__('Autoplay Delay', 'medicross'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 5000,
                            'condition' => [
                                'autoplay' => 'false'
                            ]
                        ),
                        array(
                            'name' => 'infinite',
                            'label' => esc_html__('Infinite Loop', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                        ),
                        array(
                            'name' => 'speed',
                            'label' => esc_html__('Animation Speed', 'medicross'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'default' => 500,
                        ),
                        array(
                            'name' => 'drap',
                            'label' => esc_html__('Show Scroll Drap', 'medicross'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => false,
                        ),
                        array(
                            'name' => 'offset_left',
                            'label' => esc_html__('Offset Left', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-swiper-slider .pxl-swiper-container' => 'margin-left: -{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .pxl-carousel-inner' => 'overflow: visible;',
                            ],
                        ),
                        array(
                            'name' => 'offset_right',
                            'label' => esc_html__('Offset Right', 'medicross' ),
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
                                '{{WRAPPER}} .pxl-swiper-slider .pxl-swiper-container' => 'margin-right: -{{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .pxl-carousel-inner' => 'overflow: visible;',
                            ],
                        ),
                    ),
),
array(
    'name' => 'section_display',
    'label' => esc_html__('Display', 'medicross' ),
    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
    'controls' => array(
        array(
            'name'    => 'filter',
            'label'   => esc_html__('Term Filter', 'medicross' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'false',
            'options' => [
                'true'  => esc_html__('Enable', 'medicross' ),
                'false' => esc_html__('Disable', 'medicross' ),
            ],
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'industries'],
                            ['name' => 'layout_industries', 'operator' => 'in', 'value' => ['industries-1']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'portfolio'],
                            ['name' => 'layout_portfolio', 'operator' => 'in', 'value' => ['portfolio-1','portfolio-2']]
                        ]
                    ]
                ],
            ],
        ),
        array(
            'name'      => 'filter_default_title',
            'label'     => esc_html__('Filter Default Title', 'medicross' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => esc_html__('All', 'medicross' ),
            'condition' => [
                'filter'         => 'true',
            ],
        ),
        array(
          'name' => 'align_ft',
            'label' => esc_html__( 'Alignment Filter', 'medicross' ),
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
            ],
            'selectors' => [
                '{{WRAPPER}} .pxl-grid-filter' => 'text-align: {{VALUE}} !important;',
            ],
            'condition' => [
                'filter'         => 'true',
            ],
        ),
        array(
            'name' => 'btn_typography',
            'label' => esc_html__('Typography Filter', 'medicross' ),
            'type' => \Elementor\Group_Control_Typography::get_type(),
            'control_type' => 'group',
            'selector' => '{{WRAPPER}} .filter-item',
            'condition' => [
                'filter'         => 'true',
            ],
        ),
        array(
            'name' => 'color_filter',
            'label' => esc_html__('Filter Color', 'medicross' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .filter-item' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'filter'         => 'true',
            ],
        ),
        array(
            'name' => 'colordv_filter',
            'label' => esc_html__('Filter Under Line Color', 'medicross' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pxl-el-divider' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'filter'         => 'true',
            ],
        ),
        array(
            'name' => 'color_filter_at',
            'label' => esc_html__('Filter Active Color', 'medicross' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .filter-item.active' => 'color: {{VALUE}};',
                '{{WRAPPER}} .filter-item:hover' => 'color: {{VALUE}};',
            ],
            'condition' => [
                'filter'         => 'true',
            ],
        ),
        array(
            'name' => 'padding_right',
            'label' => esc_html__('Widget Padding Right', 'medicross' ),
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
                '{{WRAPPER}} .pxl-swiper-slider' => 'padding-right: {{SIZE}}{{UNIT}};',
            ],
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'service'],
                            ['name' => 'layout_service', 'operator' => 'in', 'value' => ['service-1']]
                        ]
                    ]
                ],
            ],
        ),

        array(
            'name' => 'style_l11',
            'label' => esc_html__('Style', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'pxl-post-style1' => 'Style 1',
                'pxl-post-style2' => 'Style 2',
            ],
            'default' => 'pxl-post-style1',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ],
                ],
            ]
        ),
        array(
            'name' => 'img_size',
            'label' => esc_html__('Image Size', 'medicross' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'description' => 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Default: 370x300 (Width x Height)).',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'industries'],
                            ['name' => 'layout_industries', 'operator' => 'in', 'value' => ['industries-1']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'service'],
                            ['name' => 'layout_service', 'operator' => 'in', 'value' => ['service-1']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'portfolio'],
                            ['name' => 'layout_portfolio', 'operator' => 'in', 'value' => ['portfolio-1','portfolio-2','portfolio-3','portfolio-4']]
                        ]
                    ]
                ],
            ]
        ),
        array(
            'name' => 'show_date',
            'label' => esc_html__('Show Date', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'true',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ]
                ],
            ]
        ),
        array(
            'name' => 'show_category',
            'label' => esc_html__('Show Category', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'true',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ],
                ],
            ]
        ),
        array(
            'name' => 'show_author',
            'label' => esc_html__('Show Author', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'true',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ],
                ],
            ]
        ),
        array(
            'name' => 'show_comment',
            'label' => esc_html__('Show Comment', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'true',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ],
                ],
            ]
        ),
        array(
            'name' => 'show_excerpt',
            'label' => esc_html__('Show Excerpt', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'true',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'service'],
                            ['name' => 'layout_service', 'operator' => 'in', 'value' => ['service-1','service-2','service-3','service-4']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'portfolio'],
                            ['name' => 'layout_portfolio', 'operator' => 'in', 'value' => ['portfolio-1']]
                        ]
                    ]
                ],
            ]
        ),
        array(
            'name' => 'num_words',
            'label' => esc_html__('Number of Words', 'medicross' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 25,
            'separator' => 'after',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'portfolio'],
                            ['name' => 'layout_portfolio', 'operator' => 'in', 'value' => ['portfolio-1','portfolio-2']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'industries'],
                            ['name' => 'layout_industries', 'operator' => 'in', 'value' => ['industries-1']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1','post-2']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'service'],
                            ['name' => 'layout_service', 'operator' => 'in', 'value' => ['service-1','service-2','service-3','service-4']],
                            ['name' => 'show_excerpt', 'operator' => '==', 'value' => 'true'],
                        ]
                    ]
                ],
            ]
        ),
        array(
            'name' => 'show_button',
            'label' => esc_html__('Show Button Readmore', 'medicross' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'true',
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'service'],
                            ['name' => 'layout_service', 'operator' => 'in', 'value' => ['service-1','service-2','service-3','service-4']]
                        ]
                    ]
                ],
            ]
        ),
        array(
            'name' => 'button_text',
            'label' => esc_html__('Button Text', 'medicross' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'post'],
                            ['name' => 'layout_post', 'operator' => 'in', 'value' => ['post-1','post-2']]
                        ]
                    ],
                    [
                        'terms' => [
                            ['name' => 'post_type', 'operator' => '==', 'value' => 'service'],
                            ['name' => 'layout_service', 'operator' => 'in', 'value' => ['service-1','service-2','service-3','service-4']],
                            ['name' => 'show_button', 'operator' => '==', 'value' => 'true']
                        ]
                    ],
                ],
            ]
        ),
    ),
),
array(
    'name' => 'section_style_title',
    'label' => esc_html__('Title', 'medicross'),
    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
    'controls' => array(
        array(
            'name' => 'item_padding',
            'label' => esc_html__('Item Padding', 'medicross' ),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'selectors' => [
                '{{WRAPPER}} .pxl-swiper-slider .pxl-swiper-container' => 'margin-right: -{{RIGHT}}{{UNIT}} !important;  margin-left: -{{LEFT}}{{UNIT}}  !important;',
                '{{WRAPPER}} .pxl-swiper-slider .pxl-swiper-container .pxl-swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
            'control_type' => 'responsive',
        ),
        array(
            'name' => 'title_color',
            'label' => esc_html__('Color', 'medicross' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                'body {{WRAPPER}} .pxl-swiper-slider .pxl-item--title,body {{WRAPPER}} .pxl-swiper-slider .pxl-item--title a,body {{WRAPPER}} .pxl-swiper-slider .pxl-post--title ,body {{WRAPPER}} .pxl-swiper-slider .pxl-post--title a' => 'color: {{VALUE}};',
            ],
        ),
        array(
            'name' => 'title_typography',
            'label' => esc_html__('Typography', 'medicross' ),
            'type' => \Elementor\Group_Control_Typography::get_type(),
            'control_type' => 'group',
            'selector' => 'body {{WRAPPER}} .pxl-swiper-slider .pxl-item--title,body {{WRAPPER}} .pxl-swiper-slider .pxl-item--title a,body {{WRAPPER}} .pxl-swiper-slider .pxl-post--title ,body {{WRAPPER}} .pxl-swiper-slider .pxl-post--title a',
        ),
    ),
),
array(
    'name' => 'section_style_excerpt',
    'label' => esc_html__('Excerpt', 'medicross'),
    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
    'conditions' => [
        'relation' => 'or',
        'terms' => [
            [
                'terms' => [
                    ['name' => 'post_type', 'operator' => '==', 'value' => 'service'],
                    ['name' => 'layout_service', 'operator' => 'in', 'value' => ['service-1']]
                ]
            ]
        ],
    ],
    'controls' => array(
        array(
            'name' => 'excerpt_color',
            'label' => esc_html__('Color', 'medicross' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .pxl-swiper-slider .pxl-post--content' => 'color: {{VALUE}};',
            ],
        ),
        array(
            'name' => 'excerpt_typography',
            'label' => esc_html__('Typography', 'medicross' ),
            'type' => \Elementor\Group_Control_Typography::get_type(),
            'control_type' => 'group',
            'selector' => '{{WRAPPER}} .pxl-swiper-slider .pxl-post--content',
        ),
    ),
),
array(
    'name' => 'box-content',
    'label' => esc_html__('Box Content', 'medicross'),
    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
    'conditions' => [
        'relation' => 'or',
        'terms' => [
            [
                'terms' => [
                    ['name' => 'post_type', 'operator' => '==', 'value' => 'portfolio'],
                    ['name' => 'layout_portfolio', 'operator' => 'in', 'value' => ['portfolio-4']]
                ]
            ]
        ],
    ],
    'controls' => array(
        array(
            'name' => 'sub_title_box',
            'label' => esc_html__('Sub Title', 'medicross' ),
            'type' => \Elementor\Controls_Manager::TEXT,
        ),
        array(
            'name' => 'title_box',
            'label' => esc_html__('Title', 'medicross' ),
            'type' => \Elementor\Controls_Manager::TEXT,
        ),
        array(
            'name' => 'desc_box',
            'label' => esc_html__('Description', 'medicross' ),
            'type' => \Elementor\Controls_Manager::TEXT,
        ),
        array(
            'name' => 'btn_text',
            'label' => esc_html__('Button Text', 'medicross' ),
            'type' => \Elementor\Controls_Manager::TEXT,
        ),

        array(
            'name' => 'btn_link',
            'label' => esc_html__('Button Link', 'medicross' ),
            'type' => \Elementor\Controls_Manager::URL,
        ),
    ),
),
),
),
),
medicross_get_class_widget_path()
);

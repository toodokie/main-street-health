<?php
$pt_supports = ['post'];
use Elementor\Controls_Manager;
pxl_add_custom_widget(
    array(
        'name'       => 'pxl_post_list',
        'title'      => esc_html__('Case Post List', 'medicross' ),
        'icon'       => 'eicon-post-list',
        'categories' => array('pxltheme-core'),
        'scripts'    => [
            'pxl-post-grid',
        ],
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
                        medicross_get_post_list_layout($pt_supports)
                    ),
                ),
                array(
                    'name' => 'source_section',
                    'label' => esc_html__('Source', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
                        medicross_get_term_by_post_type($pt_supports, ['custom_condition' => ['select_post_by' => 'term_selected']]),
                        medicross_get_ids_by_post_type($pt_supports, ['custom_condition' => ['select_post_by' => 'post_selected']]),
                        medicross_get_ids_unselected_by_post_type($pt_supports, ['custom_condition' => ['select_post_by' => 'term_selected']]),
                        array(
                            array(
                                'name'    => 'orderby',
                                'label'   => esc_html__('Order By', 'medicross' ),
                                'type'    => \Elementor\Controls_Manager::SELECT,
                                'default' => 'date',
                                'options' => [
                                    'date'   => esc_html__('Date', 'medicross' ),
                                    'ID'     => esc_html__('ID', 'medicross' ),
                                    'author' => esc_html__('Author', 'medicross' ),
                                    'title'  => esc_html__('Title', 'medicross' ),
                                    'rand'   => esc_html__('Random', 'medicross' ),
                                ],
                            ),
                            array(
                                'name'    => 'order',
                                'label'   => esc_html__('Sort Order', 'medicross' ),
                                'type'    => \Elementor\Controls_Manager::SELECT,
                                'default' => 'desc',
                                'options' => [
                                    'desc' => esc_html__('Descending', 'medicross' ),
                                    'asc'  => esc_html__('Ascending', 'medicross' ),
                                ],
                            ),
                            array(
                                'name'    => 'limit',
                                'label'   => esc_html__('Total items', 'medicross' ),
                                'type'    => \Elementor\Controls_Manager::NUMBER,
                                'default' => '6',
                            ),
                        )
                    ),
                ),
                array(
                    'name' => 'general_section',
                    'label' => esc_html__('General Settings', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array_merge(
                        array(
                            array(
                                'name'    => 'show_toolbar',
                                'label'   => esc_html__('Show Toolbar', 'medicross' ),
                                'type'    => \Elementor\Controls_Manager::SELECT,
                                'default' => 'hide',
                                'options' => [
                                    'show' => esc_html__('Show', 'medicross' ),
                                    'hide'   => esc_html__('Hide', 'medicross' )
                                ],
                                'condition' => ['post_type' => 'post','layout_post' => 'post-list-1' ],
                            ),
                            array(
                                'name'        => 'img_size',
                                'label'       => esc_html__('Image Size', 'medicross' ),
                                'type'        => \Elementor\Controls_Manager::TEXT,
                                'description' =>  esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Default: full).', 'medicross')
                            ),
                            array(
                                'name'    => 'pagination_type',
                                'label'   => esc_html__('Pagination Type', 'medicross' ),
                                'type'    => \Elementor\Controls_Manager::SELECT,
                                'default' => 'false',
                                'options' => [
                                    'pagination' => esc_html__('Pagination', 'medicross' ),
                                    'loadmore'   => esc_html__('Loadmore', 'medicross' ),
                                    'false'      => esc_html__('Disable', 'medicross' ),
                                ],
                            ),
                            array(
                                'name'      => 'loadmore_text',
                                'label'     => esc_html__( 'Load More text', 'medicross' ),
                                'type'      => \Elementor\Controls_Manager::TEXT,
                                'default'   => esc_html__('Load More','medicross'),
                                'condition' => [
                                    'pagination_type' => 'loadmore'
                                ]
                            ),
                            array(
                                'name'         => 'pagination_alignment',
                                'label'        => esc_html__( 'Pagination Alignment', 'medicross' ),
                                'type'         => 'choose',
                                'control_type' => 'responsive',
                                'options' => [
                                    'start' => [
                                        'title' => esc_html__( 'Start', 'medicross' ),
                                        'icon'  => 'eicon-text-align-left',
                                    ],
                                    'center' => [
                                        'title' => esc_html__( 'Center', 'medicross' ),
                                        'icon'  => 'eicon-text-align-center',
                                    ],
                                    'end' => [
                                        'title' => esc_html__( 'End', 'medicross' ),
                                        'icon'  => 'eicon-text-align-right',
                                    ]
                                ],
                                'selectors' => [
                                    '{{WRAPPER}} .pxl-grid-pagination, {{WRAPPER}} .pxl-load-more' => 'justify-content: {{VALUE}};'
                                ],
                                'default'      => 'start',
                                'condition' => [
                                    'pagination_type' => ['pagination', 'loadmore'],
                                ],
                            ),
                            array(
                                'name' => 'title_hover',
                                'label' => esc_html__('Title Color', 'medicross' ),
                                'type' => 'color',
                                'selectors' => [
                                    '{{WRAPPER}} .layout-post-list-2 .item-content .item-title' => 'color: {{VALUE}};'
                                ],
                                'condition' => ['post_type' => 'post','layout_post' => 'post-list-2' ],
                            ), 
                            array(
                                'name' => 'title_hover_color',
                                'label' => esc_html__('Title Hover Color', 'medicross' ),
                                'type' => 'color',
                                'selectors' => [
                                    '{{WRAPPER}} .layout-post-list-2 .item-content .item-title:hover' => 'color: {{VALUE}};'
                                ],
                                'condition' => ['post_type' => 'post','layout_post' => 'post-list-2' ],
                            ), 
                        ),
                    )
                ),
                array(
                    'name' => 'display_post_section',
                    'label' => esc_html__('Display Options', 'medicross' ),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name'      => 'post_date',
                            'label'     => esc_html__('Show Date', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::SWITCHER,
                            'default'   => 'true',
                        ),
                        array(
                            'name'      => 'post_author',
                            'label'     => esc_html__('Show Author', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::SWITCHER,
                            'default'   => 'true',
                        ),
                        array(
                            'name'      => 'post_category',
                            'label'     => esc_html__('Show Category', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::SWITCHER,
                            'default'   => 'false',
                        ),
                        array(
                            'name'      => 'post_comment',
                            'label'     => esc_html__('Show Comment', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::SWITCHER,
                            'default'   => 'true',
                        ),
                        array(
                            'name'      => 'post_excerpt',
                            'label'     => esc_html__('Show Excerpt', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::SWITCHER,
                            'default'   => 'true',
                        ),
                        array(
                            'name'      => 'post_num_words',
                            'label'     => esc_html__('Number of Words', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::NUMBER,
                            'condition' => [
                                'post_excerpt' => 'true',
                            ],
                        ),
                        array(
                            'name'      => 'post_readmore',
                            'label'     => esc_html__('Show Readmore', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::SWITCHER,
                            'default'   => 'true',
                        ),
                        array(
                            'name'      => 'post_readmore_text',
                            'label'     => esc_html__('Button Text', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'post_readmore' => 'true',
                            ],
                        ),
                        array(
                            'name'      => 'post_share',
                            'label'     => esc_html__('Show Social Share', 'medicross' ),
                            'type'      => \Elementor\Controls_Manager::SWITCHER,
                            'default'   => 'true',
                        ),
                    ),
                    'condition' => ['post_type' => 'post','layout_post' => 'post-list-1' ],
                ),
                 
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
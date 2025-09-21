<?php
    pxl_add_custom_widget(
        array(
            'name' => 'pxl_post_navigation',
            'title' => esc_html__('Case  Post Navigation', 'medicross' ),
            'icon' => 'eicon-navigation-horizontal',
            'categories' => array('pxltheme-core'),
            'params' => array(
                'sections' => array(
                    array(
                        'name' => 'section_content',
                        'label' => esc_html__('Content', 'medicross' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                        'controls' => array(
                            array (
                                'name' => 'type',
                                'label' => esc_html__('Type', 'medicross'),
                                'type' => Elementor\Controls_Manager::SELECT,
                                'default' => 'pagination',
                                'options' => [
                                    'navigation' => esc_html__('Navigation', 'medicross'),
                                ]
                            ),
                            array(
                                'name' => 'link_grid_page',
                                'label' => esc_html__('Link Gird Page', 'medicross' ),
                                'type' => \Elementor\Controls_Manager::TEXT,
                                'default' => esc_html__('#', 'medicross'),
                            ),
                        ),
                    ),
                ),
            ),
        ),
        medicross_get_class_widget_path()
    )
?>
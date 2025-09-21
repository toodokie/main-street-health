<?php
/**
 * MSH Testimonial Carousel Widget
 * Custom Elementor widget for testimonial carousel with full controls
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class MSH_Testimonial_Carousel_Widget extends Widget_Base {

    public function get_name() {
        return 'msh_testimonial_carousel';
    }

    public function get_title() {
        return __('MSH Testimonial Carousel', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }

    public function get_categories() {
        return ['msh-widgets'];
    }

    public function get_keywords() {
        return ['testimonial', 'carousel', 'review', 'quote', 'slider'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Testimonials', 'medicross-child'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'testimonial_content',
            [
                'label' => __('Testimonial Content', 'medicross-child'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Working with you was the single best decision of my career. Staff couldn\'t have been more pleasant, friendly and accommodating.', 'medicross-child'),
                'rows' => 5,
            ]
        );

        $repeater->add_control(
            'client_name',
            [
                'label' => __('Client Name', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Jonathan Calure, MD', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'client_position',
            [
                'label' => __('Client Position/Company', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Maryland Vein Professionals', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'client_image',
            [
                'label' => __('Client Image', 'medicross-child'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $repeater->add_control(
            'show_quote_icon',
            [
                'label' => __('Show Quote Icon', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'testimonials_list',
            [
                'label' => __('Testimonials', 'medicross-child'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'testimonial_content' => __('I am writing on behalf of my brother who was a patient in your hospital. I would like to thank you on behalf of my entire family for the help and consideration shown to me in what was a very difficult time.', 'medicross-child'),
                        'client_name' => __('Mrs. Christina Blodgett-Dycus', 'medicross-child'),
                        'client_position' => __('Vein Specialist, Eterna Vein', 'medicross-child'),
                    ],
                    [
                        'testimonial_content' => __('Working with you was the single best decision of my career. Staff couldn\'t have been more pleasant, friendly and accommodating. Took a stressful situation and made it a very good experience.', 'medicross-child'),
                        'client_name' => __('Jonathan Calure, MD', 'medicross-child'),
                        'client_position' => __('Maryland Vein Professionals', 'medicross-child'),
                    ],
                ],
                'title_field' => '{{{ client_name }}}',
            ]
        );

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'carousel_settings',
            [
                'label' => __('Carousel Settings', 'medicross-child'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __('Slides to Show', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ],
            ]
        );

        $this->add_control(
            'slides_to_scroll',
            [
                'label' => __('Slides to Scroll', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'medicross-child'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'min' => 1000,
                'max' => 10000,
                'step' => 500,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => __('Transition Speed (ms)', 'medicross-child'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'min' => 100,
                'max' => 3000,
                'step' => 100,
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => __('Space Between Slides', 'medicross-child'),
                'type' => Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 0,
                'max' => 100,
                'step' => 10,
            ]
        );

        $this->add_control(
            'centered_slides',
            [
                'label' => __('Center Active Slide', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'infinite',
            [
                'label' => __('Infinite Loop', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __('Show Arrows', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => __('Show Dots', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Card Style Section
        $this->start_controls_section(
            'card_style_section',
            [
                'label' => __('Card Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => __('Background', 'medicross-child'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .msh-testimonial-card',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#ffffff',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => __('Padding', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '40',
                    'right' => '30',
                    'bottom' => '40',
                    'left' => '30',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-testimonial-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_margin',
            [
                'label' => __('Margin', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '15',
                    'bottom' => '30',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-testimonial-slide' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => __('Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-testimonial-card',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#e8e8e8',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-testimonial-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'label' => __('Box Shadow', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-testimonial-card',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 5,
                            'blur' => 20,
                            'spread' => 0,
                            'color' => 'rgba(0,0,0,0.1)',
                        ],
                    ],
                ],
            ]
        );

        // Hover Effects
        $this->add_control(
            'card_hover_effects',
            [
                'label' => __('Hover Effects', 'medicross-child'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animation', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'lift',
                'options' => [
                    'none' => __('None', 'medicross-child'),
                    'lift' => __('Lift Up', 'medicross-child'),
                    'scale' => __('Scale', 'medicross-child'),
                    'tilt' => __('Tilt', 'medicross-child'),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_hover_shadow',
                'label' => __('Hover Shadow', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-testimonial-card:hover',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 15,
                            'blur' => 35,
                            'spread' => 0,
                            'color' => 'rgba(0,0,0,0.15)',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // Quote Icon Style
        $this->start_controls_section(
            'quote_icon_style',
            [
                'label' => __('Quote Icon Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'quote_size',
            [
                'label' => __('Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 80,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-quote-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __('Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .msh-quote-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_margin',
            [
                'label' => __('Margin', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '20',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-quote-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'quote_alignment',
            [
                'label' => __('Alignment', 'medicross-child'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'medicross-child'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'medicross-child'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'medicross-child'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .msh-quote-icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Style
        $this->start_controls_section(
            'content_style_section',
            [
                'label' => __('Content Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __('Typography', 'medicross-child'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .msh-testimonial-content',
                'fields_options' => [
                    'typography' => [
                        'default' => 'yes',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 16,
                        ],
                    ],
                    'line_height' => [
                        'default' => [
                            'unit' => 'em',
                            'size' => 1.6,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __('Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .msh-testimonial-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => __('Margin', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '30',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-testimonial-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_alignment',
            [
                'label' => __('Alignment', 'medicross-child'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'medicross-child'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'medicross-child'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'medicross-child'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'medicross-child'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .msh-testimonial-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Client Image Style
        $this->start_controls_section(
            'client_image_style',
            [
                'label' => __('Client Image Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 120,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 70,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-client-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __('Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-client-image',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '3',
                            'right' => '3',
                            'bottom' => '3',
                            'left' => '3',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#DBAA17',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '50',
                    'right' => '50',
                    'bottom' => '50',
                    'left' => '50',
                    'unit' => '%',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-client-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label' => __('Margin', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '15',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-client-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Client Name Style
        $this->start_controls_section(
            'client_name_style',
            [
                'label' => __('Client Name Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __('Typography', 'medicross-child'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .msh-client-name',
                'fields_options' => [
                    'typography' => [
                        'default' => 'yes',
                    ],
                    'font_weight' => [
                        'default' => '600',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 18,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .msh-client-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'name_margin',
            [
                'label' => __('Margin', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '5',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-client-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Client Position Style
        $this->start_controls_section(
            'client_position_style',
            [
                'label' => __('Client Position Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'position_typography',
                'label' => __('Typography', 'medicross-child'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .msh-client-position',
                'fields_options' => [
                    'typography' => [
                        'default' => 'yes',
                    ],
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 14,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'position_color',
            [
                'label' => __('Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .msh-client-position' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'navigation_style',
            [
                'label' => __('Navigation Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Arrows
        $this->add_control(
            'arrows_heading',
            [
                'label' => __('Arrows', 'medicross-child'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrows_size',
            [
                'label' => __('Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 60,
                        'step' => 2,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; font-size: calc({{SIZE}}{{UNIT}} / 2.5) !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_color',
            [
                'label' => __('Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_hover_color',
            [
                'label' => __('Hover Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#DBAA17',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrows_bg_color',
            [
                'label' => __('Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(45, 62, 78, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'background: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrows_bg_hover_color',
            [
                'label' => __('Background Hover Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(33, 142, 156, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'background: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrows_border_width',
            [
                'label' => __('Border Width', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'border-width: {{SIZE}}{{UNIT}} !important; border-style: solid !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrows_border_color',
            [
                'label' => __('Border Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(45, 62, 78, 0.2)',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrows_border_hover_color',
            [
                'label' => __('Border Hover Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#218E9C',
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev:hover, {{WRAPPER}} .swiper-button-next:hover' => 'border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrows_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-button-prev, {{WRAPPER}} .swiper-button-next' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'show_arrows' => 'yes',
                ],
            ]
        );

        // Dots
        $this->add_control(
            'dots_heading',
            [
                'label' => __('Dots', 'medicross-child'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label' => __('Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_color',
            [
                'label' => __('Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots_active_color',
            [
                'label' => __('Active Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_spacing',
            [
                'label' => __('Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'show_dots' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();

        if (empty($settings['testimonials_list'])) {
            return;
        }

        // Calculate initial slide (middle of the carousel)
        $total_slides = count($settings['testimonials_list']);
        $initial_slide = floor($total_slides / 2);

        // Carousel configuration
        $carousel_config = [
            'slidesToShow' => intval($settings['slides_to_show'] ?? 3),
            'slidesToShowTablet' => intval($settings['slides_to_show_tablet'] ?? 2),
            'slidesToShowMobile' => intval($settings['slides_to_show_mobile'] ?? 1),
            'slidesToScroll' => intval($settings['slides_to_scroll'] ?? 1),
            'autoplay' => ($settings['autoplay'] ?? 'no') === 'yes',
            'autoplaySpeed' => intval($settings['autoplay_speed'] ?? 5000),
            'infinite' => ($settings['infinite'] ?? 'yes') === 'yes',
            'arrows' => ($settings['show_arrows'] ?? 'no') === 'yes',
            'dots' => ($settings['show_dots'] ?? 'yes') === 'yes',
            'initialSlide' => $initial_slide,
            'responsive' => [
                [
                    'breakpoint' => 1024,
                    'settings' => [
                        'slidesToShow' => intval($settings['slides_to_show_tablet'] ?? 2),
                    ]
                ],
                [
                    'breakpoint' => 767,
                    'settings' => [
                        'slidesToShow' => intval($settings['slides_to_show_mobile'] ?? 1),
                    ]
                ]
            ]
        ];

        ?>
        <div class="msh-testimonial-carousel" id="msh-testimonial-<?php echo esc_attr($widget_id); ?>">
            <div class="swiper" 
                 data-slides-per-view="<?php echo esc_attr($settings['slides_to_show'] ?? '3'); ?>"
                 data-slides-per-view-tablet="<?php echo esc_attr($settings['slides_to_show_tablet'] ?? '2'); ?>"
                 data-slides-per-view-mobile="<?php echo esc_attr($settings['slides_to_show_mobile'] ?? '1'); ?>"
                 data-space-between="<?php echo esc_attr($settings['space_between'] ?? '28'); ?>"
                 data-autoplay="<?php echo esc_attr($settings['autoplay'] ?? 'yes'); ?>"
                 data-autoplay-delay="<?php echo esc_attr($settings['autoplay_speed'] ?? '5000'); ?>"
                 data-loop="<?php echo esc_attr($settings['infinite'] ?? 'yes'); ?>"
                 data-speed="<?php echo esc_attr($settings['speed'] ?? '500'); ?>"
                 data-navigation="<?php echo esc_attr($settings['show_arrows'] ?? ''); ?>"
                 data-pagination="<?php echo esc_attr($settings['show_dots'] ?? 'yes'); ?>"
                 data-centered-slides="<?php echo esc_attr($settings['centered_slides'] ?? 'yes'); ?>"
                 data-initial-slide="<?php echo esc_attr($initial_slide); ?>">
                
                <div class="swiper-wrapper">
                    <?php foreach ($settings['testimonials_list'] as $index => $item) : ?>
                        <div class="swiper-slide">
                            <div class="msh-testimonial-card">
                                <?php if ($item['show_quote_icon'] === 'yes') : ?>
                                    <div class="msh-quote-icon">
                                        <i class="fas fa-quote-left" aria-hidden="true"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="msh-testimonial-content">
                                    <?php echo wp_kses_post($item['testimonial_content']); ?>
                                </div>
                                
                                <div class="msh-client-info">
                                    <?php if (!empty($item['client_image']['url'])) : ?>
                                        <div class="msh-client-image">
                                            <img src="<?php echo esc_url($item['client_image']['url']); ?>" 
                                                 alt="<?php echo esc_attr($item['client_name']); ?>">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="msh-client-details">
                                        <?php if (!empty($item['client_name'])) : ?>
                                            <h4 class="msh-client-name"><?php echo esc_html($item['client_name']); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($item['client_position'])) : ?>
                                            <p class="msh-client-position"><?php echo esc_html($item['client_position']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
            
            <!-- Navigation container -->
            <div class="msh-testimonial-carousel-nav">
                <!-- Pagination dots -->
                <?php if ($settings['show_dots'] === 'yes') : ?>
                    <div class="swiper-pagination">
                        <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) : ?>
                            <!-- Show static dots in editor for preview -->
                            <?php 
                            $num_dots = min(count($settings['testimonials_list']), 5);
                            for ($i = 0; $i < $num_dots; $i++) : ?>
                                <span class="swiper-pagination-bullet <?php echo $i === 0 ? 'swiper-pagination-bullet-active' : ''; ?>"></span>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Navigation arrows -->
                <?php if ($settings['show_arrows'] === 'yes') : ?>
                    <div class="msh-testimonial-carousel-arrows">
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

// Register the widget
add_action('elementor/widgets/widgets_registered', function() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new MSH_Testimonial_Carousel_Widget());
});
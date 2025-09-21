<?php
/**
 * Main Street Health Custom Services List Widget
 * Manual entries with title, description, and link
 * 
 * @package medicross-child
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Elementor\Repeater;

class MSH_Services_List_Widget extends Widget_Base {

    public function get_name() {
        return 'msh_services_list';
    }

    public function get_title() {
        return __('MSH Services List', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-bullet-list';
    }

    public function get_categories() {
        return ['pxltheme-core'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Services List', 'medicross-child'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'service_title',
            [
                'label' => __('Title', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Service Title', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'service_description',
            [
                'label' => __('Description', 'medicross-child'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __('Service description goes here', 'medicross-child'),
                'description' => __('Use the rich text editor to add lists, bold text, links, and other formatting', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'explainer_text',
            [
                'label' => __('Explainer Text', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Insurance: May be covered', 'medicross-child'),
                'description' => __('Optional small text to display between description and link', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'link_text',
            [
                'label' => __('Link Text', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Learn more', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'link_url',
            [
                'label' => __('Link URL', 'medicross-child'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'medicross-child'),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'link_icon',
            [
                'label' => __('Link Icon', 'medicross-child'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ],
            ]
        );

        $repeater->add_control(
            'media_type',
            [
                'label' => __('Media Type', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => __('Icon', 'medicross-child'),
                    'image' => __('Image', 'medicross-child'),
                    'none' => __('None', 'medicross-child'),
                ],
            ]
        );

        $repeater->add_control(
            'service_icon',
            [
                'label' => __('Service Icon', 'medicross-child'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],
                'condition' => [
                    'media_type' => 'icon',
                ],
            ]
        );

        $repeater->add_control(
            'service_image',
            [
                'label' => __('Service Image', 'medicross-child'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'media_type' => 'image',
                ],
            ]
        );

        $repeater->add_control(
            'cover_image',
            [
                'label' => __('Cover Image', 'medicross-child'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'description' => __('Optional background cover image for the entire card', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'cover_overlay',
            [
                'label' => __('Cover Overlay Opacity', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 0,
                ],
                'condition' => [
                    'cover_image[url]!' => '',
                ],
                'description' => __('Dark overlay opacity for better text readability', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'show_divider',
            [
                'label' => __('Show Divider After', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'services_list',
            [
                'label' => __('Services', 'medicross-child'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'service_title' => __('Preoperative and Postoperative Assessments', 'medicross-child'),
                        'service_description' => __('Prepare for surgery or monitor your recovery with evidence-based assessment tools.', 'medicross-child'),
                        'link_text' => __('Learn more', 'medicross-child'),
                    ],
                    [
                        'service_title' => __('Occupational Health & Wellness Assessments', 'medicross-child'),
                        'service_description' => __('Screenings and evaluations to support return-to-work planning and workplace readiness.', 'medicross-child'),
                        'link_text' => __('Learn more', 'medicross-child'),
                    ],
                ],
                'title_field' => '{{{ service_title }}}',
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'medicross-child'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => __('Layout Type', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'list',
                'options' => [
                    'list' => __('List (stacked)', 'medicross-child'),
                    'grid' => __('Grid (cards)', 'medicross-child'),
                    'horizontal' => __('Horizontal (table-like)', 'medicross-child'),
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'condition' => [
                    'layout_type' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => __('Column Gap', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'condition' => [
                    'layout_type' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => __('Row Gap', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'condition' => [
                    'layout_type' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'horizontal_item_spacing',
            [
                'label' => __('Item Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'condition' => [
                    'layout_type' => 'horizontal',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-horizontal-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-service-horizontal-item:last-child' => 'margin-bottom: 0;',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Style Section
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => __('Title Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                ],
                'description' => __('Select the HTML heading tag for the title', 'medicross-child'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Bottom Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        // Description Style Section
        $this->start_controls_section(
            'description_style_section',
            [
                'label' => __('Description Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __('Description Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-description',
            ]
        );

        $this->add_responsive_control(
            'description_spacing',
            [
                'label' => __('Bottom Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        // Explainer Text Style Section
        $this->start_controls_section(
            'explainer_style_section',
            [
                'label' => __('Explainer Text Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'explainer_color',
            [
                'label' => __('Explainer Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#218E9C',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-explainer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'explainer_typography',
                'label' => __('Explainer Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-explainer',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'size' => 14,
                            'unit' => 'px',
                        ],
                    ],
                    'font_weight' => [
                        'default' => '500',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'explainer_spacing',
            [
                'label' => __('Bottom Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 12,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-explainer' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Link Style Section
        $this->start_controls_section(
            'link_style_section',
            [
                'label' => __('Link Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'link_type',
            [
                'label' => __('Link Type', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => __('Text Link', 'medicross-child'),
                    'button' => __('Button', 'medicross-child'),
                ],
                'description' => __('Choose between text link or button style', 'medicross-child'),
            ]
        );

        $this->add_control(
            'link_position',
            [
                'label' => __('Link Position', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => __('Below description text', 'medicross-child'),
                    'stacked-left' => __('Bottom of card (Left aligned)', 'medicross-child'),
                    'stacked-center' => __('Bottom of card (Center aligned)', 'medicross-child'),
                ],
            ]
        );

        $this->start_controls_tabs('link_style_tabs');

        $this->start_controls_tab(
            'link_normal_tab',
            [
                'label' => __('Normal', 'medicross-child'),
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_icon_color',
            [
                'label' => __('Icon Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-link svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_hover_tab',
            [
                'label' => __('Hover', 'medicross-child'),
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#DBAA17',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link:not(.link-button):hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-link:not(.link-button)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#DBAA17',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link:not(.link-button):hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-link:not(.link-button):hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-link:not(.link-button) i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-link:not(.link-button) svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'label' => __('Link Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-link',
            ]
        );

        $this->add_responsive_control(
            'link_icon_spacing',
            [
                'label' => __('Icon Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 8,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Button Style Controls
        $this->add_control(
            'button_style_heading',
            [
                'label' => __('Button Style', 'medicross-child'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'link_type' => 'button',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Button Padding', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link.link-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'link_type' => 'button',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => __('Button Background', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link.link-button' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'link_type' => 'button',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background',
            [
                'label' => __('Button Hover Background', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#DBAA17',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link.link-button:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ],
                'condition' => [
                    'link_type' => 'button',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'label' => __('Button Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-link.link-button',
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
                            'unit' => 'px',
                        ],
                    ],
                    'color' => [
                        'default' => '#e0e0e0',
                    ],
                ],
                'condition' => [
                    'link_type' => 'button',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Button Border Radius', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '24',
                    'right' => '24',
                    'bottom' => '24',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link.link-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'link_type' => 'button',
                ],
            ]
        );

        $this->end_controls_section();

        // Divider Style Section
        $this->start_controls_section(
            'divider_style_section',
            [
                'label' => __('Divider Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'divider_style',
            [
                'label' => __('Divider Style', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'none' => __('None (No Divider)', 'medicross-child'),
                    'solid' => __('Solid', 'medicross-child'),
                    'dashed' => __('Dashed', 'medicross-child'),
                    'dotted' => __('Dotted', 'medicross-child'),
                    'double' => __('Double', 'medicross-child'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-divider' => 'border-bottom-style: {{VALUE}}; display: block;',
                    '{{WRAPPER}} .msh-service-divider[data-style="none"]' => 'display: none !important;',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => __('Divider Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#DBAA17',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-divider' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_width',
            [
                'label' => __('Divider Thickness', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 2,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'divider_spacing',
            [
                'label' => __('Divider Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-divider' => 'margin: {{SIZE}}{{UNIT}} 0;',
                ],
            ]
        );

        $this->end_controls_section();

        // Item Style Section
        $this->start_controls_section(
            'item_style_section',
            [
                'label' => __('Item Container', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __('Padding', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '25',
                    'right' => '25',
                    'bottom' => '25',
                    'left' => '25',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_background',
            [
                'label' => __('Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'item_hover_background',
            [
                'label' => __('Hover Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'label' => __('Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-entry',
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // DISABLED - Box shadow causing white overlay issues
        /*
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'label' => __('Box Shadow', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-entry',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'no', // Changed to NO
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 0,
                            'blur' => 0,
                            'spread' => 0,
                            'color' => 'transparent',
                        ],
                    ],
                ],
            ]
        );
        */

        $this->end_controls_section();

        // Image/Icon Style Section
        $this->start_controls_section(
            'icon_image_style_section',
            [
                'label' => __('Icon & Image Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'media_position',
            [
                'label' => __('Media Position', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Left (horizontal)', 'medicross-child'),
                    'right' => __('Right (horizontal)', 'medicross-child'),
                    'top' => __('Top (vertical left aligned)', 'medicross-child'),
                    'center' => __('Top (vertical center aligned)', 'medicross-child'),
                ],
            ]
        );


        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-service-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_color',
            [
                'label' => __('Icon & Image Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2D3E4E',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-image' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-image svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-image svg *' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __('Image Width', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-image' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __('Image Height', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __('Image/Icon Padding', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 10,
                    'bottom' => 10,
                    'left' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .msh-service-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'media_spacing',
            [
                'label' => __('Spacing from Content', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry.media-left .msh-service-icon, {{WRAPPER}} .msh-service-entry.media-left .msh-service-image' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-service-entry.media-above-left .msh-service-icon, {{WRAPPER}} .msh-service-entry.media-above-left .msh-service-image, {{WRAPPER}} .msh-service-entry.media-above-center .msh-service-icon, {{WRAPPER}} .msh-service-entry.media-above-center .msh-service-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Icon Background Controls
        $this->add_control(
            'icon_background_heading',
            [
                'label' => __('Icon Background', 'medicross-child'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_background_shape',
            [
                'label' => __('Background Shape', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => [
                    'none' => __('None', 'medicross-child'),
                    'circle' => __('Circle', 'medicross-child'),
                    'rounded' => __('Rounded Rectangle', 'medicross-child'),
                    'square' => __('Square', 'medicross-child'),
                ],
            ]
        );

        $this->add_control(
            'icon_background_color',
            [
                'label' => __('Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_background_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_background_color',
            [
                'label' => __('Hover Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#218E9C',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_background_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_border_width',
            [
                'label' => __('Border Width', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-icon' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
                'condition' => [
                    'icon_background_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color',
            [
                'label' => __('Border Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#e0e0e0',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_background_shape!' => 'none',
                    'icon_border_width[size]!' => '0',
                ],
            ]
        );

        $this->add_control(
            'icon_border_hover_color',
            [
                'label' => __('Border Hover Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#218E9C',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_background_shape!' => 'none',
                    'icon_border_width[size]!' => '0',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-icon.shape-circle' => 'border-radius: 50% !important;',
                    '{{WRAPPER}} .msh-service-icon.shape-square' => 'border-radius: 0 !important;',
                    '{{WRAPPER}} .msh-service-icon.shape-rounded' => 'border-radius: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'icon_background_shape' => 'rounded',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_container_size',
            [
                'label' => __('Container Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 64,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon_background_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Icon Hover Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_alignment',
            [
                'label' => __('Content Alignment', 'medicross-child'),
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
                    '{{WRAPPER}} .msh-service-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'link_alignment',
            [
                'label' => __('Link Alignment', 'medicross-child'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Left', 'medicross-child'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'medicross-child'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('Right', 'medicross-child'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-end',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-link-wrapper' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'layout_type',
            [
                'label' => __('Layout Type', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => __('Horizontal (Title | Description | Link)', 'medicross-child'),
                    'column' => __('Vertical (Stacked)', 'medicross-child'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-content' => 'flex-direction: {{VALUE}};',
                ],
                'prefix_class' => 'msh-layout-',
            ]
        );

        $this->add_responsive_control(
            'image_position',
            [
                'label' => __('Image Position', 'medicross-child'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => __('Left', 'medicross-child'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'row-reverse' => [
                        'title' => __('Right', 'medicross-child'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'column' => [
                        'title' => __('Top', 'medicross-child'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'column-reverse' => [
                        'title' => __('Bottom', 'medicross-child'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_size',
            [
                'label' => __('Image Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 200,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-service-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        // Image Background Controls
        $this->add_control(
            'image_background_heading',
            [
                'label' => __('Image Background', 'medicross-child'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_background_shape',
            [
                'label' => __('Image Background Shape', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'rounded',
                'options' => [
                    'none' => __('None', 'medicross-child'),
                    'rounded' => __('Rounded Rectangle', 'medicross-child'),
                    'circle' => __('Circle', 'medicross-child'),
                    'square' => __('Square', 'medicross-child'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-image.shape-none' => 'background: none; padding: 0;',
                    '{{WRAPPER}} .msh-service-image.shape-rounded' => 'border-radius: 8px;',
                    '{{WRAPPER}} .msh-service-image.shape-circle' => 'border-radius: 50%;',
                    '{{WRAPPER}} .msh-service-image.shape-square' => 'border-radius: 0;',
                ],
                'prefix_class' => 'image-shape-',
            ]
        );

        $this->add_control(
            'image_background_color',
            [
                'label' => __('Image Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-image:not(.shape-none)' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'image_background_shape!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label' => __('Image Padding', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-service-image:not(.shape-none)' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'image_background_shape!' => 'none',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => __('Image Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-service-image:not(.shape-none)',
                'condition' => [
                    'image_background_shape!' => 'none',
                ],
            ]
        );

        $this->add_control(
            'image_hover_background_color',
            [
                'label' => __('Hover Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#DBAA17',
                'selectors' => [
                    '{{WRAPPER}} .msh-service-entry:hover .msh-service-image:not(.shape-none)' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'image_background_shape!' => 'none',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['services_list'])) {
            return;
        }
        
        $layout_type = isset($settings['layout_type']) ? $settings['layout_type'] : 'list';
        $container_class = 'msh-services-list';
        if ($layout_type === 'grid') {
            $container_class = 'msh-services-grid';
        } elseif ($layout_type === 'horizontal') {
            $container_class = 'msh-services-horizontal';
        }
        ?>
        <div class="<?php echo esc_attr($container_class); ?>">
            <?php 
            $total_items = count($settings['services_list']);
            foreach ($settings['services_list'] as $index => $item) : 
                $link_key = 'link_' . $index;
                $this->add_link_attributes($link_key, $item['link_url']);
                $link_type = !empty($settings['link_type']) ? $settings['link_type'] : 'text';
                $link_classes = 'msh-service-link';
                if ($link_type === 'button') {
                    $link_classes .= ' link-button';
                }
                $this->add_render_attribute($link_key, 'class', $link_classes);
            ?>
                <?php 
                $media_position = !empty($settings['media_position']) ? $settings['media_position'] : 'left';
                $link_position = !empty($settings['link_position']) ? $settings['link_position'] : 'inline';
                $media_class = 'media-' . $media_position;
                $link_class = 'link-' . $link_position;
                $combined_classes = $media_class . ' ' . $link_class;
                
                // Add layout-specific classes
                if ($layout_type === 'grid') {
                    $combined_classes .= ' msh-service-grid-item';
                } elseif ($layout_type === 'horizontal') {
                    $combined_classes .= ' msh-service-horizontal-item';
                }
                ?>
                <?php 
                $has_cover = !empty($item['cover_image']['url']);
                if ($has_cover) {
                    $combined_classes .= ' has-cover-image';
                }
                ?>
                <?php
                // Build style attributes for cover image
                $style_attrs = [];
                if (!empty($item['link_url']['url']) && ($layout_type === 'grid' || $layout_type === 'horizontal')) {
                    $style_attrs[] = 'cursor: pointer';
                }
                if ($has_cover) {
                    $cover_image_url = esc_url($item['cover_image']['url']);
                    $style_attrs[] = 'background-image: url(' . $cover_image_url . ')';
                    $style_attrs[] = '--cover-bg-image: url(' . $cover_image_url . ')'; // CSS variable backup
                    // Add overlay opacity CSS variable
                    $overlay_opacity = !empty($item['cover_overlay']['size']) ? $item['cover_overlay']['size'] / 100 : 0;
                    $style_attrs[] = '--cover-overlay-opacity: ' . $overlay_opacity;
                }
                $style_string = !empty($style_attrs) ? 'style="' . implode('; ', $style_attrs) . '"' : '';
                ?>
                <div class="msh-service-entry <?php echo esc_attr($combined_classes); ?>" <?php if (!empty($item['link_url']['url']) && ($layout_type === 'grid' || $layout_type === 'horizontal')) : ?>onclick="window.open('<?php echo esc_url($item['link_url']['url']); ?>', '<?php echo ($item['link_url']['is_external'] ? '_blank' : '_self'); ?>')"<?php endif; ?> <?php echo $style_string; ?>>
                    <?php if (!empty($item['media_type']) && $item['media_type'] !== 'none') : ?>
                        <?php if ($item['media_type'] === 'icon' && !empty($item['service_icon']['value'])) : 
                            $icon_shape_class = !empty($settings['icon_background_shape']) ? 'shape-' . $settings['icon_background_shape'] : 'shape-circle';
                        ?>
                            <div class="msh-service-icon <?php echo esc_attr($icon_shape_class); ?>">
                                <?php Icons_Manager::render_icon($item['service_icon'], ['aria-hidden' => 'true']); ?>
                            </div>
                        <?php elseif ($item['media_type'] === 'image' && !empty($item['service_image']['url'])) : 
                            $shape_class = !empty($settings['image_background_shape']) ? 'shape-' . $settings['image_background_shape'] : 'shape-rounded';
                        ?>
                            <div class="msh-service-image <?php echo esc_attr($shape_class); ?>">
                                <img src="<?php echo esc_url($item['service_image']['url']); ?>" alt="<?php echo esc_attr($item['service_title']); ?>">
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <div class="msh-service-content">
                        <div class="msh-service-text">
                            <?php if (!empty($item['service_title'])) : 
                                $title_tag = !empty($settings['title_tag']) ? $settings['title_tag'] : 'h3';
                            ?>
                                <<?php echo esc_attr($title_tag); ?> class="msh-service-title"><?php echo esc_html($item['service_title']); ?></<?php echo esc_attr($title_tag); ?>>
                            <?php endif; ?>
                            
                            <?php if (!empty($item['service_description'])) : ?>
                                <div class="msh-service-description"><?php echo wp_kses_post($item['service_description']); ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($item['explainer_text'])) : ?>
                                <p class="msh-service-explainer"><?php echo esc_html($item['explainer_text']); ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <?php 
                        $link_position = !empty($settings['link_position']) ? $settings['link_position'] : 'inline';
                        // Only show link inside content div if NOT stacked
                        if (!empty($item['link_text']) && strpos($link_position, 'stacked') === false) : 
                        ?>
                            <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                                <span><?php echo esc_html($item['link_text']); ?></span>
                                <?php Icons_Manager::render_icon($item['link_icon'], ['aria-hidden' => 'true']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <?php 
                    // Show link outside content div for stacked positioning
                    if (!empty($item['link_text']) && strpos($link_position, 'stacked') !== false) : 
                    ?>
                        <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                            <span><?php echo esc_html($item['link_text']); ?></span>
                            <?php Icons_Manager::render_icon($item['link_icon'], ['aria-hidden' => 'true']); ?>
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php 
                // Only show divider in list layout (not grid or horizontal)
                if ($layout_type === 'list' && $item['show_divider'] === 'yes' && $index < $total_items - 1) : 
                    $divider_style = !empty($settings['divider_style']) ? $settings['divider_style'] : 'solid';
                ?>
                    <div class="msh-service-divider" data-style="<?php echo esc_attr($divider_style); ?>"></div>
                <?php endif; ?>
                
            <?php endforeach; ?>
        </div>
        <?php
    }
}

// Register the widget
add_action('elementor/widgets/widgets_registered', function() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new MSH_Services_List_Widget());
});
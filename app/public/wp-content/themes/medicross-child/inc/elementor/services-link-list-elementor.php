<?php
/**
 * Services Link List Elementor Widget
 * 
 * Custom Elementor widget for page builder integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class MSH_Services_Link_List_Elementor extends \Elementor\Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'msh_services_link_list';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('MSH Services Link List', 'medicross-child');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-editor-list-ul';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['main-street-health'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['services', 'links', 'list', 'healthcare', 'main street health'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Services', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'service_name',
            [
                'label' => __('Service Name', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Service Name', 'medicross-child'),
                'placeholder' => __('Enter service name', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'service_url',
            [
                'label' => __('Service URL', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'medicross-child'),
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        $this->add_control(
            'services_list',
            [
                'label' => __('Services List', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'service_name' => __('Physiotherapy', 'medicross-child'),
                        'service_url' => ['url' => '/physiotherapy'],
                    ],
                    [
                        'service_name' => __('Chiropractic Care', 'medicross-child'),
                        'service_url' => ['url' => '/chiropractic-care'],
                    ],
                    [
                        'service_name' => __('Massage Therapy', 'medicross-child'),
                        'service_url' => ['url' => '/massage-therapy'],
                    ],
                    [
                        'service_name' => __('Acupuncture', 'medicross-child'),
                        'service_url' => ['url' => '/acupuncture'],
                    ],
                    [
                        'service_name' => __('Custom Orthotics', 'medicross-child'),
                        'service_url' => ['url' => '/custom-orthotics'],
                    ],
                ],
                'title_field' => '{{{ service_name }}}',
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'widget_width',
            [
                'label' => __('Width', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 2000,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-link-list' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_alignment',
            [
                'label' => __('Alignment', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
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
                    '{{WRAPPER}}' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .msh-services-link-list' => 'margin-left: {{VALUE}} === "center" ? "auto" : "{{VALUE}} === "right" ? "auto" : "0"; margin-right: {{VALUE}} === "center" ? "auto" : "{{VALUE}} === "left" ? "auto" : "0";',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_padding',
            [
                'label' => __('Row Padding', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '16',
                    'right' => '0',
                    'bottom' => '16',
                    'left' => '0',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-link-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'divider_width',
            [
                'label' => __('Divider Thickness', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-link-row' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Typography Section
        $this->start_controls_section(
            'typography_section',
            [
                'label' => __('Typography', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'service_name_typography',
                'label' => __('Service Name Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .service-name',
                'fields_options' => [
                    'font_family' => [
                        'default' => 'Source Sans Pro',
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

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'learn_more_typography',
                'label' => __('Learn More Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .learn-more',
                'fields_options' => [
                    'font_family' => [
                        'default' => 'Source Sans Pro',
                    ],
                    'font_weight' => [
                        'default' => '600',
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

        $this->end_controls_section();

        // Colors Section
        $this->start_controls_section(
            'colors_section',
            [
                'label' => __('Colors', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'service_name_color',
            [
                'label' => __('Service Name Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2D3E4E',
                'selectors' => [
                    '{{WRAPPER}} .service-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'service_name_hover_color',
            [
                'label' => __('Service Name Hover Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-link-row:hover .service-name' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'learn_more_color',
            [
                'label' => __('Learn More Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#051B2D',
                'selectors' => [
                    '{{WRAPPER}} .learn-more' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'learn_more_hover_color',
            [
                'label' => __('Learn More Hover Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#218E9C',
                'selectors' => [
                    '{{WRAPPER}} .service-link-row:hover .learn-more' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .service-link-row:hover .arrow-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'divider_color',
            [
                'label' => __('Divider Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E9EFE8',
                'selectors' => [
                    '{{WRAPPER}} .service-link-row' => 'border-bottom-color: {{VALUE}}; border-bottom-style: solid;',
                ],
            ]
        );

        $this->end_controls_section();

        // Background Section
        $this->start_controls_section(
            'background_section',
            [
                'label' => __('Background', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'widget_background',
            [
                'label' => __('Widget Background', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .msh-services-link-list' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'hover_background',
            [
                'label' => __('Row Hover Background', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(233, 239, 232, 0.3)',
                'selectors' => [
                    '{{WRAPPER}} .service-link-row:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'widget_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '8',
                    'right' => '8',
                    'bottom' => '8',
                    'left' => '8',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-link-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_box_shadow',
                'label' => __('Box Shadow', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-services-link-list',
            ]
        );

        $this->add_responsive_control(
            'widget_padding',
            [
                'label' => __('Widget Padding', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-link-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Arrow Icon Section
        $this->start_controls_section(
            'arrow_section',
            [
                'label' => __('Arrow Icon', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_arrow',
            [
                'label' => __('Show Arrow', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'medicross-child'),
                'label_off' => __('Hide', 'medicross-child'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .arrow-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_arrow' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_animation',
            [
                'label' => __('Hover Animation Distance', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-link-row:hover .arrow-icon' => 'transform: translateX({{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'show_arrow' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['services_list'])) {
            return;
        }
        
        $show_arrow = $settings['show_arrow'] === 'yes';
        ?>
        
        <div class="msh-services-link-list elementor-services-widget">
            <?php foreach ($settings['services_list'] as $index => $item): ?>
                <?php 
                $target = $item['service_url']['is_external'] ? ' target="_blank"' : '';
                $nofollow = $item['service_url']['nofollow'] ? ' rel="nofollow"' : '';
                ?>
                <a href="<?php echo esc_url($item['service_url']['url']); ?>" 
                   class="service-link-row"<?php echo $target . $nofollow; ?>>
                    <span class="service-name"><?php echo esc_html($item['service_name']); ?></span>
                    <span class="learn-more">
                        Learn more
                        <?php if ($show_arrow): ?>
                        <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php endif; ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php
    }

    /**
     * Render widget output in the editor (Elementor backend)
     */
    protected function content_template() {
        ?>
        <# if ( settings.services_list.length ) { #>
            <div class="msh-services-link-list elementor-services-widget">
                <# _.each( settings.services_list, function( item, index ) { #>
                    <a href="{{ item.service_url.url }}" class="service-link-row">
                        <span class="service-name">{{{ item.service_name }}}</span>
                        <span class="learn-more">
                            Learn more
                            <svg class="arrow-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </a>
                <# }); #>
            </div>
        <# } #>
        <?php
    }
}

// Register the widget
function msh_register_elementor_services_widget() {
    if (did_action('elementor/loaded')) {
        // Create custom category for Main Street Health widgets
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'main-street-health',
            [
                'title' => __('Main Street Health', 'medicross-child'),
                'icon' => 'fa fa-plug',
            ]
        );
        
        // Register the widget
        \Elementor\Plugin::instance()->widgets_manager->register(new MSH_Services_Link_List_Elementor());
    }
}
add_action('elementor/widgets/register', 'msh_register_elementor_services_widget');
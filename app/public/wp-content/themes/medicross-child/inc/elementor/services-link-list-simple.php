<?php
/**
 * Simple Services Link List Elementor Widget
 * Simplified version to ensure Style tab appears
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Services_Simple_Elementor extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_services_simple';
    }

    public function get_title() {
        return __('MSH Services (Simple)', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-editor-list-ul';
    }

    public function get_categories() {
        return ['general'];
    }

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
            ]
        );

        $repeater->add_control(
            'service_url',
            [
                'label' => __('Service URL', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '',
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
                ],
                'title_field' => '{{{ service_name }}}',
            ]
        );

        $this->end_controls_section();

        // STYLE TAB - Width Section
        $this->start_controls_section(
            'width_section',
            [
                'label' => __('Width', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'widget_width',
            [
                'label' => __('Widget Width', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1200,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-link-list' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // STYLE TAB - Colors Section  
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
                ],
            ]
        );

        $this->end_controls_section();

        // STYLE TAB - Typography Section
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
                'label' => __('Service Name', 'medicross-child'),
                'selector' => '{{WRAPPER}} .service-name',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'learn_more_typography',
                'label' => __('Learn More', 'medicross-child'),
                'selector' => '{{WRAPPER}} .learn-more',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (empty($settings['services_list'])) {
            return;
        }
        ?>
        
        <div class="msh-services-link-list elementor-services-widget">
            <?php foreach ($settings['services_list'] as $index => $item): ?>
                <a href="<?php echo esc_url($item['service_url']['url']); ?>" class="service-link-row">
                    <span class="service-name"><?php echo esc_html($item['service_name']); ?></span>
                    <span class="learn-more">
                        Learn more
                        <svg class="arrow-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2"/>
                        </svg>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php
    }
}

// Register the simple widget
function msh_register_simple_elementor_widget() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new MSH_Services_Simple_Elementor());
}
add_action('elementor/widgets/widgets_registered', 'msh_register_simple_elementor_widget');
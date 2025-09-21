<?php
/**
 * MSH Steps Widget
 *
 * @package medicross-child
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class MSH_Steps_Widget extends Widget_Base {

    public function get_name() {
        return 'msh_steps';
    }

    public function get_title() {
        return __('MSH Steps', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-table';
    }

    public function get_categories() {
        return ['msh-widgets'];
    }

    public function get_keywords() {
        return ['steps', 'process', 'table', 'workflow', 'msh'];
    }

    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Steps Content', 'medicross-child'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Header Controls
        $this->add_control(
            'step_header',
            [
                'label' => __('Step Column Header', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Step', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title_header',
            [
                'label' => __('Title Column Header', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Title', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description_header',
            [
                'label' => __('Description Column Header', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Description', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_header',
            [
                'label' => __('Show Header Row', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'medicross-child'),
                'label_off' => __('Hide', 'medicross-child'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        // Step Repeater
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'step_number',
            [
                'label' => __('Step Number', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => '1',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'step_title',
            [
                'label' => __('Step Title', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Step Title', 'medicross-child'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'step_description',
            [
                'label' => __('Step Description', 'medicross-child'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Step description goes here.', 'medicross-child'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'steps_list',
            [
                'label' => __('Steps', 'medicross-child'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'step_number' => '1',
                        'step_title' => __('Initial Consultation', 'medicross-child'),
                        'step_description' => __('We meet with your HR lead to understand your goals, coverage, and workflow.', 'medicross-child'),
                    ],
                    [
                        'step_number' => '2',
                        'step_title' => __('Custom Plan Design', 'medicross-child'),
                        'step_description' => __('We create a care model tailored to your team size, benefits, and budget.', 'medicross-child'),
                    ],
                    [
                        'step_number' => '3',
                        'step_title' => __('HR Toolkit Delivery', 'medicross-child'),
                        'step_description' => __('You receive onboarding guides, promo materials, and communication assets.', 'medicross-child'),
                    ],
                    [
                        'step_number' => '4',
                        'step_title' => __('Employee Rollout', 'medicross-child'),
                        'step_description' => __('We help launch the program internally with full scheduling support.', 'medicross-child'),
                    ],
                    [
                        'step_number' => '5',
                        'step_title' => __('Ongoing Partnership', 'medicross-child'),
                        'step_description' => __('We check in regularly, track outcomes, and update your plan as needed.', 'medicross-child'),
                    ],
                ],
                'title_field' => '{{{ step_number }}}. {{{ step_title }}}',
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
            'table_layout',
            [
                'label' => __('Table Layout', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => __('Auto (Responsive)', 'medicross-child'),
                    'fixed' => __('Fixed Width', 'medicross-child'),
                ],
            ]
        );

        $this->add_control(
            'step_column_width',
            [
                'label' => __('Step Column Width', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table .step-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'table_layout' => 'fixed'
                ]
            ]
        );

        $this->add_control(
            'title_column_width',
            [
                'label' => __('Title Column Width', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px'],
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 50,
                    ],
                    'px' => [
                        'min' => 150,
                        'max' => 400,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table .title-column' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'table_layout' => 'fixed'
                ]
            ]
        );

        $this->end_controls_section();

        // Table Style Section
        $this->start_controls_section(
            'table_style',
            [
                'label' => __('Table Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'table_border',
                'label' => __('Table Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-steps-table',
            ]
        );

        $this->add_control(
            'table_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'table_box_shadow',
                'label' => __('Box Shadow', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-steps-table',
            ]
        );

        $this->add_control(
            'cell_padding',
            [
                'label' => __('Cell Padding', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '15',
                    'right' => '20',
                    'bottom' => '15',
                    'left' => '20',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table td, {{WRAPPER}} .msh-steps-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Header Style Section
        $this->start_controls_section(
            'header_style',
            [
                'label' => __('Header Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_header' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'header_background_color',
            [
                'label' => __('Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table thead th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_text_color',
            [
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table thead th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'header_typography',
                'label' => __('Typography', 'medicross-child'),
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .msh-steps-table thead th',
            ]
        );

        $this->add_control(
            'header_text_align',
            [
                'label' => __('Text Alignment', 'medicross-child'),
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
                    '{{WRAPPER}} .msh-steps-table thead th' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'header_border',
                'label' => __('Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-steps-table thead th',
            ]
        );

        $this->end_controls_section();

        // Step Number Style
        $this->start_controls_section(
            'step_number_style',
            [
                'label' => __('Step Number Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'step_number_color',
            [
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table .step-number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'step_number_typography',
                'label' => __('Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-steps-table .step-number',
            ]
        );

        $this->add_control(
            'step_number_align',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table .step-column' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Step Title Style
        $this->start_controls_section(
            'step_title_style',
            [
                'label' => __('Step Title Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'step_title_color',
            [
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table .step-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'step_title_typography',
                'label' => __('Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-steps-table .step-title',
            ]
        );

        $this->add_control(
            'step_title_align',
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
                    '{{WRAPPER}} .msh-steps-table .title-column' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Step Description Style
        $this->start_controls_section(
            'step_description_style',
            [
                'label' => __('Step Description Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'step_description_color',
            [
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table .step-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'step_description_typography',
                'label' => __('Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-steps-table .step-description',
            ]
        );

        $this->add_control(
            'step_description_align',
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
                    '{{WRAPPER}} .msh-steps-table .description-column' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Row Style Section
        $this->start_controls_section(
            'row_style',
            [
                'label' => __('Row Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'row_background_color',
            [
                'label' => __('Row Background Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table tbody tr' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_alternate_background',
            [
                'label' => __('Alternate Row Background', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table tbody tr:nth-child(even)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'row_hover_background',
            [
                'label' => __('Row Hover Background', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .msh-steps-table tbody tr:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'row_border',
                'label' => __('Row Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-steps-table tbody td',
            ]
        );

        $this->end_controls_section();

        // Responsive Section
        $this->start_controls_section(
            'responsive_section',
            [
                'label' => __('Responsive Settings', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'mobile_stack',
            [
                'label' => __('Stack on Mobile', 'medicross-child'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'medicross-child'),
                'label_off' => __('No', 'medicross-child'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Stack table cells vertically on mobile devices', 'medicross-child'),
            ]
        );

        $this->add_control(
            'mobile_breakpoint',
            [
                'label' => __('Mobile Breakpoint', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 320,
                        'max' => 1024,
                    ],
                ],
                'default' => [
                    'size' => 768,
                    'unit' => 'px',
                ],
                'condition' => [
                    'mobile_stack' => 'yes'
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['steps_list'])) {
            return;
        }

        $table_classes = ['msh-steps-table'];
        if ($settings['table_layout'] === 'fixed') {
            $table_classes[] = 'table-layout-fixed';
        }
        if ($settings['mobile_stack'] === 'yes') {
            $table_classes[] = 'mobile-stack';
        }
        
        ?>
        <div class="msh-steps-wrapper" data-mobile-breakpoint="<?php echo esc_attr($settings['mobile_breakpoint']['size'] ?? 768); ?>">
            <table class="<?php echo esc_attr(implode(' ', $table_classes)); ?>">
                <?php if ($settings['show_header'] === 'yes') : ?>
                <thead>
                    <tr>
                        <th class="step-column"><?php echo esc_html($settings['step_header']); ?></th>
                        <th class="title-column"><?php echo esc_html($settings['title_header']); ?></th>
                        <th class="description-column"><?php echo esc_html($settings['description_header']); ?></th>
                    </tr>
                </thead>
                <?php endif; ?>
                <tbody>
                    <?php foreach ($settings['steps_list'] as $index => $step) : ?>
                    <tr class="step-row">
                        <td class="step-column">
                            <span class="step-number"><?php echo esc_html($step['step_number']); ?></span>
                        </td>
                        <td class="title-column">
                            <span class="step-title"><?php echo esc_html($step['step_title']); ?></span>
                        </td>
                        <td class="description-column">
                            <span class="step-description"><?php echo esc_html($step['step_description']); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        if (settings.steps_list.length) {
            var tableClasses = ['msh-steps-table'];
            if (settings.table_layout === 'fixed') {
                tableClasses.push('table-layout-fixed');
            }
            if (settings.mobile_stack === 'yes') {
                tableClasses.push('mobile-stack');
            }
        #>
        <div class="msh-steps-wrapper" data-mobile-breakpoint="{{ settings.mobile_breakpoint.size || 768 }}">
            <table class="{{ tableClasses.join(' ') }}">
                <# if (settings.show_header === 'yes') { #>
                <thead>
                    <tr>
                        <th class="step-column">{{{ settings.step_header }}}</th>
                        <th class="title-column">{{{ settings.title_header }}}</th>
                        <th class="description-column">{{{ settings.description_header }}}</th>
                    </tr>
                </thead>
                <# } #>
                <tbody>
                    <# _.each( settings.steps_list, function( step, index ) { #>
                    <tr class="step-row">
                        <td class="step-column">
                            <span class="step-number">{{{ step.step_number }}}</span>
                        </td>
                        <td class="title-column">
                            <span class="step-title">{{{ step.step_title }}}</span>
                        </td>
                        <td class="description-column">
                            <span class="step-description">{{{ step.step_description }}}</span>
                        </td>
                    </tr>
                    <# }); #>
                </tbody>
            </table>
        </div>
        <# } #>
        <?php
    }

    public function get_style_depends() {
        return ['msh-steps-widget'];
    }
}

// Register the widget
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new MSH_Steps_Widget());
<?php
/**
 * Test Elementor Widget - Basic test to see if it works
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Test_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh-test-widget';
    }

    public function get_title() {
        return __('MSH Test Widget', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-posts-ticker';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Test Widget', 'medicross-child'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .test-widget' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .test-widget' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="test-widget">
            <h3><?php echo esc_html($settings['title']); ?></h3>
            <p>This is a test widget to verify Elementor integration is working.</p>
        </div>
        <?php
    }
}

// Register widget
function register_msh_test_widget() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new MSH_Test_Widget());
}
add_action('elementor/widgets/widgets_registered', 'register_msh_test_widget');
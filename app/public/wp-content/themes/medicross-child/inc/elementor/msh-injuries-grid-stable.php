<?php
/**
 * MSH Injuries Grid Widget - Stable Registration Version
 * Ensures widget stays registered and doesn't disappear
 */

if (!defined('ABSPATH')) {
    exit;
}

// Make sure widget is registered at the right time
add_action('elementor/widgets/register', function($widgets_manager) {
    
    // Check if Elementor is loaded
    if (!did_action('elementor/loaded')) {
        return;
    }
    
    // Check if our widget class already exists to avoid redeclaration
    if (!class_exists('MSH_Injuries_Grid_Widget_Stable')) {
        
        class MSH_Injuries_Grid_Widget_Stable extends \Elementor\Widget_Base {

            public function get_name() {
                return 'msh_injuries_grid_stable';
            }

            public function get_title() {
                return __('MSH Injuries Grid', 'medicross-child');
            }
            
            public function get_keywords() {
                return ['msh', 'injuries', 'grid', 'cards', 'injury', 'medical', 'health', 'pain'];
            }

            public function get_icon() {
                return 'eicon-gallery-grid';
            }

            public function get_categories() {
                return ['general', 'pxltheme-core']; // Adding to both categories
            }

            protected function register_controls() {
                
                // Content Section
                $this->start_controls_section(
                    'content_section',
                    [
                        'label' => __('Content', 'medicross-child'),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                );

                $repeater = new \Elementor\Repeater();

                $repeater->add_control(
                    'image',
                    [
                        'label' => __('Background Image', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ]
                );

                $repeater->add_control(
                    'icon',
                    [
                        'label' => __('Circle Icon', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value' => 'fas fa-plus',
                            'library' => 'fa-solid',
                        ],
                    ]
                );

                $repeater->add_control(
                    'title',
                    [
                        'label' => __('Title', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __('Injury Title', 'medicross-child'),
                    ]
                );

                $repeater->add_control(
                    'description',
                    [
                        'label' => __('Description', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => __('Your injury description goes here.', 'medicross-child'),
                    ]
                );

                $repeater->add_control(
                    'link_text',
                    [
                        'label' => __('Link Text', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => __('Learn more', 'medicross-child'),
                    ]
                );

                $repeater->add_control(
                    'link',
                    [
                        'label' => __('Link', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => __('https://your-link.com', 'medicross-child'),
                    ]
                );

                $this->add_control(
                    'injuries_list',
                    [
                        'label' => __('Injuries', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::REPEATER,
                        'fields' => $repeater->get_controls(),
                        'default' => [
                            [
                                'title' => __('Work-Related Injuries', 'medicross-child'),
                                'description' => __('Workplace injuries need focused care.', 'medicross-child'),
                            ],
                            [
                                'title' => __('Sport Injuries', 'medicross-child'),
                                'description' => __('Get back in the game with our help.', 'medicross-child'),
                            ],
                        ],
                        'title_field' => '{{{ title }}}',
                    ]
                );

                $this->end_controls_section();

                // Layout Section
                $this->start_controls_section(
                    'layout_section',
                    [
                        'label' => __('Layout', 'medicross-child'),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                );

                $this->add_responsive_control(
                    'columns',
                    [
                        'label' => __('Columns', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '4',
                        'tablet_default' => '2',
                        'mobile_default' => '1',
                        'options' => [
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .msh-injuries-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                        ],
                    ]
                );

                $this->add_responsive_control(
                    'gap',
                    [
                        'label' => __('Gap', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'default' => [
                            'size' => 30,
                        ],
                        'selectors' => [
                            '{{WRAPPER}} .msh-injuries-grid' => 'gap: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $this->end_controls_section();

                // Style sections
                $this->start_controls_section(
                    'card_style',
                    [
                        'label' => __('Card Style', 'medicross-child'),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    ]
                );

                $this->add_control(
                    'card_bg',
                    [
                        'label' => __('Content Background', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#ffffff',
                        'selectors' => [
                            '{{WRAPPER}} .card-content' => 'background: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'title_color',
                    [
                        'label' => __('Title Color', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#2B4666',
                        'selectors' => [
                            '{{WRAPPER}} .injury-title' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->add_control(
                    'text_color',
                    [
                        'label' => __('Text Color', 'medicross-child'),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#666666',
                        'selectors' => [
                            '{{WRAPPER}} .injury-description' => 'color: {{VALUE}};',
                        ],
                    ]
                );

                $this->end_controls_section();
            }

            protected function render() {
                $settings = $this->get_settings_for_display();
                ?>
                <style>
                    .msh-injuries-grid {
                        display: grid;
                        gap: 30px;
                    }
                    .injury-card {
                        position: relative;
                        height: 400px;
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                    }
                    .injury-card img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        position: absolute;
                        top: 0;
                        left: 0;
                    }
                    .injury-icon {
                        position: absolute;
                        top: 20px;
                        right: 20px;
                        width: 64px;
                        height: 64px;
                        background: #D4AF37;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 3;
                        color: white;
                        font-size: 32px;
                    }
                    .card-content {
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        background: white;
                        padding: 20px;
                        z-index: 2;
                    }
                    .injury-title {
                        font-size: 18px;
                        font-weight: 600;
                        margin-bottom: 10px;
                    }
                    .injury-description {
                        font-size: 14px;
                        line-height: 1.5;
                        margin-bottom: 15px;
                    }
                    .injury-link {
                        color: #2B4666;
                        text-decoration: none;
                        font-weight: 500;
                        display: inline-flex;
                        align-items: center;
                        gap: 8px;
                    }
                    .injury-link:hover {
                        color: #5CB3CC;
                    }
                    @media (max-width: 768px) {
                        .injury-card {
                            height: 350px;
                        }
                    }
                </style>
                
                <div class="msh-injuries-grid">
                    <?php foreach ($settings['injuries_list'] as $item) : ?>
                        <div class="injury-card">
                            <?php if (!empty($item['image']['url'])) : ?>
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?> - Main Street Health">
                            <?php endif; ?>
                            
                            <?php if (!empty($item['icon']['value'])) : ?>
                                <div class="injury-icon">
                                    <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-content">
                                <h3 class="injury-title"><?php echo esc_html($item['title']); ?></h3>
                                <p class="injury-description"><?php echo esc_html($item['description']); ?></p>
                                
                                <?php if (!empty($item['link']['url'])) : ?>
                                    <a href="<?php echo esc_url($item['link']['url']); ?>" class="injury-link">
                                        <?php echo esc_html($item['link_text']); ?>
                                        <span>→</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php
            }
        }
    }
    
    // Register the widget
    $widgets_manager->register(new MSH_Injuries_Grid_Widget_Stable());
    
}, 10);
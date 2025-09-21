<?php
/**
 * MSH Injuries Grid Widget
 * Card-based grid layout for injuries with image, icon, title, description, and link
 * 
 * @package medicross-child
 */

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

class MSH_Injuries_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'msh_injuries_grid';
    }

    public function get_title() {
        return __('MSH Injuries Grid', 'medicross-child');
    }
    
    public function get_keywords() {
        return ['msh', 'injuries', 'grid', 'cards', 'injury', 'medical', 'health'];
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['pxltheme-core'];
    }

    public function get_script_depends() {
        return ['imagesloaded', 'isotope'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'medicross-child'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => __('Background Image', 'medicross-child'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label' => __('Circle Icon', 'medicross-child'),
                'type' => Controls_Manager::ICONS,
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
                'type' => Controls_Manager::TEXT,
                'default' => __('Injury Title', 'medicross-child'),
                'placeholder' => __('Type your title here', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'medicross-child'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Your injury description goes here.', 'medicross-child'),
                'placeholder' => __('Type your description here', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'link_text',
            [
                'label' => __('Link Text', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Learn more', 'medicross-child'),
                'placeholder' => __('Learn more', 'medicross-child'),
            ]
        );

        $repeater->add_control(
            'link_icon',
            [
                'label' => __('Link Icon', 'medicross-child'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'medicross-child'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'medicross-child'),
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'injuries_list',
            [
                'label' => __('Injuries', 'medicross-child'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => __('Work-Related Injuries', 'medicross-child'),
                        'description' => __('Workplace injuries need focused care. We\'re a trusted leader in WSIB rehab and claims.', 'medicross-child'),
                        'link_text' => __('Learn more', 'medicross-child'),
                    ],
                    [
                        'title' => __('Sport Injuries', 'medicross-child'),
                        'description' => __('When injury takes you out of the game, our therapists can help put you back in.', 'medicross-child'),
                        'link_text' => __('Learn more', 'medicross-child'),
                    ],
                    [
                        'title' => __('Motor Vehicle Injuries', 'medicross-child'),
                        'description' => __('We specialize in treating car accident injuries, and we\'ll even help with your MVA insurance claim.', 'medicross-child'),
                        'link_text' => __('Learn more', 'medicross-child'),
                    ],
                    [
                        'title' => __('Chronic Pain', 'medicross-child'),
                        'description' => __('Personalized care that targets the root causes and provides lasting relief from ongoing pain.', 'medicross-child'),
                        'link_text' => __('Learn more', 'medicross-child'),
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
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __('Columns', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
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
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injuries-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
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

        $this->add_responsive_control(
            'card_height',
            [
                'label' => __('Card Height', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 800,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 16,
                    'right' => 16,
                    'bottom' => 16,
                    'left' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .msh-injury-card .card-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_box_shadow',
                'label' => __('Box Shadow', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-injury-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => __('Border', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-injury-card',
            ]
        );

        $this->end_controls_section();

        // Icon Style Section
        $this->start_controls_section(
            'icon_style_section',
            [
                'label' => __('Circle Icon Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 80,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-injury-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_circle_size',
            [
                'label' => __('Circle Size', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 120,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 64,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-injury-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_background',
                'label' => __('Background', 'medicross-child'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .msh-injury-icon',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => '#D4AF37',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_position_top',
            [
                'label' => __('Position from Top', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-icon' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_position_right',
            [
                'label' => __('Position from Right', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-icon' => 'right: {{SIZE}}{{UNIT}};',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-injury-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Bottom Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __('Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-injury-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_spacing',
            [
                'label' => __('Bottom Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'label' => __('Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-injury-link',
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
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_icon_color',
            [
                'label' => __('Icon Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-link i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-injury-link svg' => 'fill: {{VALUE}};',
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
                'label' => __('Text Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_icon_hover_color',
            [
                'label' => __('Icon Color', 'medicross-child'),
                'type' => Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-link:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .msh-injury-link:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'link_icon_spacing',
            [
                'label' => __('Icon Spacing', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
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
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-link i' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-injury-link svg' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Content Container Style Section
        $this->start_controls_section(
            'content_container_style_section',
            [
                'label' => __('Content Container', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Padding', 'medicross-child'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 20,
                    'left' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'label' => __('Background', 'medicross-child'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .card-content',
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

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        
        <div class="msh-injuries-grid">
            <?php foreach ($settings['injuries_list'] as $index => $item) : 
                $link_key = 'link_' . $index;
                $this->add_render_attribute($link_key, 'class', 'msh-injury-link');
                
                if (!empty($item['link']['url'])) {
                    $this->add_link_attributes($link_key, $item['link']);
                }
                ?>
                
                <div class="msh-injury-card">
                    <?php if (!empty($item['image']['url'])) : ?>
                        <div class="card-image">
                            <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($item['icon']['value'])) : ?>
                        <div class="msh-injury-icon">
                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-content">
                        <?php if (!empty($item['title'])) : ?>
                            <h3 class="msh-injury-title"><?php echo esc_html($item['title']); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($item['description'])) : ?>
                            <p class="msh-injury-description"><?php echo esc_html($item['description']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($item['link']['url']) && !empty($item['link_text'])) : ?>
                            <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                                <?php echo esc_html($item['link_text']); ?>
                                <?php if (!empty($item['link_icon']['value'])) : ?>
                                    <?php \Elementor\Icons_Manager::render_icon($item['link_icon'], ['aria-hidden' => 'true']); ?>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php endforeach; ?>
        </div>

        <style>
        .msh-injuries-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            margin: 0;
        }

        .msh-injury-card {
            position: relative;
            height: 400px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .msh-injury-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .msh-injury-icon {
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .msh-injury-icon i,
        .msh-injury-icon svg {
            font-size: 32px;
            color: #ffffff;
        }

        .card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            padding: 20px;
            z-index: 2;
            border-radius: 0 0 16px 16px;
        }

        .msh-injury-title {
            font-size: 18px;
            font-weight: 600;
            color: #2B4666;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .msh-injury-description {
            font-size: 14px;
            color: #666666;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .msh-injury-link {
            display: inline-flex;
            align-items: center;
            color: #2B4666;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .msh-injury-link:hover {
            color: #5CB3CC;
            text-decoration: none;
        }

        .msh-injury-link i,
        .msh-injury-link svg {
            margin-left: 8px;
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .msh-injury-link:hover i,
        .msh-injury-link:hover svg {
            transform: translateX(3px);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .msh-injuries-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .msh-injuries-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .msh-injury-card {
                height: 350px;
            }
            
            .msh-injury-icon {
                width: 56px;
                height: 56px;
                top: 15px;
                right: 15px;
            }
            
            .msh-injury-icon i,
            .msh-injury-icon svg {
                font-size: 28px;
            }
            
            .card-content {
                padding: 16px;
            }
            
            .msh-injury-title {
                font-size: 16px;
                margin-bottom: 10px;
            }
            
            .msh-injury-description {
                font-size: 13px;
                margin-bottom: 16px;
            }
        }
        </style>

        <?php
    }

    protected function content_template() {
        ?>
        <#
        view.addRenderAttribute('grid', 'class', 'msh-injuries-grid');
        #>
        
        <div {{{ view.getRenderAttributeString('grid') }}}>
            <# _.each(settings.injuries_list, function(item, index) { #>
                <div class="msh-injury-card">
                    <# if (item.image && item.image.url) { #>
                        <div class="card-image">
                            <img src="{{{ item.image.url }}}" alt="{{{ item.title }}}">
                        </div>
                    <# } #>
                    
                    <# if (item.icon && item.icon.value) { #>
                        <div class="msh-injury-icon">
                            <# 
                            var iconHTML = elementor.helpers.renderIcon(view, item.icon, { 'aria-hidden': true }, 'i', 'object');
                            print(iconHTML.value);
                            #>
                        </div>
                    <# } #>
                    
                    <div class="card-content">
                        <# if (item.title) { #>
                            <h3 class="msh-injury-title">{{{ item.title }}}</h3>
                        <# } #>
                        
                        <# if (item.description) { #>
                            <p class="msh-injury-description">{{{ item.description }}}</p>
                        <# } #>
                        
                        <# if (item.link && item.link.url && item.link_text) { #>
                            <a href="{{{ item.link.url }}}" class="msh-injury-link">
                                {{{ item.link_text }}}
                                <# if (item.link_icon && item.link_icon.value) { #>
                                    <# 
                                    var linkIconHTML = elementor.helpers.renderIcon(view, item.link_icon, { 'aria-hidden': true }, 'i', 'object');
                                    print(linkIconHTML.value);
                                    #>
                                <# } #>
                            </a>
                        <# } #>
                    </div>
                </div>
            <# }); #>
        </div>
        <?php
    }
}

// Register the widget
add_action('elementor/widgets/register', function($widgets_manager) {
    $widgets_manager->register(new MSH_Injuries_Grid_Widget());
});
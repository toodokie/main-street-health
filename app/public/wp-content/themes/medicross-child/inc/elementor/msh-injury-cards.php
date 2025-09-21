<?php
/**
 * MSH Injury Cards Widget - Fresh Implementation
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Injury_Cards_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_injury_cards';
    }

    public function get_title() {
        return __('MSH Injury Cards Grid', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['injury', 'cards', 'grid', 'medical', 'health'];
    }

    protected function register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Injury Cards', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => __('Title', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Injury Title', 'medicross-child'),
                'label_block' => true,
            ]
        );


        $repeater->add_control(
            'description',
            [
                'label' => __('Description', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Description of the injury or condition.', 'medicross-child'),
                'label_block' => true,
            ]
        );

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
                'label' => __('Icon', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __('Link', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'medicross-child'),
                'default' => [
                    'url' => '#',
                ],
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

        $this->add_control(
            'cards',
            [
                'label' => __('Injury Cards', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' => __('Work-Related Injuries', 'medicross-child'),
                        'description' => __('Workplace injuries need focused care. We\'re a trusted leader in WSIB rehab and claims.', 'medicross-child'),
                    ],
                    [
                        'title' => __('Sport Injuries', 'medicross-child'),
                        'description' => __('When injury takes you out of the game, our therapists can help put you back in.', 'medicross-child'),
                    ],
                    [
                        'title' => __('Motor Vehicle Injuries', 'medicross-child'),
                        'description' => __('We specialize in treating car accident injuries, and we\'ll even help with your MVA insurance claim.', 'medicross-child'),
                    ],
                    [
                        'title' => __('Chronic Pain', 'medicross-child'),
                        'description' => __('Personalized care that targets the root causes and provides lasting relief from ongoing pain.', 'medicross-child'),
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('Title HTML Tag', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                ],
                'default' => 'h3',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        // Display Mode
        $this->start_controls_section(
            'display_section',
            [
                'label' => __('Display Mode', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'display_mode',
            [
                'label' => __('Display Mode', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => __('Grid', 'medicross-child'),
                    'carousel' => __('Carousel', 'medicross-child'),
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Controls
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
                    '{{WRAPPER}} .msh-injury-cards-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-cards-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'display_mode' => 'grid',
                ],
            ]
        );

        // Carousel Settings
        $this->add_control(
            'carousel_heading',
            [
                'label' => __('Carousel Settings', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'display_mode' => 'carousel',
                ],
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => __('Slides to Show', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
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
                    'display_mode' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'display_mode' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'display_mode' => 'carousel',
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => __('Show Navigation', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'display_mode' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => __('Show Pagination', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'display_mode' => 'carousel',
                ],
            ]
        );

        $this->add_responsive_control(
            'carousel_gap',
            [
                'label' => __('Space Between Slides', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'condition' => [
                    'display_mode' => 'carousel',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Controls
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Card Style', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_height',
            [
                'label' => __('Card Height', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 600,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Image Style Section
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => __('Image Style', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_object_fit',
            [
                'label' => __('Image Fit', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => __('Cover', 'medicross-child'),
                    'contain' => __('Contain', 'medicross-child'),
                    'fill' => __('Fill', 'medicross-child'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card .card-bg' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_object_position',
            [
                'label' => __('Image Position', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'center center' => __('Center Center', 'medicross-child'),
                    'center top' => __('Center Top', 'medicross-child'),
                    'center bottom' => __('Center Bottom', 'medicross-child'),
                    'left center' => __('Left Center', 'medicross-child'),
                    'right center' => __('Right Center', 'medicross-child'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card .card-bg' => 'object-position: {{VALUE}};',
                ],
                'condition' => [
                    'image_object_fit' => 'cover',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style Section
        $this->start_controls_section(
            'navigation_style_section',
            [
                'label' => __('Navigation Style', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_mode' => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'arrow_size',
            [
                'label' => __('Arrow Size', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 16,
                        'max' => 40,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-carousel .swiper-button-prev, {{WRAPPER}} .msh-injury-carousel .swiper-button-next' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important; font-size: calc({{SIZE}}{{UNIT}} * 0.67) !important;',
                    '{{WRAPPER}} .msh-injury-carousel .swiper-button-prev:after, {{WRAPPER}} .msh-injury-carousel .swiper-button-next:after' => 'font-size: calc({{SIZE}}{{UNIT}} * 0.67);',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => __('Arrow Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-carousel .swiper-button-prev, {{WRAPPER}} .msh-injury-carousel .swiper-button-next' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => __('Arrow Hover Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#DBAA17',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-carousel .swiper-button-prev:hover, {{WRAPPER}} .msh-injury-carousel .swiper-button-next:hover' => 'color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dot_border_color',
            [
                'label' => __('Dots Border Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-carousel .swiper-pagination-bullet' => 'border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dot_active_color',
            [
                'label' => __('Active Dot Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#09243C',
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-carousel .swiper-pagination-bullet-active' => 'background: {{VALUE}} !important; border-color: {{VALUE}} !important;',
                ],
                'condition' => [
                    'pagination' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Link Style Section
        $this->start_controls_section(
            'link_style_section',
            [
                'label' => __('Link Style', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __('Link Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card .msh-read-more' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .msh-injury-cards-grid .msh-injury-card .msh-read-more' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .msh-injury-carousel .msh-injury-card .msh-read-more' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .msh-injury-card .msh-read-more:hover' => 'color: {{VALUE}} !important; text-decoration: none !important; border: none !important;',
                    '{{WRAPPER}} .msh-injury-cards-grid .msh-injury-card .msh-read-more:hover' => 'color: {{VALUE}} !important; text-decoration: none !important; border: none !important;',
                    '{{WRAPPER}} .msh-injury-carousel .msh-injury-card .msh-read-more:hover' => 'color: {{VALUE}} !important; text-decoration: none !important; border: none !important;',
                ],
            ]
        );

        $this->add_control(
            'link_typography_heading',
            [
                'label' => __('Typography', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'link_typography',
                'label' => __('Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-read-more',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        $is_carousel = ('carousel' === $settings['display_mode']);
        
        // Debug output
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            echo '<!-- Debug: link_color = ' . ($settings['link_color'] ?? 'not set') . ' -->';
            echo '<!-- Debug: link_hover_color = ' . ($settings['link_hover_color'] ?? 'not set') . ' -->';
        }
        ?>
        <style>
            /* Force the colors from widget settings */
            <?php if (!empty($settings['link_color'])): ?>
            .elementor-element-<?php echo $widget_id; ?> .msh-injury-card .msh-read-more {
                color: <?php echo $settings['link_color']; ?> !important;
            }
            <?php endif; ?>
            
            <?php if (!empty($settings['link_hover_color'])): ?>
            .elementor-element-<?php echo $widget_id; ?> .msh-injury-card .msh-read-more:hover {
                color: <?php echo $settings['link_hover_color']; ?> !important;
                text-decoration: none !important;
            }
            <?php endif; ?>
        </style>
        <style>
            .msh-injury-cards-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
            }
            .msh-injury-card {
                background: white;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                cursor: pointer;
                height: 400px;
                display: flex;
                flex-direction: column;
            }
            .msh-injury-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            }
            .msh-injury-card .card-image-container {
                position: relative;
                height: 200px;
                margin: 20px 20px 0 20px;
                border-radius: 12px;
                overflow: hidden;
            }
            .msh-injury-card .card-bg {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center center;
            }
            .msh-injury-card .card-icon {
                position: absolute;
                bottom: 15px;
                right: 15px;
                width: 70px;
                height: 70px;
                background: #D4AF37;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 3;
                color: white;
                font-size: 20px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }
            .msh-injury-card .card-icon i {
                font-size: 32px !important;
            }
            .msh-injury-card .card-icon svg {
                width: 32px !important;
                height: 32px !important;
            }
            .msh-injury-card:hover .card-icon {
                background: #218E9C;
                transition: background-color 0.3s ease;
            }
            .msh-injury-card .card-content {
                flex: 1;
                padding: 20px;
                display: flex;
                flex-direction: column;
            }
            .msh-injury-card .card-title {
                font-size: 18px;
                font-weight: 600;
                color: #2B4666;
                margin: 0 0 10px 0;
                line-height: 1.3;
            }
            .msh-injury-card .card-desc {
                font-size: 14px;
                color: #666;
                line-height: 1.5;
                margin: 0 0 15px 0;
            }
            .msh-injury-card .msh-read-more {
                color: #2B4666;
                text-decoration: none;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: color 0.3s ease;
            }
            .msh-injury-card .msh-read-more:hover {
                color: #5CB3CC;
                text-decoration: none;
            }
            @media (max-width: 1024px) {
                .msh-injury-cards-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            @media (max-width: 768px) {
                .msh-injury-cards-grid {
                    grid-template-columns: 1fr;
                }
                .msh-injury-card {
                    height: 350px;
                }
            }
            
            /* Carousel Styles */
            .msh-injury-carousel {
                position: relative;
                padding-bottom: 50px;
            }
            .msh-injury-carousel .swiper {
                overflow: visible;
            }
            
            /* Navigation Container at Bottom */
            .msh-injury-carousel-nav {
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 20px;
            }
            
            /* Navigation Arrows */
            .msh-injury-carousel .swiper-button-prev,
            .msh-injury-carousel .swiper-button-next {
                position: static;
                width: 24px !important;
                height: 24px !important;
                margin: 0;
                background: none;
                color: #09243C !important;
                font-size: 16px !important;
                font-weight: bold;
                transition: color 0.3s ease;
            }
            .msh-injury-carousel .swiper-button-prev:after,
            .msh-injury-carousel .swiper-button-next:after {
                font-size: 16px;
                font-weight: bold;
            }
            .msh-injury-carousel .swiper-button-prev:hover,
            .msh-injury-carousel .swiper-button-next:hover {
                color: #DBAA17 !important;
            }
            
            /* Pagination Dots */
            .msh-injury-carousel .swiper-pagination {
                position: static;
                display: flex;
                justify-content: center;
                gap: 8px;
                order: 2; /* Put dots in the middle */
            }
            .msh-injury-carousel .swiper-pagination-bullet {
                width: 12px;
                height: 12px;
                background: transparent;
                border: 2px solid #cccccc;
                border-radius: 50%;
                opacity: 1;
                margin: 0 4px !important;
                transition: all 0.3s ease;
            }
            .msh-injury-carousel .swiper-pagination-bullet-active {
                background: #09243C !important;
                border-color: #09243C !important;
            }
            
            /* Order the navigation elements */
            .msh-injury-carousel .swiper-button-prev {
                order: 1; /* Left side */
            }
            .msh-injury-carousel .swiper-button-next {
                order: 3; /* Right side */
            }
        </style>
        
        <?php if ($is_carousel) : ?>
            <div class="msh-injury-carousel" id="msh-injury-carousel-<?php echo $widget_id; ?>">
                <div class="swiper" 
                     data-slides-per-view="<?php echo esc_attr($settings['slides_to_show'] ?? '3'); ?>"
                     data-slides-per-view-tablet="<?php echo esc_attr($settings['slides_to_show_tablet'] ?? '2'); ?>"
                     data-slides-per-view-mobile="<?php echo esc_attr($settings['slides_to_show_mobile'] ?? '1'); ?>"
                     data-space-between="<?php echo esc_attr($settings['carousel_gap']['size'] ?? '30'); ?>"
                     data-autoplay="<?php echo esc_attr($settings['autoplay'] ?? 'yes'); ?>"
                     data-autoplay-delay="<?php echo esc_attr($settings['autoplay_speed'] ?? '3000'); ?>"
                     data-speed="300"
                     data-loop="yes"
                     data-navigation="<?php echo esc_attr($settings['navigation'] ?? 'yes'); ?>"
                     data-pagination="<?php echo esc_attr($settings['pagination'] ?? 'yes'); ?>">
                    
                    <div class="swiper-wrapper">
                        <?php foreach ($settings['cards'] as $item) : ?>
                            <div class="swiper-slide">
                                <div class="msh-injury-card">
                                    <div class="card-image-container">
                                        <?php if (!empty($item['image']['url'])) : ?>
                                            <img src="<?php echo esc_url($item['image']['url']); ?>" class="card-bg" alt="<?php echo esc_attr($item['title']); ?> - Main Street Health">
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($item['icon']['value'])) : ?>
                                            <div class="card-icon">
                                                <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-content">
                                        <?php 
                                        $title_tag = !empty($settings['title_tag']) ? $settings['title_tag'] : 'h3';
                                        echo '<' . $title_tag . ' class="card-title">' . esc_html($item['title']) . '</' . $title_tag . '>';
                                        ?>
                                        <p class="card-desc"><?php echo esc_html($item['description']); ?></p>
                                        
                                        <?php if (!empty($item['link']['url'])) : 
                                            $target = $item['link']['is_external'] ? ' target="_blank"' : '';
                                            $nofollow = $item['link']['nofollow'] ? ' rel="nofollow"' : '';
                                        ?>
                                            <a href="<?php echo esc_url($item['link']['url']); ?>" class="msh-read-more"  onmouseover="this.style.setProperty('text-decoration', 'none', 'important');" onmouseout="this.style.setProperty('text-decoration', 'none', 'important');"<?php echo $target . $nofollow; ?>>
                                                <?php echo esc_html($item['link_text']); ?>
                                                <span>→</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Navigation container -->
                <div class="msh-injury-carousel-nav">
                    <!-- Left arrow -->
                    <?php if ($settings['navigation'] === 'yes') : ?>
                        <div class="swiper-button-prev"></div>
                    <?php endif; ?>
                    
                    <!-- Pagination dots -->
                    <?php if ($settings['pagination'] === 'yes') : ?>
                        <div class="swiper-pagination">
                            <?php if (\Elementor\Plugin::$instance->editor->is_edit_mode()) : ?>
                                <!-- Show static dots in editor for preview -->
                                <?php 
                                $num_dots = min(count($settings['cards']), 5);
                                for ($i = 0; $i < $num_dots; $i++) : ?>
                                    <span class="swiper-pagination-bullet <?php echo $i === 0 ? 'swiper-pagination-bullet-active' : ''; ?>"></span>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Right arrow -->
                    <?php if ($settings['navigation'] === 'yes') : ?>
                        <div class="swiper-button-next"></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <div class="msh-injury-cards-grid">
                <?php foreach ($settings['cards'] as $item) : ?>
                    <div class="msh-injury-card">
                        <div class="card-image-container">
                            <?php if (!empty($item['image']['url'])) : ?>
                                <img src="<?php echo esc_url($item['image']['url']); ?>" class="card-bg" alt="<?php echo esc_attr($item['title']); ?> - Main Street Health">
                            <?php endif; ?>
                            
                            <?php if (!empty($item['icon']['value'])) : ?>
                                <div class="card-icon">
                                    <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-content">
                            <?php 
                            $title_tag = !empty($settings['title_tag']) ? $settings['title_tag'] : 'h3';
                            echo '<' . $title_tag . ' class="card-title">' . esc_html($item['title']) . '</' . $title_tag . '>';
                            ?>
                            <p class="card-desc"><?php echo esc_html($item['description']); ?></p>
                            
                            <?php if (!empty($item['link']['url'])) : 
                                $target = $item['link']['is_external'] ? ' target="_blank"' : '';
                                $nofollow = $item['link']['nofollow'] ? ' rel="nofollow"' : '';
                            ?>
                                <a href="<?php echo esc_url($item['link']['url']); ?>" class="msh-read-more"  onmouseover="this.style.setProperty('text-decoration', 'none', 'important');" onmouseout="this.style.setProperty('text-decoration', 'none', 'important');"<?php echo $target . $nofollow; ?>>
                                    <?php echo esc_html($item['link_text']); ?>
                                    <span>→</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        
        <?php
    }
}
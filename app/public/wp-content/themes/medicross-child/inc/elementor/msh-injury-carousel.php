<?php
/**
 * MSH Injury Cards Carousel Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Injury_Carousel_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_injury_carousel';
    }

    public function get_title() {
        return __('MSH Injury Cards Carousel', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-carousel';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['injury', 'cards', 'carousel', 'slider', 'medical', 'health'];
    }

    public function get_script_depends() {
        return ['swiper'];
    }

    public function get_style_depends() {
        return ['swiper'];
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

        // Carousel Settings
        $this->start_controls_section(
            'carousel_section',
            [
                'label' => __('Carousel Settings', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
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
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => __('Autoplay Speed (ms)', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'loop',
            [
                'label' => __('Infinite Loop', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => __('Show Navigation', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => __('Show Pagination', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => __('Space Between', 'medicross-child'),
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
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = $this->get_id();
        ?>
        <style>
            .msh-injury-carousel {
                position: relative;
            }
            .msh-injury-carousel .swiper-container {
                overflow: hidden;
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
                width: 50px;
                height: 50px;
                background: #D4AF37;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 3;
                color: white;
                font-size: 24px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
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
                flex: 1;
            }
            .msh-injury-card .card-link {
                color: #2B4666;
                text-decoration: none;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: color 0.3s ease;
                margin-top: auto;
            }
            .msh-injury-card .card-link:hover {
                color: #5CB3CC;
            }
            
            /* Swiper Navigation */
            .msh-injury-carousel .swiper-button-next,
            .msh-injury-carousel .swiper-button-prev {
                color: #2B4666;
                background: white;
                width: 44px;
                height: 44px;
                border-radius: 50%;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .msh-injury-carousel .swiper-button-next:after,
            .msh-injury-carousel .swiper-button-prev:after {
                font-size: 16px;
                font-weight: bold;
            }
            
            /* Swiper Pagination */
            .msh-injury-carousel .swiper-pagination-bullet {
                background: #2B4666;
                opacity: 0.3;
            }
            .msh-injury-carousel .swiper-pagination-bullet-active {
                opacity: 1;
                background: #D4AF37;
            }
        </style>
        
        <div class="msh-injury-carousel">
            <div class="swiper-container" id="msh-injury-carousel-<?php echo $widget_id; ?>">
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
                                        <a href="<?php echo esc_url($item['link']['url']); ?>" class="card-link"<?php echo $target . $nofollow; ?>>
                                            <?php echo esc_html($item['link_text']); ?>
                                            <span>→</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ('yes' === $settings['navigation']) : ?>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                <?php endif; ?>
                
                <?php if ('yes' === $settings['pagination']) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swiper !== 'undefined') {
                new Swiper('#msh-injury-carousel-<?php echo $widget_id; ?>', {
                    slidesPerView: <?php echo $settings['slides_to_show_mobile'] ?: 1; ?>,
                    spaceBetween: <?php echo $settings['space_between']['size'] ?: 30; ?>,
                    loop: <?php echo 'yes' === $settings['loop'] ? 'true' : 'false'; ?>,
                    autoplay: <?php echo 'yes' === $settings['autoplay'] ? '{delay: ' . ($settings['autoplay_speed'] ?: 3000) . '}' : 'false'; ?>,
                    navigation: {
                        nextEl: '#msh-injury-carousel-<?php echo $widget_id; ?> .swiper-button-next',
                        prevEl: '#msh-injury-carousel-<?php echo $widget_id; ?> .swiper-button-prev',
                    },
                    pagination: {
                        el: '#msh-injury-carousel-<?php echo $widget_id; ?> .swiper-pagination',
                        clickable: true,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: <?php echo $settings['slides_to_show_mobile'] ?: 1; ?>,
                        },
                        768: {
                            slidesPerView: <?php echo $settings['slides_to_show_tablet'] ?: 2; ?>,
                        },
                        1024: {
                            slidesPerView: <?php echo $settings['slides_to_show'] ?: 3; ?>,
                        }
                    }
                });
            }
        });
        </script>
        <?php
    }
}
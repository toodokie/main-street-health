<?php
/**
 * MSH Team Carousel Widget
 * Custom team widget that pulls from msh_team_member post type
 */

class MSH_Team_Carousel extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_team_carousel';
    }

    public function get_title() {
        return esc_html__('MSH Team Carousel', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['pxltheme-core'];
    }

    public function get_script_depends() {
        return ['swiper', 'pxl-swiper'];
    }

    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'number_posts',
            [
                'label' => esc_html__('Number of Team Members', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label' => esc_html__('Order By', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'menu_order',
                'options' => [
                    'menu_order' => esc_html__('Menu Order', 'medicross-child'),
                    'title' => esc_html__('Title', 'medicross-child'),
                    'date' => esc_html__('Date', 'medicross-child'),
                    'rand' => esc_html__('Random', 'medicross-child'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('Order', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC' => esc_html__('Ascending', 'medicross-child'),
                    'DESC' => esc_html__('Descending', 'medicross-child'),
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel Settings
        $this->start_controls_section(
            'carousel_section',
            [
                'label' => esc_html__('Carousel Settings', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slides_to_show',
            [
                'label' => esc_html__('Slides to Show', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
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
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'navigation',
            [
                'label' => esc_html__('Navigation', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'pagination',
            [
                'label' => esc_html__('Pagination', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'medicross-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_background',
            [
                'label' => esc_html__('Card Background', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .msh-team-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => esc_html__('Name Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-team-name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Name Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#35332f',
                'selectors' => [
                    '{{WRAPPER}} .msh-team-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'position_typography',
                'label' => esc_html__('Position Typography', 'medicross-child'),
                'selector' => '{{WRAPPER}} .msh-team-position',
            ]
        );

        $this->add_control(
            'position_color',
            [
                'label' => esc_html__('Position Color', 'medicross-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-team-position' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Query team members
        $args = [
            'post_type' => 'msh_team_member',
            'posts_per_page' => $settings['number_posts'],
            'orderby' => $settings['order_by'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        ];

        $team_query = new WP_Query($args);

        if (!$team_query->have_posts()) {
            echo '<p>' . esc_html__('No team members found.', 'medicross-child') . '</p>';
            return;
        }

        // Carousel settings
        $carousel_settings = [
            'slides_to_show' => $settings['slides_to_show'],
            'slides_to_show_tablet' => $settings['slides_to_show_tablet'],
            'slides_to_show_mobile' => $settings['slides_to_show_mobile'],
            'autoplay' => $settings['autoplay'] === 'yes',
            'autoplay_speed' => $settings['autoplay_speed'],
            'navigation' => $settings['navigation'] === 'yes',
            'pagination' => $settings['pagination'] === 'yes',
        ];

        ?>
        <div class="msh-team-carousel-wrapper">
            <div class="msh-team-carousel swiper-container" data-settings='<?php echo json_encode($carousel_settings); ?>'>
                <div class="swiper-wrapper">
                    <?php while ($team_query->have_posts()) : $team_query->the_post(); ?>
                        <div class="swiper-slide">
                            <div class="msh-team-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="msh-team-image">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="msh-team-content">
                                    <?php
                                    // Get position from custom field or excerpt
                                    $position = get_post_meta(get_the_ID(), '_team_position', true);
                                    if (empty($position)) {
                                        $position = get_post_meta(get_the_ID(), 'position', true);
                                    }
                                    if (empty($position) && has_excerpt()) {
                                        $position = get_the_excerpt();
                                    }
                                    if (!empty($position)) : ?>
                                        <div class="msh-team-position"><?php echo esc_html($position); ?></div>
                                    <?php endif; ?>

                                    <h3 class="msh-team-name"><?php the_title(); ?></h3>

                                    <?php if (get_the_content()) : ?>
                                        <div class="msh-team-description">
                                            <?php
                                            $content = get_the_content();
                                            $content = apply_filters('the_content', $content);
                                            echo $content;
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ($settings['navigation'] === 'yes') : ?>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                <?php endif; ?>

                <?php if ($settings['pagination'] === 'yes') : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        </div>

        <style>
        .msh-team-carousel-wrapper {
            position: relative;
            margin: 40px 0;
        }

        .msh-team-card {
            background: #F6F8FA;
            border-radius: 0;
            padding: 30px 25px;
            margin: 15px;
            border: 1px solid #E5E9ED;
            transition: all 0.3s ease;
        }

        .msh-team-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .msh-team-image {
            margin-bottom: 25px;
            text-align: left;
        }

        .msh-team-image img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .msh-team-content {
            text-align: left;
        }

        .msh-team-position {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5CB3CC;
            margin-bottom: 8px;
            font-family: 'Source Sans Pro', sans-serif;
        }

        .msh-team-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #2C3E50;
            line-height: 1.2;
            font-family: 'Source Sans Pro', sans-serif;
        }

        .msh-team-description {
            font-size: 14px;
            line-height: 1.7;
            color: #5A6C7D;
            margin-bottom: 0;
            font-family: 'Source Sans Pro', sans-serif;
        }

        .msh-team-description p {
            margin-bottom: 15px;
        }

        .msh-team-description p:last-child {
            margin-bottom: 0;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #5CB3CC;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            box-shadow: 0 3px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: #5CB3CC;
            color: white;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 18px;
            font-weight: 600;
        }

        .swiper-pagination-bullet {
            background: #D1D8E0;
            opacity: 1;
            width: 10px;
            height: 10px;
        }

        .swiper-pagination-bullet-active {
            background: #5CB3CC;
            transform: scale(1.2);
        }

        .swiper-pagination {
            bottom: -50px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .msh-team-card {
                margin: 10px 5px;
                padding: 25px 20px;
            }

            .msh-team-name {
                font-size: 20px;
            }

            .msh-team-image img {
                width: 100px;
                height: 100px;
            }
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('.msh-team-carousel').each(function() {
                const $carousel = $(this);
                const settings = $carousel.data('settings');

                new Swiper($carousel[0], {
                    slidesPerView: settings.slides_to_show_mobile || 1,
                    spaceBetween: 20,
                    autoplay: settings.autoplay ? {
                        delay: settings.autoplay_speed,
                        disableOnInteraction: false,
                    } : false,
                    navigation: settings.navigation ? {
                        nextEl: $carousel.find('.swiper-button-next')[0],
                        prevEl: $carousel.find('.swiper-button-prev')[0],
                    } : false,
                    pagination: settings.pagination ? {
                        el: $carousel.find('.swiper-pagination')[0],
                        clickable: true,
                    } : false,
                    breakpoints: {
                        768: {
                            slidesPerView: settings.slides_to_show_tablet || 2,
                        },
                        1024: {
                            slidesPerView: settings.slides_to_show || 3,
                        }
                    }
                });
            });
        });
        </script>
        <?php

        wp_reset_postdata();
    }
}

// Register the widget with both old and new methods for compatibility
add_action('elementor/widgets/widgets_registered', function() {
    if (class_exists('MSH_Team_Carousel')) {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Team_Carousel());
    }
});

add_action('elementor/widgets/register', function($widgets_manager) {
    if (class_exists('MSH_Team_Carousel')) {
        if (method_exists($widgets_manager, 'register')) {
            $widgets_manager->register(new \MSH_Team_Carousel());
        } else {
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MSH_Team_Carousel());
        }
    }
});
?>
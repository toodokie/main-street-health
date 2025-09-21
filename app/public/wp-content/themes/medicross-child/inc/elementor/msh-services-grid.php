<?php
/**
 * Main Street Health Custom Services Grid Widget
 * Custom version of the masonry grid for services page
 * 
 * @package medicross-child
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class MSH_Services_Grid_Widget extends Widget_Base {

    public function get_name() {
        return 'msh_services_grid';
    }

    public function get_title() {
        return __('MSH Services Grid', 'medicross-child');
    }

    public function get_icon() {
        return 'eicon-posts-masonry';
    }

    public function get_categories() {
        return ['pxltheme-core'];
    }

    public function get_script_depends() {
        return ['imagesloaded', 'isotope', 'pxl-post-grid'];
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

        $this->add_control(
            'post_type',
            [
                'label' => __('Post Type', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'service',
                'options' => [
                    'service' => __('Services', 'medicross-child'),
                    'post' => __('Posts', 'medicross-child'),
                    'portfolio' => __('Portfolio', 'medicross-child'),
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'medicross-child'),
                'type' => Controls_Manager::NUMBER,
                'default' => 12,
                'min' => 1,
                'max' => 100,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => __('Date', 'medicross-child'),
                    'title' => __('Title', 'medicross-child'),
                    'menu_order' => __('Menu Order', 'medicross-child'),
                    'rand' => __('Random', 'medicross-child'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => __('Descending', 'medicross-child'),
                    'ASC' => __('Ascending', 'medicross-child'),
                ],
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
            'layout_mode',
            [
                'label' => __('Layout Mode', 'medicross-child'),
                'type' => Controls_Manager::SELECT,
                'default' => 'masonry',
                'options' => [
                    'masonry' => __('Masonry', 'medicross-child'),
                    'fitRows' => __('Fit Rows', 'medicross-child'),
                    'vertical' => __('Vertical', 'medicross-child'),
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
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-grid-item' => 'width: calc(100% / {{VALUE}});',
                ],
            ]
        );

        $this->add_control(
            'gap',
            [
                'label' => __('Gap', 'medicross-child'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-services-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-grid-inner' => 'margin: -{{SIZE}}{{UNIT}}/2;',
                    '{{WRAPPER}} .msh-grid-item' => 'padding: {{SIZE}}{{UNIT}}/2;',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'medicross-child'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'custom_class',
            [
                'label' => __('Custom CSS Class', 'medicross-child'),
                'type' => Controls_Manager::TEXT,
                'default' => 'msh-custom-grid',
                'description' => __('Add your custom class for additional styling', 'medicross-child'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Query arguments
        $args = [
            'post_type' => $settings['post_type'],
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'post_status' => 'publish',
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) : ?>
            <div class="msh-services-grid <?php echo esc_attr($settings['custom_class']); ?>" 
                 data-layout="<?php echo esc_attr($settings['layout_mode']); ?>">
                <div class="msh-grid-inner pxl-grid-inner pxl-grid-masonry row">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="msh-grid-item pxl-grid-item">
                            <div class="msh-item-inner pxl-post--inner">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="msh-item-thumbnail pxl-post--featured">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium_large'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="msh-item-content pxl-post--content">
                                    <h3 class="msh-item-title pxl-post--title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <?php if (has_excerpt()) : ?>
                                        <div class="msh-item-excerpt pxl-post--excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="msh-item-link pxl-item--link">
                                        <a href="<?php the_permalink(); ?>" class="msh-read-more">
                                            <?php _e('Learn More', 'medicross-child'); ?>
                                            <i class="flaticon flaticon-next"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <script>
            jQuery(document).ready(function($) {
                // Initialize Isotope for MSH Services Grid
                var $grid = $('.msh-services-grid .msh-grid-inner');
                if ($grid.length && typeof $.fn.isotope !== 'undefined') {
                    $grid.imagesLoaded(function() {
                        $grid.isotope({
                            itemSelector: '.msh-grid-item',
                            layoutMode: '<?php echo esc_js($settings['layout_mode']); ?>',
                            percentPosition: true,
                            masonry: {
                                columnWidth: '.msh-grid-item'
                            }
                        });
                    });
                }
            });
            </script>
            
        <?php else : ?>
            <p><?php _e('No items found.', 'medicross-child'); ?></p>
        <?php endif;

        wp_reset_postdata();
    }
}

// Register the widget
add_action('elementor/widgets/widgets_registered', function() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new MSH_Services_Grid_Widget());
});
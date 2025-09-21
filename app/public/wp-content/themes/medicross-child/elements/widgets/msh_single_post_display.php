<?php
/**
 * MSH Single Post Display Widget
 * Custom widget for displaying a single post in Main Street Health style
 */

if (!defined('ABSPATH')) exit;

class MSH_Single_Post_Display extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_single_post_display';
    }

    public function get_title() {
        return esc_html__('MSH Single Post Display', 'medicross');
    }

    public function get_icon() {
        return 'eicon-post';
    }

    public function get_categories() {
        return ['pxltheme-core'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'medicross'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_selection',
            [
                'label' => esc_html__('Post Selection', 'medicross'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'latest',
                'options' => [
                    'latest' => esc_html__('Latest Post', 'medicross'),
                    'featured' => esc_html__('Featured Post', 'medicross'),
                    'specific' => esc_html__('Specific Post', 'medicross'),
                ],
            ]
        );

        $this->add_control(
            'specific_post',
            [
                'label' => esc_html__('Select Post', 'medicross'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_posts_list(),
                'condition' => [
                    'post_selection' => 'specific',
                ],
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label' => esc_html__('Show Category', 'medicross'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'medicross'),
                'label_off' => esc_html__('Hide', 'medicross'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => esc_html__('Show Author & Date', 'medicross'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'medicross'),
                'label_off' => esc_html__('Hide', 'medicross'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'medicross'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                ],
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => esc_html__('Excerpt Length', 'medicross'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 10,
                'max' => 100,
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'medicross'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label' => esc_html__('Category Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-post-category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-post-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__('Meta Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .msh-post-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label' => esc_html__('Excerpt Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .msh-post-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => esc_html__('Button Background', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-read-more' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Button Text Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .msh-read-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_posts_list() {
        $posts = get_posts([
            'numberposts' => 20,
            'post_status' => 'publish',
            'post_type' => 'post',
        ]);
        
        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title;
        }
        
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get the post based on selection
        $post = $this->get_selected_post($settings);
        
        if (!$post) {
            echo '<p>' . esc_html__('No post found.', 'medicross') . '</p>';
            return;
        }

        setup_postdata($post);
        ?>
        
        <div class="msh-single-post-display">
            <?php if ($settings['show_category'] === 'yes') : ?>
                <div class="msh-post-category">
                    <?php 
                    $categories = get_the_category($post->ID);
                    if (!empty($categories)) {
                        $category = $categories[0];
                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html(strtoupper($category->name)) . '</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($settings['show_meta'] === 'yes') : ?>
                <div class="msh-post-meta">
                    <div class="msh-post-author">by <a href="<?php echo esc_url(get_author_posts_url($post->post_author)); ?>"><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></a></div>
                    <div class="msh-post-date"><?php echo esc_html(get_the_date('j M Y', $post->ID)); ?></div>
                </div>
            <?php endif; ?>

            <<?php echo esc_attr($settings['title_tag']); ?> class="msh-post-title">
                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                    <?php echo esc_html($post->post_title); ?>
                </a>
            </<?php echo esc_attr($settings['title_tag']); ?>>

            <?php if (has_post_thumbnail($post->ID)) : ?>
                <div class="msh-post-image">
                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                        <?php echo get_the_post_thumbnail($post->ID, 'large'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="msh-post-excerpt">
                <?php 
                $excerpt = wp_trim_words(get_the_excerpt($post->ID), $settings['excerpt_length'], '...');
                echo wp_kses_post($excerpt);
                ?>
            </div>

            <div class="msh-post-actions">
                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="msh-read-more">
                    Read Blog Post
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="transform:scalex(-1);height:15px;">
                        <path d="m22 11h-17.586l5.293-5.293a1 1 0 1 0 -1.414-1.414l-7 7a1 1 0 0 0 0 1.414l7 7a1 1 0 0 0 1.414-1.414l-5.293-5.293h17.586a1 1 0 0 0 0-2z"></path>
                    </svg>
                </a>
            </div>
        </div>

        <style>
        /* FORCE TITLE SIZE - NUCLEAR OPTION */
        .elementor-widget-container .msh-single-post-display .msh-post-title,
        .elementor-widget-container .msh-single-post-display .msh-post-title a {
            font-size: 28px !important;
        }
        
        .msh-single-post-display {
            max-width: 70%;
        }
        
        /* Full width on mobile */
        @media (max-width: 767px) {
            .msh-single-post-display {
                max-width: 100%;
            }
        }

        .msh-post-category {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .msh-post-category a {
            color: #5CB3CC;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .msh-post-category a:hover {
            color: #2B4666;
        }

        .msh-post-meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .msh-post-meta a {
            color: #2B4666;
            text-decoration: none;
        }

        .msh-post-author {
            margin-bottom: 4px;
        }

        .msh-post-date {
            margin: 0;
        }

        .msh-post-title {
            font-size: 28px !important;
            font-weight: 700;
            line-height: 1.3;
            margin: 15px 0 25px;
            color: #2B4666;
        }

        .msh-post-title a {
            font-size: 28px !important;
            color: #2B4666;
            text-decoration: none;
        }

        .msh-post-title a:hover {
            color: #A6C0A4;
        }

        .msh-post-image {
            margin: 25px 0;
        }

        .msh-post-image img {
            width: 100%;
            height: auto;
            border-radius: 18px;
            transition: transform 0.3s ease;
        }

        .msh-post-image:hover img {
            transform: scale(1.02);
        }

        .msh-post-excerpt {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin: 20px 0 25px;
        }

        .msh-read-more {
            display: inline-flex !important;
            align-items: center !important;
            gap: 10px !important;
            background-color: #2B4666 !important;
            color: white !important;
            text-decoration: none !important;
            padding: 12px 24px !important;
            border-radius: 13px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
            border: none !important;
        }

        .msh-read-more:hover,
        .msh-read-more:focus,
        .msh-read-more:active {
            background-color: #DBAA17 !important;
            color: white !important;
            transform: translateY(-2px) !important;
            text-decoration: none !important;
            border-bottom: none !important;
        }

        .msh-read-more:visited {
            color: white !important;
            text-decoration: none !important;
        }

        /* Override any theme link styles */
        .msh-single-post-display a.msh-read-more,
        .msh-single-post-display a.msh-read-more:hover,
        .msh-single-post-display a.msh-read-more:focus,
        .msh-single-post-display a.msh-read-more:active,
        .msh-single-post-display a.msh-read-more:visited {
            text-decoration: none !important;
            border-bottom: none !important;
            background-color: #2B4666 !important;
        }

        .msh-single-post-display a.msh-read-more:hover,
        .msh-single-post-display a.msh-read-more:focus,
        .msh-single-post-display a.msh-read-more:active {
            background-color: #DBAA17 !important;
            color: white !important;
        }

        .msh-read-more svg {
            width: 15px;
            height: 15px;
            fill: currentColor;
        }

        @media (max-width: 768px) {
            .msh-post-title {
                font-size: 24px;
            }
            
            .msh-single-post-display {
                padding: 0 15px;
            }
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force title font size with JavaScript
            var titles = document.querySelectorAll('.msh-single-post-display .msh-post-title');
            var titleLinks = document.querySelectorAll('.msh-single-post-display .msh-post-title a');
            
            titles.forEach(function(title) {
                title.style.setProperty('font-size', '28px', 'important');
                title.style.setProperty('line-height', '1.2', 'important');
            });
            
            titleLinks.forEach(function(link) {
                link.style.setProperty('font-size', '28px', 'important');
                link.style.setProperty('line-height', '1.2', 'important');
            });
            
            // Force button styling with JavaScript
            var buttons = document.querySelectorAll('.msh-single-post-display .msh-read-more');
            
            buttons.forEach(function(button) {
                // Set initial button styles
                button.style.setProperty('background-color', '#2B4666', 'important');
                button.style.setProperty('color', 'white', 'important');
                button.style.setProperty('text-decoration', 'none', 'important');
                button.style.setProperty('border-bottom', 'none', 'important');
                button.style.setProperty('border-radius', '13px', 'important');
                
                // Add hover event
                button.addEventListener('mouseenter', function() {
                    this.style.setProperty('background-color', '#DBAA17', 'important');
                    this.style.setProperty('color', 'white', 'important');
                    this.style.setProperty('text-decoration', 'none', 'important');
                    this.style.setProperty('border-bottom', 'none', 'important');
                });
                
                // Add mouse leave event
                button.addEventListener('mouseleave', function() {
                    this.style.setProperty('background-color', '#2B4666', 'important');
                    this.style.setProperty('color', 'white', 'important');
                    this.style.setProperty('text-decoration', 'none', 'important');
                    this.style.setProperty('border-bottom', 'none', 'important');
                });
            });
        });
        </script>

        <?php
        wp_reset_postdata();
    }

    private function get_selected_post($settings) {
        switch ($settings['post_selection']) {
            case 'latest':
                $posts = get_posts([
                    'numberposts' => 1,
                    'post_status' => 'publish',
                    'post_type' => 'post',
                ]);
                return !empty($posts) ? $posts[0] : null;
                
            case 'featured':
                $posts = get_posts([
                    'numberposts' => 1,
                    'post_status' => 'publish',
                    'post_type' => 'post',
                    'meta_key' => '_featured_post',
                    'meta_value' => '1',
                ]);
                
                // Fallback to latest if no featured posts
                if (empty($posts)) {
                    $posts = get_posts([
                        'numberposts' => 1,
                        'post_status' => 'publish',
                        'post_type' => 'post',
                    ]);
                }
                return !empty($posts) ? $posts[0] : null;
                
            case 'specific':
                if (!empty($settings['specific_post'])) {
                    return get_post($settings['specific_post']);
                }
                return null;
                
            default:
                return null;
        }
    }
}

// Register the widget
function register_msh_single_post_display_widget($widgets_manager) {
    $widgets_manager->register(new MSH_Single_Post_Display());
}
add_action('elementor/widgets/register', 'register_msh_single_post_display_widget');
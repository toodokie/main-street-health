<?php
/**
 * MSH Popular Tags Widget
 * Display popular or selected tags from blog posts in Main Street Health style
 */

if (!defined('ABSPATH')) exit;

class MSH_Popular_Tags extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_popular_tags';
    }

    public function get_title() {
        return esc_html__('MSH Popular Tags', 'medicross');
    }

    public function get_icon() {
        return 'eicon-tags';
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
            'widget_title',
            [
                'label' => esc_html__('Widget Title', 'medicross'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Popular tags',
                'placeholder' => esc_html__('Enter widget title', 'medicross'),
            ]
        );

        $this->add_control(
            'tag_selection_type',
            [
                'label' => esc_html__('Tag Selection', 'medicross'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'popular',
                'options' => [
                    'popular' => esc_html__('Most Popular Tags', 'medicross'),
                    'recent' => esc_html__('Recently Used Tags', 'medicross'),
                    'selected' => esc_html__('Manually Selected Tags', 'medicross'),
                ],
            ]
        );

        $this->add_control(
            'selected_tags',
            [
                'label' => esc_html__('Select Tags', 'medicross'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_tags(),
                'condition' => [
                    'tag_selection_type' => 'selected',
                ],
            ]
        );

        $this->add_control(
            'number_of_tags',
            [
                'label' => esc_html__('Number of Tags', 'medicross'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 8,
                'min' => 1,
                'max' => 20,
                'condition' => [
                    'tag_selection_type!' => 'selected',
                ],
            ]
        );

        $this->add_control(
            'show_post_count',
            [
                'label' => esc_html__('Show Post Count', 'medicross'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'medicross'),
                'label_off' => esc_html__('Hide', 'medicross'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'tag_link',
            [
                'label' => esc_html__('Make Tags Clickable', 'medicross'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'medicross'),
                'label_off' => esc_html__('No', 'medicross'),
                'return_value' => 'yes',
                'default' => 'yes',
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
            'title_color',
            [
                'label' => esc_html__('Title Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-tags-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tag_bg_color',
            [
                'label' => esc_html__('Tag Background Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .msh-tag-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tag_text_color',
            [
                'label' => esc_html__('Tag Text Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-tag-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tag_hover_bg_color',
            [
                'label' => esc_html__('Tag Hover Background', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-tag-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tag_hover_text_color',
            [
                'label' => esc_html__('Tag Hover Text Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .msh-tag-item:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tag_border_radius',
            [
                'label' => esc_html__('Tag Border Radius', 'medicross'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-tag-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tags_gap',
            [
                'label' => esc_html__('Gap Between Tags', 'medicross'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-tags-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_all_tags() {
        $tags = get_tags(['hide_empty' => false]);
        $options = [];
        
        foreach ($tags as $tag) {
            $options[$tag->term_id] = $tag->name . ' (' . $tag->count . ')';
        }
        
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get tags based on selection type
        $tags = $this->get_selected_tags($settings);
        
        if (empty($tags)) {
            echo '<p>' . esc_html__('No tags found.', 'medicross') . '</p>';
            return;
        }
        ?>
        
        <div class="msh-popular-tags-widget">
            <?php if (!empty($settings['widget_title'])) : ?>
                <h3 class="msh-tags-title"><?php echo esc_html($settings['widget_title']); ?></h3>
            <?php endif; ?>

            <div class="msh-tags-container">
                <?php foreach ($tags as $tag) : ?>
                    <div class="msh-tag-item-wrapper">
                        <?php if ($settings['tag_link'] === 'yes') : ?>
                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="msh-tag-item">
                                <?php echo esc_html($tag->name); ?>
                                <?php if ($settings['show_post_count'] === 'yes') : ?>
                                    <span class="tag-count">(<?php echo esc_html($tag->count); ?>)</span>
                                <?php endif; ?>
                            </a>
                        <?php else : ?>
                            <span class="msh-tag-item">
                                <?php echo esc_html($tag->name); ?>
                                <?php if ($settings['show_post_count'] === 'yes') : ?>
                                    <span class="tag-count">(<?php echo esc_html($tag->count); ?>)</span>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
        .msh-popular-tags-widget {
            max-width: 100%;
        }

        .msh-tags-title {
            color: #2B4666;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            margin-top: 0;
        }

        .msh-tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-start;
        }

        .msh-tag-item {
            display: inline-block;
            background-color: #f8f9fa;
            color: #5CB3CC !important;
            padding: 10px 16px;
            border-radius: 8px !important;
            text-decoration: none !important;
            font-family: 'Source Sans Pro', 'Segoe UI', sans-serif !important;
            font-size: 14px !important;
            font-weight: 400 !important;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            white-space: nowrap;
        }

        .msh-tag-item:hover {
            background-color: #5CB3CC;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(92, 179, 204, 0.3);
            text-decoration: none;
        }

        .msh-tag-item .tag-count {
            opacity: 0.7;
            font-size: 12px;
            margin-left: 4px;
        }

        .msh-tag-item:hover .tag-count {
            opacity: 0.9;
        }

        /* Non-clickable tags styling */
        .msh-tag-item:not([href]) {
            cursor: default;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .msh-tags-container {
                gap: 8px;
            }
            
            .msh-tag-item {
                padding: 8px 12px;
                font-size: 13px;
            }
            
            .msh-tags-title {
                font-size: 16px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 480px) {
            .msh-tags-container {
                gap: 6px;
            }
            
            .msh-tag-item {
                padding: 6px 10px;
                font-size: 12px;
            }
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force tag styling with JavaScript
            var tags = document.querySelectorAll('.msh-popular-tags-widget .msh-tag-item');
            
            tags.forEach(function(tag) {
                // Set initial tag styles
                tag.style.setProperty('color', '#5CB3CC', 'important');
                tag.style.setProperty('border-radius', '8px', 'important');
                tag.style.setProperty('text-decoration', 'none', 'important');
                tag.style.setProperty('border-bottom', 'none', 'important');
                tag.style.setProperty('font-family', "'Source Sans Pro', 'Segoe UI', sans-serif", 'important');
                tag.style.setProperty('font-size', '14px', 'important');
                tag.style.setProperty('font-weight', '400', 'important');
                
                // Add hover event
                tag.addEventListener('mouseenter', function() {
                    this.style.setProperty('background-color', '#5CB3CC', 'important');
                    this.style.setProperty('color', '#ffffff', 'important');
                    this.style.setProperty('text-decoration', 'none', 'important');
                    this.style.setProperty('border-bottom', 'none', 'important');
                });
                
                // Add mouse leave event
                tag.addEventListener('mouseleave', function() {
                    this.style.setProperty('background-color', '#f8f9fa', 'important');
                    this.style.setProperty('color', '#5CB3CC', 'important');
                    this.style.setProperty('text-decoration', 'none', 'important');
                    this.style.setProperty('border-bottom', 'none', 'important');
                });
            });
        });
        </script>

        <?php
    }

    private function get_selected_tags($settings) {
        switch ($settings['tag_selection_type']) {
            case 'popular':
                return get_tags([
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => $settings['number_of_tags'],
                    'hide_empty' => true,
                ]);
                
            case 'recent':
                return get_tags([
                    'orderby' => 'term_id',
                    'order' => 'DESC',
                    'number' => $settings['number_of_tags'],
                    'hide_empty' => true,
                ]);
                
            case 'selected':
                if (!empty($settings['selected_tags'])) {
                    return get_tags([
                        'include' => $settings['selected_tags'],
                        'hide_empty' => false,
                    ]);
                }
                return [];
                
            default:
                return [];
        }
    }
}

// Register the widget
function register_msh_popular_tags_widget($widgets_manager) {
    $widgets_manager->register(new MSH_Popular_Tags());
}
add_action('elementor/widgets/register', 'register_msh_popular_tags_widget');
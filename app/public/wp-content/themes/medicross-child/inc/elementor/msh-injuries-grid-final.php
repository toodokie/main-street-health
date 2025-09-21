<?php
/**
 * MSH Injuries Grid Widget - Final Stable Version
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define the widget class outside of any hooks
class MSH_Injuries_Grid_Widget_Final extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_injuries_grid_final';
    }

    public function get_title() {
        return __('MSH Injuries Grid', 'medicross-child');
    }
    
    public function get_keywords() {
        return ['msh', 'injuries', 'grid', 'cards', 'injury', 'medical', 'health', 'pain', 'chronic'];
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general']; // Put in general category for maximum visibility
    }

    protected function register_controls() {
        
        // Content Section
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
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-injuries-grid-final' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
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
                    '{{WRAPPER}} .msh-injuries-grid-final' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <style>
            .msh-injuries-grid-final {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
            }
            .msh-injury-card-final {
                position: relative;
                height: 400px;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .msh-injury-card-final:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            }
            .msh-injury-card-final .card-bg {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                z-index: 1;
            }
            .msh-injury-card-final .card-icon {
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
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            }
            .msh-injury-card-final .card-content {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                padding: 20px;
                z-index: 2;
            }
            .msh-injury-card-final .card-title {
                font-size: 18px;
                font-weight: 600;
                color: #2B4666;
                margin: 0 0 10px 0;
                line-height: 1.3;
            }
            .msh-injury-card-final .card-desc {
                font-size: 14px;
                color: #666;
                line-height: 1.5;
                margin: 0 0 15px 0;
            }
            .msh-injury-card-final .card-link {
                color: #2B4666;
                text-decoration: none;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: color 0.3s ease;
            }
            .msh-injury-card-final .card-link:hover {
                color: #5CB3CC;
            }
            @media (max-width: 768px) {
                .msh-injuries-grid-final {
                    grid-template-columns: 1fr;
                }
                .msh-injury-card-final {
                    height: 350px;
                }
            }
        </style>
        
        <div class="msh-injuries-grid-final">
            <?php foreach ($settings['cards'] as $item) : ?>
                <div class="msh-injury-card-final">
                    <?php if (!empty($item['image']['url'])) : ?>
                        <img src="<?php echo esc_url($item['image']['url']); ?>" class="card-bg" alt="<?php echo esc_attr($item['title']); ?> - Main Street Health">
                    <?php endif; ?>
                    
                    <?php if (!empty($item['icon']['value'])) : ?>
                        <div class="card-icon">
                            <?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-content">
                        <h3 class="card-title"><?php echo esc_html($item['title']); ?></h3>
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
            <?php endforeach; ?>
        </div>
        <?php
    }
}

// Register widget function
function register_msh_injuries_grid_final() {
    \Elementor\Plugin::instance()->widgets_manager->register(new MSH_Injuries_Grid_Widget_Final());
}
add_action('elementor/widgets/register', 'register_msh_injuries_grid_final');
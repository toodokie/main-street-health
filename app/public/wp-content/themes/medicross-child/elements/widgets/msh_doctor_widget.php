<?php
/**
 * MSH Doctor Widget
 * Standalone doctor profile widget with custom fields
 */

if (!defined('ABSPATH')) exit;

class MSH_Doctor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'msh_doctor_widget';
    }

    public function get_title() {
        return esc_html__('MSH Doctor Profile', 'medicross');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['pxltheme-core'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Doctor Information', 'medicross'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'doctor_image',
            [
                'label' => esc_html__('Doctor Photo', 'medicross'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'doctor_specialty',
            [
                'label' => esc_html__('Specialty/Title', 'medicross'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'CHIROPRACTOR',
                'placeholder' => esc_html__('e.g., CHIROPRACTOR, PHYSIOTHERAPIST', 'medicross'),
            ]
        );

        $this->add_control(
            'doctor_name',
            [
                'label' => esc_html__('Doctor Name', 'medicross'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Dr. Tony Asaro',
                'placeholder' => esc_html__('Enter doctor name', 'medicross'),
            ]
        );

        $this->add_control(
            'doctor_credentials',
            [
                'label' => esc_html__('Credentials', 'medicross'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'DC, BSc., CAFCI',
                'placeholder' => esc_html__('e.g., DC, BSc., CAFCI', 'medicross'),
            ]
        );

        $this->add_control(
            'doctor_description',
            [
                'label' => esc_html__('Description', 'medicross'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 6,
                'default' => 'With 20 years of experience, Dr. Asaro provides evidence-based chiropractic care and acupuncture with a strong focus on patient education and collaborative rehabilitation. He treats a wide range of musculoskeletal, inflammatory, and non-inflammatory conditions and is a certified Concussion Management Provider.',
                'placeholder' => esc_html__('Enter doctor description', 'medicross'),
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
            'specialty_color',
            [
                'label' => esc_html__('Specialty Text Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#5CB3CC',
                'selectors' => [
                    '{{WRAPPER}} .msh-doctor-specialty' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Doctor Name Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-doctor-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'credentials_color',
            [
                'label' => esc_html__('Credentials Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-doctor-credentials' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#2B4666',
                'selectors' => [
                    '{{WRAPPER}} .msh-doctor-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Background Color', 'medicross'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .msh-doctor-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'medicross'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 12,
                ],
                'selectors' => [
                    '{{WRAPPER}} .msh-doctor-widget' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .msh-doctor-image img' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-bottom-left-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Get image URL
        $doctor_image = !empty($settings['doctor_image']['url']) ? $settings['doctor_image']['url'] : \Elementor\Utils::get_placeholder_image_src();
        ?>
        
        <div class="msh-doctor-widget">
            <div class="msh-doctor-image">
                <img src="<?php echo esc_url($doctor_image); ?>" alt="<?php echo esc_attr($settings['doctor_name']); ?>" />
            </div>
            <div class="msh-doctor-content">
                <?php if (!empty($settings['doctor_specialty'])) : ?>
                    <div class="msh-doctor-specialty"><?php echo esc_html($settings['doctor_specialty']); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($settings['doctor_name'])) : ?>
                    <h4 class="msh-doctor-name"><?php echo esc_html($settings['doctor_name']); ?></h4>
                <?php endif; ?>
                
                <?php if (!empty($settings['doctor_credentials'])) : ?>
                    <div class="msh-doctor-credentials"><?php echo esc_html($settings['doctor_credentials']); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($settings['doctor_description'])) : ?>
                    <div class="msh-doctor-description">
                        <?php echo wp_kses_post(wpautop($settings['doctor_description'])); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <style>
        .msh-doctor-widget {
            display: grid;
            grid-template-columns: 1fr 2.33fr;
            background-color: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 100%;
            min-height: 280px;
            font-family: 'Source Sans Pro', 'Segoe UI', sans-serif;
        }

        .msh-doctor-image {
            position: relative;
            width: 100%;
        }

        .msh-doctor-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .msh-doctor-image::before {
            content: '';
            display: block;
            width: 100%;
            padding-bottom: 100%;
        }

        .msh-doctor-content {
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: visible;
            min-height: 0;
        }

        .msh-doctor-specialty {
            color: #5CB3CC;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-family: 'Source Sans Pro', 'Segoe UI', sans-serif !important;
        }

        .msh-doctor-name {
            color: #2B4666;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 8px 0;
            font-family: 'Source Sans Pro', 'Segoe UI', sans-serif !important;
        }

        .msh-doctor-credentials {
            color: #2B4666;
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 20px;
            font-family: 'Source Sans Pro', 'Segoe UI', sans-serif !important;
        }

        .msh-doctor-description {
            color: #2B4666;
            font-size: 16px;
            line-height: 1.6;
            font-weight: 400;
            margin: 0;
            font-family: 'Source Sans Pro', 'Segoe UI', sans-serif !important;
        }

        /* Tablet portrait and mobile */
        @media (max-width: 768px) {
            .msh-doctor-widget {
                display: flex;
                flex-direction: column;
                height: auto;
            }
            
            .msh-doctor-image {
                width: 100%;
                height: 0;
                padding-bottom: 100%;
                position: relative;
                flex-shrink: 0;
            }
            
            .msh-doctor-image::before {
                display: none;
            }
            
            .msh-doctor-image img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 12px 12px 0 0;
            }
            
            .msh-doctor-content {
                padding: 20px 25px;
                height: auto;
                min-height: auto;
            }
            
            .msh-doctor-name {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .msh-doctor-content {
                padding: 15px 20px;
            }
            
            .msh-doctor-name {
                font-size: 20px;
            }
            
            .msh-doctor-specialty {
                font-size: 12px;
            }
            
            .msh-doctor-credentials,
            .msh-doctor-description {
                font-size: 14px;
            }
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force font family for all doctor widget elements
            var doctorElements = document.querySelectorAll('.msh-doctor-widget *');
            doctorElements.forEach(function(element) {
                element.style.setProperty('font-family', "'Source Sans Pro', 'Segoe UI', sans-serif", 'important');
            });
        });
        </script>

        <?php
    }
}

// Register the widget
function register_msh_doctor_widget($widgets_manager) {
    $widgets_manager->register(new MSH_Doctor_Widget());
}
add_action('elementor/widgets/register', 'register_msh_doctor_widget');
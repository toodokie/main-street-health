<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) { exit; }

class MSH_Mixed_Post_Carousel extends Widget_Base {
    public function get_name() { return 'msh_mixed_post_carousel'; }
    public function get_title() { return __('MSH Mixed Post Carousel', 'medicross-child'); }
    public function get_icon() { return 'eicon-posts-carousel'; }
    public function get_categories() { return ['pxltheme-core','general']; }
    public function get_script_depends() { 
        // Ensure Swiper is loaded
        wp_enqueue_script('swiper');
        wp_enqueue_script('pxl-swiper');
        return ['swiper','pxl-swiper']; 
    }

    protected function register_controls() {
        // Content
        $this->start_controls_section('content_section', [ 'label' => __('Content', 'medicross-child') ]);
        $this->add_control('mixed_post_types', [
            'label' => __('Post Types', 'medicross-child'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'default' => ['service','injury','pxl_product','portfolio'],
            'options' => [
                'service' => __('Services', 'medicross-child'),
                'injury' => __('Injuries', 'medicross-child'),
                'pxl_product' => __('Products & Devices', 'medicross-child'),
                'portfolio' => __('Conditions', 'medicross-child'),
            ],
        ]);

        $this->add_control('select_post_by', [
            'label' => __('Select posts by', 'medicross-child'),
            'type' => Controls_Manager::SELECT,
            'default' => 'recent',
            'options' => [
                'recent' => __('Latest', 'medicross-child'),
                'post_selected' => __('Posts selected', 'medicross-child'),
            ],
        ]);

        // Specific picks per type
        $this->add_control('source_service_post_ids', [
            'label' => __('Select Service posts', 'medicross-child'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $this->list_posts('service'),
            'condition' => ['select_post_by' => 'post_selected'],
        ]);
        $this->add_control('source_injury_post_ids', [
            'label' => __('Select Injury posts', 'medicross-child'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $this->list_posts('injury'),
            'condition' => ['select_post_by' => 'post_selected'],
        ]);
        $this->add_control('source_pxl_product_post_ids', [
            'label' => __('Select Product posts', 'medicross-child'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $this->list_posts('pxl_product'),
            'condition' => ['select_post_by' => 'post_selected'],
        ]);
        $this->add_control('source_portfolio_post_ids', [
            'label' => __('Select Condition posts', 'medicross-child'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $this->list_posts('portfolio'),
            'condition' => ['select_post_by' => 'post_selected'],
        ]);

        $this->add_control('orderby', [
            'label' => __('Order By', 'medicross-child'),
            'type' => Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [ 'date'=>'Date','title'=>'Title','rand'=>'Random','ID'=>'ID' ],
        ]);
        $this->add_control('order', [
            'label' => __('Sort Order', 'medicross-child'),
            'type' => Controls_Manager::SELECT,
            'default' => 'desc',
            'options' => [ 'desc'=>'Descending','asc'=>'Ascending' ],
        ]);
        $this->add_control('limit', [
            'label' => __('Total items', 'medicross-child'),
            'type' => Controls_Manager::NUMBER,
            'default' => 6,
        ]);
        $this->end_controls_section();

        // Layout/Style similar to service
        $this->start_controls_section('layout_section', [ 'label' => __('Layout', 'medicross-child') ]);
        $this->add_control('img_size', [ 'label'=>__('Image Size','medicross-child'),'type'=>Controls_Manager::TEXT, 'default'=>'370x418' ]);
        $this->add_control('show_excerpt', [ 'label'=>__('Show Excerpt','medicross-child'),'type'=>Controls_Manager::SWITCHER, 'default'=>'true' ]);
        $this->add_control('num_words', [ 'label'=>__('Excerpt Words','medicross-child'),'type'=>Controls_Manager::NUMBER, 'default'=>20 ]);
        $this->add_control('show_button', [ 'label'=>__('Show Button','medicross-child'),'type'=>Controls_Manager::SWITCHER, 'default'=>'true' ]);
        $this->add_control('button_text', [ 'label'=>__('Button Text','medicross-child'),'type'=>Controls_Manager::TEXT, 'default'=>__('Read More','medicross-child') ]);
        $this->end_controls_section();

        // Carousel basics
        $this->start_controls_section('carousel_section', [ 'label' => __('Carousel', 'medicross-child') ]);
        foreach (['col_xs'=>1,'col_sm'=>2,'col_md'=>3,'col_lg'=>4,'col_xl'=>4,'col_xxl'=>4] as $k=>$def){
            $this->add_control($k, [ 'label'=>strtoupper($k), 'type'=>Controls_Manager::SELECT, 'default'=>(string)$def,
                'options'=>['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6']]);
        }
        $this->add_control('slides_to_scroll', [ 'label'=>__('Slides Scroll','medicross-child'), 'type'=>Controls_Manager::SELECT, 'default'=>'1', 'options'=>['1'=>'1','2'=>'2','3'=>'3'] ]);
        $this->add_control('slides_gutter', [ 'label'=>__('Gutter (px)','medicross-child'), 'type'=>Controls_Manager::NUMBER, 'default'=>20 ]);
        $this->add_control('arrows',[ 'label'=>__('Show Arrows','medicross-child'), 'type'=>Controls_Manager::SWITCHER, 'default'=>'true' ]);
        $this->add_control('pagination',[ 'label'=>__('Show Pagination','medicross-child'), 'type'=>Controls_Manager::SWITCHER, 'default'=>'' ]);
        $this->add_control('autoplay',[ 'label'=>__('Autoplay','medicross-child'), 'type'=>Controls_Manager::SWITCHER, 'default'=>'' ]);
        $this->add_control('autoplay_speed',[ 'label'=>__('Autoplay Delay','medicross-child'), 'type'=>Controls_Manager::NUMBER, 'default'=>5000, 'condition'=>['autoplay'=>'true'] ]);
        $this->add_control('pause_on_hover',[ 'label'=>__('Pause on Hover','medicross-child'), 'type'=>Controls_Manager::SWITCHER, 'default'=>'true' ]);
        $this->add_control('center',[ 'label'=>__('Center Slides','medicross-child'), 'type'=>Controls_Manager::SWITCHER, 'default'=>'' ]);
        $this->end_controls_section();
    }

    private function list_posts($post_type){
        $opts = [];
        $q = get_posts(['post_type'=>$post_type,'numberposts'=>-1,'post_status'=>'publish']);
        foreach ($q as $p) { $opts[$p->ID] = $p->post_title; }
        return $opts;
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $types = (array)($s['mixed_post_types'] ?? ['service','injury','pxl_product','portfolio']);
        $limit = (int)($s['limit'] ?? 6);

        // Normalize switchers (Elementor returns 'yes' when enabled)
        $show_excerpt = isset($s['show_excerpt']) && ($s['show_excerpt'] === 'yes' || $s['show_excerpt'] === true || $s['show_excerpt'] === 'true');
        $show_button  = isset($s['show_button'])  && ($s['show_button']  === 'yes' || $s['show_button']  === true || $s['show_button']  === 'true');
        $arrows       = isset($s['arrows'])       && ($s['arrows']       === 'yes' || $s['arrows']       === true || $s['arrows']       === 'true');
        $pagination   = isset($s['pagination'])   && ($s['pagination']   === 'yes' || $s['pagination']   === true || $s['pagination']   === 'true');
        $autoplay     = isset($s['autoplay'])     && ($s['autoplay']     === 'yes' || $s['autoplay']     === true || $s['autoplay']     === 'true');
        $pause_on_hover = isset($s['pause_on_hover']) && ($s['pause_on_hover'] === 'yes' || $s['pause_on_hover'] === true || $s['pause_on_hover'] === 'true');
        $center       = isset($s['center'])       && ($s['center']       === 'yes' || $s['center']       === true || $s['center']       === 'true');

        // Build query
        if (($s['select_post_by'] ?? 'recent') === 'post_selected') {
            $ids = array_filter(array_merge(
                (array)($s['source_service_post_ids'] ?? []),
                (array)($s['source_injury_post_ids'] ?? []),
                (array)($s['source_pxl_product_post_ids'] ?? []),
                (array)($s['source_portfolio_post_ids'] ?? [])
            ));
            $query_args = [ 'post_type'=>$types, 'post__in'=>$ids, 'orderby'=>'post__in', 'posts_per_page'=>$limit ];
        } else {
            $query_args = [
                'post_type' => $types,
                'posts_per_page' => $limit,
                'orderby' => $s['orderby'] ?? 'date',
                'order' => $s['order'] ?? 'desc',
            ];
        }
        $q = new WP_Query($query_args);

        // Swiper settings similar to service carousel
        $opts = [
            'slide_direction'=>'horizontal',
            'slide_percolumn'=>1,
            'slide_percolumnfill'=>1,
            'slide_mode'=>'slide',
            'slides_to_show'=>(int)$s['col_xl'],
            'slides_to_show_xxl'=>(int)$s['col_xxl'],
            'slides_to_show_lg'=>(int)$s['col_lg'],
            'slides_to_show_md'=>(int)$s['col_md'],
            'slides_to_show_sm'=>(int)$s['col_sm'],
            'slides_to_show_xs'=>(int)$s['col_xs'],
            'slides_to_scroll'=>(int)$s['slides_to_scroll'],
            'slides_gutter'=>(int)($s['slides_gutter'] ?? 20),
            'arrow'=>$arrows,
            'pagination'=>$pagination,
            'autoplay'=>$autoplay,
            'delay'=>(int)($s['autoplay_speed'] ?? 5000),
            'pause_on_hover'=>$pause_on_hover,
            'loop'=>false,
            'speed'=>500,
            'center'=>$center,
        ];

        echo '<div class="pxl-swiper-slider pxl-service-carousel pxl-service-carousel1 pxl-service-style1 msh-mixed-carousel">';
        echo '<div class="pxl-carousel-inner">';
        $dir = is_rtl() ? 'rtl' : 'ltr';
        printf('<div class="pxl-swiper-container" dir="%s" data-settings="%s">', esc_attr($dir), esc_attr(wp_json_encode($opts)));
        echo '<div class="pxl-swiper-wrapper">';

        if ($q->have_posts()) {
            while($q->have_posts()){ $q->the_post();
                $post_id = get_the_ID();
                // Image
                $img_param = 'medium_large';
                if (!empty($s['img_size'])) {
                    $raw = trim($s['img_size']);
                    if (preg_match('/^(\d+)x(\d+)$/', $raw, $m)) {
                        $img_param = [ (int)$m[1], (int)$m[2] ];
                    } else {
                        $img_param = $raw;
                    }
                } else {
                    $img_param = [370,418];
                }
                $thumb = get_the_post_thumbnail($post_id, $img_param);
                // Icon priority: service icon meta, injury icon meta
                $service_icon_type = get_post_meta($post_id, 'service_icon_type', true);
                $service_icon_font = get_post_meta($post_id, 'service_icon_font', true);
                $service_icon_img = get_post_meta($post_id, 'service_icon_img', true);
                $injury_icon_id = (int)get_post_meta($post_id, '_injury_icon_id', true);

                echo '<div class="pxl-swiper-slide">';
                echo '<div class="pxl-post--inner">';
                echo '<div class="pxl-post--featured">';
                printf('<a href="%s">%s', esc_url(get_permalink($post_id)), $thumb ?: '');
                if ($service_icon_type === 'icon' && !empty($service_icon_font)) {
                    printf('<span class="pxl-post--icon"><i class="%s"></i></span>', esc_attr($service_icon_font));
                } elseif (!empty($service_icon_img['id'])) {
                    $icon_html = wp_get_attachment_image($service_icon_img['id'], 'full');
                    echo '<span class="pxl-post--icon">'.$icon_html.'</span>';
                } elseif ($injury_icon_id) {
                    echo '<span class="pxl-post--icon">'.wp_get_attachment_image($injury_icon_id,'full').'</span>';
                }
                echo '</a></div>'; // featured

                echo '<div class="pxl-holder-content">';
                printf('<h3 class="pxl-post--title"><a href="%s">%s</a></h3>', esc_url(get_permalink($post_id)), esc_html(get_the_title($post_id)));
                if ($show_excerpt) {
                    $excerpt = has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_strip_all_tags(get_post_field('post_content',$post_id));
                    $excerpt = wp_trim_words($excerpt, (int)($s['num_words'] ?? 20), '');
                    printf('<div class="pxl-post--content">%s</div>', esc_html($excerpt));
                }
                if ($show_button) {
                    $btn_text = !empty($s['button_text']) ? $s['button_text'] : __('Read More','medicross-child');
                    printf('<div class="pxl-post--readmore"><a class="btn-readmore" href="%s"><span>%s</span><i class="flaticon flaticon-next"></i></a></div>', esc_url(get_permalink($post_id)), esc_html($btn_text));
                }
                echo '</div>'; // holder-content
                echo '</div></div>'; // inner/slide
            }
            wp_reset_postdata();
        }

        echo '</div></div>'; // wrapper/container
        echo '</div>'; // inner

        // Pagination dots
        if ($pagination) {
            echo '<div class="pxl-swiper-dots style-1"></div>';
        }
        // Arrow controls
        if ($arrows) {
            echo '<div class="pxl-swiper-arrow-wrap style-1">'
                . '<div class="pxl-swiper-arrow pxl-swiper-arrow-prev" tabindex="0" role="button" aria-label="previous slide"><i class="flaticon flaticon-next"></i></div>'
                . '<div class="pxl-swiper-arrow pxl-swiper-arrow-next" tabindex="0" role="button" aria-label="next slide"><i class="flaticon flaticon-next" style="transform:scaleX(-1);"></i></div>'
                . '</div>';
        }

        echo '</div>'; // slider
    }
}

<?php
/**
 * MSH Image Optimizer
 * Optimizes published images for Main Street Health healthcare website
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('MSH_Safe_Rename_System')) {
    require_once get_stylesheet_directory() . '/inc/class-msh-safe-rename-system.php';
}

class MSH_Contextual_Meta_Generator {
    private $business_name = 'Main Street Health';
    private $location = 'Hamilton';
    private $location_slug = 'hamilton';

    private $service_keyword_map = [
        'physiotherapy' => [
            'default' => 'WSIB approved. MVA recovery. First responder programs.',
            'assessment' => 'Functional assessments. Return-to-work evaluation.',
            'acute' => 'Immediate injury care. Same-day appointments available.'
        ],
        'chiropractic' => [
            'default' => 'Spinal care. Workplace injury treatment. WSIB claims supported.',
            'assessment' => 'Spinal assessment and posture evaluation services.',
            'acute' => 'Acute back and neck pain management with direct billing.'
        ],
        'massage' => [
            'default' => 'Registered massage therapy. Insurance coverage available.',
            'assessment' => 'Musculoskeletal assessment and soft tissue release.',
            'acute' => 'Pain relief for muscle strain and injury recovery.'
        ],
        'acupuncture' => [
            'default' => 'Evidence-based acupuncture care. WSIB approved provider.',
            'assessment' => 'Assessment-driven acupuncture plans for recovery.',
            'acute' => 'Immediate relief protocols for pain and inflammation.'
        ],
        'rehabilitation' => [
            'default' => 'Return-to-work programs. WSIB approved. Direct billing.',
            'assessment' => 'Functional capacity assessments and workplace evaluations.',
            'acute' => 'Comprehensive rehabilitation for acute injuries.'
        ],
        'motor-vehicle-accident' => [
            'default' => 'MVA rehabilitation with insurance coordination and direct billing.',
            'assessment' => 'Comprehensive post-collision assessments and recovery plans.',
            'acute' => 'Immediate collision injury support with medical-legal documentation.'
        ],
        'workplace-injury' => [
            'default' => 'WSIB workplace injury rehabilitation with return-to-work planning.',
            'assessment' => 'Workplace functional assessments and ergonomic planning.',
            'acute' => 'Rapid workplace injury care with WSIB reporting support.'
        ],
        'first-responder' => [
            'default' => 'Dedicated first responder rehabilitation programs with duty-ready focus.',
            'assessment' => 'Operational fitness assessments for first responders.',
            'acute' => 'Immediate injury care with expedited recovery pathways.'
        ]
    ];

    private $service_keywords = [
        'physiotherapy' => ['physio', 'physiotherapy', 'physical therapy', 'rehab'],
        'chiropractic' => ['chiro', 'chiropractic', 'spinal'],
        'massage' => ['massage', 'rmt'],
        'acupuncture' => ['acupuncture', 'acupucture', 'needling', 'needle'],
        'rehabilitation' => ['rehab', 'recovery', 'rehabilitation'],
        'motor-vehicle-accident' => ['mva', 'motor vehicle', 'collision', 'auto injury', 'car accident'],
        'workplace-injury' => ['wsib', 'workplace', 'work injury', 'return to work', 'occupational'],
        'first-responder' => ['first responder', 'firefighter', 'paramedic', 'police', 'dispatcher']
    ];

    public function detect_context($attachment_id, $ignore_manual = false) {
        $context = [
            'type' => 'clinical',
            'page_type' => null,
            'page_title' => null,
            'service' => 'rehabilitation',
            'parent_id' => 0,
            'tags' => [],
            'manual' => false,
            'attachment_id' => (int) $attachment_id,
            'attachment_title' => '',
            'attachment_slug' => '',
            'file_basename' => '',
            'subject_name' => ''
        ];

        $manual = get_post_meta($attachment_id, '_msh_context', true);
        $manual = is_string($manual) ? trim($manual) : '';
        $context['manual_value'] = $manual;

        if (!$ignore_manual && $manual !== '') {
            $context['type'] = sanitize_text_field($manual);
            $context['manual'] = true;
        }

        if ($suggested_filename !== '') {
            update_post_meta($attachment_id, '_msh_suggested_filename', $suggested_filename);
        } else {
            delete_post_meta($attachment_id, '_msh_suggested_filename');
        }
        $attachment = get_post($attachment_id);
        $parent_id = $attachment ? (int) $attachment->post_parent : 0;
        $context['parent_id'] = $parent_id;
        if ($attachment) {
            $context['attachment_title'] = $attachment->post_title;
        }

        $attachment_title = $attachment ? strtolower((string) $attachment->post_title) : '';
        $file_meta = get_post_meta($attachment_id, '_wp_attached_file', true);
        $file_name = $file_meta ? basename($file_meta) : '';
        $file_basename = $file_name ? strtolower(pathinfo($file_name, PATHINFO_FILENAME)) : '';
        $context['file_basename'] = $file_basename;
        $context['attachment_slug'] = $this->slugify(!empty($context['attachment_title']) ? $context['attachment_title'] : $file_basename);
        $context['filename'] = $file_name;

        if (!$context['manual']) {
            $meta_sizes = wp_get_attachment_metadata($attachment_id);
            $width = 0;
            $height = 0;
            if (is_array($meta_sizes)) {
                $width = isset($meta_sizes['width']) ? (int) $meta_sizes['width'] : 0;
                $height = isset($meta_sizes['height']) ? (int) $meta_sizes['height'] : 0;
            }

            $icon_context = $this->detect_icon_context($attachment_id, $context, $width, $height);
            if ($icon_context && $context['type'] === 'clinical') {
                $context = array_merge($context, $icon_context);
            }

            $product_context = $this->detect_product_context($attachment_id, $context);
            if ($product_context && $context['type'] === 'clinical') {
                $context = array_merge($context, $product_context);
            }
        }


        if ($attachment) {
            $this->apply_attachment_context($context, $attachment, $attachment_title, $file_basename);
        }

        if ($parent_id > 0) {
            $parent_post = get_post($parent_id);
            if ($parent_post) {
                $context['page_type'] = get_post_type($parent_post);
                $context['page_title'] = $parent_post->post_title;
                $this->apply_parent_context($context, $parent_post, $attachment_id, $file_basename);
            }
        }

        // Featured usage (e.g., attached as featured image on other posts)
        $featured_usage = $this->find_featured_usage($attachment_id);
        if (!empty($featured_usage)) {
            $first = $featured_usage[0];
            if (empty($context['page_title'])) {
                $context['page_title'] = $first['post_title'];
                $context['page_type'] = $first['post_type'];
            }
            $this->apply_usage_context($context, $featured_usage, $file_basename);
        }

        // Media categories / taxonomies
        $media_terms = wp_get_object_terms($attachment_id, ['media_category'], ['fields' => 'slugs']);
        if (!is_wp_error($media_terms) && !empty($media_terms)) {
            $context['tags'] = array_merge($context['tags'], $media_terms);
            if (in_array('team', $media_terms, true)) {
                $context['type'] = 'team';
            } elseif (in_array('testimonials', $media_terms, true)) {
                $context['type'] = 'testimonial';
            } elseif (in_array('facility', $media_terms, true)) {
                $context['type'] = 'facility';
            } elseif (in_array('equipment', $media_terms, true)) {
                $context['type'] = 'equipment';
            }
        }

        $combined_indicator = strtolower(trim(($context['attachment_title'] ?? '') . ' ' . $file_basename));
        if (!$context['manual'] && $context['type'] === 'clinical' && strpos($combined_indicator, 'icon') !== false) {
            $context['type'] = 'service-icon';
            $context['service'] = $this->extract_service_type($context['page_title'], $context['tags'], [$combined_indicator]);
        }

        // Service extraction for clinical images
        if ($context['type'] === 'clinical') {
            $extra_sources = array_filter([$attachment_title, $file_basename]);
            $context['service'] = $this->extract_service_type($context['page_title'], $context['tags'], $extra_sources);
        }

        if (!$context['manual']) {
            $asset_type = $this->detect_asset_type(strtolower(trim(($context['attachment_title'] ?? '') . ' ' . $file_basename . ' ' . ($context['page_title'] ?? ''))));
            if ($asset_type === 'logo' && $context['type'] === 'clinical') {
                $context['type'] = 'business';
                $context['asset'] = 'logo';
            } elseif ($asset_type === 'icon' && $context['type'] === 'clinical') {
                $context['type'] = 'service-icon';
            } elseif ($asset_type === 'frame' && $context['type'] === 'clinical') {
                $context['type'] = 'business';
                $context['asset'] = 'graphic';
            } elseif ($asset_type === 'product' && $context['type'] === 'clinical') {
                $context['type'] = 'equipment';
                $context['asset'] = 'product';
                $context['product_type'] = $this->extract_product_type($file_basename, $context['attachment_title']);
            } elseif ($asset_type === 'graphic' && $context['type'] === 'clinical') {
                $context['type'] = 'business';
                $context['asset'] = 'graphic';
            }
        }

        if ($context['type'] === 'testimonial' && empty($context['subject_name'])) {
            $context['subject_name'] = $this->extract_subject_name($context['attachment_title'] ?: str_replace(['-', '_'], ' ', $file_basename));
        }

        $context['source'] = $context['manual'] ? 'manual' : 'auto';

        if (!$context['manual']) {
            update_post_meta($attachment_id, '_msh_auto_context', $context['type']);
        }

        return $context;
    }

    private function apply_parent_context(array &$context, WP_Post $parent_post, $attachment_id, $file_basename = '') {
        if (!empty($context['manual'])) {
            return;
        }

        $title = $parent_post->post_title;
        $post_type = get_post_type($parent_post);

        if (in_array($post_type, ['team', 'staff', 'msh_team_member'], true)) {
            $context['type'] = 'team';
            $context['staff_name'] = $title;
            return;
        }

        $categories = wp_get_post_categories($parent_post->ID, ['fields' => 'slugs']);
        if (!is_wp_error($categories) && !empty($categories)) {
            $context['tags'] = array_merge($context['tags'], $categories);
            if (array_intersect($categories, ['team', 'staff'])) {
                $context['type'] = 'team';
                $context['staff_name'] = $title;
                return;
            }
            if (array_intersect($categories, ['testimonials', 'reviews', 'success-stories'])) {
                $context['type'] = 'testimonial';
                if (empty($context['subject_name'])) {
                    $context['subject_name'] = $this->extract_subject_name($title ?: $file_basename);
                }
                return;
            }
            if (array_intersect($categories, ['facility', 'clinic', 'office'])) {
                $context['type'] = 'facility';
            }
            if (array_intersect($categories, ['equipment'])) {
                $context['type'] = 'equipment';
            }
        }

        $template = get_page_template_slug($parent_post->ID);
        if ($template) {
            if (strpos($template, 'team') !== false) {
                $context['type'] = 'team';
                $context['staff_name'] = $title;
            } elseif (strpos($template, 'testimonial') !== false) {
                $context['type'] = 'testimonial';
                if (empty($context['subject_name'])) {
                    $context['subject_name'] = $this->extract_subject_name($title ?: $file_basename);
                }
            } elseif (strpos($template, 'facility') !== false) {
                $context['type'] = 'facility';
            }
        }

        if ($context['type'] === 'clinical') {
            $extra_sources = array_filter([$title, $file_basename]);
            $context['service'] = $this->extract_service_type($title, $context['tags'], $extra_sources);
        }

        // Gallery detection for reference
        if (!empty($parent_post->post_content) && has_shortcode($parent_post->post_content, 'gallery')) {
            if (strpos($parent_post->post_content, (string) $attachment_id) !== false) {
                $context['in_gallery'] = true;
                $context['gallery_page'] = $title;
            }
        }
    }

    private function extract_service_type($title, array $tags = [], array $extra_sources = []) {
        $sources = [];
        if (!empty($title)) {
            $sources[] = strtolower((string) $title);
        }
        foreach ($tags as $tag) {
            $sources[] = strtolower((string) $tag);
        }
        foreach ($extra_sources as $extra) {
            if (!empty($extra)) {
                $sources[] = strtolower((string) $extra);
            }
        }

        foreach ($sources as $text) {
            foreach ($this->service_keywords as $service => $keywords) {
                foreach ($keywords as $keyword) {
                    if ($keyword !== '' && strpos($text, $keyword) !== false) {
                        return $service;
                    }
                }
            }
        }

        return 'rehabilitation';
    }

    private function find_featured_usage($attachment_id) {
        global $wpdb;
        $posts = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT posts.ID, posts.post_title, posts.post_type \n                 FROM {$wpdb->postmeta} meta \n                 INNER JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id \n                 WHERE meta.meta_key = '_thumbnail_id' AND meta.meta_value = %d AND posts.post_status = 'publish'",
                $attachment_id
            ),
            ARRAY_A
        );

        return $posts ?: [];
    }

    private function apply_usage_context(array &$context, array $usage, $file_basename = '') {
        if (!empty($context['manual'])) {
            return;
        }

        foreach ($usage as $item) {
            $post_type = $item['post_type'];
            $title = $item['post_title'];

            if (empty($context['page_title'])) {
                $context['page_title'] = $title;
                $context['page_type'] = $post_type;
            }

            if (in_array($post_type, ['team', 'staff', 'msh_team_member'], true)) {
                $context['type'] = 'team';
                $context['staff_name'] = $title;
                return;
            }

            if (stripos($title, 'testimonial') !== false || stripos($title, 'review') !== false) {
                $context['type'] = 'testimonial';
                if (empty($context['subject_name'])) {
                    $context['subject_name'] = $this->extract_subject_name($title ?: $file_basename);
                }
                return;
            }

            if ($context['type'] === 'clinical') {
                $service = $this->extract_service_type($title, [], array_filter([$file_basename]));
                if (!empty($service)) {
                    $context['service'] = $service;
                }
            }
        }
    }

    private function apply_attachment_context(array &$context, WP_Post $attachment, $title_lower, $file_basename) {
        if (!empty($context['manual'])) {
            return;
        }

        $combined = trim($title_lower . ' ' . $file_basename);

        if ($context['type'] !== 'team' && $this->text_contains_any($combined, ['team', 'staff', 'doctor', 'dr-', 'physiotherapist', 'therapist', 'rmt', 'chiropractor'])) {
            $context['type'] = 'team';
            $context['staff_name'] = $attachment->post_title;
            return;
        }

        if ($context['type'] !== 'testimonial' && $this->text_contains_any($combined, ['testimonial', 'review', 'patient-story', 'patient_story', 'success', 'case-study', 'before-after'])) {
            $context['type'] = 'testimonial';
            if (empty($context['subject_name'])) {
                $context['subject_name'] = $this->extract_subject_name($attachment->post_title ?: $file_basename);
            }
            return;
        }

        if ($context['type'] !== 'facility' && $this->text_contains_any($combined, ['facility', 'clinic', 'office', 'reception', 'lobby', 'exterior', 'interior', 'waiting-room', 'front-desk'])) {
            $context['type'] = 'facility';
            return;
        }

        if ($context['type'] !== 'equipment' && $this->text_contains_any($combined, ['equipment', 'machine', 'device', 'laser', 'ultrasound', 'tens', 'table', 'traction'])) {
            $context['type'] = 'equipment';
        }
    }

    private function text_contains_any($text, array $needles) {
        if ($text === '') {
            return false;
        }

        foreach ($needles as $needle) {
            if ($needle !== '' && strpos($text, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    private function extract_subject_name($text) {
        $text = trim((string) $text);
        if ($text === '') {
            return '';
        }

        $text = preg_replace('/\.[a-z0-9]{2,5}$/i', '', $text);
        $text = str_replace(['-', '_'], ' ', $text);
        $text = preg_replace('/\b(review|testimonial|success|story|patient|case\s*study)\b/i', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        if ($text === '') {
            return 'Patient';
        }

        $normalized = ucwords(strtolower($text));
        return $normalized ?: 'Patient';
    }

    private function truncate_slug($slug, $max_words = 4) {
        if (empty($slug)) {
            return '';
        }

        $parts = preg_split('/-+/', strtolower($slug));
        $stopwords = ['and', 'with', 'the', 'for', 'a', 'an', 'to', 'of', 'in', 'at', 'on', 'by', 'from', 'about'];

        $filtered = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '' || in_array($part, $stopwords, true) || is_numeric($part)) {
                continue;
            }
            $filtered[] = $part;
            if (count($filtered) >= max(1, (int) $max_words)) {
                break;
            }
        }

        if (empty($filtered)) {
            $filtered = array_slice(array_filter($parts), 0, 1);
        }

        return implode('-', $filtered);
    }

    private function format_service_label($service) {
        if (empty($service)) {
            return 'Rehabilitation';
        }

        $label = str_replace(['-', '_'], ' ', strtolower($service));
        $label = preg_replace('/\s+/', ' ', $label);

        return ucwords(trim($label));
    }

    private function merge_slug_fragments($base, $extra) {
        if (empty($extra)) {
            return $base;
        }

        $base_parts = array_filter(explode('-', strtolower($base)));
        $extra_parts = array_filter(explode('-', strtolower($extra)));

        $combined = $base_parts;
        foreach ($extra_parts as $part) {
            if (!in_array($part, $combined, true)) {
                $combined[] = $part;
            }
        }

        return implode('-', $combined);
    }

    private function normalize_icon_concept($concept_source) {
        $concept_source = strtolower(trim((string) $concept_source));
        $concept_source = preg_replace('/\.[a-z0-9]+$/', '', $concept_source);
        $concept_source = str_replace(['_', ' '], '-', $concept_source);
        $concept_source = preg_replace('/-+/', '-', $concept_source);
        $concept_source = trim($concept_source, '-');

        $slug = preg_replace('/-?icon$/', '', $concept_source);

        if ($slug === '') {
            $slug = 'service';
        }

        $label = $this->format_service_label($slug);

        return [$slug, $label];
    }


    private function detect_product_context($attachment_id, array $context) {
        $file_meta = get_post_meta($attachment_id, '_wp_attached_file', true);
        $filename = strtolower($file_meta ? basename($file_meta) : '');
        $title = strtolower($context['attachment_title'] ?? '');
        $caption = strtolower((string) get_post_field('post_excerpt', $attachment_id));
        $combined = $filename . ' ' . $title . ' ' . $caption;

        $patterns = [
            '/mediflow|waterbase|pillow/' => ['product_type' => 'therapeutic-pillow'],
            '/biofreeze|gel|cream/' => ['product_type' => 'pain-relief'],
            '/tens|electrotherapy|stimulator/' => ['product_type' => 'tens-unit'],
            '/frame-?\d+|custom.?sole|orthotic|insole/' => ['product_type' => 'custom-orthotics'],
            '/compression|stocking|sock/' => ['product_type' => 'compression-therapy'],
            '/(ankle|wrist|knee|elbow|shoulder|back|neck|foot|hand).*(brace|support|wrap|sleeve)/' => ['product_type' => 'support-brace'],
            '/brace|support|wrap|sleeve|splint|stabilizer/' => ['product_type' => 'support-product']
        ];

        foreach ($patterns as $pattern => $data) {
            if (preg_match($pattern, $combined)) {
                return [
                    'type' => 'equipment',
                    'service' => 'rehabilitation',
                    'product_type' => $data['product_type'],
                    'asset' => 'product',
                    'source' => 'auto'
                ];
            }
        }

        return null;
    }

    private function detect_icon_context($attachment_id, array $context, $width = 0, $height = 0) {
        $file_meta = get_post_meta($attachment_id, '_wp_attached_file', true);
        $filename = strtolower($file_meta ? basename($file_meta) : '');
        $directory = strtolower($file_meta ? dirname($file_meta) : '');
        $title = strtolower($context['attachment_title'] ?? '');
        $caption = strtolower((string) get_post_field('post_excerpt', $attachment_id));
        $combined = $filename . ' ' . $title . ' ' . $caption . ' ' . $directory;

        $icon_keyword = preg_match('/icon|\.svg$|\/icons\//', $combined);
        $concept_keyword = preg_match('/(chronic[-_ ]?pain|sport[-_ ]?injur|work[-_ ]?related[-_ ]?injur|workplace[-_ ]?injur|motor[-_ ]?icon|vehicle[-_ ]?icon|accident|wsib|program)/', $combined);

        if (!$icon_keyword && $concept_keyword) {
            $max_icon_dimension = 600;

            if ($width > 0 && $height > 0 && ($width > $max_icon_dimension || $height > $max_icon_dimension)) {
                return null;
            }
        }

        if (!$icon_keyword && !$concept_keyword) {
            return null;
        }

        $category = 'service';
        if (preg_match('/chronic|pain|injur|condition|mobility|wellness|posture|sport/', $combined)) {
            $category = 'condition';
        } elseif (preg_match('/wsib|work([-_ ]?related)|workplace|mva|vehicle|program|rehab-plan/', $combined)) {
            $category = 'program';
        } elseif (preg_match('/team|staff|doctor|therapist/', $combined)) {
            $category = 'team';
        }

        $concept_source = $context['attachment_slug'] ?? pathinfo($filename, PATHINFO_FILENAME);

        // If still no obvious concept, try inferring from service keywords
        if (!$icon_keyword) {
            foreach ($this->service_keywords as $service => $keywords) {
                if ($this->text_contains_any($combined, $keywords)) {
                    $concept_source = $service . '-icon';
                    $category = 'service';
                    break;
                }
            }
        }

        list($concept_slug, $concept_label) = $this->normalize_icon_concept($concept_source);

        return [
            'type' => 'icon',
            'icon_type' => $category,
            'icon_concept' => $concept_slug,
            'icon_concept_label' => $concept_label,
            'asset' => 'icon',
            'source' => 'auto'
        ];
    }

    private function detect_asset_type($text) {
        if (empty($text)) {
            return false;
        }

        $patterns = [
            'logo' => '/\b(logo|brandmark|wordmark|seal|badge)\b/i',
            'icon' => '/\b(icon|symbol|glyph|badge)\b/i',
            'frame' => '/\b(frame|border|template|layout|mockup)\b/i',
            'product' => '/\b(pillow|brace|support|sleeve|wrap|tens|biofreeze|orthotic|stocking|pillow|equipment|device|gel|cream)\b/i',
            'equipment' => '/\b(machine|table|apparatus|equipment|device|tool)\b/i',
            'graphic' => '/\b(graphic|illustration|diagram|infographic|chart)\b/i'
        ];

        foreach ($patterns as $type => $pattern) {
            if (preg_match($pattern, $text)) {
                return $type;
            }
        }

        return false;
    }

    private function extract_product_type($basename, $title) {
        $combined = strtolower($basename . ' ' . $title);
        $keywords = ['pillow', 'brace', 'support', 'sleeve', 'wrap', 'gel', 'cream', 'tape', 'orthotic', 'stocking'];

        foreach ($keywords as $keyword) {
            if (strpos($combined, $keyword) !== false) {
                return $keyword;
            }
        }

        return 'rehabilitation-product';
    }

    private function ensure_unique_title($title, $attachment_id) {
        if (!$attachment_id) {
            return $title;
        }

        global $wpdb;

        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND ID != %d AND post_type = 'attachment' LIMIT 1",
            $title,
            $attachment_id
        ));

        if (!$existing) {
            return $title;
        }

        $variants = [' Session', ' Treatment', ' Case Study', ' Program', ' Assessment'];
        $index = $attachment_id % count($variants);

        return $title . $variants[$index];
    }

    public function generate_meta_fields($attachment_id, array $context) {
        switch ($context['type']) {
            case 'team':
                return $this->generate_team_meta($context);
            case 'testimonial':
                return $this->generate_testimonial_meta($context);
            case 'icon':
                return $this->generate_icon_meta($context);
            case 'service-icon':
                return $this->generate_service_icon_meta($context);
            case 'facility':
                return $this->generate_facility_meta($context);
            case 'equipment':
                if (!empty($context['asset']) && $context['asset'] === 'product') {
                    return $this->generate_product_meta($context);
                }
                return $this->generate_equipment_meta($context);
            case 'business':
                if (!empty($context['asset']) && $context['asset'] === 'logo') {
                    return $this->generate_logo_meta($context);
                }
                return $this->generate_business_meta($context);
            case 'clinical':
            default:
                return $this->generate_clinical_meta($context);
        }
    }

    public function generate_filename_slug($attachment_id, array $context, $extension = null) {
        switch ($context['type']) {
            case 'team':
                $name = !empty($context['staff_name']) ? $context['staff_name'] : 'team-member';
                return $this->slugify("{$this->business_name}-team-{$name}");
            case 'testimonial':
                $subject_slug = !empty($context['attachment_slug']) ? $this->truncate_slug($context['attachment_slug'], 3) : 'patient';
                return $this->slugify('patient-testimonial-' . $subject_slug . '-' . $this->location_slug);
            case 'icon':
                $concept_source = $context['icon_concept'] ?? $context['attachment_slug'] ?? 'service';
                $concept = $this->slugify($concept_source);
                if ($concept === '') {
                    $concept = 'service';
                }
                return $this->slugify($concept . '-icon-' . $this->location_slug);
            case 'service-icon':
                $concept_slug = !empty($context['icon_concept']) ? $this->slugify($context['icon_concept']) : '';
                if ($concept_slug !== '') {
                    return $this->slugify($concept_slug . '-icon-' . $this->location_slug);
                }
                $service_slug = $this->slugify($context['service'] ?? 'service');
                return $this->slugify($service_slug . '-icon-' . $this->location_slug);
            case 'facility':
                return $this->slugify($this->business_name . '-facility-' . $this->location_slug);
            case 'equipment':
                if (!empty($context['asset']) && $context['asset'] === 'product') {
                    $product_map = [
                        'therapeutic-pillow' => 'pillow',
                        'custom-orthotics' => 'orthotics',
                        'support-brace' => 'brace',
                        'support-product' => 'support',
                        'tens-unit' => 'tens-unit',
                        'pain-relief' => 'pain-relief',
                        'compression-therapy' => 'compression'
                    ];
                    $product_type = $context['product_type'] ?? 'support';
                    $product_slug = $this->truncate_slug($product_map[$product_type] ?? $product_type, 2);
                    $components = array_filter([$product_slug, $this->location_slug]);
                    return $this->slugify(implode('-', $components));
                }
                return $this->slugify('rehabilitation-equipment-' . $this->location_slug);
            case 'business':
                if (!empty($context['asset']) && $context['asset'] === 'logo') {
                    return $this->slugify($this->business_name . '-logo-' . $this->location_slug);
                }
                return $this->slugify($this->business_name . '-' . $this->location_slug . '-branding');
            case 'clinical':
            default:
                $service = $context['service'] ?? 'rehabilitation';
                $parts = [$service, $this->location_slug];
                $base_slug = implode('-', array_filter($parts));

                if (!empty($context['attachment_slug'])) {
                    $extra = $this->truncate_slug($context['attachment_slug'], 3);
                    $base_slug = $this->merge_slug_fragments($base_slug, $extra);
                }

                return $this->slugify($base_slug);
        }
    }

    private function generate_clinical_meta(array $context) {
        $service = $context['service'] ?? 'rehabilitation';
        $service_label = $this->format_service_label($service);
        $service_lower = strtolower($service_label);
        $page_title_lower = strtolower((string) ($context['page_title'] ?? ''));

        $variant = 'default';
        if (strpos($page_title_lower, 'assessment') !== false) {
            $variant = 'assessment';
        } elseif (strpos($page_title_lower, 'acute') !== false) {
            $variant = 'acute';
        }

        $keyword_line = $this->get_service_keyword_line($service, $variant);

        $action_word = [
            'default' => 'Treatment',
            'assessment' => 'Assessment',
            'acute' => 'Care'
        ][$variant] ?? 'Treatment';

        $title_focus = trim("{$service_label} {$action_word}");
        $title = $this->ensure_unique_title(
            "{$title_focus} - {$this->business_name} {$this->location}",
            $context['attachment_id'] ?? 0
        );

        $caption_map = [
            'default' => "Professional {$service_label} treatment session",
            'assessment' => "Clinical {$service_label} assessment in progress",
            'acute' => "Immediate {$service_label} care for acute injuries"
        ];

        $description_map = [
            'default' => "Comprehensive {$service_lower} care tailored to patient recovery. {$keyword_line}",
            'assessment' => "Detailed {$service_lower} assessment with measurable progress tracking. {$keyword_line}",
            'acute' => "Rapid-response {$service_lower} care supporting immediate relief. {$keyword_line}"
        ];

        $alt_map = [
            'default' => "{$service_label} treatment at {$this->business_name} {$this->location} rehabilitation clinic",
            'assessment' => "{$service_label} assessment at {$this->business_name} {$this->location} clinic",
            'acute' => "{$service_label} care team providing acute support at {$this->business_name} {$this->location}"
        ];

        return [
            'title' => $this->clean_text($title),
            'alt_text' => $this->clean_text($alt_map[$variant] ?? $alt_map['default']),
            'caption' => $this->clean_text($caption_map[$variant] ?? $caption_map['default']),
            'description' => $this->clean_text($description_map[$variant] ?? $description_map['default'])
        ];
    }

    private function get_service_keyword_line($service, $variant) {
        $service = strtolower($service);
        $variants = $this->service_keyword_map[$service] ?? $this->service_keyword_map['rehabilitation'];
        return $variants[$variant] ?? $variants['default'];
    }

    private function generate_team_meta(array $context) {
        $name = !empty($context['staff_name']) ? $context['staff_name'] : 'Healthcare Professional';

        return [
            'title' => $this->clean_text("{$name} - {$this->business_name} {$this->location}"),
            'alt_text' => $this->clean_text("{$name}, healthcare professional at {$this->business_name} {$this->location}"),
            'caption' => $this->clean_text("{$name} - Registered rehabilitation provider"),
            'description' => $this->clean_text("{$name} provides expert rehabilitation services at {$this->business_name} in {$this->location}. Specialized in WSIB and MVA recovery programs.")
        ];
    }

    private function generate_testimonial_meta(array $context) {
        $subject = !empty($context['subject_name']) ? $context['subject_name'] : 'Patient';
        $service = $context['service'] ?? 'rehabilitation';
        $service_label = $this->format_service_label($service);
        $service_lower = strtolower($service_label);
        $keywords_line = $this->get_service_keyword_line($service, 'default');

        $caption = sprintf(
            '%s shares %s recovery experience at %s',
            $subject,
            $service_lower,
            $this->business_name
        );

        $description = sprintf(
            'Patient testimonial from %s highlighting %s recovery at %s %s. %s',
            $subject,
            $service_lower,
            $this->business_name,
            $this->location,
            $keywords_line
        );

        $title_base = "{$subject} Patient Success Story - {$this->business_name} {$this->location}";
        $final_title = $this->ensure_unique_title($title_base, $context['attachment_id'] ?? 0);

        return [
            'title' => $this->clean_text($final_title),
            'alt_text' => $this->clean_text("Patient {$subject} shares {$service_lower} recovery story at {$this->business_name} {$this->location}"),
            'caption' => $this->clean_text($caption),
            'description' => $this->clean_text($description)
        ];
    }

    private function generate_service_icon_meta(array $context) {
        $service = $context['service'] ?? 'rehabilitation';
        $service_label = $this->format_service_label($service);
        $service_lower = strtolower($service_label);

        return [
            'title' => $this->clean_text("{$service_label} Services - {$this->business_name} {$this->location}"),
            'alt_text' => $this->clean_text("{$service_label} rehabilitation services icon"),
            'caption' => $this->clean_text("{$service_label} recovery and support programs"),
            'description' => $this->clean_text("Comprehensive {$service_lower} rehabilitation at {$this->business_name} {$this->location}. Insurance coordination, direct billing, and personalized recovery planning.")
        ];
    }

    private function generate_icon_meta(array $context) {
        $category = $context['icon_type'] ?? 'service';
        $concept_label = $context['icon_concept_label'] ?? $this->format_service_label($context['icon_concept'] ?? 'service');

        $category_labels = [
            'condition' => 'Condition Icon',
            'program' => 'Program Icon',
            'team' => 'Team Icon',
            'service' => 'Service Icon'
        ];

        $icon_category = $category_labels[$category] ?? 'Service Icon';

        $title = $this->ensure_unique_title("{$concept_label} {$icon_category} - {$this->business_name} {$this->location}", $context['attachment_id'] ?? 0);
        $alt = "{$concept_label} {$icon_category} for {$this->business_name} {$this->location}";
        $caption = "{$icon_category} for {$concept_label} navigation";
        $description = "{$concept_label} {$icon_category} used across {$this->business_name} {$this->location} website.";

        return [
            'title' => $this->clean_text($title),
            'alt_text' => $this->clean_text($alt),
            'caption' => $this->clean_text($caption),
            'description' => $this->clean_text($description)
        ];
    }

    private function generate_logo_meta(array $context) {
        return [
            'title' => $this->clean_text("{$this->business_name} Logo - {$this->location} Rehabilitation Clinic"),
            'alt_text' => $this->clean_text("{$this->business_name} brand logo"),
            'caption' => $this->clean_text("{$this->business_name} branding for rehabilitation services"),
            'description' => $this->clean_text("Official {$this->business_name} logo representing rehabilitation, physiotherapy, chiropractic, and workplace injury care in {$this->location}.")
        ];
    }

    private function generate_product_meta(array $context) {
        $map = [
            'therapeutic-pillow' => [
                'name' => 'Therapeutic Support Pillow',
                'caption' => 'Mediflow water-based pillow for neck support',
                'description' => 'Therapeutic pillow recommended for neck pain and sleep positioning. Available for purchase at our clinic.'
            ],
            'custom-orthotics' => [
                'name' => 'Custom Orthotics',
                'caption' => 'Custom-fitted orthotic insoles',
                'description' => 'Custom orthotics designed for optimal foot support and biomechanical correction. Professional fitting available.'
            ],
            'support-brace' => [
                'name' => 'Support Brace',
                'caption' => 'Medical-grade support brace for injury recovery',
                'description' => 'Support brace for joint stabilization and injury recovery. Multiple sizes available with professional fitting.'
            ],
            'support-product' => [
                'name' => 'Rehabilitation Support Product',
                'caption' => 'Clinical support product for rehabilitation',
                'description' => 'Therapeutic support product recommended by our rehabilitation team. Available for purchase with insurance receipts.'
            ],
            'tens-unit' => [
                'name' => 'TENS Unit',
                'caption' => 'TENS electrotherapy device',
                'description' => 'Transcutaneous electrical nerve stimulation unit for pain relief and muscle activation. Professional guidance provided.'
            ],
            'pain-relief' => [
                'name' => 'Pain Relief Product',
                'caption' => 'Topical pain relief solution',
                'description' => 'Professional pain relief products recommended by our therapists. Available for purchase with usage instructions.'
            ],
            'compression-therapy' => [
                'name' => 'Compression Therapy Garment',
                'caption' => 'Compression stocking for circulation support',
                'description' => 'Compression therapy garment supporting circulation and recovery. Measurements and fittings performed in clinic.'
            ]
        ];

        $product_type = $context['product_type'] ?? 'support-product';
        $product = $map[$product_type] ?? $map['support-product'];

        $title = $product['name'] . " - {$this->business_name} {$this->location}";
        $alt_text = $product['name'] . ' available at ' . $this->business_name;
        $caption = $product['caption'];
        $description = $product['description'];

        return [
            'title' => $this->clean_text($this->ensure_unique_title($title, $context['attachment_id'] ?? 0)),
            'alt_text' => $this->clean_text($alt_text),
            'caption' => $this->clean_text($caption),
            'description' => $this->clean_text($description)
        ];
    }

    private function generate_facility_meta(array $context) {
        return [
            'title' => $this->clean_text("{$this->business_name} Clinic - {$this->location} Rehabilitation Facility"),
            'alt_text' => $this->clean_text("Interior view of {$this->business_name} rehabilitation clinic in {$this->location}"),
            'caption' => $this->clean_text("Modern rehabilitation facility at {$this->business_name} {$this->location}"),
            'description' => $this->clean_text("Modern rehabilitation facility at {$this->business_name} {$this->location}. Professional physiotherapy and chiropractic clinic with specialized treatment rooms and WSIB approved programs.")
        ];
    }

    private function generate_equipment_meta(array $context) {
        return [
            'title' => $this->clean_text("Therapeutic Equipment - {$this->business_name} {$this->location}"),
            'alt_text' => $this->clean_text("Therapeutic rehabilitation equipment at {$this->business_name} clinic in {$this->location}"),
            'caption' => $this->clean_text("Advanced therapeutic equipment for rehabilitation at {$this->business_name}"),
            'description' => $this->clean_text("Professional rehabilitation equipment at {$this->business_name} {$this->location}. Advanced therapeutic technology supporting physiotherapy, chiropractic care, and patient recovery.")
        ];
    }

    private function generate_business_meta(array $context) {
        return [
            'title' => $this->clean_text("{$this->business_name} - {$this->location} Rehabilitation Services"),
            'alt_text' => $this->clean_text("{$this->business_name} rehabilitation clinic branding in {$this->location}"),
            'caption' => $this->clean_text("{$this->business_name} providing professional rehabilitation services in {$this->location}"),
            'description' => $this->clean_text("{$this->business_name} {$this->location} rehabilitation clinic. Professional physiotherapy, chiropractic, and workplace injury rehabilitation services. WSIB approved provider with direct billing available for MVA recovery and first responder care.")
        ];
    }

    private function slugify($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    private function clean_text($text) {
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
}

class MSH_Image_Optimizer {
    
    private $batch_size = 10;
    private $processed_count = 0;
    private $current_attachment_id = null;
    private $contextual_meta_generator;
    private $healthcare_contexts = [
        'homepage_hero' => ['max_width' => 1200, 'max_height' => 600, 'quality' => 85],
        'service_page' => ['max_width' => 800, 'max_height' => 600, 'quality' => 80],
        'team_photo' => ['max_width' => 400, 'max_height' => 600, 'quality' => 85],
        'blog_featured' => ['max_width' => 800, 'max_height' => 450, 'quality' => 80],
        'testimonial' => ['max_width' => 200, 'max_height' => 200, 'quality' => 75],
        'facility' => ['max_width' => 800, 'max_height' => 600, 'quality' => 80]
    ];

    public function __construct() {
        add_action('wp_ajax_msh_analyze_images', array($this, 'ajax_analyze_images'));
        add_action('wp_ajax_msh_optimize_batch', array($this, 'ajax_optimize_batch'));
        add_action('wp_ajax_msh_get_progress', array($this, 'ajax_get_progress'));
        add_action('wp_ajax_msh_reset_optimization', array($this, 'ajax_reset_optimization'));
        add_action('wp_ajax_msh_apply_filename_suggestions', array($this, 'ajax_apply_filename_suggestions'));
        add_action('wp_ajax_msh_save_filename_suggestion', array($this, 'ajax_save_filename_suggestion'));
        add_action('wp_ajax_msh_remove_filename_suggestion', array($this, 'ajax_remove_filename_suggestion'));
        add_action('wp_ajax_msh_preview_meta_text', array($this, 'ajax_preview_meta_text'));
        add_action('wp_ajax_msh_save_edited_meta', array($this, 'ajax_save_edited_meta'));
        add_action('wp_ajax_msh_update_context', array($this, 'ajax_update_context'));

        $this->contextual_meta_generator = new MSH_Contextual_Meta_Generator();

        add_filter('attachment_fields_to_edit', array($this, 'add_context_attachment_field'), 10, 2);
        add_filter('attachment_fields_to_save', array($this, 'save_context_attachment_field'), 10, 2);
    }

    /**
     * Check if recompression is needed with safe file checks
     */
    private function needs_recompression($attachment_id) {
        $source_file = get_attached_file($attachment_id);
        if (!$source_file || !file_exists($source_file)) {
            return 'needs_attention'; // File missing or invalid
        }
        
        $source_mtime = @filemtime($source_file);
        if ($source_mtime === false) {
            return 'needs_attention'; // Can't read file time
        }
        
        $last_webp = (int)get_post_meta($attachment_id, 'msh_webp_last_converted', true);
        $last_metadata = (int)get_post_meta($attachment_id, 'msh_metadata_last_updated', true);
        
        // If no optimization timestamps exist, this isn't a recompression case
        if (!$last_webp && !$last_metadata) {
            return false;
        }
        
        // Needs recompression if source is newer than optimization
        return $source_mtime > max($last_webp, $last_metadata);
    }
    
    /**
     * Validate optimization status and provide fallback for unexpected values
     */
    private function validate_status($status) {
        $valid_statuses = [
            'ready_for_optimization',
            'optimized',
            'metadata_missing',
            'needs_recompression', 
            'webp_missing',
            'metadata_current',
            'needs_attention'
        ];
        
        if (!in_array($status, $valid_statuses)) {
            error_log("MSH Optimizer: Invalid status '$status' returned, defaulting to needs_attention");
            return 'needs_attention';
        }
        
        return $status;
    }

    /**
     * Get optimization status with enhanced logic and validation
     */
    private function get_optimization_status($attachment_id) {
        $webp_time = (int)get_post_meta($attachment_id, 'msh_webp_last_converted', true);
        $meta_time = (int)get_post_meta($attachment_id, 'msh_metadata_last_updated', true);
        $version = get_post_meta($attachment_id, 'msh_optimization_version', true);
        
        $source_file = get_attached_file($attachment_id);
        if (!$source_file || !file_exists($source_file)) {
            return $this->validate_status('needs_attention');
        }
        
        // Check for missing metadata FIRST (before recompression test)
        if (!$meta_time && !$webp_time) {
            return $this->validate_status('ready_for_optimization');
        }
        
        if (!$meta_time) {
            return $this->validate_status('metadata_missing');
        }
        
        $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source_file);
        $webp_exists = file_exists($webp_path);
        
        // Now check recompression (only for images that have been optimized)
        $recompression_check = $this->needs_recompression($attachment_id);
        if ($recompression_check === 'needs_attention') {
            return $this->validate_status('needs_attention');
        } elseif ($recompression_check === true) {
            return $this->validate_status('needs_recompression');
        }
        
        if ($webp_time && $meta_time && $webp_exists) {
            return $this->validate_status('optimized');
        } elseif ($webp_time && !$webp_exists) {
            return $this->validate_status('webp_missing');
        } elseif ($meta_time && !$webp_time) {
            return $this->validate_status('metadata_current');
        } else {
            return $this->validate_status('ready_for_optimization');
        }
    }

    /**
     * Get all published images that need optimization
     */
    public function get_published_images() {
        static $cached_results = null;

        if ($cached_results !== null) {
            return $cached_results;
        }

        global $wpdb;

        $attachments = $wpdb->get_results(
            "SELECT ID, post_title, post_name
             FROM {$wpdb->posts}
             WHERE post_type = 'attachment'
             AND post_mime_type LIKE 'image/%'
             ORDER BY ID",
            ARRAY_A
        );

        if (empty($attachments)) {
            $cached_results = [];
            return $cached_results;
        }

        $attachment_map = [];
        $attachment_ids = [];

        foreach ($attachments as $attachment) {
            $attachment['file_path'] = '';
            $attachment['alt_text'] = '';
            $attachment['used_in'] = [];
            $attachment_map[$attachment['ID']] = $attachment;
            $attachment_ids[] = (int) $attachment['ID'];
        }

        // Gather attachment meta in chunks to avoid oversized IN clauses
        $meta_keys = [
            '_wp_attached_file',
            '_wp_attachment_image_alt',
        ];

        $meta_rows = [];
        $chunk_size = 200;

        foreach (array_chunk($attachment_ids, $chunk_size) as $chunk) {
            $id_placeholders = implode(',', array_fill(0, count($chunk), '%d'));
            $meta_placeholders = implode(',', array_fill(0, count($meta_keys), '%s'));
            $meta_sql = "
                SELECT post_id, meta_key, meta_value
                FROM {$wpdb->postmeta}
                WHERE post_id IN ($id_placeholders)
                AND meta_key IN ($meta_placeholders)
            ";
            $prepared = $wpdb->prepare($meta_sql, array_merge($chunk, $meta_keys));
            $meta_rows = array_merge($meta_rows, $wpdb->get_results($prepared, ARRAY_A));
        }

        foreach ($meta_rows as $meta_row) {
            $post_id = (int) $meta_row['post_id'];

            if (!isset($attachment_map[$post_id])) {
                continue;
            }

            if ($meta_row['meta_key'] === '_wp_attached_file') {
                $attachment_map[$post_id]['file_path'] = ltrim((string) $meta_row['meta_value'], '/');
            }

            if ($meta_row['meta_key'] === '_wp_attachment_image_alt') {
                $attachment_map[$post_id]['alt_text'] = (string) $meta_row['meta_value'];
            }
        }

        $upload_dir = wp_get_upload_dir();
        $uploads_baseurl = isset($upload_dir['baseurl']) ? $upload_dir['baseurl'] : '';
        $uploads_baseurl = rtrim($uploads_baseurl, '/');

        $file_map = [];
        $basename_map = [];

        foreach ($attachment_map as $attachment_id => $attachment) {
            if (!empty($attachment['file_path'])) {
                $relative_path = ltrim($attachment['file_path'], '/');
                $file_map[strtolower($relative_path)] = $attachment_id;

                $basename = strtolower(basename($relative_path));
                $clean_basename = preg_replace('/-\d+x\d+(?=\.[^.]+$)/', '', $basename);
                $clean_basename = str_replace(['-scaled', '-rotated', '-edited'], '', $clean_basename);

                if (!isset($basename_map[$basename])) {
                    $basename_map[$basename] = [];
                }
                $basename_map[$basename][$attachment_id] = true;

                if (!isset($basename_map[$clean_basename])) {
                    $basename_map[$clean_basename] = [];
                }
                $basename_map[$clean_basename][$attachment_id] = true;
            }
        }

        $register_usage = static function (&$map, $attachment_id, $post_title, $post_type) {
            if (!isset($map[$attachment_id])) {
                return;
            }

            $title = trim((string) $post_title);

            if ($title === '') {
                $title = 'Untitled';
            }

            $label = $title . ' (' . $post_type . ')';
            $map[$attachment_id]['used_in'][$label] = true;
        };

        // Featured images (single query)
        $featured_rows = $wpdb->get_results(
            "SELECT meta.meta_value AS attachment_id, posts.post_title, posts.post_type
             FROM {$wpdb->postmeta} meta
             INNER JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id
             WHERE meta.meta_key = '_thumbnail_id'
             AND posts.post_status = 'publish'",
            ARRAY_A
        );

        foreach ($featured_rows as $row) {
            $attachment_id = (int) $row['attachment_id'];

            if (!isset($attachment_map[$attachment_id])) {
                continue;
            }

            $register_usage(
                $attachment_map,
                $attachment_id,
                $row['post_title'],
                $row['post_type']
            );
        }

        // Published posts/pages content scan
        $content_rows = $wpdb->get_results(
            "SELECT ID, post_title, post_type, post_content
             FROM {$wpdb->posts}
             WHERE post_status = 'publish'
             AND post_type NOT IN ('attachment','revision','nav_menu_item','customize_changeset','oembed_cache','user_request')",
            ARRAY_A
        );

        foreach ($content_rows as $post_row) {
            $content = (string) $post_row['post_content'];

            if ($content === '') {
                continue;
            }

            // Match Gutenberg and classic editor image references by attachment ID
            if (preg_match_all('/wp-image-(\d+)/', $content, $id_matches)) {
                $matched_ids = array_unique(array_map('intval', $id_matches[1]));

                foreach ($matched_ids as $attachment_id) {
                    if (!isset($attachment_map[$attachment_id])) {
                        continue;
                    }

                    $register_usage(
                        $attachment_map,
                        $attachment_id,
                        $post_row['post_title'],
                        $post_row['post_type']
                    );
                }
            }

            // Match direct file references
            if (preg_match_all('#wp-content/uploads/[^"\'\s>]+#i', $content, $path_matches)) {
                $paths = array_unique($path_matches[0]);

                foreach ($paths as $path) {
                    $normalized = strtolower($path);
                    $normalized = preg_replace('#^' . preg_quote(strtolower($uploads_baseurl), '#') . '\/?#', '', $normalized);
                    $normalized = preg_replace('#^.*wp-content\/uploads\/+#', '', $normalized);
                    $normalized = strtok($normalized, '?'); // remove query strings
                    $normalized = ltrim((string) $normalized, '/');

                    if ($normalized === '') {
                        continue;
                    }

                    if (isset($file_map[$normalized])) {
                        $attachment_id = $file_map[$normalized];
                        $register_usage(
                            $attachment_map,
                            $attachment_id,
                            $post_row['post_title'],
                            $post_row['post_type']
                        );
                        continue;
                    }

                    $basename = strtolower(basename($normalized));

                    if (isset($basename_map[$basename])) {
                        foreach (array_keys($basename_map[$basename]) as $attachment_id) {
                            $register_usage(
                                $attachment_map,
                                $attachment_id,
                                $post_row['post_title'],
                                $post_row['post_type']
                            );
                        }
                        continue;
                    }

                    $basename_clean = preg_replace('/-\d+x\d+(?=\.[^.]+$)/', '', $basename);
                    $basename_clean = str_replace(['-scaled', '-rotated', '-edited'], '', $basename_clean);

                    if (isset($basename_map[$basename_clean])) {
                        foreach (array_keys($basename_map[$basename_clean]) as $attachment_id) {
                            $register_usage(
                                $attachment_map,
                                $attachment_id,
                                $post_row['post_title'],
                                $post_row['post_type']
                            );
                        }
                    }
                }
            }
        }

        $published_images = [];

        foreach ($attachment_map as $attachment) {
            if (empty($attachment['used_in'])) {
                continue;
            }

            $attachment['used_in'] = implode(', ', array_keys($attachment['used_in']));
            $published_images[] = $attachment;
        }

        usort($published_images, static function ($a, $b) {
            return $a['ID'] <=> $b['ID'];
        });

        $cached_results = $published_images;

        return $cached_results;
    }

    /**
     * Calculate healthcare-specific priority for image optimization
     */
    private function calculate_healthcare_priority($image) {
        $priority = 1;
        $used_in = strtolower($image['used_in']);
        
        // Healthcare-specific high-priority pages
        if (strpos($used_in, 'home') !== false) {
            $priority += 15; // Homepage hero images critical for trust
        }
        
        // Medical services pages (highest conversion)
        if (strpos($used_in, 'services') !== false || 
            strpos($used_in, 'treatment') !== false ||
            strpos($used_in, 'conditions') !== false) {
            $priority += 12;
        }
        
        // Team/doctor photos (trust & credibility)
        if (strpos($used_in, 'team') !== false || 
            strpos($used_in, 'doctor') !== false ||
            strpos($used_in, 'staff') !== false) {
            $priority += 10;
        }
        
        // Patient testimonials/success stories
        if (strpos($used_in, 'testimonial') !== false || 
            strpos($used_in, 'patient') !== false) {
            $priority += 8;
        }
        
        // CRITICAL: Missing alt text in healthcare = accessibility violation
        if (empty($image['alt_text'])) {
            $priority += 20; // Healthcare accessibility is legal requirement
        }
        
        return $priority;
    }

    /**
     * Analyze single image for optimization potential
     */
    public function analyze_single_image($attachment_id) {
        $metadata = wp_get_attachment_metadata($attachment_id);
        if (!is_array($metadata)) {
            $metadata = [];
        }
        $upload_dir = wp_upload_dir();

        $relative_file = is_array($metadata) && !empty($metadata['file'])
            ? $metadata['file']
            : get_post_meta($attachment_id, '_wp_attached_file', true);

        if (empty($relative_file)) {
            return ['error' => 'No file metadata found'];
        }

        $file_path = $upload_dir['basedir'] . '/' . ltrim($relative_file, '/');
        
        if (!file_exists($file_path)) {
            return ['error' => 'File not found: ' . $file_path];
        }
        
        $file_size = filesize($file_path);
        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $is_svg = ($extension === 'svg');

        $image_info = $is_svg ? [0 => 0, 1 => 0, 'mime' => 'image/svg+xml'] : @getimagesize($file_path);
        if (!$image_info) {
            $image_info = [0 => 0, 1 => 0, 'mime' => $metadata['mime_type'] ?? 'image'];
        }

        $webp_exists = false;
        $webp_savings = null;
        if (!$is_svg) {
            $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file_path);
            $webp_exists = file_exists($webp_path);
            $webp_savings = $this->estimate_webp_savings($file_size, $image_info['mime']);
        }
        
        // Determine legacy resizing context and new contextual information
        $legacy_context = $this->determine_image_context($attachment_id);
        $context_info = $this->contextual_meta_generator->detect_context($attachment_id);
        $manual_context_value = get_post_meta($attachment_id, '_msh_context', true);
        $manual_context_value = is_string($manual_context_value) ? trim($manual_context_value) : '';
        $auto_context_value = get_post_meta($attachment_id, '_msh_auto_context', true);
        $auto_context_value = is_string($auto_context_value) ? trim($auto_context_value) : '';
        $context_source = !empty($context_info['manual']) ? 'manual' : 'auto';
        $active_context_slug = $manual_context_value !== ''
            ? $manual_context_value
            : ($context_info['type'] ?? $auto_context_value);
        $generated_meta = $this->contextual_meta_generator->generate_meta_fields($attachment_id, $context_info);
        $suggested_filename = '';

        if (!empty($extension)) {
            $slug = $this->contextual_meta_generator->generate_filename_slug($attachment_id, $context_info, $extension);
            if (!empty($slug)) {
                $suggested_filename = $this->ensure_unique_filename($slug, $extension, $attachment_id);
                $current_basename = basename($relative_file);
                if (strcasecmp($suggested_filename, $current_basename) === 0) {
                    $suggested_filename = '';
                }
            }
        }

        // Gather optimization metadata
        $optimized_date = get_post_meta($attachment_id, 'msh_optimized_date', true);
        $optimization_status = $this->get_optimization_status($attachment_id);
        $webp_last_converted = (int) get_post_meta($attachment_id, 'msh_webp_last_converted', true);
        $metadata_last_updated = (int) get_post_meta($attachment_id, 'msh_metadata_last_updated', true);
        $source_last_compressed = (int) get_post_meta($attachment_id, 'msh_source_last_compressed', true);

        if ($is_svg) {
            $optimization_potential = [
                'needs_resize' => false,
                'current_size' => $file_size,
                'recommended_dimensions' => null,
                'estimated_optimal_size' => $file_size,
                'estimated_savings_bytes' => 0,
                'estimated_savings_percent' => 0
            ];
        } else {
            $optimization_potential = $this->calculate_optimization_potential($file_path, $metadata, $legacy_context);
        }

        return [
            'current_size_bytes' => $file_size,
            'current_size_mb' => round($file_size / 1048576, 2),
            'current_dimensions' => $is_svg ? 'vector' : ($image_info[0] . 'x' . $image_info[1]),
            'current_format' => $image_info['mime'],
            'webp_exists' => $webp_exists,
            'webp_savings_estimate' => $webp_savings,
            'context' => $legacy_context,
            'context_details' => $context_info,
            'context_source' => $context_source,
            'manual_context' => $manual_context_value,
            'auto_context' => $auto_context_value,
            'context_active_label' => $this->format_context_label($active_context_slug),
            'context_auto_label' => $auto_context_value !== '' ? $this->format_context_label($auto_context_value) : '',
            'generated_meta' => $generated_meta,
            'optimization_potential' => $optimization_potential,
            'suggested_filename' => $suggested_filename,
            'optimized_date' => $optimized_date,
            'optimization_status' => $optimization_status,
            'webp_last_converted' => $webp_last_converted,
            'metadata_last_updated' => $metadata_last_updated,
            'source_last_compressed' => $source_last_compressed
        ];
    }

    /**
     * Estimate potential WebP savings when conversion hasn't run yet
     */
    private function estimate_webp_savings($file_size, $mime_type) {
        $file_size = (int) $file_size;

        if ($file_size <= 0) {
            return [
                'source_size' => 0,
                'estimated_webp_size' => 0,
                'estimated_savings_bytes' => 0,
                'estimated_savings_percent' => 0,
            ];
        }

        $mime_type = strtolower((string) $mime_type);

        // Average compression ratios based on format benchmarking
        $compression_map = [
            'image/jpeg' => 0.35,
            'image/jpg' => 0.35,
            'image/png' => 0.45,
            'image/gif' => 0.55,
            'image/webp' => 1.00,
        ];

        $ratio = isset($compression_map[$mime_type]) ? (float) $compression_map[$mime_type] : 0.40;
        $ratio = max(0.05, min(1.0, $ratio));

        $estimated_webp_size = (int) round($file_size * $ratio);
        $estimated_webp_size = max(0, min($file_size, $estimated_webp_size));

        $estimated_savings_bytes = max(0, $file_size - $estimated_webp_size);
        $estimated_savings_percent = $file_size > 0
            ? (int) round(($estimated_savings_bytes / $file_size) * 100)
            : 0;

        return [
            'source_size' => $file_size,
            'estimated_webp_size' => $estimated_webp_size,
            'estimated_savings_bytes' => $estimated_savings_bytes,
            'estimated_savings_percent' => max(0, min(100, $estimated_savings_percent)),
        ];
    }

    /**
     * Recalculate optimization potential with healthcare-aware sizes
     */
    private function calculate_optimization_potential($file_path, $metadata, $context_slug = null) {
        $current_size = @filesize($file_path);
        $current_size = $current_size !== false ? (int) $current_size : 0;

        if ($current_size <= 0 || empty($metadata)) {
            return [
                'needs_resize' => false,
                'current_size' => $current_size,
                'recommended_dimensions' => null,
                'estimated_savings_bytes' => 0,
                'estimated_savings_percent' => 0,
            ];
        }

        $dimensions = [
            'width' => $metadata['width'] ?? 0,
            'height' => $metadata['height'] ?? 0,
        ];

        $recommended = $this->get_recommended_dimensions($context_slug, $dimensions);
        $needs_resize = $this->needs_resize($dimensions, $recommended);

        if (!$needs_resize) {
            return [
                'needs_resize' => false,
                'current_size' => $current_size,
                'recommended_dimensions' => $recommended,
                'estimated_savings_bytes' => 0,
                'estimated_savings_percent' => 0,
            ];
        }

        $estimated_optimal_size = $this->estimate_optimal_filesize($current_size, $recommended, $dimensions);
        $estimated_optimal_size = max(0, min($current_size, $estimated_optimal_size));
        $estimated_savings_bytes = max(0, $current_size - $estimated_optimal_size);
        $estimated_savings_percent = $current_size > 0
            ? (int) round(($estimated_savings_bytes / $current_size) * 100)
            : 0;

        return [
            'needs_resize' => true,
            'current_size' => $current_size,
            'recommended_dimensions' => $recommended,
            'estimated_optimal_size' => $estimated_optimal_size,
            'estimated_savings_bytes' => $estimated_savings_bytes,
            'estimated_savings_percent' => max(0, min(100, $estimated_savings_percent)),
        ];
    }

    private function get_recommended_dimensions($context_slug, array $dimensions) {
        $defaults = ['width' => 1200, 'height' => 800];

        if (!$context_slug) {
            return $defaults;
        }

        $recommendations = [
            'homepage_hero' => ['width' => 1400, 'height' => 750],
            'service_page' => ['width' => 900, 'height' => 600],
            'team_photo' => ['width' => 600, 'height' => 800],
            'testimonial' => ['width' => 600, 'height' => 600],
            'facility' => ['width' => 1200, 'height' => 800],
            'equipment' => ['width' => 900, 'height' => 600],
            'blog_featured' => ['width' => 1200, 'height' => 675],
        ];

        if (!isset($recommendations[$context_slug])) {
            return $defaults;
        }

        $recommended = $recommendations[$context_slug];

        // Ensure we don't suggest an upscale
        $recommended['width'] = min($recommended['width'], (int) ($dimensions['width'] ?? $recommended['width']));
        $recommended['height'] = min($recommended['height'], (int) ($dimensions['height'] ?? $recommended['height']));

        return $recommended;
    }

    private function needs_resize(array $dimensions, array $recommended) {
        $width = (int) ($dimensions['width'] ?? 0);
        $height = (int) ($dimensions['height'] ?? 0);

        if ($width === 0 || $height === 0) {
            return false;
        }

        return $width > $recommended['width'] + 40 || $height > $recommended['height'] + 40;
    }

    private function estimate_optimal_filesize($current_size, array $recommended, array $dimensions) {
        $width = (int) ($dimensions['width'] ?? 1);
        $height = (int) ($dimensions['height'] ?? 1);

        if ($width <= 0 || $height <= 0) {
            return $current_size;
        }

        $current_pixels = $width * $height;
        $target_pixels = max(1, $recommended['width'] * $recommended['height']);
        $scale_factor = $target_pixels / $current_pixels;

        $estimated = $current_size * $scale_factor;
        $estimated = $estimated * 1.1; // include buffer for quality retention

        return (int) round($estimated);
    }

    /**
     * Determine image context based on usage
     */
    private function determine_image_context($attachment_id) {
        global $wpdb;
        
        // Check if it's a featured image
        $featured_posts = $wpdb->get_results($wpdb->prepare("
            SELECT posts.post_type, posts.post_title 
            FROM {$wpdb->postmeta} meta 
            JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id 
            WHERE meta.meta_key = '_thumbnail_id' 
            AND meta.meta_value = %d 
            AND posts.post_status = 'publish'
        ", $attachment_id));
        
        if ($featured_posts) {
            foreach ($featured_posts as $post) {
                if (strpos(strtolower($post->post_title), 'home') !== false) {
                    return 'homepage_hero';
                }
                if ($post->post_type === 'msh_service') {
                    return 'service_page';
                }
                if ($post->post_type === 'msh_team_member') {
                    return 'team_photo';
                }
                if ($post->post_type === 'post') {
                    return 'blog_featured';
                }
            }
        }
        
        // Check content usage
        $file_path = get_post_meta($attachment_id, '_wp_attached_file', true);
        if ($file_path) {
            $posts_using = $wpdb->get_results($wpdb->prepare("
                SELECT post_type, post_title 
                FROM {$wpdb->posts} 
                WHERE post_content LIKE %s 
                AND post_status = 'publish'
            ", '%' . $file_path . '%'));
            
            foreach ($posts_using as $post) {
                $title_lower = strtolower($post->post_title);
                if (strpos($title_lower, 'testimonial') !== false || 
                    strpos($title_lower, 'patient') !== false) {
                    return 'testimonial';
                }
                if (strpos($title_lower, 'facility') !== false || 
                    strpos($title_lower, 'office') !== false ||
                    strpos($title_lower, 'clinic') !== false) {
                    return 'facility';
                }
            }
        }
        
        return 'blog_featured'; // Default context
    }

    /**
     * Generate business-focused filename
     */
    private function ensure_unique_filename($base_name, $extension, $attachment_id) {
        $filename = $base_name . '.' . $extension;
        
        // Check if this exact filename already exists in WordPress
        $existing_attachment = $this->get_attachment_by_filename($filename);
        
        if ($existing_attachment && $existing_attachment !== $attachment_id) {
            // Add attachment ID suffix for uniqueness
            $filename = $base_name . '-' . $attachment_id . '.' . $extension;
        }
        
        return $filename;
    }

    /**
     * Find attachment by filename
     */
    private function get_attachment_by_filename($filename) {
        global $wpdb;
        
        $result = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s LIMIT 1",
            '%' . $wpdb->esc_like($filename)
        ));
        
        return $result ? (int) $result : null;
    }

    private function generate_business_filename($attachment_id, $context) {
        $file_path = get_post_meta($attachment_id, '_wp_attached_file', true);

        if (empty($file_path) || !is_string($file_path)) {
            error_log("MSH Optimizer: Empty or invalid file path for attachment ID: $attachment_id");
            return false;
        }

        $extension = pathinfo($file_path, PATHINFO_EXTENSION);
        if (empty($extension)) {
            error_log("MSH Optimizer: No file extension found for attachment ID: $attachment_id, file: $file_path");
            return false;
        }

        $extension = strtolower($extension);
        $context_details = $this->contextual_meta_generator->detect_context($attachment_id);

        // Legacy callers may still provide a context slug – honour it if type missing
        if (!empty($context) && empty($context_details['type'])) {
            $context_details['type'] = $context;
        }

        $slug = $this->contextual_meta_generator->generate_filename_slug($attachment_id, $context_details, $extension);
        if (empty($slug)) {
            $slug = sanitize_title($context ?: basename($file_path, '.' . $extension));
        }

        return $this->ensure_unique_filename($slug, $extension, $attachment_id);
    }

    /**
     * Check if meta should be regenerated (protect manual edits)
     */
    private function should_regenerate_meta($attachment_id, $field = null) {
        $metadata_source = get_post_meta($attachment_id, 'msh_metadata_source', true);

        // Never overwrite manual edits
        if ($metadata_source === 'manual_edit') {
            return false;
        }

        return true;
    }

    private function get_context_choices() {
        return [
            '' => __('Auto-detect (default)', 'medicross-child'),
            'clinical' => __('Clinical / Treatment', 'medicross-child'),
            'team' => __('Team Member', 'medicross-child'),
            'testimonial' => __('Patient Testimonial', 'medicross-child'),
            'service-icon' => __('Service Icon', 'medicross-child'),
            'facility' => __('Facility / Clinic', 'medicross-child'),
            'equipment' => __('Equipment', 'medicross-child'),
            'business' => __('Business / General', 'medicross-child')
        ];
    }

    private function format_context_label($slug) {
        $slug = (string) $slug;

        if ($slug === '') {
            return __('Auto-detect (default)', 'medicross-child');
        }

        $choices = $this->get_context_choices();
        if (isset($choices[$slug])) {
            return $choices[$slug];
        }

        return $this->humanize_label($slug, __('Unknown', 'medicross-child'));
    }

    private function humanize_label($value, $fallback = '') {
        if (!is_string($value) || trim($value) === '') {
            return $fallback;
        }

        $label = str_replace(['-', '_'], ' ', strtolower($value));
        $label = preg_replace('/\s+/', ' ', $label);

        $label = trim($label);
        if ($label === '') {
            return $fallback;
        }

        return ucwords($label);
    }

    /**
     * Attachment field for manual context selection
     */
    public function add_context_attachment_field($form_fields, $post) {
        if (strpos($post->post_mime_type, 'image/') !== 0) {
            return $form_fields;
        }

        $choices = $this->get_context_choices();
        $manual_value = get_post_meta($post->ID, '_msh_context', true);
        $manual_value = is_string($manual_value) ? trim($manual_value) : '';
        $auto_value = get_post_meta($post->ID, '_msh_auto_context', true);
        $context_details = $this->contextual_meta_generator->detect_context($post->ID);
        $context_source = !empty($context_details['manual']) ? 'manual' : 'auto';

        $select = '<select class="msh-context-select" name="attachments[' . esc_attr($post->ID) . '][msh_context]" style="width:100%">';
        foreach ($choices as $key => $label) {
            $selected = selected($manual_value, $key, false);
            $select .= '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        $select .= '</select>';

        $active_label = $manual_value !== ''
            ? $this->format_context_label($manual_value)
            : $this->format_context_label($context_details['type'] ?? $auto_value);

        $auto_label_value = $auto_value !== '' ? $this->format_context_label($auto_value) : '';

        $chips = [];
        $chips[] = '<span class="msh-context-chip ' . ($context_source === 'manual' ? 'manual' : 'auto') . '">' .
            esc_html($context_source === 'manual' ? __('Manual override', 'medicross-child') : __('Auto-detected', 'medicross-child')) . '</span>';

        if ($active_label) {
            $chips[] = '<span class="msh-context-chip context">' . esc_html($active_label) . '</span>';
        }

        if ($context_source === 'manual' && $auto_label_value && $manual_value !== $auto_value) {
            $chips[] = '<span class="msh-context-chip auto-note">' .
                esc_html(sprintf(__('Auto suggestion: %s', 'medicross-child'), $auto_label_value)) . '</span>';
        }

        $detail_items = [];
        if (!empty($context_details['service'])) {
            $detail_items[] = esc_html(sprintf(
                __('Service focus: %s', 'medicross-child'),
                $this->format_service_label($context_details['service'])
            ));
        }
        if (!empty($context_details['asset'])) {
            $detail_items[] = esc_html(sprintf(
                __('Asset type: %s', 'medicross-child'),
                $this->humanize_label($context_details['asset'], __('General', 'medicross-child'))
            ));
        }
        if (!empty($context_details['product_type'])) {
            $detail_items[] = esc_html(sprintf(
                __('Product indicator: %s', 'medicross-child'),
                $this->humanize_label($context_details['product_type'], __('Medical', 'medicross-child'))
            ));
        }
        if (!empty($context_details['icon_type'])) {
            $detail_items[] = esc_html(sprintf(
                __('Icon category: %s', 'medicross-child'),
                $this->humanize_label($context_details['icon_type'], __('Clinical', 'medicross-child'))
            ));
        }
        if (!empty($context_details['page_title'])) {
            $detail_items[] = esc_html(sprintf(
                __('Appears on: %s', 'medicross-child'),
                $context_details['page_title']
            ));
        }

        $details_html = '';
        if (!empty($detail_items)) {
            $details_html = '<ul class="msh-context-details"><li>' . implode('</li><li>', $detail_items) . '</li></ul>';
        }

        $primary_description = $context_source === 'manual'
            ? __('The optimizer will honour this manual context until you switch back to Auto-detect.', 'medicross-child')
            : __('Auto-detect uses usage data, taxonomies, and filenames to pick the best context. Select a manual option to lock it in.', 'medicross-child');

        $auto_description = '';
        if ($context_source === 'manual') {
            $auto_description = $auto_label_value
                ? sprintf(__('Last auto-detected context: %s', 'medicross-child'), $auto_label_value)
                : __('Run the analyzer to record the latest auto-detected context for comparison.', 'medicross-child');
            $auto_description = '<p class="description">' . esc_html($auto_description) . '</p>';
        }

        static $styles_injected = false;
        $style_block = '';
        if (!$styles_injected) {
            $styles_injected = true;
            $style_block = '<style id="msh-context-field-styles">'
                . '.msh-context-field{margin-top:8px;padding:12px;border:1px solid #dcdcde;border-radius:6px;background:#f8f9fb;}'
                . '.msh-context-chips{margin-bottom:8px;}'
                . '.msh-context-chip{display:inline-block;margin:0 6px 6px 0;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:600;border:1px solid #c3c4c7;background:#ffffff;color:#1d2327;}'
                . '.msh-context-chip.manual{background:#fde8e6;border-color:#f0b8af;color:#a4281f;}'
                . '.msh-context-chip.auto{background:#ecfbea;border-color:#b4e1b1;color:#116b25;}'
                . '.msh-context-chip.context{background:#fff;border-color:#c3c4c7;color:#1d2327;}'
                . '.msh-context-chip.auto-note{background:#eef2ff;border-color:#c0c7f8;color:#1b3f91;}'
                . '.msh-context-chip.pending{background:#fef7e5;border-color:#f7d48b;color:#7a4b00;}'
                . '.msh-context-details{margin:8px 0 0 0;padding-left:18px;font-size:12px;color:#1d2327;}'
                . '.msh-context-details li{margin-bottom:4px;}'
                . '.msh-context-select{margin-bottom:6px;}'
                . '</style>';
        }

        $html = $style_block . '<div class="msh-context-field">'
            . '<div class="msh-context-chips">' . implode('', $chips) . '</div>'
            . '<label class="screen-reader-text" for="msh-context-' . esc_attr($post->ID) . '">' . esc_html__('Image Context', 'medicross-child') . '</label>'
            . str_replace('<select', '<select id="msh-context-' . esc_attr($post->ID) . '"', $select)
            . '<p class="description">' . esc_html($primary_description) . '</p>'
            . $auto_description
            . $details_html
            . '</div>';

        $form_fields['msh_context'] = [
            'label' => __('Image Context', 'medicross-child'),
            'input' => 'html',
            'helps' => '',
            'html' => $html
        ];

        return $form_fields;
    }

    /**
     * Save manual context selection
     */
    public function save_context_attachment_field($post, $attachment) {
        if (isset($attachment['msh_context'])) {
            $choices = $this->get_context_choices();
            $value = sanitize_text_field($attachment['msh_context']);
            if (!array_key_exists($value, $choices)) {
                $value = '';
            }

            if ($value !== '') {
                update_post_meta($post['ID'], '_msh_context', $value);
            } else {
                delete_post_meta($post['ID'], '_msh_context');
            }

            // Remove deprecated metadata keys introduced in earlier batches
            delete_post_meta($post['ID'], '_msh_manual_edit');
            delete_post_meta($post['ID'], 'msh_context_last_manual_update');
        }

        return $post;
    }

    /**
     * Generate clinical meta using templates
     */
    private function validate_and_truncate_meta($meta_data) {
        $limits = ['title' => 60, 'caption' => 155, 'alt_text' => 125, 'description' => 250];
        $validated = [];
        
        foreach ($meta_data as $field => $content) {
            if (strlen($content) > $limits[$field]) {
                $content = $this->smart_truncate($content, $limits[$field]);
            }
            
            $quality_score = $this->score_meta_quality($content);
            if ($quality_score < 70) {
                error_log("MSH Optimizer: Low quality meta generated for $field: $content (score: $quality_score)");
            }
            
            $validated[$field] = $content;
        }
        
        return $validated;
    }

    /**
     * Smart truncation preserving clinical terms
     */
    private function smart_truncate($text, $limit) {
        if (strlen($text) <= $limit) return $text;
        
        $truncated = substr($text, 0, $limit);
        $last_space = strrpos($truncated, ' ');
        
        if ($last_space !== false) {
            $truncated = substr($truncated, 0, $last_space);
        }
        
        // Preserve essential terms
        $essential_terms = ['WSIB', 'Hamilton', 'physiotherapy', 'rehabilitation'];
        foreach ($essential_terms as $term) {
            if (strpos($text, $term) !== false && strpos($truncated, $term) === false) {
                $term_pos = strpos($text, $term);
                if ($term_pos + strlen($term) <= $limit) {
                    $truncated = substr($text, 0, $term_pos + strlen($term));
                }
            }
        }
        
        return trim($truncated);
    }

    /**
     * Score meta quality
     */
    private function score_meta_quality($content) {
        $score = 100;
        
        $blacklist = ['trusted partner', 'comprehensive care', 'healthcare services'];
        foreach ($blacklist as $phrase) {
            if (stripos($content, $phrase) !== false) {
                $score -= 25;
            }
        }

        $priority_terms = ['physiotherapy', 'rehabilitation', 'WSIB', 'Hamilton', 'chiropractic', 'clinic'];
        foreach ($priority_terms as $term) {
            if (stripos($content, $term) !== false) {
                $score += 8;
            }
        }

        return max(0, min(100, $score));
    }

    /**
     * Generate clinical caption
     */
    private function generate_title($attachment_id, $legacy_context = null) {
        $context_info = $this->contextual_meta_generator->detect_context($attachment_id);
        $meta = $this->contextual_meta_generator->generate_meta_fields($attachment_id, $context_info);

        if (!empty($meta['title'])) {
            return $meta['title'];
        }

        return 'Main Street Health - Rehabilitation Services';
    }
    
    private function generate_caption($attachment_id, $legacy_context = null) {
        $context_info = $this->contextual_meta_generator->detect_context($attachment_id);
        $meta = $this->contextual_meta_generator->generate_meta_fields($attachment_id, $context_info);

        if (!empty($meta['caption'])) {
            return $meta['caption'];
        }

        return 'Professional rehabilitation therapy for injury recovery in Hamilton.';
    }


    private function generate_alt_text($attachment_id, $legacy_context = null) {
        $context_info = $this->contextual_meta_generator->detect_context($attachment_id);
        $meta = $this->contextual_meta_generator->generate_meta_fields($attachment_id, $context_info);

        if (!empty($meta['alt_text'])) {
            return $meta['alt_text'];
        }

        return 'Main Street Health rehabilitation clinic in Hamilton Ontario';
    }


    private function generate_description($attachment_id, $legacy_context = null) {
        $context_info = $this->contextual_meta_generator->detect_context($attachment_id);
        $meta = $this->contextual_meta_generator->generate_meta_fields($attachment_id, $context_info);

        if (!empty($meta['description'])) {
            return $meta['description'];
        }

        return 'Professional rehabilitation services at Main Street Health in Hamilton with WSIB approved programs and direct billing.';
    }


    /**
     * AJAX handler for image analysis
     */
    public function ajax_analyze_images() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $start_time = microtime(true);
        
        // Debug: First check total images
        global $wpdb;
        $total_images = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'");
        
        $images = $this->get_published_images();
        $analysis_results = [];
        
        foreach ($images as $image) {
            $analysis = $this->analyze_single_image($image['ID']);
            $priority = $this->calculate_healthcare_priority($image);
            
            $analysis_results[] = array_merge($image, $analysis, ['priority' => $priority]);
        }
        
        // Sort by priority (highest first)
        usort($analysis_results, function($a, $b) {
            return $b['priority'] - $a['priority'];
        });
        
        // Include minimal debug info in response
        $duration_ms = round((microtime(true) - $start_time) * 1000, 2);
        
        wp_send_json_success([
            'images' => $analysis_results,
            'debug' => [
                'total_images_in_db' => intval($total_images),
                'published_images_found' => count($images),
                'analysis_duration_ms' => $duration_ms
            ]
        ]);
    }

    /**
     * AJAX handler for batch optimization
     */
    public function ajax_optimize_batch() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $image_ids = $_POST['image_ids'] ?? [];
        $results = [];
        
        foreach ($image_ids as $attachment_id) {
            $result = $this->optimize_single_image(intval($attachment_id));
            $results[] = [
                'id' => $attachment_id,
                'result' => $result
            ];
        }
        
        wp_send_json_success($results);
    }

    /**
     * Run single image optimization (Batch 2: apply contextual metadata & filename suggestion)
     */
    private function optimize_single_image($attachment_id) {
        $attachment_id = intval($attachment_id);

        $result = [
            'status' => 'skipped',
            'actions' => [],
        ];

        if ($attachment_id <= 0) {
            $result['status'] = 'error';
            $result['actions'][] = 'Invalid attachment ID';
            return $result;
        }

        $attachment = get_post($attachment_id);
        if (!$attachment || $attachment->post_type !== 'attachment') {
            $result['status'] = 'error';
            $result['actions'][] = 'Attachment not found';
            return $result;
        }

        $file_path = get_attached_file($attachment_id);
        if (!$file_path || !file_exists($file_path)) {
            $result['status'] = 'error';
            $result['actions'][] = 'Original file missing';
            return $result;
        }

        $legacy_context = $this->determine_image_context($attachment_id);
        $context_details = $this->contextual_meta_generator->detect_context($attachment_id);
        $manual_context_value = get_post_meta($attachment_id, '_msh_context', true);
        $manual_context_value = is_string($manual_context_value) ? trim($manual_context_value) : '';
        $auto_context_value = get_post_meta($attachment_id, '_msh_auto_context', true);
        $auto_context_value = is_string($auto_context_value) ? trim($auto_context_value) : '';
        $context_source = !empty($context_details['manual']) ? 'manual' : 'auto';
        $active_context_slug = $manual_context_value !== ''
            ? $manual_context_value
            : ($context_details['type'] ?? $auto_context_value);
        $active_context_label = $this->format_context_label($active_context_slug);
        $auto_context_label = $auto_context_value !== '' ? $this->format_context_label($auto_context_value) : '';
        $meta_preview = $this->contextual_meta_generator->generate_meta_fields($attachment_id, $context_details);
        $meta_preview = $this->validate_and_truncate_meta($meta_preview);

        $context_message = $context_source === 'manual'
            ? sprintf(__('Manual override in effect: %s', 'medicross-child'), $active_context_label)
            : sprintf(__('Auto-detected context: %s', 'medicross-child'), $active_context_label);

        if ($context_source === 'manual' && $auto_context_label && $manual_context_value !== $auto_context_value) {
            $context_message .= ' ' . sprintf(__('(Auto suggestion: %s)', 'medicross-child'), $auto_context_label);
        }

        $timestamp = time();
        $meta_applied = [];
        $meta_skipped = [];

        // Title
        if (!empty($meta_preview['title'])) {
            if ($this->should_regenerate_meta($attachment_id, 'title')) {
                wp_update_post([
                    'ID' => $attachment_id,
                    'post_title' => sanitize_text_field($meta_preview['title']),
                    'post_name' => sanitize_title($meta_preview['title'])
                ]);
                $result['actions'][] = 'Title updated from contextual generator';
                $meta_applied['title'] = $meta_preview['title'];
            } else {
                $meta_skipped[] = 'title';
            }
        }

        // Caption
        if (!empty($meta_preview['caption'])) {
            if ($this->should_regenerate_meta($attachment_id, 'caption')) {
                wp_update_post([
                    'ID' => $attachment_id,
                    'post_excerpt' => sanitize_textarea_field($meta_preview['caption'])
                ]);
                $result['actions'][] = 'Caption updated from contextual generator';
                $meta_applied['caption'] = $meta_preview['caption'];
            } else {
                $meta_skipped[] = 'caption';
            }
        }

        // Description
        if (!empty($meta_preview['description'])) {
            if ($this->should_regenerate_meta($attachment_id, 'description')) {
                wp_update_post([
                    'ID' => $attachment_id,
                    'post_content' => sanitize_textarea_field($meta_preview['description'])
                ]);
                $result['actions'][] = 'Description updated from contextual generator';
                $meta_applied['description'] = $meta_preview['description'];
            } else {
                $meta_skipped[] = 'description';
            }
        }

        // Alt text
        if (!empty($meta_preview['alt_text'])) {
            if ($this->should_regenerate_meta($attachment_id, 'alt_text')) {
                update_post_meta($attachment_id, '_wp_attachment_image_alt', sanitize_text_field($meta_preview['alt_text']));
                $result['actions'][] = 'ALT text updated from contextual generator';
                $meta_applied['alt_text'] = $meta_preview['alt_text'];
            } else {
                $meta_skipped[] = 'alt_text';
            }
        }

        if (!empty($meta_applied)) {
            update_post_meta($attachment_id, 'msh_metadata_last_updated', (int) $timestamp);
            delete_post_meta($attachment_id, 'msh_metadata_source');
        }

        foreach ($meta_skipped as $field) {
            $result['actions'][] = ucfirst(str_replace('_', ' ', $field)) . ' preserved (manual edit)';
        }

        // Refresh filename suggestion using contextual slug helper
        $suggested_filename = '';
        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        if (!empty($extension)) {
            $slug = $this->contextual_meta_generator->generate_filename_slug($attachment_id, $context_details, $extension);
            if (!empty($slug)) {
                $suggested_filename = $this->ensure_unique_filename($slug, $extension, $attachment_id);
                update_post_meta($attachment_id, '_msh_suggested_filename', $suggested_filename);
                update_post_meta($attachment_id, 'msh_filename_last_suggested', (int) $timestamp);
                $result['actions'][] = 'Filename suggestion refreshed';
            }
        }

        $result['status'] = $this->get_optimization_status($attachment_id);
        $result['actions'][] = $context_message;
        $result['context'] = [
            'legacy' => $legacy_context,
            'detected' => $context_details,
            'source' => $context_source,
            'manual_override' => $manual_context_value,
            'auto' => $auto_context_value,
            'active_label' => $active_context_label,
            'auto_label' => $auto_context_label,
        ];
        $result['meta_preview'] = $meta_preview;
        $result['meta_applied'] = $meta_applied;
        $result['suggested_filename'] = $suggested_filename;

        return $result;
    }

    /**
     * AJAX: Update manual context selection directly from analyzer UI
     */
    public function ajax_update_context() {
        check_ajax_referer('msh_image_optimizer', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $attachment_id = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;
        if ($attachment_id <= 0) {
            wp_send_json_error(__('Invalid attachment ID.', 'medicross-child'));
        }

        $raw_context = isset($_POST['context']) ? wp_unslash($_POST['context']) : '';
        $new_context = sanitize_text_field($raw_context);

        $choices = $this->get_context_choices();
        if ($new_context !== '' && !array_key_exists($new_context, $choices)) {
            wp_send_json_error(__('Invalid context selection.', 'medicross-child'));
        }

        if ($new_context !== '') {
            update_post_meta($attachment_id, '_msh_context', $new_context);
        } else {
            delete_post_meta($attachment_id, '_msh_context');
        }

        // Clean up deprecated keys retained for backwards compatibility.
        delete_post_meta($attachment_id, '_msh_manual_edit');
        delete_post_meta($attachment_id, 'msh_context_last_manual_update');

        // Refresh auto-detected context for comparison badges.
        $auto_context = $this->contextual_meta_generator->detect_context($attachment_id, true);
        if (!empty($auto_context['type'])) {
            update_post_meta($attachment_id, '_msh_auto_context', $auto_context['type']);
        } else {
            delete_post_meta($attachment_id, '_msh_auto_context');
        }

        $image_data = $this->analyze_single_image($attachment_id);
        if (!is_array($image_data) || isset($image_data['error'])) {
            $error_message = is_array($image_data) && isset($image_data['error'])
                ? $image_data['error']
                : __('Unable to refresh analyzer data.', 'medicross-child');
            wp_send_json_error($error_message);
        }

        wp_send_json_success([
            'image' => $image_data,
        ]);
    }

    /**
     * AJAX handler for progress tracking
     */
    public function ajax_get_progress() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        
        // Get total published images (simplified for performance)
        $total_images = count($this->get_published_images());
        
        // Get optimized count (only from published images)
        $optimized_count = $wpdb->get_var("
            SELECT COUNT(DISTINCT pm.post_id) 
            FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE pm.meta_key = 'msh_optimized_date'
            AND p.post_type = 'attachment'
            AND p.post_mime_type LIKE 'image/%'
        ");
        
        $remaining = max(0, $total_images - $optimized_count);
        $percentage = $total_images > 0 ? min(100, round(($optimized_count / $total_images) * 100, 2)) : 0;
        
        $progress = [
            'total' => intval($total_images),
            'optimized' => intval($optimized_count),
            'percentage' => $percentage,
            'remaining' => $remaining
        ];
        
        wp_send_json_success($progress);
    }

    /**
     * AJAX handler to reset optimization flags (allows re-optimization with improved logic)
     */
    public function ajax_reset_optimization() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        
        // Remove optimization flags to allow re-processing with improved metadata preservation
        $reset_count = $wpdb->query("
            DELETE FROM {$wpdb->postmeta} 
            WHERE meta_key IN ('msh_optimized_date', '_msh_suggested_filename')
        ");
        
        wp_send_json_success([
            'reset_count' => $reset_count,
            'message' => "Reset {$reset_count} optimization flags. Images can now be re-optimized with improved metadata preservation."
        ]);
    }
    
    /**
     * Apply filename suggestions in batch
     */
    public function ajax_apply_filename_suggestions() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $mode = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : 'full';
        $limit = isset($_POST['limit']) ? max(0, intval($_POST['limit'])) : 0;

        $image_ids = isset($_POST['image_ids']) ? array_map('intval', $_POST['image_ids']) : [];

        if (empty($image_ids)) {
            global $wpdb;
            $image_ids = $wpdb->get_col("
                SELECT post_id FROM {$wpdb->postmeta}
                WHERE meta_key = '_msh_suggested_filename'
                AND meta_value != ''
            ");
        }

        if ($mode === 'test' && $limit > 0) {
            $image_ids = array_slice($image_ids, 0, $limit);
        }

        if (empty($image_ids)) {
            wp_send_json_success([
                'results' => [],
                'summary' => [
                    'total' => 0,
                    'success' => 0,
                    'errors' => 0,
                    'skipped' => 0
                ]
            ]);
        }

        $renamer = MSH_Safe_Rename_System::get_instance();

        $results = [];
        $success_count = 0;
        $error_count = 0;
        $skipped_count = 0;

        foreach ($image_ids as $attachment_id) {
            $suggested_filename = get_post_meta($attachment_id, '_msh_suggested_filename', true);

            if (!$suggested_filename) {
                $results[] = [
                    'id' => $attachment_id,
                    'status' => 'skipped',
                    'message' => 'No filename suggestion available'
                ];
                $skipped_count++;
                continue;
            }

            $suggested_filename = sanitize_file_name($suggested_filename);
            $result = $renamer->rename_attachment($attachment_id, basename($suggested_filename), $mode === 'test');

            if (is_wp_error($result)) {
                $results[] = [
                    'id' => $attachment_id,
                    'status' => 'error',
                    'message' => $result->get_error_message()
                ];
                $error_count++;
                continue;
            }

            if (!empty($result['skipped'])) {
                delete_post_meta($attachment_id, '_msh_suggested_filename');

                $results[] = [
                    'id' => $attachment_id,
                    'status' => 'skipped',
                    'message' => __('Filename already optimized', 'medicross-child')
                ];
                $skipped_count++;
                continue;
            }

            delete_post_meta($attachment_id, '_msh_suggested_filename');

            $results[] = [
                'id' => $attachment_id,
                'status' => 'success',
                'old_url' => $result['old_url'],
                'new_url' => $result['new_url'],
                'references_updated' => $result['replaced'],
                'message' => sprintf(__('References updated: %d', 'medicross-child'), $result['replaced'])
            ];
            $success_count++;
        }

        wp_send_json_success([
            'results' => $results,
            'summary' => [
                'total' => count($image_ids),
                'success' => $success_count,
                'errors' => $error_count,
                'skipped' => $skipped_count,
                'mode' => $mode
            ]
        ]);
    }
    
    /**
     * AJAX handler to save individual filename suggestion
     */
    public function ajax_save_filename_suggestion() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $image_id = intval($_POST['image_id'] ?? 0);
        $suggested_filename = sanitize_file_name($_POST['suggested_filename'] ?? '');
        
        if (!$image_id || !$suggested_filename) {
            wp_send_json_error('Missing image ID or filename suggestion');
            return;
        }
        
        // Validate the attachment exists
        if (!get_post($image_id) || get_post_type($image_id) !== 'attachment') {
            wp_send_json_error('Invalid attachment ID');
            return;
        }
        
        // Ensure the filename has an extension
        if (pathinfo($suggested_filename, PATHINFO_EXTENSION) === '') {
            // Get the original file extension
            $current_file = get_attached_file($image_id);
            if ($current_file) {
                $original_extension = pathinfo($current_file, PATHINFO_EXTENSION);
                $suggested_filename .= '.' . $original_extension;
            } else {
                $suggested_filename .= '.jpg'; // Default fallback
            }
        }
        
        // Save the suggestion with timestamp
        update_post_meta($image_id, '_msh_suggested_filename', $suggested_filename);
        update_post_meta($image_id, 'msh_filename_last_suggested', (int)time());
        
        wp_send_json_success([
            'message' => 'Filename suggestion saved successfully',
            'image_id' => $image_id,
            'suggested_filename' => $suggested_filename
        ]);
    }
    
    /**
     * AJAX handler to remove filename suggestion (keep current name)
     */
    public function ajax_remove_filename_suggestion() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $image_id = intval($_POST['image_id'] ?? 0);
        
        if (!$image_id) {
            wp_send_json_error('Missing image ID');
            return;
        }
        
        // Validate the attachment exists
        if (!get_post($image_id) || get_post_type($image_id) !== 'attachment') {
            wp_send_json_error('Invalid attachment ID');
            return;
        }
        
        // Remove the suggestion
        delete_post_meta($image_id, '_msh_suggested_filename');
        
        wp_send_json_success([
            'message' => 'Filename suggestion removed - current name will be kept',
            'image_id' => $image_id
        ]);
    }
    
    /**
     * AJAX handler to preview meta text generation
     */
    public function ajax_preview_meta_text() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $image_id = intval($_POST['image_id'] ?? 0);
        
        if (!$image_id) {
            wp_send_json_error('Missing image ID');
            return;
        }
        
        // Validate the attachment exists
        if (!get_post($image_id) || get_post_type($image_id) !== 'attachment') {
            wp_send_json_error('Invalid attachment ID');
            return;
        }
        
        // Generate meta text preview using the same logic as optimization
        $context = $this->determine_image_context($image_id);
        
        $preview = [
            'title' => $this->generate_title($image_id, $context),
            'caption' => $this->generate_caption($image_id, $context),
            'alt_text' => $this->generate_alt_text($image_id, $context),
            'description' => $this->generate_description($image_id, $context)
        ];
        
        wp_send_json_success($preview);
    }
    
    /**
     * AJAX handler to save edited meta text
     */
    public function ajax_save_edited_meta() {
        check_ajax_referer('msh_image_optimizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $image_id = intval($_POST['image_id'] ?? 0);
        $meta_data = wp_unslash($_POST['meta_data'] ?? []);
        
        if (!$image_id || empty($meta_data)) {
            wp_send_json_error('Missing image ID or meta data');
            return;
        }
        
        // Validate the attachment exists
        if (!get_post($image_id) || get_post_type($image_id) !== 'attachment') {
            wp_send_json_error('Invalid attachment ID');
            return;
        }
        
        $updates_made = [];
        
        // Update Title
        if (!empty($meta_data['title'])) {
            wp_update_post([
                'ID' => $image_id,
                'post_title' => sanitize_text_field($meta_data['title']),
                'post_name' => sanitize_title($meta_data['title'])
            ]);
            $updates_made[] = 'title';
        }
        
        // Update Caption
        if (!empty($meta_data['caption'])) {
            wp_update_post([
                'ID' => $image_id,
                'post_excerpt' => sanitize_textarea_field($meta_data['caption'])
            ]);
            $updates_made[] = 'caption';
        }
        
        // Update ALT text
        if (!empty($meta_data['alt_text'])) {
            update_post_meta($image_id, '_wp_attachment_image_alt', sanitize_text_field($meta_data['alt_text']));
            $updates_made[] = 'alt_text';
        }
        
        // Update Description
        if (!empty($meta_data['description'])) {
            wp_update_post([
                'ID' => $image_id,
                'post_content' => sanitize_textarea_field($meta_data['description'])
            ]);
            $updates_made[] = 'description';
        }
        
        // Update metadata timestamp to reflect manual edit
        update_post_meta($image_id, 'msh_metadata_last_updated', (int)time());
        update_post_meta($image_id, 'msh_metadata_source', 'manual_edit');
        
        wp_send_json_success([
            'message' => 'Meta text updated successfully',
            'updates_made' => $updates_made,
            'image_id' => $image_id
        ]);
    }
}

// Initialize the optimizer
new MSH_Image_Optimizer();

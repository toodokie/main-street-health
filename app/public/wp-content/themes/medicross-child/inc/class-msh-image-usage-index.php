<?php
/**
 * MSH Image Usage Index
 * Builds and maintains an index of where images are used for fast lookup during renames
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Image_Usage_Index {
    private static $instance = null;
    private $index_table;

    private function __construct() {
        global $wpdb;
        $this->index_table = $wpdb->prefix . 'msh_image_usage_index';

        add_action('init', [$this, 'maybe_create_index_table']);
        add_action('save_post', [$this, 'update_post_index'], 10, 1);
        add_action('deleted_post', [$this, 'remove_post_index'], 10, 1);
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function maybe_create_index_table() {
        if (get_option('msh_usage_index_table_version') === '1') {
            return;
        }

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->index_table} (
            id int(11) NOT NULL AUTO_INCREMENT,
            attachment_id int(11) NOT NULL,
            url_variation text NOT NULL,
            table_name varchar(64) NOT NULL,
            row_id int(11) NOT NULL,
            column_name varchar(64) NOT NULL,
            context_type varchar(50) DEFAULT 'content',
            post_type varchar(20) DEFAULT NULL,
            last_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY attachment_id (attachment_id),
            KEY table_row (table_name, row_id),
            KEY url_variation (url_variation(191)),
            KEY context_type (context_type),
            FULLTEXT KEY url_search (url_variation)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        update_option('msh_usage_index_table_version', '1');
    }

    /**
     * Build complete usage index for all images
     */
    public function build_complete_index($batch_size = 50) {
        global $wpdb;

        // Clear existing index
        $wpdb->query("TRUNCATE TABLE {$this->index_table}");

        $processed = 0;
        $total_attachments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'");

        $offset = 0;
        while ($offset < $total_attachments) {
            $attachments = $wpdb->get_results($wpdb->prepare("
                SELECT ID FROM {$wpdb->posts}
                WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'
                ORDER BY ID
                LIMIT %d OFFSET %d
            ", $batch_size, $offset));

            foreach ($attachments as $attachment) {
                $this->index_attachment_usage($attachment->ID);
                $processed++;
            }

            $offset += $batch_size;

            // Progress logging
            if ($processed % 100 === 0) {
                error_log("MSH Usage Index: Processed {$processed}/{$total_attachments} attachments");
            }
        }

        update_option('msh_usage_index_last_build', current_time('mysql'));
        return $processed;
    }

    /**
     * Index usage for a specific attachment
     */
    public function index_attachment_usage($attachment_id) {
        global $wpdb;

        // Remove existing entries for this attachment
        $wpdb->delete($this->index_table, ['attachment_id' => $attachment_id], ['%d']);

        // Get all URL variations for this attachment
        $detector = MSH_URL_Variation_Detector::get_instance();
        $variations = $detector->get_all_variations($attachment_id);

        if (empty($variations)) {
            return 0;
        }

        $usage_count = 0;

        // Index usage in posts content and excerpt
        $usage_count += $this->index_posts_usage($attachment_id, $variations);

        // Index usage in postmeta
        $usage_count += $this->index_postmeta_usage($attachment_id, $variations);

        // Index usage in options
        $usage_count += $this->index_options_usage($attachment_id, $variations);

        // Index usage in usermeta
        $usage_count += $this->index_usermeta_usage($attachment_id, $variations);

        // Index usage in termmeta if exists
        if (isset($wpdb->termmeta)) {
            $usage_count += $this->index_termmeta_usage($attachment_id, $variations);
        }

        return $usage_count;
    }

    /**
     * Index usage in posts table
     */
    private function index_posts_usage($attachment_id, $variations) {
        global $wpdb;
        $usage_count = 0;

        foreach ($variations as $variation) {
            if (empty($variation)) continue;

            $like = '%' . $wpdb->esc_like($variation) . '%';

            // Check post_content
            $posts = $wpdb->get_results($wpdb->prepare("
                SELECT ID, post_type, post_content
                FROM {$wpdb->posts}
                WHERE post_content LIKE %s
            ", $like));

            foreach ($posts as $post) {
                $wpdb->insert($this->index_table, [
                    'attachment_id' => $attachment_id,
                    'url_variation' => $variation,
                    'table_name' => 'posts',
                    'row_id' => $post->ID,
                    'column_name' => 'post_content',
                    'context_type' => 'content',
                    'post_type' => $post->post_type
                ]);
                $usage_count++;
            }

            // Check post_excerpt
            $posts = $wpdb->get_results($wpdb->prepare("
                SELECT ID, post_type, post_excerpt
                FROM {$wpdb->posts}
                WHERE post_excerpt LIKE %s
            ", $like));

            foreach ($posts as $post) {
                $wpdb->insert($this->index_table, [
                    'attachment_id' => $attachment_id,
                    'url_variation' => $variation,
                    'table_name' => 'posts',
                    'row_id' => $post->ID,
                    'column_name' => 'post_excerpt',
                    'context_type' => 'excerpt',
                    'post_type' => $post->post_type
                ]);
                $usage_count++;
            }
        }

        return $usage_count;
    }

    /**
     * Index usage in postmeta table
     */
    private function index_postmeta_usage($attachment_id, $variations) {
        global $wpdb;
        $usage_count = 0;

        foreach ($variations as $variation) {
            if (empty($variation)) continue;

            $like = '%' . $wpdb->esc_like($variation) . '%';

            $meta_rows = $wpdb->get_results($wpdb->prepare("
                SELECT pm.meta_id, pm.post_id, pm.meta_key, pm.meta_value, p.post_type
                FROM {$wpdb->postmeta} pm
                LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                WHERE pm.meta_value LIKE %s
            ", $like));

            foreach ($meta_rows as $meta) {
                $context_type = $this->determine_meta_context($meta->meta_key, $meta->meta_value);

                $wpdb->insert($this->index_table, [
                    'attachment_id' => $attachment_id,
                    'url_variation' => $variation,
                    'table_name' => 'postmeta',
                    'row_id' => $meta->meta_id,
                    'column_name' => 'meta_value',
                    'context_type' => $context_type,
                    'post_type' => $meta->post_type
                ]);
                $usage_count++;
            }
        }

        return $usage_count;
    }

    /**
     * Index usage in options table
     */
    private function index_options_usage($attachment_id, $variations) {
        global $wpdb;
        $usage_count = 0;

        foreach ($variations as $variation) {
            if (empty($variation)) continue;

            $like = '%' . $wpdb->esc_like($variation) . '%';

            $options = $wpdb->get_results($wpdb->prepare("
                SELECT option_id, option_name, option_value
                FROM {$wpdb->options}
                WHERE option_value LIKE %s
            ", $like));

            foreach ($options as $option) {
                $context_type = $this->determine_option_context($option->option_name, $option->option_value);

                $wpdb->insert($this->index_table, [
                    'attachment_id' => $attachment_id,
                    'url_variation' => $variation,
                    'table_name' => 'options',
                    'row_id' => $option->option_id,
                    'column_name' => 'option_value',
                    'context_type' => $context_type,
                    'post_type' => null
                ]);
                $usage_count++;
            }
        }

        return $usage_count;
    }

    /**
     * Index usage in usermeta table
     */
    private function index_usermeta_usage($attachment_id, $variations) {
        global $wpdb;
        $usage_count = 0;

        foreach ($variations as $variation) {
            if (empty($variation)) continue;

            $like = '%' . $wpdb->esc_like($variation) . '%';

            $meta_rows = $wpdb->get_results($wpdb->prepare("
                SELECT umeta_id, user_id, meta_key, meta_value
                FROM {$wpdb->usermeta}
                WHERE meta_value LIKE %s
            ", $like));

            foreach ($meta_rows as $meta) {
                $wpdb->insert($this->index_table, [
                    'attachment_id' => $attachment_id,
                    'url_variation' => $variation,
                    'table_name' => 'usermeta',
                    'row_id' => $meta->umeta_id,
                    'column_name' => 'meta_value',
                    'context_type' => 'user_meta',
                    'post_type' => null
                ]);
                $usage_count++;
            }
        }

        return $usage_count;
    }

    /**
     * Index usage in termmeta table
     */
    private function index_termmeta_usage($attachment_id, $variations) {
        global $wpdb;
        $usage_count = 0;

        foreach ($variations as $variation) {
            if (empty($variation)) continue;

            $like = '%' . $wpdb->esc_like($variation) . '%';

            $meta_rows = $wpdb->get_results($wpdb->prepare("
                SELECT meta_id, term_id, meta_key, meta_value
                FROM {$wpdb->termmeta}
                WHERE meta_value LIKE %s
            ", $like));

            foreach ($meta_rows as $meta) {
                $wpdb->insert($this->index_table, [
                    'attachment_id' => $attachment_id,
                    'url_variation' => $variation,
                    'table_name' => 'termmeta',
                    'row_id' => $meta->meta_id,
                    'column_name' => 'meta_value',
                    'context_type' => 'term_meta',
                    'post_type' => null
                ]);
                $usage_count++;
            }
        }

        return $usage_count;
    }

    /**
     * Determine context type for postmeta
     */
    private function determine_meta_context($meta_key, $meta_value) {
        // Featured image
        if ($meta_key === '_thumbnail_id') {
            return 'featured_image';
        }

        // ACF fields
        if (strpos($meta_key, 'field_') === 0 || function_exists('get_field_object')) {
            return 'acf_field';
        }

        // Gallery fields
        if (strpos($meta_key, 'gallery') !== false || strpos($meta_key, '_gallery') !== false) {
            return 'gallery';
        }

        // Page builder content
        if (strpos($meta_key, '_elementor_data') !== false || strpos($meta_key, 'vc_') === 0) {
            return 'page_builder';
        }

        // Serialized data
        if (is_serialized($meta_value)) {
            return 'serialized_meta';
        }

        return 'meta';
    }

    /**
     * Determine context type for options
     */
    private function determine_option_context($option_name, $option_value) {
        // Theme options
        if (strpos($option_name, 'theme_') === 0 || strpos($option_name, 'mods_') === 0) {
            return 'theme_options';
        }

        // Widget data
        if (strpos($option_name, 'widget_') === 0) {
            return 'widget';
        }

        // Customizer
        if (strpos($option_name, 'customize_') === 0) {
            return 'customizer';
        }

        // Serialized data
        if (is_serialized($option_value)) {
            return 'serialized_option';
        }

        return 'option';
    }

    /**
     * Get usage locations for an attachment
     */
    public function get_attachment_usage($attachment_id) {
        global $wpdb;

        return $wpdb->get_results($wpdb->prepare("
            SELECT *
            FROM {$this->index_table}
            WHERE attachment_id = %d
            ORDER BY table_name, context_type, row_id
        ", $attachment_id));
    }

    /**
     * Get targeted update queries for an attachment rename
     */
    public function get_targeted_updates($attachment_id, $replacement_map) {
        global $wpdb;

        $updates = [];
        $usage_locations = $this->get_attachment_usage($attachment_id);

        foreach ($usage_locations as $location) {
            $old_url = $location->url_variation;
            $new_url = $replacement_map[$old_url] ?? null;

            if (!$new_url || $old_url === $new_url) {
                continue;
            }

            $table_name = $wpdb->prefix . $location->table_name;
            $id_column = $this->get_id_column_for_table($location->table_name);

            $updates[] = [
                'table' => $table_name,
                'id_column' => $id_column,
                'row_id' => $location->row_id,
                'column' => $location->column_name,
                'old_value' => $old_url,
                'new_value' => $new_url,
                'context' => $location->context_type
            ];
        }

        return $updates;
    }

    /**
     * Update post index when a post is saved
     */
    public function update_post_index($post_id) {
        // Get all attachments used in this post
        $content = get_post_field('post_content', $post_id);
        $excerpt = get_post_field('post_excerpt', $post_id);

        // Extract attachment IDs from content
        preg_match_all('/wp-image-(\d+)/', $content . ' ' . $excerpt, $matches);
        $attachment_ids = array_unique($matches[1]);

        foreach ($attachment_ids as $attachment_id) {
            $this->index_attachment_usage($attachment_id);
        }
    }

    /**
     * Remove post from index when deleted
     */
    public function remove_post_index($post_id) {
        global $wpdb;

        $wpdb->delete($this->index_table, [
            'table_name' => 'posts',
            'row_id' => $post_id
        ], ['%s', '%d']);

        $wpdb->delete($this->index_table, [
            'table_name' => 'postmeta',
            'row_id' => $post_id
        ], ['%s', '%d']);
    }

    /**
     * Get ID column for table
     */
    private function get_id_column_for_table($table) {
        $columns = [
            'posts' => 'ID',
            'postmeta' => 'meta_id',
            'options' => 'option_id',
            'usermeta' => 'umeta_id',
            'termmeta' => 'meta_id'
        ];

        return $columns[$table] ?? 'id';
    }

    /**
     * Get index statistics
     */
    public function get_index_stats() {
        global $wpdb;

        $stats = $wpdb->get_row("
            SELECT
                COUNT(*) as total_entries,
                COUNT(DISTINCT attachment_id) as indexed_attachments,
                COUNT(DISTINCT CONCAT(table_name, '-', row_id)) as unique_locations,
                MAX(last_updated) as last_update
            FROM {$this->index_table}
        ");

        $context_stats = $wpdb->get_results("
            SELECT context_type, COUNT(*) as count
            FROM {$this->index_table}
            GROUP BY context_type
            ORDER BY count DESC
        ");

        return [
            'summary' => $stats,
            'by_context' => $context_stats
        ];
    }
}
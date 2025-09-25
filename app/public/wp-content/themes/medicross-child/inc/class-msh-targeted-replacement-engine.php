<?php
/**
 * MSH Targeted Replacement Engine
 * Performs fast, precise URL replacements using the usage index
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Targeted_Replacement_Engine {
    private static $instance = null;
    private $usage_index;
    private $backup_system;
    private $url_detector;

    private function __construct() {
        // Lazy load these to avoid instantiation issues
        $this->usage_index = null;
        $this->backup_system = MSH_Backup_Verification_System::get_instance();
        $this->url_detector = MSH_URL_Variation_Detector::get_instance();
    }

    private function get_usage_index() {
        if ($this->usage_index === null) {
            $this->usage_index = MSH_Image_Usage_Index::get_instance();
        }
        return $this->usage_index;
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Perform safe, targeted replacement for an attachment
     */
    public function replace_attachment_urls($attachment_id, $old_filename, $new_filename, $test_mode = false) {
        global $wpdb;

        // Generate operation ID for tracking
        $operation_id = $this->backup_system->generate_operation_id();

        // Build replacement map
        $replacement_map = $this->url_detector->build_filename_replacement_map($attachment_id, $old_filename, $new_filename);

        if (empty($replacement_map)) {
            return new WP_Error('no_replacements', 'No URL variations found for replacement');
        }

        // Validate replacement map
        $validation = $this->url_detector->validate_replacement_map($replacement_map);
        if ($validation !== true) {
            return new WP_Error('invalid_map', 'Invalid replacement map: ' . implode(', ', $validation));
        }

        $results = [
            'operation_id' => $operation_id,
            'attachment_id' => $attachment_id,
            'replacement_map' => $replacement_map,
            'test_mode' => $test_mode,
            'backup_count' => 0,
            'updated_count' => 0,
            'error_count' => 0,
            'updates' => [],
            'errors' => []
        ];

        try {
            // Create backup if not in test mode
            if (!$test_mode) {
                $results['backup_count'] = $this->backup_system->create_backup($operation_id, $attachment_id, $replacement_map);
            }

            // Get targeted updates using fast index lookup (with extensive debugging)
            error_log('MSH INDEX DEBUG: Starting index lookup for attachment ' . $attachment_id);

            $usage_index = $this->get_usage_index();
            error_log('MSH INDEX DEBUG: Got usage index instance: ' . (is_object($usage_index) ? 'SUCCESS' : 'FAILED'));

            $targeted_updates = $usage_index->get_targeted_updates($attachment_id, $replacement_map);
            error_log('MSH INDEX DEBUG: get_targeted_updates returned ' . count($targeted_updates) . ' results');

            if (!empty($targeted_updates)) {
                error_log('MSH INDEX DEBUG: Using index data - found ' . count($targeted_updates) . ' targeted updates');
                foreach ($targeted_updates as $i => $update) {
                    error_log('MSH INDEX DEBUG: Update ' . ($i+1) . ': ' . $update['table'] . '.' . $update['column'] . ' row_id=' . $update['row_id']);
                }
            }

            // Fallback to direct scanning if index is empty/missing
            if (empty($targeted_updates)) {
                error_log('MSH INDEX DEBUG: ❌ INDEX FAILED - No results from index lookup');
                error_log('MSH INDEX DEBUG: Checking why index is empty...');

                // Debug the index state
                global $wpdb;
                $index_table = $wpdb->prefix . 'msh_image_usage_index';
                $total_entries = $wpdb->get_var("SELECT COUNT(*) FROM {$index_table}");
                $attachment_entries = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$index_table} WHERE attachment_id = %d", $attachment_id));

                error_log('MSH INDEX DEBUG: Index table total entries: ' . $total_entries);
                error_log('MSH INDEX DEBUG: Entries for attachment ' . $attachment_id . ': ' . $attachment_entries);

                if ($attachment_entries > 0) {
                    $sample_entries = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$index_table} WHERE attachment_id = %d LIMIT 3", $attachment_id));
                    error_log('MSH INDEX DEBUG: Sample index entries: ' . print_r($sample_entries, true));
                    error_log('MSH INDEX DEBUG: Replacement map keys: ' . implode(', ', array_keys($replacement_map)));
                }

                error_log('MSH Safe Rename: Index empty, falling back to direct scanning for attachment ' . $attachment_id);
                $targeted_updates = $this->get_targeted_updates_direct($attachment_id, $replacement_map);
            }

            // Perform targeted updates
            foreach ($targeted_updates as $update) {
                $update_result = $this->perform_targeted_update($update, $test_mode);

                if ($update_result['success']) {
                    $results['updated_count']++;
                    $results['updates'][] = $update_result;
                } else {
                    $results['error_count']++;
                    $results['errors'][] = $update_result;
                }
            }

            // Debug before verification check
            error_log('MSH REPLACEMENT DEBUG: Verification check - test_mode: ' . ($test_mode ? 'TRUE' : 'FALSE') . ', error_count: ' . $results['error_count']);

            // Perform verification if not in test mode
            if (!$test_mode && $results['error_count'] === 0) {
                error_log('MSH REPLACEMENT DEBUG: ✅ PROCEEDING WITH VERIFICATION');
                error_log('MSH REPLACEMENT DEBUG: About to call verification with targeted_updates: ' . (empty($targeted_updates) ? 'EMPTY' : count($targeted_updates) . ' items'));
                if (!empty($targeted_updates)) {
                    foreach ($targeted_updates as $i => $update) {
                        error_log('MSH REPLACEMENT DEBUG: Update ' . ($i+1) . ': ' . $update['table'] . '.' . $update['column'] . ' row_id=' . $update['row_id']);
                    }
                }
                // Pass the targeted updates list to verification for precise checking
                $verification = $this->backup_system->verify_replacement($operation_id, $attachment_id, $replacement_map, $targeted_updates);
                $results['verification'] = $verification;

                // If verification failed, restore backup
                if ($verification['overall_status'] === 'failed') {
                    $restored = $this->backup_system->restore_backup($operation_id);
                    $results['backup_restored'] = $restored;
                    return new WP_Error('verification_failed', 'Replacement verification failed, backup restored', $results);
                }
            } else {
                error_log('MSH REPLACEMENT DEBUG: ❌ SKIPPING VERIFICATION - test_mode: ' . ($test_mode ? 'TRUE' : 'FALSE') . ', error_count: ' . $results['error_count']);
            }

        } catch (Exception $e) {
            // If something went wrong and we're not in test mode, restore backup
            if (!$test_mode && $results['backup_count'] > 0) {
                $this->backup_system->restore_backup($operation_id);
            }

            return new WP_Error('replacement_error', $e->getMessage(), $results);
        }

        return $results;
    }

    /**
     * Get targeted updates directly without using index (simplified approach)
     */
    private function get_targeted_updates_direct($attachment_id, $replacement_map) {
        global $wpdb;
        $updates = [];

        foreach ($replacement_map as $old_url => $new_url) {
            if ($old_url === $new_url) continue;

            $like_pattern = '%' . $wpdb->esc_like($old_url) . '%';

            // Search posts content
            $posts = $wpdb->get_results($wpdb->prepare("
                SELECT ID, post_type FROM {$wpdb->posts}
                WHERE (post_content LIKE %s OR post_excerpt LIKE %s)
                AND post_status = 'publish'
            ", $like_pattern, $like_pattern));

            foreach ($posts as $post) {
                $updates[] = [
                    'table' => $wpdb->posts,
                    'id_column' => 'ID',
                    'row_id' => $post->ID,
                    'column' => 'post_content',
                    'old_value' => $old_url,
                    'new_value' => $new_url,
                    'context' => 'content'
                ];
                $updates[] = [
                    'table' => $wpdb->posts,
                    'id_column' => 'ID',
                    'row_id' => $post->ID,
                    'column' => 'post_excerpt',
                    'old_value' => $old_url,
                    'new_value' => $new_url,
                    'context' => 'excerpt'
                ];
            }

            // Search postmeta
            $meta_rows = $wpdb->get_results($wpdb->prepare("
                SELECT meta_id, post_id FROM {$wpdb->postmeta}
                WHERE meta_value LIKE %s
            ", $like_pattern));

            foreach ($meta_rows as $meta) {
                $updates[] = [
                    'table' => $wpdb->postmeta,
                    'id_column' => 'meta_id',
                    'row_id' => $meta->meta_id,
                    'column' => 'meta_value',
                    'old_value' => $old_url,
                    'new_value' => $new_url,
                    'context' => 'meta'
                ];
            }

            // Search options (widgets, etc.)
            $options = $wpdb->get_results($wpdb->prepare("
                SELECT option_id FROM {$wpdb->options}
                WHERE option_value LIKE %s
            ", $like_pattern));

            foreach ($options as $option) {
                $updates[] = [
                    'table' => $wpdb->options,
                    'id_column' => 'option_id',
                    'row_id' => $option->option_id,
                    'column' => 'option_value',
                    'old_value' => $old_url,
                    'new_value' => $new_url,
                    'context' => 'option'
                ];
            }
        }

        return $updates;
    }

    /**
     * Perform a single targeted update
     */
    private function perform_targeted_update($update, $test_mode = false) {
        global $wpdb;

        error_log('MSH REPLACEMENT DEBUG: Performing update on ' . $update['table'] . ' row ' . $update['row_id'] . ' (test_mode: ' . ($test_mode ? 'TRUE' : 'FALSE') . ')');

        $result = [
            'success' => false,
            'table' => $update['table'],
            'row_id' => $update['row_id'],
            'column' => $update['column'],
            'context' => $update['context'],
            'old_value' => $update['old_value'],
            'new_value' => $update['new_value'],
            'test_mode' => $test_mode
        ];

        try {
            // Get current value
            $current_value = $wpdb->get_var($wpdb->prepare(
                "SELECT {$update['column']} FROM {$update['table']} WHERE {$update['id_column']} = %d",
                $update['row_id']
            ));

            if ($current_value === null) {
                $result['error'] = 'Row not found';
                error_log('MSH REPLACEMENT DEBUG: ❌ ERROR - Row not found: ' . $update['table'] . ' row ' . $update['row_id']);
                return $result;
            }

            // Handle serialized data specially
            $new_content = $this->replace_in_content($current_value, $update['old_value'], $update['new_value'], $update['context']);

            if ($new_content === $current_value) {
                $result['success'] = true;
                $result['message'] = 'No replacement needed';
                return $result;
            }

            // Perform update if not in test mode
            if (!$test_mode) {
                $updated = $wpdb->update(
                    $update['table'],
                    [$update['column'] => $new_content],
                    [$update['id_column'] => $update['row_id']],
                    ['%s'],
                    ['%d']
                );

                if ($updated === false) {
                    $result['error'] = 'Database update failed: ' . $wpdb->last_error;
                    error_log('MSH REPLACEMENT DEBUG: ❌ ERROR - Database update failed: ' . $wpdb->last_error . ' (table: ' . $update['table'] . ', row: ' . $update['row_id'] . ')');
                    return $result;
                }
            }

            $result['success'] = true;
            $result['message'] = $test_mode ? 'Would update' : 'Updated successfully';
            $result['changes_made'] = substr_count($current_value, $update['old_value']);
            error_log('MSH REPLACEMENT DEBUG: ✅ SUCCESS - ' . $result['message'] . ' (changes: ' . $result['changes_made'] . ')');

        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            error_log('MSH REPLACEMENT DEBUG: ❌ EXCEPTION - ' . $e->getMessage() . ' (table: ' . $update['table'] . ', row: ' . $update['row_id'] . ')');
        }

        return $result;
    }

    /**
     * Replace URLs in content, handling serialized data
     */
    private function replace_in_content($content, $old_url, $new_url, $context) {
        if (empty($content) || $old_url === $new_url) {
            return $content;
        }

        // Handle serialized data
        if (is_serialized($content)) {
            $unserialized = maybe_unserialize($content);
            $updated = $this->recursive_replace($unserialized, $old_url, $new_url);
            return maybe_serialize($updated);
        }

        // Handle JSON data (common in modern page builders)
        if ($this->is_json($content)) {
            $decoded = json_decode($content, true);
            if ($decoded !== null) {
                $updated = $this->recursive_replace($decoded, $old_url, $new_url);
                return json_encode($updated, JSON_UNESCAPED_SLASHES);
            }
        }

        // Regular string replacement
        return str_replace($old_url, $new_url, $content);
    }

    /**
     * Recursively replace URLs in nested data structures
     */
    private function recursive_replace($data, $old_url, $new_url) {
        if (is_string($data)) {
            return str_replace($old_url, $new_url, $data);
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->recursive_replace($value, $old_url, $new_url);
            }
            return $data;
        }

        if (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = $this->recursive_replace($value, $old_url, $new_url);
            }
            return $data;
        }

        return $data;
    }

    /**
     * Check if string is JSON
     */
    private function is_json($string) {
        if (!is_string($string) || empty($string)) {
            return false;
        }

        $first_char = $string[0];
        return ($first_char === '{' || $first_char === '[') && json_decode($string) !== null;
    }

    /**
     * Batch process multiple attachments
     */
    public function batch_replace($attachments, $test_mode = false, $batch_size = 10) {
        $results = [
            'total_attachments' => count($attachments),
            'processed' => 0,
            'successful' => 0,
            'failed' => 0,
            'details' => []
        ];

        $batches = array_chunk($attachments, $batch_size);

        foreach ($batches as $batch_index => $batch) {
            foreach ($batch as $attachment_data) {
                $attachment_id = $attachment_data['id'];
                $old_filename = $attachment_data['old_filename'];
                $new_filename = $attachment_data['new_filename'];

                $result = $this->replace_attachment_urls($attachment_id, $old_filename, $new_filename, $test_mode);

                if (is_wp_error($result)) {
                    $results['failed']++;
                    $results['details'][$attachment_id] = [
                        'status' => 'error',
                        'error' => $result->get_error_message(),
                        'data' => $result->get_error_data()
                    ];
                } else {
                    $results['successful']++;
                    $results['details'][$attachment_id] = [
                        'status' => 'success',
                        'data' => $result
                    ];
                }

                $results['processed']++;

                // Progress logging
                if ($results['processed'] % 10 === 0) {
                    error_log("MSH Targeted Replacement: Processed {$results['processed']}/{$results['total_attachments']} attachments");
                }
            }

            // Brief pause between batches to prevent timeouts
            if ($batch_index < count($batches) - 1) {
                usleep(100000); // 0.1 second
            }
        }

        return $results;
    }

    /**
     * Dry run to preview what would be changed
     */
    public function preview_changes($attachment_id, $old_filename, $new_filename) {
        // Build replacement map
        $replacement_map = $this->url_detector->build_filename_replacement_map($attachment_id, $old_filename, $new_filename);

        if (empty($replacement_map)) {
            return ['error' => 'No URL variations found'];
        }

        // Get targeted updates using fast index lookup
        $targeted_updates = $this->usage_index->get_targeted_updates($attachment_id, $replacement_map);

        $preview = [
            'attachment_id' => $attachment_id,
            'old_filename' => $old_filename,
            'new_filename' => $new_filename,
            'replacement_map' => $replacement_map,
            'total_updates' => count($targeted_updates),
            'updates_by_context' => [],
            'updates_by_table' => [],
            'sample_updates' => array_slice($targeted_updates, 0, 10)
        ];

        // Group by context
        foreach ($targeted_updates as $update) {
            $context = $update['context'];
            $table = basename($update['table']);

            if (!isset($preview['updates_by_context'][$context])) {
                $preview['updates_by_context'][$context] = 0;
            }
            $preview['updates_by_context'][$context]++;

            if (!isset($preview['updates_by_table'][$table])) {
                $preview['updates_by_table'][$table] = 0;
            }
            $preview['updates_by_table'][$table]++;
        }

        return $preview;
    }

    /**
     * Get replacement statistics
     */
    public function get_replacement_stats($days = 30) {
        global $wpdb;

        // Get stats from backup table
        $backup_table = $wpdb->prefix . 'msh_rename_backups';
        $verification_table = $wpdb->prefix . 'msh_rename_verification';

        $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        $stats = $wpdb->get_row($wpdb->prepare("
            SELECT
                COUNT(DISTINCT operation_id) as total_operations,
                COUNT(DISTINCT attachment_id) as unique_attachments,
                COUNT(*) as total_backups,
                AVG(CASE WHEN status = 'restored' THEN 1 ELSE 0 END) as restore_rate
            FROM {$backup_table}
            WHERE backup_date >= %s
        ", $cutoff_date));

        $verification_stats = $wpdb->get_row($wpdb->prepare("
            SELECT
                COUNT(*) as total_checks,
                AVG(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as success_rate
            FROM {$verification_table}
            WHERE check_date >= %s
        ", $cutoff_date));

        return [
            'period_days' => $days,
            'operations' => $stats,
            'verification' => $verification_stats
        ];
    }
}
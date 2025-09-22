<?php
/**
 * MSH Safe Rename System
 * Handles filename changes while updating references safely.
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Safe_Rename_System {
    private static $instance = null;
    private $log_table;
    private $test_mode = false;
    private $last_replacements = 0;
    private $backup_retention = DAY_IN_SECONDS;

    private function __construct() {
        global $wpdb;
        $this->log_table = $wpdb->prefix . 'msh_rename_log';

        add_action('init', [$this, 'maybe_create_log_table']);
        add_action('template_redirect', [$this, 'handle_old_urls'], 1);
        add_action('msh_cleanup_rename_backup', [$this, 'cleanup_backup'], 10, 1);
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function maybe_create_log_table() {
        if (get_option('msh_rename_log_table_version') === '1') {
            return;
        }

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->log_table} (
            id int(11) NOT NULL AUTO_INCREMENT,
            attachment_id int(11) NOT NULL,
            old_filename varchar(255) NOT NULL,
            new_filename varchar(255) NOT NULL,
            old_url varchar(500) NOT NULL,
            new_url varchar(500) NOT NULL,
            old_relative varchar(500) NOT NULL,
            new_relative varchar(500) NOT NULL,
            renamed_date datetime DEFAULT CURRENT_TIMESTAMP,
            replaced_count int(11) DEFAULT 0,
            status varchar(20) DEFAULT 'pending',
            details text NULL,
            PRIMARY KEY (id),
            KEY attachment_id (attachment_id),
            KEY old_url (old_url(191))
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        update_option('msh_rename_log_table_version', '1');
    }

    public function rename_attachment($attachment_id, $new_filename, $test_mode = false) {
        $this->test_mode = (bool) $test_mode;
        $this->last_replacements = 0;

        $current_path = get_attached_file($attachment_id);
        if (!$current_path || !file_exists($current_path)) {
            return new WP_Error('missing_file', __('Original file not found for attachment.', 'medicross-child'));
        }

        $new_filename = sanitize_file_name($new_filename);
        $current_basename = basename($current_path);
        if ($new_filename === '' || strcasecmp($current_basename, $new_filename) === 0) {
            return [
                'old_url' => wp_get_attachment_url($attachment_id),
                'new_url' => wp_get_attachment_url($attachment_id),
                'replaced' => 0,
                'skipped' => true
            ];
        }

        $upload_dir = wp_upload_dir();
        $old_url = wp_get_attachment_url($attachment_id);
        $old_relative = get_post_meta($attachment_id, '_wp_attached_file', true);

        $new_filename = $this->ensure_unique_filename($new_filename, dirname($current_path));
        $new_relative = str_replace(basename($old_relative), $new_filename, $old_relative);
        $new_url = trailingslashit($upload_dir['baseurl']) . ltrim($new_relative, '/');

        $log_id = $this->log_intent($attachment_id, $current_basename, $new_filename, $old_url, $new_url, $old_relative, $new_relative);

        $old_metadata = wp_get_attachment_metadata($attachment_id);
        $rename = $this->rename_physical_files($current_path, $new_filename, $old_metadata);
        if (is_wp_error($rename)) {
            $this->update_log($log_id, 'failed', 0, $rename->get_error_message());
            return $rename;
        }

        $this->update_wordpress_metadata($attachment_id, $rename['new_path'], $old_metadata, $new_relative);

        $map = $this->build_search_replace_map($old_url, $new_url, $old_metadata, $upload_dir);
        $replaced = $this->replace_references($map, $attachment_id, $current_basename, $new_filename);
        $this->last_replacements = $replaced;

        $this->update_log($log_id, 'complete', $replaced, null);

        return [
            'old_url' => $old_url,
            'new_url' => $new_url,
            'replaced' => $replaced,
            'backup' => $rename['backup_path']
        ];
    }

    private function log_intent($attachment_id, $old_filename, $new_filename, $old_url, $new_url, $old_relative, $new_relative) {
        global $wpdb;

        $wpdb->insert(
            $this->log_table,
            [
                'attachment_id' => $attachment_id,
                'old_filename' => $old_filename,
                'new_filename' => $new_filename,
                'old_url' => $old_url,
                'new_url' => $new_url,
                'old_relative' => $old_relative,
                'new_relative' => $new_relative,
                'status' => 'pending'
            ],
            ['%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s']
        );

        return $wpdb->insert_id;
    }

    private function update_log($log_id, $status, $replaced_count = 0, $details = null) {
        global $wpdb;

        $wpdb->update(
            $this->log_table,
            [
                'status' => $status,
                'replaced_count' => intval($replaced_count),
                'details' => $details
            ],
            ['id' => $log_id],
            ['%s', '%d', '%s'],
            ['%d']
        );
    }

    private function rename_physical_files($old_path, $new_filename, $old_metadata) {
        $dir = dirname($old_path);
        $new_path = trailingslashit($dir) . $new_filename;

        if (!copy($old_path, $new_path)) {
            return new WP_Error('copy_failed', __('Unable to copy new file.', 'medicross-child'));
        }

        $backup_path = $this->move_to_backup($old_path);

        if (is_array($old_metadata) && !empty($old_metadata['sizes'])) {
            foreach ($old_metadata['sizes'] as $size => $data) {
                if (empty($data['file'])) {
                    continue;
                }

                $old_size_path = trailingslashit($dir) . $data['file'];
                if (!file_exists($old_size_path)) {
                    continue;
                }

                $ext = pathinfo($data['file'], PATHINFO_EXTENSION);
                $new_size_filename = pathinfo($new_filename, PATHINFO_FILENAME) . '-' . $data['width'] . 'x' . $data['height'] . '.' . $ext;
                $new_size_path = trailingslashit($dir) . $new_size_filename;

                if (@copy($old_size_path, $new_size_path)) {
                    $this->move_to_backup($old_size_path);
                }
            }
        }

        return [
            'new_path' => $new_path,
            'backup_path' => $backup_path
        ];
    }

    private function ensure_unique_filename($filename, $directory) {
        $directory = trailingslashit($directory);
        $pathinfo = pathinfo($filename);
        $name = $pathinfo['filename'];
        $ext = isset($pathinfo['extension']) && $pathinfo['extension'] !== '' ? '.' . $pathinfo['extension'] : '';
        $candidate = $filename;
        $counter = 1;

        while (file_exists($directory . $candidate)) {
            $candidate = sprintf('%s-%d%s', $name, $counter, $ext);
            $counter++;
        }

        return $candidate;
    }

    private function move_to_backup($path) {
        if (!file_exists($path)) {
            return null;
        }

        $upload_dir = wp_upload_dir();
        $base_dir = trailingslashit($upload_dir['basedir']);
        $real_path = realpath($path);

        if ($real_path === false || strpos($real_path, $base_dir) !== 0) {
            return null;
        }

        $backup_dir = $base_dir . 'msh-rename-backups';
        if (!file_exists($backup_dir)) {
            wp_mkdir_p($backup_dir);
        }

        $backup_path = trailingslashit($backup_dir) . basename($path) . '.' . time();
        if (@rename($path, $backup_path)) {
            wp_schedule_single_event(time() + $this->backup_retention, 'msh_cleanup_rename_backup', [$backup_path]);
            return $backup_path;
        }

        return null;
    }

    private function update_wordpress_metadata($attachment_id, $new_path, $old_metadata, $new_relative) {
        update_attached_file($attachment_id, $new_path);

        if (is_array($old_metadata)) {
            $metadata = $old_metadata;
            $metadata['file'] = $new_relative;

            if (!empty($metadata['sizes'])) {
                foreach ($metadata['sizes'] as $size => $data) {
                    $ext = pathinfo($data['file'], PATHINFO_EXTENSION);
                    $metadata['sizes'][$size]['file'] = pathinfo($new_relative, PATHINFO_FILENAME) . '-' . $data['width'] . 'x' . $data['height'] . '.' . $ext;
                }
            }

            wp_update_attachment_metadata($attachment_id, $metadata);
        }

        $mime = get_post_mime_type($attachment_id);
        if ($mime && strpos($mime, 'image/') === 0) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $regen = wp_generate_attachment_metadata($attachment_id, $new_path);
            if (!is_wp_error($regen) && !empty($regen)) {
                $regen['file'] = $new_relative;
                wp_update_attachment_metadata($attachment_id, $regen);
            }
        }

        $guid = wp_get_attachment_url($attachment_id);
        if ($guid) {
            global $wpdb;
            $wpdb->update(
                $wpdb->posts,
                ['guid' => $guid, 'post_name' => sanitize_title(pathinfo($new_relative, PATHINFO_FILENAME))],
                ['ID' => $attachment_id],
                ['%s', '%s'],
                ['%d']
            );
        }
    }

    private function build_search_replace_map($old_url, $new_url, $old_metadata, $upload_dir) {
        $map = [];
        $map[$old_url] = $new_url;

        $old_relative = str_replace(trailingslashit($upload_dir['baseurl']), '', $old_url);
        $new_relative = str_replace(trailingslashit($upload_dir['baseurl']), '', $new_url);
        $map[$old_relative] = $new_relative;

        $map[basename($old_url)] = basename($new_url);

        if (is_array($old_metadata) && !empty($old_metadata['sizes'])) {
            $old_dir = trailingslashit(dirname($old_url));
            $new_dir = trailingslashit(dirname($new_url));
            foreach ($old_metadata['sizes'] as $size => $data) {
                if (empty($data['file'])) {
                    continue;
                }

                $old_size_url = $old_dir . $data['file'];
                $ext = pathinfo($data['file'], PATHINFO_EXTENSION);
                $new_size_filename = pathinfo($new_url, PATHINFO_FILENAME) . '-' . $data['width'] . 'x' . $data['height'] . '.' . $ext;
                $new_size_url = $new_dir . $new_size_filename;
                $map[$old_size_url] = $new_size_url;

                $old_size_rel = str_replace(trailingslashit($upload_dir['baseurl']), '', $old_size_url);
                $new_size_rel = str_replace(trailingslashit($upload_dir['baseurl']), '', $new_size_url);
                $map[$old_size_rel] = $new_size_rel;
            }
        }

        return $map;
    }

    private function replace_references($map, $attachment_id = null, $old_filename = null, $new_filename = null) {
        global $wpdb;

        // Use the new targeted replacement engine if available and we have the required info
        if (class_exists('MSH_Targeted_Replacement_Engine') && $attachment_id && $old_filename && $new_filename) {
            error_log('MSH Safe Rename: Using enhanced targeted replacement engine for attachment ' . $attachment_id);

            $replacement_engine = MSH_Targeted_Replacement_Engine::get_instance();
            $result = $replacement_engine->replace_attachment_urls($attachment_id, $old_filename, $new_filename, $this->test_mode);

            if (is_wp_error($result)) {
                error_log('MSH Safe Rename: Targeted replacement failed: ' . $result->get_error_message());
                return 0;
            } else {
                error_log('MSH Safe Rename: Targeted replacement successful. Updated: ' . $result['updated_count']);
                return $result['updated_count'];
            }
        }

        // If targeted replacement not available, skip database updates for safety
        error_log('MSH Safe Rename: Targeted replacement engine not available - skipping URL replacement for safety');
        return 0;

        foreach ($map as $old => $new) {
            if ($old === $new) {
                continue;
            }

            $like = '%' . $wpdb->esc_like($old) . '%';

            $fields = ['post_content', 'post_excerpt'];
            foreach ($fields as $field) {
                $updated = $wpdb->query($wpdb->prepare(
                    "UPDATE {$wpdb->posts} SET {$field} = REPLACE({$field}, %s, %s) WHERE {$field} LIKE %s",
                    $old,
                    $new,
                    $like
                ));
                if ($updated !== false) {
                    $total_updates += $updated;
                }
            }
        }

        $this->replace_in_serialized_table($wpdb->postmeta, 'meta_id', 'meta_value', $map);
        $this->replace_in_serialized_table($wpdb->options, 'option_id', 'option_value', $map);
        if (isset($wpdb->termmeta)) {
            $this->replace_in_serialized_table($wpdb->termmeta, 'meta_id', 'meta_value', $map);
        }
        $this->replace_in_serialized_table($wpdb->usermeta, 'umeta_id', 'meta_value', $map);

        return $total_updates;
    }

    private function replace_in_serialized_table($table, $id_column, $value_column, $map) {
        global $wpdb;

        foreach ($map as $old => $new) {
            if ($old === $new) {
                continue;
            }

            $like = '%' . $wpdb->esc_like($old) . '%';
            $rows = $wpdb->get_results($wpdb->prepare(
                "SELECT {$id_column} AS id, {$value_column} AS value FROM {$table} WHERE {$value_column} LIKE %s",
                $like
            ));

            foreach ($rows as $row) {
                $value = maybe_unserialize($row->value);
                $updated = $this->recursive_replace_map($value, $map);

                if ($updated !== $value) {
                    $wpdb->update(
                        $table,
                        [$value_column => maybe_serialize($updated)],
                        [$id_column => $row->id],
                        ['%s'],
                        ['%d']
                    );
                }
            }
        }
    }

    private function recursive_replace_map($data, $map) {
        if (is_string($data)) {
            return strtr($data, $map);
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->recursive_replace_map($value, $map);
            }
        }

        if (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = $this->recursive_replace_map($value, $map);
            }
        }

        return $data;
    }

    public function handle_old_urls() {
        if (!is_404()) {
            return;
        }

        if (!isset($_SERVER['REQUEST_URI'])) {
            return;
        }

        global $wpdb;
        $requested_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (!$requested_uri) {
            return;
        }

        $upload_dir = wp_upload_dir();
        $relative = ltrim(str_replace(trailingslashit(parse_url(home_url(), PHP_URL_PATH)), '', $requested_uri), '/');

        $row = $wpdb->get_row($wpdb->prepare(
            "SELECT new_url FROM {$this->log_table} WHERE (old_url LIKE %s OR old_relative LIKE %s) AND status = 'complete' AND renamed_date > DATE_SUB(NOW(), INTERVAL 30 DAY) ORDER BY renamed_date DESC LIMIT 1",
            '%' . $wpdb->esc_like($relative),
            '%' . $wpdb->esc_like($relative)
        ));

        if ($row && !empty($row->new_url)) {
            wp_redirect($row->new_url, 301);
            exit;
        }
    }

    public function cleanup_backup($backup_path) {
        $real = realpath($backup_path);
        if (!$real) {
            return;
        }

        $upload_dir = wp_upload_dir();
        $base = realpath($upload_dir['basedir']);
        if (!$base || strpos($real, $base) !== 0) {
            return;
        }

        if (file_exists($real)) {
            @unlink($real);
        }

        $dir = dirname($real);
        if (is_dir($dir) && count(glob($dir . '/*')) === 0) {
            @rmdir($dir);
        }
    }
}

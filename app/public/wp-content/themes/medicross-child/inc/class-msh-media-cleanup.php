<?php
/**
 * MSH Media Cleanup Tool
 * Organizes and cleans up duplicate/unused images in media library
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Media_Cleanup {
    
    /**
     * @var MSH_Hash_Cache_Manager|null
     */
    private $hash_manager;
    
    public function __construct() {
        add_action('wp_ajax_msh_analyze_duplicates', array($this, 'ajax_analyze_duplicates'));
        add_action('wp_ajax_msh_cleanup_media', array($this, 'ajax_cleanup_media'));
        add_action('wp_ajax_msh_test_cleanup', array($this, 'ajax_test_cleanup'));
        add_action('wp_ajax_msh_scan_full_library', array($this, 'ajax_scan_full_library'));
        add_action('wp_ajax_msh_quick_duplicate_scan', array($this, 'ajax_quick_duplicate_scan'));
        add_action('wp_ajax_msh_deep_library_scan', array($this, 'ajax_deep_library_scan'));
        add_action('wp_ajax_msh_check_duplicate_usage', array($this, 'ajax_check_duplicate_usage'));

        if (!class_exists('MSH_Hash_Cache_Manager')) {
            $hash_manager_path = __DIR__ . '/class-msh-hash-cache-manager.php';
            if (file_exists($hash_manager_path)) {
                require_once $hash_manager_path;
            }
        }

        if (class_exists('MSH_Hash_Cache_Manager')) {
            $this->hash_manager = new MSH_Hash_Cache_Manager();
        }
    }

    /**
     * Retrieve the shared hash manager instance.
     *
     * @return MSH_Hash_Cache_Manager|null
     */
    private function get_hash_manager() {
        if (!$this->hash_manager && class_exists('MSH_Hash_Cache_Manager')) {
            $this->hash_manager = new MSH_Hash_Cache_Manager();
        }

        return $this->hash_manager;
    }

    /**
     * Test AJAX handler to verify the class is working
     */
    public function ajax_test_cleanup() {
        check_ajax_referer('msh_media_cleanup', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        wp_send_json_success(['message' => 'Media cleanup class is working!', 'timestamp' => current_time('mysql')]);
    }
    
    /**
     * Find duplicate images and group them (optimized for large libraries)
     */
    public function find_duplicate_groups($limit = 100) {
        global $wpdb;
        
        // Simple, fast query - just get recent images first
        $images = $wpdb->get_results($wpdb->prepare("
            SELECT 
                p.ID,
                p.post_title,
                p.post_date,
                pm.meta_value as file_path,
                p.post_mime_type
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_wp_attached_file'
            WHERE p.post_type = 'attachment'
            AND p.post_mime_type LIKE 'image/%%'
            AND p.post_mime_type != 'image/svg+xml'
            AND pm.meta_value IS NOT NULL
            AND pm.meta_value != ''
            ORDER BY p.post_date DESC
            LIMIT %d
        ", $limit), ARRAY_A);
        
        $groups = [];
        $base_names = [];
        
        foreach ($images as $image) {
            // Extract base name (without size suffixes)
            $file_path = $image['file_path'];
            if (empty($file_path)) continue;
            
            $base_name = $this->get_base_filename($file_path);
            
            if (!isset($base_names[$base_name])) {
                $base_names[$base_name] = [];
            }
            
            $base_names[$base_name][] = $image;
        }
        
        // Filter to only groups with multiple images
        foreach ($base_names as $base_name => $group) {
            if (count($group) > 1) {
                $groups[$base_name] = $this->analyze_group($group);
            }
        }
        
        return $groups;
    }
    
    /**
     * Retrieve scan progress transient key for the current user.
     *
     * @return string
     */
    private function get_progress_transient_key() {
        $user_id = get_current_user_id();
        $suffix = $user_id ? $user_id : 'guest';
        return 'msh_scan_progress_' . $suffix;
    }

    /**
     * Get the list of images to scan for content duplicates.
     *
     * @param int|null $limit Optional limit for query size.
     * @return array
     */
    private function get_images_for_scanning($limit = null) {
        global $wpdb;

        $sql = "
            SELECT
                p.ID,
                p.post_title,
                p.post_date,
                p.post_status,
                pm.meta_value AS file_path
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_wp_attached_file'
            WHERE p.post_type = 'attachment'
                AND p.post_mime_type LIKE 'image/%'
                AND pm.meta_value IS NOT NULL
                AND pm.meta_value <> ''
            ORDER BY p.ID ASC
        ";

        if (null !== $limit && is_numeric($limit)) {
            $sql .= ' LIMIT ' . intval($limit);
        }

        return $wpdb->get_results($sql);
    }

    /**
     * Run a content-based duplicate scan with transient progress updates.
     *
     * @param int|null $limit Optional limit for how many images to process.
     * @return array
     */
    public function find_content_duplicates($limit = null) {
        $hash_manager = $this->get_hash_manager();

        if (!$hash_manager) {
            return [
                'groups' => [],
                'total_groups' => 0,
                'total_duplicates' => 0,
                'hash_map' => [],
                'error' => 'Hash manager unavailable',
            ];
        }

        $images = $this->get_images_for_scanning($limit);
        if (empty($images)) {
            return [
                'groups' => [],
                'total_groups' => 0,
                'total_duplicates' => 0,
                'hash_map' => [],
                'error' => 'No images found',
            ];
        }

        $progress_key = $this->get_progress_transient_key();
        delete_transient($progress_key);

        $total = count($images);
        $processed = 0;
        $hash_map = [];

        set_transient($progress_key, [
            'status' => 'processing',
            'current' => 0,
            'total' => $total,
            'message' => __('Starting content scan...', 'medicross-child'),
        ], 300);

        foreach ($images as $image) {
            $processed++;

            $hash = $hash_manager->get_file_hash($image->ID);
            if (!$hash) {
                continue;
            }

            if (!isset($hash_map[$hash])) {
                $hash_map[$hash] = [];
            }

            $thumb_url = wp_get_attachment_thumb_url($image->ID);
            $hash_map[$hash][] = [
                'id' => $image->ID,
                'title' => $image->post_title,
                'date' => $image->post_date,
                'status' => $image->post_status,
                'file' => $image->file_path,
                'thumb_url' => $thumb_url ? $thumb_url : '',
                'full_url' => wp_get_attachment_url($image->ID),
            ];

            if ($processed % 10 === 0 || $processed === $total) {
                set_transient($progress_key, [
                    'status' => 'processing',
                    'current' => $processed,
                    'total' => $total,
                    'message' => sprintf(__('Processing image %1$d of %2$d...', 'medicross-child'), $processed, $total),
                ], 300);
            }
        }

        $duplicates = $this->process_hash_map($hash_map);

        delete_transient($progress_key);

        return $duplicates;
    }

    /**
     * Convert a hash map into duplicate group metadata.
     *
     * @param array $hash_map
     * @return array
     */
    private function process_hash_map(array $hash_map) {
        $groups = [];
        $total_duplicates = 0;

        foreach ($hash_map as $hash => $items) {
            if (count($items) < 2) {
                continue;
            }

            $groups[] = [
                'hash' => $hash,
                'count' => count($items),
                'images' => $items,
            ];

            $total_duplicates += count($items) - 1;
        }

        return [
            'groups' => $groups,
            'total_groups' => count($groups),
            'total_duplicates' => $total_duplicates,
            'hash_map' => $hash_map,
        ];
    }

    /**
     * Get base filename without size suffixes - ENHANCED VERSION
     */
    private function get_base_filename($file_path) {
        $filename = basename($file_path);

        // Remove extension
        $name = pathinfo($filename, PATHINFO_FILENAME);

        // Enhanced pattern removal for better duplicate detection

        // WordPress 5.3+ scaled images
        $name = preg_replace('/-scaled$/', '', $name);

        // Size suffixes like -150x150, -300x200, etc.
        $name = preg_replace('/-\d+x\d+$/', '', $name);

        // Retina images @2x, @3x
        $name = preg_replace('/@\d+x$/', '', $name);

        // Timestamp suffixes (e1234567890)
        $name = preg_replace('/-e\d{10}$/', '', $name);

        // Windows-style duplicates (1), (2), (copy)
        $name = preg_replace('/\(\d+\)$/', '', $name);
        $name = preg_replace('/\(copy\)$/i', '', $name);

        // Common thumbnail patterns
        $name = preg_replace('/(_thumb|_thumbnail|_tn)$/', '', $name);

        // Version numbers v1, v2, final, new
        $name = preg_replace('/([-_]v\d+|[-_]final|[-_]new)$/i', '', $name);

        // Copy suffixes
        $name = preg_replace('/([-_]copy\d*|[-_]duplicate)$/i', '', $name);

        // Numbered suffixes like -1, -2, etc. (keep this last)
        $name = preg_replace('/-\d+$/', '', $name);

        // Clean up any remaining separators
        $name = trim($name, '-_');

        return $name;
    }
    
    /**
     * Analyze a group of duplicate images (lightweight version)
     */
    private function analyze_group($group) {
        $keep_candidate = null;
        $keep_score = 0;
        $published_count = 0;
        
        foreach ($group as &$image) {
            // Quick usage check - just check if it's used somewhere
            $image['is_published'] = $this->quick_usage_check($image['ID']);
            if ($image['is_published']) $published_count++;
            
            // Calculate "keep" score (ENHANCED FOR HEALTHCARE)
            $score = 0;

            // Core safety factors
            if ($image['is_published']) $score += 15;  // Increased weight for published

            // File quality indicators
            if (strpos($image['file_path'], '-scaled') === false) $score += 5;
            if (!preg_match('/-\d+x\d+/', $image['file_path'])) $score += 8;

            // Healthcare-specific naming (Hamilton clinic)
            $filename = strtolower($image['file_path']);
            if (strpos($filename, 'hamilton') !== false) $score += 8;
            if (strpos($filename, 'msh') !== false) $score += 6;
            if (strpos($filename, 'main-street') !== false) $score += 6;

            // Medical/professional naming
            if (preg_match('/(therapy|treatment|rehab|chiro|physio|medical|health|care)/', $filename)) $score += 4;

            // Negative factors (avoid keeping these)
            if (strpos($image['post_title'], 'copy') !== false) $score -= 5;
            if (strpos($filename, 'temp') !== false) $score -= 8;
            if (strpos($filename, 'test') !== false) $score -= 3;
            if (strpos($filename, 'old') !== false) $score -= 3;
            if (preg_match('/untitled|image\d+|img_\d+/', $filename)) $score -= 2;

            // Format preferences
            if (strpos($image['file_path'], '.webp') !== false) $score += 10;  // Prefer WebP
            if (strpos($image['file_path'], '.svg') !== false) $score += 7;   // SVG for icons
            if (strpos($image['file_path'], '.jpg') !== false) $score += 3;   // JPG standard
            
            $image['keep_score'] = $score;
            $image['usage'] = []; // Populate later if needed
            
            if ($score > $keep_score) {
                $keep_score = $score;
                $keep_candidate = $image;
            }
        }
        
        return [
            'images' => $group,
            'recommended_keep' => $keep_candidate,
            'total_count' => count($group),
            'published_count' => $published_count,
            'sizes_available' => ['Multiple sizes'], // Simplified
            'cleanup_potential' => count($group) - 1
        ];
    }
    
    /**
     * Improved usage check - check both featured images and content usage
     */
    private function quick_usage_check($attachment_id) {
        global $wpdb;
        
        // Check featured images
        $featured = $wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) 
            FROM {$wpdb->postmeta} meta
            JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id
            WHERE meta.meta_key = '_thumbnail_id' 
            AND meta.meta_value = %d
            AND posts.post_status = 'publish'
        ", $attachment_id));
        
        if ($featured > 0) return true;
        
        // Check content usage (quick check)
        $file_path = get_post_meta($attachment_id, '_wp_attached_file', true);
        if ($file_path) {
            $filename = basename($file_path);
            $content_usage = $wpdb->get_var($wpdb->prepare("
                SELECT COUNT(*) 
                FROM {$wpdb->posts} 
                WHERE post_content LIKE %s 
                AND post_status = 'publish'
                LIMIT 1
            ", '%' . $filename . '%'));
            
            if ($content_usage > 0) return true;
        }
        
        return false;
    }
    
    /**
     * Check where an image is being used
     */
    private function check_image_usage($attachment_id) {
        global $wpdb;
        
        $usage = [];
        
        // Check featured images
        $featured = $wpdb->get_results($wpdb->prepare("
            SELECT posts.post_title, posts.post_type, posts.post_status
            FROM {$wpdb->postmeta} meta 
            JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id 
            WHERE meta.meta_key = '_thumbnail_id' 
            AND meta.meta_value = %d
        ", $attachment_id));
        
        foreach ($featured as $post) {
            $usage[] = [
                'type' => 'featured_image',
                'title' => $post->post_title,
                'post_type' => $post->post_type,
                'status' => $post->post_status
            ];
        }
        
        // Check content usage
        $file_path = get_post_meta($attachment_id, '_wp_attached_file', true);
        if ($file_path) {
            $filename = basename($file_path);
            $content_posts = $wpdb->get_results($wpdb->prepare("
                SELECT post_title, post_type, post_status 
                FROM {$wpdb->posts} 
                WHERE post_content LIKE %s
            ", '%' . $filename . '%'));
            
            foreach ($content_posts as $post) {
                $usage[] = [
                    'type' => 'content',
                    'title' => $post->post_title,
                    'post_type' => $post->post_type,
                    'status' => $post->post_status
                ];
            }
        }
        
        return $usage;
    }
    
    /**
     * AJAX: Analyze duplicates
     */
    public function ajax_analyze_duplicates() {
        try {
            check_ajax_referer('msh_media_cleanup', 'nonce');
            
            if (!current_user_can('manage_options')) {
                wp_die('Unauthorized');
            }
            
            // Add some debug info
            error_log('MSH: Starting duplicate analysis...');
            
            // Start with very small batch for reliability
            $limit = 50; // Process first 50 images only
            $duplicate_groups = $this->find_duplicate_groups($limit);
            
            error_log('MSH: Found ' . count($duplicate_groups) . ' duplicate groups from ' . $limit . ' images');
            
            $summary = [
                'total_groups' => count($duplicate_groups),
                'total_duplicates' => array_sum(array_map(function($group) {
                    return $group['cleanup_potential'];
                }, $duplicate_groups)),
                'groups' => $duplicate_groups,
                'debug_info' => [
                    'memory_usage' => memory_get_usage(true),
                    'time' => current_time('mysql')
                ]
            ];
            
            wp_send_json_success($summary);
            
        } catch (Exception $e) {
            error_log('MSH Cleanup Error: ' . $e->getMessage());
            wp_send_json_error([
                'message' => 'Analysis failed: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
    
    /**
     * AJAX: Progressive full library scan
     */
    public function ajax_scan_full_library() {
        try {
            // Add error reporting for debugging
            error_reporting(E_ALL);
            ini_set('display_errors', 0); // Don't display errors in response
            
            check_ajax_referer('msh_media_cleanup', 'nonce');
            
            if (!current_user_can('manage_options')) {
                wp_send_json_error(['message' => 'Unauthorized access']);
                return;
            }
            
            $offset = intval($_POST['offset'] ?? 0);
            $batch_size = 10; // Process only 10 images per batch to avoid timeouts
            
            // Clear transient data on first batch (offset 0)
            if ($offset === 0) {
                delete_transient('msh_deep_scan_data');
            }
            
            error_log("MSH: Full scan batch starting at offset {$offset}");
            
            // Use simple, fast queries
            global $wpdb;
            
            // Simple count query - include all images including SVG
            $total_images = $wpdb->get_var("
                SELECT COUNT(*) 
                FROM {$wpdb->posts} 
                WHERE post_type = 'attachment'
                AND post_mime_type LIKE 'image/%'
            ");
            
            if (!$total_images) {
                wp_send_json_error(['message' => 'No images found in database']);
                return;
            }
            
            // Get this batch of images with simple query - include all images
            $images = $wpdb->get_results($wpdb->prepare("
                SELECT 
                    ID,
                    post_title,
                    post_date,
                    post_mime_type
                FROM {$wpdb->posts}
                WHERE post_type = 'attachment'
                AND post_mime_type LIKE 'image/%%'
                ORDER BY post_date DESC
                LIMIT %d OFFSET %d
            ", $batch_size, $offset), ARRAY_A);
            
            if (empty($images)) {
                // No more images to process - get final results
                $stored_images = get_transient('msh_deep_scan_data') ?: [];
                $groups = $this->process_all_collected_images($stored_images);
                delete_transient('msh_deep_scan_data');
                
                wp_send_json_success([
                    'completed' => true,
                    'total_processed' => $offset,
                    'message' => 'Deep library scan completed',
                    'groups' => $groups,
                    'total_groups' => count($groups),
                    'total_duplicates' => array_sum(array_column($groups, 'cleanup_potential'))
                ]);
                return;
            }
            
            // For deep scan, collect ALL file paths first, then group them at the end
            // This is more memory intensive but ensures proper cross-batch grouping
            
            // Store batch data in transient for aggregation across batches
            $transient_key = 'msh_deep_scan_data';
            $stored_images = get_transient($transient_key) ?: [];
            
            // Add current batch images to stored data
            foreach ($images as $image) {
                $file_path = get_post_meta($image['ID'], '_wp_attached_file', true);
                if (!empty($file_path)) {
                    $image['file_path'] = $file_path;
                    $stored_images[] = $image;
                }
            }
            
            // Store updated data
            set_transient($transient_key, $stored_images, HOUR_IN_SECONDS);
            
            // Check if this is the last batch
            $processed = $offset + count($images);
            if ($processed >= $total_images) {
                // Final processing - increase timeout and process collected images
                @set_time_limit(60); // 60 seconds for final processing
                @ini_set('memory_limit', '256M'); // Increase memory limit
                
                error_log("MSH: Starting final processing of " . count($stored_images) . " collected images");
                
                try {
                    $groups = $this->process_all_collected_images($stored_images);
                    delete_transient($transient_key); // Clean up
                    
                    error_log("MSH: Final processing complete - found " . count($groups) . " duplicate groups");
                    
                    // Return completion with final results
                    wp_send_json_success([
                        'completed' => true,
                        'groups' => $groups,
                        'total_images' => $total_images,
                        'processed' => $processed,
                        'progress_percent' => 100,
                        'total_groups' => count($groups),
                        'total_duplicates' => array_sum(array_column($groups, 'cleanup_potential')),
                        'debug_info' => [
                            'final_processing' => true,
                            'total_images_analyzed' => count($stored_images),
                            'memory_peak' => memory_get_peak_usage(true) / 1024 / 1024 . 'MB'
                        ]
                    ]);
                    return;
                } catch (Exception $e) {
                    error_log("MSH: Final processing error: " . $e->getMessage());
                    delete_transient($transient_key);
                    wp_send_json_error(['message' => 'Final processing failed: ' . $e->getMessage()]);
                    return;
                }
            } else {
                // Intermediate batch - just return progress
                $groups = [];
            }
            
            $progress_percent = round(($processed / $total_images) * 100, 1);
            
            wp_send_json_success([
                'completed' => false,
                'groups' => $groups,
                'total_images' => $total_images,
                'processed' => $processed,
                'progress_percent' => $progress_percent,
                'duplicates_found' => array_sum(array_map(function($group) {
                    return $group['cleanup_potential'];
                }, $groups)),
                'next_offset' => $processed,
                'debug_info' => [
                    'batch_image_count' => count($images),
                    'groups_found' => count($groups)
                ]
            ]);
            
        } catch (Exception $e) {
            error_log('MSH Full Scan Error: ' . $e->getMessage());
            wp_send_json_error([
                'message' => 'Full scan failed: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * AJAX: Simplified Deep Library Scan - no batching, just get ALL images and process them
     */
    public function ajax_deep_library_scan() {
        try {
            check_ajax_referer('msh_media_cleanup', 'nonce');
            
            if (!current_user_can('manage_options')) {
                wp_send_json_error(['message' => 'Unauthorized access']);
                return;
            }
            
            @set_time_limit(120); // 2 minutes
            @ini_set('memory_limit', '512M'); // Increase memory
            
            global $wpdb;
            
            // Get ALL images at once - no batching
            $all_images = $wpdb->get_results("
                SELECT 
                    p.ID,
                    p.post_title,
                    p.post_date,
                    pm.meta_value as file_path
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_wp_attached_file'
                WHERE p.post_type = 'attachment'
                AND p.post_mime_type LIKE 'image/%'
                AND pm.meta_value IS NOT NULL
                AND pm.meta_value != ''
                ORDER BY p.post_date DESC
            ", ARRAY_A);
            
            if (empty($all_images)) {
                wp_send_json_error(['message' => 'No images found']);
                return;
            }
            
            // Process exactly like Quick scan but with all images
            $groups = [];
            foreach ($all_images as $file) {
                $base_name = $this->get_base_filename($file['file_path']);
                
                if (!isset($groups[$base_name])) {
                    $groups[$base_name] = [
                        'images' => [],
                        'total_count' => 0,
                        'cleanup_potential' => 0,
                        'published_count' => 0,
                        'sizes_available' => []
                    ];
                }
                
                // No usage check for speed - just mark as potential duplicate
                $file['is_published'] = false;
                $file['usage'] = [];
                $file['keep_score'] = 1;
                
                $groups[$base_name]['images'][] = $file;
                $groups[$base_name]['total_count']++;
                $groups[$base_name]['cleanup_potential']++; // All are potential cleanup candidates
            }
            
            // Filter to only groups with multiple images and set simple recommended keep
            $filtered_groups = [];
            foreach ($groups as $base_name => $group) {
                if ($group['total_count'] > 1) {
                    // Keep the first one found (simple logic for speed)
                    $group['recommended_keep'] = $group['images'][0];
                    $group['published_count'] = 0; // Skip expensive checks
                    $group['sizes_available'] = ['Multiple sizes'];
                    $group['cleanup_potential'] = $group['total_count'] - 1; // All but first
                    
                    $filtered_groups[$base_name] = $group;
                }
            }
            
            wp_send_json_success([
                'total_groups' => count($filtered_groups),
                'total_duplicates' => array_sum(array_column($filtered_groups, 'cleanup_potential')),
                'groups' => $filtered_groups,
                'debug_info' => [
                    'total_scanned' => count($all_images),
                    'all_groups_found' => count($groups),
                    'memory_usage' => memory_get_usage(),
                    'scan_type' => 'deep_simplified'
                ]
            ]);
            
        } catch (Exception $e) {
            error_log('MSH Deep Scan Error: ' . $e->getMessage());
            wp_send_json_error([
                'message' => 'Deep scan failed: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * AJAX: Check usage status for duplicate images
     */
    public function ajax_check_duplicate_usage() {
        try {
            check_ajax_referer('msh_media_cleanup', 'nonce');
            
            if (!current_user_can('manage_options')) {
                wp_send_json_error(['message' => 'Unauthorized access']);
                return;
            }
            
            $image_ids = $_POST['image_ids'] ?? [];
            if (empty($image_ids) || !is_array($image_ids)) {
                wp_send_json_error(['message' => 'No image IDs provided']);
                return;
            }
            
            @set_time_limit(90); // 90 seconds for usage checking
            $start_time = microtime(true);
            
            global $wpdb;
            
            $usage_details = [];
            $used_count = 0;
            $safe_to_delete = 0;
            
            foreach ($image_ids as $image_id) {
                $image_id = intval($image_id);
                if ($image_id <= 0) continue;
                
                $usage_array = $this->check_image_usage($image_id);
                
                // Convert existing format to new format
                $is_used = false;
                $formatted_usage = [];
                
                foreach ($usage_array as $usage_item) {
                    if ($usage_item['status'] === 'publish') {
                        $is_used = true;
                        $formatted_usage[] = [
                            'type' => $usage_item['type'] === 'featured_image' ? 'Featured Image' : 'Post Content',
                            'title' => $usage_item['title'],
                            'post_type' => $usage_item['post_type']
                        ];
                    }
                }
                
                $usage_details[$image_id] = [
                    'is_used' => $is_used,
                    'usage_details' => $formatted_usage,
                    'usage_count' => count($formatted_usage)
                ];
                
                if ($is_used) {
                    $used_count++;
                } else {
                    $safe_to_delete++;
                }
            }
            
            $end_time = microtime(true);
            $time_taken = round(($end_time - $start_time) * 1000); // Convert to milliseconds
            
            wp_send_json_success([
                'usage_details' => $usage_details,
                'used_count' => $used_count,
                'safe_to_delete' => $safe_to_delete,
                'debug_info' => [
                    'total_checked' => count($image_ids),
                    'time_taken' => $time_taken
                ]
            ]);
            
        } catch (Exception $e) {
            error_log('MSH Usage Check Error: ' . $e->getMessage());
            wp_send_json_error([
                'message' => 'Usage check failed: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Process all collected images for deep duplicate analysis (optimized for memory)
     */
    private function process_all_collected_images($all_images) {
        $base_names = [];
        
        // Group all images by base filename - process in chunks to save memory
        $chunk_size = 100;
        $image_chunks = array_chunk($all_images, $chunk_size);
        
        foreach ($image_chunks as $chunk) {
            foreach ($chunk as $image) {
                $base_name = $this->get_base_filename($image['file_path']);
                
                if (!isset($base_names[$base_name])) {
                    $base_names[$base_name] = [];
                }
                
                // Only store essential data to save memory
                $base_names[$base_name][] = [
                    'ID' => $image['ID'],
                    'post_title' => $image['post_title'],
                    'file_path' => $image['file_path'],
                    'post_date' => $image['post_date'] ?? ''
                ];
            }
            
            // Clear chunk from memory
            unset($chunk);
        }
        
        // Only keep groups with multiple images and analyze them (simplified analysis)
        $groups = [];
        foreach ($base_names as $base_name => $group) {
            if (count($group) > 1) {
                // Simplified group analysis for performance
                $groups[$base_name] = [
                    'images' => $group,
                    'total_count' => count($group),
                    'cleanup_potential' => count($group) - 1, // Keep first, delete rest
                    'published_count' => 0, // Skip expensive checks
                    'sizes_available' => ['Multiple variations'],
                    'recommended_keep' => $group[0]
                ];
            }
        }
        
        return $groups;
    }
    
    /**
     * AJAX: Quick duplicate scan - finds obvious duplicates fast
     */
    public function ajax_quick_duplicate_scan() {
        try {
            check_ajax_referer('msh_media_cleanup', 'nonce');

            if (!current_user_can('manage_options')) {
                wp_send_json_error(['message' => 'Unauthorized access']);
                return;
            }

            global $wpdb;

            error_log('MSH DUPLICATE: Starting Quick Duplicate Scan - content-based detection (post-optimization compatible)');

            // IMPROVED: Content-based duplicate detection that works even after Step 1 filename optimization
            // Instead of relying on filename patterns, group by base names and file sizes
            $all_images = $wpdb->get_results("
                SELECT
                    p.ID,
                    p.post_title,
                    pm.meta_value as file_path,
                    pm_size.meta_value as file_metadata
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = '_wp_attached_file'
                LEFT JOIN {$wpdb->postmeta} pm_size ON p.ID = pm_size.post_id AND pm_size.meta_key = '_wp_attachment_metadata'
                WHERE p.post_type = 'attachment'
                AND p.post_mime_type LIKE 'image/%'
                AND pm.meta_value != ''
                AND pm.meta_value IS NOT NULL
                ORDER BY p.post_date DESC
                LIMIT 250
            ", ARRAY_A);

            error_log('MSH DUPLICATE: Loaded ' . count($all_images) . ' images for content-based analysis');

            if (empty($all_images)) {
                wp_send_json_success([
                    'total_groups' => 0,
                    'total_duplicates' => 0,
                    'groups' => [],
                    'debug_info' => [
                        'message' => 'No images found in media library'
                    ]
                ]);
                return;
            }

            // Group images by base filename + file size (content-based approach)
            $groups = [];
            $processed = 0;
            $upload_dir = wp_upload_dir();

            foreach ($all_images as $image) {
                if (empty($image['file_path'])) {
                    continue;
                }

                $processed++;

                // Get base filename (removes WordPress size suffixes)
                $base_name = $this->get_base_filename($image['file_path']);

                if (empty($base_name)) {
                    error_log('MSH DUPLICATE: Could not extract base name from: ' . $image['file_path']);
                    continue;
                }

                // Extract file size from metadata or filesystem
                $file_size = null;
                if (!empty($image['file_metadata'])) {
                    $metadata = maybe_unserialize($image['file_metadata']);
                    if (isset($metadata['filesize'])) {
                        $file_size = $metadata['filesize'];
                    } elseif (isset($metadata['width']) && isset($metadata['height'])) {
                        // Rough size estimate from dimensions
                        $file_size = intval($metadata['width'] * $metadata['height'] * 0.3); // Approximate file size
                    }
                }

                // Fallback: get actual file size
                if (!$file_size) {
                    $full_path = $upload_dir['basedir'] . '/' . $image['file_path'];
                    if (file_exists($full_path)) {
                        $file_size = filesize($full_path);
                    }
                }

                // Group by base_name + size_bucket (handles truly identical content)
                $size_bucket = $file_size ? intval($file_size / 5000) * 5000 : 'unknown'; // 5KB buckets
                $group_key = $base_name . '_sz_' . $size_bucket;

                if (!isset($groups[$group_key])) {
                    $groups[$group_key] = [];
                }

                $groups[$group_key][] = [
                    'ID' => $image['ID'],
                    'post_title' => $image['post_title'],
                    'file_path' => $image['file_path'],
                    'file_size' => $file_size,
                    'base_name' => $base_name,
                    'is_published' => false, // Quick scan - skip usage check for speed
                    'usage' => [],
                    'keep_score' => 1
                ];
            }

            error_log('MSH DUPLICATE: Processed ' . $processed . ' images into ' . count($groups) . ' content groups');

            // Find groups with multiple files (potential duplicates)
            $duplicate_groups = [];
            $total_duplicates = 0;

            foreach ($groups as $group_key => $images) {
                if (count($images) > 1) {
                    // Analyze this duplicate group
                    $analyzed_group = $this->analyze_group($images);
                    $analyzed_group['group_key'] = $group_key;
                    $duplicate_groups[] = $analyzed_group;
                    $total_duplicates += $analyzed_group['cleanup_potential'];
                }
            }

            error_log('MSH DUPLICATE: Found ' . count($duplicate_groups) . ' duplicate groups with ' . $total_duplicates . ' files for potential cleanup');

            // Success response
            wp_send_json_success([
                'total_groups' => count($duplicate_groups),
                'total_duplicates' => $total_duplicates,
                'groups' => $duplicate_groups,
                'debug_info' => [
                    'approach' => 'content-based detection (base filename + file size)',
                    'images_analyzed' => $processed,
                    'content_groups_created' => count($groups),
                    'duplicate_groups_found' => count($duplicate_groups),
                    'sample_groups' => array_slice(array_keys($groups), 0, 3),
                    'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . 'MB',
                    'post_optimization_compatible' => true
                ]
            ]);

        } catch (Exception $e) {
            error_log('MSH DUPLICATE ERROR: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            wp_send_json_error([
                'message' => 'Quick scan failed: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }
    
    /**
     * AJAX: Cleanup media (remove selected duplicates)
     */
    public function ajax_cleanup_media() {
        check_ajax_referer('msh_media_cleanup', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $action_type = $_POST['action_type'] ?? 'safe';
        $image_ids = $_POST['image_ids'] ?? [];
        
        $results = [];
        $deleted_count = 0;
        
        foreach ($image_ids as $attachment_id) {
            $attachment_id = intval($attachment_id);
            
            // Safety check - don't delete if used in published content
            if ($action_type === 'safe') {
                $usage = $this->check_image_usage($attachment_id);
                $published_usage = array_filter($usage, function($use) {
                    return $use['status'] === 'publish';
                });
                
                if (!empty($published_usage)) {
                    $results[] = [
                        'id' => $attachment_id,
                        'status' => 'skipped',
                        'reason' => 'Used in published content'
                    ];
                    continue;
                }
            }
            
            // Delete the attachment
            $deleted = wp_delete_attachment($attachment_id, true);
            
            if ($deleted) {
                $deleted_count++;
                $results[] = [
                    'id' => $attachment_id,
                    'status' => 'deleted',
                    'reason' => 'Successfully removed'
                ];
            } else {
                $results[] = [
                    'id' => $attachment_id,
                    'status' => 'error',
                    'reason' => 'Failed to delete'
                ];
            }
        }
        
        wp_send_json_success([
            'deleted_count' => $deleted_count,
            'results' => $results
        ]);
    }
}

// Initialize media cleanup
new MSH_Media_Cleanup();
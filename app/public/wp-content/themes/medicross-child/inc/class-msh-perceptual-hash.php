<?php
/**
 * MSH Perceptual Hash Manager
 *
 * Provides lightweight dHash generation for visual duplicate detection.
 * Handles caching, batching, and similarity comparisons with tiered thresholds.
 *
 * @package MSH_Media_Optimization
 * @since 1.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class MSH_Perceptual_Hash {

    const META_HASH = '_msh_perceptual_hash';
    const META_TIME = '_msh_phash_time';
    const META_MODIFIED = '_msh_phash_file_modified';
    const META_STATUS = '_msh_phash_status';

    const STATUS_OK = 'ok';
    const STATUS_SKIPPED_SVG = 'skipped_svg';
    const STATUS_UNSUPPORTED = 'unsupported';
    const STATUS_ERROR = 'error';

    const DEFAULT_BATCH_SIZE = 100;

    /**
     * Default similarity thresholds in bits (Hamming distance).
     *
     * @var array
     */
    private $thresholds = [
        'definite' => 5,
        'likely'   => 10,
        'possible' => 15,
    ];

    /**
     * Cached bit counts for fast popcount.
     *
     * @var int[]
     */
    private static $bitCountMap = [];

    /**
     * Singleton instance.
     *
     * @var self|null
     */
    private static $instance = null;

    /**
     * Get singleton instance.
     *
     * @return self
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        if (empty(self::$bitCountMap)) {
            $this->bootstrap_bitcount_map();
        }
    }

    /**
     * Generate or fetch perceptual hash for attachment.
     *
     * @param int  $attachment_id Attachment ID.
     * @param bool $force         Force regeneration even if cache looks fresh.
     *
     * @return string|WP_Error 16-character hex hash or error on failure.
     */
    public function generate_hash($attachment_id, $force = false) {
        $attachment_id = absint($attachment_id);

        if (!$attachment_id) {
            return new WP_Error('msh_phash_invalid_attachment', 'Invalid attachment ID provided for perceptual hash.');
        }

        $file = get_attached_file($attachment_id);

        if (!$file || !file_exists($file)) {
            return new WP_Error('msh_phash_missing_file', 'Attachment file missing; unable to generate perceptual hash.');
        }

        $mime_type = get_post_mime_type($attachment_id);
        if (!$mime_type) {
            $file_info = wp_check_filetype_and_ext($file, basename($file));
            $mime_type = $file_info['type'] ?? '';
        }

        if ('image/svg+xml' === $mime_type) {
            $this->persist_skip_state($attachment_id, self::STATUS_SKIPPED_SVG);
            return new WP_Error('msh_phash_skipped_svg', 'SVG files are skipped; raster comparison not supported.');
        }

        if (!extension_loaded('gd')) {
            return new WP_Error('msh_phash_missing_gd', 'GD extension not available; perceptual hashing requires GD.');
        }

        $cached_hash   = get_post_meta($attachment_id, self::META_HASH, true);
        $cached_mtime  = (int) get_post_meta($attachment_id, self::META_MODIFIED, true);
        $cached_status = get_post_meta($attachment_id, self::META_STATUS, true);

        if (!$force) {
            if (self::STATUS_SKIPPED_SVG === $cached_status) {
                return new WP_Error('msh_phash_skipped_svg', 'SVG files are skipped; raster comparison not supported.');
            }

            if ($cached_hash) {
                $current_mtime = filemtime($file);

                if ($current_mtime && $cached_mtime && $current_mtime === (int) $cached_mtime) {
                    return $cached_hash;
                }
            }
        }

        $hash = $this->compute_hash_from_file($file);

        if (is_wp_error($hash)) {
            $this->persist_failure_state($attachment_id, $hash);
            return $hash;
        }

        $this->persist_hash($attachment_id, $hash, $file);

        return $hash;
    }

    /**
     * Ensure batch of attachments has cached hashes.
     *
     * @param int[] $attachment_ids Attachment IDs.
     * @param bool  $force          Force regeneration.
     *
     * @return array
     */
    public function ensure_hash_for_batch(array $attachment_ids, $force = false) {
        $summary = [
            'processed' => 0,
            'generated' => 0,
            'cached'    => 0,
            'skipped'   => [],
            'errors'    => [],
        ];

        if (empty($attachment_ids)) {
            return $summary;
        }

        foreach ($attachment_ids as $attachment_id) {
            $summary['processed']++;

            $result = $this->generate_hash($attachment_id, $force);

            if (is_wp_error($result)) {
                $code = $result->get_error_code();

                if ('msh_phash_skipped_svg' === $code) {
                    $summary['skipped'][] = [
                        'attachment_id' => $attachment_id,
                        'reason'        => self::STATUS_SKIPPED_SVG,
                    ];
                    continue;
                }

                $summary['errors'][] = [
                    'attachment_id' => $attachment_id,
                    'code'          => $code,
                    'message'       => $result->get_error_message(),
                ];
                continue;
            }

            if ($result) {
                $summary['generated']++;
            } else {
                $summary['cached']++;
            }
        }

        return $summary;
    }

    /**
     * Compare two perceptual hashes.
     *
     * @param string $hash_a First 64-bit hex hash.
     * @param string $hash_b Second 64-bit hex hash.
     *
     * @return array|WP_Error
     */
    public function compare_hashes($hash_a, $hash_b) {
        $hash_a = strtolower(trim((string) $hash_a));
        $hash_b = strtolower(trim((string) $hash_b));

        if (strlen($hash_a) !== 16 || strlen($hash_b) !== 16) {
            return new WP_Error('msh_phash_invalid_length', 'Perceptual hashes must be 16-character hex strings.');
        }

        $bin_a = hex2bin($hash_a);
        $bin_b = hex2bin($hash_b);

        if (false === $bin_a || false === $bin_b) {
            return new WP_Error('msh_phash_invalid_hex', 'Invalid perceptual hash values supplied.');
        }

        $distance = 0;
        $length   = strlen($bin_a);

        for ($i = 0; $i < $length; $i++) {
            $distance += $this->popcount(ord($bin_a[$i]) ^ ord($bin_b[$i]));
        }

        $similarity = max(0, 100 - (($distance / 64) * 100));

        return [
            'distance'   => $distance,
            'similarity' => round($similarity, 2),
            'tier'       => $this->classify_distance($distance),
        ];
    }

    /**
     * Group similar records using perceptual hashes.
     *
     * @param array $records  Array of attachment context rows containing ID, hash, mime, width, height.
     * @param array $options  Optional overrides (thresholds, distance_cap).
     *
     * @return array
     */
    public function group_similar(array $records, array $options = []) {
        $thresholds = $this->resolve_thresholds($options['thresholds'] ?? []);
        $distance_cap = isset($options['distance_cap']) ? (int) $options['distance_cap'] : $thresholds['possible'];
        if ($distance_cap < 1) {
            $distance_cap = $thresholds['possible'];
        }

        $record_index = [];
        $buckets      = [];

        foreach ($records as $record) {
            $attachment_id = isset($record['ID']) ? (int) $record['ID'] : 0;
            $hash_value    = isset($record['hash']) ? strtolower(trim((string) $record['hash'])) : '';

            if (!$attachment_id || !$hash_value || strlen($hash_value) !== 16) {
                continue;
            }

            $record_index[$attachment_id] = $record;
            $bucket_key = $this->derive_bucket_key($record);
            if (!isset($buckets[$bucket_key])) {
                $buckets[$bucket_key] = [];
            }
            $buckets[$bucket_key][] = $attachment_id;
        }

        if (empty($record_index)) {
            return [
                'groups'        => [],
                'pair_metrics'  => [],
                'thresholds'    => $thresholds,
                'bucket_count'  => 0,
                'record_count'  => 0,
            ];
        }

        $graph        = [];
        $pair_metrics = [];

        foreach ($buckets as $bucket_ids) {
            $bucket_total = count($bucket_ids);
            if ($bucket_total < 2) {
                continue;
            }

            for ($i = 0; $i < $bucket_total - 1; $i++) {
                $id_a = $bucket_ids[$i];
                $record_a = $record_index[$id_a];

                for ($j = $i + 1; $j < $bucket_total; $j++) {
                    $id_b = $bucket_ids[$j];
                    $record_b = $record_index[$id_b];

                    if (!$this->passes_dimension_gate($record_a, $record_b)) {
                        continue;
                    }

                    $comparison = $this->compare_hashes($record_a['hash'], $record_b['hash']);

                    if (is_wp_error($comparison)) {
                        continue;
                    }

                    $distance = (int) $comparison['distance'];

                    if ($distance > $distance_cap) {
                        continue;
                    }

                    if (!isset($graph[$id_a])) {
                        $graph[$id_a] = [];
                    }

                    if (!isset($graph[$id_b])) {
                        $graph[$id_b] = [];
                    }

                    $graph[$id_a][$id_b] = $distance;
                    $graph[$id_b][$id_a] = $distance;

                    $tier = $this->classify_distance($distance, $thresholds);
                    $key  = $this->pair_key($id_a, $id_b);

                    $pair_metrics[$key] = [
                        'source'     => $id_a,
                        'target'     => $id_b,
                        'distance'   => $distance,
                        'similarity' => $comparison['similarity'],
                        'tier'       => $tier,
                    ];
                }
            }
        }

        $groups = [];
        $visited = [];

        foreach (array_keys($graph) as $root_id) {
            if (isset($visited[$root_id])) {
                continue;
            }

            $component = $this->collect_component($root_id, $graph);

            foreach ($component as $node_id) {
                $visited[$node_id] = true;
            }

            if (count($component) < 2) {
                continue;
            }

            $component_pairs = $this->extract_component_pairs($component, $pair_metrics);

            if (empty($component_pairs)) {
                continue;
            }

            $distances = array_map(static function ($pair) {
                return (int) $pair['distance'];
            }, $component_pairs);

            $min_distance = min($distances);
            $max_distance = max($distances);
            $avg_distance = array_sum($distances) / count($distances);

            $tier = $this->classify_distance($min_distance, $thresholds);

            $groups[] = [
                'attachment_ids' => $component,
                'records'        => array_values(array_map(function ($id) use ($record_index) {
                    return $record_index[$id];
                }, $component)),
                'pairs'          => array_values($component_pairs),
                'metrics'        => [
                    'min_distance'   => $min_distance,
                    'max_distance'   => $max_distance,
                    'avg_distance'   => round($avg_distance, 2),
                    'primary_tier'   => $tier,
                    'primary_score'  => round(100 - (($min_distance / 64) * 100), 2),
                    'pair_count'     => count($component_pairs),
                ],
            ];
        }

        return [
            'groups'        => $groups,
            'pair_metrics'  => array_values($pair_metrics),
            'thresholds'    => $thresholds,
            'bucket_count'  => count($buckets),
            'record_count'  => count($record_index),
        ];
    }

    /**
     * Clear cached hash for attachment.
     *
     * @param int $attachment_id Attachment ID.
     */
    public function clear_cache($attachment_id) {
        delete_post_meta($attachment_id, self::META_HASH);
        delete_post_meta($attachment_id, self::META_TIME);
        delete_post_meta($attachment_id, self::META_MODIFIED);
        delete_post_meta($attachment_id, self::META_STATUS);
    }

    /**
     * Get cached hash if it exists and remains valid.
     *
     * @param int $attachment_id Attachment ID.
     *
     * @return string|false
     */
    public function get_cached_hash($attachment_id) {
        $attachment_id = absint($attachment_id);
        if (!$attachment_id) {
            return false;
        }

        $hash = get_post_meta($attachment_id, self::META_HASH, true);
        if (!$hash) {
            return false;
        }

        $file = get_attached_file($attachment_id);
        if (!$file || !file_exists($file)) {
            return false;
        }

        $cached_mtime = (int) get_post_meta($attachment_id, self::META_MODIFIED, true);
        $current_mtime = filemtime($file);

        if ($current_mtime && $cached_mtime && $current_mtime === $cached_mtime) {
            return $hash;
        }

        return false;
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    /**
     * Compute perceptual hash from file path.
     *
     * @param string $file File path.
     *
     * @return string|WP_Error
     */
    private function compute_hash_from_file($file) {
        $contents = @file_get_contents($file);
        if (false === $contents) {
            return new WP_Error('msh_phash_read_failure', 'Unable to read file contents for perceptual hash.');
        }

        $image = @imagecreatefromstring($contents);
        if (!$image) {
            return new WP_Error('msh_phash_decode_failure', 'Failed to decode image for perceptual hashing.');
        }

        $width  = imagesx($image);
        $height = imagesy($image);

        if ($width < 2 || $height < 2) {
            imagedestroy($image);
            return new WP_Error('msh_phash_small_image', 'Image too small for perceptual hashing.');
        }

        $target_w = 9;
        $target_h = 8;

        $resized = imagecreatetruecolor($target_w, $target_h);
        if (!$resized) {
            imagedestroy($image);
            return new WP_Error('msh_phash_resize_failure', 'Unable to allocate memory for perceptual hash resize.');
        }

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $target_w, $target_h, $width, $height);
        imagedestroy($image);

        $hash = $this->generate_dhash($resized, $target_w, $target_h);

        imagedestroy($resized);

        if (!$hash) {
            return new WP_Error('msh_phash_generation_failure', 'Failed to compute perceptual hash.');
        }

        return $hash;
    }

    /**
     * Generate 64-bit dHash from downsized GD image.
     *
     * @param \GdImage $image GD image resource.
     * @param int      $width Width of downsampled image (expects 9).
     * @param int      $height Height of downsampled image (expects 8).
     *
     * @return string 16-character hex string.
     */
    private function generate_dhash($image, $width, $height) {
        $bits = '';

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width - 1; $x++) {
                $left_rgb  = imagecolorat($image, $x, $y);
                $right_rgb = imagecolorat($image, $x + 1, $y);

                $left_luma  = $this->luma_from_color($left_rgb);
                $right_luma = $this->luma_from_color($right_rgb);

                $bits .= ($left_luma > $right_luma) ? '1' : '0';
            }
        }

        if (strlen($bits) !== 64) {
            return '';
        }

        $hash = '';
        for ($i = 0; $i < 64; $i += 4) {
            $nibble = substr($bits, $i, 4);
            $hash  .= dechex(bindec($nibble));
        }

        return str_pad($hash, 16, '0', STR_PAD_LEFT);
    }

    /**
     * Convert integer color to luminance value.
     *
     * @param int $color GD color value.
     *
     * @return float
     */
    private function luma_from_color($color) {
        $r = ($color >> 16) & 0xFF;
        $g = ($color >> 8) & 0xFF;
        $b = $color & 0xFF;

        return (0.299 * $r) + (0.587 * $g) + (0.114 * $b);
    }

    /**
     * Persist successful hash metadata.
     *
     * @param int    $attachment_id Attachment ID.
     * @param string $hash           64-bit hex hash.
     * @param string $file           File path.
     */
    private function persist_hash($attachment_id, $hash, $file) {
        $modified = filemtime($file);
        if (false === $modified) {
            $modified = time();
        }

        update_post_meta($attachment_id, self::META_HASH, $hash);
        update_post_meta($attachment_id, self::META_TIME, time());
        update_post_meta($attachment_id, self::META_MODIFIED, (int) $modified);
        update_post_meta($attachment_id, self::META_STATUS, self::STATUS_OK);
    }

    /**
     * Persist skip state for unsupported assets.
     *
     * @param int    $attachment_id Attachment ID.
     * @param string $status        Status string.
     */
    private function persist_skip_state($attachment_id, $status) {
        $status = $status ?: self::STATUS_UNSUPPORTED;
        update_post_meta($attachment_id, self::META_HASH, '');
        update_post_meta($attachment_id, self::META_TIME, time());
        update_post_meta($attachment_id, self::META_MODIFIED, 0);
        update_post_meta($attachment_id, self::META_STATUS, $status);
    }

    /**
     * Persist failure state to prevent repeated attempts.
     *
     * @param int      $attachment_id Attachment ID.
     * @param WP_Error $error         Failure data.
     */
    private function persist_failure_state($attachment_id, WP_Error $error) {
        $this->persist_skip_state($attachment_id, self::STATUS_ERROR);
        update_post_meta($attachment_id, '_msh_phash_error', [
            'code'    => $error->get_error_code(),
            'message' => $error->get_error_message(),
            'time'    => time(),
        ]);
    }

    /**
     * Build bucket key from record.
     *
     * @param array $record Attachment record.
     *
     * @return string
     */
    private function derive_bucket_key(array $record) {
        $mime = isset($record['mime']) ? strtolower((string) $record['mime']) : 'unknown';
        $width_bucket  = $this->dimension_bucket($record['width'] ?? 0);
        $height_bucket = $this->dimension_bucket($record['height'] ?? 0);

        return sprintf('%s|%s|%s', $mime, $width_bucket, $height_bucket);
    }

    /**
     * Coarse bucket for dimensions.
     *
     * @param int|float $dimension Dimension value.
     *
     * @return string
     */
    private function dimension_bucket($dimension) {
        $dimension = (int) $dimension;
        if ($dimension <= 0) {
            return 'unknown';
        }

        $tolerance = max(4, (int) round($dimension * 0.05));
        $bucket    = (int) floor($dimension / $tolerance);

        return (string) $bucket;
    }

    /**
     * Determine if two records should be compared based on dimensions.
     *
     * @param array $a Record A.
     * @param array $b Record B.
     *
     * @return bool
     */
    private function passes_dimension_gate(array $a, array $b) {
        $width_a  = isset($a['width']) ? (int) $a['width'] : 0;
        $width_b  = isset($b['width']) ? (int) $b['width'] : 0;
        $height_a = isset($a['height']) ? (int) $a['height'] : 0;
        $height_b = isset($b['height']) ? (int) $b['height'] : 0;

        if ($width_a <= 0 || $width_b <= 0 || $height_a <= 0 || $height_b <= 0) {
            return true; // Missing metadata; fallback to hash distance alone.
        }

        $width_delta  = abs($width_a - $width_b);
        $height_delta = abs($height_a - $height_b);

        $width_limit  = max(4, (int) round(max($width_a, $width_b) * 0.05));
        $height_limit = max(4, (int) round(max($height_a, $height_b) * 0.05));

        return ($width_delta <= $width_limit) && ($height_delta <= $height_limit);
    }

    /**
     * Collect connected component from graph.
     *
     * @param int   $root_id Starting node.
     * @param array $graph   Adjacency list.
     *
     * @return int[]
     */
    private function collect_component($root_id, array $graph) {
        $stack = [$root_id];
        $component = [];
        $seen = [];

        while (!empty($stack)) {
            $current = array_pop($stack);
            if (isset($seen[$current])) {
                continue;
            }

            $seen[$current] = true;
            $component[] = $current;

            if (!isset($graph[$current])) {
                continue;
            }

            foreach ($graph[$current] as $neighbor => $distance) {
                if (!isset($seen[$neighbor])) {
                    $stack[] = $neighbor;
                }
            }
        }

        sort($component);

        return $component;
    }

    /**
     * Extract relevant pair metrics for component.
     *
     * @param int[] $component Nodes in component.
     * @param array $pair_metrics All metrics.
     *
     * @return array
     */
    private function extract_component_pairs(array $component, array $pair_metrics) {
        $map = [];
        foreach ($component as $id) {
            $map[$id] = true;
        }

        $pairs = [];
        foreach ($pair_metrics as $pair) {
            if (isset($map[$pair['source']]) && isset($map[$pair['target']])) {
                $pairs[] = $pair;
            }
        }

        return $pairs;
    }

    /**
     * Classify distance into confidence tier.
     *
     * @param int   $distance   Hamming distance.
     * @param array $thresholds Threshold map.
     *
     * @return string
     */
    private function classify_distance($distance, array $thresholds = null) {
        $thresholds = $this->resolve_thresholds($thresholds);

        if ($distance <= $thresholds['definite']) {
            return 'definite';
        }

        if ($distance <= $thresholds['likely']) {
            return 'likely';
        }

        if ($distance <= $thresholds['possible']) {
            return 'possible';
        }

        return 'distinct';
    }

    /**
     * Merge overrides with defaults.
     *
     * @param array $overrides Overrides.
     *
     * @return array
     */
    private function resolve_thresholds($overrides) {
        if (!is_array($overrides)) {
            $overrides = [];
        }

        $merged = array_merge($this->thresholds, array_filter($overrides, static function ($value) {
            return is_int($value) && $value >= 0;
        }));

        // Ensure monotonic order.
        if ($merged['definite'] > $merged['likely']) {
            $merged['likely'] = $merged['definite'];
        }

        if ($merged['likely'] > $merged['possible']) {
            $merged['possible'] = $merged['likely'];
        }

        return $merged;
    }

    /**
     * Initialize bitcount lookup table.
     */
    private function bootstrap_bitcount_map() {
        for ($i = 0; $i < 256; $i++) {
            self::$bitCountMap[$i] = substr_count(decbin($i), '1');
        }
    }

    /**
     * Count set bits for byte.
     *
     * @param int $byte Byte value.
     *
     * @return int
     */
    private function popcount($byte) {
        return self::$bitCountMap[$byte & 0xFF];
    }

    /**
     * Create deterministic pair key.
     *
     * @param int $a Node A.
     * @param int $b Node B.
     *
     * @return string
     */
    private function pair_key($a, $b) {
        return ($a < $b) ? $a . '|' . $b : $b . '|' . $a;
    }
}


<?php
/**
 * Standalone Image Usage Index Rebuild Script
 * Run this file directly to rebuild the usage index without JavaScript timeouts
 *
 * Usage: Open this URL in browser or run via command line
 * Example: php rebuild-index.php
 */

// Load WordPress
require_once dirname(__FILE__) . '/wp-config.php';
require_once dirname(__FILE__) . '/wp-blog-header.php';

// Security check - only allow admin users or command line execution
if (!is_admin() && !defined('WP_CLI') && php_sapi_name() !== 'cli') {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access. Admin privileges required.');
    }
}

// Start output
echo "<h1>MSH Image Usage Index Rebuild</h1>\n";
echo "<p>Starting robust server-side rebuild...</p>\n";

// Flush output immediately
if (ob_get_level()) {
    ob_end_flush();
}
flush();

try {
    // Get the image usage index instance
    $usage_index = MSH_Image_Usage_Index::get_instance();

    if (!$usage_index) {
        throw new Exception('Failed to get MSH_Image_Usage_Index instance');
    }

    echo "<p>Usage index instance loaded successfully.</p>\n";
    flush();

    // Run the robust server rebuild
    $processed = $usage_index->robust_server_rebuild(10);

    echo "<h2>Rebuild Complete!</h2>\n";
    echo "<p>Successfully processed <strong>{$processed}</strong> attachments.</p>\n";

    // Get final stats
    $stats = $usage_index->get_index_stats();
    if ($stats && isset($stats['summary'])) {
        echo "<h3>Final Statistics:</h3>\n";
        echo "<ul>\n";
        echo "<li>Total entries: " . $stats['summary']->total_entries . "</li>\n";
        echo "<li>Indexed attachments: " . $stats['summary']->indexed_attachments . "</li>\n";
        echo "<li>Unique locations: " . $stats['summary']->unique_locations . "</li>\n";
        echo "<li>Last updated: " . $stats['summary']->last_update . "</li>\n";
        echo "</ul>\n";
    }

    echo "<p><strong>Success!</strong> The image usage index has been fully rebuilt.</p>\n";
    echo "<p>You can now safely return to the admin interface.</p>\n";

} catch (Exception $e) {
    echo "<h2>Error During Rebuild</h2>\n";
    echo "<p style='color: red;'>Error: " . esc_html($e->getMessage()) . "</p>\n";
    echo "<p>Check the error logs for more details.</p>\n";
}

// Add some basic styling
echo "<style>
body { font-family: Arial, sans-serif; margin: 40px; max-width: 800px; }
h1 { color: #333; }
h2 { color: #666; }
ul { background: #f5f5f5; padding: 15px; border-radius: 5px; }
li { margin: 5px 0; }
</style>";
?>
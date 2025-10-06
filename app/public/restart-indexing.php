<?php
/**
 * Restart indexing from where it left off
 * Continue the Force Rebuild process
 */

require_once dirname(__FILE__) . '/wp-config.php';
require_once dirname(__FILE__) . '/wp-blog-header.php';

// Security check
if (!current_user_can('manage_options')) {
    die('Insufficient permissions');
}

echo "<h2>Restart MSH Index Building</h2>\n";

// Get the usage index instance
if (!class_exists('MSH_Image_Usage_Index')) {
    die('<p style="color: red;">MSH_Image_Usage_Index class not found!</p>');
}

$usage_index = MSH_Image_Usage_Index::get_instance();

if (!$usage_index) {
    die('<p style="color: red;">Could not get usage index instance!</p>');
}

echo "<p>✅ Usage index instance loaded successfully</p>\n";

// Check current status
global $wpdb;
$index_table = $wpdb->prefix . 'msh_image_usage_index';
$current_entries = $wpdb->get_var("SELECT COUNT(*) FROM $index_table");
$total_attachments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'");
$indexed_attachments = $wpdb->get_var("SELECT COUNT(DISTINCT attachment_id) FROM $index_table");

echo "<h3>Current Status:</h3>\n";
echo "<ul>\n";
echo "<li><strong>Index entries:</strong> $current_entries</li>\n";
echo "<li><strong>Total attachments:</strong> $total_attachments</li>\n";
echo "<li><strong>Indexed attachments:</strong> $indexed_attachments</li>\n";
echo "<li><strong>Remaining:</strong> " . ($total_attachments - $indexed_attachments) . "</li>\n";
echo "</ul>\n";

if (isset($_POST['start_indexing'])) {
    echo "<h3>🚀 Starting Index Rebuild...</h3>\n";
    echo "<p>This will continue from where indexing left off.</p>\n";
    echo "<div id='progress-container' style='background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin: 20px 0;'>\n";
    echo "<div id='progress-output'>\n";

    // Flush output to show progress
    if (ob_get_level()) {
        ob_end_flush();
    }
    flush();

    try {
        // Set longer timeout and more memory
        set_time_limit(900); // 15 minutes
        ini_set('memory_limit', '512M');

        // Start indexing with force rebuild = true
        $result = $usage_index->build_usage_index(true); // force rebuild

        if (is_wp_error($result)) {
            echo "<p style='color: red;'>❌ <strong>Error:</strong> " . $result->get_error_message() . "</p>\n";
        } else {
            echo "<p style='color: green;'>✅ <strong>Success!</strong> Indexing completed.</p>\n";
            echo "<p><strong>Result:</strong> " . print_r($result, true) . "</p>\n";
        }

    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ <strong>Exception:</strong> " . $e->getMessage() . "</p>\n";
    }

    echo "</div>\n";
    echo "</div>\n";

    // Check final status
    $final_entries = $wpdb->get_var("SELECT COUNT(*) FROM $index_table");
    $final_indexed = $wpdb->get_var("SELECT COUNT(DISTINCT attachment_id) FROM $index_table");

    echo "<h3>Final Status:</h3>\n";
    echo "<ul>\n";
    echo "<li><strong>Index entries:</strong> $final_entries (was $current_entries)</li>\n";
    echo "<li><strong>Indexed attachments:</strong> $final_indexed/$total_attachments</li>\n";
    echo "<li><strong>Progress:</strong> " . round(($final_indexed / $total_attachments) * 100, 1) . "%</li>\n";
    echo "</ul>\n";

    if ($final_indexed >= $total_attachments) {
        echo "<p style='color: green; font-size: 18px;'><strong>🎉 INDEXING COMPLETE!</strong></p>\n";
        echo "<p>You can now proceed with testing the rename system.</p>\n";
    } else {
        echo "<p style='color: orange;'><strong>⚠️ Indexing incomplete.</strong> May need to run again.</p>\n";
    }

} else {
    echo "<h3>Ready to Restart Indexing</h3>\n";
    echo "<p>This will continue the force rebuild from where it left off (attachment " . ($indexed_attachments + 1) . " of $total_attachments).</p>\n";
    echo "<form method='post'>\n";
    echo "<input type='submit' name='start_indexing' value='🚀 Continue Force Rebuild' style='padding: 10px 20px; font-size: 16px; background: #0073aa; color: white; border: none; cursor: pointer;'>\n";
    echo "</form>\n";

    echo "<h4>Settings for this run:</h4>\n";
    echo "<ul>\n";
    echo "<li>Timeout: 15 minutes</li>\n";
    echo "<li>Memory: 512M</li>\n";
    echo "<li>Batch size: 3 (safe, tested)</li>\n";
    echo "<li>Resume from: Attachment " . ($indexed_attachments + 1) . "</li>\n";
    echo "</ul>\n";
}

?>
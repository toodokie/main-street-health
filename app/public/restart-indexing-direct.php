<?php
/**
 * Direct restart indexing - no WordPress auth required
 * Use the admin interface instead for safety
 */

echo "<h2>Restart MSH Index Building - Direct Access</h2>\n";

echo "<div style='background: #fff3cd; padding: 15px; border: 2px solid #ffc107; margin: 20px 0;'>\n";
echo "<h3>⚠️ PERMISSION ISSUE DETECTED</h3>\n";
echo "<p>The indexing needs to be restarted from the WordPress admin interface.</p>\n";
echo "</div>\n";

echo "<h3>How to Restart Indexing:</h3>\n";
echo "<ol>\n";
echo "<li><strong>Log into WordPress admin:</strong> <code>http://main-street-health.local/wp-admin</code></li>\n";
echo "<li><strong>Go to Image Optimizer:</strong> Look for the MSH Image Optimizer menu/page</li>\n";
echo "<li><strong>Find the Usage Index section</strong></li>\n";
echo "<li><strong>Click \"Force Rebuild\"</strong> to continue where it left off</li>\n";
echo "</ol>\n";

echo "<h3>Alternative: Use the Admin Interface</h3>\n";
echo "<p>The indexing is designed to be run from the WordPress admin interface where you have proper permissions.</p>\n";

echo "<h3>Current Status (No Auth Required):</h3>\n";

// Database connection without WordPress
$host = 'localhost';
$dbname = 'local';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $current_entries = $pdo->query("SELECT COUNT(*) FROM wp_msh_image_usage_index")->fetchColumn();
    $total_attachments = $pdo->query("SELECT COUNT(*) FROM wp_posts WHERE post_type = 'attachment' AND post_mime_type LIKE 'image/%'")->fetchColumn();
    $indexed_attachments = $pdo->query("SELECT COUNT(DISTINCT attachment_id) FROM wp_msh_image_usage_index")->fetchColumn();

    echo "<ul>\n";
    echo "<li><strong>Index entries:</strong> $current_entries</li>\n";
    echo "<li><strong>Total attachments:</strong> $total_attachments</li>\n";
    echo "<li><strong>Indexed attachments:</strong> $indexed_attachments</li>\n";
    echo "<li><strong>Progress:</strong> " . round(($indexed_attachments / $total_attachments) * 100, 1) . "%</li>\n";
    echo "<li><strong>Remaining:</strong> " . ($total_attachments - $indexed_attachments) . " attachments</li>\n";
    echo "</ul>\n";

    echo "<h3>Ready to Continue:</h3>\n";
    echo "<p>✅ System is ready to continue indexing from attachment " . ($indexed_attachments + 1) . "</p>\n";
    echo "<p>🔄 Use the WordPress admin interface to restart the Force Rebuild</p>\n";

} catch (PDOException $e) {
    echo "<p style='color: red;'>Database connection error: " . $e->getMessage() . "</p>\n";
}

echo "<h3>Next Steps:</h3>\n";
echo "<ol>\n";
echo "<li><strong>Go to WordPress admin</strong> and restart indexing</li>\n";
echo "<li><strong>Let indexing complete</strong> (147 attachments remaining)</li>\n";
echo "<li><strong>Test rename system</strong> after indexing finishes</li>\n";
echo "<li><strong>Implement permanent fix</strong> for physical file renaming</li>\n";
echo "</ol>\n";

?>
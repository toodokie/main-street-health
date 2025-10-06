<?php
/**
 * Analyze the broken image situation
 */

// Security check - relaxed for Local development
if (!defined('WP_LOCAL_DEV')) {
    define('WP_LOCAL_DEV', true);
}

require_once dirname(__FILE__) . '/wp-load.php';

echo "<h2>🔍 Broken Image Analysis</h2>\n";
echo "<p><strong>Understanding why images are still broken</strong></p>\n";

try {
    // Get all attachments
    $all_attachments = get_posts([
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
        'post_mime_type' => 'image'
    ]);

    echo "<p>✅ Found " . count($all_attachments) . " total image attachments</p>\n";

    // Check which files exist vs don't exist
    $existing_files = 0;
    $missing_files = 0;
    $broken_files = [];

    foreach ($all_attachments as $attachment) {
        $file_path = get_attached_file($attachment->ID);
        $file_url = wp_get_attachment_url($attachment->ID);

        if (file_exists($file_path)) {
            $existing_files++;
        } else {
            $missing_files++;
            $broken_files[] = [
                'id' => $attachment->ID,
                'title' => $attachment->post_title,
                'expected_path' => $file_path,
                'expected_url' => $file_url,
                'filename' => basename($file_path)
            ];
        }
    }

    echo "<p><strong>File Status:</strong></p>\n";
    echo "<ul>\n";
    echo "<li style='color: green;'>✅ Existing files: {$existing_files}</li>\n";
    echo "<li style='color: red;'>❌ Missing files: {$missing_files}</li>\n";
    echo "</ul>\n";

    if (!empty($broken_files)) {
        echo "<h3>🔴 Missing Files (First 10):</h3>\n";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>\n";
        echo "<tr><th>ID</th><th>Title</th><th>Expected Filename</th><th>Check Alternative</th></tr>\n";

        foreach (array_slice($broken_files, 0, 10) as $broken) {
            echo "<tr>\n";
            echo "<td>{$broken['id']}</td>\n";
            echo "<td>" . htmlspecialchars($broken['title']) . "</td>\n";
            echo "<td>" . htmlspecialchars($broken['filename']) . "</td>\n";

            // Check for alternative filenames that might exist
            $upload_dir = wp_upload_dir();
            $dirname = dirname($broken['expected_path']);
            $filename = $broken['filename'];
            $pathinfo = pathinfo($filename);

            $alternatives = [];

            // Common pattern fixes
            $test_patterns = [
                str_replace('-photo.', '-hamilton.', $filename),
                str_replace('-injuries-', '-injury-', $filename),
                str_replace('-icon-hamilton-', '-hamilton-', $filename),
                preg_replace('/-\d+\./', '.', $filename), // Remove numbers
                $pathinfo['filename'] . '-1.' . $pathinfo['extension'], // Add -1
                $pathinfo['filename'] . '-hamilton.' . $pathinfo['extension'] // Add -hamilton
            ];

            foreach ($test_patterns as $test_filename) {
                $test_path = $dirname . '/' . $test_filename;
                if (file_exists($test_path) && $test_filename !== $filename) {
                    $alternatives[] = $test_filename;
                }
            }

            if (!empty($alternatives)) {
                echo "<td style='color: green;'>Found: " . implode(', ', $alternatives) . "</td>\n";
            } else {
                echo "<td style='color: red;'>No alternatives found</td>\n";
            }

            echo "</tr>\n";
        }
        echo "</table>\n";
    }

    // Check emergency redirect patterns
    echo "<h3>🔄 Emergency Redirect Analysis</h3>\n";

    // Count how many would be fixed by each pattern
    $pattern1_fixes = 0; // *-photo.png → *-hamilton.png
    $pattern2_fixes = 0; // *-injuries-* → *-injury-*

    foreach ($broken_files as $broken) {
        $filename = $broken['filename'];
        $dirname = dirname($broken['expected_path']);

        // Pattern 1 test
        if (preg_match('/(.+)-photo\.(png|jpg|jpeg|webp)$/i', $filename)) {
            $alt_filename = str_replace('-photo.', '-hamilton.', $filename);
            if (file_exists($dirname . '/' . $alt_filename)) {
                $pattern1_fixes++;
            }
        }

        // Pattern 2 test
        if (preg_match('/(.+)-injuries-(.+)\.(png|jpg|jpeg|webp)$/i', $filename)) {
            $alt_filename = str_replace('-injuries-', '-injury-', $filename);
            if (file_exists($dirname . '/' . $alt_filename)) {
                $pattern2_fixes++;
            }
        }
    }

    echo "<p><strong>Emergency Redirect Effectiveness:</strong></p>\n";
    echo "<ul>\n";
    echo "<li>Pattern 1 (*-photo → *-hamilton): {$pattern1_fixes} fixes</li>\n";
    echo "<li>Pattern 2 (*-injuries → *-injury): {$pattern2_fixes} fixes</li>\n";
    echo "<li>Total emergency fixes: " . ($pattern1_fixes + $pattern2_fixes) . " out of {$missing_files} broken</li>\n";
    echo "</ul>\n";

    // Summary
    echo "<div style='background: #fff3cd; padding: 15px; border: 2px solid #ffc107; margin: 20px 0;'>";
    echo "<h3>📊 Summary</h3>";
    echo "<p><strong>The Issue:</strong> {$missing_files} files have database entries but missing physical files</p>";
    echo "<p><strong>Emergency Redirects Fix:</strong> " . ($pattern1_fixes + $pattern2_fixes) . " files (" . round((($pattern1_fixes + $pattern2_fixes) / $missing_files) * 100, 1) . "%)</p>";
    echo "<p><strong>Still Broken:</strong> " . ($missing_files - $pattern1_fixes - $pattern2_fixes) . " files need proper renaming</p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<p style='color: red;'><strong>❌ Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>\n";
}
?>
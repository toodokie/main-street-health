<?php
/**
 * Clean up database references to the 12 missing image files
 * Since these files don't exist anywhere, remove them from database content
 */

// Database connection
$host = 'localhost';
$dbname = 'local';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

echo "<h2>Clean Missing Image References</h2>\n";

// The 12 completely missing files
$missing_files = [
    'wp-content/uploads/2024/07/blsg2.webp',
    'wp-content/uploads/2024/07/blsg3.webp',
    'wp-content/uploads/2024/07/blsg4.webp',
    'wp-content/uploads/2024/10/home-1.webp',
    'wp-content/uploads/2024/10/home-2.webp',
    'wp-content/uploads/2024/10/home-4.webp',
    'wp-content/uploads/2025/03/ps29.webp',
    'wp-content/uploads/2025/03/ps29-300x212.webp',
    'wp-content/uploads/2025/03/eye1.webp',
    'wp-content/uploads/2025/03/eye1-300x212.webp',
    'wp-content/uploads/2025/04/home-dental.webp',
    'wp-content/uploads/2025/04/home-dental-300x212.webp'
];

echo "<h3>Analysis Before Cleanup:</h3>\n";

$total_references = 0;
$found_in_posts = [];
$found_in_meta = [];

// Check where these missing files are referenced
foreach ($missing_files as $missing_url) {
    // Check post content
    $stmt = $pdo->prepare("
        SELECT ID, post_title, post_type, post_status
        FROM wp_posts
        WHERE post_content LIKE :pattern
    ");
    $pattern = '%' . $missing_url . '%';
    $stmt->execute(['pattern' => $pattern]);
    $posts = $stmt->fetchAll();

    if (!empty($posts)) {
        $found_in_posts[$missing_url] = $posts;
        $total_references += count($posts);
    }

    // Check postmeta
    $stmt = $pdo->prepare("
        SELECT meta_id, post_id, meta_key
        FROM wp_postmeta
        WHERE meta_value LIKE :pattern
    ");
    $stmt->execute(['pattern' => $pattern]);
    $meta = $stmt->fetchAll();

    if (!empty($meta)) {
        $found_in_meta[$missing_url] = $meta;
        $total_references += count($meta);
    }
}

echo "<p><strong>Total references found:</strong> $total_references</p>\n";

if (!empty($found_in_posts) || !empty($found_in_meta)) {
    echo "<h4>References Found:</h4>\n";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>Missing File</th><th>Found In</th><th>Details</th></tr>\n";

    foreach ($missing_files as $missing_url) {
        echo "<tr>\n";
        echo "<td style='font-size: 11px;'>" . htmlspecialchars($missing_url) . "</td>\n";

        $locations = [];
        if (isset($found_in_posts[$missing_url])) {
            foreach ($found_in_posts[$missing_url] as $post) {
                $locations[] = "Post: {$post['post_title']} (ID: {$post['ID']}, Type: {$post['post_type']})";
            }
        }
        if (isset($found_in_meta[$missing_url])) {
            foreach ($found_in_meta[$missing_url] as $meta) {
                $locations[] = "Meta: Post {$meta['post_id']} - {$meta['meta_key']}";
            }
        }

        if (!empty($locations)) {
            echo "<td style='color: red;'>Found</td>\n";
            echo "<td style='font-size: 10px;'>" . implode('<br>', array_slice($locations, 0, 3)) . "</td>\n";
        } else {
            echo "<td style='color: green;'>Not found</td>\n";
            echo "<td>-</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";

    echo "<h3>Cleanup Options:</h3>\n";
    echo "<div style='background: #f0f8ff; padding: 15px; border: 2px solid #4CAF50;'>\n";
    echo "<h4>Option 1: Safe Replacement (Recommended)</h4>\n";
    echo "<p>Replace missing image references with placeholder or existing similar images:</p>\n";
    echo "<textarea style='width: 100%; height: 150px; font-family: monospace;'>";
    echo "-- Replace missing files with placeholder\n";
    foreach ($missing_files as $missing_url) {
        echo "UPDATE wp_posts SET post_content = REPLACE(post_content, '" . addslashes($missing_url) . "', 'wp-content/uploads/placeholder.png') WHERE post_content LIKE '%" . addslashes($missing_url) . "%';\n";
        echo "UPDATE wp_postmeta SET meta_value = REPLACE(meta_value, '" . addslashes($missing_url) . "', 'wp-content/uploads/placeholder.png') WHERE meta_value LIKE '%" . addslashes($missing_url) . "%';\n";
    }
    echo "</textarea>\n";
    echo "</div>\n";

    echo "<div style='background: #fff3cd; padding: 15px; border: 2px solid #ffc107; margin-top: 10px;'>\n";
    echo "<h4>Option 2: Remove References (More Aggressive)</h4>\n";
    echo "<p>Remove the entire image tags/references:</p>\n";
    echo "<textarea style='width: 100%; height: 150px; font-family: monospace;'>";
    echo "-- Remove entire img tags with missing files\n";
    foreach ($missing_files as $missing_url) {
        echo "UPDATE wp_posts SET post_content = REGEXP_REPLACE(post_content, '<img[^>]*" . addslashes($missing_url) . "[^>]*>', '', 'g') WHERE post_content LIKE '%" . addslashes($missing_url) . "%';\n";
    }
    echo "</textarea>\n";
    echo "</div>\n";

    echo "<h3>Recommended Approach:</h3>\n";
    echo "<ol>\n";
    echo "<li><strong>First:</strong> Try Option 1 (replace with placeholder)</li>\n";
    echo "<li><strong>Test:</strong> Check website still looks good</li>\n";
    echo "<li><strong>Later:</strong> Replace placeholders with proper images</li>\n";
    echo "<li><strong>Final:</strong> Remove placeholder references if not needed</li>\n";
    echo "</ol>\n";

} else {
    echo "<p style='color: green;'><strong>✅ GOOD NEWS:</strong> These missing files are not actually referenced in your database!</p>\n";
    echo "<p>This means they were just found in our analysis but aren't causing broken images on your website.</p>\n";
}

echo "<h3>Next Steps:</h3>\n";
echo "<ul>\n";
echo "<li>If references found: Run the cleanup SQL above</li>\n";
echo "<li>If no references: These files can be ignored</li>\n";
echo "<li>Either way: Your 51 redirects are still working great!</li>\n";
echo "<li>Focus on indexing and permanent fix next</li>\n";
echo "</ul>\n";

?>
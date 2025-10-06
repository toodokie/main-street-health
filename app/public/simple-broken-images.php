<?php
/**
 * Simple broken images finder - no WordPress dependencies
 * TEMPORARY - DELETE AFTER FIXING
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

echo "<h2>MSH Broken Images Analysis (Simple Version)</h2>\n";

// Find image URLs in post content
$stmt = $pdo->query("
    SELECT DISTINCT post_content
    FROM wp_posts
    WHERE post_content LIKE '%wp-content/uploads/%'
    AND post_status = 'publish'
    LIMIT 50
");

$image_refs = [];
$document_root = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public';

while ($row = $stmt->fetch()) {
    preg_match_all('/wp-content\/uploads\/[^"\s<>]+\.(png|jpg|jpeg|webp|gif)/i', $row['post_content'], $matches);
    foreach ($matches[0] as $match) {
        $image_refs[$match] = ($image_refs[$match] ?? 0) + 1;
    }
}

echo "<p>Found " . count($image_refs) . " unique image references</p>\n";

$broken_images = [];
$working_images = [];

foreach ($image_refs as $url => $count) {
    $file_path = $document_root . '/' . ltrim($url, '/');

    if (!file_exists($file_path)) {
        $broken_images[$url] = $count;
    } else {
        $working_images[$url] = $count;
    }
}

echo "<p><strong>✅ Working:</strong> " . count($working_images) . "</p>\n";
echo "<p><strong>❌ Broken:</strong> " . count($broken_images) . "</p>\n";

if (!empty($broken_images)) {
    echo "<h3>Broken Images Analysis:</h3>\n";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>Broken URL</th><th>Count</th><th>Potential Fix</th></tr>\n";

    $redirects_found = [];

    foreach ($broken_images as $broken_url => $count) {
        echo "<tr>\n";
        echo "<td style='font-size: 12px;'>" . htmlspecialchars($broken_url) . "</td>\n";
        echo "<td>$count</td>\n";

        $potential_fix = null;

        // Try different patterns
        $patterns = [
            // Pattern 1: -photo → -hamilton
            str_replace('-photo.', '-hamilton.', $broken_url),
            // Pattern 2: -injuries → -injury
            str_replace('-injuries-', '-injury-', $broken_url),
            // Pattern 3: -services → -service
            str_replace('-services-', '-service-', $broken_url),
        ];

        foreach ($patterns as $pattern) {
            $test_path = $document_root . '/' . ltrim($pattern, '/');
            if (file_exists($test_path)) {
                $potential_fix = $pattern;
                $redirects_found[$broken_url] = $pattern;
                break;
            }
        }

        if ($potential_fix) {
            echo "<td style='color: green; font-size: 12px;'>✅ " . htmlspecialchars($potential_fix) . "</td>\n";
        } else {
            echo "<td style='color: red;'>❌ No match found</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";

    if (!empty($redirects_found)) {
        echo "<h3>WordPress Redirect Code to Add:</h3>\n";
        echo "<textarea style='width: 100%; height: 200px; font-family: monospace;'>";
        echo "// Replace the \$specific_redirects array in functions.php with:\n";
        echo "\$specific_redirects = [\n";
        foreach ($redirects_found as $broken => $working) {
            echo "    '" . addslashes($broken) . "' => '" . addslashes($working) . "',\n";
        }
        echo "];\n";
        echo "</textarea>\n";

        echo "<h3>Summary:</h3>\n";
        echo "<ul>\n";
        echo "<li><strong>Total broken images:</strong> " . count($broken_images) . "</li>\n";
        echo "<li><strong>Fixable with redirects:</strong> " . count($redirects_found) . "</li>\n";
        echo "<li><strong>Still broken after fix:</strong> " . (count($broken_images) - count($redirects_found)) . "</li>\n";
        echo "</ul>\n";
    }
}

echo "<h3>Quick Test Current Setup:</h3>\n";
echo "<img src='/wp-content/uploads/2024/09/motor-injuries-photo.png' alt='Test' style='max-width: 200px; border: 2px solid green;'>\n";
echo "<p><em>If image shows: redirect works. If broken: redirect failed.</em></p>\n";

?>
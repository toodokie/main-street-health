<?php
/**
 * Find all broken images by comparing database references with actual files
 * TEMPORARY - DELETE AFTER FIXING
 */

require_once dirname(__FILE__) . '/wp-config.php';
require_once dirname(__FILE__) . '/wp-blog-header.php';

global $wpdb;

echo "<h2>MSH Broken Images Analysis</h2>\n";

// Find all image URLs referenced in database
$image_refs = [];

// Search post content
$posts = $wpdb->get_results("
    SELECT ID, post_content
    FROM {$wpdb->posts}
    WHERE post_content LIKE '%wp-content/uploads/%'
    AND post_status = 'publish'
");

foreach ($posts as $post) {
    preg_match_all('/wp-content\/uploads\/[^"\s<>]+\.(png|jpg|jpeg|webp|gif)/i', $post->post_content, $matches);
    foreach ($matches[0] as $match) {
        $image_refs[$match] = ($image_refs[$match] ?? 0) + 1;
    }
}

// Search postmeta
$meta = $wpdb->get_results("
    SELECT meta_value
    FROM {$wpdb->postmeta}
    WHERE meta_value LIKE '%wp-content/uploads/%'
");

foreach ($meta as $m) {
    preg_match_all('/wp-content\/uploads\/[^"\s<>]+\.(png|jpg|jpeg|webp|gif)/i', $m->meta_value, $matches);
    foreach ($matches[0] as $match) {
        $image_refs[$match] = ($image_refs[$match] ?? 0) + 1;
    }
}

echo "<h3>Analysis Results:</h3>\n";
echo "<p>Found " . count($image_refs) . " unique image references in database</p>\n";

$broken_images = [];
$working_images = [];

foreach ($image_refs as $url => $count) {
    $file_path = ABSPATH . ltrim($url, '/');

    if (!file_exists($file_path)) {
        $broken_images[$url] = $count;
    } else {
        $working_images[$url] = $count;
    }
}

echo "<p><strong>✅ Working images:</strong> " . count($working_images) . "</p>\n";
echo "<p><strong>❌ Broken images:</strong> " . count($broken_images) . "</p>\n";

if (!empty($broken_images)) {
    echo "<h3>Broken Images (Need Redirects):</h3>\n";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>Broken URL</th><th>References</th><th>Potential Fix</th></tr>\n";

    foreach ($broken_images as $broken_url => $count) {
        echo "<tr>\n";
        echo "<td>" . htmlspecialchars($broken_url) . "</td>\n";
        echo "<td>$count</td>\n";

        // Try to find the actual file by pattern matching
        $potential_fixes = [];

        // Pattern 1: -photo → -hamilton
        $hamilton_version = str_replace('-photo.', '-hamilton.', $broken_url);
        if (file_exists(ABSPATH . ltrim($hamilton_version, '/'))) {
            $potential_fixes[] = $hamilton_version;
        }

        // Pattern 2: -injuries → -injury
        $injury_version = str_replace('-injuries-', '-injury-', $broken_url);
        if (file_exists(ABSPATH . ltrim($injury_version, '/'))) {
            $potential_fixes[] = $injury_version;
        }

        // Pattern 3: -services → -service
        $service_version = str_replace('-services-', '-service-', $broken_url);
        if (file_exists(ABSPATH . ltrim($service_version, '/'))) {
            $potential_fixes[] = $service_version;
        }

        // Pattern 4: Try to find similar files in the same directory
        $dir = dirname(ABSPATH . ltrim($broken_url, '/'));
        $filename = basename($broken_url);
        $name_parts = pathinfo($filename);

        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if (stripos($file, $name_parts['filename']) !== false && $file !== $filename) {
                    $potential_fixes[] = str_replace(basename($broken_url), $file, $broken_url);
                }
            }
        }

        if (!empty($potential_fixes)) {
            echo "<td><strong>Found:</strong> " . htmlspecialchars($potential_fixes[0]) . "</td>\n";
        } else {
            echo "<td><em>No matching file found</em></td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";

    // Generate PHP code for all redirects
    echo "<h3>Generated Redirect Code:</h3>\n";
    echo "<textarea style='width: 100%; height: 300px;'>\n";
    echo "// Add these specific redirects to functions.php\n";
    echo "\$specific_redirects = [\n";

    foreach ($broken_images as $broken_url => $count) {
        // Try the same pattern matching as above
        $hamilton_version = str_replace('-photo.', '-hamilton.', $broken_url);
        if (file_exists(ABSPATH . ltrim($hamilton_version, '/'))) {
            echo "    '$broken_url' => '$hamilton_version',\n";
        }
    }
    echo "];\n";
    echo "</textarea>\n";
}

echo "<h3>Quick Test:</h3>\n";
echo "<p>Testing current redirect for known broken image:</p>\n";
echo "<img src='/wp-content/uploads/2024/09/motor-injuries-photo.png' alt='Test' style='max-width: 200px; border: 2px solid green;'>\n";

?>
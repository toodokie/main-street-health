<?php
/**
 * Smart image matcher - finds actual files for broken URLs
 * Uses fuzzy matching to find renamed files
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

echo "<h2>Smart Image Matcher</h2>\n";

// Get broken images from previous analysis
$broken_images = [
    'wp-content/uploads/2025/08/chevron.png' => 12,
    'wp-content/uploads/2024/09/motor-injuries-photo.png' => 1,
    'wp-content/uploads/2025/09/chronic-pain-photo.png' => 1,
    'wp-content/uploads/2025/08/patient-testimonial-chevron-hamilton.png' => 8,
    'wp-content/uploads/2025/08/Summer-review.png' => 1,
    'wp-content/uploads/2025/08/Kiera-review.png' => 1,
    // Add more high-priority broken images here
];

$document_root = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public';
$redirects_found = [];

echo "<h3>Smart Matching Results:</h3>\n";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr><th>Broken URL</th><th>References</th><th>Best Match</th><th>Confidence</th></tr>\n";

foreach ($broken_images as $broken_url => $count) {
    echo "<tr>\n";
    echo "<td style='font-size: 11px;'>" . htmlspecialchars($broken_url) . "</td>\n";
    echo "<td>$count</td>\n";

    // Extract file info
    $path_info = pathinfo($broken_url);
    $directory = dirname($broken_url);
    $filename = $path_info['filename'];
    $extension = $path_info['extension'];

    // Search directory for similar files
    $search_dir = $document_root . '/' . ltrim($directory, '/');
    $best_match = null;
    $best_score = 0;

    if (is_dir($search_dir)) {
        $files = glob($search_dir . '/*.' . $extension);

        foreach ($files as $file) {
            $candidate = str_replace($document_root . '/', '', $file);
            $candidate_name = pathinfo($candidate, PATHINFO_FILENAME);

            // Calculate similarity score
            $score = 0;

            // Exact name match
            if ($candidate_name === $filename) {
                $score = 100;
            }
            // Contains the original name
            elseif (stripos($candidate_name, $filename) !== false) {
                $score = 80;
            }
            // Original name contains candidate
            elseif (stripos($filename, $candidate_name) !== false) {
                $score = 70;
            }
            // Similar_text comparison
            else {
                similar_text(strtolower($filename), strtolower($candidate_name), $percent);
                $score = $percent;
            }

            // Boost score for common patterns
            if (strpos($candidate_name, 'hamilton') !== false) $score += 10;
            if (strpos($candidate_name, 'icon') !== false) $score += 5;
            if (strpos($candidate_name, 'testimonial') !== false) $score += 5;

            if ($score > $best_score) {
                $best_score = $score;
                $best_match = $candidate;
            }
        }
    }

    if ($best_match && $best_score > 50) {
        echo "<td style='color: green; font-size: 11px;'>✅ " . htmlspecialchars($best_match) . "</td>\n";
        echo "<td style='color: green;'>" . round($best_score) . "%</td>\n";
        $redirects_found[$broken_url] = $best_match;
    } else {
        echo "<td style='color: red;'>❌ No good match</td>\n";
        echo "<td style='color: red;'>-</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

if (!empty($redirects_found)) {
    echo "<h3>High-Priority Redirect Code:</h3>\n";
    echo "<textarea style='width: 100%; height: 300px; font-family: monospace;'>";
    echo "// Replace the \$specific_redirects array in functions.php with this:\n";
    echo "\$specific_redirects = [\n";
    foreach ($redirects_found as $broken => $working) {
        echo "    '/" . addslashes($broken) . "' => '/" . addslashes($working) . "',\n";
    }
    echo "];\n";
    echo "</textarea>\n";

    echo "<h3>Impact Assessment:</h3>\n";
    $total_refs = array_sum(array_values($broken_images));
    $fixed_refs = 0;
    foreach ($redirects_found as $broken => $working) {
        $fixed_refs += $broken_images[$broken];
    }

    echo "<ul>\n";
    echo "<li><strong>High-priority broken images analyzed:</strong> " . count($broken_images) . "</li>\n";
    echo "<li><strong>Successfully matched:</strong> " . count($redirects_found) . "</li>\n";
    echo "<li><strong>Total broken references:</strong> $total_refs</li>\n";
    echo "<li><strong>References that will be fixed:</strong> $fixed_refs</li>\n";
    echo "<li><strong>Fix percentage:</strong> " . round(($fixed_refs / $total_refs) * 100) . "%</li>\n";
    echo "</ul>\n";
}

echo "<h3>Next Steps:</h3>\n";
echo "<ol>\n";
echo "<li>Copy the redirect code above</li>\n";
echo "<li>Replace the \$specific_redirects array in functions.php</li>\n";
echo "<li>Test your website - most broken images should now work</li>\n";
echo "<li>Run full analysis again to find remaining issues</li>\n";
echo "</ol>\n";

?>
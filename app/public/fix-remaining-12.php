<?php
/**
 * Investigate and fix the remaining 12 broken images
 * Manual investigation for files that couldn't be auto-matched
 */

echo "<h2>Fix Remaining 12 Broken Images</h2>\n";

// The 12 remaining broken images
$remaining_broken = [
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

$document_root = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public';
$new_redirects = [];

echo "<h3>Manual Investigation Results:</h3>\n";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr><th>Broken File</th><th>Directory Check</th><th>Similar Files Found</th><th>Recommendation</th></tr>\n";

foreach ($remaining_broken as $broken_url) {
    echo "<tr>\n";
    echo "<td style='font-size: 11px;'>" . htmlspecialchars($broken_url) . "</td>\n";

    $directory = dirname($broken_url);
    $filename = pathinfo($broken_url, PATHINFO_FILENAME);
    $extension = pathinfo($broken_url, PATHINFO_EXTENSION);

    $search_dir = $document_root . '/' . ltrim($directory, '/');

    if (!is_dir($search_dir)) {
        echo "<td style='color: red;'>❌ Directory doesn't exist</td>\n";
        echo "<td>-</td>\n";
        echo "<td>Create directory or remove references</td>\n";
    } else {
        echo "<td style='color: green;'>✅ Directory exists</td>\n";

        // Get all files in directory (including SVGs)
        $all_files = [];
        $extensions = ['webp', 'png', 'jpg', 'jpeg', 'svg'];
        foreach ($extensions as $ext) {
            $files = glob($search_dir . '/*.' . $ext);
            $all_files = array_merge($all_files, $files);
        }

        $similar_files = [];
        $exact_matches = [];

        foreach ($all_files as $file) {
            $candidate = str_replace($document_root . '/', '', $file);
            $candidate_name = pathinfo($candidate, PATHINFO_FILENAME);

            // Look for files with similar names
            if (stripos($candidate_name, $filename) !== false || stripos($filename, $candidate_name) !== false) {
                $similar_files[] = basename($candidate);
            }

            // Look for files that might be the same but different format
            if (strpos($candidate_name, str_replace(['1', '2', '3', '4', '5'], '', $filename)) !== false) {
                $exact_matches[] = basename($candidate);
            }
        }

        if (!empty($exact_matches)) {
            echo "<td style='color: green; font-size: 10px;'>" . implode(', ', array_slice($exact_matches, 0, 3)) . "</td>\n";
            echo "<td style='color: green;'>✅ Use first match</td>\n";

            // Take the first exact match
            $best_match = $directory . '/' . $exact_matches[0];
            $new_redirects[$broken_url] = $best_match;

        } elseif (!empty($similar_files)) {
            echo "<td style='color: orange; font-size: 10px;'>" . implode(', ', array_slice($similar_files, 0, 3)) . "</td>\n";
            echo "<td style='color: orange;'>⚠️ Manual review needed</td>\n";

        } else {
            echo "<td style='color: red;'>No similar files</td>\n";
            echo "<td style='color: red;'>❌ File missing - remove references</td>\n";
        }
    }
    echo "</tr>\n";
}
echo "</table>\n";

if (!empty($new_redirects)) {
    echo "<h3>Additional Redirects Found:</h3>\n";
    echo "<textarea style='width: 100%; height: 200px; font-family: monospace;'>";
    echo "// Add these to your existing \$specific_redirects array:\n";
    foreach ($new_redirects as $broken => $working) {
        echo "    '/" . addslashes($broken) . "' => '/" . addslashes($working) . "',\n";
    }
    echo "</textarea>\n";
}

// Check what's actually in some of these directories
echo "<h3>Directory Contents Analysis:</h3>\n";

$check_dirs = [
    '2024/07' => 'Blog/Logo images',
    '2024/10' => 'Home page images',
    '2025/03' => 'Psychology/Eye services',
    '2025/04' => 'Dental services'
];

foreach ($check_dirs as $dir => $description) {
    $full_dir = $document_root . '/wp-content/uploads/' . $dir;
    echo "<h4>$description ($dir):</h4>\n";

    if (is_dir($full_dir)) {
        $files = glob($full_dir . '/*.{webp,png,jpg,jpeg,svg}', GLOB_BRACE);
        echo "<ul style='font-size: 12px;'>\n";
        foreach (array_slice($files, 0, 10) as $file) {
            echo "<li>" . basename($file) . "</li>\n";
        }
        if (count($files) > 10) {
            echo "<li><em>... and " . (count($files) - 10) . " more files</em></li>\n";
        }
        echo "</ul>\n";
    } else {
        echo "<p style='color: red;'>Directory doesn't exist</p>\n";
    }
}

echo "<h3>Action Plan for Remaining Files:</h3>\n";
echo "<ol>\n";
echo "<li><strong>Apply any additional redirects found above</strong></li>\n";
echo "<li><strong>For files with no matches:</strong> These references should be removed from database or replaced with existing images</li>\n";
echo "<li><strong>Consider:</strong> These might be placeholder images that were never uploaded</li>\n";
echo "<li><strong>Priority:</strong> Focus on high-reference files first (home-*.webp, dental images)</li>\n";
echo "</ol>\n";

?>
<?php
/**
 * Test SVG Analysis with Real Medical Files
 */

// Include the enhanced AI class
require_once(__DIR__ . '/demo-fallback.php');

echo "=== Real Medical SVG Analysis Test ===\n\n";

$generator = new AI_Media_Descriptor_Demo();

$svg_files = [
    '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads/2025/09/noun-injury-7344654-FFFFFF-3.svg',
    '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads/2025/09/noun-central-nervous-system-2937625-FFFFFF-3.svg',
    '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads/2025/09/noun-central-nervous-system-2937625-FFFFFF-2.svg'
];

foreach ($svg_files as $svg_file) {
    if (!file_exists($svg_file)) continue;
    
    $filename = basename($svg_file);
    echo "Analyzing: $filename\n";
    echo str_repeat("=", 60) . "\n";
    
    echo "File size: " . number_format(filesize($svg_file)) . " bytes\n";
    echo "MIME type: " . mime_content_type($svg_file) . "\n\n";
    
    // Show first few lines of SVG content for context
    echo "SVG Content Preview:\n";
    $content = file_get_contents($svg_file);
    $lines = explode("\n", $content);
    echo implode("\n", array_slice($lines, 0, 5)) . "\n...\n\n";
    
    // Analyze keywords
    echo "SVG Analysis:\n";
    $keywords = $generator->analyze_svg_content($svg_file);
    if (!empty($keywords)) {
        echo "Extracted keywords:\n";
        foreach ($keywords as $keyword) {
            echo "  ✓ $keyword\n";
        }
    } else {
        echo "  No specific medical patterns found\n";
    }
    echo "\n";
    
    // Generate final result
    echo "Generated Descriptions:\n";
    $result = $generator->generate_fallback($filename, 'image/svg+xml', $svg_file);
    echo "  Title: " . $result['title'] . "\n";
    echo "  Alt Text: " . $result['alt_text'] . "\n";
    echo "  Description: " . $result['description'] . "\n";
    
    echo "\n" . str_repeat("=", 80) . "\n\n";
}

// Also test with some PNG files for comparison
echo "=== PNG File Comparison ===\n\n";

$png_files = glob('/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads/**/*.png');
if (!empty($png_files)) {
    $png_file = $png_files[0];
    $filename = basename($png_file);
    
    echo "Analyzing PNG: $filename\n";
    echo str_repeat("-", 40) . "\n";
    echo "File size: " . number_format(filesize($png_file)) . " bytes\n";
    echo "MIME type: " . mime_content_type($png_file) . "\n\n";
    
    $result = $generator->generate_fallback($filename, mime_content_type($png_file), $png_file);
    echo "Generated Descriptions:\n";
    echo "  Title: " . $result['title'] . "\n";
    echo "  Alt Text: " . $result['alt_text'] . "\n";
    echo "  Description: " . $result['description'] . "\n";
}
?>
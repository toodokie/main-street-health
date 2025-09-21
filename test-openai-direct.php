<?php
/**
 * Direct OpenAI Vision API Test
 */

// Load WordPress
require_once('/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-load.php');

// Load the AI script
require_once('/Users/anastasiavolkova/Local Sites/main-street-health/ai-media-descriptions.php');

// Create generator instance
$generator = new AI_Media_Descriptor();

echo "=== OpenAI Vision API Test ===\n";
echo "OpenAI API Key configured: " . (defined('OPENAI_API_KEY') ? '✓ Yes' : '✗ No') . "\n\n";

// Test with first unprocessed non-SVG image
$args = [
    'post_type' => 'attachment',
    'post_mime_type' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
    'posts_per_page' => 1,
    'post_status' => 'inherit',
    'meta_query' => [
        [
            'key' => '_ai_generated_method',
            'compare' => 'NOT EXISTS'
        ]
    ]
];

$attachments = get_posts($args);

if (!empty($attachments)) {
    $attachment = $attachments[0];
    echo "Testing with: " . $attachment->post_title . "\n";
    echo "File: " . basename(get_attached_file($attachment->ID)) . "\n";
    echo "MIME: " . $attachment->post_mime_type . "\n";
    
    $image_url = wp_get_attachment_url($attachment->ID);
    echo "Image URL: " . $image_url . "\n\n";
    
    // Check file path and existence
    $upload_dir = wp_upload_dir();
    $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);
    echo "File path: " . $file_path . "\n";
    echo "File exists: " . (file_exists($file_path) ? "✓ Yes" : "✗ No") . "\n";
    if (file_exists($file_path)) {
        echo "File size: " . number_format(filesize($file_path)) . " bytes\n";
    }
    echo "\n";
    
    echo "Processing with OpenAI Vision...\n";
    $openai_result = $generator->generate_with_openai($image_url, 'Main Street Health medical website');
    
    if ($openai_result) {
        echo "✓ OpenAI SUCCESS!\n";
        echo "Title: " . $openai_result['title'] . "\n";
        echo "Alt: " . $openai_result['alt_text'] . "\n";
        echo "Description: " . $openai_result['description'] . "\n";
    } else {
        echo "❌ OpenAI failed - trying fallback method\n";
        $fallback_result = $generator->generate_fallback(basename(get_attached_file($attachment->ID)), $attachment->post_mime_type, $file_path);
        echo "Fallback Title: " . $fallback_result['title'] . "\n";
        echo "Fallback Alt: " . $fallback_result['alt_text'] . "\n";
        echo "Fallback Description: " . $fallback_result['description'] . "\n";
    }
    
} else {
    echo "No unprocessed non-SVG images found!\n";
    
    // Try to find ANY supported image
    $args['meta_query'] = []; // Remove the NOT EXISTS filter
    $attachments = get_posts($args);
    
    if (!empty($attachments)) {
        echo "\nFound processed images - testing with first available:\n";
        $attachment = $attachments[0];
        echo "Testing with: " . $attachment->post_title . "\n";
        echo "File: " . basename(get_attached_file($attachment->ID)) . "\n";
        echo "MIME: " . $attachment->post_mime_type . "\n";
        
        $image_url = wp_get_attachment_url($attachment->ID);
        echo "Image URL: " . $image_url . "\n\n";
        
        echo "Processing with OpenAI Vision...\n";
        $openai_result = $generator->generate_with_openai($image_url, 'Main Street Health medical website');
        
        if ($openai_result) {
            echo "✓ OpenAI SUCCESS!\n";
            echo "Title: " . $openai_result['title'] . "\n";
            echo "Alt: " . $openai_result['alt_text'] . "\n";
            echo "Description: " . $openai_result['description'] . "\n";
        } else {
            echo "❌ OpenAI failed\n";
        }
    } else {
        echo "No supported images found at all!\n";
    }
}
?>
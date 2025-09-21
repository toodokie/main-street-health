<?php
/**
 * Test AI Media Descriptions
 * Access this file directly in your browser after starting Local
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is logged in as admin
if (!current_user_can('manage_options')) {
    die('Please login as admin first');
}

// Load the AI script
require_once('/Users/anastasiavolkova/Local Sites/main-street-health/ai-media-descriptions.php');

// Create generator instance
$generator = new AI_Media_Descriptor();

echo "<h1>AI Media Description Test</h1>";
echo "<p>OpenAI API Key configured: " . (defined('OPENAI_API_KEY') ? '✓ Yes' : '✗ No') . "</p>";

// Test with first unprocessed image (exclude SVG for OpenAI testing)
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
    echo "<h2>Testing with: " . $attachment->post_title . "</h2>";
    echo "<img src='" . wp_get_attachment_url($attachment->ID) . "' style='max-width: 300px;'><br><br>";
    
    echo "<p>Processing...</p>";
    flush();
    
    // Debug: Test API directly first
    $image_url = wp_get_attachment_url($attachment->ID);
    echo "<p><strong>Image URL:</strong> " . $image_url . "</p>";
    
    // Test OpenAI call
    echo "<p>Testing OpenAI API...</p>";
    echo "<p>Checking if URL is local... ";
    if (strpos($image_url, '.local/') !== false) {
        echo "YES - will use base64 method</p>";
        
        // Test file path resolution
        $parsed_url = parse_url($image_url);
        $file_path1 = $_SERVER['DOCUMENT_ROOT'] . $parsed_url['path'];
        echo "<p>Trying path 1: $file_path1 - " . (file_exists($file_path1) ? "EXISTS" : "NOT FOUND") . "</p>";
        
        $upload_dir = wp_upload_dir();
        $file_path2 = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);
        echo "<p>Trying path 2: $file_path2 - " . (file_exists($file_path2) ? "EXISTS" : "NOT FOUND") . "</p>";
        
        if (file_exists($file_path2)) {
            $file_size = filesize($file_path2);
            echo "<p>File size: " . number_format($file_size) . " bytes</p>";
            echo "<p>File type: " . mime_content_type($file_path2) . "</p>";
        }
    } else {
        echo "NO - will use URL method</p>";
    }
    flush();
    
    $openai_result = $generator->generate_with_openai($image_url, 'Main Street Health medical website');
    
    if ($openai_result) {
        echo "<h3>✓ OpenAI Success!</h3>";
        echo "<p><strong>OpenAI Title:</strong> " . $openai_result['title'] . "</p>";
        echo "<p><strong>OpenAI Alt:</strong> " . $openai_result['alt_text'] . "</p>";
        echo "<p><strong>OpenAI Description:</strong> " . $openai_result['description'] . "</p>";
    } else {
        echo "<p>❌ OpenAI failed - checking why...</p>";
        
        // Check API key
        echo "<p>API Key starts with: " . substr(OPENAI_API_KEY, 0, 15) . "...</p>";
        
        // Test basic API call
        $test_response = wp_remote_get('https://api.openai.com/v1/models', [
            'headers' => [
                'Authorization' => 'Bearer ' . OPENAI_API_KEY,
            ],
            'timeout' => 10
        ]);
        
        if (is_wp_error($test_response)) {
            echo "<p>❌ API Connection Error: " . $test_response->get_error_message() . "</p>";
        } else {
            $status = wp_remote_retrieve_response_code($test_response);
            echo "<p>API Status Code: " . $status . "</p>";
            if ($status !== 200) {
                $body = wp_remote_retrieve_body($test_response);
                echo "<p>API Error: " . $body . "</p>";
            } else {
                echo "<p>✓ API connection works!</p>";
            }
        }
    }
    
    echo "<hr>";
    
    $result = $generator->process_attachment($attachment->ID);
    
    if ($result) {
        echo "<h3>Final Result:</h3>";
        echo "<p><strong>Title:</strong> " . $result['title'] . "</p>";
        echo "<p><strong>Alt Text:</strong> " . $result['alt_text'] . "</p>";
        echo "<p><strong>Description:</strong> " . $result['description'] . "</p>";
        echo "<p><strong>Method Used:</strong> " . $result['method'] . "</p>";
    } else {
        echo "<p>❌ Failed to generate description</p>";
    }
} else {
    echo "<p>No unprocessed images found!</p>";
}

echo "<hr>";
echo "<p><a href='#' onclick='location.reload()'>Test Another Image</a></p>";
?>
<?php
/**
 * Process images with enhanced fallback system
 * Since OpenAI quota is exceeded, use intelligent fallback
 */

// Load WordPress (try to use the working directory)
$wp_load_paths = [
    '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-load.php',
    __DIR__ . '/app/public/wp-load.php'
];

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die("WordPress not found. Please run from the correct directory.\n");
}

// Load the AI script
require_once(__DIR__ . '/ai-media-descriptions.php');

// Create generator instance
$generator = new AI_Media_Descriptor();

echo "=== AI Media Description Generator (Fallback Mode) ===\n";
echo "Processing with enhanced fallback due to API quota limitations\n\n";

// Get all unprocessed images
$args = [
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => 10, // Process 10 at a time
    'post_status' => 'inherit',
    'meta_query' => [
        [
            'key' => '_ai_generated_method',
            'compare' => 'NOT EXISTS'
        ]
    ]
];

$attachments = get_posts($args);

if (empty($attachments)) {
    echo "No unprocessed images found!\n\n";
    
    // Show statistics
    $total_images = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'post_status' => 'inherit',
        'fields' => 'ids'
    ]);
    
    $processed_images = get_posts([
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'post_status' => 'inherit',
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => '_ai_generated_method',
                'compare' => 'EXISTS'
            ]
        ]
    ]);
    
    echo "Total images: " . count($total_images) . "\n";
    echo "Already processed: " . count($processed_images) . "\n";
    exit;
}

echo "Found " . count($attachments) . " images to process\n\n";

$processed = 0;
$sample_results = [];

foreach ($attachments as $attachment) {
    $filename = basename(get_attached_file($attachment->ID));
    echo "Processing: $filename\n";
    
    // Get file path for enhanced analysis
    $upload_dir = wp_upload_dir();
    $file_url = wp_get_attachment_url($attachment->ID);
    $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $file_url);
    
    // Process with enhanced fallback
    $result = $generator->generate_fallback($filename, $attachment->post_mime_type, $file_path);
    
    if ($result) {
        // Update WordPress attachment
        wp_update_post([
            'ID' => $attachment->ID,
            'post_title' => $result['title'],
            'post_content' => $result['description']
        ]);
        
        // Update alt text
        update_post_meta($attachment->ID, '_wp_attachment_image_alt', $result['alt_text']);
        
        // Mark as processed with method
        update_post_meta($attachment->ID, '_ai_generated_method', 'enhanced_fallback');
        update_post_meta($attachment->ID, '_ai_generated_date', current_time('mysql'));
        
        $processed++;
        
        // Store sample results
        if (count($sample_results) < 5) {
            $sample_results[] = [
                'filename' => $filename,
                'title' => $result['title'],
                'alt_text' => $result['alt_text'],
                'description' => $result['description']
            ];
        }
        
        echo "✓ Success\n";
    } else {
        echo "❌ Failed\n";
    }
    
    echo "\n";
}

echo "=== Processing Complete ===\n";
echo "Processed: $processed images\n\n";

if (!empty($sample_results)) {
    echo "Sample Results:\n";
    echo str_repeat("=", 50) . "\n";
    
    foreach ($sample_results as $sample) {
        echo "File: " . $sample['filename'] . "\n";
        echo "Title: " . $sample['title'] . "\n";
        echo "Alt Text: " . $sample['alt_text'] . "\n";
        echo "Description: " . $sample['description'] . "\n";
        echo str_repeat("-", 30) . "\n";
    }
}
?>
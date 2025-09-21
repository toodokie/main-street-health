<?php
/**
 * Auto-Generate Media Titles and Descriptions
 * 
 * Usage: wp eval-file generate-media-titles.php
 * Or run directly in WordPress admin via plugin
 */

// Function to clean filename into readable title
function clean_filename_to_title($filename) {
    // Remove file extension
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Remove common suffixes like -scaled, -1, -2, dimensions
    $name = preg_replace('/-scaled.*$/', '', $name);
    $name = preg_replace('/-\d+x\d+/', '', $name);
    $name = preg_replace('/-\d+$/', '', $name);
    
    // Replace hyphens and underscores with spaces
    $name = str_replace(['-', '_'], ' ', $name);
    
    // Capitalize words
    $name = ucwords(strtolower($name));
    
    // Clean up multiple spaces
    $name = preg_replace('/\s+/', ' ', $name);
    
    return trim($name);
}

// Function to generate description based on title
function generate_description($title, $mime_type) {
    $type = strpos($mime_type, 'image') !== false ? 'Image' : 'Media';
    
    // Common medical/healthcare keywords for your site
    $healthcare_terms = ['Doctor', 'Patient', 'Medical', 'Health', 'Care', 'Treatment', 'Service', 'Team', 'Staff'];
    
    // Check if title contains healthcare terms
    $is_medical = false;
    foreach ($healthcare_terms as $term) {
        if (stripos($title, $term) !== false) {
            $is_medical = true;
            break;
        }
    }
    
    if ($is_medical) {
        return "$type showing $title at Main Street Health medical facility";
    } else {
        return "$type of $title for Main Street Health website";
    }
}

// Get all media attachments without titles
$args = array(
    'post_type'      => 'attachment',
    'posts_per_page' => -1,
    'post_status'    => 'inherit',
    'meta_query'     => array(
        'relation' => 'OR',
        array(
            'key'     => '_wp_attachment_image_alt',
            'compare' => 'NOT EXISTS'
        ),
        array(
            'key'     => '_wp_attachment_image_alt',
            'value'   => '',
            'compare' => '='
        )
    )
);

$attachments = get_posts($args);

echo "Found " . count($attachments) . " media items to process...\n\n";

$updated = 0;
$sample_output = [];

foreach ($attachments as $attachment) {
    $filename = basename(get_attached_file($attachment->ID));
    $generated_title = clean_filename_to_title($filename);
    $generated_description = generate_description($generated_title, $attachment->post_mime_type);
    
    // Update post title if empty
    if (empty($attachment->post_title) || $attachment->post_title == $filename) {
        wp_update_post(array(
            'ID'         => $attachment->ID,
            'post_title' => $generated_title,
            'post_content' => $generated_description,
        ));
    }
    
    // Update alt text
    update_post_meta($attachment->ID, '_wp_attachment_image_alt', $generated_title);
    
    $updated++;
    
    // Store first 5 for sample output
    if (count($sample_output) < 5) {
        $sample_output[] = [
            'original' => $filename,
            'title' => $generated_title,
            'description' => $generated_description
        ];
    }
}

echo "Updated $updated media items!\n\n";
echo "Sample results:\n";
foreach ($sample_output as $sample) {
    echo "Original: " . $sample['original'] . "\n";
    echo "Title: " . $sample['title'] . "\n";
    echo "Description: " . $sample['description'] . "\n\n";
}

// Additional SEO-friendly updates
if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success("Media titles and descriptions generated successfully!");
}
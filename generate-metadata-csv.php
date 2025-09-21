<?php
/**
 * Generate SEO Metadata CSV - No Database Required
 * Creates CSV file that can be imported later
 */

require_once(__DIR__ . '/production-batch-processor.php');

class CSV_Metadata_Generator extends Production_AI_Media_Processor {
    
    /**
     * Get original images only
     */
    public function get_original_images() {
        $upload_base = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads';
        
        $all_files = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($upload_base)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
                    $filename = $file->getFilename();
                    
                    // Skip WordPress generated sizes
                    if (!preg_match('/-\d+x\d+\.(jpg|jpeg|png|webp|svg)$/i', $filename) &&
                        !preg_match('/-(scaled|medium|large|thumbnail|150x150|300x300|80x70|120x104)/i', $filename)) {
                        $all_files[] = $file->getRealPath();
                    }
                }
            }
        }
        
        return $all_files;
    }
    
    /**
     * Use enhanced fallback for all images
     */
    public function process_with_fallback_only($image_path) {
        if (!file_exists($image_path)) {
            return false;
        }
        
        $filename = basename($image_path);
        $mime_type = mime_content_type($image_path);
        
        return $this->generate_fallback($filename, $mime_type, $image_path);
    }
}

echo "=== Generate SEO Metadata for All Images ===\n";
echo "This creates a CSV file with titles, alt text, and descriptions\n";
echo "No database changes - safe to run!\n\n";

$processor = new CSV_Metadata_Generator();
$original_files = $processor->get_original_images();

echo "Found " . count($original_files) . " original images\n";
echo "Processing all images to generate SEO metadata...\n\n";

$csv_data = [];
$csv_data[] = ['Filename', 'Original Path', 'SEO Title', 'Alt Text', 'Description', 'Method', 'File Size', 'MIME Type'];

$processed_count = 0;
$start_time = time();

echo "Processing images:\n";
echo str_repeat("=", 50) . "\n";

foreach ($original_files as $file_path) {
    $filename = basename($file_path);
    
    $result = $processor->process_with_fallback_only($file_path);
    
    if ($result) {
        $relative_path = str_replace('/Users/anastasiavolkova/Local Sites/main-street-health/app/public/', '', $file_path);
        $file_size = number_format(filesize($file_path));
        $mime_type = mime_content_type($file_path);
        
        $csv_data[] = [
            $filename,
            $relative_path,
            $result['title'],
            $result['alt_text'],
            $result['description'],
            $result['method'],
            $file_size . ' bytes',
            $mime_type
        ];
        
        echo "✓ $filename\n";
        $processed_count++;
        
        // Progress update every 50 files
        if ($processed_count % 50 === 0) {
            $elapsed = time() - $start_time;
            $rate = $processed_count / max(1, $elapsed);
            $eta_minutes = round((count($original_files) - $processed_count) / max(1, $rate) / 60);
            $percent = round(($processed_count / count($original_files)) * 100, 1);
            
            echo "  Progress: $processed_count/" . count($original_files) . " ($percent%) - ETA: {$eta_minutes}m\n";
        }
    } else {
        echo "❌ Failed: $filename\n";
    }
}

// Save CSV file
$csv_filename = 'seo_metadata_' . date('Y-m-d_H-i-s') . '.csv';
$csv_file = fopen(__DIR__ . '/' . $csv_filename, 'w');

foreach ($csv_data as $row) {
    fputcsv($csv_file, $row);
}

fclose($csv_file);

$total_time = time() - $start_time;

echo "\n" . str_repeat("=", 50) . "\n";
echo "PROCESSING COMPLETE!\n";
echo "Processed: $processed_count images\n";
echo "Total time: " . gmdate("H:i:s", $total_time) . "\n";
echo "CSV saved to: $csv_filename\n\n";

echo "Next steps:\n";
echo "1. Open the CSV file to review the generated metadata\n";
echo "2. Import the data to WordPress using a plugin like 'WP All Import'\n";
echo "3. Or manually update key images through the WordPress media library\n\n";

// Show sample results
echo "Sample Results:\n";
echo str_repeat("-", 40) . "\n";
$sample_rows = array_slice($csv_data, 1, 5);
foreach ($sample_rows as $row) {
    echo "File: " . $row[0] . "\n";
    echo "Title: " . $row[2] . "\n";
    echo "Alt: " . $row[3] . "\n";
    echo "Description: " . $row[4] . "\n";
    echo str_repeat("-", 30) . "\n";
}

echo "\nTotal images processed: $processed_count\n";
echo "CSV file ready for import: $csv_filename\n";
?>
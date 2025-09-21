<?php
/**
 * Process Original Images Only - Skip WordPress Generated Sizes
 */

require_once(__DIR__ . '/production-batch-processor.php');

class Smart_Original_Processor extends Production_AI_Media_Processor {
    
    /**
     * Check if this is likely an original image (not a WordPress generated size)
     */
    private function is_original_image($filename) {
        // Skip files with size suffixes like -300x200, -768x576, etc.
        if (preg_match('/-\d+x\d+\.(jpg|jpeg|png|webp|svg)$/i', $filename)) {
            return false;
        }
        
        // Skip files with common WordPress size suffixes
        $wordpress_sizes = ['-scaled', '-medium', '-large', '-thumbnail', '-150x150', '-300x300', '-80x70', '-120x104'];
        foreach ($wordpress_sizes as $size) {
            if (strpos($filename, $size) !== false) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get only original images
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
                    
                    // Only include original images
                    if ($this->is_original_image($filename)) {
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

echo "=== Processing ORIGINAL Images Only ===\n";
echo "This skips WordPress-generated thumbnails and sizes\n";
echo "Processing only the actual uploaded original images\n\n";

$processor = new Smart_Original_Processor();
$original_files = $processor->get_original_images();

echo "Found " . count($original_files) . " original images (vs 3,673 total files)\n";
echo "This should be close to your 606 media library count\n\n";

// Show first 10 files as sample
echo "Sample original files:\n";
for ($i = 0; $i < min(10, count($original_files)); $i++) {
    echo "  " . basename($original_files[$i]) . "\n";
}
echo "\n";

echo "Continue processing " . count($original_files) . " original images? (y/N): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
fclose($handle);

if (trim(strtolower($line)) !== 'y') {
    echo "Cancelled.\n";
    exit;
}

$total_images = count($original_files);
$processed_count = 0;
$failed_count = 0;
$start_time = time();

echo "\nProcessing original images...\n";
echo str_repeat("=", 50) . "\n";

foreach ($original_files as $file_path) {
    $filename = basename($file_path);
    
    $result = $processor->process_with_fallback_only($file_path);
    
    if ($result) {
        echo "✓ $filename\n";
        $processed_count++;
        
        // Log the result
        $log_entry = date('Y-m-d H:i:s') . " | " . $filename . " | " . $result['method'] . " | " . $result['title'] . "\n";
        file_put_contents(__DIR__ . '/original_images_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
    } else {
        echo "❌ Failed: $filename\n";
        $failed_count++;
    }
    
    // Progress update every 50 files
    if (($processed_count + $failed_count) % 50 === 0) {
        $current = $processed_count + $failed_count;
        $percent = round(($current / $total_images) * 100, 1);
        $elapsed = time() - $start_time;
        $rate = $current / max(1, $elapsed);
        $eta_minutes = round(($total_images - $current) / max(1, $rate) / 60);
        
        echo "  Progress: $current/$total_images ($percent%) - ETA: {$eta_minutes}m\n";
    }
}

$total_time = time() - $start_time;

echo "\n" . str_repeat("=", 50) . "\n";
echo "PROCESSING COMPLETE!\n";
echo "Processed: $processed_count images\n";
echo "Failed: $failed_count images\n";
echo "Total time: " . gmdate("H:i:s", $total_time) . "\n";
echo "Cost: $0.00 (enhanced fallback)\n";
echo "Log saved to: original_images_log.txt\n";

// Show sample results
if (file_exists(__DIR__ . '/original_images_log.txt')) {
    echo "\nSample results:\n";
    $log_lines = file(__DIR__ . '/original_images_log.txt');
    $sample_lines = array_slice($log_lines, 0, 5);
    foreach ($sample_lines as $line) {
        $parts = explode(' | ', trim($line));
        if (count($parts) >= 4) {
            echo "  " . $parts[1] . " → " . $parts[3] . "\n";
        }
    }
}
?>
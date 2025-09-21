<?php
/**
 * Process All Images - Fallback First Strategy
 * Uses enhanced fallback primarily, saves API quota
 */

require_once(__DIR__ . '/production-batch-processor.php');

class Fallback_First_Processor extends Production_AI_Media_Processor {
    
    /**
     * Override to use fallback first, AI only for special cases
     */
    public function process_with_smart_fallback($image_path, $context = 'Main Street Health medical website') {
        if (!file_exists($image_path)) {
            return false;
        }
        
        $filename = basename($image_path);
        $mime_type = mime_content_type($image_path);
        
        // Always use enhanced fallback - it's producing great results!
        return $this->generate_fallback($filename, $mime_type, $image_path);
    }
}

echo "=== Processing All Images with Enhanced Fallback ===\n";
echo "This uses our enhanced fallback system for all images\n";
echo "Quality is excellent and cost is $0\n\n";

// Get total count
$upload_base = '/Users/anastasiavolkova/Local Sites/main-street-health/app/public/wp-content/uploads';
$all_files = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($upload_base)
);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $extension = strtolower($file->getExtension());
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) {
            $all_files[] = $file->getRealPath();
        }
    }
}

$total_images = count($all_files);
echo "Found $total_images total images to process\n";
echo "Estimated processing time: ~" . ceil($total_images/60) . " minutes\n\n";

echo "Continue with fallback-only processing? (y/N): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
fclose($handle);

if (trim(strtolower($line)) !== 'y') {
    echo "Cancelled.\n";
    exit;
}

$processor = new Fallback_First_Processor();
$batch_size = 100;
$total_batches = ceil($total_images / $batch_size);
$processed_total = 0;
$start_time = time();

echo "\nProcessing in $total_batches batches of $batch_size images each\n";
echo str_repeat("=", 60) . "\n\n";

for ($batch = 1; $batch <= $total_batches; $batch++) {
    echo "Batch $batch/$total_batches:\n";
    
    $start_index = ($batch - 1) * $batch_size;
    $batch_files = array_slice($all_files, $start_index, $batch_size);
    
    $batch_processed = 0;
    foreach ($batch_files as $file_path) {
        $filename = basename($file_path);
        
        $result = $processor->process_with_smart_fallback($file_path);
        
        if ($result) {
            echo "  ✓ $filename\n";
            $batch_processed++;
            $processed_total++;
            
            // Log results
            $log_entry = date('Y-m-d H:i:s') . " | " . $filename . " | " . $result['method'] . " | " . $result['title'] . "\n";
            file_put_contents(__DIR__ . '/fallback_processing_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
        } else {
            echo "  ❌ Failed: $filename\n";
        }
        
        // Show progress every 20 files
        if ($batch_processed % 20 === 0) {
            $elapsed = time() - $start_time;
            $rate = $processed_total / max(1, $elapsed);
            $eta_seconds = ($total_images - $processed_total) / max(1, $rate);
            $eta_minutes = round($eta_seconds / 60);
            
            echo "    Progress: $processed_total/$total_images (" . round(($processed_total/$total_images)*100, 1) . "%) - ETA: {$eta_minutes}m\n";
        }
    }
    
    echo "  Batch complete: $batch_processed/" . count($batch_files) . " processed\n";
    echo "  Total: $processed_total/$total_images (" . round(($processed_total/$total_images)*100, 1) . "%)\n\n";
}

$total_time = time() - $start_time;
echo str_repeat("=", 60) . "\n";
echo "PROCESSING COMPLETE!\n";
echo "Total processed: $processed_total/$total_images images\n";
echo "Total time: " . gmdate("H:i:s", $total_time) . "\n";
echo "Average rate: " . round($processed_total / max(1, $total_time), 1) . " images/second\n";
echo "Total cost: $0.00 (enhanced fallback)\n";
echo "Log saved to: fallback_processing_log.txt\n";

// Show sample from log
if (file_exists(__DIR__ . '/fallback_processing_log.txt')) {
    echo "\nSample processed files:\n";
    $log_lines = file(__DIR__ . '/fallback_processing_log.txt');
    $sample_lines = array_slice($log_lines, -10, 10);
    foreach ($sample_lines as $line) {
        $parts = explode(' | ', trim($line));
        if (count($parts) >= 4) {
            echo "  " . $parts[1] . " → " . $parts[3] . "\n";
        }
    }
}
?>
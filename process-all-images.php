<?php
/**
 * Process All Images - Large Scale Batch Processor
 * Processes images in batches with progress tracking
 */

// Include the production processor
require_once(__DIR__ . '/production-batch-processor.php');

echo "=== Large Scale AI Media Description Processing ===\n";
echo "This will process all images in batches of 50\n";
echo "Cost estimate: ~$0.01 per image for OpenAI Vision\n\n";

$processor = new Production_AI_Media_Processor();

// Get total count first
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

// Ask for confirmation
echo "\nThis will process all images. Continue? (y/N): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
fclose($handle);

if (trim(strtolower($line)) !== 'y') {
    echo "Cancelled.\n";
    exit;
}

$batch_size = 50;
$total_batches = ceil($total_images / $batch_size);
$processed_total = 0;

echo "\nProcessing in $total_batches batches of $batch_size images each\n";
echo str_repeat("=", 60) . "\n\n";

for ($batch = 1; $batch <= $total_batches; $batch++) {
    echo "Batch $batch/$total_batches:\n";
    
    // Create a custom processor for this batch
    $batch_processor = new Production_AI_Media_Processor();
    
    // Get slice of files for this batch
    $start_index = ($batch - 1) * $batch_size;
    $batch_files = array_slice($all_files, $start_index, $batch_size);
    
    // Process this batch
    $batch_processed = 0;
    foreach ($batch_files as $file_path) {
        $filename = basename($file_path);
        echo "  Processing: $filename... ";
        
        $result = $batch_processor->process_with_openai($file_path);
        
        if ($result) {
            echo "✓ " . $result['method'] . "\n";
            $batch_processed++;
            $processed_total++;
            
            // Save results to a log file
            $log_entry = date('Y-m-d H:i:s') . " | " . $filename . " | " . $result['method'] . " | " . $result['title'] . "\n";
            file_put_contents(__DIR__ . '/ai_processing_log.txt', $log_entry, FILE_APPEND | LOCK_EX);
        } else {
            echo "❌ Failed\n";
        }
        
        // Rate limiting for OpenAI requests
        if ($result && $result['method'] === 'openai_vision') {
            usleep(150000); // 0.15 second pause
        }
    }
    
    echo "  Batch complete: $batch_processed/" . count($batch_files) . " processed\n";
    echo "  Total progress: $processed_total/$total_images (" . round(($processed_total/$total_images)*100, 1) . "%)\n";
    
    // Longer pause between batches
    if ($batch < $total_batches) {
        echo "  Pausing 2 seconds before next batch...\n\n";
        sleep(2);
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "PROCESSING COMPLETE!\n";
echo "Total processed: $processed_total/$total_images images\n";

// Show cost estimate
$openai_cost = $processed_total * 0.01;
echo "Estimated cost: ~$" . number_format($openai_cost, 2) . "\n";

echo "\nLog file saved: ai_processing_log.txt\n";

// Show final summary
if (file_exists(__DIR__ . '/ai_processing_log.txt')) {
    echo "\nLast 5 processed files:\n";
    $log_lines = file(__DIR__ . '/ai_processing_log.txt');
    $last_lines = array_slice($log_lines, -5);
    foreach ($last_lines as $line) {
        echo "  " . trim($line) . "\n";
    }
}
?>
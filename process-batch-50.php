<?php
/**
 * Process 50 Images - Medium Scale Test
 */

require_once(__DIR__ . '/production-batch-processor.php');

echo "=== Processing 50 Images ===\n";
echo "This will process 50 images to test the system at scale\n";
echo "Estimated cost: ~$0.50\n\n";

$processor = new Production_AI_Media_Processor();
$processor->process_batch(50);

echo "\nIf this works well, you can run process-all-images.php for the full batch\n";
?>
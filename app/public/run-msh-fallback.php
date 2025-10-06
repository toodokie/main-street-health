<?php
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

require_once __DIR__ . '/wp-load.php';

$index = MSH_Image_Usage_Index::get_instance();

$result = $index->run_full_fallback_sweep(false);

printf("Attempted: %d\nRecovered: %d\nRemaining: %d\n", $result['attempted'], $result['recovered'], $result['remaining']);

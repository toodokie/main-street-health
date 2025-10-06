<?php
/**
 * Emergency Fix: Clear corrupted WP-Cron events
 * Run this once to fix the cron corruption causing 55k+ error messages
 *
 * Usage: Visit https://main-street-health.local/fix-cron-corruption.php in browser
 * Then DELETE THIS FILE for security
 */

// Load WordPress
require_once __DIR__ . '/wp-load.php';

if (!current_user_can('manage_options')) {
    die('Unauthorized');
}

echo "<h1>WP-Cron Corruption Fix</h1>\n";
echo "<pre>\n";

// Get current cron array
$cron_array = _get_cron_array();

echo "Current cron events: " . count($cron_array, COUNT_RECURSIVE) . "\n\n";

// Count msh_cleanup_rename_backup events
$backup_cleanup_count = 0;
foreach ($cron_array as $timestamp => $events) {
    if (isset($events['msh_cleanup_rename_backup'])) {
        $backup_cleanup_count += count($events['msh_cleanup_rename_backup']);
    }
}

echo "Found {$backup_cleanup_count} msh_cleanup_rename_backup events\n\n";

// Clear all msh_cleanup_rename_backup events
$cleared = 0;
foreach ($cron_array as $timestamp => $events) {
    if (isset($events['msh_cleanup_rename_backup'])) {
        foreach ($events['msh_cleanup_rename_backup'] as $event) {
            $args = isset($event['args']) ? $event['args'] : [];
            wp_unschedule_event($timestamp, 'msh_cleanup_rename_backup', $args);
            $cleared++;
        }
    }
}

echo "Cleared {$cleared} backup cleanup cron events\n\n";

// Verify
$cron_array_after = _get_cron_array();
echo "Remaining cron events: " . count($cron_array_after, COUNT_RECURSIVE) . "\n";

// Clear error log (move to backup)
$error_log = __DIR__ . '/../logs/php/error.log';
if (file_exists($error_log)) {
    $backup_log = __DIR__ . '/../logs/php/error.log.backup.' . date('Y-m-d-His');
    if (rename($error_log, $backup_log)) {
        echo "\nError log backed up to: " . basename($backup_log) . "\n";
        echo "Creating fresh error log...\n";
        touch($error_log);
    }
}

echo "\n✅ DONE! Cron system cleaned.\n";
echo "\n⚠️ IMPORTANT: Delete this file now for security: fix-cron-corruption.php\n";
echo "</pre>\n";

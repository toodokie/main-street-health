<?php
/**
 * MSH Safe Rename Database Setup
 * Creates the required database tables for the enhanced safe rename system
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // Load WordPress if accessed directly
    require_once dirname(__FILE__) . '/../../../wp-load.php';
}

// Ensure user is logged in and has admin privileges
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    die('Access denied');
}

echo "<h1>MSH Safe Rename Database Setup</h1>\n";

global $wpdb;

// 1. Create usage index table
$index_table = $wpdb->prefix . 'msh_image_usage_index';
$charset_collate = $wpdb->get_charset_collate();

$index_sql = "CREATE TABLE {$index_table} (
    id int(11) NOT NULL AUTO_INCREMENT,
    attachment_id int(11) NOT NULL,
    url_variation text NOT NULL,
    table_name varchar(64) NOT NULL,
    row_id int(11) NOT NULL,
    column_name varchar(64) NOT NULL,
    context_type varchar(50) DEFAULT 'content',
    post_type varchar(20) DEFAULT NULL,
    last_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY attachment_id (attachment_id),
    KEY table_row (table_name, row_id),
    KEY url_variation (url_variation(191)),
    KEY context_type (context_type)
) $charset_collate;";

// 2. Create backup table
$backup_table = $wpdb->prefix . 'msh_rename_backups';

$backup_sql = "CREATE TABLE {$backup_table} (
    id int(11) NOT NULL AUTO_INCREMENT,
    operation_id varchar(32) NOT NULL,
    attachment_id int(11) NOT NULL,
    table_name varchar(64) NOT NULL,
    row_id int(11) NOT NULL,
    column_name varchar(64) NOT NULL,
    original_value longtext NOT NULL,
    backup_date datetime DEFAULT CURRENT_TIMESTAMP,
    status varchar(20) DEFAULT 'active',
    PRIMARY KEY (id),
    KEY operation_id (operation_id),
    KEY attachment_id (attachment_id),
    KEY backup_date (backup_date)
) $charset_collate;";

// 3. Create verification table
$verification_table = $wpdb->prefix . 'msh_rename_verification';

$verification_sql = "CREATE TABLE {$verification_table} (
    id int(11) NOT NULL AUTO_INCREMENT,
    operation_id varchar(32) NOT NULL,
    attachment_id int(11) NOT NULL,
    check_type varchar(50) NOT NULL,
    expected_value text NOT NULL,
    actual_value text NOT NULL,
    status varchar(20) NOT NULL,
    error_message text NULL,
    check_date datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY operation_id (operation_id),
    KEY attachment_id (attachment_id),
    KEY status (status)
) $charset_collate;";

require_once ABSPATH . 'wp-admin/includes/upgrade.php';

echo "<h2>Creating Tables...</h2>\n";

// Create tables
$result1 = dbDelta($index_sql);
echo "<p><strong>Usage Index Table:</strong><br>" . implode("<br>", $result1) . "</p>\n";

$result2 = dbDelta($backup_sql);
echo "<p><strong>Backup Table:</strong><br>" . implode("<br>", $result2) . "</p>\n";

$result3 = dbDelta($verification_sql);
echo "<p><strong>Verification Table:</strong><br>" . implode("<br>", $result3) . "</p>\n";

// Set options to mark tables as created
update_option('msh_usage_index_table_version', '1');
update_option('msh_backup_tables_version', '1');

echo "<h2>✅ Database Setup Complete!</h2>\n";
echo "<p>The following tables have been created:</p>\n";
echo "<ul>\n";
echo "<li>{$index_table} - For fast image usage lookups</li>\n";
echo "<li>{$backup_table} - For operation backups</li>\n";
echo "<li>{$verification_table} - For operation verification</li>\n";
echo "</ul>\n";

echo "<p><strong>Next steps:</strong></p>\n";
echo "<ol>\n";
echo "<li>Go back to Media > Image Optimizer</li>\n";
echo "<li>Click 'Build Usage Index' to populate the index</li>\n";
echo "<li>Test the safe rename functionality</li>\n";
echo "</ol>\n";

echo "<p><a href='" . admin_url('media.php?page=msh-image-optimizer') . "'>→ Go to Image Optimizer</a></p>\n";
?>
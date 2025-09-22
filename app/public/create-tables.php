<?php
// Load WordPress
require_once 'wp-config.php';
require_once 'wp-load.php';

if (!current_user_can('manage_options')) {
    echo "Please log in as admin first at: " . admin_url() . "\n";
    exit;
}

global $wpdb;

echo "Creating MSH Safe Rename Database Tables...\n\n";

// 1. Usage Index Table
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

// 2. Backup Table
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

// 3. Verification Table
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

// Create tables
echo "Creating {$index_table}...\n";
$result1 = dbDelta($index_sql);
foreach ($result1 as $msg) echo "  - $msg\n";

echo "\nCreating {$backup_table}...\n";
$result2 = dbDelta($backup_sql);
foreach ($result2 as $msg) echo "  - $msg\n";

echo "\nCreating {$verification_table}...\n";
$result3 = dbDelta($verification_sql);
foreach ($result3 as $msg) echo "  - $msg\n";

// Set options
update_option('msh_usage_index_table_version', '1');
update_option('msh_backup_tables_version', '1');

echo "\n✅ Database setup complete!\n";
echo "\nNext: Go to Media > Image Optimizer and click 'Build Usage Index'\n";
echo "URL: " . admin_url('media.php?page=msh-image-optimizer') . "\n";
?>
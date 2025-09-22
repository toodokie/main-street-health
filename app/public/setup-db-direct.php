<?php
// Direct database setup
define('DB_NAME', 'local');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'localhost');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database successfully!\n\n";

    // Create usage index table
    $sql1 = "CREATE TABLE IF NOT EXISTS wp_msh_image_usage_index (
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
    )";

    $pdo->exec($sql1);
    echo "✅ Created wp_msh_image_usage_index table\n";

    // Create backup table
    $sql2 = "CREATE TABLE IF NOT EXISTS wp_msh_rename_backups (
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
    )";

    $pdo->exec($sql2);
    echo "✅ Created wp_msh_rename_backups table\n";

    // Create verification table
    $sql3 = "CREATE TABLE IF NOT EXISTS wp_msh_rename_verification (
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
    )";

    $pdo->exec($sql3);
    echo "✅ Created wp_msh_rename_verification table\n";

    // Set WordPress options
    $sql4 = "INSERT INTO wp_options (option_name, option_value) VALUES
        ('msh_usage_index_table_version', '1'),
        ('msh_backup_tables_version', '1')
        ON DUPLICATE KEY UPDATE option_value = VALUES(option_value)";

    $pdo->exec($sql4);
    echo "✅ Set WordPress options\n";

    echo "\n🎉 Database setup complete!\n";
    echo "All tables created successfully for the enhanced safe rename system.\n";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
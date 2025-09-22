<?php
/**
 * MSH Safe Rename System Test
 * Quick verification that all components are working
 */

// Load WordPress
require_once 'wp-config.php';
require_once 'wp-load.php';

if (!current_user_can('manage_options')) {
    die("Please log in as admin first\n");
}

echo "🧪 MSH Safe Rename System Test\n";
echo "================================\n\n";

// Test 1: Check if classes are loaded
echo "1️⃣ Testing Class Loading...\n";
$classes = [
    'MSH_URL_Variation_Detector',
    'MSH_Backup_Verification_System',
    'MSH_Image_Usage_Index',
    'MSH_Targeted_Replacement_Engine',
    'MSH_Safe_Rename_System'
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "   ✅ $class loaded\n";
    } else {
        echo "   ❌ $class NOT loaded\n";
    }
}

// Test 2: Check database tables
echo "\n2️⃣ Testing Database Tables...\n";
global $wpdb;
$tables = [
    'wp_msh_image_usage_index',
    'wp_msh_rename_backups',
    'wp_msh_rename_verification'
];

foreach ($tables as $table) {
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$table'");
    if ($exists) {
        echo "   ✅ $table exists\n";
    } else {
        echo "   ❌ $table missing\n";
    }
}

// Test 3: Test URL variation detection
echo "\n3️⃣ Testing URL Variation Detector...\n";
if (class_exists('MSH_URL_Variation_Detector')) {
    $detector = MSH_URL_Variation_Detector::get_instance();

    // Get a sample attachment for testing
    $sample_attachment = $wpdb->get_var("
        SELECT ID FROM {$wpdb->posts}
        WHERE post_type = 'attachment'
        AND post_mime_type LIKE 'image/%'
        LIMIT 1
    ");

    if ($sample_attachment) {
        $variations = $detector->get_all_variations($sample_attachment);
        echo "   ✅ Generated " . count($variations) . " URL variations for attachment #$sample_attachment\n";
        echo "   📝 Sample variations:\n";
        foreach (array_slice($variations, 0, 3) as $variation) {
            echo "      • $variation\n";
        }
    } else {
        echo "   ⚠️ No sample attachments found for testing\n";
    }
} else {
    echo "   ❌ URL Variation Detector not available\n";
}

// Test 4: Test targeted replacement engine
echo "\n4️⃣ Testing Targeted Replacement Engine...\n";
if (class_exists('MSH_Targeted_Replacement_Engine')) {
    $engine = MSH_Targeted_Replacement_Engine::get_instance();
    echo "   ✅ Targeted Replacement Engine initialized\n";

    if ($sample_attachment) {
        try {
            $preview = $engine->preview_changes($sample_attachment, 'old-name.jpg', 'new-name.jpg');
            if (isset($preview['total_updates'])) {
                echo "   ✅ Preview function working - would update " . $preview['total_updates'] . " locations\n";
            } else {
                echo "   ✅ Preview function working - no updates needed for test\n";
            }
        } catch (Exception $e) {
            echo "   ⚠️ Preview test error: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "   ❌ Targeted Replacement Engine not available\n";
}

// Test 5: Check WordPress options
echo "\n5️⃣ Testing System Configuration...\n";
$options = [
    'msh_usage_index_table_version' => '1',
    'msh_backup_tables_version' => '1'
];

foreach ($options as $option => $expected) {
    $value = get_option($option);
    if ($value === $expected) {
        echo "   ✅ $option = $value\n";
    } else {
        echo "   ⚠️ $option = " . ($value ?: 'not set') . " (expected: $expected)\n";
    }
}

// Final summary
echo "\n🎯 SYSTEM STATUS\n";
echo "================\n";

$all_classes = array_reduce($classes, function($carry, $class) {
    return $carry && class_exists($class);
}, true);

$all_tables = array_reduce($tables, function($carry, $table) use ($wpdb) {
    return $carry && $wpdb->get_var("SHOW TABLES LIKE '$table'");
}, true);

if ($all_classes && $all_tables) {
    echo "🟢 ALL SYSTEMS GO! Safe rename system is ready for use.\n\n";
    echo "Next steps:\n";
    echo "1. Go to Media > Image Optimizer\n";
    echo "2. Click '🚀 Build Usage Index' to activate\n";
    echo "3. Test renaming a file\n";
} else {
    echo "🟡 Some components need attention. Check the details above.\n";
}

echo "\n✨ Test completed!\n";
?>
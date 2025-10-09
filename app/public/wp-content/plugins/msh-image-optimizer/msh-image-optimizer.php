<?php
/**
 * Plugin Name: MSH Image Optimizer
 * Description: Extracted Image Optimizer tool originally embedded in the Main Street Health theme.
 * Version: 0.1.0
 * Author: Main Street Health
 * Text Domain: msh-image-optimizer
 */

if (!defined('ABSPATH')) {
    exit;
}

final class MSH_Image_Optimizer_Plugin {
    const VERSION = '0.1.0';

    private static $instance = null;

    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->define_constants();
        $this->includes();
        add_action('plugins_loaded', [$this, 'init']);
        add_action('init', [$this, 'load_textdomain']);
    }

    public function load_textdomain() {
        load_plugin_textdomain('msh-image-optimizer', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    private function define_constants() {
        if (!defined('MSH_IO_PLUGIN_FILE')) {
            define('MSH_IO_PLUGIN_FILE', __FILE__);
        }
        if (!defined('MSH_IO_PLUGIN_DIR')) {
            define('MSH_IO_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }
        if (!defined('MSH_IO_PLUGIN_URL')) {
            define('MSH_IO_PLUGIN_URL', plugin_dir_url(__FILE__));
        }
        if (!defined('MSH_IO_ASSETS_URL')) {
            define('MSH_IO_ASSETS_URL', trailingslashit(MSH_IO_PLUGIN_URL . 'assets'));
        }
    }

    private function includes() {
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-safe-rename-system.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-url-variation-detector.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-targeted-replacement-engine.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-backup-verification-system.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-hash-cache-manager.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-image-usage-index.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-content-usage-lookup.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-perceptual-hash.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-safe-rename-cli.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-media-cleanup.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-webp-delivery.php';
        require_once MSH_IO_PLUGIN_DIR . 'includes/class-msh-image-optimizer.php';
        require_once MSH_IO_PLUGIN_DIR . 'admin/image-optimizer-admin.php';
    }

    public function init() {
        if (function_exists('MSH_Safe_Rename_System::get_instance')) {
            MSH_Safe_Rename_System::get_instance();
        }
        if (class_exists('MSH_Image_Usage_Index')) {
            MSH_Image_Usage_Index::get_instance();
        }
        if (class_exists('MSH_Content_Usage_Lookup')) {
            MSH_Content_Usage_Lookup::get_instance();
        }
        // Ensure admin assets are enqueued by the admin file.
        do_action('msh_image_optimizer_plugin_loaded');
    }
}

MSH_Image_Optimizer_Plugin::instance();

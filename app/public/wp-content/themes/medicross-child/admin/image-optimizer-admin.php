<?php
/**
 * MSH Image Optimizer Admin Interface - COMPLETE ORIGINAL VERSION
 * WordPress admin interface for image optimization
 */

if (!defined('ABSPATH')) {
    exit;
}

class MSH_Image_Optimizer_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_media_page(
            'MSH Image Optimizer',
            'Image Optimizer',
            'manage_options',
            'msh-image-optimizer',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ('media_page_msh-image-optimizer' !== $hook) {
            return;
        }
        
        wp_enqueue_script(
            'msh-image-optimizer-admin',
            get_stylesheet_directory_uri() . '/assets/js/image-optimizer-admin.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // TEMPORARILY DISABLED: Enhanced UI files have syntax errors
        // Will fix after testing core functionality
        /*
        wp_enqueue_script(
            'msh-image-optimizer-rename-ui',
            get_stylesheet_directory_uri() . '/assets/js/image-optimizer-rename-ui.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'msh-image-optimizer-enhanced',
            get_stylesheet_directory_uri() . '/assets/js/image-optimizer-enhanced.js',
            array('jquery', 'msh-image-optimizer-admin', 'msh-image-optimizer-rename-ui'),
            '1.0.0',
            true
        );
        */
        
        wp_localize_script('msh-image-optimizer-admin', 'mshImageOptimizer', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('msh_image_optimizer'),
            'cleanup_nonce' => wp_create_nonce('msh_media_cleanup'),
            'strings' => array(
                'analyzing' => __('Analyzing images...', 'medicross-child'),
                'optimizing' => __('Optimizing images...', 'medicross-child'),
                'complete' => __('Optimization complete!', 'medicross-child'),
                'error' => __('An error occurred. Please try again.', 'medicross-child')
            )
        ));
        
        wp_enqueue_style(
            'msh-image-optimizer-admin',
            get_stylesheet_directory_uri() . '/assets/css/image-optimizer-admin.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Admin page content - COMPLETE ORIGINAL VERSION
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('MSH Image Optimizer', 'medicross-child'); ?></h1>
            
            <div class="msh-optimizer-dashboard">
                
                <!-- WebP Delivery Status -->
                <div class="msh-webp-status-section">
                    <h2><?php _e('WebP Delivery Status', 'medicross-child'); ?></h2>
                    <div id="webp-status-display">
                        <div class="webp-status-item">
                            <span class="status-label"><?php _e('Browser Support:', 'medicross-child'); ?></span>
                            <span id="webp-browser-support" class="status-value">Detecting...</span>
                        </div>
                        <div class="webp-status-item">
                            <span class="status-label"><?php _e('Detection Method:', 'medicross-child'); ?></span>
                            <span id="webp-detection-method" class="status-value">JavaScript + Cookie</span>
                        </div>
                        <div class="webp-status-item">
                            <span class="status-label"><?php _e('Delivery Status:', 'medicross-child'); ?></span>
                            <span id="webp-delivery-status" class="status-value">Active</span>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Overview -->
                <div class="msh-progress-section">
                    <h2><?php _e('Image Optimization Progress', 'medicross-child'); ?></h2>
                    <p style="margin-bottom: 15px; color: #666; font-size: 14px;">
                        <strong>Image Optimization:</strong> Converts images to WebP, adds ALT text, improves SEO metadata for published images.<br>
                        <strong>Duplicate Cleanup:</strong> Removes unused duplicate files to clean up media library (separate process).
                    </p>
                    <div class="progress-stats">
                        <div class="stat-box">
                            <span class="stat-number" id="total-images">-</span>
                            <span class="stat-label"><?php _e('Total Published Images', 'medicross-child'); ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number" id="optimized-images">-</span>
                            <span class="stat-label"><?php _e('Optimized', 'medicross-child'); ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number" id="remaining-images">-</span>
                            <span class="stat-label"><?php _e('Remaining', 'medicross-child'); ?></span>
                        </div>
                        <div class="stat-box">
                            <span class="stat-number" id="progress-percentage">-</span>
                            <span class="stat-label"><?php _e('Complete', 'medicross-child'); ?></span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
                        <span class="progress-percent" id="progress-percent">0%</span>
                    </div>
                    <div class="progress-status" id="progress-status">Waiting for analysis…</div>
                </div>
                
                <!-- Step 1: Image Optimization -->
                <div class="msh-actions-section">
                    <h2 style="color: #35332f;"><?php _e('Step 1: Optimize Published Images', 'medicross-child'); ?></h2>
                    <p style="margin-bottom: 15px; color: #35332f; font-size: 14px; background: #faf9f6; padding: 10px; border-radius: 4px;">
                        <strong>RECOMMENDED FIRST:</strong> Optimize your published images with WebP conversion, proper ALT text, and SEO improvements before cleaning duplicates.
                    </p>
                    <div class="action-buttons">
                        <button id="build-usage-index" class="button" style="background: #5CB3CC; color: #ffffff; border: 1px solid #5CB3CC; margin-right: 10px;">
                            <?php _e('🚀 Build Usage Index', 'medicross-child'); ?>
                        </button>
                        <button id="analyze-images" class="button" style="background: #35332f; color: #ffffff; border: 1px solid #35332f;">
                            <?php _e('Analyze Published Images', 'medicross-child'); ?>
                        </button>
                        <button id="optimize-high-priority" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f;" disabled>
                            <?php _e('Optimize High Priority (15+)', 'medicross-child'); ?>
                        </button>
                        <button id="optimize-medium-priority" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f;" disabled>
                            <?php _e('Optimize Medium Priority (10-14)', 'medicross-child'); ?>
                        </button>
                        <button id="optimize-all" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f;" disabled>
                            <?php _e('Optimize All Remaining', 'medicross-child'); ?>
                        </button>
                        <button id="apply-filename-suggestions" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f;" disabled>
                            <?php _e('Apply Filename Suggestions', 'medicross-child'); ?>
                        </button>
                        <button id="reset-optimization" class="button button-secondary" style="background: #faf9f6; color: #35332f; border: 1px solid #35332f;">
                            <?php _e('Reset Optimization Flags', 'medicross-child'); ?>
                        </button>
                    </div>
                </div>

                <!-- Results Display -->
                <div class="msh-results-section" style="display: none;">
                    <h2><?php _e('Analysis Results', 'medicross-child'); ?></h2>
                    <div class="filters-section">
                        <h3><?php _e('Filter Results:', 'medicross-child'); ?></h3>
                        <label><input type="checkbox" id="filter-high-priority" checked> <?php _e('High Priority (15+)', 'medicross-child'); ?></label>
                        <label><input type="checkbox" id="filter-medium-priority" checked> <?php _e('Medium Priority (10-14)', 'medicross-child'); ?></label>
                        <label><input type="checkbox" id="filter-low-priority" checked> <?php _e('Low Priority (0-9)', 'medicross-child'); ?></label>
                        <label><input type="checkbox" id="filter-missing-alt"> <?php _e('Missing ALT Text', 'medicross-child'); ?></label>
                        <label><input type="checkbox" id="filter-no-webp"> <?php _e('No WebP', 'medicross-child'); ?></label>
                    </div>
                    
                    <div class="bulk-actions">
                        <label><input type="checkbox" id="select-all"> <?php _e('Select All', 'medicross-child'); ?></label>
                        <button id="optimize-selected" class="button" disabled><?php _e('Optimize Selected', 'medicross-child'); ?></button>
                    </div>
                    
                    <table class="results-table" id="results-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-header"></th>
                                <th><?php _e('Image', 'medicross-child'); ?></th>
                                <th><?php _e('Filename', 'medicross-child'); ?></th>
                                <th><?php _e('Priority', 'medicross-child'); ?></th>
                                <th><?php _e('Issues', 'medicross-child'); ?></th>
                                <th><?php _e('Size', 'medicross-child'); ?></th>
                                <th><?php _e('Used In', 'medicross-child'); ?></th>
                                <th><?php _e('Actions', 'medicross-child'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="results-tbody">
                            <tr><td colspan="8" class="no-results"><?php _e('Click "Analyze Published Images" to begin analysis.', 'medicross-child'); ?></td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Step 2: Duplicate Management -->
                <div class="msh-actions-section">
                    <h2 style="color: #35332f;"><?php _e('Step 2: Clean Up Duplicate Images', 'medicross-child'); ?></h2>
                    <p style="margin-bottom: 15px; color: #35332f; font-size: 14px; background: #faf9f6; padding: 10px; border-radius: 4px;">
                        <strong>AFTER OPTIMIZATION:</strong> Find and safely remove duplicate images to free up storage space and organize your media library.
                    </p>
                    <div class="action-buttons">
                        <button id="quick-duplicate-scan" class="button" style="background: #35332f; color: #ffffff; border: 1px solid #35332f;">
                            <?php _e('Quick Duplicate Scan', 'medicross-child'); ?>
                        </button>
                        <button id="full-library-scan" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f; font-weight: 600;">
                            <?php _e('Deep Library Scan', 'medicross-child'); ?>
                        </button>
                        <button id="test-cleanup" class="button button-secondary" style="background: #faf9f6; color: #35332f; border: 1px solid #35332f; margin-left: 20px;">
                            <?php _e('Test Connection', 'medicross-child'); ?>
                        </button>
                    </div>
                </div>
                
                <!-- Processing Modal -->
                <div id="processing-modal" class="processing-modal" style="display: none;">
                    <div class="modal-content">
                        <h3 id="modal-title"><?php _e('Processing...', 'medicross-child'); ?></h3>
                        <div class="modal-spinner"></div>
                        <p id="modal-status"><?php _e('Please wait while we process your request.', 'medicross-child'); ?></p>
                        <div id="modal-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" id="modal-progress-fill" style="width: 0%"></div>
                            </div>
                            <span id="modal-progress-text">0%</span>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Optimization Log -->
            <div class="msh-log-section">
                <h2><?php _e('Optimization Log', 'medicross-child'); ?></h2>
                <div class="log-container">
                    <textarea id="optimization-log" readonly placeholder="<?php _e('Activity log will appear here...', 'medicross-child'); ?>"></textarea>
                </div>
            </div>
            
        </div>
        <?php
    }
}

// Initialize the admin interface
new MSH_Image_Optimizer_Admin();

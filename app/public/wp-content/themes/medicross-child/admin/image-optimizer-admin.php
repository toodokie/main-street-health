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
            'msh-image-optimizer-modern',
            get_stylesheet_directory_uri() . '/assets/js/image-optimizer-modern.js',
            array('jquery'),
            '2.0.1-' . time(),
            true
        );
        
        wp_localize_script('msh-image-optimizer-modern', 'mshImageOptimizer', array(
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
            '1.0.1-' . time()
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
                        <div style="display: inline-block; background: #d4edda; padding: 8px 12px; border-radius: 4px; margin-left: 10px; font-size: 13px; color: #155724;">
                            <strong>Smart Indexing:</strong> Files are indexed automatically when renamed for optimal performance
                        </div>
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

                <!-- Optimization Log -->
                <div class="msh-log-section" style="display: none;">
                    <h3><?php _e('Optimization Activity', 'medicross-child'); ?></h3>
                    <div class="log-container">
                        <textarea id="optimization-log" readonly placeholder="<?php _e('Activity log will appear here...', 'medicross-child'); ?>"></textarea>
                    </div>
                </div>

                <!-- Results Display -->
                <div class="msh-results-section" style="display: none;">
                    <h2><?php _e('Analysis Results', 'medicross-child'); ?></h2>

                    <!-- Modern Filters -->
                    <div class="filter-controls">
                        <div class="filter-group">
                            <label class="filter-label"><?php _e('Status:', 'medicross-child'); ?></label>
                            <select class="filter-control filter-select" data-filter-type="status">
                                <option value="all"><?php _e('All Images', 'medicross-child'); ?></option>
                                <option value="needs_optimization" selected><?php _e('Needs Optimization', 'medicross-child'); ?></option>
                                <option value="optimized"><?php _e('Optimized', 'medicross-child'); ?></option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?php _e('Priority:', 'medicross-child'); ?></label>
                            <select class="filter-control filter-select" data-filter-type="priority">
                                <option value="all"><?php _e('All Priorities', 'medicross-child'); ?></option>
                                <option value="high"><?php _e('High (15+)', 'medicross-child'); ?></option>
                                <option value="medium"><?php _e('Medium (10-14)', 'medicross-child'); ?></option>
                                <option value="low"><?php _e('Low (0-9)', 'medicross-child'); ?></option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?php _e('Filename:', 'medicross-child'); ?></label>
                            <select class="filter-control filter-select" data-filter-type="filename">
                                <option value="all"><?php _e('All Files', 'medicross-child'); ?></option>
                                <option value="has_suggestion"><?php _e('Has Filename Suggestion', 'medicross-child'); ?></option>
                                <option value="no_suggestion"><?php _e('No Filename Suggestion', 'medicross-child'); ?></option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label"><?php _e('Issues:', 'medicross-child'); ?></label>
                            <select class="filter-control filter-select" data-filter-type="issues">
                                <option value="all"><?php _e('All Issues', 'medicross-child'); ?></option>
                                <option value="missing_alt"><?php _e('Missing ALT Text', 'medicross-child'); ?></option>
                                <option value="no_webp"><?php _e('No WebP', 'medicross-child'); ?></option>
                                <option value="large_size"><?php _e('Large File Size', 'medicross-child'); ?></option>
                            </select>
                        </div>
                        <div class="filter-actions">
                            <span class="results-count" id="results-count">0 images</span>
                            <button id="clear-filters" class="button button-secondary"><?php _e('Clear', 'medicross-child'); ?></button>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="bulk-actions">
                        <label class="select-all-label">
                            <input type="checkbox" id="select-all" class="select-all-checkbox">
                            <?php _e('Select All', 'medicross-child'); ?>
                        </label>
                        <button id="optimize-selected" class="button" disabled><?php _e('Optimize Selected', 'medicross-child'); ?></button>
                        <span class="selected-count" id="selected-count">0 selected</span>
                    </div>

                    <!-- Results Table -->
                    <div class="results-container">
                        <table class="results-table" id="results-table">
                            <thead>
                                <tr>
                                    <th class="select-column"><input type="checkbox" id="select-all-header"></th>
                                    <th class="image-column"><?php _e('Image', 'medicross-child'); ?></th>
                                    <th class="filename-column"><?php _e('Filename', 'medicross-child'); ?></th>
                                    <th class="context-column"><?php _e('Content Category', 'medicross-child'); ?></th>
                                    <th class="status-column"><?php _e('Status', 'medicross-child'); ?></th>
                                    <th class="priority-column"><?php _e('Priority', 'medicross-child'); ?></th>
                                    <th class="size-column"><?php _e('Size', 'medicross-child'); ?></th>
                                    <th class="actions-column"><?php _e('Actions', 'medicross-child'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="results-tbody">
                                <tr class="no-results-row">
                                    <td colspan="8" class="no-results"><?php _e('Click "Analyze Published Images" to begin analysis.', 'medicross-child'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
            
        </div>
        <?php
    }

    /**
     * Get usage index statistics for display
     */
    private function get_usage_index_stats() {
        try {
            if (class_exists('MSH_Image_Usage_Index')) {
                $usage_index = MSH_Image_Usage_Index::get_instance();
                $stats = $usage_index->get_index_stats();

                if ($stats && $stats['summary'] && $stats['summary']->total_entries > 0) {
                    return [
                        'total_entries' => $stats['summary']->total_entries,
                        'unique_attachments' => $stats['summary']->indexed_attachments,
                        'last_update' => $stats['summary']->last_update
                    ];
                }
            }
        } catch (Exception $e) {
            error_log('MSH DEBUG: Error getting usage index stats: ' . $e->getMessage());
        }

        return false;
    }
}

// Initialize the admin interface
new MSH_Image_Optimizer_Admin();

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
            'The Dot Image Optimizer',
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
        
        wp_enqueue_style(
            'msh-image-optimizer-fonts',
            'https://use.typekit.net/gac6jnd.css',
            array(),
            null
        );

        if (!class_exists('MSH_Image_Usage_Index')) {
            require_once get_stylesheet_directory() . '/inc/class-msh-image-usage-index.php';
        }

        $index_summary = null;
        if (class_exists('MSH_Image_Usage_Index')) {
            $usage_index = MSH_Image_Usage_Index::get_instance();
            $stats = $usage_index->get_index_stats();
            $formatted = $usage_index->format_stats_for_ui($stats);
            if ($formatted) {
                $index_summary = $formatted;
            }
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
            'indexStats' => $index_summary,
            'strings' => array(
                'analyzing' => __('Analyzing images...', 'medicross-child'),
                'optimizing' => __('Optimizing images...', 'medicross-child'),
                'complete' => __('Optimization complete!', 'medicross-child'),
                'error' => __('An error occurred. Please try again.', 'medicross-child'),
                'indexHealthy' => __('Healthy', 'medicross-child'),
                'indexQueued' => __('Queued', 'medicross-child'),
                'indexAttention' => __('Attention', 'medicross-child'),
                'indexNotBuilt' => __('Not Built', 'medicross-child'),
                'queueWarning' => __('Background indexing in progress - attachments queued for processing', 'medicross-child'),
                'queueInfo' => __('Background refresh queued; no action needed unless jobs pile up.', 'medicross-child'),
                'orphanWarning' => __('Orphaned entries detected - references to deleted attachments', 'medicross-child'),
                'viewOrphans' => __('View Orphan List', 'medicross-child'),
                'hideOrphans' => __('Hide Orphan List', 'medicross-child'),
                'noOrphans' => __('No orphaned attachments detected.', 'medicross-child'),
                'derivedHeading' => __('Derived copies (alternate formats)', 'medicross-child'),
                'derivedInfo' => __('Alternate formats detected; these mirror another attachment.', 'medicross-child')
            )
        ));
        
        wp_enqueue_style(
            'msh-image-optimizer-admin',
            get_stylesheet_directory_uri() . '/assets/css/image-optimizer-admin.css',
            array('msh-image-optimizer-fonts'),
            '1.0.1-' . time()
        );
    }
    
    /**
     * Admin page content - COMPLETE ORIGINAL VERSION
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('The Dot Image Optimizer', 'medicross-child'); ?></h1>
            
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

                <!-- Rename Settings -->
                <div class="msh-rename-settings-section">
                    <h2><?php _e('File Rename Settings', 'medicross-child'); ?></h2>
                    <div class="rename-setting-card">
                        <div class="rename-setting-content">
                            <label class="rename-toggle-wrapper">
                                <input type="checkbox" id="enable-file-rename" class="rename-toggle-checkbox"
                                       <?php checked(get_option('msh_enable_file_rename', '0'), '1'); ?>>
                                <span class="rename-toggle-slider"></span>
                                <div class="rename-toggle-text">
                                    <strong><?php _e('Enable File Renaming', 'medicross-child'); ?></strong>
                                    <span class="rename-toggle-description">
                                        <?php _e('Allow the optimizer to rename files for better SEO. Requires usage index to prevent broken links.', 'medicross-child'); ?>
                                    </span>
                                </div>
                            </label>
                            <div id="rename-status-indicator" class="rename-status">
                                <span class="rename-status-text">
                                    <?php
                                    $rename_enabled = get_option('msh_enable_file_rename', '0') === '1';
                                    $index_built = get_option('msh_usage_index_last_build') !== false;

                                    if ($rename_enabled && $index_built) {
                                        echo '<span class="status-ready">' . __('✓ Ready for renaming', 'medicross-child') . '</span>';
                                    } elseif ($rename_enabled && !$index_built) {
                                        echo '<span class="status-pending">' . __('⚠ Index required', 'medicross-child') . '</span>';
                                    } else {
                                        echo '<span class="status-disabled">' . __('Renaming disabled', 'medicross-child') . '</span>';
                                    }
                                    ?>
                                </span>
                            </div>
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
                    <div class="index-status-card">
                        <div class="index-status-info">
                            <div class="index-health-copy">
                                <div>
                                    <span class="index-status-label"><?php _e('Usage Index:', 'medicross-child'); ?></span>
                                    <span id="index-health-badge" class="index-health-badge">Loading...</span>
                                </div>
                                <span id="index-status-summary" class="index-status-value">&mdash;</span>
                            </div>
                            <div id="index-last-updated" class="index-status-timestamp"></div>
                            <div id="index-queue-warning" class="index-queue-warning" style="display: none;"></div>
                            <div class="index-table-mix">
                                <div style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; margin-bottom: 4px;">Reference Distribution</div>
                                <div id="index-mix-bar" class="index-mix-bar">
                                    <span class="index-mix-segment posts" style="width: 33%;" title="Posts"></span>
                                    <span class="index-mix-segment meta" style="width: 33%;" title="Post Meta"></span>
                                    <span class="index-mix-segment options" style="width: 34%;" title="Options"></span>
                                </div>
                                <div class="index-mix-legend" style="display: flex; gap: 12px; margin-top: 6px; font-size: 11px;">
                                    <span><span style="display: inline-block; width: 10px; height: 10px; background: #60a5fa; border-radius: 2px; margin-right: 4px;"></span>Posts: <span id="mix-posts-count">0</span></span>
                                    <span><span style="display: inline-block; width: 10px; height: 10px; background: #34d399; border-radius: 2px; margin-right: 4px;"></span>Meta: <span id="mix-meta-count">0</span></span>
                                    <span><span style="display: inline-block; width: 10px; height: 10px; background: #fbbf24; border-radius: 2px; margin-right: 4px;"></span>Options: <span id="mix-options-count">0</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="index-status-actions">
                            <button id="trigger-incremental-refresh" class="button button-secondary">
                                <?php _e('Trigger Incremental Refresh', 'medicross-child'); ?>
                            </button>
                            <button id="rebuild-usage-index" class="button button-secondary" style="margin-left: 10px;">
                                <?php _e('Smart Build Index', 'medicross-child'); ?>
                            </button>
                            <button id="force-rebuild-usage-index" class="button button-primary" style="margin-left: 10px;">
                                <?php _e('Force Rebuild', 'medicross-child'); ?>
                            </button>
                            <button id="view-orphan-list" class="button button-secondary" style="margin-left: 10px; display: none;">
                                <?php _e('View Orphan List', 'medicross-child'); ?>
                            </button>
                            <button id="cleanup-orphans" class="button button-secondary" style="margin-left: 10px; display: none;">
                                <?php _e('Clean Orphans', 'medicross-child'); ?> <span id="orphan-chip" class="index-orphan-chip" style="display: inline-block; margin-left: 4px; padding: 2px 6px; background: #fee2e2; color: #b91c1c; border-radius: 999px; font-size: 10px; font-weight: 600;">0</span>
                            </button>
                            <div class="index-button-help" style="margin-top: 8px; font-size: 12px; color: #666;">
                                <div><strong>Trigger Incremental Refresh:</strong> Queues background lookup refresh for next cron cycle</div>
                                <div><strong>Smart Build:</strong> Only processes new/changed attachments (fast, incremental)</div>
                                <div><strong>Force Rebuild:</strong> Clears everything and rebuilds from scratch (slow, complete)</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="index-orphan-panel" class="index-orphan-list" style="display: none;"></div>
                
                <!-- Step 1: Image Optimization -->
                <div class="msh-actions-section">
                    <h2 style="color: #35332f;"><?php _e('Step 1: Optimize Published Images', 'medicross-child'); ?></h2>
                    <p style="margin-bottom: 15px; color: #35332f; font-size: 14px; background: #faf9f6; padding: 10px; border-radius: 4px;">
                        <strong>RECOMMENDED FIRST:</strong> Optimize your published images with WebP conversion, proper ALT text, and SEO improvements before cleaning duplicates.
                    </p>
                    <p class="msh-inline-note"><em><?php _e('Smart Indexing: Files are indexed automatically when renamed for optimal performance', 'medicross-child'); ?></em></p>
                    <div class="action-buttons" style="flex-wrap: wrap;">
                        <button id="analyze-images" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f;">
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
                        <span class="action-button-pair">
                        <button id="verify-webp-status" class="button" style="background: #faf9f6; color: #35332f; border: 1px solid #35332f;">
                            <?php _e('Verify WebP Status', 'medicross-child'); ?>
                        </button>
                        <button id="reset-optimization" class="button button-secondary" style="background: #faf9f6; color: #35332f; border: 1px solid #35332f;">
                            <?php _e('Reset Optimization Flags', 'medicross-child'); ?>
                        </button>
                        </span>
                    </div>
                </div>

                <!-- Step 1: Optimization Activity Log -->
                <div class="msh-log-section step1-log" style="display: none;">
                    <h3><?php _e('Optimization Activity', 'medicross-child'); ?></h3>
                    <div class="log-container">
                        <textarea id="optimization-log" readonly placeholder="<?php _e('Optimization activity will appear here...', 'medicross-child'); ?>"></textarea>
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
                        <br><br>
                        <strong>Detection Methods:</strong>
                        <span style="display: inline-block; margin-left: 8px; padding: 2px 6px; background: #e0f2fe; color: #0369a1; border-radius: 3px; font-size: 11px;">🔒 MD5</span> Exact file matches &nbsp;|&nbsp;
                        <span style="display: inline-block; padding: 2px 6px; background: #fef3c7; color: #92400e; border-radius: 3px; font-size: 11px;">👁️ Perceptual</span> Visually similar images &nbsp;|&nbsp;
                        <span style="display: inline-block; padding: 2px 6px; background: #e0e7ff; color: #4338ca; border-radius: 3px; font-size: 11px;">📝 Filename</span> Name-based matches
                    </p>
                    <div class="action-buttons">
                        <button id="visual-similarity-scan" class="button" style="background: #35332f; color: #ffffff; border: 1px solid #35332f;">
                            <?php _e('Visual Similarity Scan', 'medicross-child'); ?>
                        </button>
                        <button id="quick-duplicate-scan" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f;">
                            <?php _e('Quick Duplicate Scan', 'medicross-child'); ?>
                        </button>
                        <button id="full-library-scan" class="button" style="background: #daff00; color: #35332f; border: 1px solid #35332f;">
                            <?php _e('Deep Library Scan', 'medicross-child'); ?>
                        </button>
                        <button id="test-cleanup" class="button button-secondary" style="background: #faf9f6; color: #35332f; border: 1px solid #35332f; margin-left: 20px;">
                            <?php _e('Test Connection', 'medicross-child'); ?>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Duplicate Cleanup Activity Log -->
                <div class="msh-log-section step2-log" style="display: none;">
                    <h3><?php _e('Duplicate Cleanup Activity', 'medicross-child'); ?></h3>
                    <div class="log-container">
                        <textarea id="duplicate-log" readonly placeholder="<?php _e('Duplicate cleanup activity will appear here...', 'medicross-child'); ?>"></textarea>
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
            // Debug logging removed for production
        }

        return false;
    }
}

// Initialize the admin interface
new MSH_Image_Optimizer_Admin();

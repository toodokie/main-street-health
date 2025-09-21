<?php
/**
 * WebP Delivery Test Page
 * Template Name: WebP Test
 */

get_header();

// Get some sample images for testing
$sample_images = get_posts([
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => 5,
    'post_status' => 'inherit'
]);

?>

<div class="container" style="padding: 40px 20px;">
    <h1>WebP Delivery System Test</h1>
    
    <div class="webp-test-info" style="background: #f0f0f1; padding: 20px; margin-bottom: 30px; border-radius: 8px;">
        <h2>Browser WebP Support Status</h2>
        <div id="webp-support-status">Checking...</div>
        
        <h3>How to Test:</h3>
        <ol>
            <li>Open browser developer tools (F12)</li>
            <li>Go to Network tab</li>
            <li>Reload this page</li>
            <li>Look for image requests - WebP-supported browsers should load .webp files</li>
            <li>Non-WebP browsers will load original .jpg/.png files</li>
        </ol>
    </div>
    
    <div class="image-tests">
        <h2>Sample Images (Testing WebP Delivery)</h2>
        
        <?php if ($sample_images): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($sample_images as $image): ?>
                    <?php
                    $image_url = wp_get_attachment_url($image->ID);
                    $image_src = wp_get_attachment_image_src($image->ID, 'medium');
                    ?>
                    <div class="image-test-item" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                        <h4><?php echo esc_html($image->post_title ?: 'Untitled'); ?></h4>
                        
                        <!-- Test wp_get_attachment_image -->
                        <div class="test-method">
                            <h5>wp_get_attachment_image():</h5>
                            <?php echo wp_get_attachment_image($image->ID, 'medium', false, ['style' => 'max-width: 100%; height: auto;']); ?>
                        </div>
                        
                        <!-- Test featured image HTML -->
                        <div class="test-method" style="margin-top: 15px;">
                            <h5>Direct URL:</h5>
                            <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; height: auto;" alt="Test image">
                        </div>
                        
                        <div class="image-info" style="font-size: 12px; color: #666; margin-top: 10px;">
                            <strong>Original URL:</strong> <?php echo esc_html(basename($image_url)); ?><br>
                            <strong>Image ID:</strong> <?php echo $image->ID; ?><br>
                            <strong>MIME Type:</strong> <?php echo esc_html($image->post_mime_type); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No images found in media library.</p>
        <?php endif; ?>
    </div>
    
    <div class="technical-info" style="background: #f8f9fa; padding: 20px; margin-top: 30px; border-radius: 8px;">
        <h3>Technical Details</h3>
        <ul>
            <li><strong>WebP Detection:</strong> JavaScript + Cookie-based</li>
            <li><strong>Fallback Method:</strong> User-Agent and Accept header detection</li>
            <li><strong>Image Replacement:</strong> WordPress filters (wp_get_attachment_image_src, the_content, etc.)</li>
            <li><strong>Picture Element:</strong> Used for maximum compatibility</li>
            <li><strong>SVG Handling:</strong> SVG files are never converted (preserved as-is)</li>
        </ul>
    </div>
</div>

<script>
// WebP detection for test page
(function() {
    var webp = new Image();
    webp.onload = webp.onerror = function () {
        var supported = (webp.height == 2);
        var statusEl = document.getElementById('webp-support-status');
        
        if (supported) {
            statusEl.innerHTML = '<strong style="color: green;">✅ WebP Supported</strong> - You should see .webp files in Network tab';
            statusEl.style.background = '#d4edda';
        } else {
            statusEl.innerHTML = '<strong style="color: orange;">❌ WebP Not Supported</strong> - You will see original .jpg/.png files';
            statusEl.style.background = '#fff3cd';
        }
        
        statusEl.style.padding = '10px';
        statusEl.style.borderRadius = '4px';
        statusEl.style.border = '1px solid';
        statusEl.style.borderColor = supported ? '#c3e6cb' : '#ffeeba';
        
        // Show current cookie status
        var cookieStatus = document.cookie.match(/webp_support=([^;]+)/);
        if (cookieStatus) {
            statusEl.innerHTML += '<br><small>Cookie set: webp_support=' + cookieStatus[1] + '</small>';
        }
    };
    webp.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
})();
</script>

<?php get_footer(); ?>
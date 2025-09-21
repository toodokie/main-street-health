<?php
/**
 * Per-Product Icon Meta (pxl_product post type)
 */
if (!defined('ABSPATH')) { exit; }

// Add meta box
add_action('add_meta_boxes', function(){
    add_meta_box(
        'pxl_product_icon_meta',
        __('Product/Icon Badge', 'medicross-child'),
        function($post){
            $type = get_post_meta($post->ID, '_pxl_product_icon_type', true) ?: 'none';
            $font = get_post_meta($post->ID, '_pxl_product_icon_font', true) ?: '';
            $img  = (int) get_post_meta($post->ID, '_pxl_product_icon_img', true);
            $url  = $img ? wp_get_attachment_image_url($img, 'thumbnail') : '';
            wp_nonce_field('pxl_product_icon_meta_nonce', 'pxl_product_icon_meta_nonce'); ?>
            <p>
                <label for="_pxl_product_icon_type"><strong><?php _e('Icon Type', 'medicross-child'); ?></strong></label><br/>
                <select name="_pxl_product_icon_type" id="_pxl_product_icon_type">
                    <option value="none" <?php selected($type,'none'); ?>><?php _e('None','medicross-child'); ?></option>
                    <option value="icon" <?php selected($type,'icon'); ?>><?php _e('Font Icon','medicross-child'); ?></option>
                    <option value="image" <?php selected($type,'image'); ?>><?php _e('Image','medicross-child'); ?></option>
                </select>
            </p>
            <p>
                <label for="_pxl_product_icon_font"><strong><?php _e('Font Icon Class', 'medicross-child'); ?></strong></label><br/>
                <input type="text" class="regular-text" name="_pxl_product_icon_font" id="_pxl_product_icon_font" value="<?php echo esc_attr($font); ?>" placeholder="e.g. flaticon-heart"/>
            </p>
            <p>
                <label><strong><?php _e('Icon Image', 'medicross-child'); ?></strong></label><br/>
                <input type="hidden" name="_pxl_product_icon_img" id="_pxl_product_icon_img" value="<?php echo esc_attr($img); ?>"/>
                <button type="button" class="button" id="pxl_product_icon_img_btn"><?php _e('Select Image','medicross-child'); ?></button>
                <span id="pxl_product_icon_img_preview" style="display:inline-block;margin-left:10px;vertical-align:middle;">
                    <?php if ($url) echo '<img src="'.esc_url($url).'" style="max-width:48px;max-height:48px;"/>'; ?>
                </span>
            </p>
            <script>(function($){$(function(){var f;$('#pxl_product_icon_img_btn').on('click',function(e){e.preventDefault();if(f){f.open();return;}f=wp.media({title:'Select Icon',button:{text:'Use this icon'},multiple:false});f.on('select',function(){var a=f.state().get('selection').first().toJSON();$('#_pxl_product_icon_img').val(a.id);var u=a.sizes&&a.sizes.thumbnail?a.sizes.thumbnail.url:a.url;$('#pxl_product_icon_img_preview').html('<img src="'+u+'" style="max-width:48px;max-height:48px;"/>');});f.open();});});})(jQuery);</script>
        <?php },
        'pxl_product', 'side', 'default'
    );
});

// Save meta
add_action('save_post_pxl_product', function($post_id){
    if (!isset($_POST['pxl_product_icon_meta_nonce']) || !wp_verify_nonce($_POST['pxl_product_icon_meta_nonce'], 'pxl_product_icon_meta_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $type = isset($_POST['_pxl_product_icon_type']) ? sanitize_text_field($_POST['_pxl_product_icon_type']) : 'none';
    $font = isset($_POST['_pxl_product_icon_font']) ? sanitize_text_field($_POST['_pxl_product_icon_font']) : '';
    $img  = isset($_POST['_pxl_product_icon_img']) ? intval($_POST['_pxl_product_icon_img']) : 0;
    
    update_post_meta($post_id, '_pxl_product_icon_type', $type);
    update_post_meta($post_id, '_pxl_product_icon_font', $font);
    update_post_meta($post_id, '_pxl_product_icon_img', $img);
});


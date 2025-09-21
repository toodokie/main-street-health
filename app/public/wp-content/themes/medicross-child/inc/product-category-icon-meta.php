<?php
/**
 * Products & Devices (pxl-product-category) Icon Meta
 * Adds icon/image fields to the taxonomy and provides helpers to render them.
 */

if (!defined('ABSPATH')) { exit; }

$tax = 'pxl-product-category';

// Add fields on Add New term screen
add_action("{$tax}_add_form_fields", function() use ($tax) { ?>
    <div class="form-field">
        <label for="pxl_product_cat_icon_type"><?php _e('Icon Type', 'medicross-child'); ?></label>
        <select name="pxl_product_cat_icon_type" id="pxl_product_cat_icon_type">
            <option value="none"><?php _e('None', 'medicross-child'); ?></option>
            <option value="icon"><?php _e('Font Icon', 'medicross-child'); ?></option>
            <option value="image"><?php _e('Image', 'medicross-child'); ?></option>
        </select>
        <p class="description"><?php _e('Choose how the category icon should be rendered.', 'medicross-child'); ?></p>
    </div>
    <div class="form-field">
        <label for="pxl_product_cat_icon_font"><?php _e('Font Icon Class', 'medicross-child'); ?></label>
        <input type="text" name="pxl_product_cat_icon_font" id="pxl_product_cat_icon_font" placeholder="e.g. flaticon-heart" />
    </div>
    <div class="form-field">
        <label for="pxl_product_cat_icon_img"><?php _e('Icon Image', 'medicross-child'); ?></label>
        <input type="hidden" name="pxl_product_cat_icon_img" id="pxl_product_cat_icon_img" value="" />
        <button type="button" class="button" id="pxl_product_cat_icon_img_btn"><?php _e('Select Image', 'medicross-child'); ?></button>
        <div id="pxl_product_cat_icon_img_preview" style="margin-top:8px;"></div>
    </div>
    <script>
    (function($){
        $(function(){
            var frame;
            $('#pxl_product_cat_icon_img_btn').on('click', function(e){
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({ title: 'Select Icon', button: {text: 'Use this icon'}, multiple: false });
                frame.on('select', function(){
                    var att = frame.state().get('selection').first().toJSON();
                    $('#pxl_product_cat_icon_img').val(att.id);
                    var url = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
                    $('#pxl_product_cat_icon_img_preview').html('<img src="'+url+'" style="max-width:64px;max-height:64px;"/>');
                });
                frame.open();
            });
        });
    })(jQuery);
    </script>
<?php });

// Edit term screen fields
add_action("{$tax}_edit_form_fields", function($term) use ($tax) {
    $type = get_term_meta($term->term_id, '_pxl_product_cat_icon_type', true) ?: 'none';
    $font = get_term_meta($term->term_id, '_pxl_product_cat_icon_font', true) ?: '';
    $img  = (int) get_term_meta($term->term_id, '_pxl_product_cat_icon_img', true);
    $url  = $img ? wp_get_attachment_image_url($img, 'thumbnail') : ''; ?>
    <tr class="form-field">
        <th scope="row"><label for="pxl_product_cat_icon_type"><?php _e('Icon Type', 'medicross-child'); ?></label></th>
        <td>
            <select name="pxl_product_cat_icon_type" id="pxl_product_cat_icon_type">
                <option value="none" <?php selected($type, 'none'); ?>><?php _e('None', 'medicross-child'); ?></option>
                <option value="icon" <?php selected($type, 'icon'); ?>><?php _e('Font Icon', 'medicross-child'); ?></option>
                <option value="image" <?php selected($type, 'image'); ?>><?php _e('Image', 'medicross-child'); ?></option>
            </select>
            <p class="description"><?php _e('Choose how the category icon should be rendered.', 'medicross-child'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="pxl_product_cat_icon_font"><?php _e('Font Icon Class', 'medicross-child'); ?></label></th>
        <td>
            <input type="text" name="pxl_product_cat_icon_font" id="pxl_product_cat_icon_font" value="<?php echo esc_attr($font); ?>" placeholder="e.g. flaticon-heart" />
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="pxl_product_cat_icon_img"><?php _e('Icon Image', 'medicross-child'); ?></label></th>
        <td>
            <input type="hidden" name="pxl_product_cat_icon_img" id="pxl_product_cat_icon_img" value="<?php echo esc_attr($img); ?>" />
            <button type="button" class="button" id="pxl_product_cat_icon_img_btn"><?php _e('Select Image', 'medicross-child'); ?></button>
            <div id="pxl_product_cat_icon_img_preview" style="margin-top:8px;">
                <?php if ($url) echo '<img src="'.esc_url($url).'" style="max-width:64px;max-height:64px;"/>'; ?>
            </div>
            <script>(function($){$(function(){var f;$('#pxl_product_cat_icon_img_btn').on('click',function(e){e.preventDefault();if(f){f.open();return;}f=wp.media({title:'Select Icon',button:{text:'Use this icon'},multiple:false});f.on('select',function(){var a=f.state().get('selection').first().toJSON();$('#pxl_product_cat_icon_img').val(a.id);var u=a.sizes&&a.sizes.thumbnail?a.sizes.thumbnail.url:a.url;$('#pxl_product_cat_icon_img_preview').html('<img src="'+u+'" style="max-width:64px;max-height:64px;"/>');});f.open();});});})(jQuery);</script>
        </td>
    </tr>
<?php });

// Save term meta
add_action("created_{$tax}", function($term_id){
    $type = isset($_POST['pxl_product_cat_icon_type']) ? sanitize_text_field($_POST['pxl_product_cat_icon_type']) : 'none';
    $font = isset($_POST['pxl_product_cat_icon_font']) ? sanitize_text_field($_POST['pxl_product_cat_icon_font']) : '';
    $img  = isset($_POST['pxl_product_cat_icon_img']) ? intval($_POST['pxl_product_cat_icon_img']) : 0;
    update_term_meta($term_id, '_pxl_product_cat_icon_type', $type);
    update_term_meta($term_id, '_pxl_product_cat_icon_font', $font);
    update_term_meta($term_id, '_pxl_product_cat_icon_img', $img);
});
add_action("edited_{$tax}", function($term_id){
    $type = isset($_POST['pxl_product_cat_icon_type']) ? sanitize_text_field($_POST['pxl_product_cat_icon_type']) : 'none';
    $font = isset($_POST['pxl_product_cat_icon_font']) ? sanitize_text_field($_POST['pxl_product_cat_icon_font']) : '';
    $img  = isset($_POST['pxl_product_cat_icon_img']) ? intval($_POST['pxl_product_cat_icon_img']) : 0;
    update_term_meta($term_id, '_pxl_product_cat_icon_type', $type);
    update_term_meta($term_id, '_pxl_product_cat_icon_font', $font);
    update_term_meta($term_id, '_pxl_product_cat_icon_img', $img);
});

// Helper: get icon data for a product post (first category)
function msh_pxl_product_term_icon($post_id){
    $terms = get_the_terms($post_id, 'pxl-product-category');
    if (!$terms || is_wp_error($terms)) return null;
    $t = array_shift($terms);
    $type = get_term_meta($t->term_id, '_pxl_product_cat_icon_type', true);
    if (!$type || $type==='none') return null;
    if ($type==='icon') {
        $font = get_term_meta($t->term_id, '_pxl_product_cat_icon_font', true);
        if ($font) return ['type'=>'icon','font'=>$font];
        return null;
    }
    if ($type==='image') {
        $img = (int) get_term_meta($t->term_id, '_pxl_product_cat_icon_img', true);
        if ($img) return ['type'=>'image','id'=>$img];
        return null;
    }
    return null;
}


<?php
/**
 * Injury Icon Meta Box (image/SVG via media library)
 */

// Add meta box
add_action('add_meta_boxes', function() {
    add_meta_box(
        'injury_icon_meta',
        __('Injury Icon', 'medicross-child'),
        function($post) {
            $icon_id = (int) get_post_meta($post->ID, '_injury_icon_id', true);
            $icon_url = $icon_id ? wp_get_attachment_image_url($icon_id, 'thumbnail') : '';
            wp_nonce_field('injury_icon_meta_nonce', 'injury_icon_meta_nonce');
            ?>
            <div style="display:flex; gap:12px; align-items:center;">
                <div id="injury-icon-preview" style="width:64px;height:64px;border:1px dashed #ccc;border-radius:6px;display:flex;align-items:center;justify-content:center;background:#fafafa;overflow:hidden;">
                    <?php if ($icon_url): ?>
                        <img src="<?php echo esc_url($icon_url); ?>" style="max-width:100%;max-height:100%;" />
                    <?php else: ?>
                        <span style="color:#888;font-size:12px;">64×64</span>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="hidden" id="injury_icon_id" name="injury_icon_id" value="<?php echo esc_attr($icon_id); ?>" />
                    <button type="button" class="button" id="injury-icon-upload"><?php _e('Select Icon', 'medicross-child'); ?></button>
                    <button type="button" class="button" id="injury-icon-remove" <?php disabled(!$icon_id); ?>><?php _e('Remove', 'medicross-child'); ?></button>
                    <p class="description" style="margin-top:6px;">
                        <?php _e('Upload an image or SVG to use as the injury icon. Recommended size ~64×64.', 'medicross-child'); ?>
                    </p>
                </div>
            </div>
            <script>
            (function($){
                $(function(){
                    var frame;
                    $('#injury-icon-upload').on('click', function(e){
                        e.preventDefault();
                        if (frame) { frame.open(); return; }
                        frame = wp.media({ title: 'Select Injury Icon', button: { text: 'Use this icon' }, multiple: false });
                        frame.on('select', function(){
                            var attachment = frame.state().get('selection').first().toJSON();
                            $('#injury_icon_id').val(attachment.id);
                            var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                            $('#injury-icon-preview').html('<img src="'+url+'" style="max-width:100%;max-height:100%;" />');
                            $('#injury-icon-remove').prop('disabled', false);
                        });
                        frame.open();
                    });
                    $('#injury-icon-remove').on('click', function(){
                        $('#injury_icon_id').val('');
                        $('#injury-icon-preview').html('<span style="color:#888;font-size:12px;">64×64</span>');
                        $(this).prop('disabled', true);
                    });
                });
            })(jQuery);
            </script>
            <?php
        },
        'injury',
        'side',
        'default'
    );
});

// Save meta
add_action('save_post_injury', function($post_id){
    if (!isset($_POST['injury_icon_meta_nonce']) || !wp_verify_nonce($_POST['injury_icon_meta_nonce'], 'injury_icon_meta_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $icon_id = isset($_POST['injury_icon_id']) ? (int) $_POST['injury_icon_id'] : 0;
    if ($icon_id) {
        update_post_meta($post_id, '_injury_icon_id', $icon_id);
    } else {
        delete_post_meta($post_id, '_injury_icon_id');
    }
});

// Helper: get icon HTML
function msh_injury_get_icon_html($post_id = null, $size = 'thumbnail', $attrs = []){
    $post_id = $post_id ?: get_the_ID();
    $icon_id = (int) get_post_meta($post_id, '_injury_icon_id', true);
    if (!$icon_id) return '';
    $default = ['class' => 'injury-icon'];
    $attrs = array_merge($default, $attrs);
    $attr_str = '';
    foreach ($attrs as $k=>$v) { $attr_str .= ' '.esc_attr($k).'="'.esc_attr($v).'"'; }
    $url = wp_get_attachment_image_url($icon_id, $size) ?: wp_get_attachment_url($icon_id);
    if (!$url) return '';
    return '<img src="'.esc_url($url).'"'.$attr_str.' />';
}

// Shortcode: [injury_icon size="thumbnail"]
add_shortcode('injury_icon', function($atts){
    $atts = shortcode_atts(['size' => 'thumbnail'], $atts, 'injury_icon');
    return msh_injury_get_icon_html(get_the_ID(), $atts['size']);
});


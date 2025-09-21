<?php
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');

$col_xl = 12 / intval($col_xl);
$col_lg = 12 / intval($col_lg);
$col_md = 12 / intval($col_md);
$col_sm = 12 / intval($col_sm);
$col_xs = 12 / intval($col_xs);

$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$image_size = !empty($settings['img_size']) ? $settings['img_size'] : 'full';




?>
<?php if(isset($settings['team']) && !empty($settings['team']) && count($settings['team'])): ?>
<div class="pxl-grid pxl-team-grid pxl-team-grid1 pxl-team-layout1 <?php echo esc_attr($settings['style_l1']); ?>" data-layout="<?php echo esc_attr($settings['layout_mode']); ?>">
    <div class="pxl-grid-inner pxl-grid-masonry row" data-gutter="15">
        <?php foreach ($settings['team'] as $key => $value):
            $title = isset($value['title']) ? $value['title'] : '';
            $position = isset($value['position']) ? $value['position'] : '';
            $popup_template = isset($value['popup_template']) ? $value['popup_template'] : '';
            $image = isset($value['image']) ? $value['image'] : '';
            $social = isset($value['social']) ? $value['social'] : '';
            if($popup_template > 0 ){
                if ( !has_action( 'pxl_anchor_target_page_popup_'.$popup_template) ){
                    add_action( 'pxl_anchor_target_page_popup_'.$popup_template, 'medicross_hook_anchor_page_popup' );
                } 
            }
            ?>
            <div class="<?php echo esc_attr($item_class); ?>">
                <div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                    <?php if(!empty($image['id'])) { 
                        $img = pxl_get_image_by_size( array(
                            'attach_id'  => $image['id'],
                            'thumb_size' => $image_size,
                            'class' => 'no-lazyload',
                        ));
                        $thumbnail = $img['thumbnail'];
                        ?>
                        <div class="pxl-item--image">
                            <a href="javascript:void(0)" data-target=".pxl-page-popup-template-<?php echo esc_attr($popup_template); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            <?php if(!empty($social)): ?>
                                <div class="pxl-social--wrap">
                                    <div class="pxl-social">
                                        <?php  $team_social = json_decode($social, true); ?>
                                        <?php foreach ($team_social as $value): ?>
                                            <a href="<?php echo esc_url($value['url']); ?>" target="_blank"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                        <?php endforeach; ?>
                                    </div>
                                    <span><i class="fas fa-share-alt"></i></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                    <div class="pxl-item--holder ">
                        <div class="pxl-item--meta pxl-flex-grow ">
                            <h3 class="pxl-item--title">    
                                <a ><?php echo pxl_print_html($title); ?></a>
                            </h3>
                            <div class="pxl-item--position"><?php echo pxl_print_html($position); ?></div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
    </div>
</div>
<?php endif; ?>

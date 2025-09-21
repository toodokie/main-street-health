<?php
// Register Button Widget
pxl_add_custom_widget(
    array(
        'name' => 'icon_post_format',
        'title' => esc_html__('Case Icon Post Format', 'medicross' ),
        'icon' => 'eicon-cart-medium',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
              
            ),
        ),
    ),
    medicross_get_class_widget_path()
);
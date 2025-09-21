<?php if(!function_exists('medicross_configs')){
    function medicross_configs($value){
        $configs = [
            'theme_colors' => [
                'primary'   => [
                    'title' => esc_html__('Primary', 'medicross'), 
                    'value' => medicross()->get_opt('primary_color', '#ffcc53')
                ],
                'secondary'   => [
                    'title' => esc_html__('Secondary', 'medicross'), 
                    'value' => medicross()->get_opt('secondary_color', '#051b2e')
                ],
                'third'   => [
                    'title' => esc_html__('Third', 'medicross'), 
                    'value' => medicross()->get_opt('third_color', '#68747a')
                ],
                'four'   => [
                    'title' => esc_html__('Third', 'medicross'), 
                    'value' => medicross()->get_opt('four_color', '#09243c')
                ],
                'body_bg'   => [
                    'title' => esc_html__('Body Background Color', 'medicross'), 
                    'value' => medicross()->get_opt('body_bg_color', '#fff')
                ]
            ],
               
        ];
        return $configs[$value];
    }
}
if(!function_exists('medicross_inline_styles')) {
    function medicross_inline_styles() {  
        
        $theme_colors      = medicross_configs('theme_colors');
        //$link_color        = medicross_configs('link');
        //$gradient_color    = medicross_configs('gradient');
        ob_start();
        echo ':root{';
            
            foreach ($theme_colors as $color => $value) {
                printf('--%1$s-color: %2$s;', str_replace('#', '',$color),  $value['value']);
            }
            foreach ($theme_colors as $color => $value) {
                printf('--%1$s-color-rgb: %2$s;', str_replace('#', '',$color),  medicross_hex_rgb($value['value']));
            }
            // foreach ($link_color as $color => $value) {
            //     printf('--link-%1$s: %2$s;', $color, $value);
            // }
            // foreach ($gradient_color as $color => $value) {
            //     printf('--gradient-%1$s: %2$s;', $color, $value);
            //}
        echo '}';

        return ob_get_clean();
         
    }
}
 
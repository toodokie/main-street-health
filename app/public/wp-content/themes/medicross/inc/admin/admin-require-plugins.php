<?php
/**
 * Include the TGM_Plugin_Activation class.
 */
get_template_part( 'inc/admin/libs/tgmpa/class-tgm-plugin-activation' );

add_action( 'tgmpa_register', 'medicross_register_required_plugins' );
function medicross_register_required_plugins() {
    include( locate_template( 'inc/admin/demo-data/demo-config.php' ) );
    $pxl_server_info = apply_filters( 'pxl_server_info', ['plugin_url' => 'https://api.casethemes.net/plugins/'] ) ; 
    $default_path = $pxl_server_info['plugin_url'];  
    $images = get_template_directory_uri() . '/inc/admin/assets/img/plugins'; 
    $plugins = array(

        array(
            'name'               => esc_html__('Redux Framework', 'medicross'),
            'slug'               => 'redux-framework',
            'required'           => true,
            'logo'        => $images . '/redux.png',
            'description' => esc_html__( 'Build theme options and post, page options for WordPress Theme.', 'medicross' ),
        ),

        array(
            'name'               => esc_html__('Elementor', 'medicross'),
            'slug'               => 'elementor',
            'required'           => true,
            'logo'        => $images . '/elementor.png',
            'description' => esc_html__( 'Introducing a WordPress website builder, with no limits of design. A website builder that delivers high-end page designs and advanced capabilities', 'medicross' ),
        ),  

        array(
            'name'               => esc_html__('Case Addons', 'medicross'),
            'slug'               => 'case-addons',
            'source'             => 'case-addons.zip',
            'required'           => true,
            'logo'        => $images . '/case-logo.png',
            'description' => esc_html__( 'Main process and Powerful Elements Plugin, exclusively for Farmas WordPress Theme.', 'medicross' ),
        ),
        array(
            'name'               => esc_html__('Contact Form 7', 'medicross'),
            'slug'               => 'contact-form-7',
            'required'           => true,
            'logo'        => $images . '/contact-f7.png',
            'description' => esc_html__( 'Contact Form 7 can manage multiple contact forms, you can customize the form and the mail contents flexibly with simple markup', 'medicross' ),
        ),
        array(
            'name'               => esc_html__('Revolution Slider', 'medicross'),
            'slug'               => 'revslider',
            'source'             => 'revslider.zip',
            'required'           => true,
            'logo'        => $images . '/rev-slider.png',
            'description' => esc_html__( 'Revolution Slider helps beginner-and mid-level designers WOW their clients with pro-level visuals.', 'medicross' )
        ),
        array(
            'name'               => esc_html__('Time Table', 'medicross'),
            'slug'               => 'timetable',
            'source'             => 'timetable.zip',
            'required'           => true,
            'logo'        => $images . '/book.png',
            'description' => esc_html__( 'Timetable Responsive Schedule For Wordpress', 'medicross' )
        ),
        array(
            'name'               => esc_html__('Addons Contact Form 7', 'medicross'),
            'slug'               => 'ultimate-addons-for-contact-form-7',
            'required'           => true,
            'logo'        => $images . '/addons-ctf7.png',
            'description' => esc_html__( 'Support CTF7', 'medicross' )
        ), 
        array(
            'name'               => esc_html__('WooCommerce', 'medicross'),
            'slug'               => "woocommerce",
            'required'           => true,
            'logo'        => $images . '/woo.png',
            'description' => esc_html__( 'WooCommerce is the world’s most popular open-source eCommerce solution.', 'medicross' ),
        ),
        array(
            'name'               => esc_html__('Wishlist', 'medicross'),
            'slug'               => "woo-smart-wishlist",
            'required'           => true,
            'logo'        => $images . '/woo-smart-wishlist.png',
            'description' => esc_html__( 'WPC Smart Wishlist is a simple but powerful tool that can help your customer save products for buying later.', 'medicross' ),
        ),
    );
    $config = array(
        'default_path' => $default_path,           // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'is_automatic' => true,
    );

    tgmpa( $plugins, $config );
}
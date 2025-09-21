<?php
 
add_action( 'pxl_post_metabox_register', 'medicross_page_options_register' );
function medicross_page_options_register( $metabox ) {
 
	$panels = [
		'post' => [
			'opt_name'            => 'post_option',
			'display_name'        => esc_html__( 'Post Settings', 'medicross' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'post_settings' => [
					'title'  => esc_html__( 'Post Settings', 'medicross' ),
					'icon'   => 'el el-refresh',
					'fields' => array_merge(
						medicross_sidebar_pos_opts(['prefix' => 'post_', 'default' => true, 'default_value' => '-1']),
						medicross_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
                        array(
                            array(
                                'id'           => 'custom_main_title',
                                'type'         => 'text',
                                'title'        => esc_html__( 'Custom Main Title', 'medicross' ),
                                'subtitle'     => esc_html__( 'Custom heading text title', 'medicross' ),
                                'required' => array( 'pt_mode', '!=', 'none' )
                            ),
                            array(
                                'id'           => 'custom_sub_title',
                                'type'         => 'text',
                                'title'        => esc_html__( 'Custom Sub title', 'medicross' ),
                                'subtitle'     => esc_html__( 'Add short description for page title', 'medicross' ),
                                'required' => array( 'pt_mode', '!=', 'none' )
                            )
                        ),
                        array(
                            array(
                                'id'          => 'featured-video-url',
                                'type'        => 'text',
                                'title'       => esc_html__( 'Video URL', 'medicross' ),
                                'description' => esc_html__( 'Video will show when set post format is video', 'medicross' ),
                                'validate'    => 'url',
                                'msg'         => 'Url error!',
                            ),
                            array(
                                'id'          => 'featured-audio-url',
                                'type'        => 'text',
                                'title'       => esc_html__( 'Audio URL', 'medicross' ),
                                'description' => esc_html__( 'Audio that will show when set post format is audio', 'medicross' ),
                                'validate'    => 'url',
                                'msg'         => 'Url error!',
                            ),
                            array(
                                'id'=>'featured-quote-text',
                                'type' => 'textarea',
                                'title' => esc_html__('Quote Text', 'medicross'),
                                'default' => '',
                            ),
                            array(
                                'id'          => 'featured-quote-cite',
                                'type'        => 'text',
                                'title'       => esc_html__( 'Quote Cite', 'medicross' ),
                                'description' => esc_html__( 'Quote will show when set post format is quote', 'medicross' ),
                            ),
                            array(
                                'id'       => 'featured-link-url',
                                'type'     => 'text',
                                'title'    => esc_html__( 'Format Link URL', 'medicross' ),
                                'description' => esc_html__( 'Link will show when set post format is link', 'medicross' ),
                            ),
                            array(
                                'id'          => 'featured-link-text',
                                'type'        => 'text',
                                'title'       => esc_html__( 'Format Link Text', 'medicross' ),
                            ),
                        )
					)
				]
			]
		],
		'page' => [
			'opt_name'            => 'pxl_page_options',
			'display_name'        => esc_html__( 'Page Options', 'medicross' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'Header', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
				        medicross_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						medicross_header_mobile_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'header_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Header Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
				            
							array(
								'id'       => 'logo_m',
								'type'     => 'media',
								'title'    => esc_html__('Mobile Logo', 'medicross'),
							),
					        array(
				                'id'       => 'p_menu',
				                'type'     => 'select',
				                'title'    => esc_html__( 'Menu', 'medicross' ),
				                'options'  => medicross_get_nav_menu_slug(),
				                'default' => '',
				            ),
					    ),
					    array(
				            array(
				                'id'       => 'sticky_scroll',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Sticky Scroll', 'medicross'),
				                'options'  => array(
				                    '-1' => esc_html__('Inherit', 'medicross'),
				                    'pxl-sticky-stt' => esc_html__('Scroll To Top', 'medicross'),
				                    'pxl-sticky-stb'  => esc_html__('Scroll To Bottom', 'medicross'),
				                ),
				                'default'  => '-1',
				            ),
				        )
				    )
					 
				],
				'page_title' => [
					'title'  => esc_html__( 'Page Title', 'medicross' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        medicross_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						])
				    )
				],
				'content' => [
					'title'  => esc_html__( 'Content', 'medicross' ),
					'icon'   => 'el-icon-pencil',
					'fields' => array_merge(
						medicross_sidebar_pos_opts(['prefix' => 'page_', 'default' => false, 'default_value' => '0']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'medicross' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							), 
					    )
					)
				],
				'footer' => [
					'title'  => esc_html__( 'Footer', 'medicross' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        medicross_footer_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'footer_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
							array(
				                'id'       => 'p_footer_fixed',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Fixed', 'medicross'),
				                'options'  => array(
				                    'inherit' => esc_html__('Inherit', 'medicross'),
				                    'on' => esc_html__('On', 'medicross'),
				                    'off' => esc_html__('Off', 'medicross'),
				                ),
				                'default'  => 'inherit',
				            ),
				            array(
				                'id'       => 'back_top_top_style',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Back to Top Style', 'medicross'),
				                'options'  => array(
				                    'style-default' => esc_html__('Default', 'medicross'),
				                    'style-round' => esc_html__('Round', 'medicross'),
				                ),
				                'default'  => 'style-default',
				            ),
						)
				    )
				],
				'colors' => [
					'title'  => esc_html__( 'Colors', 'medicross' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        array(
							array(
								'id'       => 'content_bgp_color',
								'type'     => 'color_rgba',
								'title'    => esc_html__('Body Background Color', 'medicross'),
								'subtitle' => esc_html__('Body Background color.', 'medicross'),
								'output'   => array('background-color' => 'body')
							),
				        	array(
					            'id'          => 'primary_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Primary Color', 'medicross'),
					            'transparent' => false,
					            'default'     => ''
					        ),
				        	array(
					            'id'          => 'secondary_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Secondary Color', 'medicross'),
					            'transparent' => false,
					            'default'     => ''
					        ),
					    )
				    )
				],
				'extra' => [
					'title'  => esc_html__( 'Extra', 'medicross' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        array(
				        	array(
					            'id' => 'body_custom_class',
					            'type' => 'text',
					            'title' => esc_html__('Body Custom Class', 'medicross'),
					        ),
					    )
				    )
				]
			]
		],
		'portfolio' => [
			'opt_name'            => 'pxl_portfolio_options',
			'display_name'        => esc_html__( 'Product Options', 'medicross' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header1' => [
					'title'  => esc_html__( 'Header', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
				        medicross_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						medicross_header_mobile_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'header_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Header Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
					        array(
				                'id'       => 'p_menu',
				                'type'     => 'select',
				                'title'    => esc_html__( 'Menu', 'medicross' ),
				                'options'  => medicross_get_nav_menu_slug(),
				                'default' => '',
				            ),
					    ),
					    array(
				            array(
				                'id'       => 'sticky_scroll',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Sticky Scroll', 'medicross'),
				                'options'  => array(
				                    '-1' => esc_html__('Inherit', 'medicross'),
				                    'pxl-sticky-stt' => esc_html__('Scroll To Top', 'medicross'),
				                    'pxl-sticky-stb'  => esc_html__('Scroll To Bottom', 'medicross'),
				                ),
				                'default'  => '-1',
				            ),
				        )
				    )
					 
				],
				'page_title' => [
					'title'  => esc_html__( 'Page Title', 'medicross' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        medicross_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						])
				    )
				],
				'content' => [
					'title'  => esc_html__( 'Content', 'medicross' ),
					'icon'   => 'el-icon-pencil',
					'fields' => array_merge(
						medicross_sidebar_pos_opts(['prefix' => 'page_', 'default' => false, 'default_value' => '0']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'medicross' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							), 
					    )
					)
				],
				'footer' => [
					'title'  => esc_html__( 'Footer', 'medicross' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        medicross_footer_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'footer_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
							array(
				                'id'       => 'p_footer_fixed',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Fixed', 'medicross'),
				                'options'  => array(
				                    'inherit' => esc_html__('Inherit', 'medicross'),
				                    'on' => esc_html__('On', 'medicross'),
				                    'off' => esc_html__('Off', 'medicross'),
				                ),
				                'default'  => 'inherit',
				            ),
				            array(
				                'id'       => 'back_top_top_style',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Back to Top Style', 'medicross'),
				                'options'  => array(
				                    'style-default' => esc_html__('Default', 'medicross'),
				                    'style-round' => esc_html__('Round', 'medicross'),
				                ),
				                'default'  => 'style-default',
				            ),
						)
				    )
				],
			]
		],
		'product' => [
			'opt_name'            => 'pxl_product_options',
			'display_name'        => esc_html__( 'Portfolio Options', 'medicross' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header1' => [
					'title'  => esc_html__( 'Header', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
				        medicross_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						medicross_header_mobile_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'header_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Header Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
					        array(
				                'id'       => 'p_menu',
				                'type'     => 'select',
				                'title'    => esc_html__( 'Menu', 'medicross' ),
				                'options'  => medicross_get_nav_menu_slug(),
				                'default' => '',
				            ),
					    ),
					    array(
				            array(
				                'id'       => 'sticky_scroll',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Sticky Scroll', 'medicross'),
				                'options'  => array(
				                    '-1' => esc_html__('Inherit', 'medicross'),
				                    'pxl-sticky-stt' => esc_html__('Scroll To Top', 'medicross'),
				                    'pxl-sticky-stb'  => esc_html__('Scroll To Bottom', 'medicross'),
				                ),
				                'default'  => '-1',
				            ),
				        )
				    )
					 
				],
				'page_title' => [
					'title'  => esc_html__( 'Page Title', 'medicross' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        medicross_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						])
				    )
				],
				'content' => [
					'title'  => esc_html__( 'Content', 'medicross' ),
					'icon'   => 'el-icon-pencil',
					'fields' => array_merge(
						medicross_sidebar_pos_opts(['prefix' => 'page_', 'default' => false, 'default_value' => '0']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'medicross' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							), 
					    )
					)
				],
				'footer' => [
					'title'  => esc_html__( 'Footer', 'medicross' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        medicross_footer_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
				    )
				],
			]
		],
		'service' => [
			'opt_name'            => 'pxl_service_options',
			'display_name'        => esc_html__( 'Service Options', 'medicross' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
						array(
							array(
					            'id'=> 'service_external_link',
					            'type' => 'text',
					            'title' => esc_html__('External Link', 'medicross'),
					            'validate' => 'url',
					            'default' => '',
					        ),
					        array(
					            'id'       => 'service_icon_type',
					            'type'     => 'button_set',
					            'title'    => esc_html__('Icon Type', 'medicross'),
					            'options'  => array(
					                'icon'  => esc_html__('Icon', 'medicross'),
					                'image'  => esc_html__('Image', 'medicross'),
					            ),
					            'default'  => 'icon'
					        ),
					        array(
					            'id'       => 'service_icon_font',
					            'type'     => 'pxl_iconpicker',
					            'title'    => esc_html__('Icon', 'medicross'),
					            'required' => array( 0 => 'service_icon_type', 1 => 'equals', 2 => 'icon' ),
            					'force_output' => true
					        ),
					        array(
					            'id'       => 'service_icon_img',
					            'type'     => 'media',
					            'title'    => esc_html__('Icon Image', 'medicross'),
					            'default' => '',
					            'required' => array( 0 => 'service_icon_type', 1 => 'equals', 2 => 'image' ),
				            	'force_output' => true
					        ),
						)
				    )
				],
				'header1' => [
					'title'  => esc_html__( 'Header', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
				        medicross_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						medicross_header_mobile_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'header_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Header Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
					        array(
				                'id'       => 'p_menu',
				                'type'     => 'select',
				                'title'    => esc_html__( 'Menu', 'medicross' ),
				                'options'  => medicross_get_nav_menu_slug(),
				                'default' => '',
				            ),
					    ),
					    array(
				            array(
				                'id'       => 'sticky_scroll',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Sticky Scroll', 'medicross'),
				                'options'  => array(
				                    '-1' => esc_html__('Inherit', 'medicross'),
				                    'pxl-sticky-stt' => esc_html__('Scroll To Top', 'medicross'),
				                    'pxl-sticky-stb'  => esc_html__('Scroll To Bottom', 'medicross'),
				                ),
				                'default'  => '-1',
				            ),
				        )
				    )
					 
				],
				'page_title' => [
					'title'  => esc_html__( 'Page Title', 'medicross' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        medicross_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						])
				    )
				],
				'content' => [
					'title'  => esc_html__( 'Content', 'medicross' ),
					'icon'   => 'el-icon-pencil',
					'fields' => array_merge(
						medicross_sidebar_pos_opts(['prefix' => 'page_', 'default' => false, 'default_value' => '0']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'medicross' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							), 
					    )
					)
				],
				'footer' => [
					'title'  => esc_html__( 'Footer', 'medicross' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        medicross_footer_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'footer_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
							array(
				                'id'       => 'p_footer_fixed',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Fixed', 'medicross'),
				                'options'  => array(
				                    'inherit' => esc_html__('Inherit', 'medicross'),
				                    'on' => esc_html__('On', 'medicross'),
				                    'off' => esc_html__('Off', 'medicross'),
				                ),
				                'default'  => 'inherit',
				            ),
				            array(
				                'id'       => 'back_top_top_style',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Back to Top Style', 'medicross'),
				                'options'  => array(
				                    'style-default' => esc_html__('Default', 'medicross'),
				                    'style-round' => esc_html__('Round', 'medicross'),
				                ),
				                'default'  => 'style-default',
				            ),
						)
				    )
				],
			]
		],
		'industries' => [
			'opt_name'            => 'pxl_industries_options',
			'display_name'        => esc_html__( 'Industries Options', 'medicross' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
						array(
							array(
					            'id'=> 'industries_external_link',
					            'type' => 'text',
					            'title' => esc_html__('External Link', 'medicross'),
					            'validate' => 'url',
					            'default' => '',
					        ),
							array(
					            'id'=> 'position',
					            'type' => 'text',
					            'title' => esc_html__('Position', 'medicross'),
					            'default' => '',
					        ),
						)
				    )
				],
				'header1' => [
					'title'  => esc_html__( 'Header', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
				        medicross_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						medicross_header_mobile_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'header_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Header Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
					        array(
				                'id'       => 'p_menu',
				                'type'     => 'select',
				                'title'    => esc_html__( 'Menu', 'medicross' ),
				                'options'  => medicross_get_nav_menu_slug(),
				                'default' => '',
				            ),
					    ),
					    array(
				            array(
				                'id'       => 'sticky_scroll',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Sticky Scroll', 'medicross'),
				                'options'  => array(
				                    '-1' => esc_html__('Inherit', 'medicross'),
				                    'pxl-sticky-stt' => esc_html__('Scroll To Top', 'medicross'),
				                    'pxl-sticky-stb'  => esc_html__('Scroll To Bottom', 'medicross'),
				                ),
				                'default'  => '-1',
				            ),
				        )
				    )
					 
				],
				'page_title' => [
					'title'  => esc_html__( 'Page Title', 'medicross' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        medicross_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						])
				    )
				],
				'content' => [
					'title'  => esc_html__( 'Content', 'medicross' ),
					'icon'   => 'el-icon-pencil',
					'fields' => array_merge(
						medicross_sidebar_pos_opts(['prefix' => 'page_', 'default' => false, 'default_value' => '0']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'medicross' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							), 
					    )
					)
				],
				'footer' => [
					'title'  => esc_html__( 'Footer', 'medicross' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        medicross_footer_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'footer_display',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Display', 'medicross'),
				                'options'  => array(
				                    'show' => esc_html__('Show', 'medicross'),
				                    'hide'  => esc_html__('Hide', 'medicross'),
				                ),
				                'default'  => 'show',
				            ),
							array(
				                'id'       => 'p_footer_fixed',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Fixed', 'medicross'),
				                'options'  => array(
				                    'inherit' => esc_html__('Inherit', 'medicross'),
				                    'on' => esc_html__('On', 'medicross'),
				                    'off' => esc_html__('Off', 'medicross'),
				                ),
				                'default'  => 'inherit',
				            ),
				            array(
				                'id'       => 'back_top_top_style',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Back to Top Style', 'medicross'),
				                'options'  => array(
				                    'style-default' => esc_html__('Default', 'medicross'),
				                    'style-round' => esc_html__('Round', 'medicross'),
				                ),
				                'default'  => 'style-default',
				            ),
						)
				    )
				],
			]
		],

		'pxl-template' => [ //post_type
			'opt_name'            => 'pxl_hidden_template_options',
			'display_name'        => esc_html__( 'Template Options', 'medicross' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'medicross' ),
					'icon'   => 'el-icon-website',
					'fields' => array(
						array(
							'id'    => 'template_type',
							'type'  => 'select',
							'title' => esc_html__('Type', 'medicross'),
				            'options' => [
				            	'df'       	   => esc_html__('Select Type', 'medicross'), 
								'header'       => esc_html__('Header Desktop', 'medicross'),
								'header-mobile'       => esc_html__('Header Mobile', 'medicross'),
								'footer'       => esc_html__('Footer', 'medicross'), 
								'mega-menu'    => esc_html__('Mega Menu', 'medicross'), 
								'page-title'   => esc_html__('Page Title', 'medicross'), 
								'tab' => esc_html__('Tab', 'medicross'),
								'hidden-panel' => esc_html__('Hidden Panel', 'medicross'),
								'popup' => esc_html__('Popup', 'medicross'),
								'widget' => esc_html__('Widget Sidebar', 'medicross'),
								'page' => esc_html__('Page', 'medicross'),
								'slider' => esc_html__('Slider', 'medicross'),
				            ],
				            'default' => 'df',
				        ),
				        array(
							'id'    => 'header_type',
							'type'  => 'select',
							'title' => esc_html__('Header Type', 'medicross'),
				            'options' => [
				            	'px-header--default'       	   => esc_html__('Default', 'medicross'), 
								'px-header--transparent'       => esc_html__('Transparent', 'medicross'),
								'px-header--left_sidebar'       => esc_html__('Left Sidebar', 'medicross'),
				            ],
				            'default' => 'px-header--default',
				            'indent' => true,
                			'required' => array( 0 => 'template_type', 1 => 'equals', 2 => 'header' ),
				        ),

				        array(
							'id'    => 'header_mobile_type',
							'type'  => 'select',
							'title' => esc_html__('Header Type', 'medicross'),
				            'options' => [
				            	'px-header--default'       	   => esc_html__('Default', 'medicross'), 
								'px-header--transparent'       => esc_html__('Transparent', 'medicross'),
				            ],
				            'default' => 'px-header--default',
				            'indent' => true,
                			'required' => array( 0 => 'template_type', 1 => 'equals', 2 => 'header-mobile' ),
				        ),

				        array(
							'id'    => 'hidden_panel_position',
							'type'  => 'select',
							'title' => esc_html__('Hidden Panel Position', 'medicross'),
				            'options' => [
				            	'top'       	   => esc_html__('Top', 'medicross'),
				            	'right'       	   => esc_html__('Right', 'medicross'),
				            ],
				            'default' => 'right',
				            'required' => array( 0 => 'template_type', 1 => 'equals', 2 => 'hidden-panel' ),
				        ),
				        array(
				            'id'          => 'hidden_panel_height',
				            'type'        => 'text',
				            'title'       => esc_html__('Hidden Panel Height', 'medicross'),
				            'subtitle'       => esc_html__('Enter number.', 'medicross'),
				            'transparent' => false,
				            'default'     => '',
				            'force_output' => true,
				            'required' => array( 0 => 'hidden_panel_position', 1 => 'equals', 2 => 'top' ),
				        ),
				        array(
				            'id'          => 'hidden_panel_boxcolor',
				            'type'        => 'color',
				            'title'       => esc_html__('Box Color', 'medicross'),
				            'transparent' => false,
				            'default'     => '',
				            'required' => array( 0 => 'template_type', 1 => 'equals', 2 => 'hidden-panel' ),
				        ),

				        array(
				            'id'          => 'header_sidebar_width',
				            'type'        => 'slider',
				            'title'       => esc_html__('Header Sidebar Width', 'medicross'),
				            "default"   => 300,
						    "min"       => 50,
						    "step"      => 1,
						    "max"       => 900,
				            'force_output' => true,
				            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'px-header--left_sidebar' ),
				        ),

				        array(
				            'id'          => 'header_sidebar_border',
				            'type'        => 'border',
				            'title'       => esc_html__('Header Sidebar Border', 'medicross'),
				            'force_output' => true,
				            'required' => array( 0 => 'header_type', 1 => 'equals', 2 => 'px-header--left_sidebar' ),
				            'default' => '',
				        ),
					),
				    
				],
			]
		],
	];
 
	$metabox->add_meta_data( $panels );
}
 
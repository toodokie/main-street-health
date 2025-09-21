<?php

if( !defined( 'ABSPATH' ) )
	exit; 

class Medicross_Admin_Templates extends Medicross_Base{

	public function __construct() {
		$this->add_action( 'admin_menu', 'register_page', 20 );
	}
 
	public function register_page() {
		add_submenu_page(
			'pxlart',
		    esc_html__( 'Templates', 'medicross' ),
		    esc_html__( 'Templates', 'medicross' ),
		    'manage_options',
		    'edit.php?post_type=pxl-template',
		    false
		);
	}
}
new Medicross_Admin_Templates;

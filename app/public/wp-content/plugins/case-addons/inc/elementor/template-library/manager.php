<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Pxl_El_Template_Library
{
    private static $_instance = null;
    public function __construct(){
        /*$test = get_option('elementor_remote_info_library');
        if( $test){
            echo '<pre>';
            print_r($test['templates']);
            echo '</pre>';
        }*/
      
        add_action( 'elementor/init', [ $this, 'pxl_egister_template_library_source' ], 15 );

    }


    public static function instance(){
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
 
    function pxl_egister_template_library_source(){
        global $wp_filesystem;
        if( defined('THEME_DEV_CLEAR_CACHE') && THEME_DEV_CLEAR_CACHE ){ 
            delete_transient( 'elementor_remote_templates_data_'.ELEMENTOR_VERSION );
            delete_transient( 'elementor_remote_info_api_data_'.ELEMENTOR_VERSION );
            delete_option('elementor_remote_info_library');
            
            delete_transient( 'custom_remote_update_timestamp' ); 
            delete_option('custom_remote_info_library');
        }

        $e_library_data = get_option('elementor_remote_info_library');

        $update_timestamp = get_transient( 'custom_remote_update_timestamp' );
        $elementor_update_timestamp = get_option( '_transient_timeout_elementor_remote_info_api_data_' . ELEMENTOR_VERSION );

        if ( ! $update_timestamp || $update_timestamp != $elementor_update_timestamp ) {
            $info_file_path = get_template_directory().'/elements/template-library/info.json';
            
            if( file_exists($info_file_path) ){
                $info_data = json_decode( $wp_filesystem->get_contents( $info_file_path ), true); 
     
                if( !empty( $info_data['library']['types_data']['block']['categories'])){
                    if( !empty( $e_library_data['types_data']['block']['categories'] )){
                        foreach( $info_data['library']['types_data']['block']['categories'] as $pxl_cat){
                            if( !in_array( $pxl_cat, $e_library_data['types_data']['block']['categories']) ){
                                array_unshift($e_library_data['types_data']['block']['categories'], $pxl_cat);
                                array_unshift($e_library_data['categories'], $pxl_cat);
                            }
                        }
                        update_option('elementor_remote_info_library', $e_library_data);
                    }
                }
            }
        }

        include 'source-custom.php';
        $unregister_source = function($id) {
            unset( $this->_registered_sources[ $id ] );
        };

        $unregister_source->call( \Elementor\Plugin::instance()->templates_manager, 'remote');

        \Elementor\Plugin::instance()->templates_manager->register_source( 'Elementor\TemplateLibrary\Source_Custom' );
    }
 
}

Pxl_El_Template_Library::instance();
<?php
/**
 * Theme functions: init, enqueue scripts and styles, include required files and widgets.
 *
 * @package Case-Themes
 * @since Medicross 1.0
 */

if(!defined('DEV_MODE')){ define('DEV_MODE', true); }

if(!defined('THEME_DEV_MODE_ELEMENTS') && is_user_logged_in()){
    define('THEME_DEV_MODE_ELEMENTS', true);
}
 
require_once get_template_directory() . '/inc/classes/class-main.php';

if ( is_admin() ){ 
	require_once get_template_directory() . '/inc/admin/admin-init.php'; }
 
/**
 * Theme Require
*/
medicross()->require_folder('inc');
medicross()->require_folder('inc/classes');
medicross()->require_folder('inc/theme-options');
medicross()->require_folder('template-parts/widgets');
if(class_exists('Woocommerce')){
    medicross()->require_folder('woocommerce');
}

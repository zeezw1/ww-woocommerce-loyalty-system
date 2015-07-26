<?php
/**
 * Plugin Name: WW WooCommerce Loyalty System
 * Version: 0.5.0
 * Author: WebEmpire
 * Author URI: http://wierzgacz.pl/
 * Text Domain: ww
 * Domain Path: /languages/
 *
 * @author  WebEmpire
 * @package WW Loyalty System
 * @version 0.5.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

require_once 'various.php';
require_once 'class.WWLS_Setup.php';
require_once 'class.WWLS_Payment_Gateway';
/*
* Activation hooks
*/

register_activation_hook(   __FILE__, array( 'WWLS_Setup', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'WWLS_Setup', 'on_deactivation' ) );
register_uninstall_hook(    __FILE__, array( 'WWLS_Setup', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'WWLS_Setup', 'init' ) );
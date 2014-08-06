<?php
/**
 * Advanced Ads.
 *
 * @package   Advanced_Ads_Admin
 * @author    Thomas Maier <thomas.maier@webgilde.com>
 * @license   GPL-2.0+
 * @link      http://webgilde.com
 * @copyright 2013 Thomas Maier, webgilde GmbH
 *
 * @wordpress-plugin
 * Plugin Name:       Advanced Ads
 * Plugin URI:        http://webgilde.com
 * Description:       Manage and optimize your ads in WordPress
 * Version:           1.0.1
 * Author:            Thomas Maier
 * Author URI:        http://webgilde.com
 * Text Domain:       advanced-ads
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// only load if not already existing (maybe within another plugin I created)
if(!class_exists('Advanced_Ads')) {

// load basic path to the plugin
DEFINE('ADVADS_BASE_PATH', plugin_dir_path(__FILE__));
// general and global slug, e.g. to store options in WP
DEFINE('ADVADS_SLUG', 'advancedads');

/*----------------------------------------------------------------------------*
 * Autoloading Objects
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'includes/autoloader.php' );
spl_autoload_register(array('Advads_Autoloader', 'load'));

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-advanced-ads.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Advanced_Ads', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Advanced_Ads', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Advanced_Ads', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if( defined('DOING_AJAX') ) {
    require_once( plugin_dir_path( __FILE__ ) . 'includes/class_ajax_callbacks.php' );
}
// load ad conditions array
require_once( plugin_dir_path( __FILE__ ) . 'includes/array_ad_conditions.php' );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-advanced-ads-admin.php' );
	add_action( 'plugins_loaded', array( 'Advanced_Ads_Admin', 'get_instance' ) );

}

// load public functions
require_once( plugin_dir_path( __FILE__ ) . 'public/functions.php' );

}
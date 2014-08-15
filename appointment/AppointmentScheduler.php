<?php
/**
 *
 * @package   Appointment Scheduler
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name: Appointment Scheduler
 * Plugin URI:  Appointment Scheduler
 * Description: Appointment Scheduler
 * Version:     1.0.0
 * Author:      Appointment Scheduler
 * Author URI:  Appointment Scheduler
 * Text Domain: Appointment Scheduler
 * License:     GPL-2.0+
 * License URI: 
 * Domain Path: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require 'library/app/config/config.inc.php';

if ( is_admin() )
{
	$db = array();
	$db['n'] 		= DB_NAME;
	$db['u'] 		= DB_USER;
	$db['h'] 		= DB_HOST;
	$db['pf'] = $table_prefix;

	$db = http_build_query($db);
	$dbp 		= DB_PASSWORD;

	//setcookie("appointment_scheduler", $db, time()+36000, "/", "");
	//setcookie("appointment_scheduler_p", $dbp, time()+36000, "/", "");

} else {
	//setcookie("appointment_scheduler", $db, time()-36000, "/", "");
	//setcookie("appointment_scheduler_p", '', time()+36000, "/", "");
}

$pf = $table_prefix;
setcookie("as_pf", $pf, time()+3600, "/", "");

if ( is_admin() ){
	setcookie("as_admin", 'admin', time()+300, "/", "");
} else {
	setcookie("as_admin", 'admin', time()-300, "/", "");
}

require_once( plugin_dir_path( __FILE__ ) . 'class-AppointmentScheduler.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'AppointmentScheduler', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'AppointmentScheduler', 'deactivate' ) );

AppointmentScheduler::get_instance();

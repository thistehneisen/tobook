<?php
/**
 *
 * @package   Time Slots Booking Calendar
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name: Time Slots Booking Calendar
 * Plugin URI:  Time Slots Booking Calendar
 * Description: Time Slots Booking Calendar
 * Version:     1.0.0
 * Author:      Time Slots Booking Calendar
 * Author URI:  Time Slots Booking Calendar
 * Text Domain: Time Slots Booking Calendar
 * License:     GPL-2.0+
 * License URI: 
 * Domain Path: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() ){
	setcookie("tsbc_admin", 'admin', time()+300, "/", "");
} else {
	setcookie("tsbc_admin", 'admin', time()-300, "/", "");
}

require_once( plugin_dir_path( __FILE__ ) . 'class-TimeSlotsBookingCalendar.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'TimeSlotsBookingCalendar', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'TimeSlotsBookingCalendar', 'deactivate' ) );

TimeSlotsBookingCalendar::get_instance();
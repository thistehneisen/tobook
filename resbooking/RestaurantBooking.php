<?php
/**
 *
 * @package   Restaurant Booking
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name: Restaurant Booking
 * Plugin URI:  Restaurant Booking
 * Description: Restaurant Booking
 * Version:     1.0.0
 * Author:      Restaurant Booking
 * Author URI:  Restaurant Booking
 * Text Domain: Restaurant Booking
 * License:     GPL-2.0+
 * License URI: 
 * Domain Path: 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( is_admin() ){
	setcookie("rbooking_admin", 'admin', time()+300, "/", "");
} else {
	setcookie("rbooking_admin", 'admin', time()-300, "/", "");
}

require_once( plugin_dir_path( __FILE__ ) . 'class-RestaurantBooking.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'RestaurantBooking', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'RestaurantBooking', 'deactivate' ) );

RestaurantBooking::get_instance();
<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Event Booking
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

// TODO: Define uninstall functionality here

// Tables
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_bookings" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_bookings_tables" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_countries" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_dates" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_options" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_roles" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_tables" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_users" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_service" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_template" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_vouchers" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_working_times" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_tables_group" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_bookings_tables_group" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_menu" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "restaurant_booking_bookings_menu" );
delete_option('rb_db_version');
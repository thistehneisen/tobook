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
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_bookings" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_bookings_slots" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_calendars" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_countries" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_dates" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_options" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_prices" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_prices_days" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_roles" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_users" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "ts_booking_working_times" );

delete_option('tsbc_db_version');
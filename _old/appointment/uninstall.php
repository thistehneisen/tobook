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

global $wpdb, $table_prefix;
		
if ( is_multisite() ) {
	
	$list_blog = get_blog_list( 0, 'all' );;
	
	if ( isset($list_blog) && is_array($list_blog)) {
		foreach ($list_blog as $blog) {
			
			switch_to_blog($blog['blog_id']);
			
			// Tables
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_bookings" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_bookings_services" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_calendars" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_dates" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_employees" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_employees_services" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_fields" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_multi_lang" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_options" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_roles" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_services" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_users" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_working_times" );
				
			delete_option('as_db_version'); 
		}
		
		restore_current_blog();
	}
	
} else {
	
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_bookings" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_bookings_services" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_calendars" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_dates" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_employees" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_employees_services" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_fields" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_multi_lang" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_options" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_roles" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_services" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_users" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "appscheduler_working_times" );
	
	delete_option('as_db_version');
}
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
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_billers" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_calendar" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_categories" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_comment" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_customers" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_damage_products" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_date_format" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_deliveries" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_discounts" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_groups" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_invoice_types" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_login_attempts" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_pos_settings" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_products" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_purchases" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_purchase_items" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_quotes" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_quote_items" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_sales" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_sale_items" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_settings" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_subcategories" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_suppliers" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_suspended_bills" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_suspended_items" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_tax_rates" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_transfers" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_transfer_items" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_users" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_users_groups" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_warehouses" );
			$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_warehouses_products" );
			

			delete_option('cm_db_version');
		}

		restore_current_blog();
	}

} else {

	// Tables
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_billers" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_calendar" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_categories" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_comment" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_customers" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_damage_products" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_date_format" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_deliveries" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_discounts" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_groups" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_invoice_types" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_login_attempts" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_pos_settings" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_products" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_purchases" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_purchase_items" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_quotes" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_quote_items" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_sales" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_sale_items" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_settings" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_subcategories" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_suppliers" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_suspended_bills" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_suspended_items" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_tax_rates" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_transfers" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_transfer_items" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_users" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_users_groups" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_warehouses" );
	$wpdb->query( "DROP TABLE IF EXISTS " . $table_prefix . "sma_warehouses_products" );
	

	delete_option('cm_db_version');
}
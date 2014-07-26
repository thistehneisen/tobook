<?php
/**
 *
 * @package   Time Slots Booking Calendar
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

/**
 * Plugin class.
 *
 *
 * @package Time Slots Booking Calendar
 * @author  Your Name <email@example.com>
 */
class TimeSlotsBookingCalendar {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'time_slots_booking_calendar';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action( 'TODO', array( $this, 'action_method_name' ) );
		add_filter( 'TODO', array( $this, 'filter_method_name' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		// TODO: Define activation functionality here
		global $wpdb;
		
		$rb_db_version = get_option('tsbc_db_version');
		
		if ( isset($rb_db_version) && (int) $rb_db_version == 1) {
			
			return;
		}
		
		$filename = plugin_dir_path( __FILE__ ) . 'library/app/config/config.inc.php';
		
		$string = file_get_contents(plugin_dir_path( __FILE__ ) . 'library/app/config/config.sample.php');
		if ($string === FALSE)
		{
			exit;
		}
			
		
		$localpath = str_replace("\\", "/", dirname(getenv("SCRIPT_NAME")));
		$localpath = str_replace("\\", "/", $localpath);
		$localpath = str_replace("/wp-admin", "", $localpath);
		$localpath = preg_replace('/^\//', '', $localpath, 1) . '/';
		$localpath = !in_array($localpath, array('/', '\\')) ? $localpath : NULL;
		
		/*
		$string = str_replace('[hostname]', DB_HOST, $string);
		$string = str_replace('[username]', DB_USER, $string);
		$string = str_replace('[password]', DB_PASSWORD, $string);
		$string = str_replace('[database]', DB_NAME, $string);
		$string = str_replace('[prefix]', $wpdb->prefix, $string);
		
		$string = str_replace('[install_folder]', '/'. $localpath .'wp-content/plugins/' . dirname(plugin_basename( __FILE__ )) . '/library/', $string);
		$string = str_replace('[install_path]', plugin_dir_path( __FILE__ ) . 'library/', $string);
		$string = str_replace('[install_url]', plugins_url('', __FILE__ ) . '/library/', $string);
		
		
		$doc_root = $_SERVER['DOCUMENT_ROOT'];
		$doc_root = str_replace('\\', '/', $doc_root);
		
		$cwd = getcwd();
		$cwd = str_replace('\\', '/', $cwd);
		*/
		//$folder = str_replace($doc_root, '', $cwd);
		//$folder = preg_replace('/^\//', '', $folder, 1) . '/';
		$folder = '/'. $localpath .'wp-content/plugins/' . dirname(plugin_basename( __FILE__ )) . '/library/';
		
		$string = str_replace('[hostname]', DB_HOST, $string);
		$string = str_replace('[username]', DB_USER, $string);
		$string = str_replace('[password]', DB_PASSWORD, $string);
		$string = str_replace('[database]', DB_NAME, $string);
		$string = str_replace('[prefix]', $wpdb->prefix, $string);
		$string = str_replace('[folder]', $folder, $string);
		$string = str_replace('[install_folder]', $folder, $string);
		$string = str_replace('[install_path]', plugin_dir_path( __FILE__ ) . 'library/', $string);
		$string = str_replace('[install_url]', 'http://' . $_SERVER['SERVER_NAME'] . $folder, $string);
		
		
		if (is_writable($filename))
		{
			if (!$handle = fopen($filename, 'wb'))
			{
				exit;
			}
			 
			if (fwrite($handle, $string) === FALSE)
			{
				exit;
			}
		
			fclose($handle);
		} else {
			exit;
		}
		
		$sql = file_get_contents(plugin_dir_path( __FILE__ ) . 'library/app/config/database.sql');
		$sql = preg_replace(
				array('/INSERT\s+INTO\s+`/', '/DROP\s+TABLE\s+IF\s+EXISTS\s+`/', '/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`/'),
				array('INSERT INTO `'.$wpdb->prefix, 'DROP TABLE IF EXISTS `'.$wpdb->prefix, 'CREATE TABLE IF NOT EXISTS `'.$wpdb->prefix),
				$sql);
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$arr = preg_split('/;(\s+)?\n/', $sql);
		foreach ($arr as $v)
		{
			$v = trim($v);
			if (!empty($v))
			{
				dbDelta( $v );
			}
		} 
		
		$sql_opt = "INSERT INTO `".$wpdb->prefix."ts_booking_options` (`calendar_id`, `key`, `tab_id`, `group`, `value`, `description`, `label`, `type`, `order`) VALUES
(1, 'bf_include_address', 5, NULL, '1|2|3::2', 'Address<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 7),
(1, 'bf_include_captcha', 5, NULL, '1|2|3::3', 'Captcha<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 9),
(1, 'bf_include_city', 5, NULL, '1|2|3::2', 'City<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 6),
(1, 'bf_include_country', 5, NULL, '1|2|3::3', 'Country<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 5),
(1, 'bf_include_email', 5, NULL, '1|2|3::3', 'E-Mail address<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 2),
(1, 'bf_include_name', 5, NULL, '1|2|3::3', 'Name<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 1),
(1, 'bf_include_notes', 5, NULL, '1|2|3::2', 'Notes<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 4),
(1, 'bf_include_phone', 5, NULL, '1|2|3::2', 'Phone<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 3),
(1, 'bf_include_zip', 5, NULL, '1|2|3::3', 'Zip<br />\r\n<span style=\"font-size: 0.9em\">Select \"Yes\" if you want to include the field in the booking form, otherwise select \"No\"</span>', 'No|Yes|Yes (Required)', 'enum', 8),
(1, 'booking_status', 3, NULL, 'confirmed|pending|cancelled::pending', 'Default booking status<br />\r\n<span style=\"font-size: 0.9em\">set the default status for each booking after it is made</span>', NULL, 'enum', 5),
(1, 'calendar_height', 2, NULL, '400', 'Calendar height (px)', NULL, 'int', 2),
(1, 'calendar_width', 2, NULL, '500', 'Calendar width (px)', NULL, 'int', 1),
(1, 'color_bg_dayoff', 2, 'colors', 'a37ca3', 'Day off background', NULL, 'color', 211),
(1, 'color_bg_empty_cells', 2, 'colors', 'ededed', 'Empty slots', NULL, 'color', 205),
(1, 'color_bg_form', 2, 'colors', 'ffffff', 'Booking form background', NULL, 'color', 206),
(1, 'color_bg_full', 2, 'colors', 'd60d3f', 'Fully booked days', NULL, 'color', 207),
(1, 'color_bg_legend', 2, 'colors', 'ffffff', 'Legend background', NULL, 'color', 208),
(1, 'color_bg_month', 2, 'colors', '000000', 'Month background<br />\r\n<span style=\"font-size: 0.9em\">month name printed on top of the calendar</span>', NULL, 'color', 201),
(1, 'color_bg_slot', 2, 'colors', '58a222', 'Days with slots background', NULL, 'color', 203),
(1, 'color_bg_today', 2, 'colors', 'cc6600', 'Current date', NULL, 'color', 209),
(1, 'color_bg_tooltip', 2, 'colors', '000000', 'Tooltip background color', NULL, 'color', 210),
(1, 'color_bg_weekday', 2, 'colors', '3d3c3d', 'Week days background', NULL, 'color', 202),
(1, 'color_bg_partly', 2, 'colors', 'FFCC00', 'Partly booked days background', NULL, 'color', 204),
(1, 'color_bg_past', 2, 'colors', 'cfcfcf', 'Past days background', NULL, 'color', 212),
(1, 'color_border_form', 2, 'colors', 'ffffff', 'Form border color', NULL, 'color', 270),
(1, 'color_border_inner', 2, 'colors', 'ffffff', 'Inner border color', NULL, 'color', 271),
(1, 'color_border_legend', 2, 'colors', 'ffffff', 'Legend border color', NULL, 'color', 272),
(1, 'color_border_outer', 2, 'colors', 'ffffff', 'Outer border color', NULL, 'color', 273),
(1, 'color_font_day', 2, 'colors', 'ffffff', 'Days  font color', NULL, 'color', 240),
(1, 'color_font_dayoff', 2, 'colors', 'ffffff', 'Day off font color', NULL, 'color', 247),
(1, 'color_font_event', 2, 'colors', 'ffffff', 'Events font color', NULL, 'color', 241),
(1, 'color_font_form', 2, 'colors', '000000', 'Form font color', NULL, 'color', 242),
(1, 'color_font_legend', 2, 'colors', '000000', 'Legend font color', NULL, 'color', 243),
(1, 'color_font_month', 2, 'colors', 'ffffff', 'Month font color', NULL, 'color', 244),
(1, 'color_font_tooltip', 2, 'colors', 'ffffff', 'Tooltip font color', NULL, 'color', 245),
(1, 'color_font_weekday', 2, 'colors', 'ffffff', 'Week days font color', NULL, 'color', 246),
(1, 'color_font_partly', 2, 'colors', '000000', 'Partly booked days font color', NULL, 'color', 248),
(1, 'color_font_past', 2, 'colors', 'ffffff', 'Past days font color', NULL, 'color', 249),
(1, 'color_legend', 1, NULL, 'Yes|No::Yes', 'Show color legend below calendar', NULL, 'enum', 7),
(1, 'currency', 3, NULL, 'AUD|BRL|CAD|CZK|DKK|EUR|HKD|HUF|ILS|JPY|MYR|MXN|NOK|NZD|PHP|PLN|GBP|SGD|SEK|CHF|TWD|THB|USD::USD', 'Currency', NULL, 'enum', 1),
(1, 'date_format', 1, NULL, 'd.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j::d.m.Y', 'Date format', 'd.m.Y (25.09.2010)|m.d.Y (09.25.2010)|Y.m.d (2010.09.25)|j.n.Y (25.9.2010)|n.j.Y (9.25.2010)|Y.n.j (2010.9.25)|d/m/Y (25/09/2010)|m/d/Y (09/25/2010)|Y/m/d (2010/09/25)|j/n/Y (25/9/2010)|n/j/Y (9/25/2010)|Y/n/j (2010/9/25)|d-m-Y (25-09-2010)|m-d-Y (09-25-2010)|Y-m-d (2010-09-25)|j-n-Y (25-9-2010)|n-j-Y (9-25-2010)|Y-n-j (2010-9-25)', 'enum', 5),
(1, 'deposit_percent', 3, NULL, '10', 'Deposit payment %<br />\r\n<span style=\"font-size: 0.9em\">set % of the booking amount to be paid as a deposit. For full payment enter 100</span>', NULL, 'int', 2),
(1, 'email_address', 4, NULL, 'info@domain.com', 'Notification email address', NULL, 'string', 1),
(1, 'email_confirmation', 4, NULL, '1|2|3::2', 'Send confirmation email<br />\r\n<span style=\"font-size: 0.9em\">select if and when confirmation email should be sent to clients after they make a booking</span>', 'None|After booking form|After payment', 'enum', 2),
(1, 'email_confirmation_message', 4, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nName: {Name}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nCountry: {Country}\r\nCity: {City}\r\nAddress: {Address}\r\nZip: {Zip}\r\nNotes: {Notes}\r\n\r\nBooking details:\r\nBooking ID: {BookingID}\r\nBooking Slots: {BookingSlots}\r\nDeposit: {Deposit}\r\nTotal: {Total}\r\nTax: {Tax}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Confirmation email<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{Address}<br />\r\n{Zip}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 4),
(1, 'email_confirmation_subject', 4, NULL, 'Confirmation message', 'Confirmation email subject', NULL, 'string', 3),
(1, 'email_payment', 4, NULL, '1|3::1', 'Send payment confirmation email<br />\r\n<span style=\"font-size: 0.9em\">select if and when confirmation email should be sent to clients after they make a payment for their booking</span>', 'None|After payment', 'enum', 5),
(1, 'email_payment_message', 4, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nName: {Name}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nCountry: {Country}\r\nCity: {City}\r\nAddress: {Address}\r\nZip: {Zip}\r\nNotes: {Notes}\r\n\r\nBooking details:\r\nBooking ID: {BookingID}\r\nBooking Slots: {BookingSlots}\r\nDeposit: {Deposit}\r\nTotal: {Total}\r\nTax: {Tax}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Payment email<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{Address}<br />\r\n{Zip}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 7),
(1, 'email_payment_subject', 4, NULL, 'Payment message', 'Payment email subject', NULL, 'string', 6),
(1, 'month_year_format', 1, NULL, 'Month Year|Month, Year|Year Month|Year, Month::Month Year', 'Month / Year format', NULL, 'enum', 4),
(1, 'payment_authorize_key', 3, NULL, '', 'Authorize.net transaction key', NULL, 'string', 34),
(1, 'payment_authorize_mid', 3, NULL, '', 'Authorize.net merchant ID', NULL, 'string', 35),
(1, 'payment_disable', 3, NULL, 'No|Yes::No', 'Disable payments<br /><span style=\"font-size: 0.9em\">to disable payments and only accept bookings, set this to \"Yes\"</span>', NULL, 'enum', 30),
(1, 'payment_enable_authorize', 3, NULL, 'Yes|No::No', 'Allow Authorize.net payments', NULL, 'enum', 33),
(1, 'payment_enable_creditcard', 3, NULL, 'Yes|No::No', 'Allow payments with Credit cards', NULL, 'enum', 38),
(1, 'payment_enable_paypal', 3, NULL, 'Yes|No::Yes', 'Allow PayPal payments', NULL, 'enum', 31),
(1, 'payment_status', 3, NULL, 'confirmed|pending|cancelled::confirmed', 'Default booking status after payment<br />\r\n<span style=\"font-size: 0.9em\">set the default status for each booking after payment is made for it</span>', NULL, 'enum', 6),
(1, 'paypal_address', 3, NULL, 'paypal@domain.com', 'PayPal business email address', NULL, 'string', 32),
(1, 'show_tooltip', 1, NULL, 'Yes|No::Yes', 'Show tooltip on date hover', NULL, 'enum', 2),
(1, 'size_border_form', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Booking form border size', NULL, 'enum', 401),
(1, 'size_border_inner', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Inner border size', NULL, 'enum', 402),
(1, 'size_border_legend', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Legend border size', NULL, 'enum', 403),
(1, 'size_border_outer', 2, 'sizes', '0|1|2|3|4|5|6|7|8|9::1', 'Outer border size', NULL, 'enum', 404),
(1, 'size_font_day', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::12', 'Days font size', NULL, 'enum', 405),
(1, 'size_font_event', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::12', 'Events font size', NULL, 'enum', 406),
(1, 'size_font_month', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::20', 'Month font size', NULL, 'enum', 407),
(1, 'size_font_tooltip', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::10', 'Tooltip font size', NULL, 'enum', 408),
(1, 'size_font_weekday', 2, 'sizes', '10|12|14|16|18|20|22|24|26|28|30::12', 'Week days font size', NULL, 'enum', 409),
(1, 'style_font_day', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-style: italic', 'Dates font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 301),
(1, 'style_font_event', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::text-decoration: underline', 'Events font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 302),
(1, 'style_font_family', 2, 'fonts', 'Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana::Arial', 'Font family<br />\r\n<span style=\"font-size: 0.9em\">default font family for all the text on your calendar</span>', 'Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana', 'enum', 300),
(1, 'style_font_month', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-weight: bold', 'Month font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 304),
(1, 'style_font_weekday', 2, 'fonts', 'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-weight: normal', 'Week days font style', 'Normal|Bold|Italic|Underline|Bold Italic', 'enum', 305),
(1, 'tax', 3, NULL, '0', 'Tax payment %<br />\r\n<span style=\"font-size: 0.9em\">set % for tax that clients pay</span>', NULL, 'float', 3),
(1, 'thank_you_page', 3, NULL, 'http://varaa.com/', '\"Thank you\" page location<br />\r\n<span style=\"font-size: 0.9em\">this is the page where people will be redirected after paying</span>', NULL, 'string', 7),
(1, 'timezone', 1, NULL, '-43200|-39600|-36000|-32400|-28800|-25200|-21600|-18000|-14400|-10800|-7200|-3600|0|3600|7200|10800|14400|18000|21600|25200|28800|32400|36000|39600|43200|46800::0', 'Timezone<br />\r\n<span style=\"font-size: 0.9em\">select your time zone so booking interval can be limited based on your time zone</span>', 'GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00', 'enum', 9),
(1, 'time_format', 1, NULL, 'H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A::H:i', 'Time format', 'H:i (09:45)|G:i (9:45)|h:i (09:45)|h:i a (09:45 am)|h:i A (09:45 AM)|g:i (9:45)|g:i a (9:45 am)|g:i A (9:45 AM)', 'enum', 6),
(1, 'ts_booking_form_position', 1, NULL, '1|2|3::1', 'Timeslot details and booking form position', 'Below calendar|Replace calendar|Right of calendar', 'enum', 1),
(1, 'week_start', 1, NULL, '0|1|2|3|4|5|6::1', 'Week start day', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', 3),
(1, 'hide_prices', 3, NULL, 'Yes|No::No', 'Hide prices', NULL, 'enum', 21),
(1, 'reminder_body', 6, NULL, 'You''ve just made a booking.\r\n\r\nPersonal details:\r\nName: {Name}\r\nE-Mail: {Email}\r\nPhone: {Phone}\r\nCountry: {Country}\r\nCity: {City}\r\nState: {State}\r\nZip: {Zip}\r\nAddress: {Address}\r\nNotes: {Notes}\r\n\r\nBooking details:\r\nBooking ID: {BookingID}\r\nBooking Slots: {BookingSlots}\r\nDeposit: {Deposit}\r\nTotal: {Total}\r\nTax: {Tax}\r\n\r\nIf you want to cancel your booking follow next link: {CancelURL}\r\n\r\nThank you, we will contact you ASAP.', 'Email Reminder body<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{State}<br />\r\n{Zip}<br />\r\n{Address}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 4),
(1, 'reminder_email_before', 6, NULL, '2', 'Send email reminder', NULL, 'int', 2),
(1, 'reminder_enable', 6, NULL, 'Yes|No::Yes', 'Enable notifications', NULL, 'enum', 1),
(1, 'reminder_sms_country_code', 6, NULL, '358', 'SMS country code', NULL, 'int', 5),
(1, 'reminder_sms_hours', 6, NULL, '1', 'Send SMS reminder', NULL, 'int', 5),
(1, 'reminder_sms_message', 6, NULL, '{Name}, booking reminder\r\n\r\n{BookingSlots}', 'SMS message<br />\r\n<u>Available Tokens:</u><br />\r\n{Name}<br />\r\n{Email}<br />\r\n{Phone}<br />\r\n{Country}<br />\r\n{City}<br />\r\n{State}<br />\r\n{Zip}<br />\r\n{Address}<br />\r\n{Notes}<br />\r\n{BookingID}<br />\r\n{BookingSlots}<br />\r\n{Deposit}<br />\r\n{Total}<br />\r\n{Tax}<br />\r\n{PaymentMethod}<br />\r\n{PaymentOption}<br />\r\n{CCType}<br />\r\n{CCNum}<br />\r\n{CCExp}<br />\r\n{CCSec}<br />\r\n{CancelURL}<br />', NULL, 'text', 7),
(1, 'reminder_subject', 6, NULL, 'Booking Reminder', 'Email Reminder subject', NULL, 'string', 3);";
		
		mysql_query($sql_opt) or die(mysql_error());
		
		$data['username'] = 'admin';
		$data['role_id'] = 1;
		$data['session_id'] = md5(uniqid(rand(), true));
		$data['password'] = 'admin123';
		
		$rows_affected = $wpdb->insert( $wpdb->prefix.'ts_booking_users', $data );
		
		add_option( "tsbc_db_version", '1' );
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * TODO:
		 *
		 */
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Time Slots Booking Calendar', $this->plugin_slug ),
			__( 'TS Booking Calendar', $this->plugin_slug ),
			'administrator',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		
		$plugins_url = plugins_url('', __FILE__ );
		
		include_once( 'views/admin.php' );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}
}
<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Event Calendar
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Your Name or Company Name
 */

global $table_prefix;

if ( isset($_COOKIE["cashierinstall" . $table_prefix]) && $_COOKIE["cashierinstall" . $table_prefix] == 1 ) {
	$install = '&amp;install=1';
	
} else $install = '&amp;module=home';
?>
<div class="wrap">
	<!-- TODO: Provide markup for your options page here. -->
	<iframe id="frame" src="<?php echo $plugins_url . '/library/index.php?prefix='. $table_prefix . $install; ?>" width="100%" height="1300px">
   	</iframe>
</div>

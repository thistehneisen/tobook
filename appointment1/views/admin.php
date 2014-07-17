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

?>
<div class="wrap">

	<?php //screen_icon(); ?>
	<!-- <h2><?php echo esc_html( get_admin_page_title() ); ?></h2> -->
	
	<!-- TODO: Provide markup for your options page here. -->
	<iframe id="frame" src="<?php echo $plugins_url . '/library/index.php?as_pf='. $table_prefix; ?>" width="100%" height="1000px">
   	</iframe>
</div>

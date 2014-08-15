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
<div class="wrap" style="margin:0px;padding:0px;overflow:hidden">

	<?php //screen_icon(); ?>
	<!-- <h2><?php echo esc_html( get_admin_page_title() ); ?></h2> -->
	<script language="javascript" type="text/javascript">
	  function resizeIframe(obj) {
		  var height = 0;
		  height = screen.height - 148;
	    //obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
		  obj.style.height = height  + 'px';
	  }
	</script>
	<!-- TODO: Provide markup for your options page here. -->
	<iframe name="Stack" src="<?php echo $plugins_url . '/library/index.php?as_pf='. $table_prefix; ?>" width="100%" frameborder="0" id="iframe" onload='javascript:resizeIframe(this);'>
   	</iframe>
</div>

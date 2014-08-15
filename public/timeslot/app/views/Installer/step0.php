<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Installer
 */
?>
<h3>Notice</h3>
<p class="form_install">
You need to install the script first. Go to <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=Installer&amp;action=step1&amp;install=1">Install</a> page.
</p>
<?php
echo '<iframe width="1" height="1" src="http://www.phpscripthelper.com/updates.php?script='.SCRIPT_ID.'&version='.SCRIPT_VERSION.'&notes=install" scrolling="no" frameborder="0"></iframe>';
?>
<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Admin
 */
?>
<div class="login-box">
	
	<h3><?php echo $TS_LANG['login_login']; ?></h3>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=Admin&amp;action=login" method="post" id="frmLoginAdmin" class="tsbc-form">
		<input type="hidden" name="login_user" value="1" />
		<p><label class="title"><?php echo $TS_LANG['login_username']; ?>:</label><input name="login_username" type="text" class="text w300" id="login_username" /></p>
		<p><label class="title"><?php echo $TS_LANG['login_password']; ?>:</label><input name="login_password" type="password" class="text w300" id="login_password" /></p>
		<p><label class="title">&nbsp;</label><input type="submit" value="" class="button button_login" /></p>
		<?php
		if (isset($_GET['err']))
		{
			switch ($_GET['err'])
			{
				case 1:
					?><p><label class="title"><?php echo $TS_LANG['login_error']; ?>:</label><span class="left"><?php echo $TS_LANG['login_err'][1]; ?></span></p><?php
					break;
				case 2:
					?><p><label class="title"><?php echo $TS_LANG['login_error']; ?>:</label><span class="left"><?php echo $TS_LANG['login_err'][2]; ?></span></p><?php
					break;
				case 3:
					?><p><label class="title"><?php echo $TS_LANG['login_error']; ?>:</label><span class="left"><?php echo $TS_LANG['login_err'][3]; ?></span></p><?php
					break;
			}
		}
		?>
	</form>
</div>

<div class="align_right t10">
Copyright &copy; <?php echo date("Y"); ?> <a href="http://varaa.com/" target="_blank">varaa</a>
</div>
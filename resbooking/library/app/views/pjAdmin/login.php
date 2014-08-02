<div class="login-box">
	
	<h3><?php echo $RB_LANG['login_login']; ?></h3>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=login&amp;rbpf=<?php echo PREFIX; ?>" method="post" id="frmLoginAdmin" class="form">
		<input type="hidden" name="login_user" value="1" />
		<p><label class="title"><?php echo $RB_LANG['login_email']; ?>:</label><input name="login_email" type="text" class="text w300" id="login_email" /></p>
		<p><label class="title"><?php echo $RB_LANG['login_password']; ?>:</label><input name="login_password" type="password" class="text w300" id="login_password" /></p>
		<p><label class="title">&nbsp;</label><input type="submit" value="" class="button button_login" /></p>
		<?php
		if (isset($_GET['err']))
		{
			switch ($_GET['err'])
			{
				case 1:
					?><p><label class="title"><?php echo $RB_LANG['login_error']; ?>:</label><span class="left"><?php echo $RB_LANG['login_err'][1]; ?></span></p><?php
					break;
				case 2:
					?><p><label class="title"><?php echo $RB_LANG['login_error']; ?>:</label><span class="left"><?php echo $RB_LANG['login_err'][2]; ?></span></p><?php
					break;
				case 3:
					?><p><label class="title"><?php echo $RB_LANG['login_error']; ?>:</label><span class="left"><?php echo $RB_LANG['login_err'][3]; ?></span></p><?php
					break;
			}
		}
		?>
	</form>
</div>
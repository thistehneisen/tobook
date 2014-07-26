<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 'ok':
			$STORAGE = &$_SESSION[$controller->default_product]['Installer'];
			?>
			<h3>Step 1: MySQL server data</h3>
			<form action="index.php?controller=pjInstaller&amp;action=step2&amp;install=1" method="post" id="frmStep1" class="form_install">
				<input type="hidden" name="step1" value="1" />
			
				<p><label class="title">Hostname <span class="red">*</span></label> <input type="text" name="hostname" value="<?php echo isset($STORAGE['hostname']) ? $STORAGE['hostname'] : 'localhost'; ?>" /></p>
				<p><label class="title">Username <span class="red">*</span></label> <input type="text" name="username" value="<?php echo isset($STORAGE['username']) ? $STORAGE['username'] : NULL; ?>" /></p>
				<p><label class="title">Password</label> <input type="text" name="password" value="<?php echo isset($STORAGE['password']) ? $STORAGE['password'] : NULL; ?>" /></p>
				<p><label class="title">Database <span class="red">*</span></label> <input type="text" name="database" value="<?php echo isset($STORAGE['database']) ? $STORAGE['database'] : NULL; ?>" /></p>
				<p><label class="title">Table prefix</label> <input type="text" name="prefix" value="<?php echo isset($STORAGE['prefix']) ? $STORAGE['prefix'] : NULL; ?>" /></p>
				<p><label class="title">&nbsp;</label> <input type="submit" value="Next &gt;&gt;" /></p>
			
			</form>
			
			<img src="http://www.stivasoft.com/trackInstall.php?version=<?php echo SCRIPT_VERSION; ?>&script=<?php echo SCRIPT_ID; ?>" style="display: none" />
			<?php
			break;
		case 1:
			?>
			<h3>Error 1</h3>
			<?php
			foreach ($tpl['err_arr'] as $err)
			{
				?><p class="form_install"><?php
				echo ucfirst($err[0]) . " '<strong>" . $err[1] . "</strong>' is not writable.
				<br /><br />".$err[2]." '<strong>".$err[1]."</strong>'";
				?></p><?php
			}
			break;
		case 7:
			?><p class="form_install"><?php echo $RB_LANG['status'][7]; ?></p><?php
			break;
	}
}
?>
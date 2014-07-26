<?php
if (isset($_GET['err']))
{
	switch ($_GET['err'])
	{
		case 1:
			?>
			<h3>Update MySQL database</h3>
			<p class="form_install">Update has been successful</p>
			<?php
			break;
		case 2:
			?>
			<h3>Update MySQL database</h3>
			<p class="form_install">Update has not been successful</p>
			<?php
			break;
	}
} else {
	?>
	<h3>Update MySQL database</h3>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=updateDB" method="post" id="frmUpdate" class="form_install">
		<input type="hidden" name="update" value="1" />
	
		<p><label class="title">Current version:</label><span><?php echo $tpl['currentVersion']; ?></span></p>
		<p><label class="title">Available version:</label><span><?php echo SCRIPT_BUILD; ?></span></p>
		<?php 
		$compare = version_compare($tpl['currentVersion'], SCRIPT_BUILD);
		switch ($compare)
		{
			case -1:
				# the first version is lower than the second
				if (isset($tpl['availableUpdate']))
				{
					?>
					<input type="hidden" name="currentVersion" value="<?php echo $tpl['currentVersion']; ?>" />
					<p><label class="title">&nbsp;</label> <input type="submit" value="Update" /></p><?php
				} else {
					?><p>Update SQL file not found</p><?php
				}
				break;
			case 0:
				# they are equal
				?><p>Your version is up to date</p><?php
				break;
			case 1:
				# the second is lower
				?><p>You have installed the most recent version</p><?php
				break;
		}
		?>
	</form>
	<?php
}
?>
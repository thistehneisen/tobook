<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminCalendars
 */
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			?><p class="status_err"><?php echo $TS_LANG['status'][1]; ?></p><?php
			break;
		case 2:
			?><p class="status_err"><?php echo $TS_LANG['status'][2]; ?></p><?php
			break;
	}
} else {
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $TS_LANG['calendar_update']; ?></a></li>
		</ul>
		<div id="tabs-1">
		
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCalendars&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>" method="post" id="frmCreateCalendar" class="tsbc-form">
				<input type="hidden" name="calendar_update" value="1" />
				<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
				
				<p><label class="title"><?php echo $TS_LANG['calendar_title']; ?>:</label><input type="text" name="calendar_title" id="calendar_title" class="text w400 required" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['calendar_title'])); ?>" /></p>
				<?php if ($controller->isAdmin() && $controller->isMultiUser()) : ?>
				<p>
					<label class="title"><?php echo $TS_LANG['calendar_owner']; ?>:</label>
					<select name="user_id" id="user_id" class="select">
						<option value=""><?php echo $TS_LANG['calendar_choose']; ?></option>
						<?php
						foreach ($tpl['user_arr'] as $v)
						{
							if (isset($tpl['arr']['user_id']) && $tpl['arr']['user_id'] == $v['id'])
							{
								?><option value="<?php echo $v['id']; ?>" selected="selected"><?php echo stripslashes($v['username']); ?></option><?php
							} else {
								?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['username']); ?></option><?php
							}
						}
						?>
					</select>
				</p>
				<?php endif; ?>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="" class="button button_save" />
				</p>
				
			</form>
		</div>
	</div>
	<?php
}
?>
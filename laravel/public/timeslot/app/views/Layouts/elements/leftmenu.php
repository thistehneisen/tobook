<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Layouts.elements
 */
if ($controller->isMultiCalendar())
{
	?>
	<div class="leftmenu-top"></div>
	<div class="leftmenu-middle">
		<ul class="tsbc-menu">
			<li>
				<select name="_calendar_id" id="tsbc-calendar-id" class="select" style="width: 199px">
					<option value=""><?php echo $TS_LANG['menu_choose_calendar']; ?></option>
				<?php
				if (isset($tpl['_calendar_arr']))
				{
					foreach ($tpl['_calendar_arr'] as $v)
					{
						if (isset($_SESSION[$controller->default_user]['calendar_id']) && $_SESSION[$controller->default_user]['calendar_id'] == $v['id'])
						{
							?><option value="<?php echo $v['id']; ?>" selected="selected"><?php echo stripslashes($v['calendar_title']); ?></option><?php
						} else {
							?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['calendar_title']); ?></option><?php
						}
					}
				}
				?>
				</select>
			</li>
		</ul>
	</div>
	<div class="leftmenu-bottom"></div>
	<?php
}
?>

<div class="leftmenu-top"></div>
<div class="leftmenu-middle">
	<ul class="tsbc-menu">
		<?php
		if ($controller->isMultiCalendar())
		{
			?>
			<li><a class="<?php echo $_GET['controller'] == 'AdminCalendars' ? 'tsbc-menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCalendars"><span class="tsbc-menu-calendar">&nbsp;</span><?php echo $TS_LANG['menu_calendar']; ?></a></li>
			<?php
		} else {
			?>
			<li><a class="<?php echo $_GET['controller'] == 'AdminCalendars' ? 'tsbc-menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCalendars&amp;action=view&amp;cid=<?php echo $controller->getCalendarId(); ?>"><span class="tsbc-menu-calendar">&nbsp;</span><?php echo $TS_LANG['menu_calendar']; ?></a></li>
			<?php
		}
		?>
		<li><a class="<?php echo $_GET['controller'] == 'AdminBookings' ? 'tsbc-menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings"><span class="tsbc-menu-bookings">&nbsp;</span><?php echo $TS_LANG['menu_bookings']; ?></a></li>
		<li><a class="<?php echo $_GET['controller'] == 'AdminTime' ? 'tsbc-menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminTime"><span class="tsbc-menu-prices">&nbsp;</span><?php echo $TS_LANG['menu_time']; ?></a></li>
		<?php
		if ($controller->isAdmin())
		{
			if ($controller->isMultiUser())
			{
				?><li><a class="<?php echo $_GET['controller'] == 'AdminUsers' ? 'tsbc-menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminUsers"><span class="tsbc-menu-users">&nbsp;</span><?php echo $TS_LANG['menu_users']; ?></a></li><?php
			}
		}
		?>
		<li><a class="<?php echo $_GET['controller'] == 'AdminOptions' && $_GET['action'] == 'index' ? 'tsbc-menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminOptions"><span class="tsbc-menu-options">&nbsp;</span><?php echo $TS_LANG['menu_options']; ?></a></li>
		<li><a class="<?php echo $_GET['controller'] == 'AdminOptions' && $_GET['action'] == 'install' ? 'tsbc-menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminOptions&amp;action=install"><span class="tsbc-menu-install">&nbsp;</span><?php echo $TS_LANG['menu_install']; ?></a></li>
		<?php
		if ((int) $controller->getCalendarId() > 0)
		{
			?><li><a href="preview.php?cid=<?php echo $controller->getCalendarId(); ?>&owner_id=<?php echo $controller->getOwnerId();?>" target="_blank"><span class="tsbc-menu-preview">&nbsp;</span><?php echo $TS_LANG['menu_preview']; ?></a></li><?php
		}
		?>
		<!-- <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=Admin&amp;action=logout"><span class="tsbc-menu-logout">&nbsp;</span><?php echo $TS_LANG['menu_logout']; ?></a></li>-->
	</ul>
</div>
<div class="leftmenu-bottom"></div>

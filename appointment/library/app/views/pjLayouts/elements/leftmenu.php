<?php
if (pjObject::getPlugin('pjOneAdmin') !== NULL)
{
	$controller->requestAction(array('controller' => 'pjOneAdmin', 'action' => 'pjActionMenu'));
}
global $as_pf;
global $owner_id;
?>

<div class="leftmenu-top"></div>
<div class="leftmenu-middle">
	<ul class="menu">
		<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdmin' && ( $_GET['action'] == 'pjActionIndex' || $_GET['action'] == 'pjActionEmployeeWeek' ) ? 'menu-focus' : NULL; ?>"><span class="menu-dashboard">&nbsp;</span><?php __('menuDashboard'); ?></a></li>
		<?php
		if ($controller->isEmployee())
		{
			?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionList&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdminBookings' ? 'menu-focus' : NULL; ?>"><span class="menu-bookings">&nbsp;</span><?php __('menuBookings'); ?></a></li><?php
			?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionProfile&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo ($_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'pjActionProfile') || $_GET['controller'] == 'pjAdminTime' ? 'menu-focus' : NULL; ?>"><span class="menu-employees">&nbsp;</span><?php __('menuProfile'); ?></a></li><?php
		}
		if ($controller->isAdmin())
		{
			?>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdminBookings' || ($_GET['controller'] == 'pjInvoice' && $_GET['action'] != 'pjActionIndex') ? 'menu-focus' : NULL; ?>"><span class="menu-bookings">&nbsp;</span><?php __('menuBookings'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdminServices' ? 'menu-focus' : NULL; ?>"><span class="menu-services">&nbsp;</span><?php __('menuServices'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdminEmployees' || ($_GET['controller'] == 'pjAdminTime' && isset($_GET['foreign_id'])) ? 'menu-focus' : NULL; ?>"><span class="menu-employees">&nbsp;</span><?php __('menuEmployees'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionIndex&amp;tab=1&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo ($_GET['controller'] == 'pjAdminOptions' && in_array($_GET['action'], array('pjActionIndex'))) || in_array($_GET['controller'], array('pjAdminLocales', 'pjBackup', 'pjLocale', 'pjCountry', 'pjSms')) || ($_GET['controller'] == 'pjAdminTime' && !isset($_GET['foreign_id'])) || ($_GET['controller'] == 'pjInvoice' && $_GET['action'] == 'pjActionIndex') ? 'menu-focus' : NULL; ?>"><span class="menu-options">&nbsp;</span><?php __('menuOptions'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;action=pjActionEmployees&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdminReports' ? 'menu-focus' : NULL; ?>"><span class="menu-reports">&nbsp;</span><?php __('menuReports'); ?></a></li>
			<!-- <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminUsers&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdminUsers' ? 'menu-focus' : NULL; ?>"><span class="menu-users">&nbsp;</span><?php __('menuUsers'); ?></a></li>-->
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionInstall&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'pjActionInstall' ? 'menu-focus' : NULL; ?>"><span class="menu-install">&nbsp;</span><?php __('menuInstall'); ?></a></li>
			<li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionPreview&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>" target="_blank"><span class="menu-preview">&nbsp;</span><?php __('menuPreview'); ?></a></li>
			<?php
		}
		?>
		<!-- <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionLogout&amp;as_pf=<?php echo $as_pf; ?>"><span class="menu-logout">&nbsp;</span><?php __('menuLogout'); ?></a></li>-->
	</ul>

</div>
<div class="leftmenu-bottom"></div>

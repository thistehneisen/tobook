<!doctype html>
<html>
	<head>
		<title>Restaurant Booking Script</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->css as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : INSTALL_URL).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		foreach ($controller->js as $js)
		{
			echo '<script type="text/javascript" src="'.(isset($js['remote']) && $js['remote'] ? NULL : INSTALL_URL).$js['path'].htmlspecialchars($js['file']).'"></script>';
		}
		?>
	</head>
	<body>
		<div id="container">
    		<div id="header">
				<a href="http://varaa.com/" id="logo" target="_blank"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>logo.png" alt="Restaurant Booking Script" /></a>
			</div>
			
			<div id="middle">
				<div id="leftmenu">
					<?php
					if (is_file(INSTALL_PATH."one-admin.php")) {
						$OneAdmin["pos"] = 'left';
						include(INSTALL_PATH."one-admin.php");
					}
					?>
					<div class="leftmenu-top"></div>
					<div class="leftmenu-middle">
						<ul class="menu">
							<?php /*<li><a class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'dashboard' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=dashboard&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-home">&nbsp;</span><?php echo $RB_LANG['menu_home']; ?></a></li>*/?>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminBookings' && $_GET['action'] != 'formstyle' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-bookings">&nbsp;</span><?php echo $RB_LANG['menu_bookings']; ?></a></li>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminReports' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-reports">&nbsp;</span><?php echo $RB_LANG['menu_reports']; ?></a></li>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminVouchers' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminVouchers&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-vouchers">&nbsp;</span><?php echo $RB_LANG['menu_vouchers']; ?></a></li>
							<li><a class="<?php echo ($_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'index') || $_GET['action'] == 'formstyle' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-options">&nbsp;</span><?php echo $RB_LANG['menu_options']; ?></a></li>
							<li><a class="<?php echo in_array($_GET['controller'], array('pjAdminTables', 'pjAdminTime', 'pjAdminMaps')) ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-restaurant">&nbsp;</span><?php echo $RB_LANG['menu_restaurant']; ?></a></li>
							<li><a href="preview.php?rbpf=<?php echo PREFIX; ?>" target="_blank"><span class="menu-preview">&nbsp;</span><?php echo $RB_LANG['menu_preview']; ?></a></li>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'install' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=install&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-install">&nbsp;</span><?php echo $RB_LANG['menu_install']; ?></a></li>
							<!-- <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=logout&amp;rbpf=<?php echo PREFIX; ?>"><span class="menu-logout">&nbsp;</span><?php echo $RB_LANG['menu_logout']; ?></a></li>-->
						</ul>
					</div>
					<div class="leftmenu-bottom"></div>
				</div>
				<div id="right">
					<div class="content-top"></div>
					<div class="content-middle" id="content">
					<?php require $content_tpl; ?>
					</div>
					<div class="content-bottom"></div>
				</div> <!-- content -->
				<div class="clear_both"></div>
			</div> <!-- middle -->
		
		</div> <!-- container -->
		<div id="footer-wrap">
			<div id="footer">
			   	<p>Copyright &copy; <?php echo date("Y"); ?> <a href="http://varaa.com/" target="_blank">varaa.com</a></p>
	        </div>
        </div>
	</body>
</html>
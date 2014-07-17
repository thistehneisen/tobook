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
	
	<script>
		function onChangeLanguage(){
			var language = $("#language").val();
			$.ajax({
				type: "POST",
				data: { language : language},
				url: "index.php?controller=pjAdmin&action=setLanguage"
			}).success(function (data) {
				window.location.reload();
			});			
		}
	</script>
		
	<body>
		<div id="container">
    		<div id="header">
				<a href="http://varaa.com/" id="logo" target="_blank"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>logo.png" alt="Restaurant Booking Script" /></a>
				<?php $currentLang = $_SESSION[ 'language' ]; ?>
				<select id="language" style="position:absolute; right: 00px; top: 80px; width: 120px;height:25px;" onchange="onChangeLanguage()">
					<option value="en" <?php if( $currentLang == "en" ) echo " selected"; ?>>English</option>
					<option value="fi" <?php if( $currentLang == "fi" ) echo " selected"; ?>>Finland</option>
				</select>				
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
							<?php /*<li><a class="<?php echo $_GET['controller'] == 'pjAdmin' && $_GET['action'] == 'dashboard' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=dashboard"><span class="menu-home">&nbsp;</span><?php echo $RB_LANG['menu_home']; ?></a></li>*/?>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminBookings' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule"><span class="menu-bookings">&nbsp;</span><?php echo $RB_LANG['menu_bookings']; ?></a></li>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminReports' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports"><span class="menu-reports">&nbsp;</span><?php echo $RB_LANG['menu_reports']; ?></a></li>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminVouchers' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminVouchers"><span class="menu-vouchers">&nbsp;</span><?php echo $RB_LANG['menu_vouchers']; ?></a></li>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'index' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions"><span class="menu-options">&nbsp;</span><?php echo $RB_LANG['menu_options']; ?></a></li>
							<li><a class="<?php echo in_array($_GET['controller'], array('pjAdminTables', 'pjAdminTime', 'pjAdminMaps')) ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime"><span class="menu-restaurant">&nbsp;</span><?php echo $RB_LANG['menu_restaurant']; ?></a></li>
							<li><a href="preview.php" target="_blank"><span class="menu-preview">&nbsp;</span><?php echo $RB_LANG['menu_preview']; ?></a></li>
							<li><a class="<?php echo $_GET['controller'] == 'pjAdminOptions' && $_GET['action'] == 'install' ? 'menu-focus' : NULL; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=install"><span class="menu-install">&nbsp;</span><?php echo $RB_LANG['menu_install']; ?></a></li>
							<!-- <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=logout"><span class="menu-logout">&nbsp;</span><?php echo $RB_LANG['menu_logout']; ?></a></li>-->
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
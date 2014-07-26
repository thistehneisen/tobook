<?php
$menu_focus = ' ui-tabs-selected ui-state-active';
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminTime' ? $menu_focus : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime"><?php echo $RB_LANG['menu_wtime']; ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminMaps' ? $menu_focus : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminMaps"><?php echo $RB_LANG['menu_seat_map']; ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminTables' ? $menu_focus : NULL; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTables"><?php echo $RB_LANG['menu_tables']; ?></a></li>
	</ul>
</div>
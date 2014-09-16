<?php
$active = " ui-tabs-active ui-state-active";
global $as_pf;
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminEmployees' || $_GET['action'] != 'pjActionUpdate' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionUpdate&amp;as_pf=<?php echo $as_pf; ?>&amp;id=<?php echo $employee_id; ?>"><?php __('employee_general'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminTime' || $_GET['action'] != 'pjActionIndex' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;type=employee&amp;foreign_id=<?php echo $employee_id; ?>&amp;as_pf=<?php echo $as_pf; ?>"><?php __('time_default_wt'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminTime' || $_GET['action'] != 'pjActionCustom' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionCustom&amp;type=employee&amp;foreign_id=<?php echo $employee_id; ?>&amp;as_pf=<?php echo $as_pf; ?>"><?php __('time_custom_wt'); ?></a></li>
		<?php
		if ($_GET['action'] == 'pjActionUpdateCustom')
		{
			?><li class="ui-state-default ui-corner-top<?php echo $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionUpdateCustom&amp;type=employee&amp;as_pf=<?php echo $as_pf; ?>&amp;foreign_id=<?php echo $employee_id; ?>&amp;id=<?php echo @$tpl['arr']['id']; ?>"><?php __('time_update_custom'); ?></a></li><?php
		}
		?>
	</ul>
</div>
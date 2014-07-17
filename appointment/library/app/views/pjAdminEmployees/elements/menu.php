<?php
$active = " ui-tabs-active ui-state-active";

global $as_pf;
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminEmployees' || $_GET['action'] != 'pjActionIndex' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuEmployees'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminEmployees' || $_GET['action'] != 'pjActionCreate' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>"><?php __('employee_add'); ?></a></li>
		<?php
		if (($_GET['controller'] == 'pjAdminEmployees' && $_GET['action'] == 'pjActionUpdate' && isset($_GET['id'])) ||
			($_GET['controller'] == 'pjAdminTime' && isset($_GET['foreign_id']))
		)
		{
			?><li class="ui-state-default ui-corner-top<?php echo $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionUpdate&amp;as_pf=<?php echo $as_pf; ?>&amp;id=<?php echo $employee_id; ?>"><?php __('employee_update'); ?></a></li><?php
		}
		?>
		
		<li class="ui-state-default ui-corner-top<?php echo $_GET['action'] == 'pjActionFreetime' ? $active : null; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionFreetime&amp;as_pf=<?php echo $as_pf; ?>">Vapaat</a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['action'] == 'pjActionCustomTimes' ? $active : null; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionCustomTimes&amp;as_pf=<?php echo $as_pf; ?>">Työvuorosuunnittelu</a></li>
	</ul>
</div>
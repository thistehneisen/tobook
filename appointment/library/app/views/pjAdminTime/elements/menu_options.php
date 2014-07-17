<?php
$active = " ui-tabs-active ui-state-active";
global $as_pf;
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['action'] != 'pjActionIndex' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('time_default'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['action'] != 'pjActionCustom' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionCustom&amp;as_pf=<?php echo $as_pf; ?>"><?php __('time_custom'); ?></a></li>
		<?php
		if ($_GET['action'] == 'pjActionUpdateCustom')
		{
			?><li class="ui-state-default ui-corner-top<?php echo $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionUpdateCustom&amp;as_pf=<?php echo $as_pf; ?>&amp;id=<?php echo @$tpl['arr']['id']; ?>"><?php __('time_update_custom'); ?></a></li><?php
		}
		?>
	</ul>
</div>
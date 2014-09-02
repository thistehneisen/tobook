<?php
$active = " ui-tabs-active ui-state-active";
global $as_pf;
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminOptions' || $_GET['tab'] != '1' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;tab=1&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuGeneral'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminOptions' || !in_array($_GET['tab'], array(3,4,5,6,7)) ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;tab=3&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuBookings'); ?></a></li>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjAdminTime' || isset($_GET['foreign_id']) ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuTime'); ?></a></li>
		<?php
		if ($controller->isAdmin() && pjObject::getPlugin('pjInvoice') !== NULL)
		{
			?><li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjInvoice' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('plugin_invoice_menu_invoices'); ?></a></li><?php
		}
		if ($controller->isAdmin() && pjObject::getPlugin('pjSms') !== NULL)
		{
			?><!-- <li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] != 'pjSms' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjSms&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('plugin_sms_menu_sms'); ?></a></li>--><?php
		}
		?>
		<li class="ui-state-default ui-corner-top<?php echo $_GET['action'] != 'pjActionStyle' ? NULL : $active; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionStyle&amp;as_pf=<?php echo $as_pf; ?>"><?php __('frontend_style') ?></a></li>
	</ul>
</div>

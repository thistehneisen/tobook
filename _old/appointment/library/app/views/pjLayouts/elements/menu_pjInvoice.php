<?php 
global $as_pf;
?>
<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuBookings'); ?></a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>">Tee varaus</a></li>
		<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjInvoice&amp;action=pjActionInvoices&amp;as_pf=<?php echo $as_pf; ?>">Laskut</a></li>
		<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCustomer&amp;as_pf=<?php echo $as_pf; ?>">Asiakkaat</a></li>
	</ul>
</div>
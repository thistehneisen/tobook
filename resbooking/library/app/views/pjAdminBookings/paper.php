<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($RB_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
		case 3:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
	}
} else {
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=1"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=2"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=paper"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=customer"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=statistics">Statistics</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=6">Group booking</a></li>
		</ul>
	</div>
	
	<fieldset class="fieldset white">
		<legend><?php echo $RB_LANG['booking_filter']; ?></legend>
		<input type="text" id="paper_date" class="text w80 datepick pointer" readonly="readonly" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date($tpl['option_arr']['date_format']); ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
	</fieldset>
	
	<div id="boxPaper"><?php include VIEWS_PATH . 'pjAdminBookings/getPaper.php'; ?></div>
	<?php
}
?>
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
	
	//include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=1&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=2&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=paper&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=customer&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=statistics&amp;rbpf=<?php echo PREFIX; ?>">Statistics</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=6&amp;rbpf=<?php echo PREFIX; ?>">Group booking</a></li>
			<!-- <li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=formstyle&amp;rbpf=<?php echo PREFIX; ?>">Form Style</a></li>-->
		</ul>
	</div>

	<fieldset class="fieldset white">
		<legend><?php echo $RB_LANG['booking_filter']; ?></legend>
		<input type="text" id="schedule_date" class="text w80 datepick pointer" readonly="readonly" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date($tpl['option_arr']['date_format']); ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
		<div id="box-date-message">
			<?php if (isset($tpl['date_arr'][0]) && count($tpl['date_arr'][0]) > 0) {?>
				<a style="float: right;" href="#editCustomTime" data-toggle="modal" data-id=<?php echo $tpl['date_arr'][0]['id']; ?>"><?php echo $RB_LANG['_edit']; ?></a>
				<p><?php echo isset($tpl['date_arr'][0]['message']) ? $tpl['date_arr'][0]['message'] : ''; ?></p>
			<?php } else { ?>
				<a style="float: right;" href="#addCustomTime" data-toggle="modal">Add</a>
			<?php } ?>
		</div>
	</fieldset>
	
	<div id="boxSchedule"><?php include VIEWS_PATH . 'pjAdminBookings/getSchedule.php'; ?></div>
	
	<?php
}
?>
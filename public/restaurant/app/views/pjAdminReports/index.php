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
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminReports&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['menu_reports']; ?></a></li>
		</ul>
	</div>
	
	<?php pjUtil::printNotice($RB_LANG['info']['reports_index']); ?>
	
	<fieldset class="fieldset white">
		<legend><?php echo $RB_LANG['report_filter']; ?></legend>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rbpf=<?php echo PREFIX; ?>" method="get" class="form">
			<input type="hidden" name="controller" value="pjAdminReports" />
			<input type="hidden" name="action" value="index" />
			<input type="hidden" name="report" value="1" />
			<?php echo $RB_LANG['report_from']; ?>: <input type="text" name="date_from" id="date_from" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : NULL; ?>" class="text w80 datepick pointer" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" readonly="readonly" />
			<?php echo $RB_LANG['report_to']; ?>: <input type="text" name="date_to" id="date_to" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : NULL; ?>" class="text w80 datepick pointer" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" readonly="readonly" />
			<input type="submit" value="" class="button button_report align_top l5" />
		</form>
	</fieldset>
	<?php
	
	if (isset($tpl['arr']))
	{
		?>
		<table class="table" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th class="sub w200">Indicator</th>
					<th class="sub">Value</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Bookings:</td>
					<td><?php echo (int) $tpl['arr']['total_bookings']; ?></td>
				</tr>
				<tr>
					<td>Amount paid:</td>
					<td><?php echo (int) $tpl['arr']['paid_total']; ?></td>
				</tr>
			</tbody>
		</table>
		<?php
	}
}
?>
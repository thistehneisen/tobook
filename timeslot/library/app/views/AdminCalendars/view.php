<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminCalendars
 */
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			Util::printNotice($TS_LANG['status'][1]);
			break;
		case 2:
			Util::printNotice($TS_LANG['status'][2]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 10:
				Util::printNotice($TS_LANG['calendar_err'][10]);
				break;
		}
	}
	list($month, $year) = explode("-", date("n-Y"));
	?>
	<style type="text/css">
	#TSBC_<?php echo $_GET['cid']; ?> table.calendarTable{
		height: 500px;
		width: 740px;
	}
	#TSBC_<?php echo $_GET['cid']; ?> td.calendarToday,
	#TSBC_<?php echo $_GET['cid']; ?> td.calendarPast,
	#TSBC_<?php echo $_GET['cid']; ?> td.calendarEmpty,
	#TSBC_<?php echo $_GET['cid']; ?> td.calendar{
		height: 86px; /*72*/
		width: 112px; /*86*/
	}
	#TSBC_<?php echo $_GET['cid']; ?> td.calendarFull{
		cursor: pointer;
	}
	</style>
	
	<div id="TSBC_<?php echo $_GET['cid']; ?>" class="TSBCalendar">
	<?php echo $tpl['calendar']->getMonthView($month, $year); ?>
	</div>
	<div id="TSBC_Slots"></div>
	<?php
	if (!$controller->isAjax())
	{
		?>
		<div id="dialogTimeslotDelete" title="<?php echo htmlspecialchars($TS_LANG['cal_del_ts_title']); ?>" style="display:none">
			<p><?php echo $TS_LANG['cal_del_ts_body']; ?></p>
		</div>
		<div id="dialogBookingDelete" title="<?php echo htmlspecialchars($TS_LANG['cal_del_title']); ?>" style="display:none">
			<p><?php echo $TS_LANG['cal_del_body']; ?></p>
		</div>
		<?php
	}
	?>
	<div id="record_id" style="display:none"></div>
	<?php
}
?>
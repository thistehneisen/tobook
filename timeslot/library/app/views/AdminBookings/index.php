<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminBookings
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
			case 1:
				Util::printNotice($TS_LANG['booking_err'][1]);
				break;
			case 2:
				Util::printNotice($TS_LANG['booking_err'][2]);
				break;
			case 3:
				Util::printNotice($TS_LANG['booking_err'][3]);
				break;
			case 4:
				Util::printNotice($TS_LANG['booking_err'][4]);
				break;
			case 5:
				Util::printNotice($TS_LANG['booking_err'][5]);
				break;
			case 7:
				Util::printNotice($TS_LANG['status'][7]);
				break;
			case 8:
				Util::printNotice($TS_LANG['booking_err'][8]);
				break;
			case 9:
				Util::printNotice($TS_LANG['booking_err'][9]);
				break;
			case 11:
				Util::printNotice($TS_LANG['booking_err'][11]);
				break;
		}
	}
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $TS_LANG['booking_list']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $TS_LANG['booking_create']; ?></a></li>
			<li><a href="#tabs-3"><?php echo $TS_LANG['booking_export']; ?></a></li>
			<li><a href="#tabs-4"><?php echo $TS_LANG['booking_customer']; ?></a></li>
		</ul>
		<div id="tabs-1">
		
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="tsbc-form b10">
				<input type="hidden" name="controller" value="AdminBookings" />
				<input type="hidden" name="action" value="index" />
				<input type="text" name="q" value="<?php echo isset($_GET['q']) && !empty($_GET['q']) ? htmlspecialchars($_GET['q']) : NULL; ?>" class="text w300" />
				<input type="submit" value="" class="button button_search" />
			</form>
		
		<?php
		if (isset($tpl['arr']))
		{
			if (is_array($tpl['arr']))
			{
				$count = count($tpl['arr']);
				if ($count > 0)
				{
					?>
					<table class="tsbc-table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th class="sub"><?php echo $TS_LANG['booking_calendar']; ?></th>
								<th class="sub"><?php echo $TS_LANG['booking_time']; ?></th>
								<th class="sub"><?php echo $TS_LANG['booking_customer_email']; ?></th>
								<th class="sub"><?php echo $TS_LANG['booking_booking_status']; ?></th>
								<th class="sub"><?php echo $TS_LANG['booking_total']; ?></th>
								<th class="sub" style="width: 8%"></th>
								<th class="sub" style="width: 8%"></th>
							</tr>
						</thead>
						<tbody>
					<?php
					for ($i = 0; $i < $count; $i++)
					{
						?>
						<tr class="<?php echo $i % 2 === 0 ? 'even' : 'odd'; ?>">
							<td><?php echo stripslashes($tpl['arr'][$i]['calendar_title']); ?></td>
							<td>
							<?php
							$t = array(); 
							foreach ($tpl['arr'][$i]['booking_slots'] as $v)
							{
								$t[] = date($tpl['option_arr']['date_format'], strtotime($v['booking_date'])) . ', ' . date($tpl['option_arr']['time_format'], strtotime($v['start_time'])) . ' - ' . date($tpl['option_arr']['time_format'], strtotime($v['end_time']));
							}
							if (count($t) > 0)
							{
								echo join("<br />", $t);
							}
							?>
							</td>
							<td><?php echo stripslashes($tpl['arr'][$i]['customer_email']); ?></td>
							<td class="align_center"><acronym title="<?php echo $tpl['arr'][$i]['booking_status']; ?>" class="booking-status booking-status-<?php echo $tpl['arr'][$i]['booking_status']; ?>"><?php echo @$TS_LANG['booking_booking_statuses'][$tpl['arr'][$i]['booking_status']]; ?></acronym></td>
							<td class="align_right"><?php echo Util::formatCurrencySign($tpl['arr'][$i]['booking_total'], $tpl['option_arr']['currency']); ?></td>
							<td><a class="icon icon-edit" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $TS_LANG['_edit']; ?></a></td>
							<td><a class="icon icon-delete" rev="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $TS_LANG['_delete']; ?></a></td>
						</tr>
						<?php
					}
					?>
						</tbody>
					</table>
					<?php
					if (!$controller->isAjax())
					{
						?>
						<div id="dialogDelete" title="<?php echo htmlspecialchars($TS_LANG['booking_del_title']); ?>" style="display:none">
							<p><?php echo $TS_LANG['booking_del_body']; ?></p>
						</div>
						<?php
					}
					?>
					<div id="record_id" style="display:none"></div>
					<?php
					if (isset($tpl['paginator']))
					{
						?>
						<ul class="tsbc-paginator">
						<?php
						for ($i = 1; $i <= $tpl['paginator']['pages']; $i++)
						{
							if ((isset($_GET['page']) && (int) $_GET['page'] == $i) || (!isset($_GET['page']) && $i == 1))
							{
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;q=<?php echo isset($_GET['q']) && !empty($_GET['q']) ? urlencode($_GET['q']) : NULL; ?>&amp;page=<?php echo $i; ?>" class="focus"><?php echo $i; ?></a></li><?php
							} else {
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;q=<?php echo isset($_GET['q']) && !empty($_GET['q']) ? urlencode($_GET['q']) : NULL; ?>&amp;page=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php
							}
						}
						?>
						</ul>
						<?php
					}
					
				} else {
					Util::printNotice($TS_LANG['booking_empty']);
				}
			}
		}
		?>
		</div>
		<div id="tabs-2">
		<?php include VIEWS_PATH . 'AdminBookings/elements/create.php'; ?>
		</div>
		<div id="tabs-3">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=export" method="post" class="tsbc-form" id="frmExportBooking">
				<p>
					<label class="title"><?php echo $TS_LANG['booking_export_format']; ?></label>
					<select name="format" id="format" class="select w100">
						<option value="csv">CSV</option>
						<option value="xml">XML</option>
						<option value="icalendar">iCalendar</option>
					</select>
				</p>
				<p>
					<label class="title"><?php echo $TS_LANG['booking_export_from']; ?></label>
					<input type="text" name="date_from" id="date_from" class="text pointer datepick" />
				</p>
				<p>
					<label class="title"><?php echo $TS_LANG['booking_export_to']; ?></label>
					<input type="text" name="date_to" id="date_to" class="text pointer datepick" />
				</p>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="" class="button button_export" />
				</p>				
			</form>
			<?php 
			Util::printNotice($TS_LANG['booking_export_note_csv'], false);
			Util::printNotice($TS_LANG['booking_export_note_xml'], false);
			Util::printNotice($TS_LANG['booking_export_note_ical'], false);
			?>
		</div>
		<div id="tabs-4">
			<?php
			if (isset($tpl['carr']))
			{
				if (is_array($tpl['carr']))
				{
					$count = count($tpl['carr']);
					if ($count > 0)
					{
						?>
						<a style="line-height: 25px; text-decoration: none;" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=download_csv" target="_blank"><?php echo 'Download'; //$RB_LANG['booking_download']; ?></a>
						<table class="tsbc-table">
							<thead>
								<tr>
									<th class="sub"><?php echo $TS_LANG['booking_customer_name']; ?></th>
									<th class="sub"><?php echo $TS_LANG['booking_customer_phone']; ?></th>
									<th class="sub"><?php echo $TS_LANG['booking_customer_email']; ?></th>
									<th class="sub"><?php echo $TS_LANG['booking_count']; ?></th>
								</tr>
							</thead>
							<tbody>
						<?php
						for ($i = 0; $i < $count; $i++)
						{
							?>
							<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?> ">
								<td class="meta"><?php echo stripslashes($tpl['carr'][$i]['customer_name']); ?></td>
								<td class="meta"><?php echo $tpl['carr'][$i]['customer_phone']; ?></td>
								<td class="meta"><?php echo stripslashes($tpl['carr'][$i]['customer_email']); ?></td>
								<td class="meta"><?php echo stripslashes($tpl['carr'][$i]['count']); ?></td>
							</tr>
							<?php
						}
						?>
							</tbody>
						</table>
						<?php
						if (isset($tpl['paginator']))
						{
							?>
							<ul class="tsbc-paginator">
							<?php
							for ($i = 1; $i <= $tpl['cpaginator']['pages']; $i++)
							{
								if ((isset($_GET['cpage']) && (int) $_GET['cpage'] == $i) || (!isset($_GET['cpage']) && $i == 1))
								{
									?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;cpage=<?php echo $i; ?>" class="focus"><?php echo $i; ?></a></li><?php
								} else {
									?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;cpage=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php
								}
							}
							?>
							</ul>
							<?php
						}
						
					} else {
						pjUtil::printNotice($RB_LANG['booking_empty']);
					}
				}
			}
			?>
		</div>
	</div>
	<?php
}
?>
<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($CR_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($CR_LANG['status'][2]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$RB_LANG['errors'][$_GET['err']]);
	}
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li><a href="#tabs-3"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li><a href="#tabs-4"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li><a href="#tabs-5"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li><a href="#tabs-6">Statistics</a></li>
			<li><a href="#tabs-7">Group booking</a></li>
			<!-- <li><a href="#tabs-8">Form Style</a></li>-->
		</ul>
		<div id="tabs-1"></div>
		<div id="tabs-2">
		<?php
		if (isset($tpl['arr']))
		{
			if (is_array($tpl['arr']))
			{
				$count = count($tpl['arr']);
				if ($count > 0)
				{
					?>
					<table class="table">
						<thead>
							<tr>
								<th class="sub"><?php echo $RB_LANG['booking_dt']; ?></th>
								<th class="sub"><?php echo $RB_LANG['booking_name']; ?></th>
								<th class="sub"><?php echo $RB_LANG['booking_email']; ?></th>
								<th class="sub"><?php echo $RB_LANG['booking_status']; ?></th>
								<th class="sub" style="width: 7%"></th>
							</tr>
						</thead>
						<tbody>
					<?php
					for ($i = 0; $i < $count; $i++)
					{
						?>
						<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?> pointer {id: '<?php echo $tpl['arr'][$i]['id']; ?>'}">
							<td class="meta"><?php echo date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr'][$i]['dt'])); ?></td>
							<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_fname'] . " " . $tpl['arr'][$i]['c_lname']); ?></td>
							<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_email']); ?></td>
							<td class="meta"><span class="booking-status booking-status-<?php echo $tpl['arr'][$i]['status']; ?>"><?php echo stripslashes($tpl['arr'][$i]['status']); ?></span></td>
							<td><a class="icon icon-delete" data-id="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
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
						<ul class="paginator">
						<?php
						for ($i = 1; $i <= $tpl['paginator']['pages']; $i++)
						{
							if ((isset($_GET['page']) && (int) $_GET['page'] == $i) || (!isset($_GET['page']) && $i == 1))
							{
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>&amp;rbpf=<?php echo PREFIX; ?>" class="focus"><?php echo $i; ?></a></li><?php
							} else {
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $i; ?></a></li><?php
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
		<div id="tabs-3">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?rbpf=<?php echo PREFIX; ?>" method="get" class="form">
				<input type="hidden" name="controller" value="pjAdminBookings" />
				<input type="hidden" name="action" value="index" />
				<input type="hidden" name="tab" value="1" />
				<p><label class="title"><?php echo $RB_LANG['booking_date_from']; ?></label><input type="text" name="date_from" id="date_from" class="text w80 datepick pointer" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" readonly="readonly" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : NULL; ?>" /></p>
				<p><label class="title"><?php echo $RB_LANG['booking_date_to']; ?></label><input type="text" name="date_to" id="date_to" class="text w80 datepick pointer" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" readonly="readonly" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : NULL; ?>" /></p>
				<p><label class="title"><?php echo $RB_LANG['booking_table']; ?></label>
					<select name="table_id" id="table_id" class="select w250">
						<option value=""><?php echo $RB_LANG['booking_choose']; ?></option>
						<?php
						foreach ($tpl['table_arr'] as $v)
						{
							?><option value="<?php echo $v['id']; ?>"<?php echo isset($_GET['table_id']) && $_GET['table_id'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo stripslashes($v['name']); ?></option><?php
						}
						?>
					</select>
				</p>
				<p><label class="title"><?php echo $RB_LANG['booking_name']; ?></label><input type="text" name="c_name" id="c_name" class="text w250" value="<?php echo isset($_GET['c_name']) ? htmlspecialchars($_GET['c_name']) : NULL; ?>" /></p>
				<p><label class="title"><?php echo $RB_LANG['booking_email']; ?></label><input type="text" name="c_email" id="c_email" class="text w250" value="<?php echo isset($_GET['c_email']) ? htmlspecialchars($_GET['c_email']) : NULL; ?>" /></p>
				<p><label class="title"><?php echo $RB_LANG['booking_phone']; ?></label><input type="text" name="c_phone" id="c_phone" class="text w250" value="<?php echo isset($_GET['c_phone']) ? htmlspecialchars($_GET['c_phone']) : NULL; ?>" /></p>
				
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="" class="button button_search" />
				</p>
			</form>
		</div>
		<div id="tabs-4"></div>
		<div id="tabs-5"></div>
		<div id="tabs-6"></div>
		<div id="tabs-7">
			<?php include VIEWS_PATH . 'pjAdminBookings/elements/tabs-7.php'; ?>
			
		</div>
		<?php if (!$controller->isAjax()) { ?>
		<div id="dialogDelete" title="<?php echo htmlspecialchars($RB_LANG['booking_del_title']); ?>" style="display:none">
			<p><?php echo $RB_LANG['booking_del_body']; ?></p>
		</div>
		<?php } ?>
		<div id="tabs-8"></div>
	</div>
	<?php
}
?>
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
		case 9:
			pjUtil::printNotice($RB_LANG['status'][9]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$RB_LANG['errors'][$_GET['err']]);
	}
	include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $RB_LANG['voucher_list']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $RB_LANG['voucher_create']; ?></a></li>
		</ul>
		<div id="tabs-1">
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
								<th class="sub"><?php echo $RB_LANG['voucher_code']; ?></th>
								<th class="sub align_right w100"><?php echo $RB_LANG['voucher_discount']; ?></th>
								<th class="sub w100"><?php echo $RB_LANG['voucher_type']; ?></th>
								<th class="sub w100"><?php echo $RB_LANG['voucher_valid']; ?></th>
								<th class="sub" style="width: 7%"></th>
								<th class="sub" style="width: 7%"></th>
							</tr>
						</thead>
						<tbody>
					<?php
					for ($i = 0; $i < $count; $i++)
					{
						?>
						<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?> {id: '<?php echo $tpl['arr'][$i]['id']; ?>'}">
							<td><?php echo stripslashes($tpl['arr'][$i]['code']); ?></td>
							<td class="align_right"><?php echo (int) $tpl['arr'][$i]['discount']; ?></td>
							<td><?php echo @$RB_LANG['voucher_types'][$tpl['arr'][$i]['type']]; ?></td>
							<td><?php echo @$RB_LANG['voucher_valids'][$tpl['arr'][$i]['valid']]; ?></td>
							<td><a class="icon icon-edit" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminVouchers&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $RB_LANG['_edit']; ?></a></td>
							<td><a class="icon icon-delete" rel="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminVouchers&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
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
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>" class="focus"><?php echo $i; ?></a></li><?php
							} else {
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php
							}
						}
						?>
						</ul>
						<?php
					}
					
					if (!$controller->isAjax())
					{
						?>
						<div id="dialogDelete" title="<?php echo htmlspecialchars($RB_LANG['voucher_del_title']); ?>" style="display:none">
							<p><?php echo $RB_LANG['voucher_del_body']; ?></p>
						</div>
						<?php
					}
				} else {
					pjUtil::printNotice($RB_LANG['voucher_empty']);
				}
			}
		}
		?>
		</div> <!-- tabs-1 -->
		<div id="tabs-2">
			<?php pjUtil::printNotice($RB_LANG['info']['add_voucher']); ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminVouchers&amp;action=create" method="post" id="frmCreateVoucher" class="form">
				<input type="hidden" name="voucher_create" value="1" />
				<p><label class="title"><?php echo $RB_LANG['voucher_code']; ?></label><input type="text" name="code" id="code" class="text w100 required" /></p>
				<p><label class="title"><?php echo $RB_LANG['voucher_discount']; ?></label><input type="text" name="discount" id="discount" class="text w80 align_right number required" /></p>
				<p><label class="title"><?php echo $RB_LANG['voucher_type']; ?></label>
					<select name="type" id="type" class="select required">
						<option value=""><?php echo $RB_LANG['voucher_choose']; ?></option>
						<?php
						foreach ($RB_LANG['voucher_types'] as $k => $v)
						{
							?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</p>
				<p><label class="title"><?php echo $RB_LANG['voucher_valid']; ?></label>
					<select name="valid" id="valid" class="select required">
						<option value=""><?php echo $RB_LANG['voucher_choose']; ?></option>
						<?php
						foreach ($RB_LANG['voucher_valids'] as $k => $v)
						{
							?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</p>
				<div id="vFixed" class="vBox" style="display: none">
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_date']; ?></label>
						<input type="text" name="f_date" id="f_date" class="text w80 pointer datepick" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
					</p>
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_time_from']; ?></label>
						<?php echo TimeWidget::hour(null, 'f_hour_from', 'f_hour_from', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
						<?php echo TimeWidget::minute(null, 'f_minute_from', 'f_minute_from', 'select'); ?>
					</p>
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_time_to']; ?></label>
						<?php echo TimeWidget::hour(null, 'f_hour_to', 'f_hour_to', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
						<?php echo TimeWidget::minute(null, 'f_minute_to', 'f_minute_to', 'select'); ?>
					</p>
				</div>
				<div id="vPeriod" class="vBox" style="display: none">
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_date_from']; ?></label>
						<input type="text" name="p_date_from" id="p_date_from" class="text w80 pointer datepick" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
						<?php echo TimeWidget::hour(null, 'p_hour_from', 'p_hour_from', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
						<?php echo TimeWidget::minute(null, 'p_minute_from', 'p_minute_from', 'select'); ?>
					</p>
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_date_to']; ?></label>
						<input type="text" name="p_date_to" id="p_date_to" class="text w80 pointer datepick" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
						<?php echo TimeWidget::hour(null, 'p_hour_to', 'p_hour_to', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
						<?php echo TimeWidget::minute(null, 'p_minute_to', 'p_minute_to', 'select'); ?>
					</p>
				</div>
				<div id="vRecurring" class="vBox" style="display: none">
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_every']; ?></label>
						<select name="r_every" id="r_every" class="select">
							<option value=""><?php echo $RB_LANG['voucher_choose']; ?></option>
							<?php
							foreach ($RB_LANG['days'] as $k => $v)
							{
								?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
							}
							?>
						</select>
					</p>
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_time_from']; ?></label>
						<?php echo TimeWidget::hour(null, 'r_hour_from', 'r_hour_from', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
						<?php echo TimeWidget::minute(null, 'r_minute_from', 'r_minute_from', 'select'); ?>
					</p>
					<p>
						<label class="title"><?php echo $RB_LANG['voucher_time_to']; ?></label>
						<?php echo TimeWidget::hour(null, 'r_hour_to', 'r_hour_to', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
						<?php echo TimeWidget::minute(null, 'r_minute_to', 'r_minute_to', 'select'); ?>
					</p>
				</div>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="" class="button button_save" />
				</p>
			</form>
		</div> <!-- tabs-2 -->
	</div>
	<?php
}
?>
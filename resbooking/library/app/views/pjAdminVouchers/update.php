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
	include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	?>
	<?php pjUtil::printNotice($RB_LANG['info']['add_voucher']); ?>
    
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSpaces&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>"><?php echo $RB_LANG['voucher_update']; ?></a></li>
		</ul>
	</div>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminVouchers&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>" method="post" id="frmUpdateVoucher" class="form">
		<input type="hidden" name="voucher_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<p><label class="title"><?php echo $RB_LANG['voucher_code']; ?></label><input type="text" name="code" id="code" class="text w100 required" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['code'])); ?>" /></p>
		<p><label class="title"><?php echo $RB_LANG['voucher_discount']; ?></label><input type="text" name="discount" id="discount" class="text w80 align_right number required" value="<?php echo (float) $tpl['arr']['discount']; ?>" /></p>
		<p><label class="title"><?php echo $RB_LANG['voucher_type']; ?></label>
			<select name="type" id="type" class="select required">
				<option value=""><?php echo $RB_LANG['voucher_choose']; ?></option>
				<?php
				foreach ($RB_LANG['voucher_types'] as $k => $v)
				{
					?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['type'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
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
					?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['valid'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
				}
				?>
			</select>
		</p>
		<?php
		$date_from = $date_to = $hour_from = $hour_to = $minute_from = $minute_to = NULL;
		if (!empty($tpl['arr']['date_from']))
		{
			$date_from = date($tpl['option_arr']['date_format'], strtotime($tpl['arr']['date_from']));
		}
		if (!empty($tpl['arr']['date_to']))
		{
			$date_to = date($tpl['option_arr']['date_format'], strtotime($tpl['arr']['date_to']));
		}
		if (!empty($tpl['arr']['time_from']) && strpos($tpl['arr']['time_from'], ":") !== false)
		{
			list($hour_from, $minute_from,) = explode(":", $tpl['arr']['time_from']);
		}
		if (!empty($tpl['arr']['time_to']) && strpos($tpl['arr']['time_to'], ":") !== false)
		{
			list($hour_to, $minute_to,) = explode(":", $tpl['arr']['time_to']);
		}
		?>
		<div id="vFixed" class="vBox" style="display: <?php echo $tpl['arr']['valid'] == 'fixed' ? 'block' : 'none'; ?>">
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_date']; ?></label>
				<input type="text" name="f_date" id="f_date" class="text w80 pointer datepick" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo $date_from; ?>" />
			</p>
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_time_from']; ?></label>
				<?php echo TimeWidget::hour($hour_from, 'f_hour_from', 'f_hour_from', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
				<?php echo TimeWidget::minute($minute_from, 'f_minute_from', 'f_minute_from', 'select'); ?>
			</p>
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_time_to']; ?></label>
				<?php echo TimeWidget::hour($hour_to, 'f_hour_to', 'f_hour_to', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
				<?php echo TimeWidget::minute($minute_to, 'f_minute_to', 'f_minute_to', 'select'); ?>
			</p>
		</div>
		<div id="vPeriod" class="vBox" style="display: <?php echo $tpl['arr']['valid'] == 'period' ? 'block' : 'none'; ?>">
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_date_from']; ?></label>
				<input type="text" name="p_date_from" id="p_date_from" class="text w80 pointer datepick" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo $date_from; ?>" />
				<?php echo TimeWidget::hour($hour_from, 'p_hour_from', 'p_hour_from', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
				<?php echo TimeWidget::minute($minute_from, 'p_minute_from', 'p_minute_from', 'select'); ?>
			</p>
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_date_to']; ?></label>
				<input type="text" name="p_date_to" id="p_date_to" class="text w80 pointer datepick" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo $date_to; ?>" />
				<?php echo TimeWidget::hour($hour_to, 'p_hour_to', 'p_hour_to', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
				<?php echo TimeWidget::minute($minute_to, 'p_minute_to', 'p_minute_to', 'select'); ?>
			</p>
		</div>
		<div id="vRecurring" class="vBox" style="display: <?php echo $tpl['arr']['valid'] == 'recurring' ? 'block' : 'none'; ?>">
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_every']; ?></label>
				<select name="r_every" id="r_every" class="select">
					<option value=""><?php echo $RB_LANG['voucher_choose']; ?></option>
					<?php
					foreach ($RB_LANG['days'] as $k => $v)
					{
						?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['every'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
					}
					?>
				</select>
			</p>
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_time_from']; ?></label>
				<?php echo TimeWidget::hour($hour_from, 'r_hour_from', 'r_hour_from', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
				<?php echo TimeWidget::minute($minute_from, 'r_minute_from', 'r_minute_from', 'select'); ?>
			</p>
			<p>
				<label class="title"><?php echo $RB_LANG['voucher_time_to']; ?></label>
				<?php echo TimeWidget::hour($hour_to, 'r_hour_to', 'r_hour_to', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
				<?php echo TimeWidget::minute($minute_to, 'r_minute_to', 'r_minute_to', 'select'); ?>
			</p>
		</div>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="" class="button button_save" />
		</p>
	</form>
	<?php
}
?>
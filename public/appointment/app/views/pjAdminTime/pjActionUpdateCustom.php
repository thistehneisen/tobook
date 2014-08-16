<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	global $as_pf;
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	if ($controller->isEmployee())
	{
		include dirname(__FILE__) . '/elements/menu_employees.php';
	} elseif (isset($_GET['foreign_id']) && isset($_GET['type'])) {
		$employee_id = $_GET['foreign_id'];
		include PJ_VIEWS_PATH . 'pjAdminEmployees/elements/menu.php';
		include dirname(__FILE__) . '/elements/menu_admin.php';
	} else {
		include PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
		include dirname(__FILE__) . '/elements/menu_options.php';
	}
	
	pjUtil::printNotice(@$titles['AT04'], @$bodies['AT04']);
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$pjTime = pjTime::factory();
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionUpdateCustom&amp;as_pf=<?php echo $as_pf; ?>" method="post" class="form" id="frmTimeCustom">
		<input type="hidden" name="custom_time" value="1" />
		<input type="hidden" name="id" value="<?php echo @$tpl['arr']['id']; ?>" />
		<?php
		if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
		{
			?><input type="hidden" name="foreign_id" value="<?php echo (int) $tpl['arr']['foreign_id']; ?>" /><?php
		}
		if (isset($_GET['type']) && !empty($_GET['type']))
		{
			?><input type="hidden" name="type" value="<?php echo pjSanitize::html($tpl['arr']['type']); ?>" /><?php
		}
		?>
		<fieldset class="fieldset white">
			<legend><?php __('time_custom'); ?></legend>
			<div class="float_left w350">
				<p>
					<label class="title"><?php __('time_date'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-after">
						<input type="text" name="date" id="date" class="pj-form-field w80 datepick pointer required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo pjUtil::formatDate($tpl['arr']['date'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
				</p>
				<p>
					<label class="title"><?php __('time_from'); ?></label>
					<?php
					$start_time = explode(":", $tpl['arr']['start_time']);
					echo $pjTime
						->reset()
						->attr('name', 'start_hour')
						->attr('id', 'start_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$start_time[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_minute')
						->attr('id', 'start_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$start_time[1])
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_to'); ?></label>
					<?php
					$end_time = explode(":", $tpl['arr']['end_time']);
					echo $pjTime
						->reset()
						->attr('name', 'end_hour')
						->attr('id', 'end_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$end_time[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_minute')
						->attr('id', 'end_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$end_time[1])
						->minute();
					?>
				</p>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button"  />
				</p>
			</div>
			<div class="float_right w350">
				<p>
					<label class="title"><?php __('time_is'); ?></label>
					<span class="block float_left t5 b10"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T"<?php echo $tpl['arr']['is_dayoff'] == 'T' ? ' checked="checked"' : NULL; ?> /></span>
				</p>
				<p>
					<label class="title"><?php __('time_lunch_from'); ?></label>
					<?php
					$start_lunch = explode(":", $tpl['arr']['start_lunch']);
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_hour')
						->attr('id', 'start_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$start_lunch[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_minute')
						->attr('id', 'start_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$start_lunch[1])
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_lunch_to'); ?></label>
					<?php
					$end_lunch = explode(":", $tpl['arr']['end_lunch']);
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_hour')
						->attr('id', 'end_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$end_lunch[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_minute')
						->attr('id', 'end_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$end_lunch[1])
						->minute();
					?>
				</p>
			</div>
			<br class="clear_both" />
		</fieldset>
	</form>
	<?php
}
?>
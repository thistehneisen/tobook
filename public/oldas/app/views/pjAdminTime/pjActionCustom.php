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
		
		pjUtil::printNotice(@$titles['AT07'], @$bodies['AT07']);
	} elseif (isset($_GET['foreign_id']) && isset($_GET['type'])) {
		$employee_id = $_GET['foreign_id'];
		include PJ_VIEWS_PATH . 'pjAdminEmployees/elements/menu.php';
		include dirname(__FILE__) . '/elements/menu_admin.php';
		
		pjUtil::printNotice(@$titles['AT07'], @$bodies['AT07']);
	} else {
		include PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
		include dirname(__FILE__) . '/elements/menu_options.php';
		
		pjUtil::printNotice(@$titles['AT05'], @$bodies['AT05']);
	}
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	$pjTime = pjTime::factory();
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionCustom&amp;as_pf=<?php echo $as_pf; ?>" method="post" class="form" id="frmTimeCustom">
		<input type="hidden" name="custom_time" value="1" />
		<?php
		if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
		{
			?><input type="hidden" name="foreign_id" value="<?php echo (int) $_GET['foreign_id']; ?>" /><?php
		}
		if (isset($_GET['type']) && !empty($_GET['type']))
		{
			?><input type="hidden" name="type" value="<?php echo pjSanitize::html($_GET['type']); ?>" /><?php
		}
		?>
		<fieldset class="fieldset white">
			<legend><?php __('time_custom'); ?></legend>
			<div class="float_left w350">
				<p>
					<label class="title"><?php __('time_date'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-after">
						<input type="text" name="date" id="date" class="pj-form-field w80 datepick pointer required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
				</p>
				<p>
					<label class="title"><?php __('time_from'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_hour')
						->attr('id', 'start_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_minute')
						->attr('id', 'start_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_to'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_hour')
						->attr('id', 'end_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_minute')
						->attr('id', 'end_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
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
					<span class="block float_left t5 b10"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T" /></span>
				</p>
				<p>
					<label class="title"><?php __('time_lunch_from'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_hour')
						->attr('id', 'start_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_minute')
						->attr('id', 'start_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_lunch_to'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_hour')
						->attr('id', 'end_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_minute')
						->attr('id', 'end_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->minute();
					?>
				</p>
			</div>
			<br class="clear_both" />
		</fieldset>
	</form>
	
	<form role="form" class="form-signin form" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionCustom&amp;as_pf=<?php echo $as_pf; ?>" method="post" enctype="multipart/form-data" id="frmTimeCustomImport">
		<input type="hidden" name="custom_time_f" value="1" enctype="multipart/form-data" role="form"/>
		<?php
		if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
		{
			?><input type="hidden" name="foreign_id" value="<?php echo (int) $_GET['foreign_id']; ?>" /><?php
		}
		if (isset($_GET['type']) && !empty($_GET['type']))
		{
			?><input type="hidden" name="type" value="<?php echo pjSanitize::html($_GET['type']); ?>" /><?php
		}
		?>
		
		<fieldset class="fieldset white">
			<legend>Import</legend>
			
			<p>
				<label class="title"><?php __('time_lunch_from'); ?></label>
				<?php
				echo $pjTime
					->reset()
					->attr('name', 'start_lunch_hour')
					->attr('id', 'start_lunch_hour')
					->attr('class', 'pj-form-field w50')
					->hour();
				?>
				<?php
				echo $pjTime
					->reset()
					->attr('name', 'start_lunch_minute')
					->attr('id', 'start_lunch_minute')
					->attr('class', 'pj-form-field w50')
					->prop('step', 5)
					->minute();
				?>
			</p>
			<p>
				<label class="title"><?php __('time_lunch_to'); ?></label>
				<?php
				echo $pjTime
					->reset()
					->attr('name', 'end_lunch_hour')
					->attr('id', 'end_lunch_hour')
					->attr('class', 'pj-form-field w50')
					->hour();
				?>
				<?php
				echo $pjTime
					->reset()
					->attr('name', 'end_lunch_minute')
					->attr('id', 'end_lunch_minute')
					->attr('class', 'pj-form-field w50')
					->prop('step', 5)
					->minute();
				?>
			</p>
				
			<p>
				<label class="title">Please upload file</label>
				<input type="file" name="fcsv" class="required"/>
			</p>
	      	<p>
		      	<label class="title"> </label>
				<input class="pj-button" type="submit" value="Save">
			</p>
		</fieldset>
	</form>
		
	<div class="b10">
		<?php
		$yesno = __('_yesno', true);
		?>
		<div class="float_right">
			<a href="#" class="pj-button btn-all"><?php __('lblAll'); ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="is_dayoff" data-value="T"><?php echo $yesno['T']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="is_dayoff" data-value="F"><?php echo $yesno['F']; ?></a>
		</div>
		<br class="clear_right" />
	</div>
	
	<div id="grid"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	pjGrid.jsDateFormat = "<?php echo pjUtil::jsDateFormat($tpl['option_arr']['o_date_format']); ?>";
	var myLabel = myLabel || {};
	myLabel.time_date = "<?php __('time_date', false, true); ?>";
	myLabel.time_start = "<?php __('time_from', false, true); ?>";
	myLabel.time_end = "<?php __('time_to', false, true); ?>";
	myLabel.time_lunch_start = "<?php __('time_lunch_from', false, true); ?>";
	myLabel.time_lunch_end = "<?php __('time_lunch_to', false, true); ?>";
	myLabel.time_dayoff = "<?php __('time_is', false, true); ?>";
	myLabel.time_yesno = <?php echo pjAppController::jsonEncode(__('_yesno', true)); ?>;
	myLabel.delete_selected = "<?php __('delete_selected', false, true); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation', false, true); ?>";
	</script>
	<?php
}
?>
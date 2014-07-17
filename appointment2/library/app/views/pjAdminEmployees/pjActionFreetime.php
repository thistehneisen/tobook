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
	
	$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
	
	include dirname(__FILE__) . '/elements/menu.php';
	if ( count($tpl['employee_arr']) > 0 ) {
		$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
		$step = $tpl['option_arr']['o_step'] * 60;
		
		$times = array();
		if ( $tpl['t_arr'] ) {
			for ($i = $tpl['t_arr']['start_ts']; $i <= $tpl['t_arr']['end_ts'] + $offset - $step; $i += $step) {
				$times[$i] = date($tpl['option_arr']['o_time_format'], $i);
			}
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionFreetime&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmFreetime" class="form pj-form">
			
			<input type="hidden" name="freetime" value="1"> 
			<?php if ( isset($_GET['id']) && !empty($_GET['id']) ) { ?>
				<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"> 
			<?php }?>
			<?php if ( isset($_GET['pjAdmin']) && !empty($_GET['pjAdmin']) ) { ?>
				<input type="hidden" name="pjAdmin" value="<?php echo $_GET['pjAdmin']; ?>"> 
			<?php }?>
			<fieldset class="fieldset white">
				<legend>Free time</legend>
				<?php 
				if ( isset($tpl['ef_arr']['start_ts']) && $tpl['ef_arr']['start_ts'] > 0 ) {
					$start_ts = $tpl['ef_arr']['start_ts'];
					
				} elseif ( isset($_GET['start_ts']) && $_GET['start_ts'] > 0 ) {
					$start_ts = $_GET['start_ts'];
					
				} else $start_ts = strtotime('now');
				?>
				<div class="b10 p">
					<label class="title"><?php __('booking_date'); ?></label>
					<span class="pj-form-field-custom pj-form-field-custom-after float_left r5">
						<input type="text" name="date" class="pj-form-field w80 datepick pointer required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo date($tpl['option_arr']['o_date_format'], $start_ts); ?>" />
						<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
					</span>
				</div>
				<?php 
				if ( isset($tpl['ef_arr']['employee_id']) ) {
					$employee_id = $tpl['ef_arr']['employee_id'];
					
				} elseif ( isset($_GET['employee_id']) ) {
					$employee_id = $_GET['employee_id'];
					
				} else $employee_id = null; 
				?>
				<p>
					<label class="title">Employees</label>
					<span class="inline_block">
						<select name="employee_id" id="employee_id" class="pj-form-field required w110">
							<?php foreach ( $tpl['employee_arr'] as $employee ) { ?>
							<option <?php echo $employee_id == $employee['id'] ? 'selected="selected"' : null; ?> value="<?php echo $employee['id']; ?>"><?php echo $employee['name']; ?></option>
							<?php } ?>
						</select>
					</span>
				</p>
				<div id="loadajaxtime">
					<?php 
					if ( isset($tpl['ef_arr']['start_ts']) && $tpl['ef_arr']['start_ts'] > 0 ) {
						$start_ts = $tpl['ef_arr']['start_ts'];
						
					} elseif ( isset($_GET['start_ts']) && $_GET['start_ts'] > 0 ) {
						$start_ts = $_GET['start_ts'];
						
					} else $start_ts = null;
					?>
					<p>
						<label class="title">Start time</label>
						<span class="inline_block">
							<select name="start_ts" id="start_ts" class="pj-form-field required w110">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php foreach ( $times as $k => $time ) { ?>
								<option <?php echo $start_ts == $k ? 'selected="selected"' : null; ?> value="<?php echo $k; ?>"><?php echo $time; ?></option>
								<?php } ?>
							</select>
						</span>
					</p>
					<p>
						<label class="title">End time</label>
						<span class="inline_block">
							<select name="end_ts" id="end_ts" class="pj-form-field required w110">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php foreach ( $times as $k => $time ) { ?>
								<option <?php echo isset($tpl['ef_arr']['end_ts']) && $tpl['ef_arr']['end_ts'] == $k ? 'selected="selected"' : null; ?> value="<?php echo $k; ?>"><?php echo $time; ?></option>
								<?php } ?>
							</select>
						</span>
					</p>
				</div>
				<p>
					<label class="title">Message</label>
					<span class="inline_block">
						<textarea name="message" id="message" class="pj-form-field w200" rows="" cols=""><?php echo isset($tpl['ef_arr']['message']) ? $tpl['ef_arr']['message'] : null; ?></textarea>
					</span>
				</p>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
				</p>
			</fieldset>
		</form>
		
		<div id="grid"></div>
		
		<script type="text/javascript">
		var pjGrid = pjGrid || {};
		var myLabel = myLabel || {};
		myLabel.name = "<?php __('employee_name', false, true); ?>";
		myLabel.date = "Date";
		myLabel.start_ts = "Start time";
		myLabel.end_ts = "End time";
		myLabel.message = "Message";
		myLabel.delete_selected = "<?php __('delete_selected', false, true); ?>";
		myLabel.delete_confirmation = "<?php __('delete_confirmation', false, true); ?>";
		</script>
	<?php
	}
}
?>
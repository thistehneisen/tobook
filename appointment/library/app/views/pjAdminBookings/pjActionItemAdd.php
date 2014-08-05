<?php
$week_start = isset($tpl['option_arr']['o_week_start']) && in_array((int) $tpl['option_arr']['o_week_start'], range(0,6)) ? (int) $tpl['option_arr']['o_week_start'] : 0;
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['o_date_format']);
if (count($tpl['service_arr']) > 0) {
	
	$service_length = $tpl['service_arr'][0]['total'] * 60;
	$service_before = $tpl['service_arr'][0]['before'] * 60;
?>
<form action="" method="post" class="pj-form form">
	<input type="hidden" name="item_add" value="1" />
	<input type="hidden" name="booking_id" value="<?php echo (int) @$_GET['id']; ?>" />
	<input type="hidden" name="tmp_hash" value="<?php echo @$_GET['tmp_hash']; ?>" />

    <div class="float_right w300">
	<div class="b10 p">
		<label class="title"><?php __('booking_date'); ?></label>
		<span class="pj-form-field-custom pj-form-field-custom-after float_left r5">
			<input type="text" name="date" class="pj-form-field w80 datepick pointer required" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" value="<?php echo isset($_GET['start_ts']) && $_GET['start_ts'] > 0 ? date($tpl['option_arr']['o_date_format'], $_GET['start_ts']) : date($tpl['option_arr']['o_date_format']); ?>" />
			<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
		</span>
	</div>
	
	<?php if (isset($tpl['employee_arr']) && count($tpl['employee_arr']) > 0) { ?>
		<div class="b10 p bEmployee" style="display: none">
			<label class="title"><?php __('booking_employee'); ?></label>
			<span class="data left float_left"><?php echo $tpl['employee_arr'][0]['name']; ?></span>
			<br class="clear_left" />
			<input type="hidden" name="employee_id" value="<?php echo $tpl['employee_arr'][0]['id']; ?>" class="ignore" />
		</div>
		
	<?php } else {?>
		<div class="b10 p bEmployee" style="display: none">
			<label class="title"><?php __('booking_employee'); ?></label>
			<span class="data left float_left">---</span>
			<br class="clear_left" />
			<input type="hidden" name="employee_id" value="" class="ignore" />
		</div>
	<?php } ?>
	
	<?php if (isset($_GET['start_ts']) && $_GET['start_ts'] > 0 ) { ?>
		<div class="b10 p bStartTime" style="display: none">
			<label class="title"><?php __('booking_start_time'); ?></label>
			<span class="data left float_left"><?php echo date($tpl['option_arr']['o_time_format'], $_GET['start_ts']); ?></span>
			<br class="clear_left" />
			<input type="hidden" name="start_ts" value="<?php echo $_GET['start_ts'] - $service_before; ?>" class="ignore" />
		</div>
		<div class="b10 p bEndTime" style="display: none">
			<label class="title"><?php __('booking_end_time'); ?></label>
			<span class="data left float_left endLabel"><?php echo date($tpl['option_arr']['o_time_format'], $_GET['start_ts'] + $service_length); ?></span>
			<br class="clear_left" />
			<input type="hidden" name="end_ts" value="<?php echo $_GET['start_ts'] + $service_length - $service_before; ?>" class="ignore" />
		</div>
	
	<?php } else { ?>
		<div class="b10 p bStartTime" style="display: none">
			<label class="title"><?php __('booking_start_time'); ?></label>
			<span class="data left float_left">---</span>
			<br class="clear_left" />
			<input type="hidden" name="start_ts" value="" class="ignore" />
		</div>
		<div class="b10 p bEndTime" style="display: none">
			<label class="title"><?php __('booking_end_time'); ?></label>
			<span class="data left float_left endLabel">---</span>
			<br class="clear_left" />
			<input type="hidden" name="end_ts" value="" class="ignore" />
		</div>
	<?php } ?>
	</div>

    <div class="float_left w300">
	<div class="b10 p">
		<label class="title"><?php __('categories'); ?></label>
		<select name="category_id" class="pj-form-field w200 stock-product">
			<?php
			foreach ($tpl['categories_arr'] as $category)
			{
				?><option value="<?php echo $category['id']; ?>"><?php echo pjSanitize::html($category['name']); ?></option><?php
			}
			?>
		</select>
	</div>
	
	<div class="b10 p" id="bookingServices">
		<label class="title"><?php __('booking_service'); ?></label>
		<select name="service_id" class="pj-form-field w200 stock-product">
			<?php if ( !isset($_GET['employee_id']) || $_GET['employee_id'] < 1 ) {?>
			<option value="">-- <?php __('booking_service'); ?> --</option>
			<?php } ?>
			<?php
			foreach ($tpl['service_arr'] as $service)
			{
				$service_length = $service['total'] * 60;
				$service_before = $service['before'] * 60;
				?><option 
						value="<?php echo $service['id']; ?>"
						<?php if (isset($_GET['start_ts']) && $_GET['start_ts'] > 0) { ?>
						data-start_ts="<?php echo $_GET['start_ts'] - $service_before; ?>" 
						data-end_ts="<?php echo $_GET['start_ts'] + $service_length - $service_before; ?>"
						data-end="<?php echo date($tpl['option_arr']['o_time_format'], $_GET['start_ts'] + $service_length); ?>"
						<?php } ?>
					><?php echo pjSanitize::html($service['name']); ?></option><?php
			}
			?>
		</select>
	</div>
	
	<div class="b10 p" id="bookingServicesTime" style="<?php echo isset($tpl['st_arr']) ? null : 'display: none'; ?>">
		<?php if ( isset($tpl['st_arr']) ) { 
		
			if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 1 ) {
				$service_length = $tpl['service_arr'][0]['total'] * 60;
				$service_before = $tpl['service_arr'][0]['before'] * 60;
			}
		?>
		<label class="title"><?php __('serviceTime'); ?></label>
		<select name="servicetime_id" class="pj-form-field w200 stock-product">
			<option value=""
			
			<?php if (isset($_GET['start_ts']) && $_GET['start_ts'] > 0) { ?>
			data-start_ts="<?php echo $_GET['start_ts'] - $service_before; ?>" 
			data-end_ts="<?php echo $_GET['start_ts'] + $service_length - $service_before; ?>"
			data-end="<?php echo date($tpl['option_arr']['o_time_format'], $_GET['start_ts'] + $service_length); ?>"
			<?php } ?>
			><?php echo $tpl['service_arr'][0]['total']; ?> <?php __('front_minutes'); ?> <?php echo isset($tpl['service_arr'][0]['description']) && !empty($tpl['service_arr'][0]['description']) ? '(' . $tpl['service_arr'][0]['description'] . ')' : null; ?></option>
			<?php
			foreach ($tpl['st_arr'] as $st) {
				$service_length = $st['total'] * 60;
				$service_before = $st['before'] * 60;
				?><option value="<?php echo $st['id']; ?>"
				
				<?php if (isset($_GET['start_ts']) && $_GET['start_ts'] > 0) { ?>
				data-start_ts="<?php echo $_GET['start_ts'] - $service_before; ?>" 
				data-end_ts="<?php echo $_GET['start_ts'] + $service_length - $service_before; ?>"
				data-end="<?php echo date($tpl['option_arr']['o_time_format'], $_GET['start_ts'] + $service_length); ?>"
				<?php } ?>
				><?php echo $st['total']; ?> <?php __('front_minutes'); ?>  <?php echo isset($st['description']) && !empty($st['description']) ? '(' . $st['description'] . ')' : null; ?></option><?php
			}
			?>
		</select>
		<?php } ?>
	</div>
    <div class="b10 p" id="bookingServiceEdit">
        <label class="title">Muokkaa aikka</label>
        <?php 
            $time = array();
            for ( $i = 0; $i <= 60; $i += $tpl['option_arr']['o_step']) {
                $time[] = $i;
                if($i > 0) $time[] = -$i;
            }
            sort($time);
        ?>
        <select name="service_edittime" class="pj-form-field w200 stock-product">
            <?php foreach ( $time as $t ) { ?>
                <option  <?php echo $t == 0 ? 'selected="selected"' : null; ?> value="<?php echo $t; ?>"><?php echo $t; ?> min</option>
            <?php } ?>
        </select>
    </div>
    </div>
	<?php
	if (empty($tpl['service_arr']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles['ABK14'], @$bodies['ABK14']);
	}
	?>
	<div class="item_details" style="display: none"></div>
</form>
<?php } ?>

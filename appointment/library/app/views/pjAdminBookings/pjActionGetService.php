<?php 
if ( isset($_GET['category_id']) && (int) $_GET['category_id'] > 0 ) { ?>
<div>
	<div class="bookingServices">
		<label class="title"><?php __('booking_service'); ?></label>
		<select name="service_id" class="pj-form-field w300 stock-product">
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
	
	<div class="bookingServicesTime">
		<?php if ( isset($tpl['st_arr']) ) { 
			
			if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 1 ) {
				$service_length = $tpl['service_arr'][0]['total'] * 60;
				$service_before = $tpl['service_arr'][0]['before'] * 60;
			}
		?>
		<label class="title">Service Time</label>
		<select name="servicetime_id" class="pj-form-field w300 stock-product">
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
				><?php echo $st['total']; ?> <?php __('front_minutes'); ?>   <?php echo isset($st['description']) && !empty($st['description']) ? '(' . $st['description'] . ')' : null; ?></option><?php
			}
			?>
		</select>
		<?php } ?>
	</div>
</div>

<?php } elseif (isset($_GET['id']) && (int) $_GET['id'] > 0 && isset($_GET['date']) && !empty($_GET['date'])) { ?>
<div>
	
	<div class="bookingServicesTime">
		<label class="title">Service Time</label>
		<?php 
			$service_length = $tpl['service_arr']['total'] * 60;
			$service_before = $tpl['service_arr']['before'] * 60;
		?>
		<select name="servicetime_id" class="pj-form-field w300 stock-product">
			<option value=""
			
			<?php if (isset($_GET['start_ts']) && $_GET['start_ts'] > 0) { ?>
			data-start_ts="<?php echo $_GET['start_ts'] - $service_before; ?>" 
			data-end_ts="<?php echo $_GET['start_ts'] + $service_length - $service_before; ?>"
			data-end="<?php echo date($tpl['option_arr']['o_time_format'], $_GET['start_ts'] + $service_length); ?>"
			<?php } ?>
			
			><?php echo $tpl['service_arr']['total']; ?> <?php __('front_minutes'); ?> <?php echo isset($tpl['service_arr']['description']) && !empty($tpl['service_arr']['description']) ? '(' . $tpl['service_arr']['description'] . ')' : null; ?></option>
			<?php
			foreach ($tpl['st_arr'] as $st)
			{
				$service_length = $st['total'] * 60;
				$service_before = $st['before'] * 60;
				
				?><option value="<?php echo $st['id']; ?>"
				
				<?php if (isset($_GET['start_ts']) && $_GET['start_ts'] > 0) { ?>
				data-start_ts="<?php echo $_GET['start_ts'] - $service_before; ?>" 
				data-end_ts="<?php echo $_GET['start_ts'] + $service_length - $service_before; ?>"
				data-end="<?php echo date($tpl['option_arr']['o_time_format'], $_GET['start_ts'] + $service_length); ?>"
				<?php } ?>
				><?php echo $st['total']; ?> <?php __('front_minutes'); ?>   <?php echo isset($st['description']) && !empty($st['description']) ? '(' . $st['description'] . ')' : null; ?></option><?php
			}
			?>
		</select>
	</div>
	
	<div class="item_details">
		<style type="text/css">
		.asContainer .asElement{
			background-color: #fff;
			border-top: solid 1px #E0E4E5;
			padding: 10px 0;
		}
		.asContainer .asElementOutline{
			margin: 0;
			overflow: hidden;
		}
		.asContainer .asEmployeeName{
			color: #0082ce;
			font: normal 1.2em PTSansBold, sans-serif;
			margin: 0 0 10px;
		}
		.asContainer .asEmployeeAvatar{
			float: left;
			width: 20%;
			box-sizing: border-box;
		}
		.asContainer .asEmployeeAvatar img{
			border: none;
			width: 100px;
			height: 100px;
			vertical-align: middle;
		}
		.asContainer .asEmployeeInfo{
			background-color: inherit;
			float: right;
			width: 80%;
			box-sizing: border-box;
		}
		.asContainer .asEmployeeNA{
			font-family: PTSansBold, sans-serif;
			text-transform: uppercase;
		}
		.asContainer .asEmployeeTimeslots{
			overflow: hidden;
			margin: 0 0 10px;
		}
		.asContainer .asEmployeeTime{
			margin: 0 0 5px;
			overflow: hidden;
			padding: 0 10px;
			width: 35%;
		}
		.asContainer .asEmployeeTimeLabel{
			color: #777;
			font-weight: normal;
			float: left;
		}
		.asContainer .asEmployeeTimeValue{
			float: right;
		}
		/* Slots */
		.asContainer .asSlotBlock{
			float: left;
			display: inline-block;
			font-size: 0.8em;
			width: 44px;
			height: 30px;
			line-height: 30px;
			text-align: center;
			margin: 0 1px 1px 0;
		}
		.asContainer .asSlotBlockWide{
			width: 67px !important;
		}
		.asContainer .asSlotAvailable{
			background-color: #def9cd;
			color: #414748;
			cursor: pointer;
			text-shadow: 1px 1px 1px #fff;
		}
		.asContainer .asSlotBooked{
			background-color: #DA384B;
			color: #fff;
		}
		.asContainer .asSlotCart{
			background-color: #01A2FF;
			color: #fff;
		}
		.asContainer .asSlotUnavailable{
			background-color: #dedede;
			color: #414748;
		}
		.asContainer .asSlotSelected{
			background-color: #0082ce;
			color: #fff;
			text-shadow: none;
		}
		</style>
		<div class="asContainer">
		<?php
		if (isset($tpl['employee_arr']) && !empty($tpl['employee_arr']))
		{
			$wideCell = NULL;
			if (in_array($tpl['option_arr']['o_time_format'], array('h:i a', 'h:i A', 'g:i a', 'g:i A')))
			{
				$wideCell = " asSlotBlockWide";
			}
			$step = $tpl['option_arr']['o_step'] * 60;
			$service_length = $tpl['service_arr']['total'] * 60;
			$service_before = $tpl['service_arr']['before'] * 60;
			foreach ($tpl['employee_arr'] as $employee)
			{
				# Fix for 24h support
				$offset = $employee['t_arr']['end_ts'] <= $employee['t_arr']['start_ts'] ? 86400 : 0;
				?>
				<div class="asElement asElementOutline">
					<div class="asEmployeeName"><?php echo pjSanitize::html($employee['name']); ?></div>
					<div class="asEmployeeAvatar">
						<?php
						if (!empty($employee['avatar']) && is_file($employee['avatar']))
						{
							$src = PJ_INSTALL_URL . $employee['avatar'];
						} else {
							$src = PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/as-nopic-gray.gif';
						}
						?>
						<img src="<?php echo $src; ?>" alt="<?php echo pjSanitize::html($employee['name']); ?>" />
					</div>
					<div class="asEmployeeInfo">
						<?php
						$isAvailable = true;
						if (!$employee['t_arr'])
						{
							$isAvailable = false;
							?><div class="asEmployeeNA"><?php __('booking_na'); ?></div><?php
						} else {
							
							?>
							<div class="asEmployeeTimeslots">
							<?php
							for ($i = $employee['t_arr']['start_ts']; $i <= $employee['t_arr']['end_ts'] + $offset - $step; $i += $step)
							{
								$is_free = true;
								$class = "asSlotAvailable";
								foreach ($employee['bs_arr'] as $item)
								{
									if ($i >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60)
									{
										$is_free = false;
										$class = "asSlotBooked";
										break;
									}
								}
								
								if ($i < time())
								{
									$is_free = false;
									$class = "asSlotUnavailable";
								}
								
								foreach ( $employee['ef_arr'] as $freetime ) {
								
									if ($i >= $freetime['start_ts'] && $i < $freetime['end_ts']) {
										$is_free = false;
										$class = "asSlotUnavailable";
										break;
									}
								}
								
								if ($i >= $employee['t_arr']['lunch_start_ts'] && $i < $employee['t_arr']['lunch_end_ts'])
								{
									$is_free = false;
									$class = "asSlotUnavailable";
								}
										
								if ($is_free)
								{
									foreach ($employee['bs_arr'] as $item)
									{
										if ($i + $service_length - $service_before > $item['start_ts'] && $i <= $item['start_ts'])
										{
											// before booking
											$class = "asSlotUnavailable";
											break;
										}
									}
									if ($i + $service_length - $service_before > $employee['t_arr']['end_ts'] + $offset)
									{
										// end of working day
										$class = "asSlotUnavailable";
									}
								}
								?><span class="asSlotBlock <?php echo $class; ?><?php echo $wideCell; ?>" data-end="<?php echo date($tpl['option_arr']['o_time_format'], $i + $service_length); ?>" data-start_ts="<?php echo $i - $service_before; ?>" data-end_ts="<?php echo $i + $service_length - $service_before; ?>" data-service_id="<?php echo $employee['service_id']; ?>" <?php echo isset($_GET['servicetime_id']) && (int) $_GET['servicetime_id'] > 0 ? 'data-servicetime_id=' . $_GET['servicetime_id'] : null; ?> data-employee_id="<?php echo $employee['employee_id']; ?>"><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></span><?php
							}
							?>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
		} else {
			$titles = __('error_titles', true);
			$bodies = __('error_bodies', true);
			pjUtil::printNotice(@$titles['ABK15'], @$bodies['ABK15']);
		} ?>
		</div>
	</div>
</div>
<?php } ?>
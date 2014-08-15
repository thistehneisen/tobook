<?php include_once VIEWS_PATH . 'pjHelpers/time.widget.php'; ?>
<?php
$STORAGE = &$_SESSION[$controller->default_product][$controller->default_order];
?>
<div class="rbBox">
	
		<?php if (isset($tpl['formstyle'][0]['banner']) && !empty($tpl['formstyle'][0]['banner'])) { ?>
		<div class="banner">
			<img alt="" src="<?php echo $tpl['formstyle'][0]['banner']; ?>">
		</div>
		<?php } else { ?>
		<div class="rbBoxWTop">
			<?php echo $RB_LANG['front']['1_title']; ?>
		</div>
		<?php } ?>
	
	<div class="rbBoxMiddle">
		<?php if (isset($tpl['formstyle'][0]['logo']) && !empty($tpl['formstyle'][0]['logo'])) { ?>
			<div class="form-logo"><img alt="" src="<?php echo $tpl['formstyle'][0]['logo']; ?>"></div>
		<?php } ?>
		<form action="" method="post" class="rbForm" name="RBookingMap_<?php echo $_GET['index']; ?>">
			<input type="hidden" name="rbSearch" value="1" />
			<?php
			if (isset($STORAGE['table_id']))
			{
				foreach ($tpl['table_arr'] as $table)
				{
					if ($table['id'] == $STORAGE['table_id'])
					{
						?><input type="hidden" name="table_id" value="<?php echo $STORAGE['table_id']; ?>" rel="<?php echo htmlspecialchars(stripslashes($table['name'])); ?>" rev="sbook-h<?php echo $_GET['index']; ?>_<?php echo $STORAGE['table_id']; ?>" class="sbook-hid" /><?php
						break;
					}
				}
			}
			?>
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['1_date']; ?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?rbpf=<?php echo PREFIX; ?>" class="rbDatepickerIcon" id="rbDate_<?php echo $_GET['index']; ?>"></a></label>
				<input type="text" id="rb_date_<?php echo $_GET['index']; ?>" name="date" class="rbText rbPointer rbMr5 rbW100 rbFloatLeft" value="<?php echo isset($STORAGE) && isset($STORAGE['date']) ? htmlspecialchars($STORAGE['date']) : date($tpl['option_arr']['date_format'], strtotime("+1 day")); ?>" readonly="readonly" />
				
			</p>
			
			<?php if ( isset($tpl['option_arr']['booking_front_end']) && 
						$tpl['option_arr']['booking_front_end'] == 2 && 
						isset($tpl['service_arr']) && count($tpl['service_arr']) > 0 ) { 
						
					$rbTimeBox = 'style="display: none"';
					$i=0;
				?>
				<div id="service">
					<p id="serviceHeader">
					<?php foreach ($tpl['service_arr'] as $service) { ?>
						<label for="service-<?php echo $service['id']; ?>">
						
							<input type="radio" name="service" 
									value="<?php echo $service['id']; ?>" 
									id="service-<?php echo $service['id']; ?>"
									onclick="showtime(this)"
									<?php if ( $i == 0 ) echo ' checked="checked"'; ?>
								/>
							
							<?php echo $service['s_name']; ?>
						</label>
					<?php $i++; } ?>
					</p>
					
					<div id="serviceContent">
					<?php $i=0; foreach ($tpl['service_arr'] as $service) { ?>
						<div class="service service-<?php echo $service['id']; ?>" <?php if( $i != 0 ) echo 'style="display: none"'; ?>>
							
							<?php for ( $h = strtotime($service['start_time']); $h < strtotime($service['end_time']); $h += 30*60 ) { ?>
								
								<a href="#" 
										data-h="<?php echo date('H', $h); ?>" 
										data-i="<?php echo date('i', $h); ?>"
										onclick="settime(this)"
									>
									<?php echo date('H:i', $h); ?>
								</a>
							<?php } ?>
						</div>
					<?php $i++; } ?>
					</div>
				</div>
				
			<?php } else { $rbTimeBox = ''; }?>
				<p id="rbTimeBox" <?php echo $rbTimeBox; ?>>
					<label class="rbLabel"><?php echo $RB_LANG['front']['1_hour']; ?></label>
					<?php
					# Fix 24h support
					$offset = $tpl['wt_arr']['end_hour'] <= $tpl['wt_arr']['start_hour'] ? 24 : 0;
					
					$hf = isset($STORAGE) && isset($STORAGE['hour']) ? $STORAGE['hour'] : 9;
					$mf = isset($STORAGE) && isset($STORAGE['minutes']) ? $STORAGE['minutes'] : null;
					$booking_length = ceil((int) $tpl['option_arr']['booking_length'] / 60);
					$start = (int) $tpl['wt_arr']['start_hour'];
					$end = (int) $tpl['wt_arr']['end_hour'] - $booking_length + $offset;
					
					if ($end < $start)
					{
						$end = $start;
					}
					?>
					<?php TimeWidget::hour($hf, 'hour', 'rb_hour_' . $_GET['index'], 'rbSelect rbMr10', array(), array('start' => $start, 'end' => $end, 'time_format' => $tpl['option_arr']['time_format'])); ?>
					<?php TimeWidget::minute($mf, 'minutes', 'rb_minutes_' . $_GET['index'], 'rbSelect', array(), 5); ?>
				</p>
			
			<p>
				<label class="rbLabel"><?php echo $RB_LANG['front']['1_people']; ?></label>
				<select name="people" id="rb_people_<?php echo $_GET['index']; ?>" class="rbSelect" style="float: left">
					<option value="">--</option>
					<?php
					foreach (range(1, 20) as $i)
					{
						?><option value="<?php echo $i; ?>"<?php echo isset($STORAGE['people']) && $STORAGE['people'] == $i ? ' selected="selected"' : NULL; ?>><?php echo $i; ?></option><?php
					}
					?>
				</select>
			</p>
			<p style="display: none">
				<label class="rbLabel"><?php echo $RB_LANG['front']['1_table']; ?></label>
				<span id="RBooking_RestaurantHolder_<?php echo $_GET['index']; ?>" style="overflow: hidden; display: block">
				<?php
				if (isset($STORAGE['table_id']) && (int) $tpl['option_arr']['use_map'] === 1)
				{
					foreach ($tpl['table_arr'] as $table)
					{
						if ($table['id'] == $STORAGE['table_id'])
						{
							?>
							<span class="RBooking_Table_Holder" rev="sbook-h<?php echo $_GET['index']; ?>_<?php echo $STORAGE['table_id']; ?>" data-people="<?php echo $STORAGE['people']; ?>">
								<span class="RBooking_Table"><?php echo stripslashes($table['name']); ?></span>
								<span class="RBooking_Table_Price"><?php echo pjUtil::formatCurrencySign(number_format($tpl['option_arr']['booking_price']), $tpl['option_arr']['currency']); ?></span>
							</span>
							<?php
							break;
						}
					}
				}
				?>
				</span>
			</p>
			<p class="rbParagraph">
				<input type="button" id="rbBtnContinue" value="" class="rbBtn rbBtnBook" />
			</p>
			<p class="rbError" style="display: none"></p>
		</form>

	</div>
	<div class="rbBoxBottom">
		<div class="rbBoxBottomLeft"></div>
		<div class="rbBoxBottomRight"></div>
	</div>
</div>
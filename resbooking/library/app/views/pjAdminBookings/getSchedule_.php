<?php
include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);

$date = isset($_GET['date']) ? $_GET['date'] : date($tpl['option_arr']['date_format']);
if ($tpl['wt_arr'] === false)
{
	pjUtil::printNotice(sprintf($RB_LANG['errors']['AB12'], $date));
} else {
	# Fix for 24h support
	$offset = $tpl['wt_arr']['end_hour'] <= $tpl['wt_arr']['start_hour'] ? 24 : 0;
					
	$numOfHours = abs($tpl['wt_arr']['start_hour'] - $tpl['wt_arr']['end_hour'] - $offset);
	if (count($tpl['arr']) > 0)
	{
		?>
		<div class="cal-container">
			<div id="date-message" style="display: none">
				<?php if (isset($tpl['date_arr'][0]) && count($tpl['date_arr'][0]) > 0) {?>
					<!-- <a style="float: right;" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=update&amp;id=<?php echo $tpl['date_arr'][0]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_edit']; ?></a>-->
					<a style="float: right;" href="#editCustomTime" data-toggle="modal" data-id=<?php echo $tpl['date_arr'][0]['id']; ?>"><?php echo $RB_LANG['_edit']; ?></a>
					<p><?php echo isset($tpl['date_arr'][0]['message']) ? $tpl['date_arr'][0]['message'] : ''; ?></p>
				<?php } else { ?>
					<a style="float: right;" href="#addCustomTime" data-toggle="modal">Add</a>
				<?php } ?>
			</div>
			<div class="cal-calendars">
				<div class="cal-title" style="height: 64px"></div>
				<?php
				foreach ($tpl['arr'] as $k => $table)
				{
					?><div class="cal-title"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTables&amp;action=update&amp;id=<?php echo $table['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo stripslashes($table['name']); ?> (<?php echo $table['seats']; ?>)</a></div><?php
				}
				?>
			</div>
			<div class="cal-dates">
				<div class="cal-scroll">
				<?php
				foreach ($tpl['arr'] as $k => $table)
				{
					//var_dump($table);
					if ($k == 0)
					{
						?>
						<div class="cal-head">
							<div class="cal-head-row">
								<span style="width: <?php echo 56 * $numOfHours - 3; ?>px"><?php echo $date; ?></span>
							</div>
							<div class="cal-head-row">
							<?php
							for ($i = $tpl['wt_arr']['start_hour']; $i < $tpl['wt_arr']['end_hour']; $i++)
							{
								?><span><?php echo $i; ?></span><?php
							}
							?>
							</div>
						</div>
						<?php
						
					}
					?>
					<div class="cal-program">
					<?php
					for ($i = $tpl['wt_arr']['start_hour']; $i < $tpl['wt_arr']['end_hour'] + $offset; $i++)
					{
		    	    	$class = pjUtil::getClass($table['hour_arr'], $i);
		    	    	$label = $i < 24 ? $i : $i - $offset; //24h
		    	    	if (isset($table['hour_arr'][$i]) && count($table['hour_arr'][$i]) > 0) { ?>
		    	    		<?php if ( (!isset($table['hour_arr'][$i-1]['id'])) || $table['hour_arr'][$i-1]['id'] != $table['hour_arr'][$i]['id'] ) {?> 
		    	    			<a href="#updatebooking" data-toggle="modal" data-id="<?php echo $table['hour_arr'][$i]['id']; ?>" class="customer-name <?php echo $class; ?>">
		    	    		<?php }?>
									<span ><?php echo $label; ?></span>
							<?php if ( (!isset($table['hour_arr'][$i+1]['id'])) || $table['hour_arr'][$i+1]['id'] != $table['hour_arr'][$i]['id'] ) {?> 
									<span class="name">
										<?php 
										if ( !empty($table['hour_arr'][$i]['c_notes']))
											echo '<span class="notes" rel="tooltip" data-original-title="'. $table['hour_arr'][$i]['c_notes'] .'" title="'. $table['hour_arr'][$i]['c_notes'] .'">?</span>';
											
										$name = $table['hour_arr'][$i]['c_fname'] . ' ' . $table['hour_arr'][$i]['c_lname'];
										
										if ( !empty($table['hour_arr'][$i]['people'])) {
											$name = $name . ' (' . $table['hour_arr'][$i]['people'] . ') ';
										}
										echo $name; ?>
									</span>
								</a>
							<?php } ?>
						<?php } else { ?>
							<a href="#addbooking"
								data-toggle="modal" 
								data-date="<?php echo $date; ?>" 
								data-hour="<?php echo $label; ?>" 
								data-tableid="<?php echo $table['id']; ?>"
								data-table-seats="<?php echo $table['seats']; ?>" 
								data-table-minimum="<?php echo $table['minimum']; ?>"
							>
								<?php echo $label; ?>
							</a>
						<?php }
					}
					?>
					</div>
					<?php
				}
				?>
				<!-- Modal -->
					<div class="modal hide fade" id="updatebooking" style="display: none;">
						<div class="modal-body">
							<p>Loading...</p>
						</div>
					</div>
					
					<div class="modal hide fade" id="addbooking" style="display: none;">
						<div class="modal-body">
							<link href="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&action=loadCss" type="text/css" rel="stylesheet" />
							<form id="frmAddbooking" class="rbForm form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule&amp;rbpf=<?php echo PREFIX; ?>">
								<input type="hidden" value="1" name="rbBookingForm" class="error_title" rev="<?php echo $RB_LANG['front']['4_v_err_title']; ?>">
								<input type="hidden" value="" name="rbBooking_date" class="rbBooking-date">
								<input type="hidden" value="" name="rbBooking_hour" class="rbBooking-hour">
								<input type="hidden" value="" name="rbBooking_tableid" class="rbBooking-tableid">
								<div class="rbLegend">
									<span class="rbLegendLeft">&nbsp;</span>
									<span class="rbLegendText"><?php echo $RB_LANG['front']['4_personal']; ?></span>
									<span class="rbLegendRight">&nbsp;</span>
								</div>
								<p>
									<label class="rbLabel"><?php echo $RB_LANG['front']['4_fname']; ?><span class="rbRed">*</span></label>
									<input type="text" name="c_fname" class="rbText rbW320 rbRequired required" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_fname'])); ?>" value="" />
								</p>
								<p>
									<label class="rbLabel"><?php echo $RB_LANG['front']['4_lname']; ?> <span class="rbRed">*</span></label>
									<input type="text" name="c_lname" class="rbText rbW320 rbRequired required" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_lname'])); ?>" value="" />
								</p>
								<p>
									<label class="rbLabel"><?php echo $RB_LANG['front']['4_phone']; ?> <span class="rbRed">*</span></label>
									<input type="text" name="c_phone" class="rbText rbW320 rbRequired required" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_phone'])); ?>" value="" />
								</p>
								<p>
									<label class="rbLabel"><?php echo $RB_LANG['front']['4_email']; ?> <span class="rbRed">*</span></label>
									<input type="text" name="c_email" class="rbText rbW320 rbEmail rbRequired required" rev="<?php echo htmlspecialchars(stripslashes($RB_LANG['front']['4_v_email'])); ?>" value="" />
								</p>
								<p>
									<label class="rbLabel"><?php echo $RB_LANG['front']['1_people']; ?></label>
									<select name="people" id="rb_people" class="rbSelect" style="float: left">
										<?php
										foreach (range(1, 20) as $i)
										{
											?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
										}
										?>
									</select>
								</p>
														
								<p>
									<input type="submit" class="rbBtn rbBtnReview rbFloatRight  " id="rbBtnContinue" value="">
								</p>
								<p style="display: none" class="rbError"></p>
							</form>
						</div>
					</div>
					
					<div class="modal hide fade" id="addCustomTime" style="display: none;">
						<div class="modal-body">
							
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=index&amp;rbpf=<?php echo PREFIX; ?>" method="post" class="form" id="frmTimeCustom">
								<input type="hidden" name="custom_time" value="1" />
								<fieldset class="fieldset white">
									<legend><?php echo $RB_LANG['time_custom']; ?></legend>
									<p>
										<label class="title"><?php echo $RB_LANG['time_date']; ?></label>
										<input type="text" name="date" id="dateCustomTime" class="text w80 datepick pointer" readonly="readonly" value="<?php echo isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date($tpl['option_arr']['date_format']); ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
									</p>
									<p>
										<label class="title"><?php echo $RB_LANG['time_from']; ?></label>
										<?php TimeWidget::hour(9, 'start_hour', 'start_hour', 'select pps', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
										<?php TimeWidget::minute(null, 'start_minute', 'start_minute', 'select w50 pps'); ?>
									</p>
									<p>
										<label class="title"><?php echo $RB_LANG['time_to']; ?></label>
										<?php TimeWidget::hour(23, 'end_hour', 'end_hour', 'select pps', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
										<?php TimeWidget::minute(null, 'end_minute', 'end_minute', 'select w50 pps'); ?>
									</p>
									<p>
										<label class="title"><?php echo $RB_LANG['time_is']; ?></label>
										<span class="left"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T" /></span>
									</p>
									<p>
										<label class="title"><?php echo $RB_LANG['time_message']; ?></label>
										<span class="left"><textarea class="textarea" rows="5" cols="50" name="message"></textarea></span>
									</p>
									<p id="boxPPS">
									
									</p>
									<p>
										<input type="submit" value="" class="button button_save"  />
									</p>
								</fieldset>
							</form>
						</div>
					</div>
					
					<div class="modal hide fade" id="editCustomTime" style="display: none;">
						<div class="modal-body">
							<p>Loading...</p>
						</div>
					</div>
	
				</div>
			</div>
		</div>
		<?php
	} else {
		pjUtil::printNotice($RB_LANG['table_empty']);
	}
}
?>
<?php
use Zend\Memory\Value;
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
	
	include dirname(__FILE__) . '/elements/menu.php';
	
	$pjTime = pjTime::factory();
	
	if ( isset($_GET['date']) && !empty($_GET['date']) ){
		$date = date('Y-m-d', strtotime($_GET['date']));
	
	} else $date = date('Y-m-d');
		
		?>
		<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionCustomTimes&amp;as_pf=<?php echo $as_pf; ?>">Luo kustomoitu aika</a></li>
				<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionEmployeeCustomTime&amp;as_pf=<?php echo $as_pf; ?>">Työvuorosuunnittelu</a></li>
			</ul>
		</div>
		<?php if ( count($tpl['employees_arr']) > 0 && count($tpl['customtime_arr']) > 0 ) { ?>
		<div id="monthly-review">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionEmployeeCustomTime&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmEmployeeCustomtime" class="form pj-form">
				
				<input type="hidden" name="EmployeeCustomTime" value="1"> 
				
				<h3 style="margin-top: 20px; font-size: 20px; text-align: left; font-weight: bold; line-height: 30px; text-align: center; border-width: 1px 1px 0px; border-style: solid; border-color: rgb(204, 204, 204);">
					<div style="width: 65%; display: inline-block; text-align: center;">
						<a class="monthly-control float_left" href="#" data-date="<?php echo (date('m', strtotime($date)) - 1) > 0 ? date('Y', strtotime($date)) .'-'. (date('m', strtotime($date)) - 1) .'-'.date('d', strtotime($date)) : $date; ?>"><small>Edellinen</small></a>
						<?php echo date('F', strtotime($date)); ?>
						<a class="monthly-control float_right" href="#" data-date="<?php echo (date('m', strtotime($date) + 1)) > 12 ? $date : date('Y', strtotime($date)) .'-'. (date('m', strtotime($date)) + 1) .'-'.date('d', strtotime($date)); ?>"><small>Seuraava</small></a>
					</div>
					<select name="employee_id" id="employee_id" class="float_right" style="margin-top: 0; ">
						<?php 
						foreach($tpl['employees_arr'] as $e ) {
							if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 && $_GET['employee_id'] == $e['id'] ) {
								$selected = 'selected="selected"';
									
							} else $selected = '';
							?>
							<option <?php echo $selected; ?> value="<?php echo $e['id']; ?>"><?php echo $e['name']; ?></option>
						<?php } ?>
					</select>
				</h3>
				<table cellspacing="0" cellpadding="0" class="pj-table" style="width: 100%;">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th><?php echo $tpl['e_arr']['name']?></th>
						</tr>
					</thead>
					<tbody>
					<?php 
					
					$week = array( "Sunnuntai", "Maanantai", "Tiistai", "Keskiviikko", "Torstai", "Perjantai", "Lauantai" );
					$week_start = $week[$tpl['option_arr']['o_week_start']];
					
					$count_days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($date)), strtotime($date));
					for ( $i = 1; $i <= $count_days; $i++) {
						$day = date('Y-m-d', strtotime(date('Y-m', strtotime($date)).'-' . $i));
						
						?>
						<tr class="<?php echo $i%2!=0 ? "pj-table-row-odd" : "pj-table-row-even"?>">
							<td><?php echo date('l', strtotime($day))?></td>
							<td><?php echo date($tpl['option_arr']['o_date_format'], strtotime($day));?></td>
							<td>
							<select name="<?php echo 'id_' . $i . '_' . $tpl['e_arr']['id']; ?>">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php foreach ($tpl['customtime_arr'] as $customtime ) { 
									$selected = '';
									
									?>
								<option  <?php echo isset($tpl['e_arr'][$day]) && $tpl['e_arr'][$day]['customtime_id'] == $customtime['id'] ? 'selected="selected"' : null; ?> value="<?php echo $customtime['id']; ?>"><?php echo $customtime['name']; ?></option>
								<?php } ?>
							</select>
							</td>
						</tr>
						<?php 
						if ( $week_start == date('l', strtotime($day) + 86400) ) { ?>
													
						<tr class="pj-table-row-week">
							<td>Viikon tunnit</td>
							<td></td>
							<td>
							<?php 
							if ( isset($tpl['e_arr'][$i]) ) {
								$min = $tpl['e_arr'][$i] /60;
								$hours =  floor($min/60);
								$min = $min%60;
								
								echo $hours . 'h' . $min;
							}
							?>
							</td>
						</tr>
						<?php } ?>
					<?php } ?>
					</tbody>
				</table>
				
				<table cellspacing="0" cellpadding="0" class="pj-table" style="width: 100%; margin-top: 20px;">
					<thead>
						<tr>
							<th></th>
							<?php foreach($tpl['employees_arr'] as $e ) { ?>
							<th><?php echo $e['name']; ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<tr class="pj-table-row-odd">
						<td>Lauantai tunnit</td>
						<?php foreach($tpl['employees_arr'] as $e ) { ?>
						<td>
						<?php 
						if ( isset($tpl['employee_hours'][$e['id']]['saturday_hours']) ) {
							$min = $tpl['employee_hours'][$e['id']]['saturday_hours'] /60;
							$hours =  floor($min/60);
							$min = $min%60;
						
							echo $hours . 'h' . $min;
							
						} else {
							echo '0h00';
						}
						?>
						</td>
						<?php } ?>
					</tr>
					<tr class="pj-table-row-even">
						<td>Sunnuntai tunnit</td>
						<?php foreach($tpl['employees_arr'] as $e ) { ?>
						<td>
						<?php 
						if ( isset($tpl['employee_hours'][$e['id']]['sunday_hours']) ) {
							$min = $tpl['employee_hours'][$e['id']]['sunday_hours'] /60;
							$hours =  floor($min/60);
							$min = $min%60;
						
							echo $hours . 'h' . $min;
							
						} else {
							echo '0h00';
						}
						?>
						</td>
						<?php } ?>
					</tr>
					<tr class="pj-table-row-odd">
						<td>Kuukauden tunnit</td>
						<?php foreach($tpl['employees_arr'] as $e ) { ?>
						<td>
						<?php 
						if ( isset($tpl['employee_hours'][$e['id']]['month_hours']) ) {
							$min = $tpl['employee_hours'][$e['id']]['month_hours'] /60;
							$hours =  floor($min/60);
							$min = $min%60;
						
							echo $hours . 'h' . $min;
							
						} else {
							echo '0h00';
						}
						?>
						</td>
						<?php } ?>
					</tr>
					</tbody>
				</table>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button"  />
				</p>
					
			</form>
		</div>
		
		<script type="text/javascript">
		var myLabel = myLabel || {};
		</script>
		<?php
		}
}
?>
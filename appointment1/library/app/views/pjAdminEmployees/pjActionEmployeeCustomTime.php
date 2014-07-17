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
		?>
		<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionCustomTimes&amp;as_pf=<?php echo $as_pf; ?>">Create Custom Time</a></li>
				<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionEmployeeCustomTime&amp;as_pf=<?php echo $as_pf; ?>">Employees Custom Time</a></li>
			</ul>
		</div>
		<?php //var_dump($tpl['customtime_arr']); ?>
		<?php if ( count($tpl['employee_arr']) > 0 && count($tpl['customtime_arr']) > 0 ) { ?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionEmployeeCustomTime&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmCustomtime" class="form pj-form">
			
			<input type="hidden" name="EmployeeCustomTime" value="1"> 
			
			<fieldset class="fieldset white">
				<legend>Employees Custom Time</legend>
			
				<table cellspacing="0" cellpadding="0" class="pj-table" style="width: 100%;">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<?php foreach ($tpl['employee_arr'] as $employee) { ?>
							<th><?php echo $employee['name']?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php 
					$count_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
					
					for ( $i = 1; $i <= $count_days; $i++) {
						$day = date('Y-m-d', strtotime(date('Y-m') . '-' . $i));
						
					?>
				
						<tr class="<?php echo $i%2!=0 ? "pj-table-row-odd" : "pj-table-row-even"?>">
							<td><?php echo date('l', strtotime($day))?></td>
							<td><?php echo date($tpl['option_arr']['o_date_format'], strtotime($day));?></td>
							<?php foreach ($tpl['employee_arr'] as $employee) { ?>
							<td>
							<select name="<?php echo 'id_' . $i . '_' . $employee['id']; ?>">
								<option value="">-- <?php __('lblChoose'); ?>--</option>
								<?php foreach ($tpl['customtime_arr'] as $customtime ) { 
									$selected = '';
									
									?>
								<option  <?php echo isset($employee[$day]) && $employee[$day]['customtime_id'] == $customtime['id'] ? 'selected="selected"' : null; ?> value="<?php echo $customtime['id']; ?>"><?php echo $customtime['name']; ?></option>
								<?php } ?>
							</select>
							</td>
							<?php } ?>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button"  />
				</p>
				
			</fieldset>
		</form>
		<?php
		}
}
?>
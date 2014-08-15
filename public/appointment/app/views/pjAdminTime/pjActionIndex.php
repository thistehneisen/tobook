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
		
		pjUtil::printNotice(@$titles['AT06'], @$bodies['AT06']);
	} elseif (isset($_GET['foreign_id']) && isset($_GET['type'])) {
		$employee_id = $_GET['foreign_id'];
		include PJ_VIEWS_PATH . 'pjAdminEmployees/elements/menu.php';
		include dirname(__FILE__) . '/elements/menu_admin.php';
		
		pjUtil::printNotice(@$titles['AT06'], @$bodies['AT06']);
	} else {
		include PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
		include dirname(__FILE__) . '/elements/menu_options.php';
		
		pjUtil::printNotice(@$titles['AT04'], @$bodies['AT04']);
	}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>" method="post" class="form AdminTime">
		<input type="hidden" name="working_time" value="1" />
		<?php
		if ($controller->isAdmin())
		{
			?><input type="hidden" name="id" value="<?php echo (int) $tpl['wt_arr']['id']; ?>" /><?php
		}
		if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
		{
			?><input type="hidden" name="foreign_id" value="<?php echo (int) $tpl['wt_arr']['foreign_id']; ?>" /><?php
		}
		if (isset($_GET['type']) && !empty($_GET['type']))
		{
			?><input type="hidden" name="type" value="<?php echo pjSanitize::html($tpl['wt_arr']['type']); ?>" /><?php
		}
		?>
		<table class="pj-table" cellpadding="0" cellspacing="0" style="width: 100%;">
			<thead>
				<tr>
					<th><?php __('time_day'); ?></th>
					<th><?php __('startTimeAdmin'); ?></th>
					<th><?php __('endTimeAdmin'); ?></th>
					<th><?php __('time_from'); ?></th>
					<th><?php __('time_to'); ?></th>
					<th><?php __('time_lunch_from'); ?></th>
					<th><?php __('time_lunch_to'); ?></th>
					<th><?php __('time_is'); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;
			$days = __('days', true);
			$w_days = array(
				'monday' => $days[1],
				'tuesday' => $days[2],
				'wednesday' => $days[3],
				'thursday' => $days[4],
				'friday' => $days[5],
				'saturday' => $days[6],
				'sunday' => $days[0]
			);
			foreach ($w_days as $k => $day)
			{
				$step = 5;
				
				$pjTime_ahf = pjTime::factory()
					->attr('name', $k . '_admin_hour_from')
					->attr('id', $k . '_admin_hour_from')
					->attr('class', 'pj-form-field');
					
				$pjTime_amf = pjTime::factory()
					->attr('name', $k . '_admin_minute_from')
					->attr('id', $k . '_admin_minute_from')
					->attr('class', 'pj-form-field')
					->prop('step', $step);
				
				$pjTime_aht = pjTime::factory()
					->attr('name', $k . '_admin_hour_to')
					->attr('id', $k . '_admin_hour_to')
					->attr('class', 'pj-form-field');
					
				$pjTime_amt = pjTime::factory()
					->attr('name', $k . '_admin_minute_to')
					->attr('id', $k . '_admin_minute_to')
					->attr('class', 'pj-form-field')
					->prop('step', $step);
				
				$pjTime_hf = pjTime::factory()
					->attr('name', $k . '_hour_from')
					->attr('id', $k . '_hour_from')
					->attr('class', 'pj-form-field');
					
				$pjTime_mf = pjTime::factory()
					->attr('name', $k . '_minute_from')
					->attr('id', $k . '_minute_from')
					->attr('class', 'pj-form-field')
					->prop('step', $step);
					
				$pjTime_lhf = pjTime::factory()
					->attr('name', $k . '_lunch_hour_from')
					->attr('id', $k . '_lunch_hour_from')
					->attr('class', 'pj-form-field');
					
				$pjTime_lmf = pjTime::factory()
					->attr('name', $k . '_lunch_minute_from')
					->attr('id', $k . '_lunch_minute_from')
					->attr('class', 'pj-form-field')
					->prop('step', $step);

				$pjTime_ht = pjTime::factory()
					->attr('name', $k . '_hour_to')
					->attr('id', $k . '_hour_to')
					->attr('class', 'pj-form-field');
					
				$pjTime_mt = pjTime::factory()
					->attr('name', $k . '_minute_to')
					->attr('id', $k . '_minute_to')
					->attr('class', 'pj-form-field')
					->prop('step', $step);
					
				$pjTime_lht = pjTime::factory()
					->attr('name', $k . '_lunch_hour_to')
					->attr('id', $k . '_lunch_hour_to')
					->attr('class', 'pj-form-field');
					
				$pjTime_lmt = pjTime::factory()
					->attr('name', $k . '_lunch_minute_to')
					->attr('id', $k . '_lunch_minute_to')
					->attr('class', 'pj-form-field')
					->prop('step', $step);
					
				if (isset($tpl['wt_arr']) && !empty($tpl['wt_arr']))
				{
					$admin_hour_from = substr($tpl['wt_arr'][$k.'_admin_from'], 0, 2);
					$admin_hour_to = substr($tpl['wt_arr'][$k.'_admin_to'], 0, 2);
					$admin_minute_from = substr($tpl['wt_arr'][$k.'_admin_from'], 3, 2);
					$admin_minute_to = substr($tpl['wt_arr'][$k.'_admin_to'], 3, 2);
					
					$hour_from = substr($tpl['wt_arr'][$k.'_from'], 0, 2);
					$hour_to = substr($tpl['wt_arr'][$k.'_to'], 0, 2);
					$minute_from = substr($tpl['wt_arr'][$k.'_from'], 3, 2);
					$minute_to = substr($tpl['wt_arr'][$k.'_to'], 3, 2);
					
					$lunch_hour_from = substr($tpl['wt_arr'][$k.'_lunch_from'], 0, 2);
					$lunch_hour_to = substr($tpl['wt_arr'][$k.'_lunch_to'], 0, 2);
					$lunch_minute_from = substr($tpl['wt_arr'][$k.'_lunch_from'], 3, 2);
					$lunch_minute_to = substr($tpl['wt_arr'][$k.'_lunch_to'], 3, 2);
					
					$checked = NULL;
					//$disabled = NULL;
					if (is_null($tpl['wt_arr'][$k.'_from']))
					{
						$pjTime_ahf->attr('disabled', 'disabled');
						$pjTime_amf->attr('disabled', 'disabled');
						$pjTime_aht->attr('disabled', 'disabled');
						$pjTime_amt->attr('disabled', 'disabled');
						
						$pjTime_hf->attr('disabled', 'disabled');
						$pjTime_mf->attr('disabled', 'disabled');
						$pjTime_ht->attr('disabled', 'disabled');
						$pjTime_mt->attr('disabled', 'disabled');
						$checked = ' checked="checked"';
						//$disabled = ' disabled="disabled"';
					}
					if (is_null($tpl['wt_arr'][$k.'_from']) || is_null($tpl['wt_arr'][$k.'_lunch_from']))
					{
						$pjTime_lhf->attr('disabled', 'disabled');
						$pjTime_lmf->attr('disabled', 'disabled');
						$pjTime_lht->attr('disabled', 'disabled');
						$pjTime_lmt->attr('disabled', 'disabled');
						$checked = ' checked="checked"';
					}
				} else {
					$admin_hour_from = NULL;
					$admin_hour_to = NULL;
					$admin_minute_from = NULL;
					$admin_minute_to = NULL;

					$hour_from = NULL;
					$hour_to = NULL;
					$minute_from = NULL;
					$minute_to = NULL;
					
					$lunch_hour_from = NULL;
					$lunch_hour_to = NULL;
					$lunch_minute_from = NULL;
					$lunch_minute_to = NULL;
					
					$checked = NULL;
					//$disabled = NULL;
				}
				?>
				<tr class="<?php echo ($i % 2 !== 0 ? 'odd' : 'even'); ?>">
					<td><?php echo $day; ?></td>
					<td>
					<?php
					echo $pjTime_ahf
						->prop('selected', $admin_hour_from)
						->hour();
					?>
					<?php
					echo $pjTime_amf
						->prop('selected', $admin_minute_from)
						->minute();
					?>
					</td>
					<td>
					<?php
					echo $pjTime_aht
						->prop('selected', $admin_hour_to)
						->hour();
					?>
					<?php
					echo $pjTime_amt
						->prop('selected', $admin_minute_to)
						->minute();
					?>
					</td>
					
					<td>
					<?php
					echo $pjTime_hf
						->prop('selected', $hour_from)
						->hour();
					?>
					<?php
					echo $pjTime_mf
						->prop('selected', $minute_from)
						->minute();
					?>
					</td>
					<td>
					<?php
					echo $pjTime_ht
						->prop('selected', $hour_to)
						->hour();
					?>
					<?php
					echo $pjTime_mt
						->prop('selected', $minute_to)
						->minute();
					?>
					</td>
					<td>
					<?php
					echo $pjTime_lhf
						->prop('selected', $lunch_hour_from)
						->hour();
					?>
					<?php
					echo $pjTime_lmf
						->prop('selected', $lunch_minute_from)
						->minute();
					?>
					</td>
					<td>
					<?php
					echo $pjTime_lht
						->prop('selected', $lunch_hour_to)
						->hour();
					?>
					<?php
					echo $pjTime_lmt
						->prop('selected', $lunch_minute_to)
						->minute();
					?>
					</td>
					<td class="align_center"><input type="checkbox" class="working_day" name="<?php echo $k; ?>_dayoff" value="T"<?php echo $checked; ?> /></td>
				</tr>
				<?php
				$i++;
			}
			?>
			<?php /*if (!isset($_GET['foreign_id']) && !isset($_GET['type']) && $controller->isAdmin()) : ?>
				<tr>
					<td colspan="6"><label><input type="checkbox" name="update_all" value="1" /> <?php __('time_update_default'); ?></label></td>
				</tr>
			<?php endif;*/ ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6"><input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" /></td>
				</tr>
			</tfoot>
		</table>
	</form>
	<?php
}
?>

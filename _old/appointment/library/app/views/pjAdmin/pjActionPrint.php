<?php
if (!$tpl['t_arr'])
{
	pjUtil::printNotice(@$titles['AD02'], @$bodies['AD02'], true, false);
} else {
	# Fix for 24h support
	$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
	$step = $tpl['option_arr']['o_step'] * 60;
	?>
	<table class="pj-table" cellpadding="0" cellspacing="0" style="width: 100%">
		<thead>
			<tr>
				<th></th>
			<?php
			foreach ($tpl['employee_arr'] as $employee)
			{
				?><th><?php echo pjSanitize::html($employee['name']); ?></th><?php
			}
			?>
			</tr>
		</thead>
		<tbody>
		<?php
		for ($i = $tpl['t_arr']['start_ts']; $i <= $tpl['t_arr']['end_ts'] + $offset - $step; $i += $step)
		{
			?>
			<tr>
				<td><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></td>
				<?php
				foreach ($tpl['employee_arr'] as $employee)
				{
					$bookings = array();
					foreach ($tpl['bs_arr'] as $item)
					{
						if ($employee['id'] == $item['employee_id'] && $i >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60)
						{
							$bookings[] = $item;
						}
					}
					?><td><?php
					if (empty($bookings))
					{
						echo str_repeat('-', 3);
					} else {
						foreach ($bookings as $booking)
						{
							?>
							<div>
								<span class="bold"><?php echo pjSanitize::html($booking['c_name']); ?></span><br/>
								<span class="fs11"><?php echo pjSanitize::html($booking['service_name']); ?></span>
							</div>
							<?php
						}
					}
					?></td><?php
				}
				?>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
}
?>
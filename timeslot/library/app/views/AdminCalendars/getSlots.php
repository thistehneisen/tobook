<table cellpadding="0" cellspacing="0" class="tsbc-table">
	<thead>
		<tr>
			<th colspan="4"><?php echo date($tpl['option_arr']['date_format'], strtotime($_GET['date'])); ?></th>
		</tr>
		<?php
		if (!isset($tpl['dayoff']))
		{
			?>
					<tr>
						<th class="sub"><?php echo $TS_LANG['front']['cart']['start_time']; ?></th>
						<th class="sub"><?php echo $TS_LANG['front']['cart']['end_time']; ?></th>
						<th class="sub"><?php echo $TS_LANG['front']['cart']['price']; ?></th>
						<th class="sub"></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$step = $tpl['t_arr']['slot_length'] * 60;
				# Fix for 24h support
				$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
				
				$now = time();
				$n = 0;
				for ($i = $tpl['t_arr']['start_ts']; $i < $tpl['t_arr']['end_ts'] + $offset; $i = $i + $step)
				{
					?>
					<tr class="<?php echo ($n % 2 !== 0 ? 'even' : 'odd'); ?>">
						<td><?php echo date($tpl['option_arr']['time_format'], $i); ?></td>
						<td><?php echo date($tpl['option_arr']['time_format'], $i + $step); ?></td>
						<td><?php echo Util::formatCurrencySign(number_format(@$tpl['price_arr'][$i . "|" . ($i + $step)], 2, '.', ','), $tpl['option_arr']['currency']); ?></td>
						<td class="align_center">
						<?php
						$booked = array();
						foreach ($tpl['bs_arr'] as $bs)
						{
							if ($bs['start_ts'] == $i && $bs['end_ts'] == $i + $step)
							{
								$booked[] = $bs;
							}
						}
						if (count($booked) < $tpl['t_arr']['slot_limit'])
						{
							if ($i < $now)
							{
								echo $TS_LANG['front']['cart']['passed'];
							} else {
								echo $TS_LANG['cal_available'];
							}
						} else {
							# Fully booked
							foreach ($booked as $k => $v)
							{
								?>
								<span><?php echo $v['customer_name']; ?></span>
								<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=update&amp;id=<?php echo $v['booking_id']; ?>" title="<?php echo htmlspecialchars($TS_LANG['_edit']); ?>"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>icon-edit.png" alt="<?php echo htmlspecialchars($TS_LANG['_edit']); ?>" /></a>
								<a href="#" class="timeslot-delete" rev="<?php echo $v['id']; ?>|<?php echo $_GET['id']; ?>|<?php echo $_GET['date']; ?>" title="<?php echo htmlspecialchars($TS_LANG['cal_del_ts_title']); ?>"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>time_delete.png" alt="<?php echo htmlspecialchars($TS_LANG['cal_del_ts_title']); ?>" /></a>
								<a href="#" class="booking-delete" rev="<?php echo $v['booking_id']; ?>|<?php echo $_GET['id']; ?>|<?php echo $_GET['date']; ?>" title="<?php echo htmlspecialchars($TS_LANG['cal_del_title']); ?>"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>table_delete.png" alt="<?php echo htmlspecialchars($TS_LANG['cal_del_title']); ?>" /></a>
								<br />
								<?php
							}
						}
						?>
						</td>
					</tr>
					<?php
					$n++;
				}
				?>
				</tbody>
			<?php
		} else {
			# Date/day is off
			?>
				<tr>
					<th colspan="4"><?php echo $TS_LANG['booking_dayoff']; ?></th>
				</tr>
			</thead>
			<?php
		}
		?>
</table>
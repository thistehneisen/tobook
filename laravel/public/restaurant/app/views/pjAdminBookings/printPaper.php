<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($CR_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($CR_LANG['status'][2]);
			break;
	}
} else {
	if (count($tpl['arr']) > 0)
	{ 
		?>
		<h3><?php echo $_GET['date']; ?></h3>
		<?php if ( isset($tpl['arr_date']) && is_array($tpl['arr_date']) ) { ?>
		<div class="bookings_coming">
			<strong>Bookings coming</strong>
			<table cellpadding="0" cellspacing="0" class="table t10">
				<thead>
					<tr>
						<th class="sub w70"><?php echo $RB_LANG['booking_paper_time_start']; ?></th>
						<th class="sub"><?php echo $RB_LANG['booking_paper_people']; ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $tpl['arr_date'] as $arr_date ) { ?>
					<tr>
						<td class="w70"><?php echo date($tpl['option_arr']['time_format'], strtotime($arr_date['dt'])); ?></td>
						<td><?php echo $arr_date['people']; ?> <?php echo (int) $arr_date['people'] > 1 ? $RB_LANG['booking_paper_person_plural'] : $RB_LANG['booking_paper_person']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
		<table cellpadding="0" cellspacing="0" class="table t10">
			<thead>
				<tr>
					<th class="sub w70"><?php echo $RB_LANG['booking_paper_time_start']; ?></th>
					<th class="sub"><?php echo $RB_LANG['booking_paper_time_end']; ?></th>
					<th class="sub"><?php echo $RB_LANG['booking_paper_table']; ?></th>
					<th class="sub"><?php echo $RB_LANG['booking_paper_people']; ?></th>
					<th class="sub"><?php echo $RB_LANG['booking_paper_client']; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ($tpl['arr'] as $booking)
			{
				?>
				<tr>
					<td class="w70"><?php echo date($tpl['option_arr']['time_format'], strtotime($booking['dt'])); ?></td>
					<td><?php echo date($tpl['option_arr']['time_format'], strtotime($booking['dt_to'])); ?></td>
					<td>
					<?php
					$t_arr = array();
					foreach ($booking['table_arr'] as $table)
					{
						$t_arr[] = stripslashes($table['name']);
					}
					echo join(", ", $t_arr);
					?>
					</td>
					<td><?php echo (int) $booking['people']; ?> <?php echo (int) $booking['people'] > 1 ? $RB_LANG['booking_paper_person_plural'] : $RB_LANG['booking_paper_person']; ?></td>
					<td><?php echo stripslashes($booking['c_fname'] . " " . $booking['c_lname']); ?></td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<script type="text/javascript">
		if (window.print) {
			window.print();
		}
		</script>
		<?php
	}
}
?>
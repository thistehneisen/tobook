<?php
if (count($tpl['arr']) > 0)
{
	?>
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=printPaper&amp;date=<?php echo urlencode(isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date($tpl['option_arr']['date_format'])); ?>&amp;rbpf=<?php echo PREFIX; ?>" target="_blank"><?php echo $RB_LANG['booking_print']; ?></a>
	<table cellpadding="0" cellspacing="0" class="table t10">
		<thead>
			<tr>
				<th class="sub"><?php echo $RB_LANG['booking_paper_time_start']; ?></th>
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
				<td><?php echo date($tpl['option_arr']['time_format'], strtotime($booking['dt'])); ?></td>
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
	<?php
} else {
	pjUtil::printNotice($RB_LANG['booking_empty']);
}
?>
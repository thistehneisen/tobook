<p><span class="bold"><?php echo $RB_LANG['booking_dt']; ?>:</span>
<?php
$dt = strtotime(pjUtil::formatDate($_GET['date'], $tpl['option_arr']['date_format']) . " " . $_GET['hour']. ":" . $_GET['minute']. ":00");
echo date($tpl['option_arr']['datetime_format'], $dt);
?></p>
<?php
if (count($tpl['table_arr']) - count($tpl['bt_arr']) > 0)
{
	?>
	<table cellpadding="0" cellspacing="0" class="table t10">
		<thead>
			<tr>
				<th class="sub"><?php echo $RB_LANG['booking_paper_table']; ?></th>
				<th class="sub w100"><?php echo $RB_LANG['table_seats']; ?></th>
				<th class="sub w100"><?php echo $RB_LANG['table_minimum']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($tpl['table_arr'] as $table)
		{
			if (in_array($table['id'], $tpl['bt_arr']))
			{
				continue;
			}
			?>
			<tr>
				<td><?php echo stripslashes($table['name']); ?></td>
				<td><?php echo $table['seats']; ?></td>
				<td><?php echo $table['minimum']; ?></td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<?php
} else {
	pjUtil::printNotice($RB_LANG['table_empty']);
}
?>
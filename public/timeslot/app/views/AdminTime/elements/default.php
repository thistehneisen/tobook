<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminTime&amp;action=index" method="post" class="tsbc-form">
	<input type="hidden" name="working_time" value="1" />
	<table class="tsbc-table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th colspan="7"><?php echo $TS_LANG['time_title']; ?></th>
			</tr>
			<tr>
				<th class="sub"><?php echo $TS_LANG['time_day']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_from']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_to']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_price']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_length']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_limit']; ?></th>
				<th class="sub"><?php echo $TS_LANG['time_is']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		include_once VIEWS_PATH . 'Helpers/time.widget.php';
		$i = 1;
		foreach ($TS_LANG['days'] as $k => $day)
		{
			if (isset($tpl['arr']) && count($tpl['arr']) > 0)
			{
				$hour_from = substr($tpl['arr'][$k.'_from'], 0, 2);
				$hour_to = substr($tpl['arr'][$k.'_to'], 0, 2);
				$minute_from = substr($tpl['arr'][$k.'_from'], 3, 2);
				$minute_to = substr($tpl['arr'][$k.'_to'], 3, 2);
				$price = $tpl['arr'][$k.'_price'];
				$attr = array();
				$checked = NULL;
				$disabled = NULL;
				$day_price = NULL;
				if (is_null($tpl['arr'][$k.'_from']))
				{
					$attr['disabled'] = 'disabled';
					$checked = ' checked="checked"';
					$disabled = ' disabled="disabled"';
					$day_price = ' disabled';
				}
				$limit = $tpl['arr'][$k.'_limit'];
				$length = $tpl['arr'][$k.'_length'];
			} else {
				$hour_from = NULL;
				$hour_to = NULL;
				$minute_from = NULL;
				$minute_to = NULL;
				$price = NULL;
				$attr = array();
				$checked = NULL;
				$disabled = NULL;
				$limit = NULL;
				$length = NULL;
				$day_price = NULL;
			}
			$step = 5;
			?>
			<tr class="<?php echo ($i % 2 !== 0 ? 'odd' : 'even'); ?>">
				<td><?php echo $day; ?></td>
				<td><?php echo hourWidget($hour_from, $k . '_hour_from', $k . '_hour_from', 'select w50', $attr); ?> <?php echo minuteWidget($minute_from, $k . '_minute_from', $k . '_minute_from', 'select w50', $attr, $step); ?></td>
				<td><?php echo hourWidget($hour_to, $k . '_hour_to', $k . '_hour_to', 'select w50', $attr); ?> <?php echo minuteWidget($minute_to, $k . '_minute_to', $k . '_minute_to', 'select w50', $attr, $step); ?></td>
				<td>
					<input type="text" name="<?php echo $k; ?>_price" id="<?php echo $k; ?>_price" class="text w60 align_right" value="<?php echo $price; ?>"<?php echo $disabled; ?> />
					<a href="<?php echo $_SERVER['PHP_SELF']; ?>" rev="<?php echo $k; ?>" class="day-price<?php echo $day_price; ?>"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>time.png" alt="" /></a>
				</td>
				<td>
					<select name="<?php echo $k; ?>_length" id="<?php echo $k; ?>_length" class="select w60">
					<?php
					foreach ($TS_LANG['time_slot_length'] as $sk => $sv)
					{
						?><option value="<?php echo $sk; ?>"<?php echo $length == $sk ? ' selected="selected"' : NULL; ?>><?php echo $sv; ?></option><?php
					}
					?>
					</select>
				</td>
				<td>
					<select name="<?php echo $k; ?>_limit" id="<?php echo $k; ?>_limit" class="select w50"<?php echo $disabled; ?>>
					<?php
					foreach (range(1, 100) as $range)
					{
						?><option value="<?php echo $range; ?>"<?php echo $limit == $range ? ' selected="selected"' : NULL; ?>><?php echo $range; ?></option><?php
					}
					?>
					</select>
				</td>
				<td><input type="checkbox" class="working_day" name="<?php echo $k; ?>_dayoff" value="T"<?php echo $checked; ?> /></td>
			</tr>
			<?php
			$i++;
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7"><input type="submit" value="" class="button button_save" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<?php
if (!$controller->isAjax())
{
	?>
	<div id="dialogDayPrice" title="<?php echo htmlspecialchars($TS_LANG['time_dp_title']); ?>" style="display:none"></div>
	<?php
}
?>
<div id="record_id" style="display:none"></div>
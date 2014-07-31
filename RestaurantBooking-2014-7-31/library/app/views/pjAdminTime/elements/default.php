<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=index&amp;rbpf=<?php echo PREFIX; ?>" method="post" class="form">
	<input type="hidden" name="working_time" value="1" />
	<table class="table" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th colspan="7"><?php echo $RB_LANG['time_title']; ?></th>
			</tr>
			<tr>
				<th class="sub"><?php echo $RB_LANG['time_day']; ?></th>
				<th class="sub"><?php echo $RB_LANG['time_from']; ?></th>
				<th class="sub"><?php echo $RB_LANG['time_to']; ?></th>
				<th class="sub"><?php echo $RB_LANG['time_is']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
		$i = 1;
		foreach ($RB_LANG['days'] as $k => $day)
		{
			if (isset($tpl['arr']) && count($tpl['arr']) > 0)
			{
				$hour_from = substr($tpl['arr'][$k.'_from'], 0, 2);
				$hour_to = substr($tpl['arr'][$k.'_to'], 0, 2);
				$minute_from = substr($tpl['arr'][$k.'_from'], 3, 2);
				$minute_to = substr($tpl['arr'][$k.'_to'], 3, 2);
				$attr = array();
				$checked = NULL;
				$disabled = NULL;
				if (is_null($tpl['arr'][$k.'_from']))
				{
					$attr['disabled'] = 'disabled';
					$checked = ' checked="checked"';
					$disabled = ' disabled="disabled"';
				}
			} else {
				$hour_from = NULL;
				$hour_to = NULL;
				$minute_from = NULL;
				$minute_to = NULL;
				$attr = array();
				$checked = NULL;
				$disabled = NULL;
			}
			$step = 5;
			?>
			<tr class="<?php echo ($i % 2 !== 0 ? 'odd' : 'even'); ?>">
				<td><?php echo $day; ?></td>
				<td><?php echo TimeWidget::hour($hour_from, $k . '_hour_from', $k . '_hour_from', 'select', $attr, array('time_format' => $tpl['option_arr']['time_format'])); ?> <?php echo TimeWidget::minute($minute_from, $k . '_minute_from', $k . '_minute_from', 'select w50', $attr, $step); ?></td>
				<td><?php echo TimeWidget::hour($hour_to, $k . '_hour_to', $k . '_hour_to', 'select', $attr, array('time_format' => $tpl['option_arr']['time_format'])); ?> <?php echo TimeWidget::minute($minute_to, $k . '_minute_to', $k . '_minute_to', 'select w50', $attr, $step); ?></td>
				<td><input type="checkbox" class="working_day" name="<?php echo $k; ?>_dayoff" value="T"<?php echo $checked; ?> /></td>
			</tr>
			<?php
			$i++;
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4"><input type="submit" value="" class="button button_save" /></td>
			</tr>
		</tfoot>
	</table>
</form>
<?php
if (!$controller->isAjax())
{
	?>
	<div id="dialogDayPrice" title="<?php echo htmlspecialchars($RB_LANG['time_dp_title']); ?>" style="display:none"></div>
	<?php
}
?>
<div id="record_id" style="display:none"></div>
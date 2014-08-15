<?php
$t_arr = $tpl['t_arr'];
?>
<select name="single_time" class="asFormField asStretch asSelectorSingleTime">
<?php
if (!$t_arr)
{
	//Day off
} else {
	$service_arr = isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 ? $tpl['service_arr'] : @$tpl['service_arr']['data'][0];
	$step = $tpl['option_arr']['o_step'] * 60;
	$service_total = $service_arr['total'] * 60;
	$service_before = $service_arr['before'] * 60;
	# Fix for 24h support
	$offset = $t_arr['end_ts'] <= $t_arr['start_ts'] ? 86400 : 0;
	
	for ($i = $t_arr['start_ts']; $i <= $t_arr['end_ts'] + $offset - $step; $i += $step)
	{
		$is_free = true;
		if ($i < time())
		{
			$is_free = false;
		}
		# Not necessary cause every employee has a different lunch break
		/*if ($i >= $t_arr['lunch_start_ts'] && $i < $t_arr['lunch_end_ts'])
		{
			$is_free = false;
		}*/
		?><option value="<?php echo $i - $service_before; ?>|<?php echo $i + $service_total - $service_before; ?>"<?php echo $is_free ? NULL : ' disabled="disabled"'; ?>><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></option>
		<?php
	}
}
?>
</select>
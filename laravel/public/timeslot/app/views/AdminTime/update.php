<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminTime
 */
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			Util::printNotice($TS_LANG['status'][1]);
			break;
		case 2:
			Util::printNotice($TS_LANG['status'][2]);
			break;
	}
} else {
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $TS_LANG['time_update']; ?></a></li>
		</ul>
		<div id="tabs-1">
			<?php include_once VIEWS_PATH . 'Helpers/time.widget.php'; ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminTime&amp;action=index" method="post" class="tsbc-form" id="frmTimeCustom">
				<input type="hidden" name="custom_time" value="1" />
				<p>
					<label class="title"><?php echo $TS_LANG['time_date']; ?></label>
					<input type="text" name="date" id="date" class="text w100 pointer required pps" readonly="readonly" value="<?php echo $tpl['arr']['date']; ?>" />
				</p>
				<p>
					<label class="title"><?php echo $TS_LANG['time_slot']; ?></label>
					<select name="slot_length" id="slot_length" class="select w100 pps">
					<?php
					$slot_length = array(10 => '10 minutes', 15 => '15 minutes', 30 => '30 minutes', 60 => '1 hour', 120 => '2 hours');
					foreach ($slot_length as $k => $v)
					{
						?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['slot_length'] == $k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
					}
					?>
					</select>
				</p>
				<p>
					<label class="title"><?php echo $TS_LANG['time_limit']; ?></label>
					<select name="slot_limit" id="slot_limit" class="select w50">
					<?php
					foreach (range(1, 100) as $range)
					{
						?><option value="<?php echo $range; ?>"<?php echo $tpl['arr']['slot_limit'] == $range ? ' selected="selected"' : NULL;?>><?php echo $range; ?></option><?php
					}
					?>
					</select>
				</p>
				<?php 
				list($sh, $sm,) = explode(":", $tpl['arr']['start_time']);
				list($eh, $em,) = explode(":", $tpl['arr']['end_time']);
				?>
				<p>
					<label class="title"><?php echo $TS_LANG['time_from']; ?></label>
					<?php hourWidget((int) $sh, 'start_hour', 'start_hour', 'select w50 pps'); ?>
					<?php minuteWidget((int) $sm, 'start_minute', 'start_minute', 'select w50 pps'); ?>
				</p>
				<p>
					<label class="title"><?php echo $TS_LANG['time_to']; ?></label>
					<?php hourWidget((int) $eh, 'end_hour', 'end_hour', 'select w50 pps'); ?>
					<?php minuteWidget((int) $em, 'end_minute', 'end_minute', 'select w50 pps'); ?>
				</p>
				<p>
					<label class="title"><?php echo $TS_LANG['time_price']; ?></label>
					<input type="text" name="price" id="price" class="text w100 align_right number" value="<?php echo floatval($tpl['arr']['price']); ?>" />
					<input type="checkbox" name="single_price" id="single_price" value="1"<?php echo count($tpl['price_arr']) > 0 ? NULL : ' checked="checked"'; ?> class="pps" /> <label for="single_price"><?php echo $TS_LANG['time_single_price']; ?></label>
				</p>
				<p>
					<label class="title"><?php echo $TS_LANG['time_is']; ?></label>
					<span class="left"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T"<?php echo $tpl['arr']['is_dayoff'] == 'T' ? ' checked="checked"' : NULL; ?> /></span>
				</p>
				<div id="boxPPS" class="b10">
				<?php 
				if (count($tpl['price_arr']) > 0)
				{
					?>
					<table cellpadding="0" cellspacing="0" class="tsbc-table">
						<thead>
							<tr>
								<th class="sub"><?php echo $TS_LANG['time_from']; ?></th>
								<th class="sub"><?php echo $TS_LANG['time_to']; ?></th>
								<th class="sub"><?php echo $TS_LANG['time_price']; ?></th>
							</tr>
						</thead>
						<tbody>
						<?php
						$step = $tpl['arr']['slot_length'] * 60;
						$start_ts = strtotime($tpl['arr']['date'] . " " . $tpl['arr']['start_time']);
						$end_ts = strtotime($tpl['arr']['date'] . " " . $tpl['arr']['end_time']);
						
						# Fix for 24h support
						$offset = $end_ts <= $start_ts ? 86400 : 0;
							
						for ($i = $start_ts; $i < $end_ts + $offset; $i = $i + $step)
						{
							$value = NULL;
							foreach ($tpl['price_arr'] as $v)
							{
								if ($v['start_ts'] == $i && $v['end_ts'] == $i + $step)
								{
									$value = $v['price'];
									break;
								}
							}
							?>
							<tr>
								<td><?php echo date($tpl['option_arr']['time_format'], $i); ?></td>
								<td><?php echo date($tpl['option_arr']['time_format'], $i + $step); ?></td>
								<td><input type="text" name="price[<?php echo $i; ?>|<?php echo $i + $step; ?>]" id="price_<?php echo $i; ?>" class="text w80 align_right" value="<?php echo $value; ?>" /> <?php echo $tpl['option_arr']['currency']; ?></td>
							</tr>
							<?php
						}
						?>
						</tbody>
					</table>
					<?php
				} 
				?>
				</div>
				<p>
					<input type="submit" value="" class="button button_save"  />
				</p>
			</form>
		
		</div>
	</div>
	<?php
}
?>
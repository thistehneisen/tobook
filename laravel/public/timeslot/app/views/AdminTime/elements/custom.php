<?php
include_once VIEWS_PATH . 'Helpers/time.widget.php';
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminTime&amp;action=index" method="post" class="tsbc-form" id="frmTimeCustom">
	<input type="hidden" name="custom_time" value="1" />
	<p>
		<label class="title"><?php echo $TS_LANG['time_date']; ?></label>
		<input type="text" name="date" id="date" class="text w100 pointer required pps" readonly="readonly" />
	</p>
	<p>
		<label class="title"><?php echo $TS_LANG['time_slot']; ?></label>
		<select name="slot_length" id="slot_length" class="select w100 pps">
		<?php
		foreach ($TS_LANG['time_slot_length_labels'] as $k => $v)
		{
			?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
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
			?><option value="<?php echo $range; ?>"><?php echo $range; ?></option><?php
		}
		?>
		</select>
	</p>
	<p>
		<label class="title"><?php echo $TS_LANG['time_from']; ?></label>
		<?php hourWidget(null, 'start_hour', 'start_hour', 'select w50 pps'); ?>
		<?php minuteWidget(null, 'start_minute', 'start_minute', 'select w50 pps'); ?>
	</p>
	<p>
		<label class="title"><?php echo $TS_LANG['time_to']; ?></label>
		<?php hourWidget(null, 'end_hour', 'end_hour', 'select w50 pps'); ?>
		<?php minuteWidget(null, 'end_minute', 'end_minute', 'select w50 pps'); ?>
	</p>
	<p>
		<label class="title"><?php echo $TS_LANG['time_price']; ?></label>
		<input type="text" name="price" id="price" class="text w100 align_right number" />
		<input type="checkbox" name="single_price" id="single_price" value="1" checked="checked" class="pps" /> <label for="single_price"><?php echo $TS_LANG['time_single_price']; ?></label>
	</p>
	<p>
		<label class="title"><?php echo $TS_LANG['time_is']; ?></label>
		<span class="left"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T" /></span>
	</p>
	<p id="boxPPS">
	
	</p>
	<p>
		<input type="submit" value="" class="button button_save"  />
	</p>
</form>

<?php
if (isset($tpl['date_arr']))
{
	if (is_array($tpl['date_arr']))
	{
		$count = count($tpl['date_arr']);
		if ($count > 0)
		{
			?>
			<table class="tsbc-table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th class="sub"><?php echo $TS_LANG['time_date']; ?></th>
						<th class="sub"><?php echo $TS_LANG['time_slot']; ?></th>
						<th class="sub"><?php echo $TS_LANG['time_limit']; ?></th>
						<th class="sub"><?php echo $TS_LANG['time_from']; ?></th>
						<th class="sub"><?php echo $TS_LANG['time_to']; ?></th>
						<th class="sub"><?php echo $TS_LANG['time_price']; ?></th>
						<th class="sub"><?php echo $TS_LANG['time_is']; ?></th>
						<th class="sub" style="width: 8%"></th>
						<th class="sub" style="width: 8%"></th>
					</tr>
				</thead>
				<tbody>
			<?php
			for ($i = 0; $i < $count; $i++)
			{
				?>
				<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
					<td><?php echo date($tpl['option_arr']['date_format'], strtotime($tpl['date_arr'][$i]['date'])); ?></td>
					<td><?php echo $tpl['date_arr'][$i]['slot_length']; ?></td>
					<td><?php echo $tpl['date_arr'][$i]['slot_limit']; ?></td>
					<td><?php echo date($tpl['option_arr']['time_format'], strtotime($tpl['date_arr'][$i]['start_time'])); ?></td>
					<td><?php echo date($tpl['option_arr']['time_format'], strtotime($tpl['date_arr'][$i]['end_time'])); ?></td>
					<td><?php echo empty($tpl['date_arr'][$i]['price']) ? 'n/a' : Util::formatCurrencySign($tpl['date_arr'][$i]['price'], $tpl['option_arr']['currency']); ?></td>
					<td class="align_center"><?php echo @$TS_LANG['_yesno'][$tpl['date_arr'][$i]['is_dayoff']]; ?></td>
					<td><a class="icon icon-edit" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=AdminTime&amp;action=update&amp;id=<?php echo $tpl['date_arr'][$i]['id']; ?>"><?php echo $TS_LANG['_edit']; ?></a></td>
					<td><a class="icon icon-delete" rev="<?php echo $tpl['date_arr'][$i]['id']; ?>" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=AdminTime&amp;action=delete&amp;id=<?php echo $tpl['date_arr'][$i]['id']; ?>"><?php echo $TS_LANG['_delete']; ?></a></td>
				</tr>
				<?php
			}
			?>
				</tbody>
			</table>
			<?php
			if (!$controller->isAjax())
			{
				?>
				<div id="dialogDelete" title="<?php echo htmlspecialchars($TS_LANG['time_del_title']); ?>" style="display:none">
					<p><?php echo $TS_LANG['time_del_body']; ?></p>
				</div>
				<?php
			}

			if (isset($tpl['paginator']))
			{
				?>
				<ul class="tsbc-paginator">
				<?php
				for ($i = 1; $i <= $tpl['paginator']['pages']; $i++)
				{
					if ((isset($_GET['page']) && (int) $_GET['page'] == $i) || (!isset($_GET['page']) && $i == 1))
					{
						?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>&amp;tab_id=tabs-2" class="focus"><?php echo $i; ?></a></li><?php
					} else {
						?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>&amp;tab_id=tabs-2"><?php echo $i; ?></a></li><?php
					}
				}
				?>
				</ul>
				<?php
			}
			
		} else {
			Util::printNotice($TS_LANG['time_empty']);
		}
	}
}
if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
{
	$tab_id = explode("-", $_GET['tab_id']);
	$tab_id = (int) $tab_id[1] - 1;
	//$tab_id = (int) $_GET['tab_id'] - 1;
	$tab_id = $tab_id < 0 ? 0 : $tab_id;
	?>
	<script type="text/javascript">
	(function ($) {
		$(function () {
			$("#tabs").tabs("option", "selected", <?php echo $tab_id; ?>);
		});
	})(jQuery);
	</script>
	<?php
}
?>
<?php
include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=index" method="post" class="form" id="frmTimeCustom">
	<input type="hidden" name="custom_time" value="1" />
	<p>
		<label class="title"><?php echo $RB_LANG['time_date']; ?></label>
		<input type="text" name="date" id="date" class="text w100 pointer required pps" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
	</p>
	<p>
		<label class="title"><?php echo $RB_LANG['time_from']; ?></label>
		<?php TimeWidget::hour(9, 'start_hour', 'start_hour', 'select pps', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
		<?php TimeWidget::minute(null, 'start_minute', 'start_minute', 'select w50 pps'); ?>
	</p>
	<p>
		<label class="title"><?php echo $RB_LANG['time_to']; ?></label>
		<?php TimeWidget::hour(23, 'end_hour', 'end_hour', 'select pps', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
		<?php TimeWidget::minute(null, 'end_minute', 'end_minute', 'select w50 pps'); ?>
	</p>
	<p>
		<label class="title"><?php echo $RB_LANG['time_is']; ?></label>
		<span class="left"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T" /></span>
	</p>
	<p>
		<label class="title"><?php echo $RB_LANG['time_message']; ?></label>
		<span class="left"><textarea class="textarea" rows="5" cols="50" name="message"></textarea></span>
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
			<table class="table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th class="sub"><?php echo $RB_LANG['time_date']; ?></th>
						<th class="sub"><?php echo $RB_LANG['time_from']; ?></th>
						<th class="sub"><?php echo $RB_LANG['time_to']; ?></th>
						<th class="sub"><?php echo $RB_LANG['time_is']; ?></th>
						<th class="sub"><?php echo $RB_LANG['time_message']; ?></th>
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
					<td><?php echo date($tpl['option_arr']['time_format'], strtotime($tpl['date_arr'][$i]['start_time'])); ?></td>
					<td><?php echo date($tpl['option_arr']['time_format'], strtotime($tpl['date_arr'][$i]['end_time'])); ?></td>
					<td class="align_center"><?php echo @$RB_LANG['_yesno'][$tpl['date_arr'][$i]['is_dayoff']]; ?></td>
					<td class="message"><?php echo $tpl['date_arr'][$i]['message']; ?></td>
					<td><a class="icon icon-edit" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=update&amp;id=<?php echo $tpl['date_arr'][$i]['id']; ?>"><?php echo $RB_LANG['_edit']; ?></a></td>
					<td><a class="custom-delete icon icon-delete" rel="<?php echo $tpl['date_arr'][$i]['id']; ?>" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=delete&amp;id=<?php echo $tpl['date_arr'][$i]['id']; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
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
				<div id="dialogDelete" title="<?php echo htmlspecialchars($RB_LANG['time_del_title']); ?>" style="display:none">
					<p><?php echo $RB_LANG['time_del_body']; ?></p>
				</div>
				<?php
			}

			if (isset($tpl['paginator']))
			{
				?>
				<ul class="paginator">
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
			pjUtil::printNotice($RB_LANG['time_empty']);
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
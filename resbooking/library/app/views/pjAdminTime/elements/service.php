<?php
include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=index" method="post" class="form" id="frmTimeService">
	<input type="hidden" name="service_time" value="1" />
	<p>
		<label class="title"><?php echo $RB_LANG['service_name']; ?></label>
		<input type="text" name="s_name" class="text w150 pointer required pps"  />
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
		<label class="title"><?php echo $RB_LANG['service_length']; ?></label>
		<input type="text" name="s_length" class="text w100 pointer required pps"  />
	</p>
	<p>
		<label class="title">Price (<?php echo $tpl['option_arr']['currency']; ?>)</label>
		<input type="text" name="s_price" class="text w100 pointer required pps"  />
	</p>
	<p style="display: none;">
		<label class="title"><?php echo $RB_LANG['table_seats']; ?></label>
		<input type="text" name="s_seats" class="text w100 pointer required pps"  />
	</p>
	<p>
	
	</p>
	<p>
		<input type="submit" value="" class="button button_save"  />
	</p>
</form>

<?php
if (isset($tpl['service_arr']))
{
	if (is_array($tpl['service_arr']))
	{
		$count = count($tpl['service_arr']);
		if ($count > 0)
		{
			?>
			<table class="table" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th class="sub"><?php echo $RB_LANG['service_name']; ?></th>
						<th class="sub"><?php echo $RB_LANG['time_from']; ?></th>
						<th class="sub"><?php echo $RB_LANG['time_to']; ?></th>
						<th class="sub"><?php echo $RB_LANG['service_length']; ?></th>
						<th class="sub">Price (<?php echo $tpl['option_arr']['currency']; ?>)</th>
						<th class="sub" style="display: none; "><?php echo $RB_LANG['table_seats']; ?></th>
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
					<td><?php echo $tpl['service_arr'][$i]['s_name']; ?></td>
					<td><?php echo date($tpl['option_arr']['time_format'], strtotime($tpl['service_arr'][$i]['start_time'])); ?></td>
					<td><?php echo date($tpl['option_arr']['time_format'], strtotime($tpl['service_arr'][$i]['end_time'])); ?></td>
					<td><?php echo $tpl['service_arr'][$i]['s_length']; ?></td>
					<td><?php echo $tpl['service_arr'][$i]['s_price']; ?></td>
					<td style="display: none; "><?php echo $tpl['service_arr'][$i]['s_seats']; ?></td>
					<td><a class="icon icon-edit" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=supdate&amp;id=<?php echo $tpl['service_arr'][$i]['id']; ?>"><?php echo $RB_LANG['_edit']; ?></a></td>
					<td><a class="service-delete icon icon-delete" rel="<?php echo $tpl['service_arr'][$i]['id']; ?>" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=sdelete&amp;id=<?php echo $tpl['service_arr'][$i]['id']; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
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
				<div id="dialogSDelete" title="<?php echo htmlspecialchars($RB_LANG['service_del_title']); ?>" style="display:none">
					<p><?php echo $RB_LANG['time_del_body']; ?></p>
				</div>
				<?php
			}

			if (isset($tpl['spaginator']))
			{
				?>
				<ul class="paginator">
				<?php
				for ($i = 1; $i <= $tpl['spaginator']['pages']; $i++)
				{
					if ((isset($_GET['spage']) && (int) $_GET['spage'] == $i) || (!isset($_GET['spage']) && $i == 1))
					{
						?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;spage=<?php echo $i; ?>&amp;tab_id=tabs-2" class="focus"><?php echo $i; ?></a></li><?php
					} else {
						?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;spage=<?php echo $i; ?>&amp;tab_id=tabs-2"><?php echo $i; ?></a></li><?php
					}
				}
				?>
				</ul>
				<?php
			}
			
		} else {
			pjUtil::printNotice($RB_LANG['service_empty']);
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
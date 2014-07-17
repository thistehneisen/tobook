<?php
include VIEWS_PATH . 'pjLayouts/elements/menu_restaurant.php';
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($RB_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
	}
} else {
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $RB_LANG['time_update']; ?></a></li>
		</ul>
		<div id="tabs-1">
			<?php include_once VIEWS_PATH . 'pjHelpers/time.widget.php'; ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=index" method="post" class="form" id="frmTimeService">
				<input type="hidden" name="service_time" value="1" />
				<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
				<p>
					<label class="title"><?php echo $RB_LANG['service_name']; ?></label>
					<input type="text" name="s_name" class="text w150 pointer required pps" value="<?php echo $tpl['arr']['s_name']; ?>" />
				</p>
				<?php
				list($sh, $sm,) = explode(":", $tpl['arr']['start_time']);
				list($eh, $em,) = explode(":", $tpl['arr']['end_time']);
				?>
				<p>
					<label class="title"><?php echo $RB_LANG['time_from']; ?></label>
					<?php TimeWidget::hour((int) $sh, 'start_hour', 'start_hour', 'select pps', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
					<?php TimeWidget::minute((int) $sm, 'start_minute', 'start_minute', 'select w50 pps'); ?>
				</p>
				<p>
					<label class="title"><?php echo $RB_LANG['time_to']; ?></label>
					<?php TimeWidget::hour((int) $eh, 'end_hour', 'end_hour', 'select pps', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
					<?php TimeWidget::minute((int) $em, 'end_minute', 'end_minute', 'select w50 pps'); ?>
				</p>
				<p>
					<label class="title"><?php echo $RB_LANG['service_length']; ?></label>
					<input type="text" name="s_length" class="text w100 pointer required pps" value="<?php echo $tpl['arr']['s_length']; ?>" />
				</p>
				<p>
					<label class="title">Price (<?php echo $tpl['option_arr']['currency']; ?>)</label>
					<input type="text" name="s_price" class="text w100 pointer required pps"  />
				</p>
				<p style="display: none; ">
					<label class="title"><?php echo $RB_LANG['table_seats']; ?></label>
					<input type="text" name="s_seats" class="text w100 pointer required pps" value="<?php echo $tpl['arr']['s_seats']; ?>" />
				</p>
				<p>
					<input type="submit" value="" class="button button_save"  />
				</p>
			</form>
		
		</div>
	</div>
	<?php
}
?>
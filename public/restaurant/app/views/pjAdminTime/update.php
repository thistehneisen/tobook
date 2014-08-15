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
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=index&amp;rbpf=<?php echo PREFIX; ?>" method="post" class="form" id="frmTimeCustom">
				<input type="hidden" name="custom_time" value="1" />
				<p>
					<label class="title"><?php echo $RB_LANG['time_date']; ?></label>
					<input type="text" name="date" id="dateEditCustom" class="text w100 pointer required pps" readonly="readonly" value="<?php echo pjUtil::formatDate($tpl['arr']['date'], 'Y-m-d', $tpl['option_arr']['date_format']); ?>" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
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
					<label class="title"><?php echo $RB_LANG['time_is']; ?></label>
					<span class="left"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T"<?php echo $tpl['arr']['is_dayoff'] == 'T' ? ' checked="checked"' : NULL; ?> /></span>
				</p>
				<p>
					<label class="title"><?php echo $RB_LANG['time_message']; ?></label>
					<span class="left"><textarea class="textarea" rows="5" cols="50" name="message"><?php echo $tpl['arr']['message']; ?></textarea></span>
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
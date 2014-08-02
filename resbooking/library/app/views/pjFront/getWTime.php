<?php include VIEWS_PATH . 'pjHelpers/time.widget.php'; ?>
<label class="rbLabel"><?php echo $RB_LANG['front']['1_hour']; ?></label>
<?php
# Fix 24h support
$offset = $tpl['wt_arr']['end_hour'] <= $tpl['wt_arr']['start_hour'] ? 24 : 0;
				
$hf = isset($STORAGE) && isset($STORAGE['hour']) ? $STORAGE['hour'] : 9;
$mf = isset($STORAGE) && isset($STORAGE['minutes']) ? $STORAGE['minutes'] : null;
$booking_length = ceil((int) $tpl['option_arr']['booking_length'] / 60);
$start = (int) $tpl['wt_arr']['start_hour'];
$end = (int) $tpl['wt_arr']['end_hour'] - $booking_length + $offset;
if ($end < $start)
{
	$end = $start;
}
?>
<?php TimeWidget::hour($hf, 'hour', 'rb_hour', 'rbSelect rbMr10', array(), array('start' => $start, 'end' => $end, 'time_format' => $tpl['option_arr']['time_format'])); ?>

<?php TimeWidget::minute($mf, 'minutes', 'rb_minutes', 'rbSelect', array(), 5); ?>
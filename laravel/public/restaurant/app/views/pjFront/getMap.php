<img id="RBookingMapImage_<?php echo $_GET['index']; ?>" src="<?php echo INSTALL_URL . UPLOAD_PATH; ?>maps/map.jpg" alt="" style="margin: 0; border: none; position: absolute; top: 0; left: 0; z-index: 500" />
<?php
foreach ($tpl['table_arr'] as $table)
{
	?><span data-price="<?php echo pjUtil::formatCurrencySign(number_format($tpl['option_arr']['booking_price'], 0), $tpl['option_arr']['currency']); ?>"
		data-table_id="<?php echo $table['id']; ?>" data-seats="<?php echo $table['seats']; ?>" data-minimum="<?php echo $table['minimum']; ?>" class="sbook-rect sbook-<?php echo (int) $table['booked'] !== 0 || (int) $_GET['people'] > $table['seats'] || (int) $_GET['people'] < $table['minimum'] ? 'busy' : 'available'; ?>"
		style="width: <?php echo $table['width']; ?>px; height: <?php echo $table['height']; ?>px; left: <?php echo $table['left']; ?>px; top: <?php echo $table['top']; ?>px; line-height: <?php echo $table['height']; ?>px"><?php echo stripslashes($table['name']); ?></span><?php
}
?>
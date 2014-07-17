<?php 
if (count($tpl['booking_arr'] > 0)) { ?>
		<table class="pj-table" style="width: 100%;">
			<thead>
				<tr>
					<th></th>
					<th><?php __('booking_name', false, true); ?></th>
					<th><?php __('booking_phone', false, true); ?></th>
					<th><?php __('booking_email', false, true); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$i = 0;
			foreach ($tpl['booking_arr'] as $booking) { 
				$i++;
				$class="trCustomer-" . $i;
				if ( $i%2 == 0 ) {
					$tr_class = "pj-table-row-even";
				} else {
					$tr_class = "pj-table-row-odd";
				}
			?>
				<tr class="<?php echo $tr_class . ' ' . $class; ?>">
					<td><input type="radio" name="radio_customer" value="<?php echo $class; ?>"></td>
					<td><span class="customerName"><?php echo $booking['c_name']; ?></span></td>
					<td><span class="customerPhone"><?php echo $booking['c_phone']; ?></span></td>
					<td><span class="customerEmail"><?php echo $booking['c_email']; ?></span></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
	
	<?php
} else {
	?><span class="left"><?php __('booking_i_empty'); ?></span><?php
}
?>
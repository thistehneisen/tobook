<?php
if (isset($tpl['status']) && $tpl['status'] == "OK")
{
	?>
	<div class="asBox">
		<div class="asServicesInner">
			<div class="asHeading"><?php __('front_system_msg'); ?></div>
			<div class="asSelectorElements asOverflowHidden">
			<?php
			$status = __('front_booking_status', true);
			if (isset($tpl['booking_arr']))
			{
				switch ($tpl['booking_arr']['payment_method'])
				{
					case 'paypal':
						switch ($tpl['invoice_arr']['status'])
						{
							case 'not_paid':
						
								?><div class="asElement asElementOutline"><?php echo $status[11]; ?></div>
								<div class="asElementOutline"><?php
								if (pjObject::getPlugin('pjPaypal') !== NULL)
								{
									$controller->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionForm', 'params' => $tpl['params']));
								}
								?></div><?php
								break;
							case 'cancelled':
								?><div class="asElement asElementOutline"><?php echo $status[5]; ?></div><?php
								break;
							default:
								?><div class="asElement asElementOutline"><?php echo $status[3]; ?></div><?php
								break;
						}
						break;
					case 'authorize':
						switch ($tpl['invoice_arr']['status'])
						{
							case 'not_paid':
								?><div class="asElement asElementOutline"><?php echo $status[11]; ?></div>
								<div class="asElementOutline"><?php
								if (pjObject::getPlugin('pjAuthorize') !== NULL)
								{
									$controller->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionForm', 'params' => $tpl['params']));
								}
								?></div><?php
								break;
							case 'cancelled':
								?><div class="asElement asElementOutline"><?php echo $status[5]; ?></div><?php
								break;
							default:
								?><div class="asElement asElementOutline"><?php echo $status[3]; ?></div><?php
								break;
						}
						break;
					case 'bank':
						?>
						<div class="asElement asElementOutline"><?php echo $status[1]; ?></div>
						<div class="asElementOutline"><?php echo pjSanitize::html(nl2br($tpl['option_arr']['o_bank_account'])); ?></div>
						<?php
						break;
					case 'creditcard':
					case 'none':
					default:
						?><div class="asElement asElementOutline"><?php echo $status[1]; ?></div><?php
				}
			} else {
				?><div class="asElement asElementOutline"><?php echo $status[4]; ?></div><?php
			}
			?>
			</div>
		</div>
	</div>
	<?php
} elseif (isset($tpl['status']) && $tpl['status'] == 'ERR') {
	?>
	<div class="asBox">
		<div class="asServicesInner">
			<div class="asHeading"><?php __('front_system_msg'); ?></div>
			<div class="asSelectorElements asOverflowHidden">
				<div class="asElement asElementOutline"><?php __('front_booking_na'); ?></div>
				<div class="asElementOutline">
					<input type="button" value="<?php __('front_return_back', false, true); ?>" class="asSelectorButton asSelectorPreview asButton asButtonGray" />
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
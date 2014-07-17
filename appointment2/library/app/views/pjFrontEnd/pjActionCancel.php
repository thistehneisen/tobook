<!doctype html>
<html>
	<head>
		<title><?php __('cancel_title'); ?></title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->getCss() as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		$cid = isset($_GET['cid']) && (int) $_GET['cid'] > 0 ? (int) $_GET['cid'] : 1;
		?>
	</head>
	<body>
		<div class="asContainer">
			<div id="asContainer_<?php echo $cid; ?>" class="asContainerInner">
			
			<?php
			global $as_pf;
			$cancel_err = __('cancel_err', true);
			if (isset($tpl['status']))
			{
				?>
				<div class="asBox">
					<div class="asServicesInner">
						<div class="asHeading"><?php __('front_system_msg'); ?></div>
						<div class="asSelectorElements asOverflowHidden">
							<div class="asElement asElementOutline">
							<?php
							switch ($tpl['status'])
							{
								case 1:
									echo $cancel_err[1];
									break;
								case 2:
									echo $cancel_err[2];
									break;
								case 3:
									echo $cancel_err[3];
									break;
								case 4:
									echo $cancel_err[4];
									break;
							}
							?>
							</div>
						</div>
					</div>
				</div>
				<?php
			} else {

				if (isset($_GET['err']))
				{
					?>
					<div class="asBox">
						<div class="asServicesInner">
							<div class="asHeading"><?php __('front_system_msg'); ?></div>
							<div class="asSelectorElements asOverflowHidden">
								<div class="asElement asElementOutline">
								<?php
								switch ((int) $_GET['err'])
								{
									case 5:
										echo $cancel_err[5];
										break;
								}
								?>
								</div>
							</div>
						</div>
					</div>
					<?php
				}

				if (isset($tpl['arr']))
				{
					?>
					<div class="asBox asCartOuter">
						<div class="asCartInner asCartInnerPreview">
							<div class="asHeading"><?php __('cancel_services'); ?></div>
							<div class="asSelectorCartWrap asOverflowHidden">
								<?php
								$hidePrices = (int) $tpl['option_arr']['o_hide_prices'] === 1;
								foreach ($tpl['arr']['details_arr'] as $v)
								{
									$date = date($tpl['option_arr']['o_date_format'], strtotime($v['date']));
									$price = pjUtil::formatCurrencySign(number_format($v['price'], 2), $tpl['option_arr']['o_currency']);
									$from = date($tpl['option_arr']['o_time_format'], $v['start_ts'] + $v['before'] * 60);
									$to = date($tpl['option_arr']['o_time_format'], $v['start_ts'] + $v['before'] * 60 + $v['length'] * 60);
									?>
									<div class="asElement asElementOutline">
										<div class="asCartService"><?php echo pjSanitize::html($v['service_name']); ?> | <?php echo pjSanitize::html($v['employee_name']); ?></div>
										<div class="asCartInfo">
											<div class="asCartDate<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo $date; ?></div>
											<div class="asCartStart<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo $from; ?></div>
											<div class="asCartEnd<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo $to; ?></div>
											<?php if (!$hidePrices) : ?>
											<div class="asCartPrice"><?php echo $price; ?></div>
											<?php endif; ?>
										</div>
										<div class="asCartInfo2">
											<div class="asCartDate<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo $date; ?></div>
											<?php if (!$hidePrices) : ?>
											<div class="asCartPrice"><?php echo $price; ?></div>
											<?php endif; ?>
											<div class="asCartTime"><?php __('front_from'); ?> <?php echo $from; ?> <?php __('front_till'); ?> <?php echo $to; ?></div>
										</div>
									</div>
									<?php
								}
								?>
								<?php if (!$hidePrices) : ?>
								<div class="asElement asElementOutline">
									<div class="">
										<div class="asCartTotal"><?php __('front_cart_total'); ?>:</div>
										<div class="asCartTotalPrice"><?php echo pjUtil::formatCurrencySign(number_format($tpl['arr']['booking_total'], 2), $tpl['option_arr']['o_currency'])?></div>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					
					<div class="asBox asServicesOuter">
						<div class="asServicesInner">
							<div class="asHeading"><?php __('cancel_details'); ?></div>
							<div class="asSelectorElements asOverflowHidden">
								<div class="asElement asElementOutline">
								
									<div class="asRow">
										<label class="asLabel"><?php __('booking_name'); ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_name']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_email'); ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_email']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_phone'); ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_phone']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_country'); ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['country_title']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_city'); ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_city']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_state'); ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_state']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_zip'); ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_zip']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_address_1');; ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_address_1']); ?></span>
									</div>
									<div class="asRow">
										<label class="asLabel"><?php __('booking_address_2');; ?></label>
										<span class="asValue"><?php echo stripslashes($tpl['arr']['c_address_2']); ?></span>
									</div>
									
								</div>
								<div class="asElementOutline">
									<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjFrontEnd&amp;action=pjActionCancel&amp;as_pf=<?php echo $as_pf; ?>" method="post">
										<input type="hidden" name="booking_cancel" value="1" />
										<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
										<input type="hidden" name="hash" value="<?php echo $_GET['hash']; ?>" />
										<input type="submit" value="<?php __('cancel_confirm', false, true); ?>" class="asButton asButtonGreen" />
									</form>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
			?>
			</div>
		</div>
		
	</body>
</html>
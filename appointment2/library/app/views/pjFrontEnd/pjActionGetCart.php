<?php
$CART = @$controller->cart->getAll();
if (!empty($CART) && isset($tpl['cart_arr']) && !empty($tpl['cart_arr']))
{
	$hidePrices = (int) $tpl['option_arr']['o_hide_prices'] === 1;
	$total = 0;
	$months = __('months', true);
	$suffix = __('front_day_suffix', true);
	
	foreach ($CART as $key => $whatever)
	{   
		list($cid, $date, $service_id, $start_ts, $end_ts, $employee_id, $wt_id) = explode("|", $key);
		
		$extra = array('length' => 0, 'price' => 0);
		if ( is_array($whatever) && count($whatever) > 0 ) {
			foreach ( $whatever as $_extra) {
				$extra['length'] += $_extra['length'];
				$extra['price'] += $_extra['price'];
			}
		}
		
		$fixed_start_ts = $start_ts + @$tpl['cart_arr'][$service_id]['before'] * 60;
		$fixed_end_ts = $end_ts - @$tpl['cart_arr'][$service_id]['after'] * 60;
		
		$total += (float) @$tpl['cart_arr'][$service_id]['price'] + $extra['price'];
		?>
		<div class="asElement asElementOutline">
			<div class="asCartService asLayout1"><?php echo pjSanitize::html(@$tpl['cart_arr'][$service_id]['name']); ?></div>
			<div class="asCartInfo asLayout1">
				<div class="asCartDate<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo pjUtil::formatDate($date, 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></div>
				<div class="asCartStart<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo date($tpl['option_arr']['o_time_format'], $fixed_start_ts); ?></div>
				<div class="asCartEnd<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo date($tpl['option_arr']['o_time_format'], $fixed_end_ts); ?></div>
				<?php if (!$hidePrices) : ?>
				<div class="asCartPrice"><?php echo pjUtil::formatCurrencySign(number_format((@$tpl['cart_arr'][$service_id]['price'] + $extra['price']), 2), $tpl['option_arr']['o_currency']); ?></div>
				<?php endif; ?>
				<div class="asCartRemove<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><a href="<?php echo pjUtil::getReferer(); ?>" class="asSelectorRemoveFromCart" data-start_ts="<?php echo $start_ts; ?>" data-end_ts="<?php echo $end_ts; ?>" data-date="<?php echo $date; ?>" data-service_id="<?php echo $service_id; ?>" data-employee_id="<?php echo $employee_id; ?>" data-wt_id="<?php echo $wt_id; ?>"></a></div>
			</div>
			<div class="asCartInfo2 asLayout1">
				<div class="asCartDate<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><?php echo pjUtil::formatDate($date, 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></div>
				<?php if (!$hidePrices) : ?>
				<div class="asCartPrice"><?php echo pjUtil::formatCurrencySign(number_format((@$tpl['cart_arr'][$service_id]['price'] + $extra['price']), 2), $tpl['option_arr']['o_currency']); ?></div>
				<?php endif; ?>
				<div class="asCartRemove<?php echo $hidePrices ? ' asCartFix' : NULL; ?>"><a href="<?php echo pjUtil::getReferer(); ?>" class="asSelectorRemoveFromCart" data-start_ts="<?php echo $start_ts; ?>" data-end_ts="<?php echo $end_ts; ?>" data-date="<?php echo $date; ?>" data-service_id="<?php echo $service_id; ?>" data-employee_id="<?php echo $employee_id; ?>" data-wt_id="<?php echo $wt_id; ?>"></a></div>
				<div class="asCartTime"><?php __('front_from'); ?> <?php echo date($tpl['option_arr']['o_time_format'], $fixed_start_ts); ?> <?php __('front_till'); ?> <?php echo date($tpl['option_arr']['o_time_format'], $fixed_end_ts); ?></div>
				
			</div>
			<div class="asSingleCartInfo asLayout2">
				<div class="asSingleCartHead">
					<span class="asSingleCartService"><?php echo pjSanitize::html(@$tpl['cart_arr'][$service_id]['name']); ?></span>
					<span class="asSingleCartRemove"><a href="<?php echo pjUtil::getReferer(); ?>" class="asSelectorRemoveFromCart" data-start_ts="<?php echo $start_ts; ?>" data-end_ts="<?php echo $end_ts; ?>" data-date="<?php echo $date; ?>" data-service_id="<?php echo $service_id; ?>" data-employee_id="<?php echo $employee_id; ?>" data-wt_id="<?php echo $wt_id; ?>"></a></span>
					<br class="asClearBoth" />
				</div>
				<div class="asSingleCartEmployee"><?php echo pjSanitize::html(@$tpl['cart_arr'][$service_id]['employee_arr'][$employee_id]['name']); ?></div>
				<div class="asSingleCartDate">
					<span class="asSingleCartLabel"><?php __('single_date'); ?>:</span>
					<span class="asSingleCartValue"><?php
					list($j, $n, $y, $s, $time) = explode("-", date("j-n-Y-S-H:ia", $fixed_start_ts));
					printf("%u%s %s %u, %s", $j, $suffix[$s], $months[$n], $y, $time); ?></span>
				</div>
				<?php if (!$hidePrices) : ?>
				<div class="asSingleCartPrice">
					<span class="asSingleCartLabel"><?php __('single_price'); ?>:</span>
					<span class="asSingleCartValue"><?php echo pjUtil::formatCurrencySign(number_format((@$tpl['cart_arr'][$service_id]['price'] + $extra['price']), 2), $tpl['option_arr']['o_currency']); ?></span>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
	switch ($_GET['action'])
	{
		case 'pjActionService':
		case 'pjActionServices':
			?>
			<div class="asElementOutline">
				<input type="button" value="<?php __('btnContinue', false, true); ?>" class="asButton asSelectorButton asButtonGreen asSelectorCheckout asFloatRight" />
			</div>
			<?php
			break;
		case 'pjActionCheckout':
		case 'pjActionPreview':
			?>
			<?php if (!$hidePrices) : ?>
			<div class="asElement asElementOutline">
				<div class="asCartInfo">
					<div class="asCartTotal"><?php __('front_cart_total'); ?>:</div>
					<div class="asCartTotalPrice"><?php echo pjUtil::formatCurrencySign(number_format($total, 2), $tpl['option_arr']['o_currency']); ?></div>
				</div>
			</div>
			<?php endif; ?>
			<?php
			break;
		case 'pjActionCart':
			?>
			<div class="asElementOutline">
				<input type="button" value="<?php __('btnCancel', false, true); ?>" class="asSelectorButton asSelectorServices asButton asButtonGray asFloatLeft" />
				<input type="submit" value="<?php __('btnContinue', false, true); ?>" class="asSelectorButton asSelectorCheckout asButton asButtonGreen asFloatRight" />
				<br class="asClearBoth" />
			</div>
			<?php
			break;
	}
} else {
	?><div class="asElement asElementOutline"><?php __('front_cart_empty'); ?></div><?php
	switch ($_GET['action'])
	{
		case 'pjActionCart':
			?>
			<div class="asElementOutline">
				<input type="button" value="<?php __('btnCancel', false, true); ?>" class="asSelectorButton asSelectorServices asButton asButtonGray" />
			</div>
			<?php
			break;
	}
}
?>
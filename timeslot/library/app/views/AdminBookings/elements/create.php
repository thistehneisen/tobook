<?php
list($month, $year) = explode("-", date("n-Y"));
?>
<style type="text/css">
#TSBC_<?php echo $controller->getCalendarId(); ?> table.calendarTable{
	height: 410px;
	width: 450px;
}
#TSBC_<?php echo $controller->getCalendarId(); ?> td.calendarToday,
#TSBC_<?php echo $controller->getCalendarId(); ?> td.calendarPast,
#TSBC_<?php echo $controller->getCalendarId(); ?> td.calendarEmpty,
#TSBC_<?php echo $controller->getCalendarId(); ?> td.calendar{
	height: 60px; /*72*/
	width: 40px; /*86*/
}
#TSBC_<?php echo $controller->getCalendarId(); ?> td.calendarFull{
	cursor: pointer;
}
#TSBC_Slots{
	width: 450px;
	overflow: hidden;
}
</style>

<div id="TSBC_<?php echo $controller->getCalendarId(); ?>" class="TSBCalendar">
<?php echo $tpl['calendar']->getMonthView($month, $year); ?>
</div>
<div id="TSBC_Slots"></div>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminBookings&amp;action=create" method="post" id="frmCreateBooking" class="tsbc-form t10">
	<input type="hidden" name="booking_create" value="1" />
	<input type="hidden" name="payment_option" value="deposit" />
	<input type="hidden" name="num_of_slots" id="num_of_slots" value="" />
	
	<div id="boxBookingDetails" style="display: none">
		<p><label class="title"><?php echo $TS_LANG['booking_total']; ?>:</label><input type="text" name="booking_total" id="booking_total" class="text align_right w80 number" /> <?php echo $tpl['option_arr']['currency']; ?></p>
		<p><label class="title"><?php echo $TS_LANG['booking_deposit']; ?>:</label><input type="text" name="booking_deposit" id="booking_deposit" class="text align_right w80 number" /> <?php echo $tpl['option_arr']['currency']; ?></p>
		<p><label class="title"><?php echo $TS_LANG['booking_tax']; ?>:</label><input type="text" name="booking_tax" id="booking_tax" class="text align_right w80 number" /> <?php echo $tpl['option_arr']['currency']; ?></p>
		<p>
			<label class="title"><?php echo $TS_LANG['booking_booking_status']; ?>:</label>
			<select name="booking_status" id="booking_status" class="select w150">
			<?php
			foreach ($TS_LANG['booking_booking_statuses'] as $k => $v)
			{
				?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
			}
			?>
			</select>
		</p>
		<p>
			<label class="title"><?php echo $TS_LANG['booking_payment_method']; ?>:</label>
			<select name="payment_method" id="payment_method" class="select w150">
				<option value="">---</option>
				<?php
				foreach ($TS_LANG['booking_payment_methods'] as $k => $v)
				{
					?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
				}
				?>
			</select>
		</p>
		<p class="cc" style="display: none">
			<label class="title"><?php echo $TS_LANG['booking_cc_type']; ?></label>
			<select name="cc_type" class="select">
				<option value="">---</option>
				<?php
				foreach ($TS_LANG['front']['bf_cc_types'] as $k => $v)
				{
					?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
				}
				?>
			</select>
		</p>
		<p class="cc" style="display: none">
			<label class="title"><?php echo $TS_LANG['booking_cc_num']; ?></label>
			<input type="text" name="cc_num" class="text w300" />
		</p>
		<p class="cc" style="display: none">
			<label class="title"><?php echo $TS_LANG['booking_cc_exp']; ?></label>
			<select name="cc_exp_month" id="cc_exp_month" class="select">
				<option value="">---</option>
				<?php
				foreach ($TS_LANG['month_name'] as $key => $val)
				{
					?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php
				}
				?>
			</select>
			<select name="cc_exp_year" id="cc_exp_year" class="select">
				<option value="">---</option>
				<?php
				$y = (int) date('Y');
				for ($i = $y; $i <= $y + 10; $i++)
				{
					?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
				}
				?>
			</select>
		</p>
		<p class="cc" style="display: none">
			<label class="title"><?php echo $TS_LANG['booking_cc_code']; ?></label>
			<input type="text" name="cc_code" class="text w100" />
		</p>
		<p><label class="title"><?php echo $TS_LANG['booking_customer_name']; ?>:</label><input type="text" name="customer_name" id="customer_name" class="text w500" /></p>
		<p><label class="title"><?php echo $TS_LANG['booking_customer_email']; ?>:</label><input type="text" name="customer_email" id="customer_email" class="text w500 email" /></p>
		<p><label class="title"><?php echo $TS_LANG['booking_customer_phone']; ?>:</label><input type="text" name="customer_phone" id="customer_phone" class="text w500" /></p>
		<p>
			<label class="title"><?php echo $TS_LANG['booking_customer_country']; ?>:</label>
			<select name="customer_country" class="select">
				<option value="">---</option>
				<?php
				foreach ($tpl['country_arr'] as $v)
				{
					?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['country_title']); ?></option><?php
				}
				?>
			</select>
		</p>
		<p><label class="title"><?php echo $TS_LANG['booking_customer_city']; ?>:</label><input type="text" name="customer_city" id="customer_city" class="text w500" /></p>
		<p><label class="title"><?php echo $TS_LANG['booking_customer_address']; ?>:</label><input type="text" name="customer_address" id="customer_address" class="text w500" /></p>
		<p><label class="title"><?php echo $TS_LANG['booking_customer_zip']; ?>:</label><input type="text" name="customer_zip" id="customer_zip" class="text w500" /></p>
		<p><label class="title"><?php echo $TS_LANG['booking_customer_notes']; ?>:</label><textarea name="customer_notes" id="customer_notes" class="textarea w500 h100"></textarea></p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="" class="button button_save" />
		</p>
	</div>
</form>
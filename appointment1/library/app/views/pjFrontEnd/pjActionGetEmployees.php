<div class="asRow">
	<label class="asLabel"><?php __('front_single_employee'); ?></label>
	<div class="asRowControl">
	<?php
	if (isset($tpl['employee_arr']) && !empty($tpl['employee_arr']))
	{
		$CART = $controller->cart->getAll();
		$acceptBookings = (int) $tpl['option_arr']['o_accept_bookings'] === 1;
		$step = $tpl['option_arr']['o_step'] * 60;
		$service_total = $tpl['service_arr']['total'] * 60;
		$service_length = $tpl['service_arr']['length'] * 60;
		$service_before = $tpl['service_arr']['before'] * 60;
		$i = $_GET['start_ts'];
		$time = time();
		foreach ($tpl['employee_arr'] as $employee)
		{
			$isAvailable = true;
			if (!$employee['t_arr'])
			{
				$isAvailable = false;
			} else {
				# Fix for 24h support
				$offset = $employee['t_arr']['end_ts'] <= $employee['t_arr']['start_ts'] ? 86400 : 0;
				
				$is_free = true;
				$class = "asSingleAvailable";
				foreach ($employee['bs_arr'] as $item)
				{
					if ($i >= $item['start_ts'] && $i < $item['start_ts'] + $item['total'] * 60)
					{
						$is_free = false;
						$class = "asSingleBooked";
						break;
					}
				}
				
				if ($i < $time)
				{
					$is_free = false;
					$class = "asSingleUnavailable";
				}
				
				if ($i + $service_before < $employee['t_arr']['start_ts'] || $i + $service_before >= $employee['t_arr']['end_ts'] + $offset)
				{
					$is_free = false;
					$class = "asSingleUnavailable";
				}
				
				if ($i + $service_before >= $employee['t_arr']['lunch_start_ts'] && $i + $service_before < $employee['t_arr']['lunch_end_ts'])
				{
					$is_free = false;
					$class = "asSingleUnavailable";
				}
				
				if ($is_free)
				{
					foreach ($employee['bs_arr'] as $item)
					{
						if ($i + $service_total - $service_before > $item['start_ts'] && $i <= $item['start_ts'])
						{
							// before booking
							$class = "asSingleUnavailable";
							break;
						}
					}
					if ($i + $service_total - $service_before > $employee['t_arr']['end_ts'] + $offset)
					{
						// end of working day
						$class = "asSingleUnavailable";
					}
					$key = sprintf("%u|%s|%u|%s|%s|%u", $_GET['cid'], $_GET['date'], $tpl['service_arr']['id'], $i - $service_before, $i + $service_total - $service_before, $employee['employee_id']);
					if (array_key_exists($key, $CART))
					{
						//$class = "asSingleAvailable asSingleSelected";
						$class = "asSingleCart";
					}
				}
				if (!empty($employee['avatar']) && is_file($employee['avatar']))
				{
					$src = PJ_INSTALL_URL . $employee['avatar'];
				} else {
					$src = PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/as-nopic-gray.gif';
				}
				if ($class == 'asSingleAvailable')
				{
					printf('<span class="asSingleEmployee asSelectorEmployee %s%s" data-id="%u">', $class, $acceptBookings ? NULL : ' asSingleNotAccept', $employee['employee_id']);
					?>
					<span class="asSingleEmployeeNameOuter">
						<span class="asSingleEmployeeNameInner"><?php echo pjSanitize::html($employee['name']); ?></span>
					</span>
					<span class="asSingleEmployeePic"><img src="<?php echo $src; ?>" alt="<?php echo pjSanitize::html($employee['name']); ?>" /></span>
					<span class="asSingleEmployeePhone"><span class="asSingleEmployeePhoneInner"><?php echo pjSanitize::html($employee['phone']); ?></span></span>
					<span class="asSingleEmployeeEmail"><span class="asSingleEmployeeEmailInner"><span class="asSingleEmployeeEmailIcon"></span><a title="<?php echo pjSanitize::html($employee['email']); ?>" href="mailto:<?php echo pjSanitize::html($employee['email']); ?>"><?php echo pjSanitize::html($employee['email']); ?></a></span></span>
					<?php
					printf('</span>');
				} else {
					printf('<span class="asSingleEmployee %s">', $class);
					?>
					<span class="asSingleEmployeeNameOuter">
						<span class="asSingleEmployeeNameInner"><?php echo pjSanitize::html($employee['name']); ?></span>
					</span>
					<span class="asSingleEmployeePic"><img src="<?php echo $src; ?>" alt="<?php echo pjSanitize::html($employee['name']); ?>" /></span>
					<span class="asSingleEmployeeNA"><?php __('front_single_na'); ?></span>
					<?php
					printf('</span>');
				}
			}
		}
	}
	?>
	</div>
</div>
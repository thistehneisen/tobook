<?php
require_once CONTROLLERS_PATH . 'AppController.controller.php';
class Cron extends AppController
{
	var $layout = 'empty';
	
	function index()
	{
		Object::import('Model', array('Calendar', 'Option', 'Booking', 'BookingSlot', 'Country'));
		$CalendarModel = new CalendarModel();
		$OptionModel = new OptionModel();
		$BookingModel = new BookingModel();
		$BookingSlotModel = new BookingSlotModel();
		$CountryModel = new CountryModel();
		
		Object::import('Component', 'Email');
		$Email = new Email();
		include_once("smslib.php");
		
		$calendar_arr = $CalendarModel->getAll();
		foreach ($calendar_arr as $calendar)
		{
			$option_arr = $OptionModel->getPairs($calendar['id']);
			# Emails
			if ($option_arr['reminder_enable'] == 'Yes')
			{
				$hours_email = (int) $option_arr['reminder_email_before'];
				$sql = sprintf("SELECT `t1`.*, `t2`.`country_title`
					FROM `%1\$s` AS `t1`
					LEFT JOIN `%2\$s` AS `t2` ON `t2`.`id` = `t1`.`customer_country`
					WHERE `t1`.`booking_status` = 'confirmed'
					AND `t1`.`calendar_id` = '%4\$u'
					AND `t1`.`reminder_email` = '0'
					AND 0 < (SELECT COUNT(*) FROM `%3\$s` WHERE `booking_id` = `t1`.`id` AND (UNIX_TIMESTAMP() BETWEEN (start_ts - %5\$u) AND start_ts) LIMIT 1)",
					$BookingModel->getTable(),
					$CountryModel->getTable(),
					$BookingSlotModel->getTable(),
					$calendar['id'],
					$hours_email * 3600					
				);
				$booking_arr = $BookingModel->execute($sql);
		
				if (count($booking_arr) > 0 && !isset($booking_arr[0]))
				{
					$booking_arr = array(0 => $booking_arr);
				}

				foreach ($booking_arr as $booking)
				{
					$datetime = AppController::buildDateTime($booking['id'], $option_arr);
					$cancelURL = INSTALL_URL . 'index.php?controller=Front&action=cancel&cid='.$booking['calendar_id'].'&id='.$booking['id'].'&hash='.sha1($booking['id'].$booking['created'].$this->salt);
					$search = array('{Name}', '{Email}', '{Phone}', '{Country}', '{City}', '{State}', '{Zip}', '{Address}', '{Notes}', '{CCType}', '{CCNum}', '{CCExp}', '{CCSec}', '{PaymentMethod}', '{PaymentOption}', '{BookingSlots}', '{Deposit}', '{Total}', '{Tax}', '{BookingID}', '{CancelURL}');
					$replace = array($booking['customer_name'], $booking['customer_email'], $booking['customer_phone'], $booking['country_title'], $booking['customer_city'], $booking['customer_state'], $booking['customer_zip'], $booking['customer_address'], $booking['customer_notes'], $booking['cc_type'], $booking['cc_num'], ($booking['payment_method'] == 'creditcard' ? $booking['cc_exp'] : NULL), $booking['cc_code'], $booking['payment_method'], $booking['payment_option'], $datetime, $booking['booking_deposit'] . " " . $option_arr['currency'], $booking['booking_total'] . " " . $option_arr['currency'], $booking['booking_tax'] . " " . $option_arr['currency'], $booking['id'], $cancelURL);
					$message = str_replace($search, $replace, $option_arr['reminder_body']);

					$Email->send($booking['customer_email'], $option_arr['reminder_subject'], $message, $option_arr['email_address']);
					$BookingModel->update(array('reminder_email' => 1, 'id' => $booking['id']));
				}
			}
			# SMS
			if (!empty($option_arr['reminder_sms_api']))
			{
				$hours_sms = (int) $option_arr['reminder_sms_hours'];
				$sql = sprintf("SELECT `t1`.*, `t2`.`country_title`
					FROM `%1\$s` AS `t1`
					LEFT JOIN `%2\$s` AS `t2` ON `t2`.`id` = `t1`.`customer_country`
					WHERE `t1`.`booking_status` = 'confirmed'
					AND `t1`.`calendar_id` = '%4\$u'
					AND `t1`.`reminder_sms` = '0'
					AND 0 < (SELECT COUNT(*) FROM `%3\$s` WHERE `booking_id` = `t1`.`id` AND (UNIX_TIMESTAMP() BETWEEN (start_ts - %5\$u) AND start_ts) LIMIT 1)",
					$BookingModel->getTable(),
					$CountryModel->getTable(),
					$BookingSlotModel->getTable(),
					$calendar['id'],
					$hours_sms * 3600					
				);
				$booking_arr = $BookingModel->execute($sql);
			
				if (count($booking_arr) > 0 && !isset($booking_arr[0]))
				{
					$booking_arr = array(0 => $booking_arr);
				}

				foreach ($booking_arr as $booking)
				{
					if (empty($booking['customer_phone']))
					{
						continue;
					}
			
					$datetime = AppController::buildDateTime($booking['id'], $option_arr);
					$cancelURL = INSTALL_URL . 'index.php?controller=Front&action=cancel&cid='.$booking['calendar_id'].'&id='.$booking['id'].'&hash='.sha1($booking['id'].$booking['created'].$this->salt);
					$search = array('{Name}', '{Email}', '{Phone}', '{Country}', '{City}', '{State}', '{Zip}', '{Address}', '{Notes}', '{CCType}', '{CCNum}', '{CCExp}', '{CCSec}', '{PaymentMethod}', '{PaymentOption}', '{BookingSlots}', '{Deposit}', '{Total}', '{Tax}', '{BookingID}', '{CancelURL}');
					$replace = array($booking['customer_name'], $booking['customer_email'], $booking['customer_phone'], $booking['country_title'], $booking['customer_city'], $booking['customer_state'], $booking['customer_zip'], $booking['customer_address'], $booking['customer_notes'], $booking['cc_type'], $booking['cc_num'], ($booking['payment_method'] == 'creditcard' ? $booking['cc_exp'] : NULL), $booking['cc_code'], $booking['payment_method'], $booking['payment_option'], $datetime, $booking['booking_deposit'] . " " . $option_arr['currency'], $booking['booking_total'] . " " . $option_arr['currency'], $booking['booking_tax'] . " " . $option_arr['currency'], $booking['id'], $cancelURL);
					$message = str_replace($search, $replace, $option_arr['reminder_sms_message']);

					$SMSLIB["phone"] = $booking['customer_phone'];
					$SMSLIB["key"] = $option_arr['reminder_sms_api'];
					sendSMS($message, $SMSLIB["phone"]);
					
					$BookingModel->update(array('reminder_sms' => 1, 'id' => $booking['id']));
				}
			}
		}
		exit;
	}
}
?>
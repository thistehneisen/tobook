<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAppController.controller.php';
class pjCron extends pjAppController
{
	var $layout = 'empty';
	
	function index()
	{
		pjObject::import('Model', array('pjOption', 'pjBooking', 'pjTable', 'pjBookingTable'));
		$pjOptionModel = new pjOptionModel();
		$pjBookingModel = new pjBookingModel();
		$pjBookingTableModel = new pjBookingTableModel();
		$pjTableModel = new pjTableModel();
		
		$option_arr = $pjOptionModel->getPairs();
		
		pjObject::import('Component', 'pjEmail');
		$pjEmail = new pjEmail();
		
		if ($option_arr['reminder_enable'] == 'Yes')
		{
			$hours_email = (int) $option_arr['reminder_email_before'];
			$arr = $pjBookingModel->getAll(array(
				't1.status' => 'confirmed',
				't1.id > 0 AND (UNIX_TIMESTAMP()' => array(sprintf("(UNIX_TIMESTAMP(t1.dt) - %1\$u) AND UNIX_TIMESTAMP(t1.dt))", $hours_email * 3600), 'BETWEEN', 'null'),
				't1.reminder_email' => array(1, '<', 'int')
			));
			$pjBookingTableModel->addJoin($pjBookingTableModel->joins, $pjTableModel->getTable(), 'TT', array('TT.id' => 't1.table_id'), array('TT.name'));
			foreach ($arr as $booking_arr)
			{
				$booking_arr['table_arr'] = $pjBookingTableModel->getAll(array('t1.booking_id' => $booking_arr['id']));
				$data = pjAppController::getData($option_arr, $booking_arr, $this->salt);
				$message = str_replace($data['search'], $data['replace'], $option_arr['reminder_body']);
				$pjEmail->send($booking_arr['c_email'], $option_arr['reminder_subject'], $message, $option_arr['email_address']);
				$pjBookingModel->update(array('reminder_email' => 1, 'id' => $booking_arr['id']));
			}
		}
		
		if (!empty($option_arr['reminder_sms_api']))
		{
			include_once("smslib.php");
			$hours_sms = (int) $option_arr['reminder_sms_hours'];
			$arr = $pjBookingModel->getAll(array(
				't1.status' => 'confirmed',
				't1.id > 0 AND (UNIX_TIMESTAMP()' => array(sprintf("(UNIX_TIMESTAMP(t1.dt) - %1\$u) AND UNIX_TIMESTAMP(t1.dt))", $hours_sms * 3600), 'BETWEEN', 'null'),
				't1.reminder_sms' => array(1, '<', 'int')
			));
			$pjBookingTableModel->joins = array();
			$pjBookingTableModel->addJoin($pjBookingTableModel->joins, $pjTableModel->getTable(), 'TT', array('TT.id' => 't1.table_id'), array('TT.name'));
			foreach ($arr as $booking_arr)
			{
				if (empty($booking_arr['c_phone']))
				{
					continue;
				}
				$booking_arr['table_arr'] = $pjBookingTableModel->getAll(array('t1.booking_id' => $booking_arr['id']));
				$data = pjAppController::getData($option_arr, $booking_arr, $this->salt);
				$message = str_replace($data['search'], $data['replace'], $option_arr['reminder_sms_message']);
				$SMSLIB["phone"] = $booking_arr['c_phone'];
				$SMSLIB["key"] = $option_arr['reminder_sms_api'];
				sendSMS($message, $SMSLIB["phone"]);
				$BookingDetailModel->update(array('reminder_sms' => 1, 'id' => $booking_arr['id']));
			}
		}
		exit;
	}
}
?>
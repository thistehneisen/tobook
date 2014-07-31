<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjBookingModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'restaurant_booking_bookings';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'uuid', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'dt', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'dt_to', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'people', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'total', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'payment_method', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'is_paid', 'type' => 'enum', 'default' => 'none'),
		array('name' => 'status', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'txn_id', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'processed_on', 'type' => 'datetime', 'default' => ':NULL'),
		array('name' => 'created', 'type' => 'datetime', 'default' => ':NOW()'),
		array('name' => 'c_title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_fname', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_lname', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_phone', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_email', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_company', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_notes', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'c_address', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_city', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_state', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_zip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'c_country', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'cc_type', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_num', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_exp', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'cc_code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'reminder_email', 'type' => 'tinyint', 'default' => 0),
		array('name' => 'reminder_sms', 'type' => 'tinyint', 'default' => 0)
	);
	
	function getBookings($table_id, $date, $wt_arr)
	{
		if ($wt_arr === false)
		{
			return false;
		}
		pjObject::import('Model', 'pjBookingTable');
		$pjBookingTableModel = new pjBookingTableModel();

		$this->joins = array();
		$this->addJoin($this->joins, $pjBookingTableModel->getTable(), 'TBT', array('TBT.booking_id' => 't1.id', 'TBT.table_id' => "'$table_id'"), array('TBT.id.bt_id'), 'inner');
		$arr = $this->getAll(array(sprintf("t1.id > 0 AND ('$date'") => array(sprintf("DATE(t1.dt) AND DATE(t1.dt_to))"), 'BETWEEN', 'null')));

		$h_arr = array();
		# Fix for 24h support
		$offset = $wt_arr['end_hour'] <= $wt_arr['start_hour'] ? 24 : 0;
		
		for ($i = $wt_arr['start_hour']; $i < $wt_arr['end_hour'] + $offset; $i++)
		{
			$ii = $i < 24 ? $i : $i - 24;
			$ts = strtotime(sprintf("%s %u:00:00", $date, $ii));
			$h_arr[$i] = array();
			foreach ($arr as $booking)
			{
				if ($ts >= strtotime($booking['dt']) && $ts < strtotime($booking['dt_to']))
				{
					$h_arr[$i] = $booking;
					break;
				}
			}
		}
		return $h_arr;
	}
}
?>
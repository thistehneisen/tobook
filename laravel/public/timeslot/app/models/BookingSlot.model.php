<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Booking model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class BookingSlotModel extends AppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to boolean null
 *
 * @var string
 * @access public
 */
	var $primaryKey = 'id';
/**
 * The name of table associate with current model
 *
 * @var string
 * @access protected
 */
	var $table = 'ts_bookings_slots';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'booking_date', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'start_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'end_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'start_ts', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'end_ts', 'type' => 'int', 'default' => ':NULL')
	);
	
	function getBookings($month, $year)
	{
		$numOfDays = date("t", mktime(0, 0, 0, $month, 1, $year));
		$_arr = array();
		foreach (range(1, $numOfDays) as $i)
		{
			$_arr[date("Y-m-d", mktime(0, 0, 0, $month, $i, $year))] = array();
		}
		
		$arr = $this->getAll(array('MONTH(t1.booking_date)' => $month, 'YEAR(t1.booking_date)' => $year));
		foreach ($arr as $v)
		{
			$_arr[$v['booking_date']][] = $v;
		}
		
		return $_arr;
	}
/**
 * Check booking availability for given start and end time
 * 
 * @param int $calendar_id
 * @param int $start_ts Timestamp
 * @param int $end_ts Timestamp
 * @param object $BookingModel
 */
	function checkBooking($calendar_id, $start_ts, $end_ts, $BookingModel = null, $offset = 0)
	{
		if (is_null($BookingModel) || !is_object($BookingModel))
		{
			Object::import('Model', 'Booking');
			$BookingModel = new BookingModel();
		}
		$start_ts += $offset;
		$end_ts += $offset;

		$this->addJoin($this->joins, $BookingModel->getTable(), 'TB', array('TB.id' => 't1.booking_id', 'TB.calendar_id' => $calendar_id,'(booking_status' =>" 'pending' OR booking_status='confirmed' ) "), array('TB.calendar_id'), 'inner');
		#return $this->getCount(array("(('$start_ts' BETWEEN t1.start_ts AND t1.end_ts) OR ('$end_ts' BETWEEN t1.start_ts AND t1.end_ts)) AND '1'" => 1));	
		return $this->getCount(array("(('$start_ts' >= t1.start_ts AND '$start_ts' < t1.end_ts) OR ('$end_ts' > t1.start_ts AND '$end_ts' <= t1.end_ts)) AND '1'" => 1));
	}
}
?>

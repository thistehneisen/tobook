<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Date model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class DateModel extends AppModel
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
	var $table = 'ts_dates';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'slot_length', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'slot_limit', 'type' => 'smallint', 'default' => ':NULL'),
		array('name' => 'start_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'end_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'is_dayoff', 'type' => 'enum', 'default' => 'F')
	);
/**
 * Get working time for given date
 *
 * @param int $calendar_id
 * @param string $date
 * @return array|false Return boolean FALSE if nothing found. Return an empty array if day off. Otherwise return array with data.
 */
	function getWorkingTime($calendar_id, $date)
	{
		$arr = $this->getAll(array('t1.calendar_id' => $calendar_id, 't1.date' => $date, 'offset' => 0, 'row_count' => 1, 'col_name' => 't1.start_time', 'direction' => 'asc'));
		if (count($arr) == 0)
		{
			return false;
		}

		if ($arr[0]['is_dayoff'] == 'T')
		{
			return array();
		}
		
		$wt = array();
		$wt['slot_length'] = $arr[0]['slot_length'];
		$wt['slot_limit'] = $arr[0]['slot_limit'];
		$wt['price'] = $arr[0]['price'];
		$d = getdate(strtotime($arr[0]['start_time']));
		$wt['start_hour'] = $d['hours'];
		$wt['start_minutes'] = $d['minutes'];
	
		$d = getdate(strtotime($arr[0]['end_time']));
		$wt['end_hour'] = $d['hours'];
		$wt['end_minutes'] = $d['minutes'];
		
		$wt['start_ts'] = strtotime($date . " " . $arr[0]['start_time']);
		$wt['end_ts'] = strtotime($date . " " . $arr[0]['end_time']);
		
		return $wt;
	}
		
	function getDatesOff($calendar_id, $month, $year)
	{
		$numOfDays = date("t", mktime(0, 0, 0, $month, 1, $year));
		$_arr = array();
		foreach (range(1, $numOfDays) as $i)
		{
			$_arr[date("Y-m-d", mktime(0, 0, 0, $month, $i, $year))] = array();
		}
		
		//'t1.is_dayoff' => 'T',
		$arr = $this->getAll(array('t1.calendar_id' => $calendar_id, 'MONTH(t1.date)' => $month, 'YEAR(t1.date)' => $year, 'col_name' => 't1.date', 'direction' => 'asc'));
		foreach ($arr as $v)
		{
			$_arr[$v['date']] = $v;
			$_arr[$v['date']]['start_ts'] = strtotime($v['date'] . " " . $v['start_time']);
			$_arr[$v['date']]['end_ts'] = strtotime($v['date'] . " " . $v['end_time']);
		}
		
		return $_arr;
	}
}
?>

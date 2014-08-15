<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjDateModel extends pjAppModel
{
	var $primaryKey = 'id';
	
	var $table = 'dates';
	
	var $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'date', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'start_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'end_time', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'is_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'message', 'type' => 'text', 'default' => ':NULL')
	);

	function getWorkingTime($date)
	{
		$arr = $this->getAll(array('t1.date' => $date, 'offset' => 0, 'row_count' => 1, 'col_name' => 't1.start_time', 'direction' => 'asc'));
		if (count($arr) == 0)
		{
			return false;
		}

		if ($arr[0]['is_dayoff'] == 'T')
		{
			return array();
		}
		
		$wt = array();
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
		
	function getDatesOff($month, $year)
	{
		$numOfDays = date("t", mktime(0, 0, 0, $month, 1, $year));
		$_arr = array();
		foreach (range(1, $numOfDays) as $i)
		{
			$_arr[date("Y-m-d", mktime(0, 0, 0, $month, $i, $year))] = array();
		}
		
		//'t1.is_dayoff' => 'T',
		$arr = $this->getAll(array('MONTH(t1.date)' => $month, 'YEAR(t1.date)' => $year, 'col_name' => 't1.date', 'direction' => 'asc'));
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

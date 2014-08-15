<?php
require_once MODELS_PATH . 'App.model.php';
class WorkingTimeModel extends AppModel
{
	var $primaryKey = 'calendar_id';
	
	var $table = 'ts_working_times';
	
	var $schema = array(
        array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'monday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'monday_limit', 'type' => 'smallint', 'default' => 1),
		array('name' => 'monday_length', 'type' => 'smallint', 'default' => 60),
		array('name' => 'monday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'tuesday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'tuesday_limit', 'type' => 'smallint', 'default' => 1),
		array('name' => 'tuesday_length', 'type' => 'smallint', 'default' => 60),
		array('name' => 'tuesday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'wednesday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'wednesday_limit', 'type' => 'smallint', 'default' => 1),
		array('name' => 'wednesday_length', 'type' => 'smallint', 'default' => 60),
		array('name' => 'wednesday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'thursday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'thursday_limit', 'type' => 'smallint', 'default' => 1),
		array('name' => 'thursday_length', 'type' => 'smallint', 'default' => 60),
		array('name' => 'thursday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'friday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'friday_limit', 'type' => 'smallint', 'default' => 1),
		array('name' => 'friday_length', 'type' => 'smallint', 'default' => 60),
		array('name' => 'friday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'saturday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'saturday_limit', 'type' => 'smallint', 'default' => 1),
		array('name' => 'saturday_length', 'type' => 'smallint', 'default' => 60),
		array('name' => 'saturday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'sunday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_price', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'sunday_limit', 'type' => 'smallint', 'default' => 1),
		array('name' => 'sunday_length', 'type' => 'smallint', 'default' => 60),
		array('name' => 'sunday_dayoff', 'type' => 'enum', 'default' => 'F')
	);
	
	function getDaysOff($calendar_id)
	{
		$_arr = array();
		$arr = $this->get($calendar_id);
		foreach ($arr as $k => $v)
		{
			if (is_null($v) && (strpos($k, "_from") !== false || strpos($k, "_to") !== false))
			{
				list($key) = explode("_", $k);
				$_arr[$key] = 1;
			}
		}
		return $_arr;
	}
	
	function getWorkingTime($calendar_id, $date)
	{
		$day = strtolower(date("l", strtotime($date)));
		$arr = $this->get($calendar_id);

		if (count($arr) == 0)
		{
			return false;
		}
	
		if ($arr[$day . '_dayoff'] == 'T')
		{
			return array();
		}
		
		$wt = array();
		foreach ($arr as $k => $v)
		{
			if (strpos($k, $day . '_limit') !== false && !is_null($v))
			{
				$wt['slot_limit'] = $v;
				continue;
			}
			
			if (strpos($k, $day . '_length') !== false && !is_null($v))
			{
				$wt['slot_length'] = $v;
				continue;
			}
			
			if (strpos($k, $day . '_price') !== false && !is_null($v))
			{
				$wt['price'] = $v;
				continue;
			}
			
			if (strpos($k, $day . '_from') !== false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['start_hour'] = $d['hours'];
				$wt['start_minutes'] = $d['minutes'];
				$wt['start_ts'] = strtotime($date . " " . $v);
				continue;
			}
		
			if (strpos($k, $day . '_to') !== false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['end_hour'] = $d['hours'];
				$wt['end_minutes'] = $d['minutes'];
				$wt['end_ts'] = strtotime($date . " " . $v);
				continue;
			}
		}
		return $wt;
	}
	
	function initWorkingTime($calendar_id)
	{
		$data['calendar_id']    = $calendar_id;
		$data['monday_from']    = '08:00:00';
		$data['monday_to']      = '18:00:00';
		$data['tuesday_from']   = '08:00:00';
		$data['tuesday_to']     = '18:00:00';
		$data['wednesday_from'] = '08:00:00';
		$data['wednesday_to']   = '18:00:00';
		$data['thursday_from']  = '08:00:00';
		$data['thursday_to']    = '18:00:00';
		$data['friday_from']    = '08:00:00';
		$data['friday_to']      = '18:00:00';
		$data['saturday_from']  = '08:00:00';
		$data['saturday_to']    = '18:00:00';
		$data['sunday_from']    = '08:00:00';
		$data['sunday_to']      = '18:00:00';
        $data['owner_id']       =  intval($_SESSION['admin_user']['owner_id']);
		return $this->save($data);
	}
}

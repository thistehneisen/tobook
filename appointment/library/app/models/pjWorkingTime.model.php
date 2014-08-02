<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjWorkingTimeModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'working_times';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'monday_admin_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_admin_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_lunch_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_lunch_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'monday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'tuesday_admin_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_admin_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_lunch_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_lunch_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'tuesday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'wednesday_admin_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_admin_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_lunch_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_lunch_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'wednesday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'thursday_admin_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_admin_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_lunch_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_lunch_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'thursday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'friday_admin_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_admin_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_lunch_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_lunch_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'friday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'saturday_admin_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_admin_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_lunch_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_lunch_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'saturday_dayoff', 'type' => 'enum', 'default' => 'F'),
		array('name' => 'sunday_admin_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_admin_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_lunch_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_lunch_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'sunday_dayoff', 'type' => 'enum', 'default' => 'F')
	);
	
	protected $validate = array(
		'rules' => array(
			'foreign_id' => array(
				'pjActionNumeric' => true,
				'pjActionRequired' => true
			),
			'type' => array(
				'pjActionRequired' => true
			)
		)
	);

	public static function factory($attr=array())
	{
		return new pjWorkingTimeModel($attr);
	}
	
	public function getDaysOff($foreign_id, $type='calendar')
	{
		$_arr = array();
		$arr = $this->reset()->where('t1.foreign_id', $foreign_id)->where('t1.type', $type)->limit(1)->findAll()->getData();
		$arr = !empty($arr) ? $arr[0] : $arr;
		foreach ($arr as $k => $v)
		{
			//if (is_null($v) && (strpos($k, "_from") !== false || strpos($k, "_to") !== false))
			if (strpos($k, "_dayoff") !== false && $v == 'T')
			{
				list($key) = explode("_", $k);
				$_arr[$key] = 1;
			}
		}
		return $_arr;
	}
	
	public function getWorkingTime($foreign_id, $type='calendar')
	{
		$arr = $this->reset()->where('t1.foreign_id', $foreign_id)->where('t1.type', $type)->limit(1)->findAll()->getData();
		
		return !empty($arr) ? $arr[0] : $arr;
	}
	
	public function filterDate($arr, $date)
	{
		if (empty($arr))
		{
			return false;
		}
		
		$day = strtolower(date("l", strtotime($date)));
	
		if ($arr[$day . '_dayoff'] == 'T')
		{
			return array();
		}
		
		$wt = array();
		foreach ($arr as $k => $v)
		{
			if (strpos($k, $day . '_lunch_from') !== false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['lunch_start_hour'] = $d['hours'];
				$wt['lunch_start_minutes'] = $d['minutes'];
				$wt['lunch_start_ts'] = strtotime($date . " " . $v);
				continue;
			}
			
			if (strpos($k, $day . '_lunch_to') !== false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['lunch_end_hour'] = $d['hours'];
				$wt['lunch_end_minutes'] = $d['minutes'];
				$wt['lunch_end_ts'] = strtotime($date . " " . $v);
				continue;
			}
			
			if (strpos($k, $day . '_from') !== false && strpos($k, $day . '_lunch_from') === false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['start_hour'] = $d['hours'];
				$wt['start_minutes'] = $d['minutes'];
				$wt['start_ts'] = strtotime($date . " " . $v);
				continue;
			}
		
			if (strpos($k, $day . '_to') !== false && strpos($k, $day . '_lunch_to') === false && !is_null($v))
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
	
	public function filterDateAdmin($arr, $date)
	{
		if (empty($arr))
		{
			return false;
		}
	
		$day = strtolower(date("l", strtotime($date)));
	
		if ($arr[$day . '_dayoff'] == 'T')
		{
			return array();
		}
	
		$wt = array();
		foreach ($arr as $k => $v)
		{
			if (strpos($k, $day . '_lunch_from') !== false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['lunch_start_hour'] = $d['hours'];
				$wt['lunch_start_minutes'] = $d['minutes'];
				$wt['lunch_start_ts'] = strtotime($date . " " . $v);
				continue;
			}
				
			if (strpos($k, $day . '_lunch_to') !== false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['lunch_end_hour'] = $d['hours'];
				$wt['lunch_end_minutes'] = $d['minutes'];
				$wt['lunch_end_ts'] = strtotime($date . " " . $v);
				continue;
			}
				
			if (strpos($k, $day . '_admin_from') !== false && strpos($k, $day . '_lunch_from') === false && !is_null($v))
			{
				$d = getdate(strtotime($v));
				$wt['start_hour'] = $d['hours'];
				$wt['start_minutes'] = $d['minutes'];
				$wt['start_ts'] = strtotime($date . " " . $v);
				continue;
			}
	
			if (strpos($k, $day . '_admin_to') !== false && strpos($k, $day . '_lunch_to') === false && !is_null($v))
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
	
	public function init($foreign_id, $type='calendar', $owner_id = null)
	{
		$data = array();
		$data['foreign_id']     = $foreign_id;
        $data['type']           = $type;
		$data['owner_id']       = $owner_id;
		
		$data['monday_from']    = '08:00';
        $data['monday_to']      = '18:00';
		$data['tuesday_from']   = '08:00';
		$data['tuesday_to']     = '18:00';
		$data['wednesday_from'] = '08:00';
		$data['wednesday_to']   = '18:00';
		$data['thursday_from']  = '08:00';
		$data['thursday_to']    = '18:00';
		$data['friday_from']    = '08:00';
		$data['friday_to']      = '18:00';
		$data['saturday_from']  = '08:00';
		$data['saturday_to']    = '18:00';
		$data['sunday_from']    = '08:00';
		$data['sunday_to']      = '18:00';
		
		$data['monday_lunch_from']    = '12:00';
		$data['monday_lunch_to']      = '13:00';
		$data['tuesday_lunch_from']   = '12:00';
		$data['tuesday_lunch_to']     = '13:00';
		$data['wednesday_lunch_from'] = '12:00';
		$data['wednesday_lunch_to']   = '13:00';
		$data['thursday_lunch_from']  = '12:00';
		$data['thursday_lunch_to']    = '13:00';
		$data['friday_lunch_from']    = '12:00';
		$data['friday_lunch_to']      = '13:00';
		$data['saturday_lunch_from']  = '12:00';
		$data['saturday_lunch_to']    = '13:00';
		$data['sunday_lunch_from']    = '12:00';
		$data['sunday_lunch_to']      = '13:00';

        $data['monday_admin_from']    = '08:00';
        $data['monday_admin_to']      = '18:00';
        $data['tuesday_admin_from']   = '08:00';
        $data['tuesday_admin_to']     = '18:00';
        $data['wednesday_admin_from'] = '08:00';
        $data['wednesday_admin_to']   = '18:00';
        $data['thursday_admin_from']  = '08:00';
        $data['thursday_admin_to']    = '18:00';
        $data['friday_admin_from']    = '08:00';
        $data['friday_admin_to']      = '18:00';
        $data['saturday_admin_from']  = '08:00';
        $data['saturday_admin_to']    = '18:00';
        $data['sunday_admin_from']    = '08:00';
        $data['sunday_admin_to']      = '18:00';
		
		return $this->reset()->setAttributes($data)->insert()->getInsertId();
	}
	
	public function initFrom($from_foreign_id, $to_foreign_id, $from_type='calendar', $to_type='employee')
	{
		$haystack = array('calendar', 'employee');
		if (!in_array($from_type, $haystack) || !in_array($to_type, $haystack))
		{
			return FALSE;
		}
		$arr = $this->reset()->where('t1.foreign_id', $from_foreign_id)->where('t1.type', $from_type)->limit(1)->findAll()->getData();
		if (empty($arr))
		{
			return FALSE;
		}
		
		$arr = $arr[0];
		unset($arr['id']);
		$arr['foreign_id'] = $to_foreign_id;
		$arr['type'] = $to_type;
		return $this->reset()->setAttributes($arr)->insert()->getInsertId();
	}
}

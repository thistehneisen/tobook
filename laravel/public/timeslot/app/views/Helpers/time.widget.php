<?php
/**
 *
 * @param $d int
 * @param $name string
 * @param $id string
 * @param $class string
 * @param $empty false|array (Array indexes: value, title)
 * @return void
 */
function dayWidget($d = null, $name = 'day', $id = 'day', $class = 'select-mini', $empty = false)
{
	?><select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"><?php
	if ($empty !== false && is_array($empty))
	{
		?><option value="<?php echo $empty['value']; ?>"><?php echo stripslashes($empty['title']); ?></option><?php
	}
	foreach (range(1, 31) as $v)
	{
		if (strlen($v) == 1)
		{
			$v = '0' . $v;
		}
		
		if (!is_null($d) && $v == $d)
		{
			?><option value="<?php echo $v; ?>" selected="selected"><?php echo $v; ?></option><?php
		} else {
			?><option value="<?php echo $v; ?>"><?php echo $v; ?></option><?php
		}
	}
	?></select><?php
}

function monthWidget($m = null, $format = null, $name = 'month', $id = 'month', $class = 'select-mini', $empty = false)
{
	?><select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"><?php
	if ($empty !== false && is_array($empty))
	{
		?><option value="<?php echo $empty['value']; ?>"><?php echo stripslashes($empty['title']); ?></option><?php
	}
	if (!is_null($format) && in_array($format, array('F', 'm', 'M', 'n')))
	{

	} else {
		$format = "m";
	}
	
	foreach (range(1, 12) as $v)
	{
		if (strlen($v) == 1)
		{
			$v = '0' . $v;
		}
		
		if (!is_null($m) && $v == $m)
		{
			?><option value="<?php echo $v; ?>" selected="selected"><?php echo date($format, mktime(0, 0, 0, $v, 1, 2000)); ?></option><?php
		} else {
			?><option value="<?php echo $v; ?>"><?php echo date($format, mktime(0, 0, 0, $v, 1, 2000)); ?></option><?php
		}
	}
	?></select><?php
}

function yearWidget($y = null, $left = null, $right = null, $name = 'year', $id = 'year', $class = 'select-mini', $empty = false)
{
	?><select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"><?php
	if ($empty !== false && is_array($empty))
	{
		?><option value="<?php echo $empty['value']; ?>"><?php echo stripslashes($empty['title']); ?></option><?php
	}
	$curr_year = date("Y");
		
	foreach (range($curr_year - (int) $left, $curr_year + 1 + (int) $right) as $v)
	{
		if (!is_null($y) && $v == $y)
		{
			?><option value="<?php echo $v; ?>" selected="selected"><?php echo $v; ?></option><?php
		} else {
			?><option value="<?php echo $v; ?>"><?php echo $v; ?></option><?php
		}
	}
	?></select><?php
}

function hourWidget($h = null, $name = 'hour', $id = 'hour', $class = 'select-mini', $attr = array(), $options=array())
{
	$defaults = array('start' => 0, 'end' => 23, 'skip' => array());
	$opts = array_merge($defaults, $options);
	
	$attributes = NULL;
	foreach ($attr as $k => $v)
	{
		if (!in_array($k, array('name', 'id', 'class')))
		{
			$attributes .= ' ' . $k . '="'.$v.'"';
		}
	}
	?><select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"<?php echo $attributes; ?>><?php
	foreach (range($opts['start'], $opts['end']) as $v)
	{
		if (in_array($v, $opts['skip'])) continue;
		
		if (strlen($v) == 1)
		{
			$v = '0' . $v;
		}
		
		if (!is_null($h) && $v == $h)
		{
			?><option value="<?php echo $v; ?>" selected="selected"><?php echo $v; ?></option><?php
		} else {
			?><option value="<?php echo $v; ?>"><?php echo $v; ?></option><?php
		}
	}
	?></select><?php
}

function minuteWidget($m = null, $name = 'minute', $id = 'minute', $class = 'select-mini', $attr = array(), $step = 1, $options = array())
{
	$defaults = array('start' => 0, 'end' => 59, 'skip' => array());
	$opts = array_merge($defaults, $options);
	
	$attributes = NULL;
	foreach ($attr as $k => $v)
	{
		if (!in_array($k, array('name', 'id', 'class')))
		{
			$attributes .= ' ' . $k . '="'.$v.'"';
		}
	}
	?><select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"<?php echo $attributes; ?>><?php
	foreach (range($opts['start'], $opts['end']) as $v)
	{
		if (in_array($v, $opts['skip'])) continue;
		
		if ($step > 0 && $v % $step !== 0)
		{
			continue;
		}
		if (strlen($v) == 1)
		{
			$v = '0' . $v;
		}
		
		if (!is_null($m) && $v == $m)
		{
			?><option value="<?php echo $v; ?>" selected="selected"><?php echo $v; ?></option><?php
		} else {
			?><option value="<?php echo $v; ?>"><?php echo $v; ?></option><?php
		}
	}
	?></select><?php
}

function compareTimes($b_from, $b_to, $tyme, $to=false)
{
	if (!$to)
	{
		return $b_from <= $tyme && $b_to > $tyme;
	} else {
		return $b_from < $tyme && $b_to > $tyme;
	}
}

function comboTimeWidget($time = null, $name = 'hour', $id = 'hour', $class = 'select-mini', $attr = array(), $step = 1, $options = array(), $to = false, $empty = false)
{
	$defaults = array('start_hour' => 0, 'start_minutes' => 0, 'end_hour' => 23, 'end_minutes' => 59, 'bookings' => array(), 'id' => NULL, 'max_booked_slots' => NULL);
	$opts = array_merge($defaults, $options);
	
	$attributes = NULL;
	foreach ($attr as $k => $v)
	{
		if (!in_array($k, array('name', 'id', 'class')))
		{
			$attributes .= ' ' . $k . '="'.$v.'"';
		}
	}
	?><select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"<?php echo $attributes; ?>><?php
	if ($empty !== false && is_array($empty))
	{
		?><option value="<?php echo $empty['value']; ?>"><?php echo stripslashes($empty['title']); ?></option><?php
	}
	list($y, $m, $d) = explode("-", date("Y-n-j"));
	$end_time = mktime($opts['end_hour'], $opts['end_minutes'], 0, $m, $d, $y);
	$start_time = mktime($opts['start_hour'], $opts['start_minutes'], 0, $m, $d, $y);
	if ($to)
	{
		$st = getdate($start_time);
		$start_time = mktime($st['hours'], $st['minutes'] + $step, $st['seconds'], $st['mon'], $st['mday'], $st['year']);
	} else {
		$et = getdate($end_time);
		$end_time = mktime($et['hours'], $et['minutes'] - $step, $et['seconds'], $et['mon'], $et['mday'], $et['year']);
	}
	$bookingCount = count($opts['bookings']);
	$isBreak = false;
	
	$s = 0;
	foreach (range($opts['start_hour'], $opts['end_hour']) as $hour)
	{
		$hour = str_pad($hour, 2, "0", STR_PAD_LEFT);
		foreach (range(0, 59) as $minute)
		{
			if (!is_null($opts['max_booked_slots']) && $s >= (int) $opts['max_booked_slots'])
			{
				break;
			}
			
			if ($to && $isBreak)
			{
				continue;
			}
			
			if ($minute % $step !== 0)
			{
				continue;
			}
			
			$minute = str_pad($minute, 2, "0", STR_PAD_LEFT);
			$tm = $hour.":".$minute;
			$tyme = mktime((int)$hour, (int)$minute, 0, $m, $d, $y);

			if (!$to && $tyme > $end_time)
			{
				continue;
			}
			
			if ($to && ($tyme < $start_time || $tyme > $end_time))
			{
				continue;
			}

			$booked = false;
			foreach ($opts['bookings'] as $booking)
			{
				if (!is_null($opts['id']) && $opts['id'] == $booking['id']) continue;
				//$b_from = strtotime($booking['booking_time_from']);
				//$b_to = strtotime($booking['booking_time_to']);
				$b_from = $booking['booking_from'];
				$b_to = $booking['booking_to'];
				list($_y, $_m, $_d) = explode("-", $booking['booking_date']);
				$tyme = mktime((int)$hour, (int)$minute, 0, $_m, $_d, $_y);
				if (compareTimes($b_from, $b_to, $tyme, $to))
				{
					$isBreak = true;
					$booked = true;
					break;
				}
			}
			
			if (!$booked)
			{
				if (!is_null($time) && $tm.":00" == $time)
				{
					?><option value="<?php echo $tm; ?>:00" selected="selected"><?php echo $tm; ?></option><?php
				} else {
					?><option value="<?php echo $tm; ?>:00"><?php echo $tm; ?></option><?php
				}
				$s++;
			}
		}
	}
	?></select><?php
}

function comboTimeWidget2($btime = array(), $name = 'hour', $id = 'hour', $class = 'select-mini', $attr = array(), $step = 1, $options=array(), $to=false)
{
	list($y, $m, $d) = explode("-", date("Y-n-j"));
	list($by, $bm, $bd) = explode("-", $btime['booking_date']);
	$time = $btime['booking_time_to'];
	$defaults = array('start_hour' => 0, 'start_minutes' => 0, 'end_hour' => 23, 'end_minutes' => 59, 'bookings' => array(), 'id' => NULL, 'max_booked_slots' => NULL);
	$opts = array_merge($defaults, $options);
	
	$cache = array();
	foreach ($opts['bookings'] as $booking)
	{
		if (!is_null($opts['id']) && $opts['id'] == $booking['id']) continue;
		for ($i = $booking['booking_from']; $i < $booking['booking_to']; $i = $i + ($step * 60))
		{
			$cache[] = $i;
		}
	}
	$cache = array_unique($cache);
		
	$attributes = NULL;
	foreach ($attr as $k => $v)
	{
		if (!in_array($k, array('name', 'id', 'class')))
		{
			$attributes .= ' ' . $k . '="'.$v.'"';
		}
	}
	?><select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"<?php echo $attributes; ?>><?php
	$from = mktime(substr($btime['booking_time_from'], 0, 2), substr($btime['booking_time_from'], 3, 2), 0, $m, $d, $y);
	$end_time = mktime($opts['end_hour'], $opts['end_minutes'], 0, $m, $d, $y);
	$start_time = mktime($opts['start_hour'], $opts['start_minutes'], 0, $m, $d, $y);
	if ($to)
	{
		$st = getdate($start_time);
		$start_time = mktime($st['hours'], $st['minutes'] + $step, $st['seconds'], $st['mon'], $st['mday'], $st['year']);
	} else {
		$et = getdate($end_time);
		$end_time = mktime($et['hours'], $et['minutes'] - $step, $et['seconds'], $et['mon'], $et['mday'], $et['year']);
	}
	$bookingCount = count($opts['bookings']);
	$isBreak = false;
	$isStop = false;
	
	$s = 0;
	foreach (range($opts['start_hour'], $opts['end_hour']) as $hour)
	{
		if ($isStop)
		{
			break;
		}
		
		$hour = str_pad($hour, 2, "0", STR_PAD_LEFT);
		foreach (range(0, 59) as $minute)
		{
			if (!is_null($opts['max_booked_slots']) && $s >= (int) $opts['max_booked_slots'])
			{
				break;
			}
			
			if ($to && $isBreak)
			{
				continue;
			}
			
			if ($minute % $step !== 0)
			{
				continue;
			}
			
			$minute = str_pad($minute, 2, "0", STR_PAD_LEFT);
			$tm = $hour.":".$minute;
			$tyme = mktime((int)$hour, (int)$minute, 0, $m, $d, $y);
			$rtime = mktime((int)$hour, (int)$minute, 0, $bm, $bd, $by);

			if ($from >= $tyme)
			{
				continue;
			}
			
			if (!$to && $tyme > $end_time)
			{
				continue;
			}
			
			if ($to && ($tyme < $start_time || $tyme > $end_time))
			{
				continue;
			}
			
			if (in_array($rtime, $cache))
			{
				$isStop = true;
				break;
				//continue;
			}

			$booked = false;
			foreach ($opts['bookings'] as $booking)
			{
				if (!is_null($opts['id']) && $opts['id'] == $booking['id']) continue;
				$b_from = $booking['booking_from'];
				$b_to = $booking['booking_to'];
				list($_y, $_m, $_d) = explode("-", $booking['booking_date']);
				$tyme = mktime((int)$hour, (int)$minute, 0, $_m, $_d, $_y);
				if (compareTimes($b_from, $b_to, $tyme, $to))
				{
					$isBreak = true;
					$booked = true;
					break;
				}
			}
			
			if (!$booked)
			{
				if (!is_null($time) && $tm.":00" == $time)
				{
					?><option value="<?php echo $tm; ?>:00" selected="selected"><?php echo $tm; ?></option><?php
				} else {
					?><option value="<?php echo $tm; ?>:00"><?php echo $tm; ?></option><?php
				}
				$s++;
			}
		}
	}
	?></select><?php
}
?>
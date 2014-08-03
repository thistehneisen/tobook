<?php
require_once FRAMEWORK_PATH . 'Controller.class.php';
/**
 * App controller
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers
 */
class AppController extends Controller
{
/**
 * Model's cache
 *
 * @var array
 * @access protected
 */
	var $models = array();
/**
 * Multi calendar support
 *
 * @var bool
 * @access private
 */
	var $multiCalendar = true;
/**
 * Multi user support
 *
 * @var bool
 * @access private
 */
	var $multiUser = false;
/**
 * Check if multi-calendar support is enabled
 *
 * @return bool
 * @access public
 */
	function isMultiCalendar()
	{
		return $this->multiCalendar;
	}
/**
 * Check if multi-user support is enabled
 *
 * @return bool
 * @access public
 */
	function isMultiUser()
	{
		return $this->multiUser;
	}
/**
 * Check loged user against 'owner' role
 *
 * @access public
 * @return bool
 */
	function isOwner()
    {
   		return $this->getRoleId() == 2;
    }
/**
 * Get calendar ID
 *
 * @access public
 * @return int|false
 */
    function getCalendarId()
    {
    	return isset($_SESSION[$this->default_user]) && array_key_exists('calendar_id', $_SESSION[$this->default_user]) ? $_SESSION[$this->default_user]['calendar_id'] : false;
    }

    /**
    * Get current owner id
    */
    function getOwnerId(){
        return isset($_SESSION[$this->default_user]) && array_key_exists('owner_id', $_SESSION[$this->default_user]) ? $_SESSION[$this->default_user]['owner_id'] : false;
    }
/**
 * Return event dates as string
 *
 * @param int $booking_id
 * @param array $option_arr
 * @access public
 * @return string
 * @static
 */
	function buildDateTime($booking_id, $option_arr)
	{
		Object::import('Model', 'BookingSlot');
		$BookingSlotModel = new BookingSlotModel();
		
		$bs_arr = $BookingSlotModel->getAll(array('t1.booking_id' => $booking_id, 'col_name' => 't1.start_ts', 'direction' => 'asc'));
		$dates = array();
		foreach ($bs_arr as $v)
		{
			$dates[] = date($option_arr['date_format'], $v['start_ts']) . ", " . date($option_arr['time_format'], $v['start_ts']) . " - " . date($option_arr['time_format'], $v['end_ts']);
		}
		$datetime = NULL;
		if (count($dates) > 0)
		{
			$datetime = join("\n", $dates);
		}
		return $datetime;
	}
/**
 * Build CSS by given $calendar_id
 *
 * @param int $calendar_id
 * @access public
 * @return string
 */
    function css($calendar_id)
    {
    	Object::import('Model', 'Option');
    	$OptionModel = new OptionModel();
    	$option_arr = $OptionModel->getPairs($calendar_id);
    	$cols = 7; //$option_arr['weeknumbers'] == "true" ? 8 : 7;

		$subject = @file_get_contents(CSS_PATH . 'calendar.txt');
		$search = array(
			'[calendar_height]',
			'[calendar_width]',
			'[cell_height]',
			'[cell_width]',
			'[color_bg_slot]',
			'[color_bg_empty_cells]',
			'[color_bg_month]',
			'[color_bg_tooltip]',
			'[color_bg_weekday]',
			'[color_bg_today]',
			'[color_bg_full]',
			'[color_bg_legend]',
			'[color_bg_dayoff]',
			'[color_bg_form]',
			'[color_bg_partly]',
			'[color_bg_past]',
			'[color_border_inner]',
			'[color_border_outer]',
			'[color_border_legend]',
			'[color_border_form]',
			'[color_font_day]',
			'[color_font_event]',
			'[color_font_month]',
			'[color_font_tooltip]',
			'[color_font_weekday]',
			'[color_font_legend]',
			'[color_font_dayoff]',
			'[color_font_form]',
			'[color_font_partly]',
			'[color_font_past]',
			'[size_border_inner]',
			'[size_border_outer]',
			'[size_border_legend]',
			'[size_border_form]',
			'[size_font_day]',
			'[size_font_event]',
			'[size_font_month]',
			'[size_font_tooltip]',
			'[size_font_weekday]',
			'[style_font_day]',
			'[style_font_event]',
			'[style_font_family]',
			'[style_font_month]',
			'[style_font_weekday]',
			'[calendarContainer]'
		);
		
		$replace = array(
			$option_arr['calendar_height'],
			$option_arr['calendar_width'],
			ceil($option_arr['calendar_height'] / $cols),
			floor($option_arr['calendar_width'] / 7),
			$option_arr['color_bg_slot'],
			$option_arr['color_bg_empty_cells'],
			$option_arr['color_bg_month'],
			$option_arr['color_bg_tooltip'],
			$option_arr['color_bg_weekday'],
			$option_arr['color_bg_today'],
			$option_arr['color_bg_full'],
			$option_arr['color_bg_legend'],
			$option_arr['color_bg_dayoff'],
			$option_arr['color_bg_form'],
			$option_arr['color_bg_partly'],
			$option_arr['color_bg_past'],
			$option_arr['color_border_inner'],
			$option_arr['color_border_outer'],
			$option_arr['color_border_legend'],
			$option_arr['color_border_form'],
			$option_arr['color_font_day'],
			$option_arr['color_font_event'],
			$option_arr['color_font_month'],
			$option_arr['color_font_tooltip'],
			$option_arr['color_font_weekday'],
			$option_arr['color_font_legend'],
			$option_arr['color_font_dayoff'],
			$option_arr['color_font_form'],
			$option_arr['color_font_partly'],
			$option_arr['color_font_past'],
			$option_arr['size_border_inner'],
			$option_arr['size_border_outer'],
			$option_arr['size_border_legend'],
			$option_arr['size_border_form'],
			$option_arr['size_font_day'],
			$option_arr['size_font_event'],
			$option_arr['size_font_month'],
			$option_arr['size_font_tooltip'],
			$option_arr['size_font_weekday'],
			$option_arr['style_font_day'],
			$option_arr['style_font_event'],
			$option_arr['style_font_family'],
			$option_arr['style_font_month'],
			$option_arr['style_font_weekday'],
			'#TSBC_' . $calendar_id
		);
		
		header("Content-type: text/css");
		echo str_replace($search, $replace, $subject);
		exit;
    }
/**
 * Set timezone
 *
 * @param int $timezone
 * @access public
 * @return void
 * @static
 */
    function setTimezone($timezone="UTC")
    {
    	if (in_array(version_compare(phpversion(), '5.1.0'), array(0,1)))
		{
			date_default_timezone_set($timezone);
		} else {
			$safe_mode = ini_get('safe_mode');
			if ($safe_mode)
			{
				putenv("TZ=".$timezone);
			}
		}
    }
/**
 * Set MySQL server time
 *
 * @param int $offset
 * @access public
 * @return void
 * @static
 */
    function setMySQLServerTime($offset="-0:00")
    {
		mysql_query("SET SESSION time_zone = '$offset';");
    }
/**
 * Return an array with total of sums (price, total, etc.) for items in Cart
 * 
 * @param int $calendar_id
 * @param string $cartName
 * @param array $option_arr
 * @static
 * @return array
 */
	function getCartTotal($calendar_id, $cartName, $option_arr)
	{
		Object::import('Model', array('WorkingTime', 'Date', 'Price', 'PriceDay'));
		$DateModel = new DateModel();
		$WorkingTimeModel = new WorkingTimeModel();
		$PriceModel = new PriceModel();
		$PriceDayModel = new PriceDayModel();

		$price = 0;
		if (isset($_SESSION[$cartName]))
		{
			foreach ($_SESSION[$cartName] as $cid => $date_arr)
			{
				if ($cid != $calendar_id)
				{
					continue;
				}
				foreach ($date_arr as $date => $time_arr)
				{
					foreach ($time_arr as $time => $qty)
					{
						$qty = 1; //FIXME
						list($start_ts, $end_ts) = explode("|", $time);
						
						$date_arr = $DateModel->getWorkingTime($cid, $date);
						if ($date_arr !== false && count($date_arr) > 0)
						{
							if (empty($date_arr['price']) || (float) $date_arr['price'] == 0)
							{
								# Price per slot for given date. Example: 22.03.2011, 10:00 - 11:00, $7.99
								$price_arr = $PriceModel->getAll(array('t1.calendar_id' => $cid, 't1.date' => $date, 't1.start_ts' => $start_ts, 't1.end_ts' => $end_ts, 'col_name' => 't1.start_time', 'direction' => 'asc', 'offset' => 0, 'row_count' => 1));
								if (count($price_arr) === 1)
								{
									$price += $price_arr[0]['price'] * $qty;
								}
							} else {
								# Price per slot for given date. Each slot has the same price. Example: 22.03.2011, $7.99
								$price += $date_arr['price'] * $qty;
							}
						} else {
							$wt_arr = $WorkingTimeModel->getWorkingTime($cid, $date);
							$day = strtolower(date("l", $start_ts));
							if ($wt_arr !== false && count($wt_arr) > 0)
							{
								$price_day_arr = $PriceDayModel->getAll(array('t1.calendar_id' => $cid, 't1.day' => $day, 't1.start_time' => date("H:i:s", $start_ts), 't1.end_time' => date("H:i:s", $end_ts), 'col_name' => 't1.start_time', 'direction' => 'asc', 'offset' => 0, 'row_count' => 1));
								if (count($price_day_arr) === 1)
								{
									//if (empty($wt_arr['price']) || (float) $wt_arr['price'] == 0)
									//{
									# Price per slot for given day of the week. Example: Monday 10:00 - 11:00, $7.99
									//{
									$price += $price_day_arr[0]['price'] * $qty;
									//}
								} else {
									# Price per slot for given day of the week. Each slot has the same price. Example: Monday, $7.99
									$price += $wt_arr['price'] * $qty;
								}
							}
						}
					}
				}
			}
		}
		
		$tax = ($price * $option_arr['tax']) / 100;
		$total = $price + $tax;
		$deposit = ($total * $option_arr['deposit_percent']) / 100;

		return array('price' => round($price, 2), 'total' => round($total, 2), 'deposit' => round($deposit, 2), 'tax' => round($tax, 2));
	}
/**
 * Return prices (per slot) for items in Cart
 * 
 * @param int $calendar_id
 * @param string $cartName
 * @static
 * @return array
 */
	function getCartPrices($calendar_id, $cartName)
	{
		Object::import('Model', array('WorkingTime', 'Date', 'Price', 'PriceDay'));
		$DateModel = new DateModel();
		$WorkingTimeModel = new WorkingTimeModel();
		$PriceModel = new PriceModel();
		$PriceDayModel = new PriceDayModel();
		
		$_arr = array();
		foreach ($_SESSION[$cartName] as $cid => $date_arr)
		{
			if ($cid != $calendar_id)
			{
				continue;
			}
			foreach ($date_arr as $date => $time_arr)
			{
				$date_arr = $DateModel->getWorkingTime($calendar_id, $date);
				$wt_arr = $WorkingTimeModel->getWorkingTime($calendar_id, $date);
				$day = strtolower(date("l", strtotime($date)));
				foreach ($time_arr as $time => $q)
				{
					list($start_ts, $end_ts) = explode("|", $time);
					
					$index = $time;
					$_arr[$index] = 0;
					if ($date_arr !== false && count($date_arr) > 0)
					{
						if (empty($date_arr['price']) || (float) $date_arr['price'] == 0)
						{
							# Price per slot for given date. Example: 22.03.2011, 10:00 - 11:00, $7.99
							# RANK: 400
							$price_arr = $PriceModel->getAll(array('t1.calendar_id' => $calendar_id, 't1.date' => $date, 't1.start_ts' => $start_ts, 't1.end_ts' => $end_ts, 'col_name' => 't1.start_time', 'direction' => 'asc', 'offset' => 0, 'row_count' => 1));
							if (count($price_arr) === 1)
							{
								$_arr[$index] = $price_arr[0]['price'];
							}
						} else {
							# Price per slot for given date. Each slot has the same price. Example: 22.03.2011, $7.99
							# RANK: 300
							$_arr[$index] = $date_arr['price'];
						}
					} else {
						//$day = strtolower(date("l", $start_ts));
						if ($wt_arr !== false && count($wt_arr) > 0)
						{
							$price_day_arr = $PriceDayModel->getAll(array('t1.calendar_id' => $calendar_id, 't1.day' => $day, 't1.start_time' => date("H:i:s", $start_ts), 't1.end_time' => date("H:i:s", $end_ts), 'col_name' => 't1.start_time', 'direction' => 'asc', 'offset' => 0, 'row_count' => 1));
							if (count($price_day_arr) === 1)
							{
								$_arr[$index] = $price_day_arr[0]['price'];
								//if (empty($wt_arr['price']) || (float) $wt_arr['price'] == 0)
								# Price per slot for given day of the week. Example: Monday 10:00 - 11:00, $7.99
								# RANK: 200								
							} else {
								# Price per slot for given day of the week. Each slot has the same price. Example: Monday, $7.99
								# RANK: 100
								$_arr[$index] = $wt_arr['price'];
							}
						}
					}
				}
			}
		}
		# NB: Higher RANK gets bigger precedence!
		return array($calendar_id => $_arr);
	}
/**
 * Return an array with prices for given date
 * 
 * @param int $calendar_id
 * @param string $date
 * @param array $option_arr
 * @static
 * @return array|false
 */
	function getPricesDate($calendar_id, $date, $option_arr)
	{
		$t_arr = AppController::getRawSlots($calendar_id, $date, $option_arr);
		if ($t_arr === false)
		{
			# It's Day off
			return false;
		}
		
		Object::import('Model', array('WorkingTime', 'Date', 'Price', 'PriceDay'));
		$DateModel = new DateModel();
		$WorkingTimeModel = new WorkingTimeModel();
		$PriceModel = new PriceModel();
		$PriceDayModel = new PriceDayModel();
		
		$date_arr = $DateModel->getWorkingTime($calendar_id, $date);
		$wt_arr = $WorkingTimeModel->getWorkingTime($calendar_id, $date);
		
		$_arr = array();
		$step = $t_arr['slot_length'] * 60;
		# Fix for 24h support
		$offset = $t_arr['end_ts'] <= $t_arr['start_ts'] ? 86400 : 0;
		$day = strtolower(date("l", strtotime($date)));
		
		for ($i = $t_arr['start_ts']; $i < $t_arr['end_ts'] + $offset; $i += $step)
		{
			$index = $i . "|" . ($i + $step);
			$_arr[$index] = 0;
			if ($date_arr !== false && count($date_arr) > 0)
			{
				if (empty($date_arr['price']) || (float) $date_arr['price'] == 0)
				{
					# Price per slot for given date. Example: 22.03.2011, 10:00 - 11:00, $7.99
					# RANK: 400
					$price_arr = $PriceModel->getAll(array('t1.calendar_id' => $calendar_id, 't1.date' => $date, 't1.start_ts' => $i, 't1.end_ts' => $i + $step, 'col_name' => 't1.start_time', 'direction' => 'asc', 'offset' => 0, 'row_count' => 1));
					if (count($price_arr) === 1)
					{
						$_arr[$index] = $price_arr[0]['price'];
					}
				} else {
					# Price per slot for given date. Each slot has the same price. Example: 22.03.2011, $7.99
					# RANK: 300
					$_arr[$index] = $date_arr['price'];
				}
			} else {
				//$day = strtolower(date("l", $i));
				if ($wt_arr !== false && count($wt_arr) > 0)
				{
					$price_day_arr = $PriceDayModel->getAll(array('t1.calendar_id' => $calendar_id, 't1.day' => $day, 't1.start_time' => date("H:i:s", $i), 't1.end_time' => date("H:i:s", $i + $step), 'col_name' => 't1.start_time', 'direction' => 'asc', 'offset' => 0, 'row_count' => 1));
					if (count($price_day_arr) === 1)
					{
						$_arr[$index] = $price_day_arr[0]['price'];
						//}
						//Promqna v proverkata poradi promqna v AdminTime->setPrices
						//if (empty($wt_arr['price']) || (float) $wt_arr['price'] == 0)
						//{
						# Price per slot for given day of the week. Example: Monday 10:00 - 11:00, $7.99
						# RANK: 200
					} else {
						# Price per slot for given day of the week. Each slot has the same price. Example: Monday, $7.99
						# RANK: 100
						$_arr[$index] = $wt_arr['price'];
					}
				}
			}
		}
		# NB: Higher RANK gets bigger precedence!
		return $_arr;
	}
/**
 * Return an array with range of slots for given date
 * 
 * @param int $calendar_id
 * @param string $date
 * @param array $option_arr
 * @static
 * @return array|false
 */
	function getRawSlots($calendar_id, $date, $option_arr)
	{
		Object::import('Model', 'Date');
		$DateModel = new DateModel();
			
		$date_arr = $DateModel->getWorkingTime($calendar_id, $date);
		if ($date_arr === false)
		{
			# There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
			Object::import('Model', 'WorkingTime');
			$WorkingTimeModel = new WorkingTimeModel();
			$wt_arr = $WorkingTimeModel->getWorkingTime($calendar_id, $date);
			if (count($wt_arr) == 0)
			{
				# It's Day off
				return false;
			}
			//$wt_arr['slot_length'] = $option_arr['slot_length'];
			$t_arr = $wt_arr;
		} else {
			# There is custom working time/prices for given date
			if (count($date_arr) == 0)
			{
				# It's Day off
				return false;
			}
			$t_arr = $date_arr;
		}
		return $t_arr;
	}
}
?>

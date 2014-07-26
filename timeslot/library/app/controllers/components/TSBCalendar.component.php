<?php
require_once COMPONENTS_PATH . 'Calendar.component.php';
/**
 * TSBCalendar component
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers.components
 */
class TSBCalendar extends Calendar
{
/**
 * Hold calendar ID
 *
 * @access public
 * @var int
 */
	var $calendar_id = null;
/**
 * Days Off, e.g. Sunday, Saturday...
 *
 * @access public
 * @var array
 */
	var $daysOff = array();
/**
 * Dates Off, e.g. 2011-03-03, 2011-01-05...
 *
 * @access public
 * @var array
 */
	var $datesOff = array();
	var $workingTime = array();
	var $bookings = array();
/**
 * Whether to show week numbers, or not
 *
 * @access public
 * @var bool
 */
	var $weeknumbers = false;
/**
 * Hold prices
 *
 * @access public
 * @var array
 */
	var $prices = array();
/**
 * Whether to show prices, or not
 *
 * @access public
 * @var bool
 */
	var $showPrices = false;
/**
 * Hold time format
 *
 * @access public
 * @var string
 */
	var $timeFormat = "H:i";
/**
 * Hold options
 *
 * @access public
 * @var array
 */
	var $options = array();
/**
 *
 * @see app/controllers/components/Calendar::getMonthView()
 * @param int $month
 * @param int $year
 * @access public
 * @return string
 */
	function getMonthView($month, $year)
    {
        return $this->getMonthHTML($month, $year, 1);
    }
/**
 *
 * @see app/controllers/components/Calendar::getCalendarLink()
 * @access public
 * @static
 * @return array
 */
	function getCalendarLink($month, $year)
	{
		return array('href' => "#", 'onclick' => '', 'class' => 'calendarLinkMonth');
	}
/**
 * Get month view for more than one month
 *
 * @param int $startMonth
 * @param int $startYear
 * @param int $numOfMonths
 * @access public
 * @return string
 */
	function getMonthViewMulti($startMonth, $startYear, $numOfMonths=3)
	{
		if ($numOfMonths < 1 && $numOfMonths > 12)
		{
			return false;
		}
		
		$month[1] = $startMonth;
		foreach (range(2, 12) as $i)
		{
			$month[$i] = ($month[1] + $i - 1) > 12 ? $month[1] + $i - 1 - 12 : $month[1] + $i - 1;
		}
		
		$year[1] = $startYear;
		foreach (range(2, 12) as $i)
		{
			$year[$i] = ($month[1] + $i - 1) > 12 ? $year[1] + 1 : $year[1];
		}
		
		$str = "";
		foreach (range(1, $numOfMonths) as $i)
		{
			$str .= $this->getMonthView($month[$i], $year[$i]);
		}
		
		return $str;
	}
/**
 * Get legend
 *
 * @param array $TS_LANG
 * @access public
 * @return string
 */
	function getLegend($TS_LANG)
	{
		$html = '
		<table class="calendarLegend" style="width: '.$this->options['calendar_width'].'px" cellspacing="1" cellpadding="2">
			<tbody>
				<tr>
					<td class="calendarColor calendarColorSlot">&nbsp;</td>
					<td class="calendarLabel">'.$TS_LANG['legend_slot'].'</td>
					<td class="calendarColor calendarColorPartly">&nbsp;</td>
					<td class="calendarLabel">'.$TS_LANG['legend_partly'].'</td>
					<td class="calendarColor calendarColorFull">&nbsp;</td>
					<td class="calendarLabel">'.$TS_LANG['legend_fully'].'</td>
					<td class="calendarColor calendarColorToday">&nbsp;</td>
					<td class="calendarLabel">'.$TS_LANG['legend_today'].'</td>
					<td class="calendarColor calendarColorDayoff">&nbsp;</td>
					<td class="calendarLabel">'.$TS_LANG['legend_dayoff'].'</td>
				</tr>
			</tbody>
		</table>';
		return $html;
	}
/**
 * Get month html
 *
 * @param int $m
 * @param int $y
 * @param bool $showYear
 * @access public
 * @return string
 */
    function getMonthHTML($m, $y, $showYear = 1)
    {
		$s = "";
        
        $showTooltip = $this->options['show_tooltip'] == 'Yes';
        
        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year = $a[1];
        
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));
    	
    	$first = $date["wday"];
    	$monthName = $this->monthNames[$month - 1];
    	
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	
    	if ($showYear == 1)
    	{
    	    $prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
    	    $nextMonth = $this->getCalendarLink($next[0], $next[1]);
    	} else {
    	    $prevMonth = "";
    	    $nextMonth = "";
    	}
    	
    	//$header = $monthName . (($showYear > 0) ? " " . $year : "");
    	
    	$search		= array('Month', 'Year');
    	$replace	= array($monthName, $showYear > 0 ? $year : "");
    	$header		= str_replace($search, $replace, $this->monthYearFormat);
		    	
    	$prevM = ((int) $month - 1) < 1 ? 12 : (int) $month - 1;
    	$prevY = ((int) $month - 1) < 1 ? (int) $year - 1 : (int) $year;
    	
    	$nextM = ((int) $month + 1) > 12 ? 1 : (int) $month + 1;
    	$nextY = ((int) $month + 1) > 12 ? (int) $year + 1 : (int) $year;
    	
    	$cols = $this->weeknumbers ? 8 : 7;

    	$s .= "<table class=\"calendarTable\" cellspacing=\"0\" cellpadding=\"0\">\n";
    	$s .= "<tr>\n";
    	$s .= "<td class=\"calendarMonth\">" . (!$this->getShowPrevLink() ? "&nbsp;" : '<a rel="prev-'.$prevM.'-'.$prevY.'" href="'.$prevMonth['href'].'" class="'.$prevMonth['class'].'" title="'.$this->getPrevTitle().'">'.$this->getPrevLink().'</a>')  . "</td>\n";
    	$s .= "<td class=\"calendarMonth\" colspan=\"".($cols == 7 ? 5 : 6)."\">$header</td>\n";
    	$s .= "<td class=\"calendarMonth\">" . (!$this->getShowNextLink() ? "&nbsp;" : '<a rel="next-'.$nextM.'-'.$nextY.'" href="'.$nextMonth['href'].'" class="'.$nextMonth['class'].'" title="'.$this->getNextTitle().'">'.$this->getNextLink().'</a>')  . "</td>\n";
    	$s .= "</tr>\n";
    	
    	$s .= "<tr>\n";
    	if ($this->weeknumbers)
    	{
    		$s .= "<td class=\"calendarWeekDay\">".$this->weekTitle."</td>\n";
    		$weekNumPattern = "<td class=\"calendarEmpty\">{WEEK_NUM}</td>";
    	}
    	$s .= "<td class=\"calendarWeekDay\">" . $this->dayNames[($this->startDay)%7] . "</td>\n";
    	$s .= "<td class=\"calendarWeekDay\">" . $this->dayNames[($this->startDay+1)%7] . "</td>\n";
    	$s .= "<td class=\"calendarWeekDay\">" . $this->dayNames[($this->startDay+2)%7] . "</td>\n";
    	$s .= "<td class=\"calendarWeekDay\">" . $this->dayNames[($this->startDay+3)%7] . "</td>\n";
    	$s .= "<td class=\"calendarWeekDay\">" . $this->dayNames[($this->startDay+4)%7] . "</td>\n";
    	$s .= "<td class=\"calendarWeekDay\">" . $this->dayNames[($this->startDay+5)%7] . "</td>\n";
    	$s .= "<td class=\"calendarWeekDay\">" . $this->dayNames[($this->startDay+6)%7] . "</td>\n";
    	$s .= "</tr>\n";

    	$d = $this->startDay + 1 - $first;
    	while ($d > 1)
    	{
    	    $d -= 7;
    	}

        $today = getdate(time());
        $midnight = mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']);
    	
        $rows = 0;
    	while ($d <= $daysInMonth)
    	{
    	    $s .= "<tr>\n";
    	    
    	    if ($this->weeknumbers)
    	    {
    	    	$s .= $weekNumPattern;
    	    }
    	    for ($i = 0; $i < 7; $i++)
    	    {
    	    	$timestamp = mktime(0, 0, 0, $month, $d, $year);
    	    	$date = date("Y-m-d", $timestamp);
    	    	$dayOfWeek = strtolower(date("l", $timestamp));
    	    	
    	    	$class = "";
    	    	$tooltip_slots = NULL;
    	    	$hasEmptySlots = true;
    	    	$hasBookedSlots = false;
    	    	$isDayOff = false;
    	    	
    	    	if ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"])
    	    	{
    	    		$class = 'calendar calendarToday';
    	    	} elseif ($d < 1 || $d > $daysInMonth) {
    	    		$class = 'calendarEmpty';
    	    	} else {
    	    		$class = 'calendar';
    	    	}
    	    	$oldClass = $class;

    	    	$e_arr1 = array();
    	    	if ($d >= 1 && $d <= $daysInMonth)
    	    	{
	    	    	if ($this->workingTime[$dayOfWeek . "_dayoff"] == 'T')
	    	    	{
	    	    		$class = 'calendarDayoff';
	    	    		$isDayOff = true;
	    	    	} elseif ($this->workingTime[$dayOfWeek . "_dayoff"] == 'F') {
	    	    		$step = $this->workingTime[$dayOfWeek . '_length'] * 60;
	    	    		$_to = strtotime($date . " " . $this->workingTime[$dayOfWeek . '_to']);
	    	    		$_from = strtotime($date . " " . $this->workingTime[$dayOfWeek . '_from']);
	    	    		$_offset = $_to <= $_from ? 86400 : 0;
	    	    		$_a = $_b = 0;
						for ($_i = $_from; $_i < $_to + $_offset; $_i = $_i + $step)
						{
							$booked = 0;
							foreach ($this->bookings[$date] as $bs)
							{
								if ($bs['start_ts'] == $_i && $bs['end_ts'] == $_i + $step)
								{
									$booked++;
								}
							}
							if ($booked > 0)
							{
								$hasBookedSlots = true;
							}
							$slot_limit = isset($this->datesOff[$date]['slot_limit']) ? $this->datesOff[$date]['slot_limit'] : $this->workingTime[$dayOfWeek . '_limit'];
							if ($booked < $slot_limit)
							{
								$e_arr1[] = date($this->options['time_format'], $_i) ." - ". date($this->options['time_format'], $_i + $step);
							} else {
								$_b++;
							}
							$_a++;
						}
						if ($_a == $_b)
						{
							$hasEmptySlots = false;
						}
	    	    	}
    	    	}
    	    	
    	    	$e_arr2 = array();
    	    	if (isset($this->datesOff[$date]) && count($this->datesOff[$date]) > 0)
    	    	{
    	    		if ($this->datesOff[$date]['is_dayoff'] == 'T')
    	    		{
    	    			$class = 'calendarDayoff';
    	    			$isDayOff = true;
    	    		} else {
    	    			$class = $oldClass;
    	    			$isDayOff = false;
    	    			$step = $this->datesOff[$date]['slot_length'] * 60;
    	    			$_a = $_b = 0;
    	    			$_offset = $this->datesOff[$date]['end_ts'] <= $this->datesOff[$date]['start_ts'] ? 86400 : 0;
						for ($_i = $this->datesOff[$date]['start_ts']; $_i < $this->datesOff[$date]['end_ts'] + $_offset; $_i = $_i + $step)
						{
							$booked = 0;
							foreach ($this->bookings[$date] as $bs)
							{
								if ($bs['start_ts'] == $_i && $bs['end_ts'] == $_i + $step)
								{
									$booked++;
								}
							}
							if ($booked > 0)
							{
								$hasBookedSlots = true;
							}
							$slot_limit = $this->datesOff[$date]['slot_limit'];
							if ($booked < $slot_limit)
							{
								$e_arr2[] = date($this->options['time_format'], $_i) ." - ". date($this->options['time_format'], $_i + $step);
							} else {
								$_b++;
							}
							$_a++;
						}
						if ($_a == $_b)
						{
							$hasEmptySlots = false;
						}
    	    		}
    	    	}
    	    	
    	    	if (!$isDayOff)
    	    	{
	    	    	if (!$hasEmptySlots && $hasBookedSlots)
	    	    	{
	    	    		$class = "calendarFull";
	    	    	} elseif ($hasBookedSlots) {
	    	    		$class = "calendar calendarPartly";
	    	    	}
    	    	}

    	    	if ($timestamp < $midnight && $d > 0 && $d <= $daysInMonth)
    	    	{
    	    		$class = "calendarPast";
    	    	}
    	    	
        	    if ($d < 1 || $d > $daysInMonth) {
        	    	$s .= '<td class="'.$class.'">';
        	    } else {
        	    	$s .= '<td class="'.$class.'" axis="'.$date.'" id="d'.$this->calendar_id.'_'.$timestamp.'">';
        	    }

    	        if ($d > 0 && $d <= $daysInMonth)
    	        {
            		$s .= '<p class="calendarLinkDate">'.$d.'</p>';
            		if ($showTooltip)
            		{
            			$e_arr = count($e_arr2) > 0 ? $e_arr2 : $e_arr1;
            			$tooltip_slots = '<div class="calendarTooltip" id="t_d'.$this->calendar_id.'_'.$timestamp.'">' . join("<br />", array_map("htmlspecialchars", $e_arr)) . '</div>';
            			$s .= $tooltip_slots;
            		}

    	        } else {
    	            $s .= "&nbsp;";
    	        }
      	        $s .= "</td>\n";
        	    $d++;
    	    }
    	    if ($this->weeknumbers)
    	    {
    	    	$s = str_replace('{WEEK_NUM}', date("W", $timestamp), $s);
    	    }
    	    $s .= "</tr>\n";
    	    $rows++;
    	}
    	
    	$emptyRow = "<tr>" . str_repeat('<td class="calendarEmpty">&nbsp;</td>', $cols) . "</tr>";
    	if ($rows == 5)
    	{
    		$s .= $emptyRow;
    	} elseif ($rows == 4) {
    		$s .= $emptyRow . $emptyRow;
    	}
    	
    	$s .= "</table>\n";

    	return $s;
    }	
}
?>
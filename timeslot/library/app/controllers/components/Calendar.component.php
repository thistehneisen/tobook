<?php
/**
 * Calendar component
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers.components
 */
class Calendar
{
/**
 * Hold start day (0-6)
 *
 * @access private
 * @var int
 */
    var $startDay = 0;
/**
 * Hold start month (1-12)
 *
 * @access private
 * @var int
 */
    var $startMonth = 1;
/**
 * Hold day names, e.g. Mon, Tue, Wed...
 *
 * @access private
 * @var array
 */
    var $dayNames = array("S", "M", "T", "W", "T", "F", "S");
/**
 * Hold month names, e.g. January, February...
 *
 * @access private
 * @var array
 */
    var $monthNames = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
/**
 * Days in month
 *
 * @access private
 * @var array
 */
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
/**
 * Whether to show next link
 *
 * @access private
 * @var bool
 */
    var $showNextLink = true;
/**
 * Whether to show prev link
 *
 * @access private
 * @var bool
 */
    var $showPrevLink = true;
/**
 *
 * @access private
 * @var string
 */
    var $weekTitle = "#";
/**
 * Anchor text of previous link
 *
 * @access private
 * @var string
 */
    var $prevLink = "&lt;";
/**
 * Anchor text of next link
 *
 * @access private
 * @var string
 */
    var $nextLink = "&gt;";
/**
 * Title attribute of prev link
 *
 * @access private
 * @var string
 */
    var $prevTitle = "Prev";
/**
 * Title attribute of next link
 *
 * @access private
 * @var string
 */
    var $nextTitle = "Next";
/**
 * Month year format
 *
 * @access private
 * @var string
 */
    var $monthYearFormat = "Month Year";
/**
 * Constructor
 */
    function Calendar()
    {
    	
    }
/**
 * Set title attribute of prev link
 *
 * @access public
 * @param string $value
 * @return void
 */
    function setPrevTitle($value)
	{
		$this->prevTitle = $value;
	}
/**
 * Get title attribute of prev link
 *
 * @access public
 * @return string
 */
	function getPrevTitle()
	{
		return $this->prevTitle;
	}
/**
 * Set title attribute of next link
 *
 * @access public
 * @param string $value
 * @return void
 */
	function setNextTitle($value)
	{
		$this->nextTitle = $value;
	}
/**
 * Get title attribute of next link
 *
 * @access public
 * @return string
 */
	function getNextTitle()
	{
		return $this->nextTitle;
	}
/**
 * Set anchor text of prev link
 *
 * @access public
 * @param string $value
 * @return void
 */
    function setPrevLink($value)
    {
    	$this->prevLink = $value;
    }
/**
 * Set anchor text of next link
 *
 * @access public
 * @param string $value
 * @return void
 */
	function setNextLink($value)
    {
    	$this->nextLink = $value;
    }
/**
 * Get anchor text of prev link
 *
 * @access public
 * @return string
 */
	function getPrevLink()
    {
    	return $this->prevLink;
    }
/**
 * Get anchor text of next link
 *
 * @access public
 * @return string
 */
	function getNextLink()
    {
    	return $this->nextLink;
    }
/**
 * Set whether to show next link or not
 *
 * @param bool $value
 * @access public
 * @return void
 */
    function setShowNextLink($value)
    {
    	if (is_bool($value))
    	{
    		$this->showNextLink = $value;
    	}
    }
/**
 * Whether to show next link or not
 *
 * @access public
 * @return bool
 */
    function getShowNextLink()
    {
    	return $this->showNextLink;
    }
/**
 * Set whether to show prev link or not
 *
 * @param bool $value
 * @access public
 * @return void
 */
	function setShowPrevLink($value)
    {
    	if (is_bool($value))
    	{
    		$this->showPrevLink = $value;
    	}
    }
/**
 * Whether to show next link or not
 *
 * @access public
 * @return bool
 */
    function getShowPrevLink()
    {
    	return $this->showPrevLink;
    }
/**
 * Get day names
 *
 * @access public
 * @return array
 */
    function getDayNames()
    {
        return $this->dayNames;
    }
/**
 * Set day names
 *
 * @param array $names
 * @access public
 * @return void
 */
    function setDayNames($names)
    {
        $this->dayNames = $names;
    }
/**
 * Get month names
 *
 * @access public
 * @return array
 */
    function getMonthNames()
    {
        return $this->monthNames;
    }
/**
 * Set month names
 *
 * @param array $names
 * @access public
 * @return void
 */
    function setMonthNames($names)
    {
        $this->monthNames = $names;
    }
/**
 * Get start day (0-6)
 *
 * @access public
 * @return int
 */
    function getStartDay()
    {
        return $this->startDay;
    }
/**
 * Set start day (0-6)
 *
 * @param int $day
 * @access public
 * @return void
 */
    function setStartDay($day)
    {
        $this->startDay = $day;
    }
/**
 * Get start month (1-12)
 *
 * @access public
 * @return int
 */
    function getStartMonth()
    {
        return $this->startMonth;
    }
/**
 * Set start month (1-12)
 *
 * @param int $month
 * @access public
 * @return void
 */
    function setStartMonth($month)
    {
        $this->startMonth = $month;
    }
/**
 * Get calendar link
 *
 * @param int $month
 * @param int $year
 * @access public
 * @return string
 */
    function getCalendarLink($month, $year)
    {
        return "";
    }
/**
 * Get date link
 *
 * @param int $day
 * @param int $month
 * @param int $year
 * @access public
 * @return string
 */
    function getDateLink($day, $month, $year)
    {
        return "";
    }
/**
 * Get current month view
 *
 * @access public
 * @return string
 */
    function getCurrentMonthView()
    {
        $d = getdate(time());
        return $this->getMonthView($d["mon"], $d["year"]);
    }
/**
 * Get month view
 *
 * @param int $month
 * @param int $year
 * @access public
 * @return string
 */
    function getMonthView($month, $year)
    {
        return $this->getMonthHTML($month, $year);
    }
    
    function getMonthHTML($m, $y, $showYear = 1)
    {
    	$s = "";
                
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

        	    if ($d < 1 || $d > $daysInMonth) {
        	    	$s .= '<td>';
        	    } else {
        	    	$s .= '<td>';
        	    }
    	               
    	        if ($d > 0 && $d <= $daysInMonth)
    	        {
            		$s .= '<p class="calendarLinkDate">'.$d.'</p>';
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
/**
 * Get number of days in given $month and $year
 *
 * @param int $month
 * @param int $year
 * @access public
 * @return int
 */
    function getDaysInMonth($month, $year)
    {
        if ($month < 1 || $month > 12)
        {
            return 0;
        }
   
        $d = $this->daysInMonth[$month - 1];
   
        if ($month == 2)
        {
            if ($year%4 == 0)
            {
                if ($year%100 == 0)
                {
                    if ($year%400 == 0)
                    {
                        $d = 29;
                    }
                } else {
                    $d = 29;
                }
            }
        }
    
        return $d;
    }
/**
 * Adjust date
 *
 * @param int $month
 * @param int $year
 * @access public
 * @return array
 * @static
 */
    function adjustDate($month, $year)
    {
        $a = array();
        $a[0] = $month;
        $a[1] = $year;
        
        while ($a[0] > 12)
        {
            $a[0] -= 12;
            $a[1]++;
        }
        
        while ($a[0] <= 0)
        {
            $a[0] += 12;
            $a[1]--;
        }
        
        return $a;
    }
}
?>
<?php
require_once dirname(__FILE__) . '/pjCalendar.component.php';
class pjASCalendar extends pjCalendar
{
	private $dates = array();
	
	public function __construct()
	{
		parent::__construct();
		
		$this->classTable = "asCalendarTable";
		$this->classWeekDay = "asCalendarWeekDay";
		$this->classMonth = "asCalendarMonth";
		$this->classMonthOuter = "asCalendarMonthOuter";
		$this->classMonthInner = "asCalendarMonthInner";
		$this->classMonthPrev = "asCalendarMonthPrev";
		$this->classMonthNext = "asCalendarMonthNext";
		$this->classPending = "asCalendarPending";
		$this->classReserved = "asCalendarReserved";
		$this->classCalendar = "asCalendarDate";
		$this->classCell = "asCalendarCell";
		$this->classToday = "asCalendarToday";
		$this->classSelected = "asCalendarSelected";
		$this->classEmpty = "asCalendarEmpty";
		$this->classWeekNum = "asCalendarWeekNum";
		$this->classPast = "asCalendarPast";
		$this->classDateOuter = "asCalendarDateOuter";
		$this->classDateInner = "asCalendarDateInner";
	}
	
	public function getMonthView($month, $year)
    {
        return $this->getMonthHTML($month, $year, 1);
    }
    
	public function getCalendarLink($month, $year)
	{
		return array('href' => '#', 'onclick' => '', 'class' => 'asCalendarLinkMonth');
	}
	
	public function get($key)
	{
		if (isset($this->$key))
		{
			return $this->$key;
		}
		return FALSE;
	}
	
	public function set($key, $value)
	{
		if (in_array($key, array('calendarId', 'weekNumbers', 'options', 'dates')))
		{
			$this->$key = $value;
		}
		return $this;
	}
	
	public function onBeforeShow($timestamp, $iso, $today, $current, $year, $month, $d)
    {
    	$date = getdate($timestamp);
    	
		if ($timestamp < $today[0])
		{
			$class = $this->classPast;
		} elseif (isset($this->dates[$iso]) && $this->dates[$iso] == 'OFF') {
			$class = $this->classReserved;
		} else {
			$class = $this->classCalendar;

			if ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"])
			{
				$class .= " " . $this->classToday;
			}
			if ($year == $current["year"] && $month == $current["mon"] && $d == $current["mday"])
			{
				$class .= " " . $this->classSelected;
			}
		}

		return $class;
    }
}
?>
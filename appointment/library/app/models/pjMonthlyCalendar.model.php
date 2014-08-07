<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}

class pjMonthlyCalendar {
	 
	/**
	 * Constructor
	 */
	public function __construct(){
		$this->naviHref = htmlentities($_SERVER['PHP_SELF']);
	}
	 
	/********************* PROPERTY ********************/
	private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
	 
	private $currentYear=0;
	 
	private $currentMonth=0;
	 
	private $currentDay=0;
	 
	private $currentDate=null;
	 
	private $daysInMonth=0;
	 
	private $naviHref= null;
	
	public $employees= array();
	
	public $booking_arr= array();
	
	public $currency = 0;
	
	public $t_arr = array();
	
	/********************* PUBLIC **********************/

	/**
	 * print out the calendar
	 */
	public function show($year, $month) {
		 
		$this->currentYear=$year;
		 
		$this->currentMonth=$month;
		 
		$this->daysInMonth=$this->_daysInMonth($month,$year);
		 
		$content='<div id="calendar">'.
				'<div class="box">'.
				$this->_createNavi().
				'</div>'.
				'<table class="box-content">'.
				'<thead class="label"><tr>'.$this->_createLabels().'</tr></thead>';
		$content.='<tbody class="dates">';
		 
		$weeksInMonth = $this->_weeksInMonth($month,$year);
		// Create weeks in a month
		for( $i=0; $i<$weeksInMonth; $i++ ){
			$content .= '<tr>';
			//Create days in a week
			for($j=1;$j<=7;$j++){
				$content.=$this->_showDay($i*7+$j);
			}
			$content .= '</tr>';
		}
		 
		$content.='</tbody>';
		 
		$content.='</table>';
		 
		$content.='</div>';
		return $content;
	}
	 
	/********************* PRIVATE **********************/
	/**
	 * create the li element for ul
	 */
	private function _showDay($cellNumber){
		 
		if($this->currentDay==0){
			 
			$firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
			 
			if(intval($cellNumber) == intval($firstDayOfTheWeek)){
				 
				$this->currentDay=1;
				 
			}
		}
		 
		if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
			 
			$this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
			 
			$cellContent = '';
			$cellContent .= '<span class="day_num">' . $this->currentDay . '</span>';
			 
			if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 ) {
				$employee_id = $_GET['employee_id'];
					
			} else $employee_id = $this->employees[0]['id'];
			
			$opening_hours = 0;
			if ( isset($this->t_arr[$this->currentDay]) && count($this->t_arr[$this->currentDay]) > 0 ) {
				$opening_hours = $this->t_arr[$this->currentDay]['end_ts'] - $this->t_arr[$this->currentDay]['start_ts'];
			}
			
			$price = 0;
			$amount = 0;
			$booking_hours = 0;
			if ( isset($this->booking_arr) && count($this->booking_arr) > 0 ) {
				
				foreach ($this->booking_arr as $booking) {
					
					if ( $booking['employee_id'] == $employee_id && $this->currentDate == $booking['date'] ) {
						$amount++;
						$price += $booking['price'];
						$booking_hours += $booking['total'];
					}
					
				}
			}
			
			$opening_hours = $opening_hours/60;
			
			if ($opening_hours > 0) {
				$cellContent .= '<p>'.__('lblRevenue', true, false).' <span>'. $price . $this->currency . '</span></p>';
				$cellContent .= '<p>'.__('lblNumOfBook', true, false).' <span>'. $amount . '</span></p>';
				$cellContent .= '<p>'.__('lblWorkingTime', true, false).' <span>'. floor($opening_hours/60) . 'h';
				$cellContent .= $opening_hours%60 < 10 ? '0' . $opening_hours%60 : $opening_hours%60;
				$cellContent .= '</span></p>';
				$cellContent .= '<p>'.__('lblBookingHours', true, false).' <span>'.  floor($booking_hours/60). 'h';
				$cellContent .= $booking_hours%60 < 10 ? '0' . $booking_hours%60 : $booking_hours%60;
				$cellContent .= '</span></p>';
				$cellContent .= '<p>'.__('lblBookingRate', true, false).' <span>'. round($booking_hours*100/$opening_hours, 2) . '%</span></p>';
			
			} else {
				$cellContent .= '<p>'.__('lblRevenue', true, false).' <span>0' . $this->currency . '</span></p>';
				$cellContent .= '<p>'.__('lblNumOfBook', true, false).' <span>0</span></p>';
				$cellContent .= '<p>'.__('lblWorkingTime', true, false).' <span>0h00</span></p>';
				$cellContent .= '<p>'.__('lblBookingHours', true, false).' <span>0h00</span></p>';
				$cellContent .= '<p>'.__('lblBookingRate', true, false).' <span>0%</span></p>';
			}
			
			$this->currentDay++;
			 
		}else{
			 
			$this->currentDate =null;

			$cellContent=null;
		}
		 
		 
		return '<td id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).
		($cellContent==null?'mask':'').'"><div class="day">'.$cellContent.'</div></td>';
	}
	 
	/**
	 * create navigation
	 */
	private function _createNavi(){
		$html = '';
		
		$nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
		 
		$nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
		 
		$preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
		 
		$preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
		 
		$html .= '<div class="header">'.
					'<a class="prev calendar-control" href="' . $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&action=pjActionGetCalendar&as_pf=' . PREFIX . '&month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">'.__('btnPrev', true, false).'</a>'.
					'<span class="title">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
					'<a class="next calendar-control" href="' . $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&action=pjActionGetCalendar&as_pf=' . PREFIX . '&month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">'.__('btnNext', true, false).'</a>'.
				'</div>';
		$html .= '<select name="employee_id" id="employee_id">';
		
		foreach($this->employees as $employee ) {
			if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 && $_GET['employee_id'] == $employee['id'] ) {
				$selected = 'selected="selected"';
					
			} else $selected = '';
				
			$html .= '<option '. $selected .' value="' . $employee['id'] . '">' . $employee['name'] . '</option>';
		}
		
		$html .= '</select>';
				
		return $html;
	}
	 
	/**
	 * create calendar week labels
	 */
	private function _createLabels(){
		 
		$content='';
		 
		foreach($this->dayLabels as $index=>$label){
			 
			$content.='<th class="'.($label==6?'end title':'start title').' title">'.$label.'</th>';

		}
		 
		return $content;
	}
	 
	 
	 
	/**
	 * calculate number of weeks in a particular month
	 */
	private function _weeksInMonth($month=null,$year=null){
		 
		if( null==($year) ) {
			$year =  date("Y",time());
		}
		 
		if(null==($month)) {
			$month = date("m",time());
		}
		 
		// find number of days in this month
		$daysInMonths = $this->_daysInMonth($month,$year);
		 
		$numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
		 
		$monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
		 
		$monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
		 
		if($monthEndingDay<$monthStartDay){
			 
			$numOfweeks++;
			 
		}
		 
		return $numOfweeks;
	}

	/**
	 * calculate number of days in a particular month
	 */
	private function _daysInMonth($month=null,$year=null){
		 
		if(null==($year))
			$year =  date("Y",time());

		if(null==($month))
			$month = date("m",time());
		 
		return date('t',strtotime($year.'-'.$month.'-01'));
	}
	 
}
?>
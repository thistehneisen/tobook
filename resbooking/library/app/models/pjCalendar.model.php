<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}

class pjCalendarModel {
	 
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
	
	public $services= array();
	
	public $booking_arr= array();
	
	public $s_seats = 0;
	
	public $currency = 0;
	
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
			 
			if ( isset($this->services) && count($this->services) > 0 ) {
				
				$services = $this->services;
				
				foreach ($this->services as $key => $service) {
					$services[$key]['people'] = 0;
					
					$start_time = strtotime($service['start_time']);
					$end_time = strtotime($service['end_time']);
					
					$fday = strtotime($this->currentDate . ' 00:00:00');
					$lday = strtotime($this->currentDate . ' 23:59:59');
					
					if ( isset($this->booking_arr) && count($this->booking_arr) > 0 ) {
						
						foreach ($this->booking_arr as $booking) {
							$dt = strtotime($booking['dt']);
							$dt_time = date('H:i:s', $dt);
							$dt_time = strtotime($dt_time);
							
							if ( $dt >= $fday && $dt <= $lday && $dt_time >= $start_time && $dt_time <= $end_time ) {
								$services[$key]['people'] += $booking['people'];
								
							}
						}
					}
					
				}
				
				$price = 0;
				foreach ( $services as $service ) {
					$price += $service['people']*$service['s_price'];
					$cellContent .= '<p class="service">' . $service['s_name'] . '<span>' . $service['people'] . ' / ' . $this->s_seats . '<span></p>';
				}
				 
				$cellContent .= '<p class="service">Revenue <span>'. $price . ' ' . $this->currency . '</span></p>';
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
		 
		$nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
		 
		$nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
		 
		$preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
		 
		$preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
		 
		return
		'<div class="header">'.
		'<a class="prev calendar-control" href="' . $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&action=calendar&month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
		'<span class="title">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
		'<a class="next calendar-control" href="' . $_SERVER['PHP_SELF'] . '?controller=pjAdminBookings&action=calendar&month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
		'</div>';
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
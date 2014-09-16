<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}

class pjFront extends pjAppController
{
	public $defaultForm = 'AppSched_Form';
	
	public $defaultCaptcha = 'AppSched_Captcha';
	
	public $defaultCart = 'AppSched_Cart';
	
	public $defaultLocale = 'AppSched_LocaleId';
	
	public $cart = NULL;
	
	public function __construct()
	{
        parent::__construct();
		$this->defaultForm = 'AppSched_Form_' . PREFIX;
		$this->defaultCaptcha = 'AppSched_Captcha_' . PREFIX;
		$this->defaultCart = 'AppSched_Cart_' . PREFIX;
		$this->defaultLocale = 'AppSched_LocaleId_' . PREFIX;
		
		$this->setLayout('pjActionFront');
		
		if (!isset($_SESSION[$this->defaultCart]))
		{
			$_SESSION[$this->defaultCart] = array();
		}
		
		$this->cart = new pjCart($_SESSION[$this->defaultCart]);
	}
	
	public function afterFilter()
	{
		pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
		$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file, t2.title')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
			->where('t2.file IS NOT NULL')
			->orderBy('t1.sort ASC')->findAll()->getData();
		
		$this->set('locale_arr', $locale_arr);
	}
	
	public function beforeFilter()
	{
		$owner_id = $this->getOwnerId();
		$pjOptionModel = pjOptionModel::factory();
		$this->option_arr = $pjOptionModel->getPairs($owner_id);
		$this->set('option_arr', $this->option_arr);
		$this->setTime();
		if (isset($_GET['locale']) && (int) $_GET['locale'] > 0)
		{
			$this->pjActionSetLocale($_GET['locale']);
		}
		
		if ($this->pjActionGetLocale() === FALSE)
		{
			pjObject::import('Model', 'pjLocale:pjLocale');
			$locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
			if (count($locale_arr) === 1)
			{
				$this->pjActionSetLocale($locale_arr[0]['id']);
			}
		}
		pjAppController::setFields($this->getLocaleId());

		if (!isset($_SESSION[$this->defaultForm]))
		{
			$_SESSION[$this->defaultForm] = array(
				'date_from' => NULL,
				'date_to' => NULL,
				'hour_from' => NULL,
				'hour_to' => NULL,
				'minute_from' => NULL,
				'minute_to' => NULL
			);
		}
	}

	public function beforeRender()
	{
		
	}
	
	private function pjActionSetLocale($locale)
	{
		if ((int) $locale > 0)
		{
			$_SESSION[$this->defaultLocale] = (int) $locale;
		}
		return $this;
	}
	
	protected function pjActionGetLocale()
	{
		return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : FALSE;
	}

	protected function getValidate($summary)
	{
		if ($this->cart->isEmpty())
		{
			return false;
		}

		if (!isset($summary['services']) || empty($summary['services']))
		{
			return false;
		}

        $pjBookingServiceModel = pjBookingServiceModel::factory();

		foreach ($summary['services'] as $service)
		{
            $existed_bookings = $pjBookingServiceModel->reset()
                    ->select('t1.*')
                    ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                    ->join('pjService', 't3.id=t1.service_id', 'inner')
                    ->where('t1.service_id', $service['id'])
                    ->where('t1.employee_id', $service['employee_id'])
                    ->where('t1.date', $service['date'])
                    ->where('t1.start', $service['start'])
                    ->findAll()
                    ->getData();

            if(empty($existed_bookings)){
                $existed_bookings = $pjBookingServiceModel->reset()
                    ->select("UNIX_TIMESTAMP(CONCAT(t1.date, ' ', t1.start)) as start_time,  UNIX_TIMESTAMP(CONCAT(t1.date, ' ', t1.start)) + (t1.total*60) as end_time")
                    ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                    ->join('pjService', 't3.id=t1.service_id', 'inner')
                    ->where('t1.service_id', $service['id'])
                    ->where('t1.employee_id', $service['employee_id'])
                    ->where(sprintf("((%s < UNIX_TIMESTAMP(CONCAT(t1.date, ' ', t1.start)) AND %s > UNIX_TIMESTAMP(CONCAT(t1.date, ' ', t1.start))) OR (%s < (UNIX_TIMESTAMP(CONCAT(t1.date, ' ', t1.start)) + (t1.total*60)) AND %s > (UNIX_TIMESTAMP(CONCAT(t1.date, ' ', t1.start)) + (t1.total*60))))", $service['start_ts'], $service['end_ts'], $service['start_ts'], $service['end_ts']))
                    ->findAll()
                    ->getData();
            }

            if(!empty($existed_bookings)){
                return false;
            }
		}
		// Pass all checks
		return true;
	}
	
	protected function getSummary()
	{
		$FORM = @$_SESSION[$this->defaultForm];
		$tax = $total = $deposit = $price = 0;
		$service_ids = $services = $service_arr = array();
		
		$cart = $this->cart->getAll();
		foreach ($cart as $key => $qty)
		{
			list($cid, $date, $service_id, $start_ts, $end_ts, $employee_id) = explode("|", $key);
			$service_ids[] = $service_id;
		}
		
		if (!empty($service_ids))
		{
			$service_arr = pjServiceModel::factory()
				->select("t1.*, t2.content AS `name`")
				->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
				->whereIn('t1.id', $service_ids)
				->groupBy('t1.id')
				->findAll()
				->getDataPair('id');
		}
		
		foreach ($cart as $key => $qty)
		{
			list($cid, $date, $service_id, $start_ts, $end_ts, $employee_id, $wt_id) = explode("|", $key);
			
			if (isset($wt_id) && $wt_id > 0 ) {
					
				$wt_arr = pjServiceTimeModel::factory()
					->select('t1.*')
					->where('t1.id', $wt_id)
					->find($wt_id)
					->getData();
			
				$service_arr[$service_id]['price'] = $wt_arr['price'];
				$service_arr[$service_id]['length'] = $wt_arr['length'];
				$service_arr[$service_id]['before'] = $wt_arr['before'];
				$service_arr[$service_id]['after'] = $wt_arr['after'];
				$service_arr[$service_id]['total'] = $wt_arr['total'];
				$service_arr[$service_id]['wt_id'] = $wt_id;
			}
			
			$employee_plustime = pjEmployeeModel::factory()
					->select('t1.*, t2.content AS `name`, t3.plustime')
					->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->join('pjEmployeeService', 't3.employee_id = t1.id', 'inner')
					->where('t3.service_id', $service_id)
					->where('t3.employee_id', $employee_id)
					->where('t1.is_active', 1)
					->orderBy('`name` ASC')
					->findAll()
					->getData();
			
			foreach ( $employee_plustime as $employee ) {
                $service_arr[$service_id]['employee_id'] = (int) $employee['id'];
                if ( isset($employee['plustime']) && (int) $employee['plustime'] != 0 ) {
                    $service_arr[$service_id]['total'] += (int) $employee['plustime'];
				}
			}
			
			$extra = array('length' => 0, 'price' => 0);
			if ( is_array($qty) && count($qty) > 0 ) {
				$service_arr[$service_id]['extra'] = $qty;
				foreach ( $qty as $_extra) {
					$extra['length'] += $_extra['length'];
					$extra['price'] += $_extra['price'];
				}
			}
			
			$service_arr[$service_id]['total'] += $extra['length'];
			$service_arr[$service_id]['price'] += $extra['price'];
			
			$price += @$service_arr[$service_id]['price'];
			
			$start = date("H:i:s", (int) $start_ts);
			$services[] = array_merge(@$service_arr[$service_id], compact('date', 'start', 'start_ts', 'end_ts', 'employee_id'));
		}
		
		if ((float) $this->option_arr['o_tax'] > 0)
		{
			$tax = ($price * (float) $this->option_arr['o_tax']) / 100;
		}
		$total = $price + $tax;
		switch ($this->option_arr['o_deposit_type'])
		{
			case 'percent':
				$deposit = ($total * (float) $this->option_arr['o_deposit']) / 100;
				break;
			case 'amount':
				$deposit = (float) $this->option_arr['o_deposit'];
				break;
		}
		
		return compact('cart', 'services', 'tax', 'total', 'deposit', 'price');
	}

	protected function getCalendar($cid, $year=null, $month=null, $day=null, $owner_id = null)
	{
		list($y, $n, $j) = explode("-", date("Y-n-j"));
		$year = is_null($year) ? $y : $year;
		$month = is_null($month) ? $n : $month;
		//$day = is_null($day) ? $j : $day;
		
		pjObject::import('Component', 'pjASCalendar');
		$pjASCalendar = new pjASCalendar();
		
		$pjASCalendar
			->setPrevLink("&nbsp;")
			->setNextLink("&nbsp;")
			->setStartDay($this->option_arr['o_week_start'])
			->setWeekNumbers($this->option_arr['o_week_numbers'])
			->set('dates', pjAppController::getDatesInRange($_GET['cid'], date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)), date("Y-m-d", mktime(0, 0, 0, $month+1, 0, $year))));
			
		if (!is_null($day))
		{
			$pjASCalendar->setCurrentDate(mktime(0, 0, 0, $month, $day, $year));
		}
		
		return $pjASCalendar;
	}
	
	protected function getCart($cid)
	{
		$service_arr = array();
		if (!$this->cart->isEmpty())
		{
			$cart = $this->cart->getAll();
			$service_ids = $employee_ids = array();
			foreach ($cart as $key => $value)
			{
				list($cid, $date, $service_id, $start_ts, $end_ts, $employee_id) = explode("|", $key);
				$service_ids[] = $service_id;
				$employee_ids[] = $employee_id;
			}
			$service_ids = array_unique($service_ids);
			$employee_ids = array_unique($employee_ids);
			if (!empty($service_ids))
			{
				$service_arr = pjServiceModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
					->whereIn('t1.id', $service_ids)
					// ->where('t1.calendar_id', $cid)
					->findAll()
					->getDataPair('id');
			}
			$employee_arr = array();
			if (!empty($employee_ids))
			{
				$employee_arr = pjEmployeeModel::factory()
					->select('t1.id, t1.email, t1.phone, t1.avatar, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
					->whereIn('t1.id', $employee_ids)
					// ->where('t1.calendar_id', $cid)
					->findAll()
					->getDataPair('id');
			}
			
			foreach ($cart as $key => $value)
			{
				list($cid, $date, $service_id, $start_ts, $end_ts, $employee_id, $wt_id) = explode("|", $key);
				
				if (isset($service_arr[$service_id]))
				{
					if (isset($wt_id) && $wt_id > 0 ) {
					
						$wt_arr = pjServiceTimeModel::factory()
							->select('t1.*')
							->where('t1.id', $wt_id)
							->find($wt_id)
							->getData();
						
						$service_arr[$service_id]['price'] = $wt_arr['price'];
						$service_arr[$service_id]['length'] = $wt_arr['length'];
						$service_arr[$service_id]['before'] = $wt_arr['before'];
						$service_arr[$service_id]['after'] = $wt_arr['after'];
						$service_arr[$service_id]['total'] = $wt_arr['total'];
						$service_arr[$service_id]['wt_id'] = $wt_id;
					}
					
					if (!isset($service_arr[$service_id]['employee_arr']))
					{
						$service_arr[$service_id]['employee_arr'] = array();
					}
					$service_arr[$service_id]['employee_arr'][$employee_id] = @$employee_arr[$employee_id];
				}
			}
		}
		
		return $service_arr;
	}
	
	protected function getServices($cid, $page=1, $owner_id = null)
	{
        $pjServiceModel = pjServiceModel::factory();

		$data = pjServiceModel::factory()->select("t1.*, t2.content AS `name`, t3.content AS `description`")
			->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
			->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='description'", 'left outer')
			// ->where('t1.calendar_id', $cid)
			->where('t1.is_active', 1)
			->orderBy('`name` ASC')
			->findAll()
			->getData();
		
		$services = $data;
		foreach ($data as $k => $service) {
            if($owner_id == null){
                $services[$k]['extra_count'] = pjServiceExtraServiceModel::factory()->where('service_id', $service['id'])->findCount()->getData();
            } else {
                $services[$k]['extra_count'] = pjServiceExtraServiceModel::factory()->disableOwnerID()->where('service_id', $service['id'])->findCount()->getData();
            }
		}
		
		$data = $services;
		
		return compact('data');
	}
	
	protected function getemployees($cid, $page=1, $owner_id = null)
	{
		$employeeModel = null;

        $employeeModel = pjEmployeeModel::factory()
            ->select("t1.*, t2.content AS `name`")
            ->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
            ->where('t1.is_active', 1)
            ->orderBy('name ASC')
            ->findAll();
        
		
		$employee_arr = $employeeModel->getData();
		$employee_ids = $employeeModel->getDataPair(null, 'id');

		$bs_arr = array();
		if (isset($employee_arr) && count($employee_arr) > 0) {
			
			$bs_arr = pjBookingServiceModel::factory()
				->join('pjBooking', "t1.booking_id=t2.id AND t2.booking_status='confirmed'", 'inner')
				->whereIn('t1.employee_id', $employee_ids)
				->where('t1.date', $_GET['date'])
				->findAll()
				->getData();
		
			foreach ($employee_arr as $k => $employee)
			{
				$employee_arr[$k]['t_arr'] = pjAppController::getRawSlotsPerEmployee($employee['id'], $_GET['date'], $_GET['cid']);
				$employee_arr[$k]['bs_arr'] = array();
				foreach ($bs_arr as $item)
				{
					if ($item['employee_id'] != $employee['id'])
					{
						continue;
					}
					$employee_arr[$k]['bs_arr'][] = $item;
				}
			}
	
		}
		
		return $employee_arr;
	}
}
/*
$pjFront = new pjFront();

$pjFront->defaultForm = 'AppSched_Form_' . PREFIX;
$pjFront->defaultCaptcha = 'AppSched_Captcha_' . PREFIX;
$pjFront->defaultCart = 'AppSched_Cart_' . PREFIX;
$pjFront->defaultLocale = 'AppSched_LocaleId_' . PREFIX;
*/
?>

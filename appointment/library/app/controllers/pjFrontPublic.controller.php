<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontPublic extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionCart()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			$this->set('cart_arr', $this->getCart($_GET['cid']));
		}
	}
	
	public function pjActionCheckout()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if ((int) $this->option_arr['o_accept_bookings'] === 0)
			{
				$this->set('status', 'ERR');
				$this->set('code', '103'); //Bookings are disabled
				return;
			}
			
			if ($this->cart->isEmpty())
			{
				$this->set('status', 'ERR');
				$this->set('code', '101'); //Empty cart
				return;
			}
			
			if (isset($_POST['as_checkout']))
			{
				$_SESSION[$this->defaultForm] = array_merge($_SESSION[$this->defaultForm], $_POST);
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 211, 'text' => __('system_211', true)));
			}
			
			if (in_array($this->option_arr['o_bf_country'], array(2,3)))
			{
				pjObject::import('Model', 'pjCountry:pjCountry');
				$this->set('country_arr', pjCountryModel::factory()
					->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->where('t1.status', 'T')
					->orderBy('`name` ASC')
					->findAll()
					->getData()
				);
			}
			
			$this->set('status', 'OK');
			//$this->set('summary', $this->getSummary());
			$this->set('cart_arr', $this->getCart($_GET['cid']));
		}
	}
	
	public function pjActionService()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$id = (int) $_GET['id'];
			} elseif (isset($_GET['_escaped_fragment_'])) {
				preg_match('/\/Service\/(\d+)/', $_GET['_escaped_fragment_'], $matches);
				if (isset($matches[1]))
				{
					$id = $matches[1];
				}
			}
			
			list($year, $month, $day) = explode("-", $_GET['date']);
			$this->set('calendar', $this->getCalendar($_GET['cid'], $year, $month, $day));
			
			$pjEmployeeServiceModel = pjEmployeeServiceModel::factory()
				->select("t1.*, t2.avatar, t2.notes, t3.content AS `name`")
				->join('pjEmployee', 't2.id=t1.employee_id AND t2.is_active=1', 'inner')
				->join('pjMultiLang', "t3.model='pjEmployee' AND t3.foreign_id=t1.employee_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.service_id', $id)
				->orderBy('`name` ASC')
				->findAll();
			
			$employee_arr = $pjEmployeeServiceModel->getData();
			$employee_ids = $pjEmployeeServiceModel->getDataPair(null, 'employee_id');
			$bs_arr = array();
			if (!empty($employee_ids))
			{
				$bs_arr = pjBookingServiceModel::factory()
					->join('pjBooking', "t1.booking_id=t2.id AND t2.booking_status='confirmed'", 'inner')
					->whereIn('t1.employee_id', $employee_ids)
					->where('t1.date', $_GET['date'])
					->findAll()
					->getData();
			}

			foreach ($employee_arr as $k => $employee)
			{
				$employee_arr[$k]['t_arr'] = pjAppController::getRawSlotsPerEmployee($employee['employee_id'], $_GET['date'], $_GET['cid']);
				
				$employee_arr[$k]['ef_arr'] = pjEmployeeFreetimeModel::factory()
					->where('t1.employee_id', $employee['employee_id'])
					->where('t1.date', $_GET['date'])
					->findAll()
					->getData();
				$employee_arr[$k]['bs_arr'] = array();
				foreach ($bs_arr as $item)
				{
					if ($item['employee_id'] != $employee['employee_id'])
					{
						continue;
					}
					$employee_arr[$k]['bs_arr'][] = $item;
				}
			}

			$pjResourcesServiceModel = pjResourcesServiceModel::factory()
				->select("t1.*, t2.name, t2.message")
				->join('pjResources', 't2.id=t1.resources_id', 'inner')
				->where('t1.service_id', $id)
				->orderBy('`name` ASC')
				->findAll();
				
			$resources_ids = $pjResourcesServiceModel->getDataPair(null, 'resources_id');
			$resources_arr = array();
			$resources_arr['count'] = count($resources_ids);
			if (!empty($resources_ids))
			{
				$resources_arr['bs_arr'] = pjBookingServiceModel::factory()
				->join('pjBooking', "t1.booking_id=t2.id AND t2.booking_status='confirmed'", 'inner')
				->whereIn('t1.resources_id', $resources_ids)
				->where('t1.date', $_GET['date'])
				->findAll()
				->getData();
			}
			
			$service_arr = pjServiceModel::factory()
								->select('t1.*, t2.content AS `name`')
								->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
								->find($id)
								->getData();
							
			if ( isset($_GET['wt_id']) && $_GET['wt_id'] >= 0 ) {
				
				$wt_id = $_GET['wt_id'];
				
				if (isset($_GET['as_pf'])) {
					$_SESSION[ $_GET['as_pf'] . 'wt_id'] = $_GET['wt_id'];
				}
				
			} elseif ( isset($_GET['as_pf']) && isset($_SESSION[ $_GET['as_pf'] . 'wt_id']) && $_SESSION[ $_GET['as_pf'] . 'wt_id'] > 0 ) {
				$wt_id = $_SESSION[ $_GET['as_pf'] . 'wt_id'];
			}
			
			if ( isset($wt_id) && $wt_id > 0 ) {
				
				$wt_arr = pjServiceTimeModel::factory()
				->select('t1.*')
				->where('t1.id', $wt_id)
				->find($wt_id)
				->getData();
				
				$service_arr['price'] = $wt_arr['price'];
				$service_arr['length'] = $wt_arr['length'];
				$service_arr['before'] = $wt_arr['before'];
				$service_arr['after'] = $wt_arr['after'];
				$service_arr['total'] = $wt_arr['total'];
				$service_arr['wt_id'] = $wt_id;
			}
			
			$this
				->set('service_arr', $service_arr)
				->set('employee_arr', $employee_arr)
				->set('resources_arr', $resources_arr)
				->set('cart_arr', $this->getCart($_GET['cid']));
		}
	}
	
	public function pjActionEmployee() {
		
		if ($this->isXHR())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$id = (int) $_GET['id'];
			
				list($year, $month, $day) = explode("-", $_GET['date']);
				$this
					->set('calendar', $this->getCalendar($_GET['cid'], $year, $month, $day))
					->set('cart_arr', $this->getCart($_GET['cid']))
					->set('service_arr', pjServiceModel::factory()
						->select("t1.*, t2.content AS `name`, t3.content AS `description`")
						->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.locale='".$this->getLocaleId()."' AND t2.field='name'", 'left outer')
						->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.locale='".$this->getLocaleId()."' AND t3.field='description'", 'left outer')
						->join('pjEmployeeService', 't1.id=t4.service_id', 'inner')
						->where('t1.is_active', 1)
						->where('t4.employee_id', $id)
						->orderBy('`name` ASC')
						->findAll()
						->getData()
						);
			}
		}
	}
	
	public function pjActionServices()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if (isset($_GET['_escaped_fragment_']))
			{
				preg_match('/\/Services\/date:([\d\-\.\/]+)?\/page:(\d+)?/', $_GET['_escaped_fragment_'], $matches);
				if (!empty($matches))
				{
					$date = $matches[1];
					$page = $matches[2];
				}
			} else {
				$date = @$_GET['date'];
				$page = @$_GET['page'];
			}
			
			$year = $month = $day = NULL;
			if (!empty($date))
			{
				list($year, $month, $day) = explode("-", $date);
			}
			
			$this->set('calendar', $this->getCalendar($_GET['cid'], $year, $month, $day))
				->set('cart_arr', $this->getCart($_GET['cid']));
			$owner_id = intval($_GET['owner_id']);
			$this->set('category_arr', 
					pjServiceCategoryModel::factory()
					->where('t1.show_front', 'on')
					->where('owner_id', $owner_id)
					->orderBy('t1.name ASC')
					->findAll()
					->getData()
				);
			
			if ( isset($_SESSION[ PREFIX . 'extra' ]) ) {
				unset($_SESSION[ PREFIX . 'extra' ]);
			}
			
			switch ($_GET['layout'])
			{
				case 2:
					$this->set('service_arr', $this->getServices($_GET['cid'], @$page));
					
					$this->set('next_dates', pjAppController::getDatesInRange($_GET['cid'], date("Y-m-d", strtotime("+1 day")), date("Y-m-d", strtotime("+8 day"))));
					$this->set('t_arr', pjAppController::getSingleDateSlots($_GET['cid'], $date));
					$this->setTemplate('pjFrontPublic', 'pjActionSingle');
					break;
					
				case 3:
					
					$this->set('employee_arr', $this->getemployees($_GET['cid'], @$page));
					
					$this->setTemplate('pjFrontPublic', 'pjActionEmployees');
					break;
					
				case 1:
				default:
					
					$data = $this->getServices($_GET['cid'], @$page);
					
					foreach ( $data['data'] as $k => $v ) {
						
						$data['data'][$k]['wtime'] = pjServiceTimeModel::factory()->select("t1.*")
												->where('t1.foreign_id', $v['id'])
												->findAll()
												->getData();
											}
					
					$this->set('service_arr', $data);
					
					$this->setTemplate('pjFrontPublic', 'pjActionServices');
					break;
			}
		}
	}
	
	public function pjActionBooking()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if ((int) $this->option_arr['o_accept_bookings'] === 0)
			{
				$this->set('status', 'ERR');
				$this->set('code', '103'); //Bookings are disabled
				return;
			}
			
			$this->set('status', 'OK');
			
			if (isset($_GET['booking_uuid']) && !empty($_GET['booking_uuid']))
			{
				$booking_uuid = $_GET['booking_uuid'];
			} elseif (isset($_GET['_escaped_fragment_'])) {
				preg_match('/\/Booking\/([A-Z]{2}\d{10})/', $_GET['_escaped_fragment_'], $matches);
				if (isset($matches[1]))
				{
					$booking_uuid = $matches[1];
				}
			}
			
			$booking_arr = pjBookingModel::factory()->where('t1.uuid', $booking_uuid)->findAll()->limit(1)->getData();
			if (!empty($booking_arr))
			{
				$booking_arr = $booking_arr[0];
				
				pjObject::import('Model', 'pjInvoice:pjInvoice');
				$invoice_arr = pjInvoiceModel::factory()->where('t1.order_id', $booking_uuid)->findAll()->limit(1)->getData();
				if (!empty($invoice_arr))
				{
					$invoice_arr = $invoice_arr[0];
					
					switch ($booking_arr['payment_method'])
					{
						case 'paypal':
							$this->set('params', array(
								'name' => 'asPaypal',
								'id' => 'asPaypal',
								'target' => '_self',
								'business' => $this->option_arr['o_paypal_address'],
								'item_name' => $booking_arr['uuid'],
								'custom' => $invoice_arr['uuid'],
								'amount' => $invoice_arr['total'],
								'currency_code' => $invoice_arr['currency'],
								'return' => $this->option_arr['o_thankyou_page'],
								'notify_url' => PJ_INSTALL_URL . 'index.php?controller=pjFrontEnd&action=pjActionConfirmPaypal',
								'submit' => __('payment_paypal_submit', true),
								'submit_class' => 'asSelectorButton asButton asButtonGreen'
							));
							break;
						case 'authorize':
							$this->set('params', array(
								'name' => 'asAuthorize',
								'id' => 'asAuthorize',
								'target' => '_self',
								'timezone' => $this->option_arr['o_authorize_tz'],
								'transkey' => $this->option_arr['o_authorize_key'],
								'x_login' => $this->option_arr['o_authorize_mid'],
								'x_description' => $booking_arr['uuid'],
								'x_amount' => $invoice_arr['total'],
								'x_invoice_num' => $invoice_arr['uuid'],
								'x_receipt_link_url' => $this->option_arr['o_thankyou_page'],
								'x_relay_url' => PJ_INSTALL_URL . 'index.php?controller=pjFrontEnd&action=pjActionConfirmAuthorize',
								'submit' => __('payment_authorize_submit', true),
								'submit_class' => 'asSelectorButton asButton asButtonGreen'
							));
							break;
					}
					
					$this->set('booking_arr', $booking_arr);
					$this->set('invoice_arr', $invoice_arr);
				}
			}
		}
	}
	
	public function pjActionPreview()
	{
		if ($this->isXHR() || isset($_GET['_escaped_fragment_']))
		{
			if ((int) $this->option_arr['o_accept_bookings'] === 0)
			{
				$this->set('status', 'ERR');
				$this->set('code', '103'); //Bookings are disabled
				return;
			}
			
			if ($this->cart->isEmpty())
			{
				$this->set('status', 'ERR');
				$this->set('code', '101'); //Empty cart
				return;
			}
			
			if (!isset($_SESSION[$this->defaultForm]) || empty($_SESSION[$this->defaultForm]))
			{
				$this->set('status', 'ERR');
				$this->set('code', '102'); //Checkout form not filled
				return;
			}
			
			if (in_array($this->option_arr['o_bf_country'], array(2,3)) && (int) @$_SESSION[$this->defaultForm]['c_country_id'] > 0)
			{
				pjObject::import('Model', 'pjCountry:pjCountry');
				$this->set('country_arr', pjCountryModel::factory()
					->select('t1.*, t2.content AS name')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->find($_SESSION[$this->defaultForm]['c_country_id'])
					->getData()
				);
			}
			$this->set('status', 'OK');
			//$this->set('summary', $this->getSummary());
			$this->set('cart_arr', $this->getCart($_GET['cid']));
		}
	}
		
	public function pjActionRouter()
	{
		$this->setAjax(false);

		if (isset($_GET['_escaped_fragment_']))
		{
			$templates = array('Checkout', 'Preview', 'Service', 'Services', 'Booking', 'Cart');
			preg_match('/^\/(\w+).*/', $_GET['_escaped_fragment_'], $m);
			if (isset($m[1]) && in_array($m[1], $templates))
			{
				$template = 'pjAction'.$m[1];
			
				if (method_exists($this, $template))
				{
					$this->$template();
				}
				$this->setTemplate('pjFrontPublic', $template);
			}
		}
	}
	
	public function getExtraService() {
		if ( $this->isXHR() ) {
			
			if ( isset($_POST['extra_id']) && isset($_POST['service_id']) ) {
				
				$extra = pjExtraServiceModel::factory()
							->whereIn('id', $_POST['extra_id'])
							->orderBy('t1.name ASC')
							->findAll()
							->getData();
				
				$_SESSION[ PREFIX . 'extra' ] = $extra;
				
				exit();
				
			} elseif ( isset($_GET['service_id']) && $_GET['service_id'] > 0 ) {
				$this->set('extra_arr', pjExtraServiceModel::factory()
						->join('pjServiceExtraService', 't2.extra_id = t1.id', 'inner')
						->where('t2.service_id', $_GET['service_id']) 
						->orderBy('t1.name ASC')
						->findAll()
						->getData()
				);
				
			} else exit();
		}
	}
}
?>

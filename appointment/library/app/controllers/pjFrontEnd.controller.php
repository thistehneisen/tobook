<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontEnd extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionAddToCart()
	{
		if ($this->isXHR())
		{
			if (isset($_GET['cid']) && isset($_POST['date']) && isset($_POST['start_ts']) && isset($_POST['end_ts']) && isset($_POST['service_id']) && isset($_POST['employee_id']))
			{
				
				$key = sprintf("%u|%s|%u|%s|%s|%u|%u", $_GET['cid'], $_POST['date'], $_POST['service_id'], $_POST['start_ts'], $_POST['end_ts'], $_POST['employee_id'], $_POST['wt_id']);
				
				# Remove services at same date
				$cart = $this->cart->getAll();
				foreach ($cart as $cart_key => $whatever)
				{
					$pattern = sprintf('/^%u\|%s\|%u/', $_GET['cid'], $_POST['date'], $_POST['service_id']);
					if (preg_match($pattern, $cart_key))
					{
						$this->cart->remove($cart_key);
					}
				}
				# --
				
				if ( isset($_SESSION[ PREFIX . 'extra' ]) ) {
					$this->cart->update($key, $_SESSION[ PREFIX . 'extra' ]);
					unset($_SESSION[ PREFIX . 'extra' ]);
					
				} else {
					$this->cart->update($key, 1);
				}
				
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 206, 'text' => __('system_206', true)));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 105, 'text' => __('system_105', true)));
		}
		exit;
	}
	
	public function pjActionRemoveFromCart()
	{
		if ($this->isXHR())
		{
			if (isset($_GET['cid']) && isset($_POST['date']) && isset($_POST['start_ts']) && isset($_POST['end_ts']) && isset($_POST['service_id']) && isset($_POST['employee_id']) && !$this->cart->isEmpty())
			{
				$key = sprintf("%u|%s|%u|%s|%s|%u|%u", $_GET['cid'], $_POST['date'], $_POST['service_id'], $_POST['start_ts'], $_POST['end_ts'], $_POST['employee_id'], $_POST['wt_id']);
				$this->cart->remove($key);
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 207, 'text' => __('system_207', true)));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 106, 'text' => __('system_106', true)));
		}
		exit;
	}

	public function pjActionValidateCart()
	{
		if ($this->isXHR())
		{
			$is_valid = $this->getValidate($this->getSummary());
			die($is_valid ? 'true' : 'false');
		}
		die('false');
	}
	
	public function pjActionCheckAvailability()
	{
		if ($this->isXHR())
		{
			$date_from = pjUtil::formatDate($_POST['date_from'], $this->option_arr['o_date_format'], 'Y-m-d');
			$date_to = pjUtil::formatDate($_POST['date_to'], $this->option_arr['o_date_format'], 'Y-m-d');

			$hour_from = $_POST['hour_from'];
			$hour_to = $_POST['hour_to'];
			$minute_from = $_POST['minute_from'];
			$minute_to = $_POST['minute_to'];
			
			$dt_from = $date_from . " " . $hour_from . ":" . $minute_from . ":00";
			$dt_to = $date_to . " " . $hour_to . ":" . $minute_to . ":00";

			$FORM = @$_SESSION[$this->defaultForm];
			if ($FORM['date_from'] != $date_from || $FORM['date_to'] != $date_to ||
				$FORM['hour_from'] != $hour_from || $FORM['hour_to'] != $hour_to ||
				$FORM['minute_from'] != $minute_from || $FORM['minute_to'] != $minute_to)
			{
				$this->cart->clear();
			}
			$_SESSION[$this->defaultForm] = array_merge($_SESSION[$this->defaultForm],
				compact('date_from', 'date_to', 'hour_from', 'hour_to', 'minute_from', 'minute_to'));
			
			if (isset($_POST['item_id']))
			{
				$arr = pjItemModel::factory()
					->select(sprintf("t1.*,
						(SELECT COUNT(*)
							FROM `%1\$s`
							WHERE `item_id` = t1.id
							AND `dt` BETWEEN '%2\$s' AND '%3\$s'
							LIMIT 1
						) AS `unavailable_days`,
						ABS(DATEDIFF('%2\$s', '%3\$s')) + 1 AS `necessary_days`,
						(SELECT COALESCE(SUM(bi.qty), 0)
							FROM `%4\$s` AS `bi`
							INNER JOIN `%5\$s` AS `b` ON b.id = bi.booking_id
								AND b.status = 'confirmed'
								AND b.dt_from <= '%7\$s'
								AND b.dt_to > '%6\$s'
							WHERE (bi.foreign_id = t1.id AND bi.type = 'item')
							OR (bi.foreign_id IN (SELECT pi.package_id
									FROM `%8\$s` AS `pi`
									INNER JOIN `%9\$s` AS `p` ON p.id = pi.package_id
									WHERE pi.item_id = t1.id) AND bi.type = 'package')
							LIMIT 1
						) AS `booked_qty`
						",
						pjAvailabilityModel::factory()->getTable(), $date_from, $date_to,
						pjBookingItemModel::factory()->getTable(), pjBookingModel::factory()->getTable(),
						$dt_from, $dt_to,
						pjPackageItemModel::factory()->getTable(),
						pjPackageModel::factory()->getTable()
					))
					->find($_POST['item_id'])
					->getData();
				
				if (!empty($arr)
					&& (int) $arr['is_active'] === 1
					&& (int) $arr['unavailable_days'] === 0
					//&& (int) $arr['available_days'] === (int) $arr['necessary_days']
					&& (int) $arr['booked_qty'] < (int) $arr['cnt'])
				{
					$price = $this->getPrice($arr['id'], 'item', $dt_from, $dt_to);
					$this->set('price', $price);
				} else {
					pjObject::import('Model', 'pjGallery:pjGallery');
					
					$other_arr = pjItemModel::factory()
						->select(sprintf("t1.*, t2.content AS `title`,
							(SELECT COUNT(*)
								FROM `%1\$s`
								WHERE `item_id` = t1.id
								AND `dt` BETWEEN '%2\$s' AND '%3\$s'
								LIMIT 1
							) AS `unavailable_days`,
							ABS(DATEDIFF('%2\$s', '%3\$s')) + 1 AS `necessary_days`,
							(SELECT COALESCE(SUM(bi.qty), 0)
								FROM `%4\$s` AS `bi`
								INNER JOIN `%5\$s` AS `b` ON b.id = bi.booking_id
									AND b.status = 'confirmed'
									AND b.dt_from <= '%7\$s'
									AND b.dt_to > '%6\$s'
								WHERE (bi.foreign_id = t1.id AND bi.type = 'item')
								OR (bi.foreign_id IN (SELECT pi.package_id
										FROM `%9\$s` AS `pi`
										INNER JOIN `%10\$s` AS `p` ON p.id = pi.package_id
										WHERE pi.item_id = t1.id) AND bi.type = 'package')
								LIMIT 1
							) AS `booked_qty`,
							(SELECT `medium_path` FROM `%8\$s`
								WHERE `foreign_id` = `t1`.`id`
								LIMIT 1) AS `pic`",
							pjAvailabilityModel::factory()->getTable(), $date_from, $date_to,
							pjBookingItemModel::factory()->getTable(), pjBookingModel::factory()->getTable(),
							$dt_from, $dt_to, pjGalleryModel::factory()->getTable(),
							pjPackageItemModel::factory()->getTable(), pjPackageModel::factory()->getTable()))
						->join('pjMultiLang', "t2.model='pjItem' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.id !=', $_POST['item_id'])
						->where('t1.is_active', 1)
						->having('`unavailable_days` = 0 AND `booked_qty` < `cnt`')
						//->having('`available_days` = `necessary_days` AND `booked_qty` < `cnt`')
						->limit(4)
						->findAll()
						->getData();

					$this->set('other_arr', $other_arr);
				}
				$this->set('arr', $arr);
				
				$this->setTemplate('pjFrontEnd', 'pjActionCheckItem');
				
			} elseif (isset($_POST['package_id'])) {
				
				$arr = pjPackageModel::factory()
					->select(sprintf("t1.*,
						(SELECT COUNT(*)
							FROM `%1\$s`
							WHERE `package_id` = t1.id
							AND `dt` BETWEEN '%2\$s' AND '%3\$s'
							LIMIT 1
						) AS `unavailable_days`,
						(SELECT MIN(`cnt`) FROM `%8\$s` WHERE `id` IN (SELECT `item_id` FROM `%9\$s` WHERE `package_id` = t1.id) LIMIT 1) AS `cnt`,
						ABS(DATEDIFF('%2\$s', '%3\$s')) + 1 AS `necessary_days`,
						(SELECT COALESCE(SUM(bi.qty), 0)
							FROM `%4\$s` AS `bi`
							INNER JOIN `%5\$s` AS `b` ON b.id = bi.booking_id
								AND b.status = 'confirmed'
								AND b.dt_from <= '%7\$s'
								AND b.dt_to > '%6\$s'
							WHERE (bi.foreign_id = t1.id AND bi.type = 'package')
							OR (bi.foreign_id IN (SELECT `item_id` FROM `%9\$s` WHERE `package_id` = t1.id) AND bi.type = 'item')
							LIMIT 1
						) AS `booked_qty`
						",
						pjPackageAvailabilityModel::factory()->getTable(), $date_from, $date_to,
						pjBookingItemModel::factory()->getTable(), pjBookingModel::factory()->getTable(),
						$dt_from, $dt_to, pjItemModel::factory()->getTable(), pjPackageItemModel::factory()->getTable()
					))
					->find($_POST['package_id'])
					->getData();
				
				if (!empty($arr)
					&& (int) $arr['is_active'] === 1
					&& (int) $arr['unavailable_days'] === 0
					//&& (int) $arr['available_days'] === (int) $arr['necessary_days']
					&& (int) $arr['booked_qty'] < (int) $arr['cnt'])
				{
					$price = $this->getPrice($arr['id'], 'package', $dt_from, $dt_to);
					$this->set('price', $price);
				} else {
					pjObject::import('Model', 'pjGallery:pjGallery');

					$other_arr = pjPackageModel::factory()
						->select(sprintf("t1.*, t2.content AS `title`,
							(SELECT COUNT(*)
								FROM `%1\$s`
								WHERE `package_id` = t1.id
								AND `dt` BETWEEN '%2\$s' AND '%3\$s'
								LIMIT 1
							) AS `unavailable_days`,
							ABS(DATEDIFF('%2\$s', '%3\$s')) + 1 AS `necessary_days`,
							(SELECT COALESCE(SUM(bi.qty), 0)
								FROM `%4\$s` AS `bi`
								INNER JOIN `%5\$s` AS `b` ON b.id = bi.booking_id
									AND b.status = 'confirmed'
									AND b.dt_from <= '%7\$s'
									AND b.dt_to > '%6\$s'
								WHERE (bi.foreign_id = t1.id AND bi.type = 'package')
								OR (bi.foreign_id IN (SELECT `item_id` FROM `%9\$s` WHERE `package_id` = t1.id) AND bi.type = 'item')
								LIMIT 1
							) AS `booked_qty`,
							(SELECT MIN(`cnt`) FROM `%8\$s` WHERE `id` IN (SELECT `item_id` FROM `%9\$s` WHERE `package_id` = t1.id) LIMIT 1) AS `cnt`,
							(SELECT `medium_path` FROM `%10\$s`
								WHERE `foreign_id` IN (SELECT `item_id` FROM `%9\$s` WHERE `package_id` = t1.id)
								ORDER BY ISNULL(`sort`), `sort` ASC, `id` ASC
								LIMIT 1) AS `pic`",
							pjPackageAvailabilityModel::factory()->getTable(), $date_from, $date_to,
							pjBookingItemModel::factory()->getTable(), pjBookingModel::factory()->getTable(),
							$dt_from, $dt_to, pjItemModel::factory()->getTable(), pjPackageItemModel::factory()->getTable(),
							pjGalleryModel::factory()->getTable()))
						->join('pjMultiLang', "t2.model='pjPackage' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
						->where('t1.id !=', $_POST['package_id'])
						->where('t1.is_active', 1)
						->having('`unavailable_days` = 0 AND `booked_qty` < `cnt`')
						//->having('`available_days` = `necessary_days` AND `booked_qty` < `cnt`')
						->limit(4)
						->findAll()
						->getData();

					$this->set('other_arr', $other_arr);
				}
				$this->set('arr', $arr);
				
				$this->setTemplate('pjFrontEnd', 'pjActionCheckPackage');
			}
		}
	}

	public function pjActionProcessOrder()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
            $owner_id = $this->getOwnerId();

			if (!isset($_POST['as_preview']) || !isset($_SESSION[$this->defaultForm]) || empty($_SESSION[$this->defaultForm]))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 109, 'text' => __('system_109', true)));
			}
			
			if ((int) $this->option_arr['o_bf_captcha'] === 3 && (!isset($_SESSION[$this->defaultForm]['captcha']) || @$_SESSION[$this->defaultCaptcha] != strtoupper($_SESSION[$this->defaultForm]['captcha'])))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 110, 'text' => __('system_110', true)));
			}
			
			$summary = $this->getSummary();
			if (!$this->getValidate($summary))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 111, 'text' => __('system_111', true)));
			}
			
			$data = array();
			
			$data['calendar_id'] = $this->getForeignId();
			$data['booking_status'] = $this->option_arr['o_status_if_not_paid'];
			$data['uuid'] = pjUtil::uuid();
			$data['ip'] = $_SERVER['REMOTE_ADDR'];
			$data['locale_id'] = $this->getLocaleId();
			$data['owner_id'] = $owner_id;
			$data = array_merge($_SESSION[$this->defaultForm], $data);
			
			if (isset($data['payment_method']) && $data['payment_method'] != 'creditcard')
			{
				unset($data['cc_type']);
				unset($data['cc_num']);
				unset($data['cc_exp_month']);
				unset($data['cc_exp_year']);
				unset($data['cc_code']);
			}
			
			$data['booking_price'] = $summary['price'];
			$data['booking_deposit'] = $summary['deposit'];
			$data['booking_tax'] = $summary['tax'];
			$data['booking_total'] = $summary['total'];
			$data['owner_id'] = $owner_id;

			$pjBookingModel = pjBookingModel::factory();
			if (!$pjBookingModel->validates($data))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 114, 'text' => __('system_114', true)));
			}
			
			$booking_id = $pjBookingModel->setAttributes($data)->insert()->getInsertId();

			if ($booking_id !== false && (int) $booking_id > 0)
			{
				
				$pjBookingServiceModel = pjBookingServiceModel::factory()->setBatchFields(array(
					'booking_id', 'service_id', 'employee_id', 'resources_id', 'date', 'start', 'start_ts', 'total', 'price', 'reminder_email', 'reminder_sms', 'owner_id'
				));
				
				foreach ($summary['services'] as $service)
				{
					$resources_ids = pjResourcesServiceModel::factory()
						->where('t1.service_id', $service['id'])
						->findAll()
						->getDataPair(null, 'resources_id');
					
					if ( isset($resources_ids) && !empty($resources_ids)) {
						$resources_bs_ids = pjBookingServiceModel::factory()
							->join('pjBooking', "t1.booking_id=t2.id AND t2.booking_status='confirmed'", 'inner')
							->whereIn('t1.resources_id', $resources_ids)
							->where('t1.date', $service['date'])
							->where('t1.start_ts >= ', $service['start_ts'])
							->where('t1.start_ts < ', $service['start_ts'] + $service['total']* 60)
							->findAll()
							->getDataPair(null, 'resources_id');
							
						if ( isset($resources_bs_ids) && !empty($resources_bs_ids)) {
							$resources_booking_ids = array_values(array_diff($resources_ids, $resources_bs_ids));
						} else $resources_booking_ids = $resources_ids;
							
					} else $resources_booking_ids = array('0' => null);
					
					$pjBookingServiceModel->addBatchRow(array(
						$booking_id, $service['id'], $service['employee_id'], $resources_booking_ids[0],
						$service['date'], @$service['start'], $service['start_ts'],
						$service['total'], $service['price'], 0, 0, $owner_id
					));
					
					if ( isset($service['extra']) && count($service['extra']) > 0 ) {
							
						$pjBookingExtraServiceModel = pjBookingExtraServiceModel::factory();
							
						$pjBookingExtraServiceModel->setBatchFields(array('booking_id', 'service_id', 'extra_id', 'date', 'owner_id'));
						foreach ( $service['extra'] as $_extra) {
							$pjBookingExtraServiceModel->addBatchRow(array($booking_id, $service['id'], $_extra['id'], $service['date'], $owner_id));
						}
						$pjBookingExtraServiceModel->insertBatch();
					}
				}
				$pjBookingServiceModel->insertBatch();
				
				$invoice_arr = $this->pjActionGenerateInvoice($booking_id, $owner_id);
				
				# Confirmation email(s)
				$booking_arr = $pjBookingModel
					->reset()
					->select('t1.*, t1.id AS `booking_id`, t3.email AS `admin_email`, t4.content AS `country_name`,
						t5.content AS `confirm_subject_client`, t6.content AS `confirm_tokens_client`,
						t7.content AS `confirm_subject_admin`, t8.content AS `confirm_tokens_admin`,
						t9.content AS `confirm_subject_employee`, t10.content AS `confirm_tokens_employee`')
					->join('pjCalendar', 't2.id=t1.calendar_id', 'left outer')
					->join('pjUser', 't3.id=t2.user_id', 'left outer')
					->join('pjMultiLang', "t4.model='pjCountry' AND t4.foreign_id=t1.c_country_id AND t4.field='name'", 'left outer')
					->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.field='confirm_subject_client'", 'left outer')
					->join('pjMultiLang', "t6.model='pjCalendar' AND t6.foreign_id=t1.calendar_id AND t6.field='confirm_tokens_client'", 'left outer')
					->join('pjMultiLang', "t7.model='pjCalendar' AND t7.foreign_id=t1.calendar_id AND t7.field='confirm_subject_admin'", 'left outer')
					->join('pjMultiLang', "t8.model='pjCalendar' AND t8.foreign_id=t1.calendar_id AND t8.field='confirm_tokens_admin'", 'left outer')
					->join('pjMultiLang', "t9.model='pjCalendar' AND t9.foreign_id=t1.calendar_id AND t9.field='confirm_subject_employee'", 'left outer')
					->join('pjMultiLang', "t10.model='pjCalendar' AND t10.foreign_id=t1.calendar_id AND t10.field='confirm_tokens_employee'", 'left outer')
					->find($booking_id)
					->getData();
					
				$booking_arr['bs_arr'] = $pjBookingServiceModel
					->reset()
					->select('t1.*, t3.before, t3.length, t4.content AS `service_name`, t5.content AS `employee_name`,
						t6.phone AS `employee_phone`, t6.email AS `employee_email`, t6.is_subscribed, t6.is_subscribed_sms')
					->join('pjBooking', 't2.id=t1.booking_id', 'inner')
					->join('pjService', 't3.id=t1.service_id', 'inner')
					->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.field='name'", 'left outer')
					->join('pjMultiLang', "t5.model='pjEmployee' AND t5.foreign_id=t1.employee_id AND t5.field='name'", 'left outer')
					->join('pjEmployee', 't6.id=t1.employee_id', 'left outer')
					->where('t1.booking_id', $booking_id)
					->findAll()
					->getData();

				$bs_ids = $pjBookingServiceModel->getDataPair('id', null);
					
				// Reset SESSION vars
				$this->cart->clear();
				
				$_SESSION[$this->defaultForm] = NULL;
				unset($_SESSION[$this->defaultForm]);
				
				$_SESSION[$this->defaultCaptcha] = NULL;
				unset($_SESSION[$this->defaultCaptcha]);
				
				header("Content-Type: application/json; charset=utf-8");
				echo pjAppController::jsonEncode(array(
					'status' => 'OK',
					'code' => 210,
					'text' => __('system_210', true),
					'booking_id' => $booking_id,
					'booking_uuid' => $booking_arr['uuid'],
					'invoice_id' => @$invoice_arr['data']['id'],
					'payment_method' => ((int) $this->option_arr['o_disable_payments'] === 0 && isset($data['payment_method']) ?
						$data['payment_method'] : 'none')
				));
				
				pjFrontEnd::pjActionConfirmSend($this->option_arr, $booking_arr, 'confirm');
				# Confirmation email(s)
				
				exit;
				
			} else {
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 119, 'text' => __('system_119', true)));
			}
		}
		exit;
	}
	
	public function pjActionLoad()
	{
		$this->setAjax(false);
		$this->setLayout('pjActionFront');
		
		ob_start();
		header("Content-Type: text/javascript; charset=utf-8");

		$days_off = $dates_off = $dates_on = array();
		$w_arr = pjWorkingTimeModel::factory()->where('t1.foreign_id', $this->getForeignId())->where('t1.type', 'calendar')->findAll()->getData();
		if (!empty($w_arr))
		{
			$w_arr = $w_arr[0];
			
			if ($w_arr['monday_dayoff'] == 'T')
			{
				$days_off[] = 1;
			}
			if ($w_arr['tuesday_dayoff'] == 'T')
			{
				$days_off[] = 2;
			}
			if ($w_arr['wednesday_dayoff'] == 'T')
			{
				$days_off[] = 3;
			}
			if ($w_arr['thursday_dayoff'] == 'T')
			{
				$days_off[] = 4;
			}
			if ($w_arr['friday_dayoff'] == 'T')
			{
				$days_off[] = 5;
			}
			if ($w_arr['saturday_dayoff'] == 'T')
			{
				$days_off[] = 6;
			}
			if ($w_arr['sunday_dayoff'] == 'T')
			{
				$days_off[] = 0;
			}
		}
		
		$d_arr = pjDateModel::factory()
			// ->where('t1.foreign_id', $this->getForeignId())
			->where('t1.type', 'calendar')
			->where('t1.date >= CURDATE()')
			->findAll()
			->getData();

		foreach ($d_arr as $date)
		{
			if ($date['is_dayoff'] == 'T')
			{
				$dates_off[] = $date['date'];
			} else {
				$dates_on[] = $date['date'];
			}
		}

		$this->set('days_off', $days_off);
		$this->set('dates_off', $dates_off);
		$this->set('dates_on', $dates_on);
				
		# Find first working day starting from tomorrow
		$first_working_date = NULL;
		list($y, $m, $d, $w) = explode("-", date("Y-n-j-w", strtotime("+1 day")));
		foreach (range(0, 365) as $i)
		{
			$timestamp = mktime(0, 0, 0, $m, $d + $i, $y);
			list($date, $wday) = explode("|", date("Y-m-d|w", $timestamp));
			
			if (!in_array($wday, $days_off) && !in_array($date, $dates_off))
			{
				$first_working_date = $date;
				break;
			}
			
			if (in_array($wday, $days_off) && in_array($date, $dates_on))
			{
				$first_working_date = $date;
				break;
			}
		}
		
		$this->set('first_working_date', $first_working_date);
	}
	
	public function pjActionLoadCss()
	{
		$layout = isset($_GET['layout']) && in_array($_GET['layout'], $this->getLayoutRange()) ?
			(int) $_GET['layout'] : (int) $this->option_arr['o_layout'];

		$arr = array(
			array('file' => 'ASCalendar.txt', 'path' => PJ_CSS_PATH),
			array('file' => 'AppScheduler.txt', 'path' => PJ_CSS_PATH),
			array('file' => 'AppScheduler.css', 'path' => PJ_CSS_PATH),
			array('file' => "AppScheduler_$layout.txt", 'path' => PJ_CSS_PATH),
			array('file' => "AppScheduler_$layout.css", 'path' => PJ_CSS_PATH)
		);
		header("Content-Type: text/css; charset=utf-8");
		$cid = $this->getForeignId();
		foreach ($arr as $item)
		{
			ob_start();
			@readfile($item['path'] . $item['file']);
			$string = ob_get_contents();
			ob_end_clean();
			
			if ($string !== FALSE)
			{
				echo str_replace(
					array(
						'../img/',
						'[URL]',
						'[calendarContainer]',
						'[cell_width]',
						'[cell_height]'
					),
					array(
						PJ_IMG_PATH,
						PJ_INSTALL_URL,
						'#asContainer_'.$cid,
						number_format((100 / ((int) @$this->option_arr['o_week_numbers'] === 1 ? 8 : 7)), 2, '.', ''),
						number_format(100 / 8, 2, '.', '')
					),
					$string
				) . "\n";
			}
		}
		exit;
	}
	
	public function pjActionCancel()
	{
		$pjBookingModel = pjBookingModel::factory();
				
		if (isset($_POST['booking_cancel']))
		{
			$arr = $pjBookingModel->find($_POST['id'])->getData();
			if (!empty($arr))
			{
				$pjBookingModel
					->reset()
					->where(sprintf("SHA1(CONCAT(`id`, `created`, '%s')) = ", PJ_SALT), $_POST['hash'])
					->limit(1)
					->modifyAll(array('booking_status' => 'cancelled'));
					 
				pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFrontEnd&action=pjActionCancel&err=5');
			}
		} else {
			if (isset($_GET['hash']) && isset($_GET['id']))
			{
				$arr = $pjBookingModel
					->select('t1.*, t2.content AS `country_title`')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.c_country_id AND t2.field='name' AND t2.locale='t1.locale_id'", 'left outer')
					->find($_GET['id'])
					->getData();
				if (empty($arr))
				{
					$this->set('status', 2);
				} else {
					if ($arr['booking_status'] == 'cancelled')
					{
						$this->set('status', 4);
					} else {
						$hash = sha1($arr['id'] . $arr['created'] . PJ_SALT);
						if ($_GET['hash'] != $hash)
						{
							$this->set('status', 3);
						} else {
							
							$arr['details_arr'] = pjBookingServiceModel::factory()
								->select('t1.*, t2.content AS `service_name`, t3.content AS `employee_name`, t4.before, t4.length')
								->join('pjMultiLang', sprintf("t2.model='pjService' AND t2.foreign_id=t1.service_id AND t2.field='name' AND t2.locale='%u'", $arr['locale_id']), 'left outer')
								->join('pjMultiLang', sprintf("t3.model='pjEmployee' AND t3.foreign_id=t1.employee_id AND t3.field='name' AND t3.locale='%u'", $arr['locale_id']), 'left outer')
								->join('pjService', 't4.id=t1.service_id', 'left outer')
								->where('t1.booking_id', $arr['id'])
								->findAll()
								->getData();
							
							$this->set('arr', $arr);
						}
					}
				}
			} elseif (!isset($_GET['err'])) {
				$this->set('status', 1);
			}
			$this->appendCss('index.php?controller=pjFrontEnd&action=pjActionLoadCss', PJ_INSTALL_URL, true);
		}
	}
	
	public function pjActionCaptcha()
	{
		$pjCaptcha = new pjCaptcha(PJ_WEB_PATH . 'obj/Anorexia.ttf', $this->defaultCaptcha, 6);
		$pjCaptcha->setImage(PJ_IMG_PATH . 'frontend/as-captcha.png')->init(@$_GET['rand']);
		exit;
	}
	
	public function pjActionCheckCaptcha()
	{
		if ($this->isXHR())
		{
			echo isset($_SESSION[$this->defaultCaptcha]) && isset($_GET['captcha']) && $_SESSION[$this->defaultCaptcha] == strtoupper($_GET['captcha']) ? 'true' : 'false';
		}
		exit;
	}
		
	public function pjActionConfirmAuthorize()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjAuthorize') === NULL)
		{
			$this->log('Authorize.NET plugin not installed');
			exit;
		}
		
		if (!isset($_POST['x_invoice_num']))
		{
			$this->log('Missing arguments');
			exit;
		}
		
		pjObject::import('Model', 'pjInvoice:pjInvoice');
		$pjInvoiceModel = pjInvoiceModel::factory();
		$pjBookingModel = pjBookingModel::factory();
		
		$invoice_arr = $pjInvoiceModel
			->where('t1.uuid', $_POST['x_invoice_num'])
			->limit(1)
			->findAll()
			->getData();
		if (!empty($invoice_arr))
		{
			$invoice_arr = $invoice_arr[0];
			$booking_arr = $pjBookingModel
				->select('t1.*, t1.id AS `booking_id`, t3.email AS `admin_email`, t4.content AS `country_name`,
					t5.content AS `payment_subject_client`, t6.content AS `payment_tokens_client`,
					t7.content AS `payment_subject_admin`, t8.content AS `payment_tokens_admin`,
					t9.content AS `payment_subject_employee`, t10.content AS `payment_tokens_employee`')
				->join('pjCalendar', 't2.id=t1.calendar_id', 'left outer')
				->join('pjUser', 't3.id=t2.user_id', 'left outer')
				->join('pjMultiLang', "t4.model='pjCountry' AND t4.foreign_id=t1.c_country_id AND t4.locale=t1.locale_id AND t4.field='name'", 'left outer')
				->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.locale=t1.locale_id AND t5.field='payment_subject_client'", 'left outer')
				->join('pjMultiLang', "t6.model='pjCalendar' AND t6.foreign_id=t1.calendar_id AND t6.locale=t1.locale_id AND t6.field='payment_tokens_client'", 'left outer')
				->join('pjMultiLang', "t7.model='pjCalendar' AND t7.foreign_id=t1.calendar_id AND t7.locale=t1.locale_id AND t7.field='payment_subject_admin'", 'left outer')
				->join('pjMultiLang', "t8.model='pjCalendar' AND t8.foreign_id=t1.calendar_id AND t8.locale=t1.locale_id AND t8.field='payment_tokens_admin'", 'left outer')
				->join('pjMultiLang', "t9.model='pjCalendar' AND t9.foreign_id=t1.calendar_id AND t9.locale=t1.locale_id AND t9.field='payment_subject_employee'", 'left outer')
				->join('pjMultiLang', "t10.model='pjCalendar' AND t10.foreign_id=t1.calendar_id AND t10.locale=t1.locale_id AND t10.field='payment_tokens_employee'", 'left outer')
				->where('t1.uuid', $invoice_arr['order_id'])
				->limit(1)
				->findAll()
				->getData();
			if (!empty($booking_arr))
			{
				$booking_arr = $booking_arr[0];
				$option_arr = pjOptionModel::factory()->getPairs($booking_arr['calendar_id']);

				$params = array(
					'transkey' => $option_arr['o_authorize_key'],
					'x_login' => $option_arr['o_authorize_mid'],
					'md5_setting' => $option_arr['o_authorize_hash'],
					'key' => md5($this->option_arr['private_key'] . PJ_SALT)
				);
				
				$response = $this->requestAction(array('controller' => 'pjAuthorize', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
				if ($response !== FALSE && $response['status'] === 'OK')
				{
					$pjBookingModel
						->reset()
						->set('id', $booking_arr['id'])
						->modify(array('booking_status' => $option_arr['o_status_if_paid']));
						
					$pjInvoiceModel
						->reset()
						->set('id', $invoice_arr['id'])
						->modify(array('status' => 'paid', 'modified' => ':NOW()'));
					
					$booking_arr['bs_arr'] = pjBookingServiceModel::factory()
						->select('t1.*, t3.before, t3.length, t4.phone AS `employee_phone`, t4.email AS `employee_email`, t4.is_subscribed, t4.is_subscribed_sms,
							t5.content AS `service_name`, t6.content AS `employee_name`')
						->join('pjBooking', 't2.id=t1.booking_id', 'inner')
						->join('pjService', 't3.id=t1.service_id', 'inner')
						->join('pjEmployee', 't4.id=t1.employee_id', 'inner')
						->join('pjMultiLang', "t5.model='pjService' AND t5.foreign_id=t1.service_id AND t5.field='name' AND t5.locale=t2.locale_id", 'left outer')
						->join('pjMultiLang', "t6.model='pjEmployee' AND t6.foreign_id=t1.employee_id AND t6.field='name' AND t6.locale=t2.locale_id", 'left outer')
						->where('t1.booking_id', $booking_arr['id'])
						->findAll()
						->getData();
					pjFrontEnd::pjActionConfirmSend($option_arr, $booking_arr, 'payment');
				} elseif (!$response) {
					$this->log('Authorization failed');
				} else {
					$this->log('Booking not confirmed. ' . $response['response_reason_text']);
				}
			} else {
				$this->log('Booking not found');
			}
		} else {
			$this->log('Invoice not found');
		}
		exit;
	}

	public function pjActionConfirmPaypal()
	{
		$this->setAjax(true);
		
		if (pjObject::getPlugin('pjPaypal') === NULL)
		{
			$this->log('Paypal plugin not installed');
			exit;
		}
		
		pjObject::import('Model', 'pjInvoice:pjInvoice');
		$pjInvoiceModel = pjInvoiceModel::factory();
		$pjBookingModel = pjBookingModel::factory();

		$invoice_arr = $pjInvoiceModel
			->where('t1.uuid', $_POST['custom'])
			->limit(1)
			->findAll()
			->getData();

		if (!empty($invoice_arr))
		{
			$invoice_arr = $invoice_arr[0];
			$booking_arr = $pjBookingModel
				->select('t1.*, t1.id AS `booking_id`, t3.email AS `admin_email`, t4.content AS `country_name`,
					t5.content AS `payment_subject_client`, t6.content AS `payment_tokens_client`,
					t7.content AS `payment_subject_admin`, t8.content AS `payment_tokens_admin`,
					t9.content AS `payment_subject_employee`, t10.content AS `payment_tokens_employee`')
				->join('pjCalendar', 't2.id=t1.calendar_id', 'left outer')
				->join('pjUser', 't3.id=t2.user_id', 'left outer')
				->join('pjMultiLang', "t4.model='pjCountry' AND t4.foreign_id=t1.c_country_id AND t4.locale=t1.locale_id AND t4.field='name'", 'left outer')
				->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.locale=t1.locale_id AND t5.field='payment_subject_client'", 'left outer')
				->join('pjMultiLang', "t6.model='pjCalendar' AND t6.foreign_id=t1.calendar_id AND t6.locale=t1.locale_id AND t6.field='payment_tokens_client'", 'left outer')
				->join('pjMultiLang', "t7.model='pjCalendar' AND t7.foreign_id=t1.calendar_id AND t7.locale=t1.locale_id AND t7.field='payment_subject_admin'", 'left outer')
				->join('pjMultiLang', "t8.model='pjCalendar' AND t8.foreign_id=t1.calendar_id AND t8.locale=t1.locale_id AND t8.field='payment_tokens_admin'", 'left outer')
				->join('pjMultiLang', "t9.model='pjCalendar' AND t9.foreign_id=t1.calendar_id AND t9.locale=t1.locale_id AND t9.field='payment_subject_employee'", 'left outer')
				->join('pjMultiLang', "t10.model='pjCalendar' AND t10.foreign_id=t1.calendar_id AND t10.locale=t1.locale_id AND t10.field='payment_tokens_employee'", 'left outer')
				->where('t1.uuid', $invoice_arr['order_id'])
				->limit(1)
				->findAll()
				->getData();
			if (!empty($booking_arr))
			{
				$booking_arr = $booking_arr[0];
				$option_arr = pjOptionModel::factory()->getPairs($booking_arr['calendar_id']);
				$params = array(
					'txn_id' => @$booking_arr['txn_id'],
					'paypal_address' => @$option_arr['o_paypal_address'],
					'deposit' => @$invoice_arr['total'],
					'currency' => @$invoice_arr['currency'],
					'key' => md5($this->option_arr['private_key'] . PJ_SALT)
				);

				$response = $this->requestAction(array('controller' => 'pjPaypal', 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
				if ($response !== FALSE && $response['status'] === 'OK')
				{
					$this->log('Booking confirmed');
					$pjBookingModel->reset()->set('id', $booking_arr['id'])->modify(array(
						'booking_status' => $option_arr['o_status_if_paid'],
						'txn_id' => $response['transaction_id'],
						'processed_on' => ':NOW()'
					));
					
					$pjInvoiceModel
						->reset()
						->set('id', $invoice_arr['id'])
						->modify(array('status' => 'paid', 'modified' => ':NOW()'));
						
					$booking_arr['bs_arr'] = pjBookingServiceModel::factory()
						->select('t1.*, t3.before, t3.length, t4.phone AS `employee_phone`, t4.email AS `employee_email`, t4.is_subscribed, t4.is_subscribed_sms,
							t5.content AS `service_name`, t6.content AS `employee_name`')
						->join('pjBooking', 't2.id=t1.booking_id', 'inner')
						->join('pjService', 't3.id=t1.service_id', 'inner')
						->join('pjEmployee', 't4.id=t1.employee_id', 'inner')
						->join('pjMultiLang', "t5.model='pjService' AND t5.foreign_id=t1.service_id AND t5.field='name' AND t5.locale=t2.locale_id", 'left outer')
						->join('pjMultiLang', "t6.model='pjEmployee' AND t6.foreign_id=t1.employee_id AND t6.field='name' AND t6.locale=t2.locale_id", 'left outer')
						->where('t1.booking_id', $booking_arr['id'])
						->findAll()
						->getData();
					pjFrontEnd::pjActionConfirmSend($option_arr, $booking_arr, 'payment');
				} elseif (!$response) {
					$this->log('Authorization failed');
				} else {
					$this->log('Booking not confirmed');
				}
			} else {
				$this->log('Booking not found');
			}
		} else {
			$this->log('Invoice not found');
		}
		exit;
	}
	
	private static function pjActionConfirmSend($option_arr, $booking_arr, $type)
	{
		if (!in_array($type, array('confirm', 'payment')))
		{
			return false;
		}
		$Email = new pjEmail();
		$Email->setContentType('text/html');
		if ($option_arr['o_send_email'] == 'smtp')
		{
			$Email
				->setTransport('smtp')
				->setSmtpHost($option_arr['o_smtp_host'])
				->setSmtpPort($option_arr['o_smtp_port'])
				->setSmtpUser($option_arr['o_smtp_user'])
				->setSmtpPass($option_arr['o_smtp_pass'])
			;
		}
		$tokens = pjAppController::getTokens($booking_arr, $option_arr, 'multi');

		switch ($type)
		{
			case 'confirm':
				// Client
				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_subject_client']);
				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_tokens_client']);
				if (!empty($subject) && !empty($message))
				{
					$message = pjUtil::textToHtml($message);
					$Email
						->setTo($booking_arr['c_email'])
						->setFrom($booking_arr['admin_email'])
						->setSubject($subject)
						->send($message);
				}
				// Admin
				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_subject_admin']);
				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_tokens_admin']);
				if (!empty($subject) && !empty($message))
				{
					$message = pjUtil::textToHtml($message);
					$Email
						->setTo($booking_arr['admin_email'])
						->setFrom($booking_arr['admin_email'])
						->setSubject($subject)
						->send($message);
				}
				// Employee
				foreach ($booking_arr['bs_arr'] as $item)
				{
					if ((int) $item['is_subscribed'] === 1 && !empty($item['employee_email']))
					{
						$tokens = pjAppController::getTokens(array_merge($booking_arr, $item), $option_arr);
						$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_subject_employee']);
						$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_tokens_employee']);
						if (!empty($subject) && !empty($message))
						{
							$message = pjUtil::textToHtml($message);
							$Email
								->setTo($item['employee_email'])
								->setFrom($booking_arr['admin_email'])
								->setSubject($subject)
								->send($message);
						}
					}
				}
				break;
			case 'payment':
				// Client
				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_subject_client']);
				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_tokens_client']);
				if (!empty($subject) && !empty($message))
				{
					$message = pjUtil::textToHtml($message);
					$Email
						->setTo($booking_arr['c_email'])
						->setFrom($booking_arr['admin_email'])
						->setSubject($subject)
						->send($message);
				}
				// Admin
				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_subject_admin']);
				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_tokens_admin']);
				if (!empty($subject) && !empty($message))
				{
					$message = pjUtil::textToHtml($message);
					$Email
						->setTo($booking_arr['admin_email'])
						->setFrom($booking_arr['admin_email'])
						->setSubject($subject)
						->send($message);
				}
				// Employee
				foreach ($booking_arr['bs_arr'] as $item)
				{
					if ((int) $item['is_subscribed'] === 1 && !empty($item['employee_email']))
					{
						$tokens = pjAppController::getTokens(array_merge($booking_arr, $item), $option_arr, 'single');
						$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_subject_employee']);
						$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_tokens_employee']);
						if (!empty($subject) && !empty($message))
						{
							$message = pjUtil::textToHtml($message);
							$Email
								->setTo($item['employee_email'])
								->setFrom($booking_arr['admin_email'])
								->setSubject($subject)
								->send($message);
						}
					}
				}
				break;
		}
		
		// SMS
		if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
		
		} else {
			
			# SMS
			include ROOT_PATH . 'oneapi.php';
			
			$message = str_replace($tokens['search'], $tokens['replace'], str_replace(array('\r\n', '\n'), ' ', $option_arr['o_reminder_sms_message']));
			$message = stripslashes($message);
			
			$phone = $booking_arr['c_phone'];
			if ( strpos($phone, '0') == 0 ) {
				$phone = ltrim($phone, '0');
			}
			
			$phone = isset($option_arr['o_reminder_sms_country_code']) ? $option_arr['o_reminder_sms_country_code'] . $phone : $phone;
			
			$send_address = isset($option_arr['o_reminder_sms_send_address']) ? $option_arr['o_reminder_sms_send_address'] : $phone;
			
			$sendsms = new pjSMSV;
			# Send to CLIENT
			$sendsms->sendSMS($send_address, $phone, $message);
		}
	}
	
	public function pjActionGetCalendar()
	{
		$this->set('calendar', $this->getCalendar($_GET['cid'], $_GET['year'], $_GET['month']));
	}
	
	public function pjActionGetCart()
	{
		$this->set('cart_arr', $this->getCart($_GET['cid']));
	}

	public function pjActionGetTime()
	{
		$this->set('service_arr', pjServiceModel::factory()->find($_GET['service_id'])->getData());
		$this->set('t_arr', pjAppController::getSingleDateSlots($_GET['cid'], $_GET['date']));
	}
	
	public function pjActionGetEmployees()
	{
		$pjEmployeeServiceModel = pjEmployeeServiceModel::factory()
			->select("t1.*, t2.avatar, t2.notes, t2.email, t2.phone, t3.content AS `name`")
			->join('pjEmployee', 't2.id=t1.employee_id AND t2.is_active=1', 'inner')
			->join('pjMultiLang', "t3.model='pjEmployee' AND t3.foreign_id=t1.employee_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
			->where('t1.service_id', $_GET['service_id'])
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

		$this->set('service_arr', pjServiceModel::factory()->find($_GET['service_id'])->getData());
		$this->set('employee_arr', $employee_arr);
	}
	
	public function pjActionLoadAjax () {
		$owner_id = intval($_GET['owner_id']);
		
		if ( isset($_GET['category_id']) && (int) $_GET['category_id'] > 0 ) {
			$service_arr = pjServiceModel::factory()
				->select("t1.*, t2.content AS `name`, t3.content AS `description`")
				->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
				->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='description'", 'left outer')
				->where('t1.is_active', 1)
				->where('t1.owner_id', $owner_id)
				->where('t1.category_id', $_GET['category_id'])
				->orderBy('`name` ASC')
				->findAll()
				->getData();
			
			$this->set('service_arr', $service_arr);
			
		} elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
					(!isset($_GET['employee_id']) || (int) $_GET['employee_id'] < 1) ) {
			$employee_arr = pjEmployeeServiceModel::factory()
				->select("t1.*, t2.notes, t3.content AS `name`")
				->join('pjEmployee', 't2.id=t1.employee_id AND t2.is_active=1', 'inner')
				->join('pjMultiLang', "t3.model='pjEmployee' AND t3.foreign_id=t1.employee_id AND t3.field='name' AND t3.locale='".$this->getLocaleId()."'", 'left outer')
				->where('t1.service_id', $_GET['service_id'])
				->where('t1.owner_id', $owner_id)
				->orderBy('`name` ASC')
				->findAll()
				->getData();
			
			$this->set('employee_arr', $employee_arr);
			
		} elseif ( isset($_GET['service_id']) && (int) $_GET['service_id'] > 0 &&
					isset($_GET['employee_id']) && (int) $_GET['employee_id'] > 0 ) {
			
			$date = isset($_GET['date']) && !empty($_GET['date']) ? $_GET['date'] : date("Y-m-d");
			$t_arr = array();
			$bs_arr = array();
			for ( $i = 0; $i < 5; $i++ ) {
			
				if ( $i == 0 ) {
					$isoDate = date('Y-m-d', strtotime($date . ' 00:00:00'));
						
				} else $isoDate = date('Y-m-d', strtotime($date . ' 00:00:00') + $i*86400);
			
				$t_arr[$i] = pjAppController::getRawSlotsPerEmployee($_GET['employee_id'], $isoDate, $this->getForeignId());
			
				$bs_arr[$i] = pjBookingServiceModel::factory()
					->select('t1.*')
					->join('pjBooking', 't2.id=t1.booking_id', 'inner')
					->join('pjService', 't3.id=t1.service_id', 'inner')
					// banana cocde
					// ->where('t2.calendar_id', $this->getForeignId())
					->where('t2.booking_status', 'confirmed')
					->where('t1.date', $isoDate)
					->where('t1.service_id', $_GET['service_id'])
					->where('t1.employee_id', $_GET['employee_id'])
					->where('t1.owner_id', $owner_id)
					->findAll()
					->getData();
			}
			
			$service_arr = pjServiceModel::factory()
				->select("t1.*")
				->where('t1.is_active', 1)
				->where('t1.owner_id', $owner_id)
				->where('t1.id', $_GET['service_id'])
				->find($_GET['service_id'])
				->getData();
			
			$this->set('bs_arr', $bs_arr);
			$this->set('t_arr', $t_arr);
			$this->set('service_arr', $service_arr);
		}
	}
}
?>

<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}

require_once CONTROLLERS_PATH . 'pjAppController.controller.php';
require_once realpath(CONTROLLERS_PATH.'../../../../vendor/autoload.php');

use Hashids\Hashids;
class pjFront extends pjAppController
{
	var $layout = 'front';
	var $default_order = 'RBooking_Order';
	var $default_captcha = 'RBooking_Captcha';

	function pjFront()
	{
		
	}
	
	function _get($key)
	{
		if ($this->_is($key))
		{
			return $_SESSION[$this->default_product][$this->default_order][$key];
		}
		return false;
	}
	
	function _is($key)
	{
		return isset($_SESSION[$this->default_product]) &&
			isset($_SESSION[$this->default_product][$this->default_order]) &&
			isset($_SESSION[$this->default_product][$this->default_order][$key]);
	}
	
	function _set($key, $value)
	{
		$_SESSION[$this->default_product][$this->default_order][$key] = $value;
		return $this;
	}

	function addPromo()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			$resp = array();
			
			pjObject::import('Model', 'pjVoucher');
			$pjVoucherModel = new pjVoucherModel();
			
			$date = pjUtil::formatDate($this->_get('date'), $this->option_arr['date_format']);
			$time = $this->_get('hour') . ":" . $this->_get('minutes') . ":00";

			$code_arr = $pjVoucherModel->getVoucher($_GET['code'], $date, $time);
			if (count($code_arr) > 0)
			{
				$resp = pjAppController::getPrice($this->option_arr, $date, $time, $_GET['code']);
				$resp['code'] = 200;
				$this->_set('code', $_GET['code']);
				
				if (isset($resp['discount_formated']))
				{
					include ROOT_PATH . 'app/locale/'. $this->getLanguage() . '.php';
					$resp['discount_text'] = $resp['discount_formated'] . " " . $RB_LANG['front']['4_discount'];
				}
			} else {
				$resp = pjAppController::getPrice($this->option_arr, $date, $time);
				$resp['code'] = 100;
			}
			pjAppController::responseJson($resp);
		}
	}
	
	function beforeFilter()
	{
		pjObject::import('Model', 'pjOption');
		$pjOptionModel = new pjOptionModel();
		$this->option_arr = $pjOptionModel->getPairs();
		$this->tpl['option_arr'] = $this->option_arr;
		
		if (isset($this->tpl['option_arr']['timezone']))
		{
			$offset = $this->option_arr['timezone'] / 3600;
			if ($offset > 0)
			{
				$offset = "-".$offset;
			} elseif ($offset < 0) {
				$offset = "+".abs($offset);
			} elseif ($offset === 0) {
				$offset = "+0";
			}
		
			pjAppController::setTimezone('Etc/GMT'.$offset);
			if (strpos($offset, '-') !== false)
			{
				$offset = str_replace('-', '+', $offset);
			} elseif (strpos($offset, '+') !== false) {
				$offset = str_replace('+', '-', $offset);
			}
			pjAppController::setMySQLServerTime($offset . ":00");
		}
	}

	function beforeRender()
	{
		
	}
	
	function bookingSave()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			if (isset($_POST['rbSummaryForm']))
			{
				pjObject::import('Model', array('pjBooking', 'pjService'));
				$pjBookingModel = new pjBookingModel();
				$pjServiceModel = new pjServiceModel();
				
				$data = array();
				
				if ($this->option_arr['payment_disable'] == 'Yes')
				{
					$data['status'] = $this->option_arr['booking_status'];
				} else {
					$data['status'] = $this->option_arr['booking_status']; //$this->option_arr['payment_status']
				}
				
				$date = pjUtil::formatDate($this->_get('date'), $this->option_arr['date_format']);
				$time = $this->_get('hour') . ":" . $this->_get('minutes') . ":00";
				$code = $this->_is('code') ? $this->_get('code') : NULL;
				
				$opts = pjAppController::getPrice($this->option_arr, $date, $time, $code);
				$data['total']   = $opts['total'];
				
				$data['dt'] = $date . " " . $time;
				
				$booking_length = $this->option_arr['booking_length'] * 60;
				$services = $pjServiceModel->getAll(array('col_name' => 't1.start_time', 'direction' => 'ASC'));
				$start_hour = strtotime($time);
					
				foreach ($services as $service) {
					if ( strtotime($service['start_time']) <= $start_hour && strtotime($service['end_time']) >= $start_hour) {
						$booking_length = $service['s_length'] * 3600;
						break;
					}
				}
				
				$data['dt_to'] = date("Y-m-d H:i:s", strtotime($data['dt']) + $booking_length);
				$data['uuid'] = time();
				if ($this->_is('payment_method') && $this->_get('payment_method') == 'creditcard')
				{
					$data['cc_exp'] = $this->_get('cc_exp_year') . '-' . $this->_get('cc_exp_month');
				}
				$payment = 'none';
				if ($this->_is('payment_method'))
				{
					$payment = $this->_get('payment_method');
				}

				$data = array_merge($_POST, $_SESSION[$this->default_product][$this->default_order], $data);
				$table_id = $this->_get('table_id');
				$tables_id = $this->_get('tables_id');
				
				if ( ($table_id === false || (int) $table_id <= 0) && ( !isset($tables_id) || !is_array($tables_id) || count($tables_id) < 1 ) )
				{
					$data['status'] = 'enquiry';
					unset($data['total']);
					unset($data['payment_method']);
				}
				
				$booking_id = $pjBookingModel->save($data);
				if ($booking_id !== false && (int) $booking_id > 0)
				{
					$booking_arr = $pjBookingModel->get($booking_id);
					
					if ($table_id !== false && (int) $table_id > 0)
					{
						pjObject::import('Model', array('pjBookingTable', 'pjTable'));
						$pjBookingTableModel = new pjBookingTableModel();
						$pjTableModel = new pjTableModel();
						
						$pjBookingTableModel->save(array('booking_id' => $booking_id, 'table_id' => $table_id));
						if (count($booking_arr) > 0)
						{
							$pjBookingTableModel->addJoin($pjBookingTableModel->joins, $pjTableModel->getTable(), 'TT', array('TT.id' => 't1.table_id'), array('TT.name'));
							$booking_arr['table_arr'] = $pjBookingTableModel->getAll(array('t1.booking_id' => $booking_arr['id']));
						}
						$op = 2;
						$json = array('code' => 200, 'text' => '', 'booking_id' => $booking_id, 'payment' => $payment);
					
					} elseif ( isset($tables_id) && is_array($tables_id) && count($tables_id) > 0 ) {
						pjObject::import('Model', array('pjBookingTable', 'pjTable'));
						$pjBookingTableModel = new pjBookingTableModel();
						$pjTableModel = new pjTableModel();
						
						foreach ($tables_id as $table_id => $people) {
							$pjBookingTableModel->save(array('booking_id' => $booking_id, 'table_id' => $table_id));
						}
						
						if (count($booking_arr) > 0) {
							$pjBookingTableModel->addJoin($pjBookingTableModel->joins, $pjTableModel->getTable(), 'TT', array('TT.id' => 't1.table_id'), array('TT.name'));
							$booking_arr['table_arr'] = $pjBookingTableModel->getAll(array('t1.booking_id' => $booking_arr['id']));
						}
						
						$op = 2;
						$json = array('code' => 200, 'text' => '', 'booking_id' => $booking_id, 'payment' => $payment);
					
					} else {
						$booking_arr['table_arr'] = array();
						$op = 4;
						$json = array('code' => 201, 'text' => '');
					}
					
					pjFront::confirmSend($this->option_arr, $booking_arr, $this->salt, $op);
					$_SESSION[$this->default_product][$this->default_order] = array();
				} else {
					$json = array('code' => 100, 'text' => '');
				}
			} else {
				$json = array();
			}
			pjAppController::responseJson($json);
		}
	}
	
	function cancel()
	{
		$this->layout = 'empty';
		
		pjObject::import('Model', 'pjBooking');
		$pjBookingModel = new pjBookingModel();
				
		if (isset($_POST['booking_cancel']))
		{
			$arr = $pjBookingModel->get($_POST['id']);
			if (count($arr) > 0)
			{
				$pjBookingModel->update(array('status' => 'cancelled'), array("SHA1(CONCAT(`id`, `created`, '".$this->salt."'))" => array("'" . $_POST['hash'] . "'", '=', 'null'), 'limit' => 1));
				pjUtil::redirect($_SERVER['PHP_SELF'] . '?controller=pjFront&action=cancel&err=200');
			}
		} else {
			if (isset($_GET['hash']) && isset($_GET['id']))
			{
				pjObject::import('Model', array('pjCountry'));
				$pjCountryModel = new pjCountryModel();
				
				$pjBookingModel->addJoin($pjBookingModel->joins, $pjCountryModel->getTable(), 'TC', array('TC.id' => 't1.c_country'), array('TC.country_title'));
				$arr = $pjBookingModel->get($_GET['id']);
				if (count($arr) == 0)
				{
					$this->tpl['status'] = 2;
				} else {
					if ($arr['status'] == 'cancelled')
					{
						$this->tpl['status'] = 4;
					} else {
						$hash = sha1($arr['id'] . $arr['created'] . $this->salt);
						if ($_GET['hash'] != $hash)
						{
							$this->tpl['status'] = 3;
						} else {
							pjObject::import('Model', array('pjBookingTable', 'pjTable'));
							$pjBookingTableModel = new pjBookingTableModel();
							$pjTableModel = new pjTableModel();
							$pjBookingTableModel->addJoin($pjBookingTableModel->joins, $pjTableModel->getTable(), 'TT', array('TT.id' => 't1.table_id'), array('TT.name'));
							$arr['table_arr'] = $pjBookingTableModel->getAll(array('t1.booking_id' => $arr['id']));
							
							$this->tpl['arr'] = $arr;
						}
					}
				}
			} elseif (!isset($_GET['err'])) {
				$this->tpl['status'] = 1;
			}
			$this->css[] = array('file' => 'install.css', 'path' => CSS_PATH);
		}
	}
	
	function captcha($renew=null)
	{
		$this->isAjax = true;
		
		pjObject::import('Component', 'pjCaptcha');
		$pjCaptcha = new pjCaptcha(WEB_PATH . 'obj/Anorexia.ttf', $this->default_product, $this->default_captcha, 6);
		$pjCaptcha->setImage(IMG_PATH . 'frontend/button.png');
		$pjCaptcha->init($renew);
	}

	function checkCaptcha()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (isset($_SESSION[$this->default_product][$this->default_captcha]) && strtoupper($_GET['captcha']) == $_SESSION[$this->default_product][$this->default_captcha])
			{
				$resp = array('code' => 200);
			} else {
				$resp = array('code' => 100);
			}
			pjAppController::responseJson($resp);
			exit;
		}
	}
	
	function checkPeople()
	{
		$this->isAjax = true;
	
		if ($this->isXHR() || $this->isAdmin())
		{
			$date = pjUtil::formatDate($_POST['date'], $this->option_arr['date_format']);
			
			pjObject::import('Model', array('pjTable', 'pjBooking', 'pjBookingTable'));
			$pjTableModel = new pjTableModel();
			$pjBookingModel = new pjBookingModel();
			$pjBookingTableModel = new pjBookingTableModel();

			$booking_length = $this->option_arr['booking_length'] * 60;
			$start_time = strtotime($date . " " . $_POST['hour'] . ":" . $_POST['minutes'] . ":00");
			$end_time = $start_time + $booking_length;
			
			$pjTableModel->addSubQuery($pjTableModel->subqueries, sprintf("SELECT COUNT(`table_id`)
				FROM `%1\$s`
				WHERE `table_id` = `t1`.`id`
				AND `booking_id` IN (SELECT `id` FROM `%2\$s` WHERE (
					UNIX_TIMESTAMP(`dt`) <= '%4\$u' AND UNIX_TIMESTAMP(`dt_to`) > '%3\$u'
				) AND `status` = 'confirmed')
				LIMIT 1",
				$pjBookingTableModel->getTable(),
				$pjBookingModel->getTable(),
				$start_time,
				$end_time
			), 'booked');
			$table_arr = $pjTableModel->getAll();
			$passed = false;
			foreach ($table_arr as $table)
			{
				if ((int) $table['booked'] !== 0 || (int) $_POST['people'] > $table['seats'] || (int) $_POST['people'] < $table['minimum'])
				{
					// busy
				} else {
					// available
					$passed = true;
					break;
				}
			}
			
			if ($passed)
			{
				$resp = array('code' => 200);
			} else {
				$resp = array('code' => 100);
			}
			pjAppController::responseJson($resp);
		}
	}
	
	function checkWTime()
	{
		$this->isAjax = true;
	
		if ($this->isXHR() || $this->isAdmin())
		{
			$resp = array();
			$date = pjUtil::formatDate($_GET['date'], $this->option_arr['date_format']);
			$wt_arr = pjAppController::getWorkingTime($date);
			$offset = 0;
			if ($wt_arr !== false)
			{
				# Fix 24h
				$offset = $wt_arr['end_hour'] <= $wt_arr['start_hour'] ? 86400 : 0;
			}
			$ts = strtotime(sprintf("%s %s:%s:00", $date, $_GET['hour'], $_GET['minutes']));
			$play = $this->option_arr['booking_earlier'] * 3600;
			if ($wt_arr === false)
			{
				# Day off
				$resp = array('code' => 100);
			} else {
				if (time() + $play > $ts)
				{
					# You must book X hours before
					$resp = array('code' => 101, 'booking_earlier' => (int) $this->option_arr['booking_earlier']);
				} elseif ($wt_arr['start_ts'] > $ts + $offset) {
					# We're not open yet
					$resp = array('code' => 102);
				} elseif ($wt_arr['end_ts'] + $offset < $ts + $this->option_arr['booking_length'] * 60) {
					# We're close
					$resp = array('code' => 103);
				} else {
					# OK
					$resp = array('code' => 200);
				}
			}
			pjAppController::responseJson($resp);
		}
	}

	function confirmAuthorize()
	{
		$this->isAjax = true;
		
		pjObject::import('Model', array('pjBooking', 'pjTable', 'pjBookingTable'));
		$pjBookingModel = new pjBookingModel();
		$pjBookingTableModel = new pjBookingTableModel();
		$pjTableModel = new pjTableModel();
		
		$booking_arr = $pjBookingModel->get($_POST['x_invoice_num']);
		if (count($booking_arr) > 0)
		{
			$pjBookingTableModel->addJoin($pjBookingTableModel->joins, $pjTableModel->getTable(), 'TT', array('TT.id' => 't1.table_id'), array('TT.name'));
			$booking_arr['table_arr'] = $pjBookingTableModel->getAll(array('t1.booking_id' => $booking_arr['id']));
		}
		if (count($booking_arr) == 0)
		{
			pjUtil::redirect($this->option_arr['thank_you_page']);
		}
		
		if (intval($_POST['x_response_code']) == 1)
		{
			$pjBookingModel->update(array('id' => $_POST['x_invoice_num'], 'status' => $this->option_arr['payment_status']));
			pjFront::confirmSend($this->option_arr, $booking_arr, $this->salt, 3);
		}
		pjUtil::redirect($this->option_arr['thank_you_page']);
	}

	function confirmPaypal()
	{
		$this->isAjax = true;
		
		$url = TEST_MODE ? 'ssl://sandbox.paypal.com' : 'ssl://www.paypal.com';
		$log = '';
		Front::log("\nPayPal - " . date("Y-m-d"));
		
		pjObject::import('Model', array('Booking'));
		$pjBookingModel = new pjBookingModel();
		
		$invoice = explode("_", $_POST['invoice']);
		$invoice = $invoice[1];
		
		$booking_arr = $pjBookingModel->get($invoice);
		if (count($booking_arr) == 0)
		{
			Front::log("No such booking");
			pjUtil::redirect($this->option_arr['thank_you_page']);
		}
		
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value)
		{
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		
		// post back to PayPal system to validate
		$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Host: www.paypal.com\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n";
		$header .= "Connection: close\r\n\r\n";
		$fp = fsockopen($url, 443, $errno, $errstr, 30);
		
		// assign posted variables to local variables
		$invoice = explode("_", $_POST['invoice']);
		$invoice = $invoice[1];
		$payment_status = $_POST['payment_status'];
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$txn_id = $_POST['txn_id'];
		$receiver_email = $_POST['receiver_email'];

		if (!$fp)
		{
			// HTTP ERROR
			Front::log("HTTP error");
		} else {
			fputs ($fp, $header . $req);
			while (!feof($fp))
			{
				$res = fgets ($fp, 1024);
				if (strcmp (trim($res), "VERIFIED") == 0)
				{
					pjFront::log("VERIFIED");
					if ($payment_status == "Completed")
					{
						pjFront::log("Completed");
						if ($booking_arr['txn_id'] != $txn_id)
						{
							pjFront::log("TXN_ID is OK");
							if ($receiver_email == $this->option_arr['paypal_address'])
							{
								pjFront::log("EMAIL address is OK");
								$booking_amount = $booking_arr['total'];
								if ($payment_amount == $booking_amount && $payment_currency == $this->option_arr['currency'])
								{
									pjFront::log("AMOUNT is OK, proceed with booking update");
									$pjBookingModel->update(array('id' => $invoice, 'status' => $this->option_arr['payment_status'], 'txn_id' => $txn_id, 'processed_on' => array('NOW()')));

									if (count($booking_arr) > 0)
									{
										pjObject::import('Model', array('pjTable', 'pjBookingTable'));
										$pjTableModel = new pjTableModel();
										$pjBookingTableModel = new pjBookingTableModel();
										$pjBookingTableModel->addJoin($pjBookingTableModel->joins, $pjTableModel->getTable(), 'TT', array('TT.id' => 't1.table_id'), array('TT.name'));
										$booking_arr['table_arr'] = $pjBookingTableModel->getAll(array('t1.booking_id' => $booking_arr['id']));
									}
									
									pjFront::confirmSend($this->option_arr, $booking_arr, $this->salt, 3);
									pjUtil::redirect($this->option_arr['thank_you_page']);
								}
							}
						}
					}
					pjUtil::redirect($this->option_arr['thank_you_page']);
			    } elseif (strcmp ($res, "INVALID") == 0) {
			    	pjFront::log("INVALID");
			  		pjUtil::redirect($this->option_arr['thank_you_page']);
			  	}
			}
			fclose($fp);
		}
	}

	function log($content, $filename=null)
	{
		if (TEST_MODE)
		{
			$filename = is_null($filename) ? 'payment.log' : $filename;
			@file_put_contents($filename, $content . "\n", FILE_APPEND|FILE_TEXT);
		}
	}

	function confirmSend($option_arr, $booking_arr, $salt, $opt)
	{
		if (!in_array((int) $opt, array(2, 3, 4)))
		{
			return false;
		}
		pjObject::import('Component', 'pjEmail');
		$pjEmail = new pjEmail();

		$data = pjAppController::getData($option_arr, $booking_arr, $salt);

		# Payment email
		if ($option_arr['email_payment'] == $opt)
		{
			$message = str_replace($data['search'], $data['replace'], $option_arr['email_payment_message']);
			# Send to ADMIN
			$pjEmail->send($option_arr['email_address'], $option_arr['email_payment_subject'], $message, $option_arr['email_address']);
			# Send to CLIENT
			$pjEmail->send($booking_arr['c_email'], $option_arr['email_payment_subject'], $message, $option_arr['email_address']);
		}
		
		# Confirmation email
		if ($option_arr['email_confirmation'] == $opt)
		{
			$message = str_replace($data['search'], $data['replace'], $option_arr['email_confirmation_message']);
			# Send to ADMIN
			$pjEmail->send($option_arr['email_address'], $option_arr['email_confirmation_subject'], $message, $option_arr['email_address']);
			# Send to CLIENT
			$pjEmail->send($booking_arr['c_email'], $option_arr['email_confirmation_subject'], $message, $option_arr['email_address']);
		}
		
		# Enquiry email
		if ($option_arr['email_enquiry'] == $opt)
		{
			$message = str_replace($data['search'], $data['replace'], $option_arr['email_enquiry_message']);
			# Send to ADMIN
			$pjEmail->send($option_arr['email_address'], $option_arr['email_enquiry_subject'], $message, $option_arr['email_address']);
			# Send to CLIENT
			$pjEmail->send($booking_arr['c_email'], $option_arr['email_enquiry_subject'], $message, $option_arr['email_address']);
		}
		
		if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
				
		} else {
		# SMS
			$message = str_replace($data['search'], $data['replace'], $option_arr['reminder_sms_message']);
			
			$phone = $booking_arr['c_phone'];
			if ( strpos($phone, '0') == 0 ) {
				$phone = ltrim($phone, '0');
			} 
			
			$phone = isset($option_arr['reminder_sms_country_code']) ? $option_arr['reminder_sms_country_code'] . $phone : $phone;
			
			$send_address = isset($option_arr['reminder_sms_send_address']) ? $option_arr['reminder_sms_send_address'] : $phone;
			
			$sendsms = new pjSMS;
			# Send to CLIENT
			$sendsms->sendSMS($send_address, $phone, $message);
		}
	}
	
	function getMap()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			pjObject::import('Model', array('pjTable', 'pjBooking', 'pjBookingTable'));
			$pjTableModel = new pjTableModel();
			$pjBookingModel = new pjBookingModel();
			$pjBookingTableModel = new pjBookingTableModel();

			$booking_length = $this->option_arr['booking_length'] * 60;
			$start_time = strtotime(pjUtil::formatDate($_GET['date'], $this->option_arr['date_format']) . " " . $_GET['hour'] . ":" . $_GET['minutes'] . ":00");
			$end_time = $start_time + $booking_length;
			
			$pjTableModel->addSubQuery($pjTableModel->subqueries, sprintf("SELECT COUNT(`table_id`)
				FROM `%1\$s`
				WHERE `table_id` = `t1`.`id`
				AND `booking_id` IN (SELECT `id` FROM `%2\$s` WHERE (
					UNIX_TIMESTAMP(`dt`) < '%4\$u' AND UNIX_TIMESTAMP(`dt_to`) > '%3\$u'
				) AND `status` = 'confirmed')
				LIMIT 1",
				$pjBookingTableModel->getTable(),
				$pjBookingModel->getTable(),
				$start_time,
				$end_time
			), 'booked');
			$this->tpl['table_arr'] = $pjTableModel->getAll();
		}
	}
	
	function getTerms()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			
		}
	}
	
	function getWTime()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			$this->tpl['wt_arr'] = pjAppController::getWorkingTime(pjUtil::formatDate($_GET['date'], $this->option_arr['date_format']));
		}
	}

	function index()
	{
		
	}

	function load()
	{
        if (!isset($_GET['v']) || empty($_GET['v'])) {
            die;
        }

		ob_start();
        $config = require realpath(CONTROLLERS_PATH.'../../../../config.php');
        $hashids = new Hashids($config['secret_key'], 8);
        $content = $hashids->decrypt($_GET['v']);
        if (!is_array($content) || !isset($content[0])) {
            die;
        }
        $owner_id = $content[0];
        @session_start();
        $_SESSION['owner_id'] = $owner_id;

		header("Content-type: text/javascript");
		
		pjObject::import('Model', array('pjWorkingTime', 'pjDate'));
		$pjWorkingTimeModel = new pjWorkingTimeModel();
		$pjDateModel = new pjDateModel();
		
		$days_off = array();
		$w_arr = $pjWorkingTimeModel->get(1);
		if (count($w_arr) > 0)
		{
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
		
		$this->tpl['days_off'] = $days_off;
		
		$dates_off = $dates_on = array();
		$d_arr = $pjDateModel->getAll(array('t1.date' => array('CURDATE()', '>=', 'null')));
		foreach ($d_arr as $date)
		{
			if ($date['is_dayoff'] == 'T')
			{
				$dates_off[] = $date['date'];
			} else {
				$dates_on[] = $date['date'];
			}
		}
		$this->tpl['dates_off'] = $dates_off;
		$this->tpl['dates_on'] = $dates_on;
	}
	
	function loadCss()
	{
		$arr = array(
			array('file' => 'calendar.css', 'path' => LIBS_PATH . 'calendarJS/themes/light/'),
			array('file' => 'overlay.css', 'path' => LIBS_PATH . 'overlayJS/themes/light/'),
			array('file' => 'RBooking.css', 'path' => CSS_PATH)
		);
		header("Content-type: text/css");
		foreach ($arr as $item)
		{
			echo str_replace(
				array('../img/', 'url(overlay-'),
				array(IMG_PATH, 'url('.LIBS_PATH.'overlayJS/overlay-'),
				@file_get_contents($item['path'] . $item['file'])) . "\n";
		}
		exit;
	}
	
	function loadSearch()
	{
		$this->isAjax = true;
		
		if ($this->isXHR() || $this->isAdmin())
		{
			if (!isset($_SESSION[$this->default_product][$this->default_order]) || count($_SESSION[$this->default_product][$this->default_order]) === 0)
			{
				$_SESSION[$this->default_product][$this->default_order] = array();
				$this->_set('hour', "09");
				$this->_set('minutes', "00");
				$this->_set('date', date($this->option_arr['date_format'], strtotime("+1 day")));
				$this->_set('date_format', $this->option_arr['date_format']);
			}
			
			if ($this->_get('date_format') != $this->option_arr['date_format'])
			{
				$this->_set('date', pjUtil::formatDate($this->_get('date'), $this->_get('date_format'), $this->option_arr['date_format']));
				$this->_set('date_format', $this->option_arr['date_format']);
			}
			
			$date = pjUtil::formatDate($this->_get('date'), $this->option_arr['date_format']);
			$this->tpl['wt_arr'] = pjAppController::getWorkingTime($date);
			
			pjObject::import('Model', array('pjService', 'pjFormStyle') );
			
			$pjFormStyleModel = new pjFormStyleModel();
			$this->tpl ['formstyle'] = $pjFormStyleModel->getAll();
			
			$pjServiceModel = new pjServiceModel();
			$this->tpl['service_arr'] = $pjServiceModel->getAll(array('col_name' => 't1.start_time', 'direction' => 'ASC'));
			
			
			$table_id = $this->_get('table_id');
			if ($table_id !== false && (int) $table_id > 0)
			{
				pjObject::import('Model', 'pjTable');
				$pjTableModel = new pjTableModel();
				$this->tpl['table_arr'] = $pjTableModel->getAll();
			}
			unset($_SESSION[$this->default_product][$this->default_order]['table_id']);
		}
	}
	
	function loadBookingForm()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (isset($_POST['rbSearch']))
			{
				$_SESSION[$this->default_product][$this->default_order] = array_merge($_SESSION[$this->default_product][$this->default_order], $_POST);
				if ($this->_get('table_id') && !isset($_POST['table_id']))
				{
					unset($_SESSION[$this->default_product][$this->default_order]['table_id']);
				}
				if (!isset($_POST['table_id']))
				{
					pjObject::import('Model', array('pjTable', 'pjBooking', 'pjBookingTable', 'pjService'));
					$pjTableModel = new pjTableModel();
					$pjBookingModel = new pjBookingModel();
					$pjBookingTableModel = new pjBookingTableModel();
					$pjServiceModel = new pjServiceModel();
					
					$booking_length = $this->option_arr['booking_length'] * 60;
					$services = $pjServiceModel->getAll(array('col_name' => 't1.start_time', 'direction' => 'ASC'));
					$start_hour = strtotime($this->_get('hour') . ":" . $this->_get('minutes') . ":00");
					
					foreach ($services as $service) {
						if ( strtotime($service['start_time']) < $start_hour && strtotime($service['end_time']) >= $start_hour) {
							$booking_length = $service['s_length'] * 3600;
							break;
						}
					}
					
					$start_time = strtotime(pjUtil::formatDate($this->_get('date'), $this->option_arr['date_format']) . " " . $this->_get('hour') . ":" . $this->_get('minutes') . ":00");
					$end_time = $start_time + $booking_length;
					
					$sql = sprintf("SELECT `t1`.`id`
						FROM `%1\$s` AS `t1`
						WHERE ('%6\$u' BETWEEN `t1`.`minimum` AND `t1`.`seats`)
						AND `t1`.`id` NOT IN (SELECT `table_id` FROM `%2\$s` WHERE `booking_id` IN (SELECT `id` FROM `%3\$s` WHERE (
								UNIX_TIMESTAMP(`dt`) < '%5\$u' AND UNIX_TIMESTAMP(`dt_to`) > '%4\$u'
							) AND `status` = 'confirmed'))
						LIMIT 1",
						$pjTableModel->getTable(),
						$pjBookingTableModel->getTable(),
						$pjBookingModel->getTable(),
						$start_time,
						$end_time,
						$this->_get('people')
					);
					
					$table_arr = $pjTableModel->execute($sql);
					if (count($table_arr) === 1)
					{
						$this->_set('table_id', $table_arr[0]['id']);
					} else {
						
						$sql = sprintf("SELECT `t1`.*
							FROM `%1\$s` AS `t1`
							WHERE `t1`.`id` NOT IN (SELECT `table_id` FROM `%2\$s` WHERE `booking_id` IN (SELECT `id` FROM `%3\$s` WHERE (
									UNIX_TIMESTAMP(`dt`) < '%5\$u' AND UNIX_TIMESTAMP(`dt_to`) > '%4\$u'
								) AND `status` = 'confirmed')) 
								ORDER BY `t1`.`seats` DESC",
									$pjTableModel->getTable(),
									$pjBookingTableModel->getTable(),
									$pjBookingModel->getTable(),
									$start_time,
									$end_time
						);
						
						$table_arr = $pjTableModel->execute($sql);
						$seats = 0;
						$people = $this->_get('people');
						
						foreach ($table_arr as $table) {
							$seats += $table['seats'];
						}
						
						if ( $people > 0 && $people <= $seats ) {
							$table_set = array();
							
							while ( $people > 0 && count($table_arr) > 0 ) {
								$table_first = $table_arr[0];
								$table_last = $table_arr[count($table_arr) -1];
								
								if ( $people - $table_first['seats'] <= $table_last['seats'] && $people - $table_first['seats'] >= $table_last['minimum'] ) {
									$table_set[$table_first['id']] = $table_first['seats'];
									$table_set[$table_last['id']] = $table_last['seats'];
									$people = 0;
									
								} elseif ($people - $table_first['seats'] > $table_last['seats'] ) {
									
									$table_set[$table_first['id']] = $table_first['seats'];
									$people = $people - $table_first['seats'];
									
									if (count($table_arr) > 2) {
										
										for ($i = count($table_arr) - 2; $i > 0; $i-- ) {
											
											if ($people <= $table_arr[$i]['seats'] && $people >= $table_arr[$i]['minimum']) {
												$table_set[$table_arr[$i]['id']] = $table_arr[$i]['seats'];
												$people = 0;
												break;
											}
										} 
									}
									
									if ($people > 0) {
										unset($table_arr[0]);
										$table_arr = array_values($table_arr);
									}
									
								} elseif ($people - $table_first['seats'] <= 0) {
									$table_set[$table_first['id']] = $table_first['seats'];
									$people = 0;
									
								}
								else{
									unset($table_arr[0]);
									$table_arr = array_values($table_arr);
								} 
							}
							
							if ($people <= 0) {
								$this->_set('tables_id', $table_set);
							}
							
						}
						
					}
				}
			}
			if (in_array($this->option_arr['bf_include_country'], array(2, 3)))
			{
				pjObject::import('Model', 'pjCountry');
				$pjCountryModel = new pjCountryModel();
				$this->tpl['country_arr'] = $pjCountryModel->getAll(array('t1.status' => 'T', 'col_name' => 't1.country_title', 'direction' => 'asc'));
			}
			
			$date = pjUtil::formatDate($this->_get('date'), $this->option_arr['date_format']);
			$time = $this->_get('hour') . ":" . $this->_get('minutes') . ":00";
			$code = $this->_is('code') ? $this->_get('code') : NULL;
			
			$resp = pjAppController::getPrice($this->option_arr, $date, $time, $code);
			if (isset($resp['discount_formated']))
			{
				include ROOT_PATH . 'app/locale/'. $this->getLanguage() . '.php';
				$resp['discount_text'] = $resp['discount_formated'] . " " . $RB_LANG['front']['4_discount'];
			}
			$this->tpl['price_arr'] = $resp;
		}
	}
	
	function loadSummaryForm()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (isset($_POST['rbBookingForm']))
			{
				if ( isset($_POST['c_notes'])) {
					$_POST['c_notes'] = strip_tags($_POST['c_notes']);
				}
				
				$_SESSION[$this->default_product][$this->default_order] = array_merge($_SESSION[$this->default_product][$this->default_order], $_POST);
			}
			if (in_array($this->option_arr['bf_include_country'], array(2, 3)))
			{
				pjObject::import('Model', 'pjCountry');
				$pjCountryModel = new pjCountryModel();
				$this->tpl['country_arr'] = $pjCountryModel->get($this->_get('c_country'));
			}
			
			$date = pjUtil::formatDate($this->_get('date'), $this->option_arr['date_format']);
			$time = $this->_get('hour') . ":" . $this->_get('minutes') . ":00";
			$code = $this->_is('code') ? $this->_get('code') : NULL;
			
			$resp = pjAppController::getPrice($this->option_arr, $date, $time, $code);
			if (isset($resp['discount_formated']))
			{
				include ROOT_PATH . 'app/locale/'. $this->getLanguage() . '.php';
				$resp['discount_text'] = $resp['discount_formated'] . " " . $RB_LANG['front']['4_discount'];
			}
			$this->tpl['price_arr'] = $resp;
		}
	}
	
	function loadPayment()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			pjObject::import('Model', array('pjBooking'));
			$pjBookingModel = new pjBookingModel();
			
			$arr = $pjBookingModel->get($_POST['id']);
			$this->tpl['arr'] = $arr;
		}
	}
	
	function removePromo()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$resp = array();
			
			$date = pjUtil::formatDate($this->_get('date'), $this->option_arr['date_format']);
			$time = $this->_get('hour') . ":" . $this->_get('minutes') . ":00";

			if ($this->_is('code'))
			{
				unset($_SESSION[$this->default_product][$this->default_order]['code']);
			}
			$resp = pjAppController::getPrice($this->option_arr, $date, $time);
			$resp['code'] = 200;
			pjAppController::responseJson($resp);
		}
	}
}

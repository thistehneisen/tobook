<?php
require_once CONTROLLERS_PATH . 'AppController.controller.php';
/**
 * Front controller
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers
 */
class Front extends AppController
{
/**
 * Define layout used by controller
 *
 * @access public
 * @var string
 */
	var $layout = 'front';
/**
 * Define name of session variable used by Captcha component
 *
 * @access public
 * @var string
 */
	var $default_captcha = 'TSBCalCaptcha';
	var $cartName = 'TSBC_Cart';
/**
 * Constructor
 */
	function Front()
	{
		ob_start();
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::beforeFilter()
 * @access public
 * @return void
 */
	function beforeFilter()
	{
		if (isset($_GET['cid']) && (int) $_GET['cid'] > 0)
		{
			Object::import('Model', 'Option');
			$OptionModel = new OptionModel();
			$this->option_arr = $OptionModel->getPairs($_GET['cid']);
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
			
				AppController::setTimezone('Etc/GMT'.$offset);
				if (strpos($offset, '-') !== false)
				{
					$offset = str_replace('-', '+', $offset);
				} elseif (strpos($offset, '+') !== false) {
					$offset = str_replace('+', '-', $offset);
				}
				AppController::setMySQLServerTime($offset . ":00");
			}
		}
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::beforeRender()
 * @access public
 */
	function beforeRender()
	{
		
	}
/**
 * Show booking form via AJAX
 *
 * @access public
 * @return void
 */
	function bookingForm()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (in_array($this->option_arr['bf_include_country'], array(2, 3)))
			{
				Object::import('Model', 'Country');
				$CountryModel = new CountryModel();
				$this->tpl['country_arr'] = $CountryModel->getAll(array('t1.status' => 'T', 'col_name' => 't1.country_title', 'direction' => 'asc'));
			}
			$this->tpl['amount'] = AppController::getCartTotal($_GET['cid'], $this->cartName, $this->option_arr);
		}
	}
/**
 * Show booking summary via AJAX
 *
 * @access public
 * @return void
 */
	function bookingSummary()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$this->tpl['amount'] = AppController::getCartTotal($_GET['cid'], $this->cartName, $this->option_arr);
			
			if (in_array($this->option_arr['bf_include_country'], array(2, 3)))
			{
				Object::import('Model', 'Country');
				$CountryModel = new CountryModel();
				$this->tpl['country_arr'] = $CountryModel->getAll(array('t1.status' => 'T', 'col_name' => 't1.country_title', 'direction' => 'asc'));
			}
		}
	}
/**
 * Save booking via AJAX
 *
 * @access public
 * @return json
 */
	function bookingSave()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('Booking', 'BookingSlot'));
			$BookingModel = new BookingModel();
			$BookingSlotModel = new BookingSlotModel();
			
			$passCheck = true;
			foreach ($_SESSION[$this->cartName] as $cid => $date_arr)
			{
				if (!$passCheck) break;
				if ($cid != $_GET['cid']) continue;
				foreach ($date_arr as $date => $time_arr)
				{
					if (!$passCheck) break;
					$t_arr = AppController::getRawSlots($cid, $date, $this->option_arr);
					if ($t_arr === false)
					{
						$passCheck = false;
						break;
					}
					foreach ($time_arr as $time => $q)
					{
						if (!$passCheck) break;
						list($start_ts, $end_ts) = explode("|", $time);
						if ($t_arr['slot_limit'] <= $BookingSlotModel->checkBooking($cid, $start_ts, $end_ts, $BookingModel, $this->option_arr['timezone']))
						{
						  
							$passCheck = false;
							break;
						}
					}
				}
			}
			
			if (!$passCheck)
			{
				$json = "{'code':101,'text':''}";
				header("Content-type: text/json");
				echo $json;
				exit;
			}
						
			$opts = AppController::getCartTotal($_GET['cid'], $this->cartName, $this->option_arr);
			
			$data = array();
			if ($this->option_arr['payment_disable'] == 'Yes')
			{
				$data['booking_status'] = $this->option_arr['booking_status'];
			} else {
				$data['booking_status'] = $this->option_arr['booking_status']; //$this->option_arr['payment_status']
			}
			$data['booking_total']   = $opts['total'];
			$data['booking_deposit'] = $opts['deposit'];
			$data['booking_tax']     = $opts['tax'];
			$processing = $data['booking_deposit'] > 0 ? 1 : 0; 
			
			if (isset($_POST['payment_method']) && $_POST['payment_method'] == 'creditcard')
			{
				$data['cc_exp'] = $_POST['cc_exp_year'] . '-' . $_POST['cc_exp_month'];
			}
						
			$insert_id = $BookingModel->save(array_merge($_POST, $data));
			if ($insert_id !== false && (int) $insert_id > 0)
			{
				$data = array();
				$data['booking_id'] = $insert_id;
				foreach ($_SESSION[$this->cartName] as $cid => $date_arr)
				{
					if ($cid != $_GET['cid'])
					{
						continue;
					}
					foreach ($date_arr as $date => $time_arr)
					{
						$data['booking_date'] = $date;
						foreach ($time_arr as $time => $q)
						{
							list($start_ts, $end_ts) = explode("|", $time);
							$data['start_time'] = date("H:i:s", $start_ts);
							$data['end_time'] = date("H:i:s", $end_ts);
							$data['start_ts'] = $start_ts;
							$data['end_ts'] = $end_ts;
							$BookingSlotModel->save($data);
						}
					}
				}

				$booking_arr = $BookingModel->get($insert_id);
				if ($this->option_arr['reminder_enable'] = 'Yes') {
					Front::confirmSend($this->option_arr, $booking_arr, $this->salt, 2);
				}
				
				$_SESSION[$this->cartName] = array();
				$json = "{'code':200,'text':'','booking_id': $insert_id, 'payment':'".@$_POST['payment_method']."', 'processing' : $processing}";
			} else {
				$json = "{'code':100,'text':''}";
			}
			header("Content-type: text/json");
			echo $json;
		}
	}
/**
 * Init calendar via AJAX
 *
 * @access public
 * @return void
 */
	function calendar()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$this->load();
		}
	}
/**
 * Cancel booking
 *
 * @access public
 * @return void
 */
	function cancel()
	{
		$this->layout = 'empty';
		
		Object::import('Model', 'Booking');
		$BookingModel = new BookingModel();
				
		if (isset($_POST['booking_cancel']))
		{
			$arr = $BookingModel->get($_POST['id']);
			if (count($arr) > 0)
			{
				$BookingModel->update(array('booking_status' => 'cancelled'), array("SHA1(CONCAT(`id`, `created`, '".$this->salt."'))" => array("'" . $_POST['hash'] . "'", '=', 'null'), 'limit' => 1));
				Util::redirect($_SERVER['PHP_SELF'] . '?controller=Front&action=cancel&err=200');
			}
		} else {
			if (isset($_GET['hash']) && isset($_GET['id']))
			{
				Object::import('Model', 'Country');
				$CountryModel = new CountryModel();
				
				$BookingModel->addJoin($BookingModel->joins, $CountryModel->getTable(), 'TC', array('TC.id' => 't1.customer_country'), array('TC.country_title'));
				$arr = $BookingModel->get($_GET['id']);
				if (count($arr) == 0)
				{
					$this->tpl['status'] = 2;
				} else {
					if ($arr['booking_status'] == 'cancelled')
					{
						$this->tpl['status'] = 4;
					} else {
						$hash = sha1($arr['id'] . $arr['created'] . $this->salt);
						if ($_GET['hash'] != $hash)
						{
							$this->tpl['status'] = 3;
						} else {
							Object::import('Model', 'BookingSlot');
							$BookingSlotModel = new BookingSlotModel();
							$arr['slot_arr'] = $BookingSlotModel->getAll(array('t1.booking_id' => $arr['id'], 'col_name' => 't1.start_ts', 'direction' => 'asc'));
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
/**
 * Display captcha
 *
 * @param mixed $renew
 * @access public
 * @return binary
 */
	function captcha($renew=null)
	{
		$this->isAjax = true;
		
		Object::import('Component', 'Captcha');
		$Captcha = new Captcha(WEB_PATH . 'obj/Anorexia.ttf', $this->default_captcha, 6);
		$Captcha->setImage(IMG_PATH . 'button.png');
		$Captcha->init($renew);
	}
/**
 * Check captcha via AJAX
 *
 * @access public
 * @return json
 */
	function checkCaptcha()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if (isset($_SESSION[$this->default_captcha]) && strtoupper($_GET['captcha']) == $_SESSION[$this->default_captcha])
			{
				$json = "{'code':200,'text':''}";
			} else {
				$json = "{'code':100,'text':''}";
			}
			header("Content-type: text/json");
			echo $json;
		}
	}
/**
 * Authorize.NET confirmation: Send email and redirect to "Thank you" page
 *
 * @access public
 * @return void
 */
	function confirmAuthorize()
	{
		$this->isAjax = true;
		
		Object::import('Model', array('Booking', 'Option'));
		$BookingModel = new BookingModel();
		$OptionModel = new OptionModel();
		
		$option_arr = $OptionModel->getPairs($_POST['x_custom_calendar_id']);
		$booking_arr = $BookingModel->get($_POST['x_invoice_num']);
		if (count($booking_arr) == 0)
		{
			Util::redirect($option_arr['thank_you_page']);
		}
		
		if (intval($_POST['x_response_code']) == 1)
		{
			$BookingModel->update(array('id' => $_POST['x_invoice_num'], 'booking_status' => $option_arr['payment_status']));
			Front::confirmSend($option_arr, $booking_arr, $this->salt, 3);
		}
		Util::redirect($option_arr['thank_you_page']);
	}
/**
 * PayPal confirmation: Send email and redirect to "Thank you" page
 * Use as IPN too
 *
 * @access public
 * @return void
 */
	function confirmPaypal()
	{
		$this->isAjax = true;
		
		$url = TEST_MODE ? 'ssl://sandbox.paypal.com' : 'ssl://www.paypal.com';
		$log = '';
		Front::log("\nPayPal - " . date("Y-m-d"));
		
		Object::import('Model', array('Booking', 'Option'));
		$BookingModel = new BookingModel();
		$OptionModel = new OptionModel();
		
		$option_arr = $OptionModel->getPairs($_POST['custom']);
		$booking_arr = $BookingModel->get($_POST['invoice']);
		if (count($booking_arr) == 0)
		{
			Front::log("No such booking");
			Util::redirect($option_arr['thank_you_page']);
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
		$invoice = $_POST['invoice'];
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
					Front::log("VERIFIED");
					if ($payment_status == "Completed")
					{
						Front::log("Completed");
						if ($booking_arr['txn_id'] != $txn_id)
						{
							Front::log("TXN_ID is OK");
							if ($receiver_email == $option_arr['paypal_address'])
							{
								Front::log("EMAIL address is OK");
								$booking_amount = $booking_arr['payment_option'] == 'deposit' ? $booking_arr['booking_deposit'] : $booking_arr['booking_total'];
								if ($payment_amount == $booking_amount && $payment_currency == $option_arr['currency'])
								{
									Front::log("AMOUNT is OK, proceed with booking update");
									$BookingModel->update(array('id' => $invoice, 'booking_status' => $option_arr['payment_status'], 'txn_id' => $txn_id, 'processed_on' => array('NOW()')));
									Front::confirmSend($option_arr, $booking_arr, $this->salt, 3);
									Util::redirect($option_arr['thank_you_page']);
								}
							}
						}
					}
					Util::redirect($option_arr['thank_you_page']);
			    } elseif (strcmp ($res, "INVALID") == 0) {
			    	Front::log("INVALID");
			  		Util::redirect($option_arr['thank_you_page']);
			  	}
			}
			fclose($fp);
		}
	}
/**
 * Send email
 *
 * @param array $option_arr
 * @param array $booking_arr
 * @param string $salt
 * @param int $opt
 * @access public
 * @return void
 * @static
 */
	function confirmSend($option_arr, $booking_arr, $salt, $opt)
	{
		if (!in_array((int) $opt, array(2, 3)))
		{
			return false;
		}
		
		if (!isset($_GET['cid']))
		{
			# Mean that beforeFilter didn't triggered 'setTimezone' and 'setMySQLServerTime', so call it manual
			if (isset($option_arr['timezone']))
			{
				$offset = $option_arr['timezone'] / 3600;
				if ($offset > 0)
				{
					$offset = "-".$offset;
				} elseif ($offset < 0) {
					$offset = "+".abs($offset);
				} elseif ($offset === 0) {
					$offset = "+0";
				}
			
				AppController::setTimezone('Etc/GMT'.$offset);
				if (strpos($offset, '-') !== false)
				{
					$offset = str_replace('-', '+', $offset);
				} elseif (strpos($offset, '+') !== false) {
					$offset = str_replace('+', '-', $offset);
				}
				AppController::setMySQLServerTime($offset . ":00");
			}
		}
		
		Object::import('Component', 'Email');
		$Email = new Email();

		$country = NULL;
		if (!empty($booking_arr['customer_country']))
		{
			Object::import('Model', 'Country');
			$CountryModel = new CountryModel();
			$country_arr = $CountryModel->get($booking_arr['customer_country']);
			if (count($country_arr) > 0)
			{
				$country = $country_arr['country_title'];
			}
		}
		
		$cancelURL = INSTALL_URL . 'index.php?controller=Front&action=cancel&cid='.$booking_arr['calendar_id'].'&id='.$booking_arr['id'].'&hash='.sha1($booking_arr['id'].$booking_arr['created'].$salt);
		$datetime = AppController::buildDateTime($booking_arr['id'], $option_arr);
		$search = array('{Name}', '{Email}', '{Phone}', '{Country}', '{City}', '{Address}', '{Zip}', '{Notes}', '{CCType}', '{CCNum}', '{CCExp}', '{CCSec}', '{PaymentMethod}', '{PaymentOption}', '{BookingSlots}', '{Deposit}', '{Total}', '{Tax}', '{BookingID}', '{CancelURL}');
		$replace = array($booking_arr['customer_name'], $booking_arr['customer_email'], $booking_arr['customer_phone'], $country, $booking_arr['customer_city'], $booking_arr['customer_address'], $booking_arr['customer_zip'], $booking_arr['customer_notes'], $booking_arr['cc_type'], $booking_arr['cc_num'], ($booking_arr['payment_method'] == 'creditcard' ? str_replace("-", "/", $booking_arr['cc_exp']) : NULL), $booking_arr['cc_code'], $booking_arr['payment_method'], $booking_arr['payment_option'], $datetime, $booking_arr['booking_deposit'] . " " . $option_arr['currency'], $booking_arr['booking_total'] . " " . $option_arr['currency'], $booking_arr['booking_tax'] . " " . $option_arr['currency'], $booking_arr['id'], $cancelURL);
				
		# Payment email
		if ($option_arr['email_payment'] == $opt)
		{
			$message = str_replace($search, $replace, $option_arr['email_payment_message']);
			# Send to ADMIN
			$Email->send($option_arr['email_address'], $option_arr['email_payment_subject'], $message, $option_arr['email_address']);
			# Send to CLIENT
			$Email->send($booking_arr['customer_email'], $option_arr['email_payment_subject'], $message, $option_arr['email_address']);
		}
		
		# Confirmation email
		if ($option_arr['email_confirmation'] == $opt)
		{
			$message = str_replace($search, $replace, $option_arr['email_confirmation_message']);
			# Send to ADMIN
			$Email->send($option_arr['email_address'], $option_arr['email_confirmation_subject'], $message, $option_arr['email_address']);
			# Send to CLIENT
			$Email->send($booking_arr['customer_email'], $option_arr['email_confirmation_subject'], $message, $option_arr['email_address']);
		}
		
		# SMS
			$message = str_replace($search, $replace, $option_arr['reminder_sms_message']);
				
			$phone = $booking_arr['customer_phone'];
			if ( strpos($phone, '0') == 0 ) {
				$phone = ltrim($phone, '0');
			}
				
			$phone = isset($option_arr['reminder_sms_country_code']) ? $option_arr['reminder_sms_country_code'] . $phone : $phone;
				
			$sendsms = new pjSMS;
			# Send to CLIENT
			$sendsms->sendSMS($phone, $phone, $message);
		
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::index()
 */
	function index()
	{
		
	}
/**
 * Init calendar
 *
 * @access public
 * @return void
 */
	function load()
	{
		Object::import('Model', array('Calendar', 'WorkingTime', 'Date', 'Booking', 'BookingSlot'));
		$CalendarModel = new CalendarModel();
								
		$arr = $CalendarModel->get($_GET['cid']);
		if (count($arr) == 0)
		{
			$this->tpl['status'] = 999; //FIXME
			return;
		}
				
		$month = isset($_GET['month']) && in_array((int) $_GET['month'], range(1,12)) ? (int) $_GET['month'] : date("n");
		$year = isset($_GET['year']) && preg_match('/\d{4}/', (int) $_GET['year']) ? (int) $_GET['year'] : date("Y");
		
		$WorkingTimeMode = new WorkingTimeModel();
		$DateModel = new DateModel();
		$BookingModel = new BookingModel();
		$BookingSlotModel = new BookingSlotModel();
		
		Object::import('Component', 'TSBCalendar');
		$TSBCalendar = new TSBCalendar();
		
		$TSBCalendar->workingTime = $WorkingTimeMode->get($_GET['cid']);
		$TSBCalendar->datesOff = $DateModel->getDatesOff($_GET['cid'], $month, $year);
		
		$BookingSlotModel->addJoin($BookingSlotModel->joins, $BookingModel->getTable(), 'TB', array('TB.id' => 't1.booking_id', 'TB.calendar_id' => $_GET['cid'],'(booking_status' =>" 'pending' OR booking_status='confirmed' ) "), array('TB.calendar_id'), 'inner');
		$TSBCalendar->bookings = $BookingSlotModel->getBookings($month, $year);
		
		$TSBCalendar->monthYearFormat = $this->option_arr['month_year_format'];
		$TSBCalendar->calendar_id = $_GET['cid'];
		$TSBCalendar->setStartDay($this->option_arr['week_start']);
		$TSBCalendar->timeFormat = $this->option_arr['time_format'];
		$TSBCalendar->options = $this->option_arr;
				
		require ROOT_PATH . 'app/locale/'. $this->getLanguage() . '.php';
		
		$TSBCalendar->setPrevLink($TS_LANG['prev_link']);
		$TSBCalendar->setNextLink($TS_LANG['next_link']);
		$TSBCalendar->setPrevTitle($TS_LANG['prev_title']);
		$TSBCalendar->setNextTitle($TS_LANG['next_title']);
		
		$dayNames = array();
		foreach (range(0, 6) as $i)
		{
			$dayNames[] = $TS_LANG['weekday_name'][$i];
		}
		$TSBCalendar->setDayNames($dayNames);
		
		$monthNames = array();
		foreach (range(1, 12) as $i)
		{
			$monthNames[] = $TS_LANG['month_name'][$i];
		}
		$TSBCalendar->setMonthNames($monthNames);
		
		$this->tpl['calendar'] = $TSBCalendar;
	}
/**
 * Write given $content to file
 *
 * @param string $content
 * @param string $filename If omitted use 'payment.log'
 * @access public
 * @return void
 * @static
 */
	function log($content, $filename=null)
	{
		if (TEST_MODE)
		{
			$filename = is_null($filename) ? 'payment.log' : $filename;
			@file_put_contents($filename, $content . "\n", FILE_APPEND|FILE_TEXT);
		}
	}
/**
 * Load payment form
 *
 * @access public
 * @return void
 */
	function paymentForm()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('Booking'));
			$BookingModel = new BookingModel();
			
			$arr = $BookingModel->get($_POST['id']);
			$this->tpl['arr'] = $arr;
		}
	}
/**
 * Get slots via AJAX
 *
 * @access public
 * @return void
 */
	function slots()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('BookingSlot', 'Booking'));
			$BookingModel = new BookingModel();
			$BookingSlotModel = new BookingSlotModel();
			
			$t_arr = AppController::getRawSlots($_GET['cid'], $_GET['date'], $this->option_arr);
			if ($t_arr === false)
			{
				# It's Day off
				$this->tpl['dayoff'] = true;
				return;
			}
			
			$this->tpl['price_arr'] = AppController::getPricesDate($_GET['cid'], $_GET['date'], $this->option_arr);
			
			# Get booked slots for given date.
			# If 24h, include next date
			$d_arr = array(Object::escapeString($_GET['date']));
			if ($t_arr['end_ts'] < $t_arr['start_ts'])
			{
				$d_arr[] = date("Y-m-d", strtotime($_GET['date']) + 86400);
			}
			
			$BookingSlotModel->addJoin($BookingSlotModel->joins, $BookingModel->getTable(), 'TB', array('TB.id' => 't1.booking_id', 'TB.calendar_id' => $_GET['cid'],'(booking_status' =>" 'pending' OR booking_status='confirmed' ) "), array('TB.calendar_id'), 'inner');
			$bs_arr = $BookingSlotModel->getAll(array('t1.booking_date' => array("('" . join("','", $d_arr) . "')", 'IN', 'null'), 't1.booking_status' => array("('cancelled')", 'NOT IN', 'null')));
			$this->tpl['bs_arr'] = $bs_arr;
			$this->tpl['t_arr'] = $t_arr;
		}
	}
}

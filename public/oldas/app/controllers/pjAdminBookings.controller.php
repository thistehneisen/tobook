<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once PJ_CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminBookings extends pjAdmin
{
	public function pjActionCreate()
	{
		$this->checkLogin();
		if ($this->isAdmin())
		{
			if (isset($_POST['booking_create']))
			{
				$owner_id = $this->getOwnerId();
				$data = $_POST;
				$data['calendar_id'] = $this->getForeignId();
				$data['locale_id'] = $this->getLocaleId();
				$data['ip'] = $_SERVER['REMOTE_ADDR'];
				$data['owner_id'] = $owner_id;
				if ($_POST['payment_method'] != "creditcard")
				{
					$data['cc_type'] = ':NULL';
					$data['cc_num'] = ':NULL';
					$data['cc_code'] = ':NULL';
					$data['cc_exp_year'] = ':NULL';
					$data['cc_exp_month'] = ':NULL';
				}
				$id = pjBookingModel::factory($data)->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					if (!empty($_POST['tmp_hash']))
					{
						pjBookingServiceModel::factory()
							->where('tmp_hash', $_POST['tmp_hash'])
							->where('owner_id', $owner_id)
							->modifyAll(array('booking_id' => $id, 'tmp_hash' => ':NULL'));
					}
					$data['booking_id'] = $id;
					//pjBookingServiceModel::factory($data)->insert();
					pjBookingStatus::factory(array('booking_id' => $id, 'admin' => 1, 'owner_id'=>$owner_id))->insert();

					$err = 'ABK03';
				} else {
					$err = 'ABK04';
				}

				if (isset($_POST['pjadmin']) && $_POST['pjadmin'] == 1 ) {
					/*
					$url = '';
					if ( isset($_SESSION[PREFIX . 'url_date']) && !empty($_SESSION[PREFIX . 'url_date']) ) $url .= '&date=' . $_SESSION[PREFIX . 'url_date'];
					if ( isset($_SESSION[PREFIX . 'url_employee_id']) && !empty($_SESSION[PREFIX . 'url_employee_id']) ) {
						$url .= '&employee_id=' . $_SESSION[PREFIX . 'url_employee_id'];
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionEmployeeWeek" . $url);

					} else
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex" . $url);
					*/
				} else
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=$err");

			} else {
                $owner_id = $this->getOwnerId();
				pjObject::import('Model', 'pjCountry:pjCountry');
				$this->set('country_arr', pjCountryModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
					->where('t1.status', 'T')
					->where('t1.owner_id', $owner_id)
					->orderBy('`name` ASC')
					->findAll()->getData());

				$this
					->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/')
					->appendJs('pjAdminBookings.js');
			}
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionDeleteBooking()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && pjBookingModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjBookingServiceModel::factory()->where('booking_id', $_GET['id'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}

	public function pjActionDeleteBookingBulk()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjBookingModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjBookingServiceModel::factory()->whereIn('booking_id', $_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}

	public function pjActionExport()
	{
		$this->setLayout('pjActionEmpty');

		if (isset($_POST['export_form']))
		{
			if (isset($_POST['record']) && !empty($_POST['record']) && isset($_POST['format']))
			{
				$booking_arr = pjBookingModel::factory()
					->select('t1.*, t2.id AS `booking_service_id`, t2.service_id, t2.employee_id, t2.date, t2.start, t2.start_ts, t2.total, t2.price, t3.content AS `service`, t4.content AS `employee`')
					->join('pjBookingService', 't2.booking_id=t1.id', 'left outer')
					->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t2.service_id AND t3.field='name'", 'left outer')
					->join('pjMultiLang', "t4.model='pjEmployee' AND t4.foreign_id=t2.employee_id AND t4.field='name'", 'left outer')
					->whereIn('t1.id', $_POST['record'])
					->orderBy('t1.id ASC')
					->findAll()
					->getData();

				switch ($_POST['format'])
				{
					case 'csv':
						$map = array(
							'comma' => ",",
							'semicolon' => ";",
							'tab' => "\t"
						);
						$csv = new pjCSV();
						if (array_key_exists($_POST['delimiter'], $map))
						{
							$csv->setDelimiter($map[$_POST['delimiter']]);
						}
						$csv
							->setHeader(true)
							->setName("Bookings-".time().".csv")
							->process($booking_arr)
							->download();
						break;
					case 'xml':
						$xml = new pjXML();
						$xml
							->setRecord('booking')
							->setRoot('bookings')
							->setName("Bookings-".time().".xml")
							->process($booking_arr)
							->download();
						break;
					case 'ical':

						$timezone = pjUtil::getTimezone($this->option_arr['o_timezone']);

						$row = array();
						$row[] = "BEGIN:VCALENDAR";
						$row[] = "VERSION:2.0";
						$row[] = "PRODID:-//Appointment Scheduler//NONSGML Foobar//EN";
						$row[] = "METHOD:UPDATE"; // requied by Outlook
						foreach ($booking_arr as $booking)
						{
							$cell = array();
							$cell[] = "BEGIN:VEVENT";
							$cell[] = "UID:".md5($booking["booking_service_id"]); // required by Outlok
							$cell[] = "SEQUENCE:1";
							$cell[] = "DTSTAMP;TZID=$timezone:".date('Ymd',$booking["start_ts"])."T000000Z"; // required by Outlook
							$cell[] = "DTSTART;TZID=$timezone:".date('Ymd',$booking["start_ts"])."T".date('His',$booking["start_ts"])."Z";
							$cell[] = "DTEND;TZID=$timezone:".date('Ymd', $booking["start_ts"])."T".date('His', $booking["start_ts"] + $booking['total']*60)."Z";
							$cell[] = "SUMMARY:Appointment";
							$cell[] = "DESCRIPTION: Name: ".pjSanitize::html($booking["c_name"])."; Email: ".pjSanitize::html($booking["c_email"])."; Phone: ".pjSanitize::html($booking["c_phone"])."; Price: ".pjSanitize::html($booking["booking_total"])."; Notes: ".pjSanitize::html(preg_replace('/\n|\r|\r\n/', ' ', $booking["c_notes"]))."; Status: ".pjSanitize::html($booking["booking_status"]);
							$cell[] = "ATTENDEE;CN=\"".pjSanitize::html($booking["c_name"])."\";PARTSTAT=NEEDS-ACTION;ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:".pjSanitize::html($booking["c_email"]);
							$cell[] = "END:VEVENT";
							$row[] = join("\n", $cell);
						}
						$row[] = "END:VCALENDAR";
						$data = join("\n", $row);

						pjToolkit::download($data, "Bookings-".time().".ics", 'text/calendar');
						break;
				}
			}
			exit;
		}
	}

	public function pjActionGetBooking()
	{
		$this->setAjax(true);
		$owner_id = $this->getOwnerId();
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			$pjBookingModel = pjBookingModel::factory();
			$pjBookingServiceModel = pjBookingServiceModel::factory();

			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjBookingModel->escapeString($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), trim($q));
				$pjBookingModel->where(sprintf("(t1.uuid LIKE '%1\$s' OR t1.c_email LIKE '%1\$s' OR t1.c_name LIKE '%1\$s')", "%$q%"));
			}

			if (isset($_GET['booking_status']) && !empty($_GET['booking_status']) && in_array((int) $_GET['booking_status'], array('confirmed', 'pending', 'cancelled')))
			{
				$pjBookingModel->where('t1.booking_status', $_GET['booking_status']);
			}

			if (isset($_GET['employee_id']) && (int) $_GET['employee_id'] > 0)
			{
				$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `employee_id` = '%u')", $pjBookingServiceModel->getTable(), (int) $_GET['employee_id']));
			}

			if (isset($_GET['service_id']) && (int) $_GET['service_id'] > 0)
			{
				$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `service_id` = '%u')", $pjBookingServiceModel->getTable(), (int) $_GET['service_id']));
			}

			if (isset($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_from']) && !empty($_GET['date_to']))
			{
				$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
				$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
				$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `date` BETWEEN '%s' AND '%s')", $pjBookingServiceModel->getTable(), $date_from, $date_to));
			} else {
				if (isset($_GET['date_from']) && !empty($_GET['date_from']))
				{
					$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
					$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `date` >= '%s')", $pjBookingServiceModel->getTable(), $date_from));
				}
				if (isset($_GET['date_to']) && !empty($_GET['date_to']))
				{
					$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
					$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `date` <= '%s')", $pjBookingServiceModel->getTable(), $date_to));
				}
			}

			$column = 'id';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjBookingModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjBookingModel
				->select(sprintf("t1.*,
					(SELECT GROUP_CONCAT(CONCAT_WS('~.~', bs.service_id, DATE_FORMAT(FROM_UNIXTIME(bs.start_ts), '%%Y-%%m-%%d %%H:%%i:%%s'), m.content) SEPARATOR '~:~')
						FROM `%1\$s` AS `bs`
						LEFT JOIN `%2\$s` AS `m` ON m.model='pjService' AND m.foreign_id=bs.service_id AND m.field='name'
						WHERE bs.booking_id = t1.id) AS `items`
					", $pjBookingServiceModel->getTable(), pjMultiLangModel::factory()->getTable()))
				->orderBy("$column $direction")->limit($rowCount, $offset)
				->findAll()
				->toArray('items', '~:~')
				->getData();

			foreach ($data as $k => $v)
			{
				foreach ($data[$k]['items'] as $key => $val)
				{
					$tmp = explode('~.~', $val);
					if (isset($tmp[1]))
					{
						$tmp[1] = date($this->option_arr['o_datetime_format'], strtotime($tmp[1]));
						$data[$k]['items'][$key] = join("~.~", $tmp);
					}
				}
				$data[$k]['total_formated'] = pjUtil::formatCurrencySign(number_format($v['booking_total'], 2), $this->option_arr['o_currency']);
			}

			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}

	public function pjActionGetBookingService()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged() && $this->isEmployee())
		{
			$pjBookingServiceModel = pjBookingServiceModel::factory()
				->join('pjBooking', 't2.id=t1.booking_id', 'inner')
				->where('t1.employee_id', $this->getUserId());

			if (isset($_GET['q']) && !empty($_GET['q']))
			{
				$q = $pjBookingServiceModel->escapeString($_GET['q']);
				$q = str_replace(array('%', '_'), array('\%', '\_'), trim($q));
				$pjBookingServiceModel->where(sprintf("t2.uuid LIKE '%1\$s' OR t2.c_email LIKE '%1\$s' OR t2.c_name LIKE '%1\$s'", "%$q%"));
			}

			if (isset($_GET['booking_status']) && !empty($_GET['booking_status']) && in_array((int) $_GET['booking_status'], array('confirmed', 'pending', 'cancelled')))
			{
				$pjBookingServiceModel->where('t2.booking_status', $_GET['booking_status']);
			}

			if (isset($_GET['service_id']) && (int) $_GET['service_id'] > 0)
			{
				$pjBookingServiceModel->where('t1.service_id', $_GET['service_id']);
			}

			if (isset($_GET['date_from']) && isset($_GET['date_to']) && !empty($_GET['date_from']) && !empty($_GET['date_to']))
			{
				$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
				$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
				$pjBookingServiceModel->where(sprintf("(t1.date BETWEEN '%s' AND '%s')", $date_from, $date_to));
			} else {
				if (isset($_GET['date_from']) && !empty($_GET['date_from']))
				{
					$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['o_date_format']);
					$pjBookingServiceModel->where('t1.date >=', $date_from);
				}
				if (isset($_GET['date_to']) && !empty($_GET['date_to']))
				{
					$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['o_date_format']);
					$pjBookingServiceModel->where('t1.date <=', $date_to);
				}
			}

			$column = 't1.date';
			$direction = 'DESC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjBookingServiceModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjBookingServiceModel
				->select("t1.*, DATE_FORMAT(FROM_UNIXTIME(t1.start_ts), '%Y-%m-%d %H:%i:%s') AS `time`,
					t2.uuid, t2.booking_status, t2.c_name, t2.c_email, t2.c_phone, t3.content AS `service_name`")
				->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.service_id AND t3.field='name'", 'left outer')
				->orderBy("$column $direction")->limit($rowCount, $offset)
				->findAll()
				->getData();

			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}

	public function pjActionGetPrice()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			$price = $deposit = $tax = $total = 0;

			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$key = 't1.booking_id';
				$value = $_POST['id'];
			} elseif (isset($_POST['tmp_hash']) && !empty($_POST['tmp_hash'])) {
				$key = 't1.tmp_hash';
				$value = $_POST['tmp_hash'];
			}

			if (isset($key) && isset($value))
			{
				$bs_arr = pjBookingServiceModel::factory()->where($key, $value)->findAll()->getData();
				foreach ($bs_arr as $service)
				{
					$price += $service['price'];
				}
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

			$data = compact('price', 'deposit', 'tax', 'total');
			$data = array_map('floatval', $data);

			pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => '', 'data' => $data));
		}
		exit;
	}
	public function pjActionCheckOverlappingBooking()
    {
        $this->setAjax(true);
        if ($this->isXHR())
        {
            //check overlap booking
            // $booking = pjBookingServiceModel::factory()->where(sprintf("`date`= '%s' and `start_ts` > %d", $data['date']), $data['start_ts'])->orderBy('`start_ts` ASC')->limit(1)->getData();
            // $total_time = intval($date['start_ts']) + (intval($data['total']) * 3600);
            // if($total_time > intval($booking['start_ts'])){

            // }
            echo "Hello World";
            $this->set('status', 200);
        }
    }
	public function pjActionGetService()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && isset($_GET['date']) && !empty($_GET['date']))
			{
				$id = (int) $_GET['id'];
				$date = pjUtil::formatDate($_GET['date'], $this->option_arr['o_date_format']);

				$pjEmployeeServiceModel = pjEmployeeServiceModel::factory()
					->select("t1.*, t2.avatar, t2.calendar_id, t3.content AS `name`")
					->join('pjEmployee', 't2.id=t1.employee_id AND t2.is_active=1', 'inner')
					->join('pjMultiLang', "t3.model='pjEmployee' AND t3.foreign_id=t1.employee_id AND t3.field='name'", 'left outer')
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
						->where('t1.date', $date)
						->findAll()
						->getData();
				}

				foreach ($employee_arr as $k => $employee)
				{
					if ($this->isAdmin()) {
						$employee_arr[$k]['t_arr'] = pjAppController::getRawSlotsPerEmployeeAdmin($employee['employee_id'], $date, $employee['calendar_id']);

					} else
						$employee_arr[$k]['t_arr'] = pjAppController::getRawSlotsPerEmployee($employee['employee_id'], $date, $employee['calendar_id']);

					$employee_arr[$k]['ef_arr'] = pjEmployeeFreetimeModel::factory()
						->where('t1.employee_id', $employee['employee_id'])
						->where('t1.date', $date)
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

				if ( isset($_GET['servicetime_id']) && (int) $_GET['servicetime_id'] > 0 ) {
					$service_arr = pjServiceTimeModel::factory()
							->where('t1.id', $_GET['servicetime_id'])
							->find($_GET['servicetime_id'])
							->getData();

					$service_arr['id'] = $id;

				} else {
					$service_arr = pjServiceModel::factory()
							->select('t1.*, t2.content AS `name`, t3.content AS `description`')
							->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
							->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='description'", 'left outer')
							->find($id)
							->getData();
				}

				$this
					->set('service_arr', $service_arr)
					->set('employee_arr', $employee_arr)
					->set('st_arr', pjServiceTimeModel::factory()
										->where('t1.foreign_id', $id)
										->findAll()
										->getData()
									);

			} elseif ( isset($_GET['category_id']) && (int) $_GET['category_id'] > 0 ) {

				if ( isset($_GET['employee_id']) && (int) $_GET['employee_id'] > 0 ) {
					$service_arr = pjServiceModel::factory()
						->select('t1.*, t3.content AS `name`, t4.content AS `description`')
						->join('pjEmployeeService', "t2.service_id =t1.id AND t2.employee_id=". $_GET['employee_id'], 'inner')
						->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='name'", 'left outer')
						->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.id AND t4.field='description'", 'left outer')
						->where('t1.is_active', 1)
						->where('t1.category_id', (int) $_GET['category_id'])
						->findAll()->getData();

				} else {
					$service_arr = pjServiceModel::factory()
						->select('t1.*, t2.content AS `name`, t3.content AS `description`')
						->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
						->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='description'", 'left outer')
						->where('t1.is_active', 1)
						->where('t1.category_id', (int) $_GET['category_id'])
						->findAll()->getData();
				}

				if ( isset($service_arr) && count($service_arr) > 0 ) {

					$st_arr = pjServiceTimeModel::factory()
						->where('t1.foreign_id', $service_arr[0]['id'])
						->findAll()
						->getData();

				} else $st_arr = array();

				$this->set('service_arr', $service_arr)
					->set('st_arr', $st_arr);
			}
		}
	}

	public function pjActionIndex()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			$this->set('employee_arr', pjEmployeeModel::factory()
				->select('t1.*, t2.content AS `name`')
				->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
				->orderBy('`name` ASC')
				->findAll()
				->getData()
			);

			$this->set('service_arr', pjServiceModel::factory()
				->select('t1.*, t2.content AS `name`')
				->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
				->orderBy('`name` ASC')
				->findAll()
				->getData()
			);

			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminBookings.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionCustomer() {
		$this->checkLogin();

		if ($this->isAdmin())
		{
			$owner_id =  $this->getOwnerId();
			$booking_arr = pjBookingModel::factory()
				->select('t1.*, COUNT(`t1`.`c_email`) as count')
				->where('t1.booking_status', 'confirmed')
				->orderBy('t1.c_name ASC')
				->groupBy('t1.c_email')
				->findAll()
				->getData();

			$service_arr = pjBookingServiceModel::factory()
				->select('t2.c_email, t4.content AS `service_name`')
				->join('pjBooking', 't2.id=t1.booking_id', 'inner')
				->join('pjService', 't3.id=t1.service_id', 'inner')
				->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.locale=t2.locale_id AND t4.field='name'", 'left outer')
				->where('t2.booking_status', 'confirmed')
				->findAll()
				->getData();

			$this->set('booking_arr', $booking_arr)
					->set('service_arr', $service_arr);

			$this
				->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/')
				->appendJs('pjAdminBookings.js');

		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionSearchCustomer() {
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged()) {
			$owner_id = $this->getOwnerId();
			$search = isset($_GET['search']) ? $_GET['search'] : Null;
			$pjBookingModel = pjBookingModel::factory()
				->select('t1.c_name, t1.c_email, t1.c_phone')
                ->where('t1.owner_id', $owner_id)
				->where('t1.booking_status', 'confirmed')
				->where(sprintf("t1.c_name LIKE '%1\$s' OR t1.c_email LIKE '%1\$s'", "%$search%"))
				->orderBy('t1.c_name ASC')
				->groupBy('t1.c_email');

			$page = isset($_GET['page']) ? $_GET['page'] : 1;
			$booking_arr = $pjBookingModel
				->limit(10, ($page-1)*10)
				->findAll()
				->getData();

			$this->set('booking_arr', $booking_arr)
				->set('count_arr', $pjBookingModel
										->findCount()
										->getData()
				);
		}
	}

	public function download_csv() {

		$booking_arr = pjBookingModel::factory()
			->select('t1.*, COUNT(`t1`.`c_email`) as count')
			->where('t1.booking_status', 'confirmed')
			->orderBy('t1.c_name ASC')
			->groupBy('t1.c_email')
			->findAll()
			->getData();

		$service_arr = pjBookingServiceModel::factory()
			->select('t2.c_email, t4.content AS `service_name`')
			->join('pjBooking', 't2.id=t1.booking_id', 'inner')
			->join('pjService', 't3.id=t1.service_id', 'inner')
			->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.locale=t2.locale_id AND t4.field='name'", 'left outer')
			->where('t2.booking_status', 'confirmed')
			->findAll()
			->getData();

		$this->download_send_headers("customer_" . date("Y-m-d") . ".csv");
		echo $this->array_csv($booking_arr, $service_arr);
		exit();
	}

	function array_csv($booking_arr, $service_arr) {
		if (count ( $booking_arr ) == 0) {
			return null;
		}
		ob_start ();
		$df = fopen ( "php://output", 'w' );

		// Download the file
		if (count($booking_arr > 0)){


			$title = array ();

			$title[] = __('booking_name', true, true);
			$title[] = __('booking_phone', true, true);
			$title[] = __('booking_email', true, true);
			$title[] = __('booking_services', true, true);
			$title[] = 'Count';

			fputcsv ( $df, $title );

			foreach ($booking_arr as $booking) {
				$value = array ();

				$value[] = $booking['c_name'];
				$value[] = $booking['c_phone'];
				$value[] = $booking['c_email'];

					if (count($service_arr) > 0) {
						$service_name = array();
						foreach ($service_arr as $service) {
							if ($service['c_email'] == $booking['c_email']) {
								$service_name[] = $service['service_name'];
							}
						}
						$value[] = join(', ', $service_name);
					}

					$value[] = $booking['count'];

				fputcsv ( $df, $value, ',', '"' );
			}

		}

		fclose ( $df );
		return ob_get_clean ();
	}
	function download_send_headers($filename) {
		// disable caching
		$now = gmdate ( "D, d M Y H:i:s" );
		header ( "Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate" );
		header ( "Last-Modified: {$now} GMT" );

		// force download
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );

		// disposition / encoding on response body
		header ( "Content-Disposition: attachment;filename={$filename}" );
		header ( "Content-Transfer-Encoding: binary" );
	}

	public function pjActionList()
	{
		$this->checkLogin();

		if ($this->isEmployee())
		{
			$this->set('service_arr', pjServiceModel::factory()
				->select('t1.*, t2.content AS `name`')
				->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
				->orderBy('`name` ASC')
				->where(sprintf("t1.id IN (SELECT `service_id` FROM `%s` WHERE `employee_id` = '%u')", pjEmployeeServiceModel::factory()->getTable(), $this->getUserId()))
				->findAll()
				->getData()
			);

			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjEmployeeBookings.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionSaveBooking()
	{
		$this->setAjax(true);
		$owner_id = $this->getOwnerId();
		if ($this->isXHR() && $this->isLoged())
		{
			$pjBookingModel = pjBookingModel::factory();
			if (!in_array($_POST['column'], $pjBookingModel->getI18n()))
			{
				$pjBookingModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $owner_id, $_GET['id'], 'pjBooking');
			}
		}
		exit;
	}

	public function pjActionUpdate()
	{
		$this->checkLogin();
		if ($this->isAdmin())
		{
			$pjBookingModel = pjBookingModel::factory();
			if (isset($_REQUEST['id']) && (int) $_REQUEST['id'] > 0)
			{
				$pjBookingModel->where('t1.id', intval($_REQUEST['id']));
			} elseif (isset($_GET['uuid']) && !empty($_GET['uuid'])) {
				$pjBookingModel->where('t1.uuid', $_GET['uuid']);
			}
			$arr = $pjBookingModel
				->limit(1)
				->findAll()
				->getData();

			if (empty($arr))
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABK08");
			}
			$arr = $arr[0];

			if (isset($_POST['booking_update']))
			{
				$data = array();
				if ($_POST['payment_method'] != "creditcard")
				{
					$data['cc_type'] = ':NULL';
					$data['cc_num'] = ':NULL';
					$data['cc_code'] = ':NULL';
					$data['cc_exp_year'] = ':NULL';
					$data['cc_exp_month'] = ':NULL';
				}
				//$data['booking_id'] = intval($_POST['id']);
				pjBookingModel::factory()->set('id', $_POST['id'])->modify(array_merge($_POST, $data));
				//pjBookingServiceModel::factory()->find(intval($_POST['booking_serivce_id']))->modify($data);
				if (isset($_POST['pjadmin']) && $_POST['pjadmin'] == 1 ) {
					//pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex");

				} else
					pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminBookings&action=pjActionIndex&err=ABK01");

			} else {
				pjObject::import('Model', 'pjCountry:pjCountry');
				$pjBookingServiceModel = pjBookingServiceModel::factory();
				$pjBookingService = $pjBookingServiceModel->where('t1.booking_id', intval($_REQUEST['id']))->limit(1)->findAll()->getData();
				$arr['booking_serivce_id'] = $pjBookingService[0]['id'];

				$this->set('arr', $arr)
					->set('country_arr', pjCountryModel::factory()
						->select('t1.*, t2.content AS `name`')
						->join('pjMultiLang', "t2.model='pjCountry' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
						->orderBy('`name` ASC')
						->findAll()->getData());

				$this->set('bi_arr', pjBookingServiceModel::factory()
					->select('t1.*, t2.content AS `title`')
					->join('pjMultiLang', sprintf("t2.model='pjService' AND t2.foreign_id=t1.service_id AND t2.field='name' AND t2.locale='%u'", $arr['locale_id']), 'left outer')
					->where('t1.booking_id', $arr['id'])
					->findAll()
					->getData()
				);

				if (isset($_GET['customer'])) {

					$service_arr = pjBookingServiceModel::factory()
						->select('t1.*, t4.content AS `service`, t5.content AS `employee`')
						->join('pjBooking', 't2.id=t1.booking_id', 'inner')
						->join('pjService', 't3.id=t1.service_id', 'inner')
						->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.locale=t2.locale_id AND t4.field='name'", 'left outer')
						->join('pjMultiLang', "t5.model='pjEmployee' AND t5.foreign_id=t1.employee_id AND t5.locale=t2.locale_id AND t5.field='name'", 'left outer')
						->where('t2.booking_status', 'confirmed')
						->where('t2.c_email', $arr['c_email'])
						->findAll()
						->getData();

					$this->set('service_arr', $service_arr);
				}

				$this
					->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/')
					->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/')
					->appendJs('pjAdminBookings.js')
					->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true)
				;
			}
		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionViewBookingService()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$arr = pjBookingServiceModel::factory()
					->select('t2.*, t1.*, t3.content AS `service_name`, t4.content AS `country_name`')
					->join('pjBooking', 't2.id=t1.booking_id', 'inner')
					->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.service_id AND t3.field='name'", 'left outer')
					->join('pjMultiLang', "t4.model='pjCountry' AND t4.foreign_id=t2.c_country_id AND t4.field='name'", 'left outer')
					->find($_GET['id'])
					->getData();

				$this->set('arr', $arr);
			}
		}
	}

	public function pjActionItemAdd()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			$pjBookingServiceModel = pjBookingServiceModel::factory();
            $owner_id = $this->getOwnerId();
			if (isset($_POST['item_add']))
			{
				if (isset($_POST['service_id']) && (int) $_POST['service_id'] > 0)
				{
					$date = pjUtil::formatDate($_POST['date'], $this->option_arr['o_date_format']);

					$service_arr = pjServiceModel::factory()->find($_POST['service_id'])->getData();

					if (isset($_POST['servicetime_id']) && (int) $_POST['servicetime_id'] > 0) {
						$servicetime_arr = pjServiceTimeModel::factory()
							->where('t1.id', $_POST['servicetime_id'])
							->find($_POST['servicetime_id'])
							->getData();

						$service_arr['total'] = $servicetime_arr['total'];
						$service_arr['price'] = $servicetime_arr['price'];
					}

					if ( isset($_POST['employee_id']) ) {
						$employee_plustime = pjEmployeeModel::factory()
							->select('t1.*, t2.content AS `name`, t3.plustime')
							->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
							->join('pjEmployeeService', 't3.employee_id = t1.id', 'inner')
							->where('t3.service_id', $_POST['service_id'])
							->where('t3.employee_id', $_POST['employee_id'])
							->where('t1.is_active', 1)
							->orderBy('`name` ASC')
							->findAll()
							->getData();

						foreach ( $employee_plustime as $employee ) {
							if ( isset($employee['plustime']) && (int) $employee['plustime'] != 0 ) {
								$service_arr['total'] += (int) $employee['plustime'];
							}
						}
                        $service_arr['total'] += (int) $_POST['service_edittime'];
					}

					$resources_ids = pjResourcesServiceModel::factory()
						->where('t1.service_id', $_POST['service_id'])
						->findAll()
						->getDataPair(null, 'resources_id');

					if ( isset($resources_ids) && !empty($resources_ids)) {
						$resources_bs_ids = pjBookingServiceModel::factory()
							->join('pjBooking', "t1.booking_id=t2.id AND t2.booking_status='confirmed'", 'inner')
							->whereIn('t1.resources_id', $resources_ids)
							->where('t1.date', $date)
							->where('t1.start_ts >= ', $_POST['start_ts'])
							->where('t1.start_ts < ', $_POST['start_ts'] + $service_arr['total']* 60)
							->findAll()
							->getDataPair(null, 'resources_id');

						if ( isset($resources_bs_ids) && !empty($resources_bs_ids)) {
							$resources_booking_ids = array_values(array_diff($resources_ids, $resources_bs_ids));
						} else $resources_booking_ids = $resources_ids;

					} else $resources_booking_ids = array('0' => null);

					$bs_id = $pjBookingServiceModel->reset()->setAttributes(array(
						'tmp_hash' => @$_POST['tmp_hash'],
                        'owner_id' => $owner_id,
						'booking_id' => @$_POST['booking_id'],
						'service_id' => $_POST['service_id'],
						'employee_id' => $_POST['employee_id'],
						'resources_id' => $resources_booking_ids[0],
						'date' => $date,
						'start' => date("H:i:s", $_POST['start_ts']),
						'start_ts' => $_POST['start_ts'],
						'total' => @$service_arr['total'],
						'price' => @$service_arr['price']
					))->insert()->getInsertId();

					if ($bs_id !== FALSE && (int) $bs_id > 0)
					{
						pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Service has been added.'));
					}
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Service has not been added.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Service couldn\'t be empty.'));
			}

			if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 ) {

				$categories = pjServiceCategoryModel::factory()
					//->where('t1.show_front', 'on')
					->join('pjService', "t2.category_id = t1.id AND t2.is_active = 1", 'inner')
					->join('pjEmployeeService', "t3.service_id =t2.id AND t3.employee_id=". $_GET['employee_id'], 'inner')
					->orderBy('t1.name ASC')
					->groupBy('t1.id')
					->findAll()
					->getData();

				if ( isset($categories) && count($categories) > 0 ) {
					$service_arr = pjServiceModel::factory()
						->select('t1.*, t3.content AS `name`, t4.content AS `description`')
						->join('pjEmployeeService', "t2.service_id =t1.id AND t2.employee_id=". $_GET['employee_id'], 'inner')
						->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='name'", 'left outer')
						->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.id AND t4.field='description'", 'left outer')
						->where('t1.is_active', 1)
						->where('t1.category_id', $categories[0]['id'])
						->findAll()->getData();

				} else {
					$service_arr = pjServiceModel::factory()
						->select('t1.*, t3.content AS `name`, t4.content AS `description`')
						->join('pjEmployeeService', "t2.service_id =t1.id AND t2.employee_id=". $_GET['employee_id'], 'inner')
						->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='name'", 'left outer')
						->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.id AND t4.field='description'", 'left outer')
						->where('t1.is_active', 1)
						->findAll()->getData();
				}

				$this->set('st_arr', pjServiceTimeModel::factory()
						->where('t1.foreign_id', $service_arr[0]['id'])
						->findAll()
						->getData()
					);

				$employee_arr = pjEmployeeModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', sprintf("t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'"), 'left outer')
					->where('t1.id', $_GET['employee_id'])
					->findAll()
					->getData();

				$this->set('employee_arr', $employee_arr);

			} else {

				$categories = pjServiceCategoryModel::factory()
					//->where('t1.show_front', 'on')
					->orderBy('t1.name ASC')
					->findAll()
					->getData();

				if ( isset($categories) && count($categories) > 0 ) {
					$service_arr = pjServiceModel::factory()
						->select('t1.*, t2.content AS `name`, t3.content AS `description`')
						->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
						->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='description'", 'left outer')
						->where('t1.is_active', 1)
						// ->where('t1.category_id', $categories[0]['id'])
						->findAll()->getData();

				} else {
					$service_arr = pjServiceModel::factory()
						->select('t1.*, t2.content AS `name`, t3.content AS `description`')
						->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
						->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='description'", 'left outer')
						->where('t1.is_active', 1)
						->findAll()->getData();
				}
			}

			$this->set('categories_arr', $categories);
			$this->set('service_arr', $service_arr);
		}
	}

	public function pjActionItemDelete()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$pjBookingServiceModel = pjBookingServiceModel::factory();
				$arr = $pjBookingServiceModel->find($_POST['id'])->getData();
				if (empty($arr))
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Item not found.'));
				}
				if (1 == $pjBookingServiceModel->set('id', $_POST['id'])->erase()->getAffectedRows())
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Item has been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Item has not been deleted.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing parameters.'));
		}
		pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Access denied.'));
		exit;
	}

	public function pjActionItemGet()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			$pjBookingServiceModel = pjBookingServiceModel::factory()
				->select("t1.*, t2.content AS `service`, t3.content AS `employee`")
				->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.service_id AND t2.field='name'")
				->join('pjMultiLang', "t3.model='pjEmployee' AND t3.foreign_id=t1.employee_id AND t3.field='name'");

			if (isset($_GET['booking_id']) && (int) $_GET['booking_id'] > 0)
			{
				$pjBookingServiceModel
					->join('pjBooking', 't4.id=t1.booking_id', 'left')
					->where('t1.booking_id', $_GET['booking_id']);
			} elseif (isset($_GET['tmp_hash']) && !empty($_GET['tmp_hash'])) {
				$pjBookingServiceModel->where('t1.tmp_hash', $_GET['tmp_hash']);
			} else {
				$pjBookingServiceModel->where('t1.id', -999);
			}
			$bi_arr = $pjBookingServiceModel->findAll()->getData();

			$this->set('bi_arr', $bi_arr);
		}
	}

	public function pjActionItemEmail()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['send_email']) && isset($_POST['to']) && !empty($_POST['to']) && !empty($_POST['from']) &&
				!empty($_POST['subject']) && !empty($_POST['message']) && !empty($_POST['id']))
			{
				$Email = new pjEmail();
				$Email->setContentType('text/html');
				if ($this->option_arr['o_send_email'] == 'smtp')
				{
					$Email
						->setTransport('smtp')
						->setSmtpHost($this->option_arr['o_smtp_host'])
						->setSmtpPort($this->option_arr['o_smtp_port'])
						->setSmtpUser($this->option_arr['o_smtp_user'])
						->setSmtpPass($this->option_arr['o_smtp_pass']);
				}

				$r = false;
				if (isset($_POST['message']) && !empty($_POST['message']))
				{
					$message = pjUtil::textToHtml($_POST['message']);
					foreach ($_POST['to'] as $recipient)
					{
						$r = $Email
							->setTo($recipient)
							->setFrom($_POST['from'])
							->setSubject($_POST['subject'])
							->send($message);
					}
				}

				if ($r)
				{
					pjBookingServiceModel::factory()->set('id', $_POST['id'])->modify(array('reminder_email' => 1));
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Email has been sent.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Email failed to send.'));
			}

			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$booking_arr = pjBookingServiceModel::factory()
					->select('t2.*, t1.*, t3.length, t3.before, t4.content AS `service_name`,
						t6.email AS `admin_email`, t7.content AS `country_name`, t8.email AS `employee_email`')
					->join('pjBooking', 't2.id=t1.booking_id', 'inner')
					->join('pjService', 't3.id=t1.service_id', 'inner')
					->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.field='name' AND t4.locale=t2.locale_id", 'left outer')
					->join('pjCalendar', 't5.id=t2.calendar_id', 'left outer')
					->join('pjUser', 't6.id=t5.user_id', 'left outer')
					->join('pjMultiLang', "t7.model='pjCountry' AND t7.foreign_id=t2.c_country_id AND t7.locale=t2.locale_id AND t7.field='name'", 'left outer')
					->join('pjEmployee', 't8.id=t1.employee_id', 'left outer')
					->find($_GET['id'])
					->getData();

				$tokens = pjAppController::getTokens($booking_arr, $this->option_arr);

				$subject_client = str_replace($tokens['search'], $tokens['replace'], $this->option_arr['o_reminder_subject']);
				$message_client = str_replace($tokens['search'], $tokens['replace'], $this->option_arr['o_reminder_body']);

				$this->set('arr', array(
					'id' => $_GET['id'],
					//'to' => $booking_arr['c_email'],
					'client_email' => $booking_arr['c_email'],
					'employee_email' => $booking_arr['employee_email'],
					'from' => !empty($booking_arr['admin_email']) ? $booking_arr['admin_email'] : $booking_arr['c_email'],
					'message' => $message_client,
					'subject' => $subject_client
				));
			} else {
				exit;
			}
		}
	}

	public function pjActionItemSms()
	{
		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['send_sms']) && isset($_POST['to']) && !empty($_POST['to']) && !empty($_POST['message']) && !empty($_POST['id']))
			{
				$params = array(
					'text' => $_POST['message'],
					'key' => md5($this->option_arr['private_key'] . PJ_SALT)
				);

				foreach ($_POST['to'] as $recipient)
				{
					$params['number'] = $recipient;
					$result = $this->requestAction(array('controller' => 'pjSms', 'action' => 'pjActionSend', 'params' => $params), array('return'));
				}

				if ((int) $result === 1)
				{
					pjBookingServiceModel::factory()->set('id', $_POST['id'])->modify(array('reminder_sms' => 1));
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'SMS has been sent.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'SMS failed to send.'));
			}

			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$booking_arr = pjBookingServiceModel::factory()
					->select('t2.*, t1.*, t3.before, t3.length, t4.content AS `service_name`,
						t6.email AS `admin_email`, t7.content AS `country_name`, t8.phone AS `employee_phone`')
					->join('pjBooking', 't2.id=t1.booking_id', 'inner')
					->join('pjService', 't3.id=t1.service_id', 'inner')
					->join('pjMultiLang', "t4.model='pjService' AND t4.foreign_id=t1.service_id AND t4.field='name' AND t4.locale=t2.locale_id", 'left outer')
					->join('pjCalendar', 't5.id=t2.calendar_id', 'left outer')
					->join('pjUser', 't6.id=t5.user_id', 'left outer')
					->join('pjMultiLang', "t7.model='pjCountry' AND t7.foreign_id=t2.c_country_id AND t7.locale=t2.locale_id AND t7.field='name'", 'left outer')
					->join('pjEmployee', 't8.id=t1.employee_id', 'left outer')
					->find($_GET['id'])
					->getData();

				$tokens = pjAppController::getTokens($booking_arr, $this->option_arr);

				$message_client = str_replace($tokens['search'], $tokens['replace'], $this->option_arr['o_reminder_sms_message']);

				$this->set('arr', array(
					'id' => $_GET['id'],
					//'to' => $booking_arr['c_phone'],
					'client_phone' => $booking_arr['c_phone'],
					'employee_phone' => $booking_arr['employee_phone'],
					'message' => $message_client
				));
			} else {
				exit;
			}
		}
	}

	function pjActionStatistics() {
		$this->checkLogin();

		if ($this->isAdmin())
		{

			$this
				->appendJs('pjAdminBookings.js');

		} else {
			$this->set('status', 2);
		}
	}

	public function pjActionGetCalendar() {

		$this->setAjax(true);

		if ($this->isXHR() && $this->isLoged()) {
			if( isset($_GET['year']) ){
				$year = $_GET['year'];

			} else {
				$year = date("Y",time());

			}

			if( isset($_GET['month']) ){
				$month = $_GET['month'];

			} else {
				$month = date("m",time());

			}

			$pjMonthlyCalendar = new pjMonthlyCalendar();

			$pjMonthlyCalendar->currency = $this->option_arr['o_currency'];

			$strtotime_fm = strtotime( $year . '-' . $month .'-1 00:00:00');
			$strtotime_lm = strtotime( $year . '-' . $month .'-31 23:59:59');

			$pjMonthlyCalendar->booking_arr = pjBookingServiceModel::factory()
				->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                // banana code
				// ->where('t2.calendar_id', $this->getForeignId())
				->where('t2.booking_status', 'confirmed')
				->where('t1.start_ts >=', $strtotime_fm)
				->where('t1.start_ts <', $strtotime_lm)
				->findAll()
				->getData();

			$pjMonthlyCalendar->employees = pjEmployeeModel::factory()
			->select("t1.*, t2.content AS `name`")
					->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left')
					// banana code
                    //->where('t1.calendar_id', $this->getForeignId())
					->orderBy('name ASC')
					->findAll()
					->getData();

			if ( count($pjMonthlyCalendar->employees) > 0 ) {

				if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 ) {
					$employee_id = $_GET['employee_id'];

				} else $employee_id = $pjMonthlyCalendar->employees[0]['id'];

				for ( $i = 1; $i < 32; $i++ ) {
					$pjMonthlyCalendar->t_arr[$i] = pjAppController::getRawSlotsPerEmployee($employee_id, $year . '-' . $month . '-' . $i, $this->getForeignId());
				}

				$this->tpl['calendar'] = $pjMonthlyCalendar->show($year, $month);

			} else $this->tpl['calendar'] = '';

		}
	}

	public function pjActionGetMonthly() {

		$this->setAjax(true);
		if ($this->isXHR() && $this->isLoged()) {

			if ( isset($_GET['m']) && !empty($_GET['m']) ){
				$m = $_GET['m'];

			} else $m = date('m');

			$_mf = $m - 1;
			$_mt = $m + 1;

			if ($_mf <= 0) {
				$_mf = 12 + $_mf;
				$_mfrom = (date('Y') -1) . '-' . $_mf . '-1 00:00:00' ;

			} else $_mfrom = date('Y') . '-' . $_mf . '-1 00:00:00' ;

			if ( $_mt > 12 ) {
				$_mt = $_mt - 12;
				$_mto = (date('Y') + 1) . '-' . $_mt . '-1 00:00:00' ;

			} else $_mto = date('Y') . '-' . $_mt . '-1 00:00:00' ;

			$monthly_arr = pjBookingServiceModel::factory()
				->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                // banana code
				//->where('t2.calendar_id', $this->getForeignId())
				->where('t2.booking_status', 'confirmed')
				->where('t1.start_ts >=', strtotime($_mfrom))
				->where('t1.start_ts <', strtotime($_mto))
				->findAll()
				->getData();

			$employees_arr = pjEmployeeModel::factory()
				->select('t1.*, t2.content AS `name`')
                // banana code
				->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
				->where('t1.is_active', 1)
				->orderBy('`name` ASC')
				->findAll()
				->getData();

			if ( count($employees_arr) == 0 ) exit();

			if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 ) {
				$employee_arr = pjEmployeeModel::factory()
					->where('t1.is_active', 1)
					->where('t1.id', $_GET['employee_id'])
					->find($_GET['employee_id'])
					->getData();

			} else {
				$employee_arr = $employees_arr[0];
			}

			for ( $_m = ($m - 1); $_m < $m + 1; $_m++ ) {
				if ( $_m <= 0 ) {
					$__m = 12 + $_m;
					$y = date('Y') -1;

				} elseif ( $_m > 12 ) {
					$__m = $_m - 12;
					$y = date('Y') + 1;

				} else {
					$__m = $_m;
					$y = date('Y');
				}

				$opening_hours = 0;
				for ( $i = 1; $i < 32; $i++ ) {
					 $t_arr = pjAppController::getRawSlotsPerEmployee($employee_arr['id'], $y . '-' . $__m . '-' . $i, $this->getForeignId());
					 if ($t_arr) {
					 	$opening_hours += ($t_arr['end_ts'] - $t_arr['start_ts']);
					 }
				}

				$employee_arr[$_m]['opening_hours'] = $opening_hours;

			}

			$this->set('monthly_arr', $monthly_arr)
				->set('employees_arr', $employees_arr)
				->set('employee_arr', $employee_arr);
		}
	}
}
?>

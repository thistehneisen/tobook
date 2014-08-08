<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once PJ_CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminTime extends pjAdmin
{
	private $types = array('calendar', 'employee');
	
	public function pjActionIndex()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			if (isset($_POST['working_time']))
			{
                $owner_id = $this->getOwnerId();
				$data = array();
				$weekDays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
				foreach ($weekDays as $day)
				{
					if (!isset($_POST[$day . '_dayoff']))
					{
						$data[$day . '_admin_from'] = $_POST[$day . '_admin_hour_from'] . ":" . $_POST[$day . '_admin_minute_from'];
						$data[$day . '_admin_to'] = $_POST[$day . '_admin_hour_to'] . ":" . $_POST[$day . '_admin_minute_to'];
						
						$data[$day . '_from'] = $_POST[$day . '_hour_from'] . ":" . $_POST[$day . '_minute_from'];
						$data[$day . '_to'] = $_POST[$day . '_hour_to'] . ":" . $_POST[$day . '_minute_to'];
						$data[$day . '_lunch_from'] = $_POST[$day . '_lunch_hour_from'] . ":" . $_POST[$day . '_lunch_minute_from'];
						$data[$day . '_lunch_to'] = $_POST[$day . '_lunch_hour_to'] . ":" . $_POST[$day . '_lunch_minute_to'];
						$data[$day . '_dayoff'] = "F";
					} else {
						$data[$day . 'admin_from'] = ":NULL";
						$data[$day . 'admin_to'] = ":NULL";
						$data[$day . '_from'] = ":NULL";
						$data[$day . '_to'] = ":NULL";
						$data[$day . '_lunch_from'] = ":NULL";
						$data[$day . '_lunch_to'] = ":NULL";
						$data[$day . '_dayoff'] = "T";
					}
				}

                 pjWorkingTimeModel::factory()->set('id', $_POST['id'])->modify($data);

				if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0 && isset($_POST['type']) && in_array($_POST['type'], $this->types))
				{
					pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionIndex&type=%s&foreign_id=%u&err=AT01",
						PJ_INSTALL_URL, $_POST['type'], $_POST['foreign_id']));
				}
					
				pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionIndex&err=AT01", PJ_INSTALL_URL));
			}
			

			$foreign_id = $this->getForeignId();
			$type = 'calendar';
			if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
			{
				$foreign_id = (int) $_GET['foreign_id'];
			}
			if (isset($_GET['type']) && in_array($_GET['type'], $this->types))
			{
				$type = $_GET['type'];
			}
			
			$wt_arr = pjWorkingTimeModel::factory()
				->where('t1.foreign_id', $foreign_id)
				->where('t1.type', $type)
				->limit(1)
				->findAll()
				->getData();

			$this->set('wt_arr', !empty($wt_arr) ? $wt_arr[0] : array());
			$this->appendJs('pjAdminTime.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public $year = 0;
	public function pjActionCustom()
	{
		$this->checkLogin();

		if ($this->isAdmin() || $this->isEmployee())
		{
			if ( isset($_POST['custom_time_f'])) {
				$input_file = null;
				
				if ( count($_FILES) ){
					// TODP: process file
					$input_file = $_FILES['fcsv']['tmp_name'];
					$is_first_line = true;
					$this->year = date('Y');
					$q = array();
			
					if ( file_exists($input_file) ){
						$fh = fopen($input_file, 'r') or die("File '$input_file' is not readable!");
						while (!feof($fh)){
							$line = fgets($fh);
							$line = trim($line);
							if ( empty($line) ) continue;
			
							if ( $is_first_line ){
								$mm = preg_match('#\d\d/\d\d/(\d\d\d\d)#', $line, $m );
								if ( $mm && count($m) >=2 ){
									$this->set_year( $m[1] );
								}
								$is_first_line = false;
								continue;
							}
			
							if ( preg_match('#^(\d\d)\.(\d\d)\.#', $line, $m) ){
								$data = array(
										'd' => $m[1],
										'm' => $m[2],
										'y' => $this->year,
										'day_off' => false
								);
								if ( preg_match('#V-Vapaa#i', $line, $m2) ){
									$data['day_off'] = true;
			
									$this->process_data( $data );
			
									continue;
								}
								// if is not day_off
								if ( !isset($q) || count($q) ) $q = array();
								array_push( $q , $data );
								continue;
							}
			
							if ( preg_match('#^(\d\d):(\d\d)-(\d\d):(\d\d)#', $line, $m) ){
								// require day of month data saved.
								if ( ($data = array_pop($q)) && is_array($data) && count($data) ){
									$data['start_time'] = array(
											'hour' => $m[1],
											'minute' => $m[2]
									);
									$data['end_time'] = array(
											'hour' => $m[3],
											'minute' => $m[4]
									);
									$this->process_data( $data );
			
									// clear queue
									$q = array();
								}
							}
						}
						fclose($fh) ;
					} else {
						die("File '$input_file' does not exists!");
					}
				}
				
				if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0 && isset($_POST['type']) && in_array($_POST['type'], $this->types))
				{
					pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionCustom&type=%s&foreign_id=%u&err=AT02",
					PJ_INSTALL_URL, $_POST['type'], $_POST['foreign_id']));
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=pjActionCustom&err=AT02");
				
			} elseif (isset($_POST['custom_time'])) {
				if ($this->isAdmin())
				{
					$foreign_id = $this->getForeignId();
					$type = 'calendar';
					if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0)
					{
						$foreign_id = (int) $_POST['foreign_id'];
					}
					if (isset($_POST['type']) && in_array($_POST['type'], $this->types))
					{
						$type = $_POST['type'];
					}
				} elseif ($this->isEmployee()) {
					$foreign_id = $this->getUserId();
					$type = 'employee';
				}
				
				$pjDateModel = pjDateModel::factory();
				$date = pjUtil::formatDate($_POST['date'], $this->option_arr['o_date_format']);
				$pjDateModel
					->where('foreign_id', $foreign_id)
					->where('`type`', $type)
					->where('`date`', $date)
					->limit(1)
					->eraseAll();
				
				$data = array();
				$data['foreign_id'] = $foreign_id;
				$data['type'] = $type;
				$data['start_time'] = join(":", array($_POST['start_hour'], $_POST['start_minute']));
				$data['end_time'] = join(":", array($_POST['end_hour'], $_POST['end_minute']));
				$data['start_lunch'] = join(":", array($_POST['start_lunch_hour'], $_POST['start_lunch_minute']));
				$data['end_lunch'] = join(":", array($_POST['end_lunch_hour'], $_POST['end_lunch_minute']));
				$data['date'] = $date;
				
				$pjDateModel->reset()->setAttributes(array_merge($_POST, $data))->insert();
				
				if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0 && isset($_POST['type']) && in_array($_POST['type'], $this->types))
				{
					pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionCustom&type=%s&foreign_id=%u&err=AT02",
						PJ_INSTALL_URL, $_POST['type'], $_POST['foreign_id']));
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=pjActionCustom&err=AT02");
			}

			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminTime.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			
		} else {
			$this->set('status', 2);
		}
	}
	

	// utility & process function
	
	public function set_year( $yyyy ){
	
		// set global $year
		$this->year = $yyyy;
	}
	
	public function process_data( $data ){
		
		if ( !isset($data['y']) || !isset($data['m']) || !isset($data['d']) ) {

			return ;
		}
		
		if ($this->isAdmin())
		{
			$foreign_id = $this->getForeignId();
			$type = 'calendar';
			if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0)
			{
				$foreign_id = (int) $_POST['foreign_id'];
			}
			if (isset($_POST['type']) && in_array($_POST['type'], $this->types))
			{
				$type = $_POST['type'];
			}
		} elseif ($this->isEmployee()) {
			$foreign_id = $this->getUserId();
			$type = 'employee';
		}
		
		$pjDateModel = pjDateModel::factory();
		$date = $data['y'] . '-' . $data['m'] . '-' . $data['d'];
		$pjDateModel
		->where('foreign_id', $foreign_id)
		->where('`type`', $type)
		->where('`date`', $date)
		->limit(1)
		->eraseAll();
		
		$data_new = array();
		$data_new['foreign_id'] = $foreign_id;
		$data_new['type'] = $type;
		
		if (isset($data["day_off"]) && $data["day_off"] == true ) {
			$data_new['start_time'] = "00:00";
			$data_new['end_time'] = "00:00";
			$data_new['start_lunch'] = "00:00";
			$data_new['end_lunch'] = "00:00";
			$data_new['is_dayoff'] = "T";
			
		} else {
			$start_hour = isset($data['start_time']['hour']) ? $data['start_time']['hour'] : '00';
			$start_minute = isset($data['start_time']['minute']) ? $data['start_time']['minute'] : '00';
			
			$end_hour = isset($data['end_time']['hour']) ? $data['end_time']['hour'] : '00';
			$end_minute = isset($data['end_time']['minute']) ? $data['end_time']['minute'] : '00';
			
			$data_new['start_time'] = join(":", array($start_hour, $start_minute));
			$data_new['end_time'] = join(":", array($end_hour, $end_minute));
			$data_new['start_lunch'] = join(":", array($_POST['start_lunch_hour'], $_POST['start_lunch_minute']));
			$data_new['end_lunch'] = join(":", array($_POST['end_lunch_hour'], $_POST['end_lunch_minute']));
			
			$data_new['is_dayoff'] = "F";
		}
				
		$data_new['date'] = $date;
		
		$pjDateModel->reset()->setAttributes($data_new)->insert();
		
	}
	
	public function pjActionDeleteDate()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && pjDateModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteDateBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjDateModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionGetDate()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDateModel = pjDateModel::factory();
				
			if ($this->isAdmin())
			{
				$foreign_id = $this->getForeignId();
				$type = 'calendar';
				if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
				{
					$foreign_id = (int) $_GET['foreign_id'];
				}
				if (isset($_GET['type']) && in_array($_GET['type'], $this->types))
				{
					$type = $_GET['type'];
				}
			} elseif ($this->isEmployee()) {
				$foreign_id = $this->getUserId();
				$type = 'employee';
			}
			
			$pjDateModel
				->where('t1.foreign_id', $foreign_id)
				->where('t1.type', $type);
			
			if (isset($_GET['is_dayoff']) && strlen($_GET['is_dayoff']) > 0 && in_array($_GET['is_dayoff'], array('T', 'F')))
			{
				$pjDateModel->where('t1.is_dayoff', $_GET['is_dayoff']);
			}
				
			$column = 'date';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjDateModel->findCount()->getData();
			$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjDateModel
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionSaveDate()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjDateModel = pjDateModel::factory();
			if (!in_array($_POST['column'], $pjDateModel->getI18n()))
			{
				$pjDateModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjDate');
			}
		}
		exit;
	}
	
	public function pjActionUpdateCustom()
	{
		$this->checkLogin();

		if ($this->isAdmin() || $this->isEmployee())
		{
			if (isset($_POST['custom_time']))
			{
				$data = array();
				$data['date'] = pjUtil::formatDate($_POST['date'], $this->option_arr['o_date_format']);
				$data['start_time'] = join(":", array($_POST['start_hour'], $_POST['start_minute']));
				$data['end_time'] = join(":", array($_POST['end_hour'], $_POST['end_minute']));
				$data['start_lunch'] = join(":", array($_POST['start_lunch_hour'], $_POST['start_lunch_minute']));
				$data['end_lunch'] = join(":", array($_POST['end_lunch_hour'], $_POST['end_lunch_minute']));
				$data['is_dayoff'] = isset($_POST['is_dayoff']) ? 'T' : 'F';
				
				pjDateModel::factory()->set('id', $_POST['id'])->modify($data);
				
				if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0 && isset($_POST['type']) && in_array($_POST['type'], $this->types))
				{
					pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionCustom&type=%s&foreign_id=%u&err=AT03",
						PJ_INSTALL_URL, $_POST['type'], $_POST['foreign_id']));
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=pjActionCustom&err=AT03");
			}
			
			$this->set('arr', pjDateModel::factory()->find($_GET['id'])->getData());
			
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('pjAdminTime.js');
		} else {
			$this->set('status', 2);
		}
	}
}
?>

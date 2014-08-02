<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminTime extends Admin
{
	function delete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				if ($this->isDemo())
				{
					$_GET['err'] = 7;
					$this->index();
					return;
				}
				
				if ($this->isXHR())
				{
					$this->isAjax = true;
					$id = $_POST['id'];
				} else {
					$id = $_GET['id'];
				}
				
				Object::import('Model', array('Date', 'Calendar'));
				$DateModel = new DateModel();
				$CalendarModel = new CalendarModel();
				
				$DateModel->addJoin($DateModel->joins, $CalendarModel->getTable(), 'TC', array('TC.id' => 't1.calendar_id'), array('TC.user_id'));
				$arr = $DateModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 8;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=8&tab_id=tabs-2");
					}
				} elseif ($this->isOwner() && $arr['user_id'] != $this->getUserId()) {
					if ($this->isXHR())
					{
						$_GET['err'] = 9;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=9&tab_id=tabs-2");
					}
				}
				
				if ($DateModel->delete($id))
				{
					Object::import('Model', 'Price');
					$PriceModel = new PriceModel();
					$PriceModel->delete(array('calendar_id' => $arr['calendar_id'], 'date' => $arr['date']));
					
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=3&tab_id=tabs-2");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=4&tab_id=tabs-2");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function getPrices()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('WorkingTime', 'PriceDay'));
			$WorkingTimeModel = new WorkingTimeModel();
			$PriceDayModel = new PriceDayModel();
			
			$this->tpl['wt_arr'] = $WorkingTimeModel->get($this->getCalendarId());
			$this->tpl['price_day_arr'] = $PriceDayModel->getAll(array('t1.calendar_id' => $this->getCalendarId(), 't1.day' => $_GET['day'], 'col_name' => 't1.start_time', 'direction' => 'asc'));
		}
	}
	
	function setPrices()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('PriceDay', 'WorkingTime'));
			$PriceDayModel = new PriceDayModel();
			$WorkingTimeModel = new WorkingTimeModel();
			
			$PriceDayModel->delete(array('calendar_id' => $this->getCalendarId(), '`day`' => $_POST['day']));
			if (!isset($_POST['delete']))
			{
				$data = array();
				$data['calendar_id'] = $this->getCalendarId();
				$data['day'] = $_POST['day'];
				foreach ($_POST['price'] as $k => $v)
				{
					if (empty($v) && strlen($v) === 0) continue;
					list($start_ts, $end_ts) = explode("|", $k);
					$data['price'] = $v;
					$data['start_time'] = date("H:i:s", $start_ts);
					$data['end_time'] = date("H:i:s", $end_ts);
					$PriceDayModel->save($data);
				}
				//Sasho ne iska da se update-va default cenata za denq v tozi sluchai (vodi do promqna i v AppCotroller::getPricesDate)
				//$WorkingTimeModel->update(array($_POST['day'] . '_price' => array('NULL')), array('calendar_id' => $this->getCalendarId()));
			}
			$this->index();
		}
	}
	
	function getSlots()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			
		}
	}
	
	function index()
	{
		if ($this->isLoged())
		{
            $owner_id = intval($_SESSION['admin_user']['owner_id']);
			if ($this->isAdmin() || $this->isOwner())
			{
				Object::import('Model', array('WorkingTime', 'Date'));
				$WorkingTimeModel = new WorkingTimeModel();
				$DateModel = new DateModel();
				
				if (isset($_POST['working_time']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=7");
					}
					
					$arr = $WorkingTimeModel->get($this->getCalendarId());
					
					$data = array();
					$data['calendar_id'] = $this->getCalendarId();
                    $data['owner_id'] =  $owner_id;
					$weekDays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
					foreach ($weekDays as $day)
					{
						if (!isset($_POST[$day . '_dayoff']))
						{
							$data[$day . '_from'] = $_POST[$day . '_hour_from'] . ":" . $_POST[$day . '_minute_from'];
							$data[$day . '_to'] = $_POST[$day . '_hour_to'] . ":" . $_POST[$day . '_minute_to'];
						}
					}
					
					if (count($arr) > 0)
					{
						$WorkingTimeModel->delete($this->getCalendarId());
					}
					$WorkingTimeModel->save(array_merge($_POST, $data));
					
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=4");
				}
				
				if (isset($_POST['custom_time']))
				{
					Object::import('Model', 'Price');
					$PriceModel = new PriceModel();
					
					$DateModel->delete(array('calendar_id' => $this->getCalendarid(), '`date`' => $_POST['date']));
					$PriceModel->delete(array('calendar_id' => $this->getCalendarId(), '`date`' => $_POST['date']));
					$_data = array();
					if (!isset($_POST['single_price']))
					{
						$data = array();
						$data['calendar_id'] = $this->getCalendarId();
						$data['date'] = $_POST['date'];
						foreach ($_POST['price'] as $k => $v)
						{
							if (!empty($v))
							{
								list($start_ts, $end_ts) = explode("|", $k);
								$data['price'] = $v;
								$data['start_time'] = date("H:i:s", $start_ts);
								$data['end_time'] = date("H:i:s", $end_ts);
								$data['start_ts'] = $start_ts;
								$data['end_ts'] = $end_ts;
								$PriceModel->save($data);
							}
						}
						$_data['price'] = array('NULL');
					}
					
					$data = array();
					$data['calendar_id'] = $this->getCalendarId();
					$data['start_time'] = join(":", array($_POST['start_hour'], $_POST['start_minute']));
					$data['end_time'] = join(":", array($_POST['end_hour'], $_POST['end_minute']));
					
					$DateModel->save(array_merge($_POST, $data, $_data));
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=5&tab_id=tabs-2");
				}
				
				$arr = $WorkingTimeModel->get($this->getCalendarId());
				$this->tpl['arr'] = $arr;
				
				$opts = array();
				$opts['t1.calendar_id'] = $this->getCalendarId();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $DateModel->getCount($opts);
				$row_count = 10;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$this->tpl['date_arr'] = $DateModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 't1.date', 'direction' => 'desc')));
				$this->tpl['paginator'] = array('pages' => $pages, 'row_count' => $row_count, 'count' => $count);
				
				# Dialog
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				# Datepicker
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminTime.js', 'path' => JS_PATH);
			}
		}
	}
	
	function update($id)
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				Object::import('Model', array('Date'));
				$DateModel = new DateModel();
				
				$arr = $DateModel->get($id);
				
				if (count($arr) == 0)
				{
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminTime&action=index&err=8&tab_id=tabs-2");
				}
				$this->tpl['arr'] = $arr;
				
				Object::import('Model', 'Price');
				$PriceModel = new PriceModel();
				$this->tpl['price_arr'] = $PriceModel->getAll(array('t1.calendar_id' => $arr['calendar_id'], 't1.date' => $arr['date'], 'col_name' => 't1.start_time', 'direction' => 'asc'));
				
				# Datepicker
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminTime.js', 'path' => JS_PATH);
				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}
?>

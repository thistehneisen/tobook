<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
/**
 * AdminCalendars controller
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers
 */
class AdminCalendars extends Admin
{
/**
 * Create new calendar
 *
 * @access public
 * @return void
 */
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				$err = NULL;
				if (isset($_POST['calendar_create']))
				{
					if ($this->isDemo())
					{
						$err = 7;
					} else {
					
						Object::import('Model', 'Calendar');
						$CalendarModel = new CalendarModel();
	
						$data = array();
						if ($this->isOwner())
						{
							$data['user_id'] = $this->getUserId();
						}
						$insert_id = $CalendarModel->save(array_merge($_POST, $data));
						if ($insert_id !== false && (int) $insert_id > 0)
						{
							Object::import('Model', array('Option', 'WorkingTime'));
							$OptionModel = new OptionModel();
							$WorkingTimeModel = new WorkingTimeModel();
				
							$WorkingTimeModel->initWorkingTime($insert_id);
							
							$arr = $OptionModel->getAll(array('group_by' => 't1.key', 'col_name' => 't1.key', 'direction' => 'asc'));
							foreach ($arr as $v)
							{
								//FIXME Optimize (bulk save)!
								$OptionModel->save(array_merge($v, array('calendar_id' => $insert_id)));
							}
							
							$_SESSION[$this->default_user]['calendar_id'] = $insert_id;
	            			$err = 1;
						} else {
							$err = 2;
						}
					}
				}
				
				Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=$err");
				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Delete a calendar
 *
 * @access public
 * @return void
 */
	function delete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				if (!$this->isMultiCalendar())
				{
					$this->tpl['status'] = 8;
					return;
				}
				
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
				
				Object::import('Model', 'Calendar');
				$CalendarModel = new CalendarModel();
					
				$arr = $CalendarModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 8;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=8");
					}
				} elseif ($this->isOwner() && $arr['user_id'] != $this->getUserId()) {
					if ($this->isXHR())
					{
						$_GET['err'] = 9;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=9");
					}
				}
				
				if ($CalendarModel->delete($id))
				{
					Object::import('Model', 'WorkingTime');
					$WorkingTimeModel = new WorkingTimeModel();
					$WorkingTimeModel->delete($id);
					
					$this->models['Option']->delete(array('calendar_id' => $id));
					
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=4");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function deleteBooking()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('Booking', 'BookingSlot'));
			$BookingModel = new BookingModel();
			if ($BookingModel->delete($_POST['id']))
			{
				$BookingSlotModel = new BookingSlotModel();
				$BookingSlotModel->delete(array('booking_id' => $_POST['id']));
			}
		}
	}
	
	function deleteTimeslot()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('BookingSlot'));
			$BookingSlotModel = new BookingSlotModel();
			$BookingSlotModel->delete(array('id' => $_POST['id']));
		}
	}
	
/**
 * Get calendar via AJAX
 *
 * @access public
 * @return void
 */
	function getCalendar()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$this->tpl['calendar'] = $this->calendar($_GET['id']);
		}
	}
	
	function getSlots()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			Object::import('Model', array('BookingSlot', 'Booking'));
			$BookingModel = new BookingModel();
			$BookingSlotModel = new BookingSlotModel();
			
			$t_arr = AppController::getRawSlots($this->getCalendarid(), $_GET['date'], $this->option_arr);
			if ($t_arr === false)
			{
				# It's Day off
				$this->tpl['dayoff'] = true;
				return;
			}
			
			$this->tpl['price_arr'] = AppController::getPricesDate($this->getCalendarid(), $_GET['date'], $this->option_arr);
			
			# Get booked slots for given date
			$BookingSlotModel->addJoin($BookingSlotModel->joins, $BookingModel->getTable(), 'TB', array('TB.id' => 't1.booking_id', 'TB.calendar_id' => $_GET['id']), array('TB.calendar_id'), 'inner');
			$bs_arr = $BookingSlotModel->getAll(array('t1.booking_date' => $_GET['date'], 't1.booking_status' => array("('cancelled')", 'NOT IN', 'null')));
			
			foreach ($bs_arr as $key => $bs) {
				if (isset($bs['booking_id'])) {
					$Booking = $BookingModel->get($bs['booking_id']);
					$bs_arr[$key]['customer_name'] = $Booking['customer_name'];
				}
			}
			
			$this->tpl['bs_arr'] = $bs_arr;
			
			$this->tpl['t_arr'] = $t_arr;
		}
	}
/**
 * List calendars
 *
 * (non-PHPdoc)
 * @see app/controllers/Admin::index()
 * @access public
 * @return void
 */
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				if (!$this->isMultiCalendar())
				{
					$this->tpl['status'] = 8;
					return;
				}
				
				$opts = array();
				if (isset($_GET['type']) && !empty($_GET['type']))
				{
					$opts['t1.type'] = $_GET['type'];
				}
				
				if ($this->isOwner())
				{
					$opts['t1.user_id'] = $this->getUserId();
				}
				
				Object::import('Model', array('Calendar', 'User'));
				$CalendarModel = new CalendarModel();
				$UserModel = new UserModel();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $CalendarModel->getCount($opts);
				$row_count = 10; #(int) $this->opts['per_page'] > 0 ? intval($this->opts['per_page']) : 10;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$CalendarModel->addJoin($CalendarModel->joins, $UserModel->getTable(), 'TU', array('TU.id' => 't1.user_id'), array('TU.username'));
				$arr = $CalendarModel->getAll(array_merge($opts, array('offset' => $offset, 'row_count' => $row_count, 'col_name' => 't1.calendar_title', 'direction' => 'asc')));
				
				$this->tpl['arr'] = $arr;
				$this->tpl['paginator'] = array('pages' => $pages, 'row_count' => $row_count, 'count' => $count);
				$this->tpl['user_arr'] = $UserModel->getAll(array('t1.role_id' => 2, 'col_name' => 't1.username', 'direction' => 'asc'));
				
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminCalendars.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Update calendar
 *
 * @param int $id
 * @access public
 * @return void
 */
	function update($id=null)
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				Object::import('Model', 'Calendar');
				$CalendarModel = new CalendarModel();
					
				if (isset($_POST['calendar_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=7");
					}
					
					$arr = $CalendarModel->get($_POST['id']);
					if (count($arr) == 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=8");
					} elseif ($this->isOwner() && $arr['user_id'] != $this->getUserId()) {
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=9");
					}
					
					$CalendarModel->update($_POST);
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=5");
					
				} else {
					$arr = $CalendarModel->get($id);
					
					if (count($arr) == 0)
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=index&err=8");
					}
					
					$this->tpl['arr'] = $arr;
					
					Object::import('Model', 'User');
					$UserModel = new UserModel();
					$this->tpl['user_arr'] = $UserModel->getAll(array('t1.role_id' => 2, 'col_name' => 't1.username', 'direction' => 'asc'));
				}
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminCalendars.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Update date
 *
 * @access public
 * @return void
 */
	function updateDate()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				Object::import('Model', 'Date');
				$DateModel = new DateModel();
				
				$arr = $DateModel->getAll(array('t1.calendar_id' => $_POST['calendar_id'], 't1.event_date' => $_POST['event_date'], 'col_name' => 't1.calendar_id', 'direction' => 'asc'));
				
				if (count($arr) == 1)
				{
					$DateModel->update($_POST, array('calendar_id' => $_POST['calendar_id'], 'event_date' => $_POST['event_date']));
				} elseif (count($arr) == 0) {
					$DateModel->save($_POST);
				}
				
				Util::redirect($_SERVER['PHP_SELF']."?controller=AdminCalendars&action=view&cid=".$_POST['calendar_id'] . "&err=10");
            			
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * View calendar
 *
 * @param int $id
 * @access public
 * @return void
 */
	function view($id)
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				$_SESSION[$this->default_user]['calendar_id'] = $id;
				$this->option_arr = $this->models['Option']->getPairs($id);
				$this->tpl['option_arr'] = $this->option_arr;
				
				$this->tpl['calendar'] = $this->calendar($id);
				
				# jQuery UI Dialog
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				# General
				$this->js[] = array('file' => 'adminCalendars.js', 'path' => JS_PATH);
				$this->css[] = array('file' => 'index.php?controller=AdminCalendars&action=css&cid=' . $id, 'path' => '');
				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}
?>
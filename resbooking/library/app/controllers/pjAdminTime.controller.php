<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminTime extends pjAdmin
{
	function delete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
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
				
				pjObject::import('Model', 'pjDate');
				$pjDateModel = new pjDateModel();
				
				$arr = $pjDateModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 8;
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=8&tab_id=tabs-3");
					}
				}
				
				if ($pjDateModel->delete($id))
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=3&tab_id=tabs-3");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=4&tab_id=tabs-3");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}

	function sdelete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
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
	
				pjObject::import('Model', 'pjService');
				$pjServiceModel = new pjServiceModel();
	
				$arr = $pjServiceModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 8;
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=8&tab_id=tabs-2");
					}
				}
	
				if ($pjServiceModel->delete($id))
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 3;
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=3&tab_id=tabs-2");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 4;
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=4&tab_id=tabs-2");
					}
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				pjObject::import('Model', array('pjWorkingTime', 'pjService', 'pjDate'));
				$pjWorkingTimeModel = new pjWorkingTimeModel();
				$pjDateModel = new pjDateModel();
				$pjServiceModel = new pjServiceModel();
				
				if (isset($_POST['working_time']))
				{
					if ($this->isDemo())
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=7");
					}
					
					$arr = $pjWorkingTimeModel->get(1);
					
					$data = array();
					$data['id'] = 1;

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
						$pjWorkingTimeModel->delete(1);
					}
					$pjWorkingTimeModel->save(array_merge($_POST, $data));
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=4");
				}
				
				if (isset($_POST['service_time'])) {
					
					$data = array();
					$data['start_time'] = join(":", array($_POST['start_hour'], $_POST['start_minute']));
					$data['end_time'] = join(":", array($_POST['end_hour'], $_POST['end_minute']));
					
					if ( isset($_POST['id'])) {
						$pjServiceModel->update(array_merge($_POST, $data));
					} else 
						$pjServiceModel->save(array_merge($_POST, $data));
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=5&tab_id=tabs-2");
				}
				if (isset($_POST['custom_time']))
				{
					$date = pjUtil::formatDate($_POST['date'], $this->option_arr['date_format']);
					$pjDateModel->delete(array('`date`' => $date));
					
					$data = array();
					$data['start_time'] = join(":", array($_POST['start_hour'], $_POST['start_minute']));
					$data['end_time'] = join(":", array($_POST['end_hour'], $_POST['end_minute']));
					$data['date'] = $date;
					
					$pjDateModel->save(array_merge($_POST, $data));
					//pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=5&tab_id=tabs-3");
				
					pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=schedule" );
				}
				
				$arr = $pjWorkingTimeModel->get(1);
				$this->tpl['arr'] = $arr;
				
				$opts = array();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $pjDateModel->getCount($opts);
				$row_count = 10;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$this->tpl['date_arr'] = $pjDateModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 't1.date', 'direction' => 'desc')));
				$this->tpl['paginator'] = array('pages' => $pages, 'row_count' => $row_count, 'count' => $count);
				
				$opts = array();
				
				$page = isset($_GET['spage']) && (int) $_GET['spage'] > 0 ? intval($_GET['spage']) : 1;
				$count = $pjServiceModel->getCount($opts);
				$row_count = 10;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$this->tpl['service_arr'] = $pjServiceModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 't1.start_time', 'direction' => 'ASC')));
				$this->tpl['spaginator'] = array('pages' => $pages, 'row_count' => $row_count, 'count' => $count);
				
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
				$this->js[] = array('file' => 'pjAdminTime.js', 'path' => JS_PATH);
			}
		}
	}
	
	function update($id)
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				pjObject::import('Model', array('pjDate'));
				$pjDateModel = new pjDateModel();
				
				$arr = $pjDateModel->get($id);
				
				if (count($arr) == 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=8&tab_id=tabs-3");
				}
				
				$this->tpl['arr'] = $arr;

				# Datepicker
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'pjAdminTime.js', 'path' => JS_PATH);
				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function supdate($id)
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				pjObject::import('Model', array('pjService'));
				$pjServiceModel = new pjServiceModel();
	
				$arr = $pjServiceModel->get($id);
	
				if (count($arr) == 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=index&err=8&tab_id=tabs-2");
				}
				$this->tpl['arr'] = $arr;
	
				# Datepicker
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
	
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'pjAdminTime.js', 'path' => JS_PATH);
	
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}
?>
<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminTables extends pjAdmin
{
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (isset($_POST['table_create']))
				{
					pjObject::import('Model', array('pjTable'));
					$pjTableModel = new pjTableModel();
					
					$id = $pjTableModel->save($_POST);
					if ($id !== false && (int) $id > 0)
					{
						$err = 'AT01';
					} else {
						$err = 'AT02';
					}
				} else {
					$err = 'AT03';
				}
				pjUtil::redirect(sprintf("%s?controller=pjAdminTables&action=index&err=%s", $_SERVER['PHP_SELF'], $err));
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function delete()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if ($this->isDemo())
				{
					$_GET['err'] = 'AA07';
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
				
				pjObject::import('Model', 'pjTable');
				$pjTableModel = new pjTableModel();
					
				$arr = $pjTableModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 'AT08';
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTables&action=index&err=AT08");
					}
				}
				
				if ($pjTableModel->delete($id))
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 'AT03';
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTables&action=index&err=AT03");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 'AT04';
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTables&action=index&err=AT04");
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
				pjObject::import('Model', array('pjTable', 'pjBooking'));
				$pjTableModel = new pjTableModel();
				$pjBookingModel = new pjBookingModel();
				
				$opts = array();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $pjTableModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				//$pjTableModel->addSubQuery($pjTableModel->subqueries, sprintf("SELECT COUNT(*) FROM `%s` WHERE `space_id` = `t1`.`id` AND `status` = 'confirmed' AND (CURDATE() BETWEEN DATE(`from`) AND DATE(`to`)) LIMIT 1", $pjBookingModel->getTable()), "booked");
				$this->tpl['arr'] = $pjTableModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 't1.name', 'direction' => 'desc')));
				$this->tpl['paginator'] = compact('pages');
				
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'pjAdminTables.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				pjObject::import('Model', array('pjTable'));
				$pjTableModel = new pjTableModel();
				
				if (isset($_POST['table_update']))
				{
					if ($pjTableModel->update($_POST))
					{
						$err = 'AT05';
					} else {
						$err = 'AT06';
					}
					pjUtil::redirect(sprintf("%s?controller=pjAdminTables&action=index&err=%s", $_SERVER['PHP_SELF'], $err));
				} else {
					$arr = $pjTableModel->get($_GET['id']);
					if (count($arr) === 0)
					{
						pjUtil::redirect(sprintf("%s?controller=pjAdminTables&action=index&err=%s", $_SERVER['PHP_SELF'], 'AT08'));
					}
					$this->tpl['arr'] = $arr;
					
					$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
					
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'pjAdminTables.js', 'path' => JS_PATH);
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}
?>
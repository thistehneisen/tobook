<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminVouchers extends pjAdmin
{
	function create()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (isset($_POST['voucher_create']))
				{
					pjObject::import('Model', array('pjVoucher'));
					$pjVoucherModel = new pjVoucherModel();
					
					$data = array();
					$data['code'] = $_POST['code'];
					$data['discount'] = $_POST['discount'];
					$data['type'] = $_POST['type'];
					$data['valid'] = $_POST['valid'];
					switch ($_POST['valid'])
					{
						case 'fixed':
							$data['date_from'] = pjUtil::formatDate($_POST['f_date'], $this->option_arr['date_format']);
							$data['date_to'] = $data['date_from'];
							$data['time_from'] = $_POST['f_hour_from'] . ":" . $_POST['f_minute_from'] . ":00";
							$data['time_to'] = $_POST['f_hour_to'] . ":" . $_POST['f_minute_to'] . ":00";
							break;
						case 'period':
							$data['date_from'] = pjUtil::formatDate($_POST['p_date_from'], $this->option_arr['date_format']);
							$data['date_to'] = pjUtil::formatDate($_POST['p_date_to'], $this->option_arr['date_format']);
							$data['time_from'] = $_POST['p_hour_from'] . ":" . $_POST['p_minute_from'] . ":00";
							$data['time_to'] = $_POST['p_hour_to'] . ":" . $_POST['p_minute_to'] . ":00";
							break;
						case 'recurring':
							$data['every'] = $_POST['r_every'];
							$data['time_from'] = $_POST['r_hour_from'] . ":" . $_POST['r_minute_from'] . ":00";
							$data['time_to'] = $_POST['r_hour_to'] . ":" . $_POST['r_minute_to'] . ":00";
							break;
					}
					
					$id = $pjVoucherModel->save($data);
					if ($id !== false && (int) $id > 0)
					{
						$err = 'AV01';
					} else {
						$err = 'AV02';
					}
				} else {
					$err = 'AV03';
				}
				pjUtil::redirect(sprintf("%s?controller=pjAdminVouchers&action=index&err=%s", $_SERVER['PHP_SELF'], $err));
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
				
				pjObject::import('Model', 'pjVoucher');
				$pjVoucherModel = new pjVoucherModel();
					
				$arr = $pjVoucherModel->get($id);
				if (count($arr) == 0)
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 'AV08';
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVouchers&action=index&err=AV08");
					}
				}
				
				if ($pjVoucherModel->delete($id))
				{
					if ($this->isXHR())
					{
						$_GET['err'] = 'AV03';
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVouchers&action=index&err=AV03");
					}
				} else {
					if ($this->isXHR())
					{
						$_GET['err'] = 'AV04';
						$this->index();
						return;
					} else {
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminVouchers&action=index&err=AV04");
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
				pjObject::import('Model', array('pjVoucher', 'pjBooking'));
				$pjVoucherModel = new pjVoucherModel();
				$pjBookingModel = new pjBookingModel();
				
				$opts = array();
				
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$count = $pjVoucherModel->getCount($opts);
				$row_count = 20;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;

				$this->tpl['arr'] = $pjVoucherModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 't1.code', 'direction' => 'desc')));
				$this->tpl['paginator'] = compact('pages');
				
				$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				
				$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'pjAdminVouchers.js', 'path' => JS_PATH);
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
				pjObject::import('Model', array('pjVoucher'));
				$pjVoucherModel = new pjVoucherModel();
				
				if (isset($_POST['voucher_update']))
				{
					$data = array();
					$data['id'] = $_POST['id'];
					$data['code'] = $_POST['code'];
					$data['discount'] = $_POST['discount'];
					$data['type'] = $_POST['type'];
					$data['valid'] = $_POST['valid'];
					switch ($_POST['valid'])
					{
						case 'fixed':
							$data['date_from'] = pjUtil::formatDate($_POST['f_date'], $this->option_arr['date_format']);
							$data['date_to'] = $data['date_from'];
							$data['time_from'] = $_POST['f_hour_from'] . ":" . $_POST['f_minute_from'] . ":00";
							$data['time_to'] = $_POST['f_hour_to'] . ":" . $_POST['f_minute_to'] . ":00";
							$data['every'] = array('NULL');
							break;
						case 'period':
							$data['date_from'] = pjUtil::formatDate($_POST['p_date_from'], $this->option_arr['date_format']);
							$data['date_to'] = pjUtil::formatDate($_POST['p_date_to'], $this->option_arr['date_format']);
							$data['time_from'] = $_POST['p_hour_from'] . ":" . $_POST['p_minute_from'] . ":00";
							$data['time_to'] = $_POST['p_hour_to'] . ":" . $_POST['p_minute_to'] . ":00";
							$data['every'] = array('NULL');
							break;
						case 'recurring':
							$data['date_from'] = array('NULL');
							$data['date_to'] = array('NULL');
							$data['every'] = $_POST['r_every'];
							$data['time_from'] = $_POST['r_hour_from'] . ":" . $_POST['r_minute_from'] . ":00";
							$data['time_to'] = $_POST['r_hour_to'] . ":" . $_POST['r_minute_to'] . ":00";
							break;
					}
					
					if ($pjVoucherModel->update($data))
					{
						$err = 'AV05';
					} else {
						$err = 'AV06';
					}
					pjUtil::redirect(sprintf("%s?controller=pjAdminVouchers&action=index&err=%s", $_SERVER['PHP_SELF'], $err));
				} else {
					$arr = $pjVoucherModel->get($_GET['id']);
					if (count($arr) === 0)
					{
						pjUtil::redirect(sprintf("%s?controller=pjAdminVouchers&action=index&err=%s", $_SERVER['PHP_SELF'], 'AV08'));
					}
					$this->tpl['arr'] = $arr;
					
					$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
					
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'pjAdminVouchers.js', 'path' => JS_PATH);
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
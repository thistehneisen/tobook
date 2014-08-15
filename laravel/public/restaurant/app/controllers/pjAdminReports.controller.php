<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminReports extends pjAdmin
{
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (isset($_GET['report']))
				{
					$sql_date_from = $sql_date_to = NULL;
					if (isset($_GET['date_from']) && !empty($_GET['date_from']))
					{
						$date_from = pjUtil::formatDate($_GET['date_from'], $this->option_arr['date_format']);
						$sql_date_from = sprintf(" AND DATE(`dt`) >= '%s'", $date_from);
					}
					if (isset($_GET['date_to']) && !empty($_GET['date_to']))
					{
						$date_to = pjUtil::formatDate($_GET['date_to'], $this->option_arr['date_format']);
						$sql_date_to = sprintf(" AND DATE(`dt_to`) <= '%s'", $date_to);
					}
					pjObject::import('Model', 'pjBooking');
					$pjBookingModel = new pjBookingModel();
					$sql = sprintf("SELECT 1,
						(SELECT COUNT(*) FROM `%1\$s` WHERE `status` = 'confirmed' %2\$s %3\$s LIMIT 1) AS `total_bookings`,
						(SELECT SUM(`total`) FROM `%1\$s` WHERE `status` = 'confirmed' AND `is_paid` = 'total' %2\$s %3\$s LIMIT 1) AS `paid_total`",
						$pjBookingModel->getTable(),
						$sql_date_from,
						$sql_date_to
					);
					$arr = $pjBookingModel->execute($sql);
					$this->tpl['arr'] = $arr[0];
				}
				
				$this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
				$this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				$this->js[] = array('file' => 'pjAdminReports.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}
?>
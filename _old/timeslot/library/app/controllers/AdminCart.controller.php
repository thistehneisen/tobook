<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
class AdminCart extends Admin
{
	var $cart = null;
	
	function AdminCart()
	{
		Object::import('Component', 'Cart');
		$this->cart = new Cart($this->cartName);
	}
	
	function add()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$qty = isset($_POST['qty']) && (int) $_POST['qty'] > 0 ? intval($_POST['qty']) : 1;
			foreach ($_POST['timeslot'] as $date => $time_arr)
			{
				/* foreach ($time_arr as $start_ts => $end_ts)
				{
					$this->cart->add($this->getCalendarId(), $date, $start_ts ."|". $end_ts, $qty);
				} */
				
				// Modified by Raccoon
				foreach ($time_arr as $cid => $timets) {
					foreach($timets as $start_ts => $end_ts) {
						$this->cart->add(/* $this->getCalendarId() */$cid, $date, $start_ts ."|". $end_ts, $qty);
					}
				}
			}
		}
	}
	
	// Added by Raccoon for Bulk processing basket
	function bulkbasket() {
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			// Added by Raccoon
			$all_basket = isset($_GET['basket_type']) ? $_GET['basket_type'] : 0;
			Object::import('Model', array('Calendar'));
			$cal_model = new CalendarModel();
			$callist = $cal_model->getAll(array('col_name'=>'t1.id', 'direction'=>'asc'));
	
			$this->tpl['basket_type'] = 1;
			foreach( $callist as $k => $v ) {
				$this->tpl['slots'][$v['id']]['cart_arr'] = $_SESSION[$this->cartName];
				$this->tpl['slots'][$v['id']]['cart_price_arr'] = AppController::getCartPrices($v['id'], $this->cartName);
				$this->tpl['slots'][$v['id']]['cal_name'] = $v['calendar_title'];
			}
		}
	}
	
	function basket()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$calendar_id = isset($_GET['calendar_id']) ? $_GET['calendar_id'] : $this->getCalendarId();
			$this->tpl['cart_arr'] = $_SESSION[$this->cartName];
			$this->tpl['cart_price_arr'] = AppController::getCartPrices($calendar_id, $this->cartName);
		}
	}
	
	// Added by Raccoon for remove bulk processing
	function bulkremove() {
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			// $calendar_id = isset($_POST['calendar_id']) && (int) $_POST['calendar_id'] > 0 ? $_POST['calendar_id'] : $this->getCalendarId();
			foreach ($_POST['timeslot'] as $date => $rdata)
			{
				foreach ($rdata as $cid => $time_arr) {
					foreach ($time_arr as $start_ts => $end_ts)
					{
						$this->cart->remove($cid, $date, $start_ts . "|" . $end_ts);
					}
				}
			}
		}
	}
	
	function remove()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$calendar_id = isset($_POST['calendar_id']) && (int) $_POST['calendar_id'] > 0 ? $_POST['calendar_id'] : $this->getCalendarId();
			foreach ($_POST['timeslot'] as $date => $time_arr)
			{
				foreach ($time_arr as $start_ts => $end_ts)
				{
					$this->cart->remove($calendar_id, $date, $start_ts . "|" . $end_ts);
				}
			}
		}
	}
	
	function reset()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$this->cart->reset();
		}
	}
	
	function update()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			foreach ($_POST['timeslot'] as $date => $time_arr)
			{
				foreach ($time_arr as $start_ts => $end_ts)
				{
					$this->cart->remove($this->getCalendarId(), $date, $start_ts . "|" . $end_ts);
				}
			}
		}
	}
}
?>
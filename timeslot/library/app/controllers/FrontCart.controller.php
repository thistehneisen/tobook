<?php
require_once CONTROLLERS_PATH . 'Front.controller.php';
class FrontCart extends Front
{
	var $cart = null;
	
	function FrontCart()
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
				foreach ($time_arr as $start_ts => $end_ts)
				{
					$this->cart->add($_GET['cid'], $date, $start_ts ."|". $end_ts, $qty);
				}
			}
		}
	}
/**
 * Use if you want to sort date and times in basket. May increase the time of loading!
 * 
 * @example 
 * $this->sort($_SESSION[$this->cartName]);
 * 
 * @param $arr
 */
	function sort(&$arr)
	{
		$_arr = array();
		foreach ($arr as $cid => $date_arr)
		{
			foreach ($date_arr as $date => $time_arr)
			{
				$_time_arr = array();
				foreach ($time_arr as $time => $q)
				{
					list($start_ts, $end_ts) = explode("|", $time);
					$_time_arr[$start_ts] = $time;
				}
				ksort($_time_arr); //ksort, krsort
				$_arr[$cid][strtotime($date)] = array_flip($_time_arr);
			}
			ksort($_arr[$cid]); //ksort, krsort
		}		
		$arr = array();
		foreach ($_arr as $cid => $date_arr)
		{
			foreach ($date_arr as $date => $time_arr)
			{
				$arr[$cid][date("Y-m-d", $date)] = $time_arr;
			}
		}
	}
	
	function basket()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			$this->tpl['cart_arr'] = $_SESSION[$this->cartName];
			$this->tpl['cart_price_arr'] = AppController::getCartPrices($_GET['cid'], $this->cartName);
		}
	}
	
	function remove()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			foreach ($_POST['timeslot'] as $date => $time_arr)
			{
				foreach ($time_arr as $start_ts => $end_ts)
				{
					$this->cart->remove($_GET['cid'], $date, $start_ts . "|" . $end_ts);
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
					$this->cart->remove($_GET['cid'], $date, $start_ts . "|" . $end_ts);
				}
			}
		}
	}
}
?>
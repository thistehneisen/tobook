<?php
class Cart
{
	var $cartName;
	
	function Cart($cartName)
	{
		$this->cartName = $cartName;
		
		if (!array_key_exists($this->cartName, $_SESSION))
		{
			$this->reset();
		}
	}
	
	function add($type, $article_id, $size_id, $qty)
	{
		if ($this->check($type, $article_id, $size_id))
		{
			$this->increase($type, $article_id, $size_id, $qty);
		} else {
			$this->insert($type, $article_id, $size_id, $qty);
		}
	}
	
	function check($type, $article_id, $size_id)
	{
		return array_key_exists($type, $_SESSION[$this->cartName]) && array_key_exists($article_id, $_SESSION[$this->cartName][$type]) && array_key_exists($size_id, $_SESSION[$this->cartName][$type][$article_id]);
	}
	
	function decrease($type, $article_id, $size_id, $qty)
	{
		$_SESSION[$this->cartName][$type][$article_id][$size_id] -= intval($qty);
		
		if ($_SESSION[$this->cartName][$type][$article_id][$size_id] < 0)
		{
			$_SESSION[$this->cartName][$type][$article_id][$size_id] = 0;
		}
	}
		
	function increase($type, $article_id, $size_id, $qty)
	{
		$_SESSION[$this->cartName][$type][$article_id][$size_id] += intval($qty);
	}
	
	function insert($type, $article_id, $size_id, $qty)
	{
		$_SESSION[$this->cartName][$type][$article_id][$size_id] = intval($qty);
	}
	
	function remove($type, $article_id, $size_id)
	{
		if ($this->check($type, $article_id, $size_id))
		{
			unset($_SESSION[$this->cartName][$type][$article_id][$size_id]);
			if (count($_SESSION[$this->cartName][$type][$article_id]) == 0)
			{
				unset($_SESSION[$this->cartName][$type][$article_id]);
			}
		}
	}

	function update($type, $article_id, $size_id, $qty)
	{
		$_SESSION[$this->cartName][$type][$article_id][$size_id] = intval($qty);
		
		if ($_SESSION[$this->cartName][$type][$article_id][$size_id] < 0)
		{
			$_SESSION[$this->cartName][$type][$article_id][$size_id] = 0;
		}
	}
	
	function reset()
	{
		$_SESSION[$this->cartName] = array();
	}
}
?>
<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppModel extends pjModel
{
	protected $isUseOwnerID  = true;
	public static function factory($attr=array())
	{
		return new pjAppModel($attr);
	}

	function getData(){
		if($this->isUseOwnerID){
			$owner_id = intval($_SESSION['owner_id']);
			$this->where('t1.owner_id', $owner_id);
		}
		return parent::getData();
	}

	function disableOwnerID(){
		$this->isUseOwnerID = false;
	}
}
?>

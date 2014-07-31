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

    public function findCount()
    {
        $this->setOwnerId();
        return parent::findCount();
    }

	public function getData(){
		$this->setOwnerId();
		return parent::getData();
	}

	public function disableOwnerID() {
		$this->isUseOwnerID = false;
	}

    protected function setOwnerId()
    {
        if($this->isUseOwnerID) {
            $this->where('t1.owner_id', (int) $_SESSION['owner_id']);
        }
    }
}


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

    public function findAll()
    {
        $this->setOwnerId();
        return parent::findAll();
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

    public function join($model, $condition, $direction) {
        $this->setOwnerId();
        return parent::join($model, $condition, $direction);
    }

    protected function setOwnerId()
    {
        if($this->isUseOwnerID) {
            $this->where('t1.owner_id', (int) $_SESSION['owner_id']);
        }
    }
}


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
        return $this;
	}

    public function join($model, $condition, $direction = null) {
        $this->setOwnerId();
        return parent::join($model, $condition, $direction);
    }

    protected function setOwnerId($tableAlias='t1.')
    {
        $owner_id = $this->getOwnerId();
        if($this->isUseOwnerID) {
            $this->where($tableAlias.'owner_id', $owner_id);
        }
    }

    private function getOwnerId(){
        $owner_id = 0;
        $use_front_owner_id = (bool)$_SESSION['use_front_owner_id'];
        if(isset($_SESSION['owner_id'])){
            $owner_id = intval($_SESSION['owner_id']);
        }
        if(isset($_SESSION['front_owner_id']) && $use_front_owner_id){
            $owner_id = intval($_SESSION['front_owner_id']);
        } 
        return $owner_id;
    }

    public function eraseAll(){
        $this->setOwnerId('');
        return parent::eraseAll();
    }

    public function modifyAll($data=array()){
        $this->setOwnerId('');
        return parent::modifyAll($data);
    }

    public function erase(){
        $this->setOwnerId('');
        return parent::erase();
    }
}


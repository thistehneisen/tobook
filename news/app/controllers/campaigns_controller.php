<?php
class CampaignsController extends AppController {

	var $name = 'Campaigns';
    var $uses = array("Campaign", "Category");


	function index() {
		$this->Campaign->recursive = 0;
		$this->set('campaigns', $this->paginate());
	}

 
	function add() {
		if (!empty($this->data)) {
			$this->Campaign->create();
			if ($this->Campaign->save($this->data)) {
				$this->Session->setFlash(__('The campaign has been saved', true));
				 $this->redirect(array("controller"=>"mails",'action' => 'campaign',$this->Campaign->id));
			} else {
				$this->Session->setFlash(__('The campaign could not be saved. Please, try again.', true));
			}
		}else{
                    $this->data["Campaign"]["forever"]=1;
                }
                $this->set('cats', $this->Category->find("list"));
	}

	function edit($id = null,$r=0) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid campaign', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Campaign->save($this->data)) {
				$this->Session->setFlash(__('The campaign has been saved', true));
                                if($r==1){
                                   $this->redirect(array("controller"=>"mails",'action' => 'campaign',$this->Campaign->id));
                                }else{
				$this->redirect(array('action' => 'index'));
                                }
			} else {
				$this->Session->setFlash(__('The campaign could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Campaign->read(null, $id);
		}
                $this->set('cats', $this->Category->find("list"));
                $this->set('r', $r);
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for campaign', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Campaign->delete($id)) {
			$this->Session->setFlash(__('Campaign deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Campaign was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>
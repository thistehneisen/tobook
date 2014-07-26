<?php

class UsersController extends AppController {

    var $name = 'Users';
    public function beforeFilter() {
       if($this->Session->read('Auth.User.level')!="0"){
             if(in_array($this->action , array("index","add","edit","delete"))){
                     $this->Session->setFlash(__('Access Denied', true));
                 $this->redirect("/");
             }
        }
        parent::beforeFilter();
         
    }
    function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    function login() {
        $this->layout = "log";

		    if ($this->Auth->user()) {
			    $this->User->id = $this->Auth->user('id');
			    $this->User->saveField('last_login', date(DATE_ATOM));
			    $this->redirect($this->Auth->redirect());
		    }

    }

    function logout() {
        $this->redirect($this->Auth->logout());
    }

    function add() {
        if (!empty($this->data)) {
            $this->User->create();
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__('The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid user', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->User->save($this->data)) {
                $this->Session->setFlash(__('The user has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->User->read(null, $id);
        }
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for user', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->User->find("count") == 1) {
            $this->Session->setFlash(__('There must be at least one user', true));
            $this->redirect(array('action' => 'index'));
        } else if ($this->User->delete($id)) {
            $this->Session->setFlash(__('User deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>
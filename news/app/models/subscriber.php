<?php

class Subscriber extends AppModel {

    var $name = 'Subscriber';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $validate = array('mail_adresse' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
                //'allowEmpty' => false,
                //'required' => false,
                'last' => false // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
        )));
    
    var $hasMany = array(
        'RecivedMails' => array(
            'className' => 'Recipient',
            'foreignKey' => 'subscriber_id',
            'dependent' => true,
            'conditions' => array("sent" => 1),
            'fields' => '',
            'order' => 'send_date desc',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'FailedMails' => array(
            'className' => 'Recipient',
            'foreignKey' => 'subscriber_id',
            'dependent' => true,
            'conditions' => array("failed >" => 1),
            'fields' => '',
            'order' => 'send_date desc',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    var $hasAndBelongsToMany = array(
        'Category' => array(
            'className' => 'Category',
            'joinTable' => 'categories_subscribers',
            'foreignKey' => 'subscriber_id',
            'associationForeignKey' => 'category_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );

    function unsubscribe($id) {
        $this->id = $id;

        $this->saveField("deleted", 1);
    }

    function resubscribe($id) {
        $this->id = $id;

        $this->saveField("deleted", 0);
    }

    function confirm($id) {
        $this->id = $id;

        $this->saveField("deleted", 0);
        $this->saveField('created', date('Y-m-d H:i:s'));
    }
    public function afterDelete() {
        $this->query("DELETE FROM `newsletter`.`categories_subscribers` WHERE `categories_subscribers`.`subscriber_id` = ".$this->id.";");
        parent::afterDelete();
    }
}

?>
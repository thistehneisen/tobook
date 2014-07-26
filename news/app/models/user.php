<?php

class User extends AppModel {

    var $name = 'User';
   var $validate = array(        'username' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            ));
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    function afterFind($results) {
        foreach ($results as $key => $val) {
            if (isset($val['User']['password'])) {
                $results[$key]['User']['password'] = "";
            }
        }
        return $results;
    }

    function beforeSave() {
        if (isset($this->data['User']['password']) && !empty($this->data['User']['password'])) {
 
        } else if (empty($this->data['User']['password'])) {
            unset($this->data['User']['password']);
        }
        return true;
    }

}

?>
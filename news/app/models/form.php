<?php

class Form extends AppModel {

    var $name = 'Form';
    var $displayField = 'name';
    var $validate = array('name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            ));
    var $belongsTo = array(
        'Configuration' => array(
            'className' => 'Configuration',
            'foreignKey' => 'configuration_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public function beforeSave() {
        if (isset($this->data['Content'])) {


            $this->data['Form']['content'] = myserialize($this->data['Content']);
        }
        if (isset($this->data['Form']['Category'])) {


            $this->data['Form']['selected_categories'] = myserialize($this->data['Form']['Category']);
        }
         if (isset($this->data['notifym'])) {


            $this->data['Form']['notify_mail'] = myserialize($this->data['notifym']);
        }
                 if (isset($this->data['confirmm'])) {


            $this->data['Form']['confirm_mail'] = myserialize($this->data['confirmm']);
        }
//   $this->data['Mail']['Data'] = $this->Node->decodeData( $this->data['Mail']['content_html'] );
        return true;
    }

    function afterFind($results) {
        foreach ($results as $key => $val) {
            if (isset($val['Form']['content'])) {
                $results[$key]['Content'] = myunserialize($val['Form']['content']);
            }
            if (isset($val['Form']['selected_categories'])) {


                $results[$key]['Form']['Category'] = myunserialize($val['Form']['selected_categories']);
            }
            if (isset($results[$key]['Form']['notify_mail'])) {
                $results[$key]['notifym'] = array();
                $results[$key]['notifym'] = @myunserialize($results[$key]['Form']['notify_mail']);
            }
            if (isset($results[$key]['Form']['confirm_mail'])) {
                $results[$key]['confirmm'] = array();
                $results[$key]['confirmm'] = @myunserialize($results[$key]['Form']['confirm_mail']);
            }
        }
        return $results;
    }

}

?>
<?php

class Campaign extends AppModel {

    var $name = 'Campaign';
    var $validate = array('Category' => array('rule' => 'checkCategory'),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ), 'end' => array('rule' => 'vend', 'message' => 'The end day must be on the start day or later.'));
    var $virtualFields = array(
        'active' => "IF((DATE_ADD(Campaign.end, INTERVAL 1 DAY)>NOW() OR Campaign.forever=1) AND Campaign.start<=NOW(),1, IF(Campaign.start>NOW() ,2,0))"
    );
    var $order = "active desc";
    var $hasMany = array(
        'Mail' => array(
            'className' => 'Mail',
            'foreignKey' => 'campaign_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    function checkCategory() {

        if (!empty($this->data['Campaign']['Category'])) {
            return true;
        }

        return false;
    }

    function vend() {

        if ($this->data['Campaign']['start'] <= $this->data['Campaign']['end'] || $this->data['Campaign']['forever'] == 1) {
            return true;
        }

        return false;
    }

    public function beforeSave() {

        if (isset($this->data['Campaign']['Category'])) {


            $this->data['Campaign']['categories'] = myserialize($this->data['Campaign']['Category']);
        }

//   $this->data['Mail']['Data'] = $this->Node->decodeData( $this->data['Mail']['content_html'] );
        return true;
    }

    function afterFind($results) {
        foreach ($results as $key => $val) {

            if (isset($val['Campaign']['categories'])) {


                $results[$key]['Campaign']['Category'] = @myunserialize($val['Campaign']['categories']);
            }
        }
        return $results;
    }

}

?>
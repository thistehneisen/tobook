<?php

class Recipient extends AppModel {

    var $name = 'Recipient';
    var $validate = array(
        'mail_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'subscriber_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Subscriber' => array(
            'className' => 'Subscriber',
            'foreignKey' => 'subscriber_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Mail' => array(
            'className' => 'Mail',
            'foreignKey' => 'mail_id',
            'conditions' => '',
            'fields' => array("id", "subject"),
            'order' => ''
        )
    );

    function openMail($mail, $subscr, $docount=true) {
        $rc = $this->find("first", array("conditions" => array("mail_id" => $mail, "subscriber_id" => $subscr)));
        if ($rc == null || $mail == null || $subscr == null || $rc["Recipient"]["read"] == 1) {
            if (isset($rc["Recipient"]["read"]) && $rc["Recipient"]["read"] == 1 && $docount) {

                $this->id = $rc["Recipient"]["id"];

                $this->saveField("open_count", $rc["Recipient"]["open_count"] + 1);
            }
            return;
        }
        $this->id = $rc["Recipient"]["id"];

        $this->saveField("read", 1);
        $this->saveField("open_count", 1);
        $country = "";
        if (in_array('curl', get_loaded_extensions())) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'http://api.hostip.info/country.php?ip=' . $_SERVER['REMOTE_ADDR']);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $country = curl_exec($ch);

            curl_close($ch);
        }

        if (empty($country)) {
            $country = "XX";
        } if ($country == "uk") {
            $country = "gb";
        }
        $this->saveField("country", strtolower($country));
        $this->saveField("read_date", date('Y-m-d h:i:s'));
    }

}

?>
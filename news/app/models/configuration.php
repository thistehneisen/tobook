<?php

class Configuration extends AppModel {

    var $name = 'Configuration';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $validate = array(
        'mails_per_connection' => array(
            'notempty' => array(
                'rule' => array('numeric'),
                'message' => 'Please enter a number',
                //'allowEmpty' => false,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'inbox_wait' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please enter a number',
                'allowEmpty' => true,
                'required' => true,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
        )),
       
    );
    var $hasMany = array(
        'Mail' => array(
            'className' => 'Mail',
            'foreignKey' => 'configuration_id',
            'dependent' => false,
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

    function getTransport($settings) {
        $transport = null;
        if ($settings["delivery"] == 1) {
            $host = $settings["host"];
            $port = $settings["port"];
            $username = $settings["username"];
            $password = $settings["password"];
            $transport = Swift_SmtpTransport::newInstance($host, !empty($port) ? $port : 25);
            if ($settings["smtp_auth"] == 1) {

                $transport->setUsername($username)
                        ->setPassword($password);
            }
        } elseif ($settings["delivery"] == 2) {
            $transport = Swift_MailTransport::newInstance();
        } elseif ($settings["delivery"] == 4) {

            $transport = Swift_SendmailTransport::newInstance($settings["sendmail_path"]);
        } elseif ($settings["delivery"] == 3) {


        require_once '../vendors/AmazonTransport.php';

           $transport = Swift_Transport_AmazonTransport::newInstance($settings["aws_access_key"], $settings["aws_secret_key"]);
            
        }
        return $transport;
    }

    function afterFind($results) {
        foreach ($results as $key => $val) {
            if (isset($val['Configuration']['inbox_flags'])) {
                $da = split("/", $results[$key]['Configuration']['inbox_flags']);
                $results[$key]['Configuration']["inbox_settings"] = $da;
                if (in_array("pop3", $da)) {
                    $results[$key]['Configuration']["inbox_service"] = "pop3";
                } elseif (in_array("nntp", $da)) {
                    $results[$key]['Configuration']["inbox_service"] = "nntp";
                } elseif (in_array("imap", $da)) {
                    $results[$key]['Configuration']["inbox_service"] = "imap";
                }
            }
        }
        return $results;
    }

    function beforeValidate($options = array()) {

        if (isset($this->data['Configuration']["inbox_service"])) {
            if (isset($this->data['Configuration']["inbox_settings"]) && !empty($this->data['Configuration']["inbox_settings"])) {
                $arr = array_merge((array) $this->data['Configuration']["inbox_service"], (array) $this->data['Configuration']["inbox_settings"]);
            } else {
                $arr = array($this->data['Configuration']["inbox_service"]);
            }

            $this->data['Configuration']["inbox_flags"] = "/" . implode("/", $arr);
        }
        return parent::beforeValidate($options);
    }

}

?>
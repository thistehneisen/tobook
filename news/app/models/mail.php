<?php

class Mail extends AppModel {

    var $name = 'Mail';
    var $displayField = 'subject';
    public $actsAs = array(
        'Encoder'
    );
    var $order = array("modified desc");
    var $validate = array(
        'subject' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'configuration_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'delay' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            //'message' => 'Your custom message here',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
        )
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'Configuration' => array(
            'className' => 'Configuration',
            'foreignKey' => 'configuration_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Template' => array(
            'className' => 'Template',
            'foreignKey' => 'template_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    var $hasMany = array(
        'Recipient' => array(
            'className' => 'Recipient',
            'foreignKey' => 'mail_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => 1,
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'Link' => array(
            'className' => 'Link',
            'foreignKey' => 'mail_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => 'clicks desc',
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
            'joinTable' => 'mails_categories',
            'foreignKey' => 'mail_id',
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
    public function beforeSave() {
        if (isset($this->data['Mail']['Data']) && count($this->data['Mail']['Data']) > 0) {


            $this->data['Mail']['content_html'] = $this->encodeData($this->data['Mail']['Data'], array(
                        'base64' => true,
                        'json' => true,
                    ));
        }
//   $this->data['Mail']['Data'] = $this->Node->decodeData( $this->data['Mail']['content_html'] );
        return true;
    }

    function afterFind($results) {
        foreach ($results as $key => $val) {
            if (isset($val['Mail']['content_html'])) {
                $results[$key]['Mail']['Data'] = $this->decodeData($val['Mail']['content_html']);
            }
        }
        return $results;
    }

    function addInfos($content, $subscriber) {
        require_once "../vendors/smarty/Smarty.class.php";
        $smarty = new Smarty;
	    $smarty->assign('template_source', str_replace(array("<!---", "--->", "- -->", "- -->", "[[", "]]"),    array("{", "}", "}", "{", "}"), str_replace(array("<script", "/script>"), array("{literal}<script", "/script>{/literal}"), str_replace(array("<style", "/style>"), array("{literal}<style", "/style>{/literal}"), preg_replace('/(%7B|\{)(\$|%24)([^%]*)(%7D|\})/i', '{$\3}', $content)))));
	    $smarty->assign('template_source', str_replace(array("<!---", "--->", "- -->", "- -->", "[[", "]]"),    array("{", "}", "}", "{", "}"), str_replace(array("<script", "/script>"), array("{literal}<script", "/script>{/literal}"), str_replace(array("<style", "/style>"), array("{literal}<style", "/style>{/literal}"), preg_replace('/(%7B|\{)(\$|%24)([^%]*)(%7D|\})/i', '{$\3}', $content)))));
        $smarty->assign("first_name", $subscriber["first_name"]);
        $smarty->assign("last_name", $subscriber["last_name"]);
        $smarty->assign("subscriber_mail", $subscriber["mail_adresse"]);
        $smarty->assign("custom1", $subscriber["custom1"]);
        $smarty->assign("custom2", $subscriber["custom2"]);
        $smarty->assign("custom3", $subscriber["custom3"]);
        $smarty->assign("custom4", $subscriber["custom4"]);
        $smarty->assign('FORMID', $subscriber["form_id"]);
        $smarty->assign('SUBSCRIBER_ID', $subscriber["id"] . "-" . $subscriber["unsubscribe_code"]);
        $smarty->template_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/templates';
        $smarty->compile_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/templates_c';
        $smarty->cache_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/cache';
        $smarty->config_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/configs';
        return $smarty->fetch('template.tlp');
    }

    function updateUrls($content, $mail_id, $data=array(), $subject="") {
        require_once "../vendors/smarty/Smarty.class.php";
        $smarty = new Smarty;
        $smarty->assign('template_source', str_replace(array("<!---", "--->", "- -->"), array("{", "}", "}"), str_replace(array("<script", "/script>"), array("{literal}<script", "/script>{/literal}"), str_replace(array("<style", "/style>"), array("{literal}<style", "/style>{/literal}"), preg_replace('/(%7B|\{)(\$|%24)([^%]*)(%7D|\})/i', '{$\3}', $content)))));
        $smarty->assign($data);
        $smarty->assign('baseurl', Router::url("/", true));
        $smarty->assign('browerslink', "show/{\$MAILID}/{\$SUBSCRIBER_ID}");
        $smarty->assign('unsubscribe', "unsubscribe/{\$MAILID}/{\$SUBSCRIBER_ID}");
         $smarty->assign('update_subscriber', "subscribe/{\$FORMID}/1/{\$SUBSCRIBER_ID}");
 
        $smarty->assign('sendtofriend', "show/{\$MAILID}/{\$SUBSCRIBER_ID}?sendtofriend=1");
        $smarty->assign('MAILID', $mail_id);
        $smarty->assign("first_name", '{$first_name}');
        $smarty->assign("subscriber_mail", '{$subscriber_mail}');
        $smarty->assign("custom1", '{$custom1}');
        $smarty->assign("custom2", '{$custom2}');
        $smarty->assign("custom3", '{$custom3}');
        $smarty->assign("custom4", '{$custom4}');
        $smarty->assign("subject", $subject);
        $smarty->assign("last_name", '{$last_name}');
        $smarty->assign('SUBSCRIBER_ID', '{$SUBSCRIBER_ID}');
        $smarty->assign('FORMID', '{$FORMID}');
        $smarty->template_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/templates';
        $smarty->compile_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/templates_c';
        $smarty->cache_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/cache';
        $smarty->config_dir = ROOT . DS . APP_DIR . DS . 'vendors/smarty/configs';
        $smarty->assign('template_source', str_replace(array("<script", "/script>"), array("{literal}<script", "/script>{/literal}"), str_replace(array("<style", "/style>"), array("{literal}<style", "/style>{/literal}"), $smarty->fetch('template.tlp'))));
        return $smarty->fetch('template.tlp');
    }

}

?>
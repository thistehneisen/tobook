<?php

class Template extends AppModel {

    var $name = 'Template';
    var $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            ));

    function beforeSave() {
        if (!empty($this->data["Template"]["fields"])) {
            $template_fields = array();
            $tempfields = split("\n", $this->data["Template"]["fields"]);
            $inarray = "";

            $temparray = array();
            $comments = array();
            foreach ($tempfields as $fieldname) {
                $rawfieldname = explode("//", trim($fieldname));
                $fieldname = trim($rawfieldname[0]);

                if (!empty($fieldname)) {
                    if ($fieldname[0] == "<" && $fieldname[1] != "/") {
                        $inarray = substr($fieldname, 1, strlen($fieldname) - 2);
                        $temparray = array();
                    } else if ($fieldname[0] == "<" && $fieldname[1] == "/") {
                        $template_fields[$inarray] = $temparray;
                        $temparray = array();

                        $inarray = "";
                    } else {
                        if ($inarray == "") {
                            $template_fields[] = $fieldname;
                            if (isset($rawfieldname[1])) {
                                $comments[$fieldname] = trim($rawfieldname[1]);
                            }
                        } else {
                            $temparray[] = $fieldname;
                            if (isset($rawfieldname[1])) {
                                $comments[$inarray . "." . $fieldname] = trim($rawfieldname[1]);
                            }
                        }
                    }
                }
            }

            $this->data["Template"]["fields_array"] = myserialize($template_fields);
            $this->data["Template"]["comment_array"] = myserialize($comments);
        }
        return true;
    }

    function beforeValidate($options = array()) {

        if (isset($this->data["Template"]["content"])) {
            $this->data["Template"]["content"] = preg_replace('/<!DOCTYPE[^>]*>/im', '', str_replace(Router::url("/", true), "{\$baseurl}", $this->data["Template"]["content"]));
            $this->data["Template"]["content"] = preg_replace(' /\<title>(.*?)<\/title\>/s', '<title>{$subject}</title>', $this->data["Template"]["content"]);
            $this->data["Template"]["content"] = str_replace("vmrtbinsefrwxsqjglty", "", $this->data["Template"]["content"]);
        }
        return parent::beforeValidate($options);
    }

}

?>
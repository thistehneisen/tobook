<?php

class Importtask extends AppModel {

    var $name = 'Importtask';

    function afterFind($results) {
        foreach ($results as $key => $val) {
            if (isset($val['Importtask']['connection'])) {
                $results[$key]['Connection'] = array();
                $results[$key]['Connection'] = @myunserialize($results[$key]['Importtask']['connection']);
            }
            if (isset($val['Importtask']['fields'])) {
                $results[$key]['Import'] = array();
                $results[$key]['Import'] = @myunserialize($results[$key]['Importtask']['fields']);
            }
                if (isset($val['Importtask']['categories'])) {
                $results[$key]['Importtask']['Category'] = array();
                $results[$key]['Importtask']['Category'] = @myunserialize($results[$key]['Importtask']['categories']);
            }

        }
        return $results;
    }

    function beforeValidate($options = array()) {

        if (isset($this->data['Connection'])) {

            $this->data['Importtask']["connection"] = @myserialize($this->data['Connection']);
        }
                if (isset($this->data['Import'])) {

            $this->data['Importtask']["fields"] = @myserialize($this->data['Import']);
        }
        if (isset($this->data["Importtask"]['Category']) ) {


            $this->data['Importtask']['categories'] = @myserialize($this->data["Importtask"]['Category']);
        }
        return parent::beforeValidate($options);
    }

}

?>
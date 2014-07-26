<?php

class HomeController extends AppController {

    var $name = 'Home';
    var $uses = array("Subscriber", "Mail");

    function index() {
       
        $this->Subscriber->recursive = 0;
        $this->set("subscribers", $this->Subscriber->find("all", array("order" => "created desc", "limit" => 5)));
        $this->Mail->recursive = 0;
        $this->set("mails", $this->Mail->find("all", array("conditions" => array("campaign_id" => 0), "order" => "modified desc", "limit" => 5)));
    }

}

?>
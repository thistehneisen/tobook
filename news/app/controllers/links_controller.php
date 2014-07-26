<?php

function str_replace2($s, $r, $t) {
    $t = str_replace(str_replace(array("[", "]"), array("%5B", "%5D"), $s), $s, $t);
    return str_replace($s, $r, $t);
}

class LinksController extends AppController {

    var $name = 'Links';
    var $uses = array("Recipient", "Mail", "Link", "Subscriber");

    function beforeFilter() {
        $this->Auth->allow('go_to');
        parent::beforeFilter();
    }

    function go_to($id, $subsci=null) {
        $link = $this->Link->read(null, $id);
        if(empty ($link)){
                   $this->cakeError('error404');
                return;
        }
        $subsci = split("-", $subsci);
        $subsc = $subsci[0];
        if (is_numeric($subsc)) {
            $this->Link->id = $id;
            $this->Link->saveField("clicks", $link["Link"]["clicks"] + 1);
            $this->Recipient->openMail($link["Link"]["mail_id"], $subsc,true);
            $subsc = $this->Subscriber->read(null, $subsc);

            if (isset($subsc["Subscriber"])) {
                if (isset($subsci[1]) && $subsc["Subscriber"]["unsubscribe_code"] == $subsci[1])
                    $link["Link"]["url"] = $this->Mail->addInfos($link["Link"]["url"], $subsc["Subscriber"]);
            }
        }
        $this->redirect($link["Link"]["url"]);
    }

}

?>
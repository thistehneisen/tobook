<?php

class ApiController extends AppController {

    var $name = 'Api';
    var $uses = array();

    function _checkapi($key) {
        if (!file_exists(CONFIGS . 'api.yml')) {
            file_put_contents(CONFIGS . 'api.yml', substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12));
        }
        if (md5(file_get_contents(CONFIGS . 'code.yml') . "-" . file_get_contents(CONFIGS . 'api.yml')) == $key) {
            return true;
        } else {
            return false;
        }
    }

    function beforeFilter() {
        $this->Auth->allow(array('subscribers', 'categories', 'forms'));
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
        header('Content-Type: text/x-json');
        parent::beforeFilter();
    }

    function subscribers($action="", $apikey="") {
        if (!$this->_checkapi($apikey)) {
            echo json_encode(array("status" => "error", "msg" => "Invalid Key"));
        }
        Configure::write('debug', 0);
        $this->autoRender = false;


        if ($action == "add") {
            $input = json_decode($_POST["data"], true);
            if (!isset($input["e-mail"]) || empty($input["e-mail"]) || !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$", $input["e-mail"])) {
                echo json_encode(array("status" => "error", "msg" => "Invalid Mail"));
                return;
            }
            if (!isset($input["Category"]) || empty($input["Category"])) {
                echo json_encode(array("status" => "error", "msg" => "No Category"));
                return;
            }
            $data = array("Subscriber" => array("mail_adresse" => $input["e-mail"],
                    "last_name" => isset($input["last_name"]) ? $input["last_name"] : "-", "first_name" => isset($input["first_name"]) ? $input["first_name"] : "-"));
            $this->loadModel('Category');
            $cats = $this->Category->find('list');
            foreach ($input["Category"] as $cat) {
                if (isset($cats[$cat])) {
                    $data["Category"]["Category"][] = $cat;
                } else {
                    echo json_encode(array("status" => "error", "msg" => "Invalid Category " . $cat));
                    return;
                }
            }

            $data["Subscriber"]["unsubscribe_code"] = substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12);
            for ($index = 1; $index <= 4; $index++) {
                if (isset($input['custom' . $index])) {
                    $data["Subscriber"]['custom' . $index] = $input['custom' . $index];
                }
            }
            $this->loadModel('Subscriber');
            if ($this->Subscriber->save($data)) {
                echo json_encode(array("status" => "ok", "msg" => "Subscriber added"));
            } else {
                echo json_encode(array("status" => "error", "msg" => "Unknown Error"));
            }
        } else if ($action == "exist") {
            $this->loadModel('Subscriber');

            echo $this->Subscriber->find("count", array("conditions" => array("mail_adresse" => $_POST["e-mail"])));
            return;
         } else if ($action == "activate") {
            $this->loadModel('Subscriber');

            $first= $this->Subscriber->find("first", array("conditions" => array("mail_adresse" => $_POST["e-mail"])));
            $this->Subscriber->resubscribe($first['Subscriber']['id']);
            return;
        }  else if ($action == "deactivate") {
            $this->loadModel('Subscriber');

            $first= $this->Subscriber->find("first", array("conditions" => array("mail_adresse" => $_POST["e-mail"])));
            $this->Subscriber->unsubscribe($first['Subscriber']['id']);
            return;
        }else if ($action == "unsubscribe") {
            $this->loadModel('Subscriber');
            $sub = $this->Subscriber->find("first", array("conditions" => array("mail_adresse" => $_POST["e-mail"])));
            if (empty($sub)) {
                echo json_encode(array("status" => "error", "msg" => "Subscriber does not exist"));
            } else if ($sub["Subscriber"]["deleted"] == 1) {
                echo json_encode(array("status" => "error", "msg" => "Subscriber already unsubscribed"));
            } else {
                $this->Subscriber->unsubscribe($sub["Subscriber"]["id"]);
                echo json_encode(array("status" => "ok", "msg" => "Subscriber unsubscribed"));
            }

            return;
        }
    }

    function categories($action="", $apikey="") {
        if (!$this->_checkapi($apikey)) {
            echo json_encode(array("status" => "error", "msg" => "Invalid Key"));
        }
        Configure::write('debug', 0);
        $this->autoRender = false;

        if ($action == "get") {
            $this->loadModel('Category');
            echo json_encode($this->Category->find('list'));
        } elseif ($action == "add") {
            $this->loadModel('Subscriber');
            $this->loadModel('Category');
            $cats = array_keys($this->Category->find('list'));
            $emails = json_decode($_POST['emails'], true);
            $category_ids = json_decode($_POST['category_ids'], true);
            foreach ($emails as $email) {
                $data = $this->Subscriber->find("first", array("order" => "Subscriber.created DESC", "conditions" => array("mail_adresse" => $email)));
                if (!empty($data)) {
                    $data["cats"] = array();
                    foreach ($data["Category"] as $i) {

                        $data["cats"][] = $i["id"];
                    }
                    foreach ($category_ids as $cat_id) {
                        if (!in_array($cat_id, $data["cats"]) && in_array($cat_id, $cats)) {
                            $this->Subscriber->query("INSERT INTO `categories_subscribers` (
`category_id` ,
`subscriber_id`
)
VALUES (
'" . $cat_id . "', '" . $data["Subscriber"]["id"] . "'
)");
                        }
                    }
                }
            }
            echo json_encode(array("status" => "ok", "msg" => ""));
        } elseif ($action == "remove") {
            $this->loadModel('Subscriber');
            $this->loadModel('Category');
            $cats = array_keys($this->Category->find('list'));
            $emails = json_decode($_POST['emails'], true);
            $category_ids = json_decode($_POST['category_ids'], true);
            foreach ($emails as $email) {
                $data = $this->Subscriber->find("first", array("order" => "Subscriber.created DESC", "conditions" => array("mail_adresse" => $email)));
                if (!empty($data)) {
                    foreach ($category_ids as $cat_id) {

                        $this->Subscriber->query("DELETE FROM `newsletter`.`categories_subscribers` WHERE `categories_subscribers`.`category_id` = " . $cat_id . " AND `categories_subscribers`.`subscriber_id` = " . $data["Subscriber"]["id"] . ";");
                    }
                }
            }
            echo json_encode(array("status" => "ok", "msg" => ""));
        }elseif ($action == "infos") {
            $this->loadModel('Subscriber');
            $this->loadModel('Category');

            $email = $_POST['e-mail'];

                 $data = $this->Subscriber->find("first", array("order" => "Subscriber.created DESC", "conditions" => array("mail_adresse" => $email)));
            $cats=array();
            foreach($data['Category'] as $c){
                $cats[]=$c['id'];
            }
            echo json_encode($cats);
        }
    }

    function forms($action="", $apikey="") {
        if (!$this->_checkapi($apikey)) {
            echo json_encode(array("status" => "error", "msg" => "Invalid Key"));
        }
        Configure::write('debug', 0);
        $this->autoRender = false;

        if ($action == "get") {
            $this->loadModel('Form');
            echo json_encode($this->Form->find('list'));
        } else if ($action == "message") {
            $this->loadModel('Form');
            $this->loadModel('Category');

            $info = $this->Form->read(null, $_POST['id']);
            $content = array();
            $content = array();
            if ($info['Form']['confirm'] == 1) {

                if (isset($info['Content']['confirm_page_url']) && $info['Content']['confirm_page_url'] != "") {
                    $content["type"] = "url";
                    $content["url"] = $info['Content']['confirm_page_url'];
                } else {
                    $content["type"] = "text";
                    $content["title"] = $info['Form']["confirm_title"];
                    $content["message"] = $info['Form']["confirm_text"];
                }
            } else {


                if (isset($info['Content']['thanks_page_url']) && $info['Content']['thanks_page_url'] != "") {
                    $content["type"] = "url";
                    $content["url"] = $info['Content']['thanks_page_url'];
                } else {
                    $content["type"] = "text";
                    $content["title"] = $info['Form']["thanks_title"];
                    $content["message"] = $info['Form']["thanks_text"];
                }
            }
            echo json_encode($content);
        } else if ($action == "info") {
            $this->loadModel('Form');
            $this->loadModel('Category');

            $info = $this->Form->read(null, $_POST['id']);
            for ($index = 1; $index <= 4; $index++) {
                if (!empty($info["Content"]["custom" . $index . "_options"]))
                    $info["Content"]["custom" . $index . "_options"] = explode(";", $info["Content"]["custom" . $index . "_options"]);
            }
            if ($info["Content"]["user_can_choose"] == 1) {
                $cats = $this->Category->find("list");
                $cate = array();
                if (isset($info["Form"]["Category"]) && !empty($info["Form"]["Category"])) {
                    foreach ($info["Form"]["Category"] as $value) {
                        if (array_key_exists($value, $cats)) {
                            $cate[$value] = $cats[$value];
                        }
                    }
                }
                $info["Content"]["Categories"] = $cate;
            }
            echo json_encode($info["Content"]);
        } else if ($action == "add") {
            $data = json_decode($_POST["data"], true);
            $formId = $_POST["FormId"];
            echo json_encode($this->requestAction("/forms/view/" . $formId . "/2", array("data" => $data)));
        }
    }

}

?>
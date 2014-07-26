<?php

class FormsController extends AppController {

    var $name = 'Forms';
    var $uses = array("Form", "Category", "Subscriber");
    var $components = array('Captcha');

    function index() {
        $this->Form->recursive = 0;
        $this->set('forms', $this->paginate());
    }

    function securimage($random_number) {
        $this->autoLayout = false; //a blank layout
        //override variables set in the component - look in component for full list
        $this->captcha->image_height = 75;
        $this->captcha->image_width = 179;

        $this->captcha->perturbation = 0.85;
        $this->captcha->image_bg_color = new Securimage_Color("#f6f6f6");
        $this->captcha->multi_text_color = array(new Securimage_Color("#3399ff"),
            new Securimage_Color("#3300cc"),
            new Securimage_Color("#3333cc"),
            new Securimage_Color("#6666ff"),
            new Securimage_Color("#99cccc")
        );
        $this->captcha->text_transparency_percentage = 30; // 100 = completely transparent
        $this->captcha->num_lines = 7;
        $this->captcha->line_color = new Securimage_Color("#eaeaea");
        $this->set('captcha_data', $this->captcha->show()); //dynamically creates an image
    }

    function beforeFilter() {
        $this->Auth->allow('unsubscribe', "view", "thank", "sendtof", "securimage", "done", "viewmin");
        parent::beforeFilter();
    }

    function unsubscribe($id=null) {
        if (!$id) {
            $dats = $this->Form->find("first");
        } else {
            $dats = $this->Form->read(null, $id);
            if (!isset($dats['Form'])) {
                $dats = $this->Form->find("first");
            }
        }

        if (isset($dats['Content']['unsubscribe_page_url']) && $dats['Content']['unsubscribe_page_url'] != "") {
            $this->redirect($dats['Content']['unsubscribe_page_url']);
            return;
        }
        $this->set('title_for_layout', $dats['Form']["unsubscribe_title"]);
        $this->layout = "mes";
        $this->set('form', $dats);
    }

    function thank($id=null) {
        if (!$id) {
            $dats = $this->Form->find("first");
        } else {
            $dats = $this->Form->read(null, $id);
            if (empty($dats)) {
                $dats = $this->Form->find("first");
            }
        }
        if (isset($dats['Content']['thanks_page_url']) && $dats['Content']['thanks_page_url'] != "") {
            $this->redirect($dats['Content']['thanks_page_url']);
        }
        $this->set('title_for_layout', $dats['Form']["thanks_title"]);
        $this->layout = "mes";
        $this->set('form', $dats);
    }

    function sendtof($id = null) {
        $this->set('mid', $id);

        if (!empty($this->data)) {
            if (empty($this->data["Form"]["sender_name"]) || eregi('[^a-z0-9_ ]', $this->data["Form"]["sender_name"])) {
                $this->Form->invalidate("sender_name", __("Invalide name, use characters A-Z", true));
            }
            if (empty($this->data["Form"]["friend_name"]) || eregi('[^a-z0-9_ ]', $this->data["Form"]["friend_name"])) {
                $this->Form->invalidate("friend_name", __("Invalide name, use characters A-Z", true));
            }
            if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$", $this->data["Form"]["sender_mail"])) {

                $this->Form->invalidate("sender_mail", __("Invalide mail addresse", true));
            }

            if ($this->Form->validates()) {
                $this->loadModel("Mail");
                $data = $this->Mail->read(null, $id);

                $mail = 0;

                $mailerror = "";
                require_once "../vendors/Swift/lib/swift_required.php";
                try {
                    try {

                        $mailer = Swift_Mailer::newInstance($this->Form->Configuration->getTransport($data["Configuration"]));


                        //Create a message
                        $message = Swift_Message::newInstance(__("A Friend Sent You A Message", true))
                                ->setFrom(array($this->data["Form"]["sender_mail"] => $this->data["Form"]["sender_name"]))
                                ->setTo(array($this->data["Form"]["friend_mail"] => $this->data["Form"]["friend_name"]));



                        $message->setReplyTo(array($this->data["Form"]["sender_mail"] => $this->data["Form"]["sender_name"]));




                        $message->addPart($this->data["Form"]["message"]);


                        $result = $mailer->send($message);
                        if ($result)
                            $mail = 1;
                        else
                            $mail=2;
                    } catch (Swift_TransportException $e) {
                        $mail = 2;
                    }
                } catch (Exception $e) {

                    $mail = 2;
                }
                if ($mail == 1) {
                    $this->Mail->id = $data["Mail"]["id"];

                    $this->Mail->saveField("sendtof", $data["Mail"]["sendtof"] + 1);
                }
                $this->set("mail", $mail);
            }
        }

        $this->layout = "clean";
    }

    function done($id = null, $type=1, $done) {
        $dats = $this->Form->read(null, $id);
        if ($type == 0) {
            $this->layout = "clean";
        } else {
            $this->layout = "mes";
        }
        if ($done == 2) {

            $this->set('done', 2);
            $this->set('title_for_layout', $dats['Form']["confirm_title"]);
        } else {
            $this->set('done', 1);
            $this->set('title_for_layout', $dats['Form']["thanks_title"]);
        }
        $this->set('form', $dats);
        $this->render("view");
    }

    function viewmin($id = null) {
        $this->captcho = true;
        $this->view($id, 0, null);
        $this->layout = 'ajax';
    }

    var $captcho = false;

    function view($id = null, $type=1, $subscriber_id=null) {
        if ($type == 2) {
            $this->data = array("Form" => $this->params['data']);
            $this->data["Form"]["id"] = $id;
            $this->data["Form"]["type"] = $type;
        }
        if (!empty($this->data)) {
            $id = $this->data["Form"]["id"];
            $type = $this->data["Form"]["type"];
        }
        if (!$id) {
            if ($type == 2) {
                return array("status" => "error", "msg" => "Invalid Form");
            }

            $this->Session->setFlash(__('Invalid form', true));
            $this->cakeError('error404');

            return;
        }
        if ($type == 0) {
            $this->layout = "clean";
        } else {
            $this->layout = "mes";
        }
        $dats = $this->Form->read(null, $id);
        $cats = $this->Category->find("list");
        $cate = array();
        if (isset($dats["Form"]["Category"]) && !empty($dats["Form"]["Category"])) {
            foreach ($dats["Form"]["Category"] as $value) {
                if (array_key_exists($value, $cats)) {
                    $cate[$value] = $cats[$value];
                }
            }
        }
        $this->set('title_for_layout', $dats['Form']["title"]);
        if (!empty($this->data)) {
            for ($index = 1; $index <= 4; $index++) {
                if (isset($dats['Content']['custom' . $index . '_required']) && $dats['Content']['custom' . $index . '_required'] == 1 && (!isset($this->data["Form"]['custom' . $index]) || empty($this->data["Form"]['custom' . $index]))) {
                    $this->Form->invalidate('custom' . $index, $dats['Content']['custom' . $index . '_error']);
                    if ($type == 2) {
                        return array("status" => "error", "msg" => $dats['Content']['custom' . $index . '_error']);
                    }
                }
            }

            if ($dats['Content']['first_name_required'] == 1 && (!isset($this->data["Form"]["first_name"]) || empty($this->data["Form"]["first_name"]))) {
                $this->Form->invalidate("first_name", $dats['Content']['first_name_error']);
                if ($type == 2) {
                    return array("status" => "error", "msg" => $dats['Content']['first_name_error']);
                }
            }

            if ($type != 2) {
                if (isset($dats['Content']["checkbox_show"]) && $dats['Content']["checkbox_show"] != "0" && $this->data["Form"]["checkbox"] == "") {
                    $this->Form->invalidate("checkbox", $dats['Content']['checkbox_error']);
                }
                if (isset($dats['Content']["captcha_show"]) && !$this->captcho && $dats['Content']["captcha_show"] != "0" && !$this->captcha->check($this->data["Form"]["captcha"])) {
                    if (empty($dats['Content']['captcha_error'])) {
                        $dats['Content']['captcha_error'] = 'Please solve this captcha';
                    }
                    $this->Form->invalidate("captcha", $dats['Content']['captcha_error']);
                }
            }
            if ($dats['Content']['last_name_required'] == 1 && (!isset($this->data["Form"]["last_name"]) || empty($this->data["Form"]["last_name"]))) {
                $this->Form->invalidate("last_name", $dats['Content']['last_name_error']);
                if ($type == 2) {
                    return array("status" => "error", "msg" => $dats['Content']['last_name_error']);
                }
            }
            $update = false;
            if (!isset($this->data["Form"]["e-mail"]) || empty($this->data["Form"]["e-mail"]) || !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$", $this->data["Form"]["e-mail"])) {
                $this->Form->invalidate("e-mail", $dats['Content']['e-mail_error']);
                if ($type == 2) {
                    return array("status" => "error", "msg" => $dats['Content']['e-mail_error']);
                }
            } else if ($this->Subscriber->find("count", array("conditions" => array("mail_adresse" => $this->data["Form"]["e-mail"]))) != 0) {
                if (isset($dats['Content']["update_subscriber"]) && $dats['Content']["update_subscriber"] == "1") {
                    $update = true;
                } else if (isset($dats['Content']["update_infos"]) && $dats['Content']["update_infos"] == "1") {
                    $update = true;
                } else {
                    if (empty($dats['Content']['e-mail_exist'])) {
                        $dats['Content']['e-mail_exist'] = 'You have already subscribed this newsletter';
                    }
                    $this->Form->invalidate("e-mail", $dats['Content']['e-mail_exist']);
                    if ($type == 2) {
                        return array("status" => "error", "msg" => $dats['Content']['e-mail_exist']);
                    }
                }
            }

            if ($dats['Content']['user_can_choose'] == 1 && (!isset($this->data["Form"]["Category"]) || empty($this->data["Form"]["Category"]))) {
                $this->Form->invalidate("Category", $dats['Content']['categories_error']);
                if ($type == 2) {
                    return array("status" => "error", "msg" => $dats['Content']['categories_error']);
                }
            }
            if ($this->Form->validates()) {
                $data = array();
                $data = array("Subscriber" => array("form_id" => $id, "mail_adresse" => $this->data["Form"]["e-mail"],
                        "last_name" => isset($this->data["Form"]["last_name"]) ? $this->data["Form"]["last_name"] : "-", "first_name" => isset($this->data["Form"]["first_name"]) ? $this->data["Form"]["first_name"] : "-"));
                if ($dats['Content']['user_can_choose'] == 1) {
                    $data["Category"]["Category"] = $this->data["Form"]["Category"];
                } else {
                    if (isset($dats["Form"]["Category"]) && !empty($dats["Form"]["Category"])) {
                        $data["Category"]["Category"] = $dats["Form"]["Category"];
                    }
                }
                for ($index = 1; $index <= 4; $index++) {
                    if (isset($this->data["Form"]['custom' . $index])) {
                        $data["Subscriber"]['custom' . $index] = $this->data["Form"]['custom' . $index];
                    }
                }
                if (!$update) {

                    $data["Subscriber"]["unsubscribe_code"] = substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12);
                } else {
                    $infos = array();

                    if (isset($dats['Content']["update_infos"]) && $dats['Content']["update_infos"] == "1") {
                        $infos = $data;

                        unset($infos["Category"]);
                    }
                    $data = $this->Subscriber->find("first", array("order" => "Subscriber.created DESC", "conditions" => array("mail_adresse" => $this->data["Form"]["e-mail"])));

                    if (isset($dats['Content']["update_subscriber"]) && $dats['Content']["update_subscriber"] == "1") {
                        $old = $data["Category"];

                        unset($data["Category"]);
                        $data["Category"]["Category"] = array();
                        if ($data["Subscriber"]["deleted"] == 0) {
                            foreach ($old as $i) {
                                if (!in_array($i["id"], $dats["Form"]["Category"])) {
                                    $data["Category"]["Category"] [] = $i["id"];
                                }
                            }
                        } else {
                            $data["Subscriber"]["deleted"] = 0;
                            $update=false;
                        }
                        if ($dats['Content']['user_can_choose'] == 1) {
                            foreach ($this->data["Form"]["Category"] as $i) {
                                $data["Category"]["Category"] [] = $i;
                            }
                        } else {
                            if (isset($dats["Form"]["Category"]) && !empty($dats["Form"]["Category"])) {
                                foreach ($dats["Form"]["Category"] as $i) {
                                    $data["Category"]["Category"] [] = $i;
                                }
                            }
                        }



                        $data["Category"]["Category"] = array_unique($data["Category"]["Category"]);
                    }
                    if(isset($infos["Subscriber"])){
                        $data["Subscriber"] = array_merge($data["Subscriber"], $infos["Subscriber"]);
                    }
                }
                if ($this->Subscriber->save($data)) {
					for($i=1; $i<=4;$i++){
						if(!isset($this->data["Form"]["custom".$i])){
							$this->data["Form"]["custom" . $i]="";
						}
					}
                    if ($dats['Form']['notify'] == 1 && !$update) {
                        $confirm_mail = str_replace(array('{$email}', '{$first_name}', '{$last_name}', '{$custom1}', '{$custom2}', '{$custom3}', '{$custom4}'), array($this->data["Form"]["e-mail"], $this->data["Form"]["first_name"], $this->data["Form"]["last_name"], $this->data["Form"]["custom1"], $this->data["Form"]["custom2"], $this->data["Form"]["custom3"], $this->data["Form"]["custom4"]), $dats["notifym"]["content"]);
                        $confirm_mail = str_replace(Router::url("/", true) . Router::url("/", true), Router::url("/", true), $confirm_mail);
                        $r = $this->__sendMail($dats["Form"]["notify_addresse"], $dats["notifym"]["title"], $confirm_mail, $dats["Configuration"]);
                    }
                    if ($dats['Form']['confirm'] == 1 && !$update) {

                        $confirm_mail = str_replace(array('{$email}', '{$first_name}', '{$last_name}', '{$custom1}', '{$custom2}', '{$custom3}', '{$custom4}'), array($this->data["Form"]["e-mail"], $this->data["Form"]["first_name"], $this->data["Form"]["last_name"], $this->data["Form"]["custom1"], $this->data["Form"]["custom2"], $this->data["Form"]["custom3"], $this->data["Form"]["custom4"]), $dats["confirmm"]["content"]);
                        $confirm_mail = str_replace('{$confirm}', Router::url(array("controller" => "subscribers", "action" => "confirm", $this->Subscriber->id . "-" . $data["Subscriber"]["unsubscribe_code"]), true), $confirm_mail);
                        $confirm_mail = str_replace(Router::url("/", true) . Router::url("/", true), Router::url("/", true), $confirm_mail);
                        $r = $this->__sendMail($this->data["Form"]["e-mail"], $dats["confirmm"]["title"], $confirm_mail, $dats["Configuration"]);
                        if (!$r) {
                            $dats['Form']['confirm'] = 0;
                        } else {
                            $this->Subscriber->unsubscribe($this->Subscriber->id);
                        }
                    }
                    if ($this->captcho) {
                        $content=array();
                        if ($dats['Form']['confirm'] == 1) {

                            if (isset($dats['Content']['confirm_page_url']) && $dats['Content']['confirm_page_url'] != "") {
                                $content["type"] = "url";
                                $content["url"] = $dats['Content']['confirm_page_url'];
                            } else {
                                $content["type"] = "text";
                                $content["title"] = $dats['Form']["confirm_title"];
                                $content["message"] = $dats['Form']["confirm_text"];
                            }
                        } else {


                            if (isset($dats['Content']['thanks_page_url']) && $dats['Content']['thanks_page_url'] != "") {
                                $content["type"] = "url";
                                $content["url"] = $dats['Content']['thanks_page_url'];
                            } else {
                                $content["type"] = "text";
                                $content["title"] = $dats['Form']["thanks_title"];
                                $content["message"] = $dats['Form']["thanks_text"];
                            }
                        }
                        echo "--";
                        echo json_encode($content);
                        die();
                    } else if ($dats['Form']['confirm'] == 1 && !$update) {

                        if ($type == 2) {
                            return array("status" => "ok", "msg" => "Confirm subscription");
                        } else {

                            if (isset($dats['Content']['confirm_page_url']) && $dats['Content']['confirm_page_url'] != "") {
                                $this->redirect($dats['Content']['confirm_page_url']);
                            } else {
                                $this->redirect("/forms/done/" . $id . "/" . $type . "/2");
                            }
                        }
                    } else {

                        if ($type == 2) {
                            return array("status" => "ok", "msg" => "Subscriber added");
                        } else {

                            if (isset($dats['Content']['thanks_page_url']) && $dats['Content']['thanks_page_url'] != "") {
                                $this->redirect($dats['Content']['thanks_page_url']);
                            } else {
                                $this->redirect("/forms/done/" . $id . "/" . $type . "/1");
                            }
                        }
                    }
                } else {
                    $this->Session->setFlash(__('Unknown error please contact webmaster.', true));
                }
            }
            if ($type == 2) {
                return array("status" => "error", "msg" => "Unknown Error");
            }
        }
        if ($subscriber_id != null) {
            $subscriber_id = split("-", $subscriber_id);
            if (count($subscriber_id) > 1) {
                $data = $this->Subscriber->find("first", array("order" => "Subscriber.created DESC", "conditions" => array("id" => $subscriber_id[0], "unsubscribe_code" => $subscriber_id[1])));
                $this->data["Form"] = $data["Subscriber"];
                $this->data["Form"]["e-mail"] = $data["Subscriber"]["mail_adresse"];
                foreach ($data["Category"] as $i) {

                    $this->data["Form"]["Category"] [] = $i["id"];
                }
            }
        }

        $this->data["Form"]["id"] = $id;
        $this->data["Form"]["type"] = $type;
        $this->set('form', $dats);
        $this->set('cats', $cate);
    }

    function __sendMail($addresse, $title, $content, $conf) {

        require_once "../vendors/Swift/lib/swift_required.php";
        try {
            try {

                $mailer = Swift_Mailer::newInstance($this->Form->Configuration->getTransport($conf));


                //Create a message
                $message = Swift_Message::newInstance($title)
                        ->setFrom(prep_add($conf["from"]))
                        ->setTo(array($addresse));
                if (!empty($conf["reply_to"]))
                    $message->setReplyTo(prep_add($conf["reply_to"]));
                $message->setEncoder(Swift_Encoding::get8BitEncoding());
                $headers = $message->getHeaders();

                $headers->addTextHeader('X-NLReft', 'test');

                $message->addPart($content, "text/html");


                //

                $result = $mailer->send($message);
                if ($result)
                    return true;
            } catch (Swift_TransportException $e) {
                
            }
        } catch (Exception $e) {
            
        }
        return false;
    }

    function add() {
        if (!empty($this->data)) {
            $this->Form->create();
            $this->data["Form"]["title"] = "Subscribe";
            $this->data["Form"]["unsubscribe_title"] = "Newsletter unsubscribed";
            $this->data["Form"]["thanks_title"] = "Thank you for subscribing!";
            $this->data["Form"]["confirm_title"] = "Please click on the link in the confirmation mail";

            $this->data["Content"] = myunserialize('a:12:{s:12:"e-mail_label";s:6:"E-Mail";s:12:"e-mail_error";s:33:"Please enter a valid mail adresse";s:16:"first_name_label";s:10:"First Name";s:16:"first_name_error";s:28:"Please enter your first name";s:19:"first_name_required";s:1:"1";s:15:"last_name_label";s:9:"Last Name";s:15:"last_name_error";s:27:"Please enter your last name";s:18:"last_name_required";s:1:"1";s:16:"categories_label";s:25:"Subscribe this categories";s:16:"categories_error";s:35:"Please chose at least one categorie";s:15:"user_can_choose";s:1:"1";s:13:"submit_button";s:14:"Subscribe new!";}');

            if ($this->Form->save($this->data)) {
                $this->Form->query('UPDATE `forms` SET  `configuration_id` = 1, `confirm` = 0, `notify` = 0, `notify_addresse` = \'yourmail@gmail.com\', `notify_mail` = \'a:2:{s:5:\"title\";s:14:\"New Subscriber\";s:7:\"content\";s:297:\"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html>\r\n<head>\r\n<title>Untitled document</title>\r\n</head>\r\n<body>\r\n<p>First Name: {$first_name}</p>\r\n<p>Last Name: {$last_name}</p>\r\n<p>E-Mail: {$email}</p>\r\n</body>\r\n</html>\";}\', `confirm_mail` = \'a:2:{s:5:\"title\";s:25:\"Confirm your subscription\";s:7:\"content\";s:462:\"<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html>\r\n<head>\r\n<title>Untitled document</title>\r\n</head>\r\n<body>\r\n<p><a href=\"{$confirm}\"><span style=\"font-size: large;\"><strong>Confirm Subscription</strong></span></a></p>\r\n<p>If you have received this email by mistake simply delete it.<br /> You won\\\'t be subscribed if you dont click the confirmation link above.</p>\r\n</body>\r\n</html>\";}\' WHERE `id`=' . $this->Form->id . ';');
                $this->redirect(array('action' => 'edit', $this->Form->id));
            } else {
                $this->Session->setFlash(__('The form could not be saved. Please, try again.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid form', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ((!isset($this->data["Form"]["Category"]) || empty($this->data["Form"]["Category"]))) {
                $this->Form->invalidate("Category", __("Please select at least on category", true));
                $this->Session->setFlash(__('The form could not be saved. Please, try again.', true));
            } else if ($this->Form->save($this->data)) {
                $this->Session->setFlash(__('The form has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The form could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Form->read(null, $id);
        }
        $this->set('cats', $this->Category->find("list"));
    }

    function editm($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid form', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Form->save($this->data)) {
                $this->Session->setFlash(__('The form has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The form could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Form->read(null, $id);
        }
        $configurations = $this->Form->Configuration->find('list');

        $this->set(compact('configurations'));
        $this->set('cats', $this->Category->find("list"));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for form', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Form->find("count") == 1) {
            $this->Session->setFlash(__('There must be at least one subscription form', true));
            $this->redirect(array('action' => 'index'));
        } elseif ($this->Form->delete($id)) {
            $this->Session->setFlash(__('Form deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Form was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>

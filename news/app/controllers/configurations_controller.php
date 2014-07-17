<?php

class ConfigurationsController extends AppController {

    var $name = 'Configurations';
    var $deltypes;

    function beforeFilter() {
        if ($this->Session->read('Auth.User.level') != "0") {
            if (in_array($this->action, array("add", "edit", "delete","unblock"))) {
                $this->Session->setFlash(__('Access Denied', true));
                $this->redirect($this->referer());
            }
        }
        $this->Auth->allow('checkAll');
        $this->deltypes = array(1 => __("SMTP",true), 2 => __("PHP mail()",true), 3 => __("Amazon Simple Email Service (Amazon SES)",true), 4 =>  __("Sendmail",true));
        parent::beforeFilter();
    }

    function index() {
        $this->Configuration->recursive = 0;
        $this->set('configurations', $this->paginate());
    }

    function checkAll($in=0) {
        @set_time_limit(60 * 60);
        $this->Configuration->recursive = 0;
        $all = $this->Configuration->find("all", array("fields" => "Configuration.id", "conditions" => array("Configuration.inbox" => 1, "Configuration.inbox_free <" => date('Y-m-d H:i:s'))));
        if (count($all) > 0) {
            foreach ($all as $next) {
                $this->check($next["Configuration"]["id"], true);
            }
            $out = "DONE";
        } else {
            $out = "NO";
        }
        if ($in == 1) {

            $this->Session->setFlash(__('All mails servers checked', true));
            $this->redirect($this->referer());
        } else {
            echo $out;
            exit();
        }
    }

    function check($id = null, $in=false) {
        @set_time_limit(60 * 60);
        if (!function_exists("imap_open")) {

            if (!$in) {
                $this->Session->setFlash(__("Function imap_open does not exist", true));
                $this->redirect(array('action' => 'index'));
            } else {
                return;
            }
            return;
        }
        error_reporting(0);
        if (!$id) {
            if (!$in) {
                $this->Session->setFlash(__('Invalid configuration', true));
                $this->redirect(array('action' => 'index'));
            } else {
                return;
            }
        }
        $this->Configuration->recursive = -1;
        $data = $this->Configuration->read(null, $id);
        $tdiv = strtotime($data['Configuration']["inbox_free"]) - mktime();
        if ($tdiv > 0) {

            $this->Session->setFlash(__('Please wait for', true) . " " . $tdiv . " " . __('seconds', true));
            if (!$in) {
                $this->redirect(array('action' => 'index'));
            } else {
                return;
            }
        }
        if ($data['Configuration']["inbox"] != 1) {
            if (!$in) {
                $this->Session->setFlash(__('No inbox', true));

                $this->redirect(array('action' => 'index'));
            } else {
                return;
            }
        }
        $f = 0;

        if ($data["Configuration"]["inbox"] == 1) {
            $path = "{" . $data["Configuration"]["inbox_host"] . (!empty($data["Configuration"]["inbox_port"]) ? ":" . $data["Configuration"]["inbox_port"] : "");
            if ($data["Configuration"]["inbox_flags"] != "/") {
                $path.=$data["Configuration"]["inbox_flags"] . "}";
            } else {
                $path.= "}";
            }
            $path.=$data["Configuration"]["mailbox"];
            $mbox = imap_open($path, $data["Configuration"]["inbox_username"], $data["Configuration"]["inbox_password"]);

            $count = 0;
            if ($mbox == false) {
                $this->Session->setFlash(__("Error: cannot open mailbox", true));
                if (!$in) {
                    $this->redirect(array('action' => 'index'));
                } else {
                    return;
                }
            } else {


                $headers = imap_headers($mbox);
                foreach ($headers as $k => $mail) {
                    $flags = substr($mail, 0, 4);
                    $isNew = (strpos($flags, "N") !== false);
                    $isUnread = (strpos($flags, "U") !== false);

                    //    Count new + unread messages
                    if ($isNew || $isUnread) {
                        $count++;

                        require_once("../vendors/bounce_driver.class.php");
                        $bouncehandler = new Bouncehandler();
                        $maild = imap_fetchheader($mbox, $k + 1) . "\n" . imap_body($mbox, $k + 1, FT_PEEK);
                        $multiArray = $bouncehandler->get_the_facts($maild);
                        // pr($maild);
                        if (!empty($multiArray[0]["recipient"])) {
                            preg_match_all("/NLReft:[ ]*id([0-9]*)/i", $maild, $cont);
                            if (isset($cont[1][0])) {
                                $this->loadModel("Recipient");

                                $this->Recipient->id = $cont[1][0];
                                $this->Recipient->saveField("failed", 3);
                                $this->Recipient->saveField("sent", 0);
                                $this->Recipient->saveField("read", 0);
                                $f++;
                                imap_setflag_full($mbox, $k + 1, "\\Seen");
                                imap_delete($mbox, $k + 1);
                            }
                        }
                    }
                }
            }

            imap_expunge($mbox);

            imap_close($mbox);
        }

        $this->Configuration->id = $data["Configuration"]["id"];
        $this->Configuration->saveField("inbox_free", date('Y-m-d H:i:s', mktime(date("H"), date("i") + $data["Configuration"]["inbox_wait"], date("s"), date("m"), date("d"), date("Y"))));

        $this->Session->setFlash( str_replace("%s", $f,__("Found %s bounce mails",true)));
        if (!$in) {
            $this->redirect(array('action' => 'index'));
        } else {
            return;
        }
    }

    function verify($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid configuration', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Configuration->recursive = -1;
        $data = $this->Configuration->read(null, $id);
        if ($data["Configuration"]["delivery"] == 3) {
            $mail = 2;
            require_once "../vendors/ses.php";
            $con = new SimpleEmailService($data["Configuration"]["aws_access_key"], $data["Configuration"]["aws_secret_key"]);
            $con->verifyEmailAddress($data["Configuration"]["from"]);
        }
        $this->Session->setFlash("Verification Mail sent");
        $this->redirect(array('action' => 'index'));
    }

    function test($id = null) {
        error_reporting(0);
        define('SWIFT_AWS_DEBUG', true);
        if (!$id) {
            $this->Session->setFlash(__('Invalid configuration', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Configuration->recursive = -1;
        $data = $this->Configuration->read(null, $id);
        // pr($data);
        $inboxerror = "";
        $inbox = 0;
        if ($data["Configuration"]["inbox"] == 1) {
            if (function_exists("imap_open")) {
                $path = "{" . $data["Configuration"]["inbox_host"] . (!empty($data["Configuration"]["inbox_port"]) ? ":" . $data["Configuration"]["inbox_port"] : "");
                if ($data["Configuration"]["inbox_flags"] != "/") {
                    $path.=$data["Configuration"]["inbox_flags"] . "}";
                } else {
                    $path.= "}";
                }
                $path.=$data["Configuration"]["mailbox"];
                $mbox = imap_open($path, $data["Configuration"]["inbox_username"], $data["Configuration"]["inbox_password"]);


                $imap_obj = imap_check($mbox);
                if ($imap_obj == null) {
                    $inbox = 2;
                    $inboxerror = imap_last_error();
                } else {
                    $inbox = 1;
                }

                imap_close($mbox);
            } else {
                $inbox = 2;
                $inboxerror = __("Function imap_open does not exist",true);
            }
        }
        $mail = 0;
        if (!function_exists("curl_init") && $data["Configuration"]["delivery"] == 3) {
            $mailerror = __("Curl plugin missing",true);
            $mail = 2;
        } 
        if ($mail == 0) {
            $mailerror = "";
            require_once "../vendors/Swift/lib/swift_required.php";
            try {
                try {
                    $mailer = Swift_Mailer::newInstance($this->Configuration->getTransport($data["Configuration"]));


                    //Create a message
                    $message = Swift_Message::newInstance("Test")
                            ->setFrom(prep_add($data["Configuration"]["from"]))
                            ->setTo(prep_add($data["Configuration"]["from"]));


                    if (!empty($data["Configuration"]["reply_to"]))
                        $message->setReplyTo(prep_add($data["Configuration"]["reply_to"]));




                    $message->addPart("Test");


                    $result = $mailer->send($message);
                    if ($result)
                        $mail = 1;
                    else
                        $mail=2;
                } catch (Swift_TransportException $e) {
                    $mail = 2;
                    $mailerror = $e->getMessage();
                }
            } catch (Exception $e) {
                $mailerror = $e->getMessage();
                $mail = 2;
            }
        }
        $this->Session->setFlash(__('Inbox:', true) . " " . ($inbox == 0 ?  __("No Tested",true) : ( $inbox == 1 ? __("Working",true) : __("Failed",true) . "<br/>" . $inboxerror)) .
                "<br />" . $this->deltypes[$data["Configuration"]["delivery"]] . ": " . ($mail == 0 ? __("No Tested",true) : ( $mail == 1 ? __("Working",true) : __("Failed",true) . "<br />" . $mailerror)));
        $this->redirect(array('action' => 'index'));
    }

    function add() {
        if (!empty($this->data)) {

            if (empty($this->data["Configuration"]["mails_per_time"]) || is_numeric($this->data["Configuration"]["mails_per_time"]) !== TRUE ||
                    empty($this->data["Configuration"]["time"]) || is_numeric($this->data["Configuration"]["time"]) !== TRUE) {
                $this->set("limiter", 1);
                $this->Session->setFlash(__('The configuration could not be saved. Please, try again.', true));
            } else {
                $this->Configuration->create();
                if ($this->Configuration->save($this->data)) {
                    $this->Session->setFlash(__('The configuration has been saved', true));
                    $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash(__('The configuration could not be saved. Please, try again.', true));
                }
            }
        }
        $this->set("deliveries", $this->deltypes);
    }

    function unblock($id=0) {
        $this->Configuration->id = $id;
        $this->Configuration->saveField("free", date('Y-m-d H:i:s', mktime(date("H"), date("i") - 2, date("s"), date("m"), date("d"), date("Y"))));
        $this->Configuration->saveField("inbox_free", date('Y-m-d H:i:s', mktime(date("H"), date("i") - 2, date("s"), date("m"), date("d"), date("Y"))));

        $this->redirect(array('action' => 'index'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid configuration', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {

            if (empty($this->data["Configuration"]["mails_per_time"]) || is_numeric($this->data["Configuration"]["mails_per_time"]) !== TRUE ||
                    empty($this->data["Configuration"]["time"]) || is_numeric($this->data["Configuration"]["time"]) !== TRUE) {
                $this->set("limiter", 1);
                $this->Session->setFlash(__('The configuration could not be saved. Please, try again.', true));
            } else if ($this->Configuration->save($this->data)) {
                $this->Session->setFlash(__('The configuration has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The configuration could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Configuration->read(null, $id);
        }
        $this->set("deliveries", $this->deltypes);
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for configuration', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Configuration->delete($id)) {
            $this->Session->setFlash(__('Configuration deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Configuration was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>

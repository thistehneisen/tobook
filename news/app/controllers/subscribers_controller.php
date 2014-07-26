<?php

class Format
{

    static public function arr_to_csv_line($arr)
    {
        $line = array();
        foreach ($arr as $v) {
            $line[] = is_array($v) ? self::arr_to_csv_line($v) : '"' . str_replace('"', '""', $v) . '"';
        }
        return implode(",", $line);
    }

    static public function arr_to_csv($arr)
    {
        $lines = array();
        foreach ($arr as $v) {
            $lines[] = self::arr_to_csv_line($v);
        }
        return implode("\n", $lines);
    }

}

class SubscribersController extends AppController
{


    var $name = 'Subscribers';
    var $uses = array("Subscriber", "Mail", "Form", "CategoriesSubscriber");

    function beforeFilter()
    {
        $this->Auth->allow('unsubscribe', "confirm", "cronclean", "croninactive");
        parent::beforeFilter();
    }

    function cleanup()
    {
        $this->Subscriber->query("DELETE FROM `categories_subscribers` WHERE (select count(*) from subscribers where subscribers.id=categories_subscribers.subscriber_id)=0 or (select count(*) from categories where categories.id=categories_subscribers.category_id)=0");
    }

    function runjob()
    {
        $this->loadModel("Subscriber");
        $this->Subscriber->query(" UPDATE `subscribers` SET `deleted` = '1' WHERE  `subscribers`.`deleted`=0 and (SELECT `failed` FROM `recipients` as d where `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 2,1)>2 and (SELECT `failed` FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 1,1)>2 and (SELECT `failed` FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 0,1)>2");

        die(__("Done", true));

    }

    function cronclean()
    {
        /*
         ** */

        $this->loadModel("Recipient");
        $this->loadModel("Subscriber");
        $this->loadModel("Mail");
        $this->Subscriber->query(" UPDATE `subscribers` SET `deleted` = '1' WHERE  `subscribers`.`deleted`=0 and (SELECT `failed` FROM `recipients` as d where `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 2,1)>2 and (SELECT `failed` FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 1,1)>2 and (SELECT `failed` FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 0,1)>2");
            die(__("Done", true));

    }

    function removeinactive()
    {

        $this->loadModel("Recipient");
        $this->loadModel("Subscriber");
        $this->loadModel("Mail");
        $this->Subscriber->query(" UPDATE `subscribers` SET `deleted` = '1' WHERE  `subscribers`.`deleted`=0 and (SELECT (`read`=0 and `sent`=1) FROM `recipients` as d where `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 2,1)=1 and (SELECT (`read`=0 and `sent`=1) FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 1,1)=1 and (SELECT (`read`=0 and `sent`=1) FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 0,1)=1");
        die(__("Done", true));

         die("Done");
    }

    function croninactive()
    {
        $this->loadModel("Recipient");
        $this->loadModel("Subscriber");
        $this->loadModel("Mail");
        $this->Subscriber->query(" UPDATE `subscribers` SET `deleted` = '1' WHERE  `subscribers`.`deleted`=0 and (SELECT (`read`=0 and `sent`=1) FROM `recipients` as d where `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 2,1)=1 and (SELECT (`read`=0 and `sent`=1) FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 1,1)=1 and (SELECT (`read`=0 and `sent`=1) FROM `recipients` as d where  `subscriber_id`=`subscribers`.`id` order by `send_date` desc  limit 0,1)=1");
        die(__("Done", true));
    }

    function search()
    {
        // the page we will redirect to
        $url['action'] = 'index';
        $url[] = "search";
        // build a URL will all the search elements in it
        // the resulting URL will be
        // example.com/cake/posts/index/Search.keywords:mykeyword/Search.tag_id:3
        foreach ($this->data as $k => $v) {
            foreach ($v as $kk => $vv) {
                $url[$k . '.' . $kk] = $vv;
            }
        }

        // redirect the user to the url
        $this->redirect($url, null, true);
    }

    function batchAction()
    {

        if ("" == trim($this->data["Subscriber"]["data"])) {
            $this->Session->setFlash(__('Please select a least one row', true));
        } else if ("toall" == trim($this->data["Subscriber"]["data"])) {
            $query = "SELECT * From (SELECT DISTINCT `Subscriber`.`id` FROM `subscribers` AS `Subscriber` LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON (`CategoriesSubscriber`.`subscriber_id` = `Subscriber`.`id`) WHERE ";

            $jsonq = json_decode($this->data["Search"] ["query"], true);
            // $jsonq['Subscriber'][] = "1=1";
            //  $jsonq['CategoriesSubscriber'][] = "1=1";
            $conditions = array() + $jsonq['Subscriber'] + $jsonq['CategoriesSubscriber'];
            if (empty($conditions)) {
                $conditions[] = "1=1";
            }

            $query = $query . implode(" and ", $conditions) . ") AS q";
            if ($this->data["Subscriber"]["action"] == "delete") {

                $query = "DELETE `Subscriber` FROM `subscribers` AS `Subscriber` LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON (`CategoriesSubscriber`.`subscriber_id` = `Subscriber`.`id`) WHERE " . implode(" and ", $conditions);

                $this->Subscriber->query($query);
            } else if ($this->data["Subscriber"]["action"] == "unsub") {
                $query = "UPDATE  `subscribers` AS `Subscriber` LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON (`CategoriesSubscriber`.`subscriber_id` = `Subscriber`.`id`) SET `Subscriber`.`deleted` = '1' WHERE " . implode(" and ", $conditions);
                $this->Subscriber->query($query);
            } else if ($this->data["Subscriber"]["action"] == "sub") {
                $query = "UPDATE  `subscribers` AS `Subscriber` LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON (`CategoriesSubscriber`.`subscriber_id` = `Subscriber`.`id`) SET `Subscriber`.`deleted` = '0' WHERE " . implode(" and ", $conditions);
                $this->Subscriber->query($query);
            } else if ($this->data["Subscriber"]["action"] == "rem") {


                $a = "DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`category_id` =" . $this->data["Subscriber"]["cat"] . " AND `categories_subscribers`.`subscriber_id` IN (" . $query . ");";
                $this->Subscriber->query($a);
            } else if ($this->data["Subscriber"]["action"] == "over") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    $b = "INSERT IGNORE INTO `categories_subscribers` (`subscriber_id`,`category_id`)(" . str_replace("SELECT DISTINCT `Subscriber`.`id`", "SELECT DISTINCT `Subscriber`.`id`, '" . $this->data["Subscriber"]["category"] . "' as s", $query) . ")";
                    $this->Subscriber->query($b);
                    $a = "DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`category_id` !=" . $this->data["Subscriber"]["category"] . " and  `categories_subscribers`.`subscriber_id` IN (" . $query . ");";
                    $this->Subscriber->query($a);
                }
            } else if ($this->data["Subscriber"]["action"] == "add") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    $b = "INSERT IGNORE INTO `categories_subscribers` (`subscriber_id`,`category_id`)(" . str_replace("SELECT DISTINCT `Subscriber`.`id`", "SELECT DISTINCT `Subscriber`.`id`, '" . $this->data["Subscriber"]["category"] . "' as s", $query) . ")";
                    $this->Subscriber->query($b);
                }
            } else if ($this->data["Subscriber"]["action"] == "move") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    $b = "INSERT IGNORE INTO `categories_subscribers` (`subscriber_id`,`category_id`)(" . str_replace("SELECT DISTINCT `Subscriber`.`id`", "SELECT DISTINCT `Subscriber`.`id`, '" . $this->data["Subscriber"]["category"] . "' as s", $query) . ")";
                    $this->Subscriber->query($b);
                    $a = "DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`category_id` =" . $this->data["Subscriber"]["cat"] . " AND `categories_subscribers`.`category_id` !=" . $this->data["Subscriber"]["category"] . " AND `categories_subscribers`.`subscriber_id` IN (" . $query . ");";
                    $this->Subscriber->query($a);
                }
            } else if ($this->data["Subscriber"]["action"] == "remf") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    $a = "DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`category_id` =" . $this->data["Subscriber"]["category"] . " AND `categories_subscribers`.`subscriber_id` IN (" . $query . ");";


                    $this->Subscriber->query($a);
                }
            } else {
                $this->Session->setFlash(__('Please select an action', true));
            }
        } else {


            $ts = split(";", substr($this->data["Subscriber"]["data"], 0, -1));
            if ($this->data["Subscriber"]["action"] == "delete") {
                foreach ($ts as $ks) {
                    $this->Subscriber->delete($ks);
                }
            } else if ($this->data["Subscriber"]["action"] == "unsub") {
                foreach ($ts as $ks) {
                    $this->Subscriber->query("UPDATE `subscribers` SET `deleted` = '1' WHERE `subscribers`.`id` ='" . $ks . "';");
                }
            } else if ($this->data["Subscriber"]["action"] == "sub") {
                foreach ($ts as $ks) {
                    $this->Subscriber->query("UPDATE `subscribers` SET `deleted` = '0' WHERE `subscribers`.`id` ='" . $ks . "';");
                }
            } else if ($this->data["Subscriber"]["action"] == "rem") {
                foreach ($ts as $ks) {
                    $this->Subscriber->query("DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`subscriber_id` = " . $ks . " and `categories_subscribers`.`category_id` = " . $this->data["Subscriber"]["cat"] . ";");
                }
            } else if ($this->data["Subscriber"]["action"] == "remf") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    foreach ($ts as $ks) {
                        $this->Subscriber->query("DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`subscriber_id` = " . $this->data["Subscriber"]["category"] . " and `categories_subscribers`.`category_id` = " . $this->data["Subscriber"]["cat"] . ";");
                    }
                }
            } else if ($this->data["Subscriber"]["action"] == "over") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    foreach ($ts as $ks) {
                        $this->Subscriber->query("DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`subscriber_id` = " . $ks . ";");
                        $this->Subscriber->query("INSERT INTO `categories_subscribers` (`category_id`, `subscriber_id`) VALUES ('" . $this->data["Subscriber"]["category"] . "', '" . $ks . "');");
                    }
                }
            } else if ($this->data["Subscriber"]["action"] == "add") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    foreach ($ts as $ks) {
                        $this->Subscriber->query("INSERT INTO `categories_subscribers` (`category_id`, `subscriber_id`) VALUES ('" . $this->data["Subscriber"]["category"] . "', '" . $ks . "');");
                    }
                }
            } else if ($this->data["Subscriber"]["action"] == "remf") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    foreach ($ts as $ks) {

                        $this->Subscriber->query("DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`subscriber_id` = " . $ks . " and `categories_subscribers`.`category_id` = " . $this->data["Subscriber"]["category"] . ";");
                    }
                }
            } else if ($this->data["Subscriber"]["action"] == "move") {
                if (empty($this->data["Subscriber"]["category"])) {
                    $this->Session->setFlash(__('Please select a category', true));
                } else {
                    foreach ($ts as $ks) {

                        $this->Subscriber->query("DELETE FROM `categories_subscribers` WHERE `categories_subscribers`.`subscriber_id` = " . $ks . " and `categories_subscribers`.`category_id` = " . $this->data["Subscriber"]["cat"] . ";");
                        $this->Subscriber->query("INSERT INTO `categories_subscribers` (`category_id`, `subscriber_id`) VALUES ('" . $this->data["Subscriber"]["category"] . "', '" . $ks . "');");
                    }
                }
            } else {
                $this->Session->setFlash(__('Please select an action', true));
            }
        }
        $this->cleanup();
        $this->redirect($this->referer(), null, true);
    }

    function export()
    {
        $categories = $this->Subscriber->Category->find('list');

        $columns = array("first_name" => __("First Name", true), "last_name" => __("Last Name", true), "mail_adresse" => __("Mail Address", true), "notes" => __("Notes", true));
        if (Configure::read('Settings.custom1_show') == "1") {
            $columns["custom1"] = Configure::read('Settings.custom1_label');
        }
        if (Configure::read('Settings.custom2_show') == "1") {
            $columns["custom2"] = Configure::read('Settings.custom2_label');
        }
        if (Configure::read('Settings.custom3_show') == "1") {
            $columns["custom3"] = Configure::read('Settings.custom3_label');
        }
        if (Configure::read('Settings.custom4_show') == "1") {
            $columns["custom4"] = Configure::read('Settings.custom4_label');
        }
        $this->set(compact('categories', 'columns'));

        if (!empty($this->data)) {
            if (empty($this->data["Subscriber"]["column"])) {
                $this->Session->setFlash(__('Please select a least one column', true));
                return;
            }
            if (empty($this->data["Category"]["Category"])) {
                $this->Session->setFlash(__('Please select a least one category', true));
                return;
            }

            $cat = array();
            //pr($cats);
            if (isset($this->data["Category"]["Category"]) && !empty($this->data["Category"]["Category"])) {
                foreach ($this->data["Category"]["Category"] as $k) {
                    $cat[] = $k;
                }
            }


            $rec = $this->CategoriesSubscriber->find("list", array("fields" => array("subscriber_id"), "conditions" => array("category_id" => $cat)));
            $this->Subscriber->recursive = -1;
            $recip = array();
            $header = array();
            foreach ($this->data["Subscriber"]["column"] as $value) {
                $header[] = $columns[$value];
            }
            $recip[] = $header;
            $this->data["Subscriber"]["column"][] = "deleted";
            foreach ($rec as $v) {

                $d = $this->Subscriber->read($this->data["Subscriber"]["column"], $v);
                if ($d["Subscriber"]["deleted"] == 0) {
                    unset($d["Subscriber"]["deleted"]);
                    $recip[] = $d["Subscriber"];
                }
            }
            if (count($recip) == 1) {
                $this->Session->setFlash(__('No subscribers in these categories', true));
                return;
            }
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"export.csv\"");
            echo Format::arr_to_csv($recip);
            die();
        }
    }

    function index($cat = "")
    {
        $this->Subscriber->recursive = 0;

        $this->Subscriber->bindModel(array('hasOne' => array('CategoriesSubscriber')), false);

        if ($cat == "search") {
            $this->paginate['fields'][] = "DISTINCT Subscriber.id";
            $this->paginate['fields'][] = "Subscriber.first_name";
            $this->paginate['fields'][] = "Subscriber.last_name";
            $this->paginate['fields'][] = "Subscriber.mail_adresse";
            $this->paginate['fields'][] = "Subscriber.created";
            $this->paginate['fields'][] = "Subscriber.deleted";
            $q = array("Subscriber" => array(), "CategoriesSubscriber" => array());
            if (isset($this->passedArgs['Search.first_name'])) {
                $q["Subscriber"][] = "Subscriber.first_name LIKE '" . addslashes(str_replace('*', '%', $this->passedArgs['Search.first_name'])) . "'";
                $this->paginate['conditions'][]['Subscriber.first_name LIKE'] = str_replace('*', '%', $this->passedArgs['Search.first_name']);
                $this->data['Search']['first_name'] = $this->passedArgs['Search.first_name'];
            }
	        if (isset($this->passedArgs['Search.last_name'])) {
		        $q["Subscriber"][] = "Subscriber.last_name LIKE '" . addslashes(str_replace('*', '%', $this->passedArgs['Search.last_name'])) . "'";
		        $this->paginate['conditions'][]['Subscriber.last_name LIKE'] = str_replace('*', '%', $this->passedArgs['Search.last_name']);
		        $this->data['Search']['last_name'] = $this->passedArgs['Search.last_name'];
	        }
	        for($i=1; $i<=4;$i++){
		        if (isset($this->passedArgs['Search.custom'.$i])) {
			        $q["Subscriber"][] = "Subscriber.custom". $i." LIKE '" . addslashes(str_replace('*', '%', $this->passedArgs['Search.custom' . $i])) . "'";
			        $this->paginate['conditions'][]['Subscriber.custom'.$i.' LIKE'] = str_replace('*', '%', $this->passedArgs['Search.custom' . $i]);
			        $this->data['Search']['custom'.$i] = $this->passedArgs['Search.custom' . $i ];
			        $this->paginate['fields'][] = "Subscriber.custom".$i;
		        }
	        }
            if (isset($this->passedArgs['Search.deleted'])) {
                $q["Subscriber"][] = "Subscriber.deleted = " . $this->passedArgs['Search.deleted'];
                $this->paginate['conditions'][]['Subscriber.deleted'] = $this->passedArgs['Search.deleted'];
                $this->data['Search']['deleted'] = $this->passedArgs['Search.deleted'];
            }
            if (isset($this->passedArgs['Search.deleted'])) {
                $q["Subscriber"][] = "Subscriber.deleted = " . $this->passedArgs['Search.deleted'];
                $this->paginate['conditions'][]['Subscriber.deleted'] = $this->passedArgs['Search.deleted'];
                $this->data['Search']['deleted'] = $this->passedArgs['Search.deleted'];
            }
            if (isset($this->passedArgs['Search.mail_adresse'])) {
                $q["Subscriber"][] = "Subscriber.mail_adresse LIKE '" . addslashes(str_replace('*', '%', $this->passedArgs['Search.mail_adresse'])) . "'";
                $this->paginate['conditions'][]['Subscriber.mail_adresse LIKE'] = str_replace('*', '%', $this->passedArgs['Search.mail_adresse']);
                $this->data['Search']['mail_adresse'] = $this->passedArgs['Search.mail_adresse'];
            }
            if (isset($this->passedArgs['Search.created'])) {
                $field = '';
                $date = explode(' ', $this->passedArgs['Search.created']);
                if (isset($date[1]) && in_array($date[0], array('<', '>', '<=', '>='))) {
                    $field = ' ' . array_shift($date);
                }
                $date = implode(' ', $date);
                $date = date('Y-m-d', strtotime($date));
                $q["Subscriber"][] = 'Subscriber.created' . $field . ' \'' . $date . '\'';
                $this->paginate['conditions'][]['Subscriber.created' . $field] = $date;
                $this->data['Search']['created'] = $this->passedArgs['Search.created'];
            }
            $this->data['Search']['query'] = json_encode($q);
            $this->Subscriber->recursive = 0;
            $this->set('subscribers', $this->paginate());
        } else if ($cat != "") {
            $this->Subscriber->recursive = 0;
            $this->paginate['conditions'] = array('CategoriesSubscriber.category_id' => array($cat));
            $this->data['Search']['query'] = json_encode(array("Subscriber" => array(), "CategoriesSubscriber" => array('CategoriesSubscriber.category_id = ' . $cat)));
            $this->set('subscribers', $this->paginate("Subscriber"));
        } else {
            $this->Subscriber->recursive = 0;
            $this->paginate['conditions'] = array('CategoriesSubscriber.category_id IS NULL');
            $this->data['Search']['query'] = json_encode(array("Subscriber" => array(), "CategoriesSubscriber" => array('CategoriesSubscriber.category_id IS NULL')));
            $this->set('subscribers', $this->paginate("Subscriber"));
        }

        $d = "SELECT COUNT(*) AS `count` FROM `subscribers` AS `Subscriber` LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON (`CategoriesSubscriber`.`subscriber_id` = `Subscriber`.`id`) WHERE `Subscriber`.`deleted`=0 and `CategoriesSubscriber`.`category_id` IS NULL ";
        $d = $this->Subscriber->query($d);
        $d2 = "SELECT COUNT(*) AS `count` FROM `subscribers` AS `Subscriber` LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON (`CategoriesSubscriber`.`subscriber_id` = `Subscriber`.`id`) WHERE `CategoriesSubscriber`.`category_id` IS NULL ";
        $d2 = $this->Subscriber->query($d2);
        $categories = $this->Subscriber->Category->find('all');
        $categories_list[""] = __("Uncategorized", true);
        $categories_list += $this->Subscriber->Category->find('list');

        $this->set("uncatd", " (" . $d[0][0]["count"] . " / " . $d2[0][0]["count"] . ")");
        $this->set(compact('categories'));
        $this->set(compact('categories_list'));
        $this->set(compact('cat'));
    }

    function import()
    {
        $path = "csv" . rand(0, 100000000) . ".csv";
        if (move_uploaded_file($this->data['Import']['file']['tmp_name'], APP . WEBROOT_DIR . DS . 'csv' . DS . $path)) {

            $this->redirect(array('action' => 'import2', $path));
        }
    }

    function import2($name = null)
    {
        @set_time_limit(60 * 60);
        $this->set("forms", $this->Form->find('list'));
        if ($name == null) {
            $this->redirect(array('action' => 'import'));
        }
        if (!file_exists(APP . WEBROOT_DIR . DS . 'csv' . DS . $name)) {
            $this->Session->setFlash(__('Invalide path', true));
            $this->redirect(array('action' => 'import'));
        }
        if (!isset($this->data)) {

            $content = file_get_contents(APP . WEBROOT_DIR . DS . 'csv' . DS . $name);
            $determ = array(";" => 0, "," => 0);
            foreach ($determ as $key => $value) {
                $determ[$key] = substr_count($content, $key);
            }
            arsort($determ);
            $determ = array_keys($determ);
            $this->data["Subscriber"]["delimiter"] = $determ[0];
            $enclosure = array("\"" => 0, "'" => 0);
            foreach ($enclosure as $key => $value) {
                $enclosure[$key] = substr_count($content, $key);
            }
            arsort($enclosure);

            $enclosure = array_keys($enclosure);

            $this->data["Subscriber"]["enclosure"] = $enclosure[0];
        }
        $jumped = false;
        $cc = 0;
        @setlocale(LC_ALL, 'en_US.UTF-8');
        if (($handle = fopen(APP . WEBROOT_DIR . DS . 'csv' . DS . $name, "r")) !== FALSE) {
            $i = 0;

            while (($lineArray = fgetcsv($handle, 4000, $this->data["Subscriber"]["delimiter"], $this->data["Subscriber"]["enclosure"])) !== FALSE) {
                $cc++;
                if (!(isset($this->data["Subscriber"]["rem_header"]) && $this->data["Subscriber"]["rem_header"] == 1) || $jumped) {
                    for ($j = 0; $j < count($lineArray); $j++) {


                        if ($i < 10) {
                            if (function_exists("mb_convert_encoding")) {
                                $data[$i][$j] = mb_convert_encoding(trim($lineArray[$j]), 'UTF-8');
                            } else {
                                $data[$i][$j] = trim($lineArray[$j]);
                            }
                        } else if ($i < 11) {
                            $data[$i][$j] = "<b>more ...</b>";
                        }
                    }
                    $i++;
                } else {
                    $jumped = true;
                }
            }
            fclose($handle);
        }
        if (!isset($this->data["Import"])) {
            $co = 0;
            $opt = array("first_name", "last_name", "");
            foreach ($data[count($data) == 0 ? 0 : 1] as $key => $value) {

                if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $value)) {
                    $this->data["Import"]["col" . $key] = "mail_adresse";
                } else {

                    $this->data["Import"]["col" . $key] = $opt[$co];
                    if ($co != 2)
                        $co++;
                }
            }
        }
        $updatebutton = __("Refresh and Import", true);
        if (isset($this->data["clicked"]) && $this->data["clicked"] == $updatebutton) {

            if (isset($this->data["Category"]["Category"][0]) || $this->data["Subscriber"]["existing"] == "unsubscribe" || $this->data["Subscriber"]["existing"] == "delete") {
                $this->set('pass', json_encode($this->data));
                $this->set('cc', $cc);
                $this->set('file', $name);
                $this->render("import3");
            } else {
                $this->Session->setFlash(__('Please select a category', true));
            }
        }
        $categories = $this->Subscriber->Category->find('list');
        $this->set(compact('categories'));
        $this->set('file', $name);
        $this->set('data', $data);
        $this->set('updatebutton', $updatebutton);
    }

    function import4($name = null)
    {
        unlink(APP . WEBROOT_DIR . DS . 'csv' . DS . $name);
        $this->cleanup();
        $this->Session->setFlash(__('Import Done', true));
        $this->redirect(array('action' => 'index'));
    }

    function import3($name = null, $start = 0, $end = 0)
    {
        $this->autoRender = FALSE;
        $finaldata = array();
        @setlocale(LC_ALL, 'en_US.UTF-8');
        $jumped = ($start != 0);
        if (($handle = fopen(APP . WEBROOT_DIR . DS . 'csv' . DS . $name, "r")) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, $_POST["Subscriber"]["delimiter"], $_POST["Subscriber"]["enclosure"])) !== FALSE) {
                if ((!(isset($_POST["Subscriber"]["rem_header"]) && $_POST["Subscriber"]["rem_header"] == 1)) || $jumped) {
                    for ($j = 0; $j < count($lineArray); $j++) {
                        if ($i >= $start && $end > $i) {
                            if (function_exists("mb_convert_encoding")) {
                                $finaldata[$i][$j] = mb_convert_encoding(trim($lineArray[$j]), 'UTF-8');
                            } else {
                                $finaldata[$i][$j] = trim($lineArray[$j]);
                            }
                        }
                    }
                    $i++;
                } else {
                    $jumped = true;
                    $i++;
                }
            }
            fclose($handle);
        }

        $pointers = array_flip($_POST["Import"]);
        if (isset($pointers[""])) {
            unset($pointers[""]);
        }
        foreach ($pointers as $key => $value) {
            $pointers[$key] = str_replace("col", "", $value);
        }
        $savedata = array();
        $mails = array();
        $added = 0;
        $updated = 0;
        $skiped = 0;
        foreach ($finaldata as $row) {
            $data_row = array();

            foreach ($pointers as $key => $value) {
                $data_row["Subscriber"][$key] = trim($row[$value]);
            }
            $data_row["Subscriber"]["form_id"] = $_POST["Subscriber"]["form"];
            $data_row["Subscriber"]["unsubscribe_code"] = substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12);
            $data_row["Category"] = $_POST["Category"];
            if (isset($data_row["Subscriber"]["mail_adresse"])) {
                if ($_POST["Subscriber"]["resub"] == 1) {


                    $this->Subscriber->query("UPDATE `subscribers` SET `deleted` = '0' WHERE `subscribers`.`mail_adresse` ='" . $data_row["Subscriber"]["mail_adresse"] . "';");
                }
	            if ($_POST["Subscriber"]["existing"] == "delete") {
		            $this->Subscriber->query("DELETE FROM `subscribers` WHERE `subscribers`.`mail_adresse` ='" . $data_row["Subscriber"]["mail_adresse"] . "';");
	            } else if ($_POST["Subscriber"]["existing"] == "unsubscribe") {
		            $this->Subscriber->query("UPDATE `subscribers` SET `deleted` = '1' WHERE `subscribers`.`mail_adresse` ='" . $data_row["Subscriber"]["mail_adresse"] . "';");
	            } else if (!in_array($data_row["Subscriber"]["mail_adresse"], $mails)) {
                    if ($this->Subscriber->find("count", array("conditions" => array("mail_adresse" => $data_row["Subscriber"]["mail_adresse"]))) == 0) {
                        if (!empty($data_row["Subscriber"]["mail_adresse"]) &&
                            preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $data_row["Subscriber"]["mail_adresse"])
                        ) {


                            $savedata[] = $data_row;
                            $mails[] = $data_row["Subscriber"]["mail_adresse"];
                            $added++;
                        } else {
                            $skiped++;
                        }
                    } else {
                        if ($_POST["Subscriber"]["existing"] == "update") {

                            $d = $this->Subscriber->find("first", array("conditions" => array("mail_adresse" => $data_row["Subscriber"]["mail_adresse"])));

                            $old = $d["Category"];
                            $d["Category"]["Category"] = array();
                            foreach ($old as $i) {
                                $d["Category"]["Category"] [] = $i["id"];
                            }

                            foreach ($_POST["Category"]["Category"] as $i) {
                                $d["Category"]["Category"] [] = $i;
                            }

                            $d["Category"]["Category"] = array_unique($d["Category"]["Category"]);
                            $this->Subscriber->save($d);

                            $mails[] = $data_row["Subscriber"]["mail_adresse"];
                            $updated++;
                        } else if ($_POST["Subscriber"]["existing"] == "overwrite") {

                            $d = $this->Subscriber->find("first", array("conditions" => array("mail_adresse" => $data_row["Subscriber"]["mail_adresse"])));

                            $d["Category"] = array();
                            $d["Category"]["Category"] = array();


                            foreach ($_POST["Category"]["Category"] as $i) {
                                $d["Category"]["Category"] [] = $i;
                            }

                            $d["Category"]["Category"] = array_unique($d["Category"]["Category"]);
                            $this->Subscriber->save($d);

                            $mails[] = $data_row["Subscriber"]["mail_adresse"];
                            $updated++;
                        } else {
                            $skiped++;
                        }
                    }
                } else {
                    $skiped++;
                }
            }
        }
        $this->Subscriber->create();

        $this->Subscriber->saveAll($savedata);
        $this->Subscriber->deleteAll(array("mail_adresse IS NULL"));
        echo "ok";
    }

    function view($id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid subscriber', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Subscriber->recursive = 2;
        $this->set('subscriber', $this->Subscriber->read(null, $id));
    }

    function confirm($data = "")
    {
        $data = split("-", $data);
        if (count($data) > 1) {
            $in = $this->Subscriber->find("first", array("conditions" => array("id" => $data[0], "unsubscribe_code" => $data[1])));
            $this->Subscriber->recursive = -1;


            if ($in != null) {
                if ($in["Subscriber"]["deleted"] == 1) {
                    $this->Subscriber->confirm($in["Subscriber"]["id"]);
                }

                $this->redirect(array("controller" => "forms", "action" => "thank", $in["Subscriber"]["form_id"]));
            }
        }
        $this->redirect(array("controller" => "forms", "action" => "thank"));
    }

    function unsubscribe($mail_id = "", $data = "")
    {
        if ($data == "0-GUEST") {
            $this->redirect("/unsubscribe_msg/");
        }
        $data = split("-", $data);
        if (count($data) > 1) {
            $in = $this->Subscriber->find("first", array("conditions" => array("id" => $data[0], "unsubscribe_code" => $data[1], "deleted" => 0)));
            $this->Subscriber->recursive = -1;


            if ($in != null) {
                $this->Subscriber->unsubscribe($in["Subscriber"]["id"]);

                $r = $this->Mail->read(array("unsubscribed"), $mail_id);
                if ($r != null) {
                    $this->Mail->Recipient->openMail($mail_id, $data[0]);
                    $this->Mail->id = $mail_id;

                    $this->Mail->saveField("unsubscribed", $r["Mail"]["unsubscribed"] + 1);
                }
                $this->redirect("/unsubscribe_msg/" . $in["Subscriber"]["form_id"]);
            }
        }
        $this->redirect("/unsubscribe_msg/");
    }

    function add($cat = null)
    {
        if (!empty($this->data)) {
            $this->Subscriber->create();
            $this->data["Subscriber"]["unsubscribe_code"] = substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12);
            if ($this->Subscriber->save($this->data)) {
                $this->Session->setFlash(__('The subscriber has been saved', true));

                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The subscriber could not be saved. Please, try again.', true));
            }
        }
        if ($cat != null) {
            $this->data["Category"]["Category"][] = $cat;
        }
        $categories = $this->Subscriber->Category->find('list');
        $this->set(compact('categories'));
    }

    function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid subscriber', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Subscriber->save($this->data)) {
                $this->Session->setFlash(__('The subscriber has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The subscriber could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Subscriber->read(null, $id);
        }
        $categories = $this->Subscriber->Category->find('list');
        $this->set(compact('categories'));
    }

    function delete($id = null, $cat = "")
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for subscriber', true));
            $this->redirect(array('action' => 'index', $cat));
        }
        if ($this->Subscriber->delete($id)) {
            $this->Session->setFlash(__('Subscriber deleted', true));
            $this->redirect(array('action' => 'index', $cat));
        }
        $this->Session->setFlash(__('Subscriber was not deleted', true));
        $this->redirect(array('action' => 'index', $cat));
    }

}

?>
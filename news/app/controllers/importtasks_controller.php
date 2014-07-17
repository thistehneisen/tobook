<?php

class ImporttasksController extends AppController {

    var $name = 'Importtasks';
    var $uses = array("Importtask", "Category", "CategoriesSubscriber", "Subscriber", "Form");
    public $defaultConfig = array(
        'name' => 'default',
        'driver' => 'mysql',
        'persistent' => false,
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'newsletter',
        'schema' => null,
        'prefix' => null,
        'encoding' => 'UTF8',
        'port' => null,
    );

    function index() {
        $this->Importtask->recursive = 0;
        $this->set('importtasks', $this->paginate());
    }

    function importjob($id=null, $loop=0, $all=0) {
        if ($id == null || $id == 0) {
            $d = $this->Importtask->find("first", array("order" => "Importtask.id asc"));
            if (!empty($d)) {
                $id = $d["Importtask"]["id"];
                $all = 1;
            }
        }
        $this->set('path', array("action" => "run", $id, $loop, $all));
    }

    function add() {
        if (!empty($this->data)) {
            $this->Importtask->create();
            if ($this->Importtask->save($this->data)) {

                $this->redirect(array('action' => 'edit1', $this->Importtask->id));
            } else {
                $this->Session->setFlash(__('The import task could not be saved. Please, try again.', true));
            }
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid import task', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Importtask->save($this->data)) {

                $this->redirect(array('action' => 'edit1', $this->Importtask->id));
            } else {
                $this->Session->setFlash(__('The import task could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Importtask->read(null, $id);
        }
    }

    function edit1($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid import task', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Importtask->save($this->data)) {
                $this->Session->setFlash(__('Connection Successful', true));
                $this->redirect(array('action' => 'edit2', $this->Importtask->id));
            } else {
                $this->Session->setFlash(__('The import task could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Importtask->read(null, $id);
        }
    }

    function edit2($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid import task', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Importtask->save($this->data)) {

                $this->redirect(array('action' => 'edit3', $this->Importtask->id));
            } else {
                $this->Session->setFlash(__('The import task could not be saved. Please, try again.', true));
            }
        }
        $infos = $this->Importtask->read(null, $id);
        @App::import('Model', 'ConnectionManager');
        $config = $this->defaultConfig;
        foreach ($infos["Connection"] AS $key => $value) {
            if (isset($infos["Connection"][$key])) {
                $config[$key] = $value;
            }
        }

        @ConnectionManager::create('tecon', $config);
        $db = ConnectionManager::getDataSource('tecon');
        if (!$db->isConnected()) {
            $this->Session->setFlash(__('Could not connect to database.', true), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'edit1', $this->Importtask->id));

            return;
        }
        if (empty($this->data)) {
            $this->data = $infos;
        }
    }

    function edit3($id = null) {
        $this->set("forms", $this->Form->find('list'));
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid import task', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Importtask->save($this->data)) {
                $this->Session->setFlash(__('The import task has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The import task could not be saved. Please, try again.', true));
            }
        }
        $infos = $this->Importtask->read(null, $id);
        @App::import('Model', 'ConnectionManager');
        $config = $this->defaultConfig;
        foreach ($infos["Connection"] AS $key => $value) {
            if (isset($infos["Connection"][$key])) {
                $config[$key] = $value;
            }
        }

        @ConnectionManager::create('tecon', $config);
        $db = ConnectionManager::getDataSource('tecon');
        if (!$db->isConnected()) {
            $this->Session->setFlash(__('Could not connect to database.', true), 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'edit1', $this->Importtask->id));

            return;
        }

        $inf = $db->query($infos["Importtask"]["query"] . " LIMIT 10");

        if (!empty($db->error)) {
            $this->Session->setFlash(__('Error in Query: ', true) . $db->error, 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'edit2', $this->Importtask->id));
        }
        $content = array();
        foreach ($inf as $row) {
            $cont = array();
            foreach ($row as $groupn => $fields) {
                foreach ($fields as $k => $v) {
                    $cont[$groupn . ":" . $k] = $v;
                }
            }
            $content[] = $cont;
        }

        $this->set("data", $content);
        if (empty($this->data)) {
            $this->data = $infos;
        }
        $categories = $this->Category->find('list');
        $this->set(compact('categories'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for import task', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Importtask->delete($id)) {
            $this->Session->setFlash(__('Import task deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Import task was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    function run($id=null, $loop=0, $all=0) {
        $infos = $this->Importtask->read(null, $id);

        if (!empty($infos)) {
            @App::import('Model', 'ConnectionManager');
            $config = $this->defaultConfig;
            foreach ($infos["Connection"] AS $key => $value) {
                if (isset($infos["Connection"][$key])) {
                    $config[$key] = $value;
                }
            }

            @ConnectionManager::create('tecon', $config);
            $db = ConnectionManager::getDataSource('tecon');
            if (!$db->isConnected()) {
                if ($all != 1) {
                    $this->Session->setFlash(__('Could not connect to database.', true), 'default', array('class' => 'error'));
                    die("Error");
                    //  $this->redirect(array('action' => 'index'));
                }
            } else {

                $inf = $db->query($infos["Importtask"]["query"] . " LIMIT " . ($loop * 100) . ", 100");
                if (count($inf) != 0) {
                    if (!empty($db->error)) {
                        if ($all != 1) {
                            $this->Session->setFlash(__('Error in Query: ', true) . $db->error, 'default', array('class' => 'error'));
                            die("Error");
                            //   $this->redirect(array('action' => 'index'));
                        }
                    } else {
                        $finaldata = array();
                        foreach ($inf as $row) {
                            $cont = array();
                            foreach ($row as $groupn => $fields) {
                                foreach ($fields as $k => $v) {
                                    $cont[$groupn . ":" . $k] = $v;
                                }
                            }
                            $finaldata[] = $cont;
                        }

                        $savedata = array();
                        $mails = array();
                        $added = 0;
                        $updated = 0;
                        $skiped = 0;
                        foreach ($finaldata as $row) {
                            $data_row = array();

                            foreach ($infos['Import'] as $key => $value) {
                                if (!empty($value)) {
                                    $data_row["Subscriber"][$value] = trim($row[$key]);
                                }
                            }

                            $data_row["Subscriber"]["form_id"] = $infos["Importtask"]["form"];
                            $data_row["Subscriber"]["unsubscribe_code"] = substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12);
                            $data_row["Category"] = $infos['Importtask']['Category'];
                            if (isset($data_row["Subscriber"]["mail_adresse"])) {
                                if ($infos["Importtask"]["resubscribe"] == 1) {


                                    $this->Subscriber->query("UPDATE `subscribers` SET `deleted` = '0' WHERE `subscribers`.`mail_adresse` ='" . $data_row["Subscriber"]["mail_adresse"] . "';");
                                }
                                if (!in_array($data_row["Subscriber"]["mail_adresse"], $mails)) {
                                    if ($this->Subscriber->find("count", array("conditions" => array("mail_adresse" => $data_row["Subscriber"]["mail_adresse"]))) == 0) {
                                        if (!empty($data_row["Subscriber"]["mail_adresse"]) &&
                                                preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $data_row["Subscriber"]["mail_adresse"])) {


                                            $savedata[] = $data_row;
                                            $mails[] = $data_row["Subscriber"]["mail_adresse"];
                                            $added++;
                                        } else {
                                            $skiped++;
                                        }
                                    } else {
                                        if ($infos['Importtask']["act"] == "update") {

                                            $d = $this->Subscriber->find("first", array("order" => "Subscriber.created DESC", "conditions" => array("mail_adresse" => $data_row["Subscriber"]["mail_adresse"])));
                                            $old = $d["Category"];
                                            $d["Category"]["Category"] = array();
                                            foreach ($old as $i) {
                                                $d["Category"]["Category"] [] = $i["id"];
                                            }

                                            foreach ($infos['Importtask']['Category'] as $i) {
                                                $d["Category"]["Category"] [] = $i;
                                            }

                                            $d["Category"]["Category"] = array_unique($d["Category"]["Category"]);
                                            $this->Subscriber->save($d);

                                            $mails[] = $data_row["Subscriber"]["mail_adresse"];
                                            $updated++;
                                        } else if ($infos['Importtask']["act"] == "overwrite") {

                                            $d = $this->Subscriber->find("first", array("order" => "Subscriber.created DESC", "conditions" => array("mail_adresse" => $data_row["Subscriber"]["mail_adresse"])));

                                            $d["Category"]["Category"] = array();


                                            foreach ($infos['Importtask']['Category'] as $i) {
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
                        //$this->redirect(array('action' => 'run', $id, $loop + 1, $all));

                        die((count($inf) + ($loop * 100)) . " " . "records imported" . "-" . Router::url(array('action' => 'run', $id, $loop + 1, $all), true));
                    }
                } else {
                    if ($all != 1) {
                        // $this->redirect(array('action' => 'index'));
                        die("DONE");
                    }
                }
            }
            if ($all == 1) {
                $d = $this->Importtask->find("first", array("conditions" => array("Importtask.id > " => $id), "order" => "Importtask.id asc"));
                if (!empty($d)) {
                    die("Next Task" . "-" . Router::url(array('action' => 'run', $d["Importtask"]["id"], 0, 1), true));
                    //        $this->redirect(array('action' => 'run', $d["Importtask"]["id"], 0, 1));
                } else {
                    //   $this->redirect(array('action' => 'index'));
                    die("DONE");
                }
            }
        } else {
            die("Invalide ID");
        }
        die("DONE");
    }

}

?>
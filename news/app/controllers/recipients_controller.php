<?php

class Format {

    static public function arr_to_csv_line($arr) {
        $line = array();
        foreach ($arr as $v) {
            $line[] = is_array($v) ? self::arr_to_csv_line($v) : '"' . str_replace('"', '""', $v) . '"';
        }
        return implode(",", $line);
    }

    static public function arr_to_csv($arr) {
        $lines = array();
        foreach ($arr as $v) {
            $lines[] = self::arr_to_csv_line($v);
        }
        return implode("\n", $lines);
    }

}

class RecipientsController extends AppController {

    var $name = 'Recipients';
    var $uses = array("Recipient", "Mail");

    function index($id, $cat="") {
        $this->paginate['conditions'][]['mail_id'] = $id;

        $this->paginate['order'] = "read_date desc";
        if ($cat == "search") {
            if (isset($this->passedArgs['Search.first_name'])) {
                $this->paginate['conditions'][]['Subscriber.first_name LIKE'] = str_replace('*', '%', $this->passedArgs['Search.first_name']);
                $this->data['Search']['first_name'] = $this->passedArgs['Search.first_name'];
            }
            if (isset($this->passedArgs['Search.last_name'])) {
                $this->paginate['conditions'][]['Subscriber.last_name LIKE'] = str_replace('*', '%', $this->passedArgs['Search.last_name']);
                $this->data['Search']['last_name'] = $this->passedArgs['Search.last_name'];
            }
            if (isset($this->passedArgs['Search.country'])) {
                $this->paginate['conditions'][]['Recipient.country LIKE'] = str_replace('*', '%', $this->passedArgs['Search.country']);
                $this->data['Search']['country'] = $this->passedArgs['Search.country'];
            }
            if (isset($this->passedArgs['Search.mail_adresse'])) {
                $this->paginate['conditions'][]['Subscriber.mail_adresse LIKE'] = str_replace('*', '%', $this->passedArgs['Search.mail_adresse']);
                $this->data['Search']['mail_adresse'] = $this->passedArgs['Search.mail_adresse'];
            }
            if (isset($this->passedArgs['Search.deleted'])) {

                $this->paginate['conditions'][]['Subscriber.deleted'] = $this->passedArgs['Search.deleted'];
                $this->data['Search']['deleted'] = $this->passedArgs['Search.deleted'];
            }
            if (isset($this->passedArgs['Search.status'])) {
                if ($this->passedArgs['Search.status'] == "0") {
                    $this->paginate['conditions'][]['read'] = 1;
                } else if ($this->passedArgs['Search.status'] == "1") {
                    $this->paginate['conditions'][]['read'] = 0;
                    $this->paginate['conditions'][]['sent'] = 1;
                } else if ($this->passedArgs['Search.status'] == "2") {
                    $this->paginate['conditions'][]['read'] = 0;
                    $this->paginate['conditions'][]['sent'] = 0;
                    $this->paginate['conditions'][]['failed <'] = 2;
                } else if ($this->passedArgs['Search.status'] == "3") {

                    $this->paginate['conditions'][] = array("or" => array("Subscriber.deleted =" => 1, "failed >" => 1, "Subscriber.deleted is NULL"));
                }
                $this->data['Search']['status'] = $this->passedArgs['Search.status'];
            }

            if (isset($this->passedArgs['Search.read_date'])) {
                $field = '';
                $date = explode(' ', $this->passedArgs['Search.read_date']);
                if (isset($date[1]) && in_array($date[0], array('<', '>', '<=', '>='))) {
                    $field = ' ' . array_shift($date);
                }
                $date = implode(' ', $date);
                $date = date('Y-m-d', strtotime($date));
                $this->paginate['conditions'][]['Recipient.read_date' . $field] = $date;
                $this->data['Search']['read_date'] = $this->passedArgs['Search.read_date'];
            }
        } else {
            $this->paginate['conditions'][]['read'] = 1;
        }

        $this->Form->recursive = 0;
        $this->set('mail', $this->Mail->read(null, $id));
        $this->set('recipients', $this->paginate());
        $this->set('cat', $cat);
    }

    function export($id, $cat="") {
        $columns = array("country" => __("Country", true), "read_date" => __("Read On", true), "open_count" => __("Open Count", true), "first_name" => __("First Name", true), "last_name" => __("Last Name", true), "mail_adresse" => __("Mail Address", true), "notes" => __("Notes", true),
            "custom1" => __("Custom Field 1", true), "custom2" => __("Custom Field 2", true), "custom3" => __("Custom Field 3", true), "custom4" => __("Custom Field 4", true));
        $this->set(compact('columns'));
        $this->set('mail', $this->Mail->read(null, $id));
        if (!empty($this->data)) {
            if (empty($this->data["Recipient"]["column"])) {
                $this->Session->setFlash(__('Please select a least one column', true));
                return;
            }
            $this->Recipient->recursive = 1;
            $conditions = array();
            $conditions['mail_id'] = $id;

            if ($cat == "search") {
                if (isset($this->passedArgs['Search.first_name'])) {
                    $conditions['Subscriber.first_name LIKE'] = str_replace('*', '%', $this->passedArgs['Search.first_name']);
                }
                if (isset($this->passedArgs['Search.last_name'])) {
                    $conditions['Subscriber.last_name LIKE'] = str_replace('*', '%', $this->passedArgs['Search.last_name']);
                }
                if (isset($this->passedArgs['Search.country'])) {
                    $conditions['Recipient.country LIKE'] = str_replace('*', '%', $this->passedArgs['Search.country']);
                }
                if (isset($this->passedArgs['Search.mail_adresse'])) {
                    $conditions['Subscriber.mail_adresse LIKE'] = str_replace('*', '%', $this->passedArgs['Search.mail_adresse']);
                }
                if (isset($this->passedArgs['Search.deleted'])) {

                    $conditions['Subscriber.deleted'] = $this->passedArgs['Search.deleted'];
                }
                if (isset($this->passedArgs['Search.status'])) {
                    if ($this->passedArgs['Search.status'] == "0") {
                        $conditions['read'] = 1;
                    } else if ($this->passedArgs['Search.status'] == "1") {
                        $conditions['read'] = 0;
                        $conditions['sent'] = 1;
                    } else if ($this->passedArgs['Search.status'] == "2") {
                        $conditions['read'] = 0;
                        $conditions['sent'] = 0;
                        $conditions['failed <'] = 2;
                    } else if ($this->passedArgs['Search.status'] == "3") {

                        $conditions = array("or" => array("Subscriber.deleted =" => 1, "failed >" => 1, "Subscriber.deleted is NULL"));
                    }
                }

                if (isset($this->passedArgs['Search.read_date'])) {
                    $field = '';
                    $date = explode(' ', $this->passedArgs['Search.read_date']);
                    if (isset($date[1]) && in_array($date[0], array('<', '>', '<=', '>='))) {
                        $field = ' ' . array_shift($date);
                    }
                    $date = implode(' ', $date);
                    $date = date('Y-m-d', strtotime($date));
                    $conditions['Recipient.read_date' . $field] = $date;
                }
            } else {
                $conditions['read'] = 1;
            }
            $da = $this->Recipient->find("all", array("conditions" => $conditions));
            $recip = array();
            $header = array();
            foreach ($this->data["Recipient"]["column"] as $value) {
                $header[] = $columns[$value];
            }
            $recip[] = $header;

            foreach ($da as $v) {
                $row = array();
                foreach ($this->data["Recipient"]["column"] as $value) {
                    if ($value != "country" && $value != "read_date" && $value != "open_count") {
                        $row[] = $v["Subscriber"][$value];
                    } else {
                        $row[] = $v["Recipient"][$value];
                    }
                }
                $recip[] = $row;
            }
            if (count($recip) == 1) {
                $this->Session->setFlash(__('No subscribers', true));
                return;
            }
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=\"export.csv\"");
            echo Format::arr_to_csv($recip);
            die();
        }
        $this->Form->recursive = 0;
    }

    function search($id) {
        // the page we will redirect to
        $url['action'] = 'index';
        $url[] = $id;
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

}

?>
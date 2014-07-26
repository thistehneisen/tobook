<?php
class TemplatesController extends AppController {

    var $name = 'Templates';
    var $uses = array('Template', 'Image');

    function index() {
        $this->Template->recursive = 0;
        $this->set('templates', $this->paginate());
    }

    function import() {
        if (!empty($this->data)) {

            $da = base64_decode(file_get_contents($this->data["Import"]["file"]["tmp_name"]));
            if (empty($da)) {
                $this->Session->setFlash(__('Invalid template', true));
                return;
            }
            $arr = myunserialize($da);
            $i = 0;
            foreach ($arr["Files"] as $k => $value) {
                $i++;
                $name = md5(microtime()) . $i . "." . end(explode(".", $k));
                $ph = "uploads/template/" . $name;
                $na = ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . "uploads" . DS . "template" . DS . $name;

                file_put_contents($na, base64_decode($value));

                $arr["Template"]["content"] = str_replace($k, $ph, $arr["Template"]["content"]);
            }

            unset($arr["Files"]);
            if (isset($arr["Preview"])) {

                $name = md5(microtime()) . $i . "." . end(explode(".", $arr["Preview"][0]));
                $ph = "uploads/template/" . $name;
                $na = ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . "uploads" . DS . "template" . DS . $name;
                file_put_contents($na, base64_decode($arr["Preview"][1]));
                $arr["Template"]["preview"]="/".$ph;
                unset($arr["Preview"]);
            }

            if ($this->Template->save($arr)) {
                $this->Session->setFlash(__('The template has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The template could not be saved. Please, try again.', true));
            }
        }
    }

    function export($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid template', true));
            $this->redirect(array('action' => 'index'));
        }
        $template = $this->Template->read(null, $id);
        unset($template["Template"]["id"]);
        preg_match_all('/[s|S][R|r][c|C]=["\\\']?([^"\\\' ]*)["\\\' ]/is', $template["Template"]["content"], $images, PREG_PATTERN_ORDER);

        preg_match_all('/background=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/i', $template["Template"]["content"], $imagesbg, PREG_PATTERN_ORDER);

        preg_match_all('/url\([\\\']?([^\)\\\']*)[\\\']?\)/i', $template["Template"]["content"], $imagescss, PREG_PATTERN_ORDER);

        $img = array_values(array_unique(array_merge($images[1], $imagescss[1])));
        $imglist = array();
        $root = '{$baseurl}';
        if (file_exists(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . str_replace("/", DS, $template["Template"]["preview"]))) {
            $template["Preview"] = array($template["Template"]["preview"], base64_encode(file_get_contents(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . str_replace("/", DS, $template["Template"]["preview"]))));
        }
        foreach ($imagesbg[1] as $im) {
            if (strpos($im, "http://") === false) {
                $im = str_replace($root, "", $im);
                if (file_exists(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . $im) || strpos($im, "images/") === 0)
                    $imglist[$im] = base64_encode(file_get_contents(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . $im));
            }
        }
        foreach ($img as $im) {
            if (strpos($im, "http://") === false) {
                $im = str_replace($root, "", $im);
                if (file_exists(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . $im) || strpos($im, "images/") === 0)
                    $imglist[$im] = base64_encode(file_get_contents(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . $im));
            }
        }
        $template["Files"] = $imglist;
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . $template["Template"]["name"] . ".nmt\"");

        echo base64_encode(myserialize($template));
        die();
    }

    function preview($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid template', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('template', $this->Template->read(null, $id));
        $this->layout = "ajax";
    }

    function add() {
        if (!empty($this->data)) {
            $this->Template->create();

            if ($this->Template->save($this->data)) {
                $this->Session->setFlash(__('The template has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The template could not be saved. Please, try again.', true));
            }
            $this->data["Template"]["content"] = str_replace("{\$baseurl}", Router::url("/", true), $this->data["Template"]["content"]);
        }
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid template', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {

            if ($this->Template->save($this->data)) {
                $this->Session->setFlash(__('The template has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The template could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->data = $this->Template->read(null, $id);
        }
        $this->data["Template"]["content"] = str_replace("{\$baseurl}", Router::url("/", true), $this->data["Template"]["content"]);
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for template', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Template->delete($id)) {
            $this->Session->setFlash(__('Template deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Template was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

    function images($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid template', true));
            $this->redirect(array('action' => 'index'));
        }
        $template = $this->Template->read(null, $id);
        if (!empty($this->data)) {
            foreach ($this->data["Image"] as $key => $value) {
                if (strpos($key, "image") !== false) {
                    $up = $this->Image->upload(array("Image" => array("image" => $value)), "template" . DS);
                    if ($up != false) {
                        $template["Template"]["content"] = str_replace($this->data["Image"]["path" . str_replace("image", "", $key)], $up, $template["Template"]["content"]);
                    } else {
                        $this->Session->setFlash(__('There was an error with the uploaded file.',true));
                    }
                }
            }
            $this->Template->id = $id;
            $this->Template->saveField("content", $template["Template"]["content"]);
        }
        preg_match_all('/[s|S][R|r][c|C]=["\\\']?([^"\\\' ]*)["\\\' ]/is', $template["Template"]["content"], $images, PREG_PATTERN_ORDER);

        preg_match_all('/background=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/i', $template["Template"]["content"], $imagesbg, PREG_PATTERN_ORDER);

        preg_match_all('/url\([\\\']?([^\)\\\']*)[\\\']?\)/i', $template["Template"]["content"], $imagescss, PREG_PATTERN_ORDER);

        $img = array_values(array_unique(array_merge($images[1], $imagescss[1])));
        $imglist = array();
        $root = '{$baseurl}';

        foreach ($imagesbg[1] as $im) {
            if (strpos($im, "http://") === false) {
                $im = str_replace($root, "", $im);
                if (!file_exists(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . $im) || strpos($im, "images/") === 0)
                    $imglist[] = $im;
            }
        }
        foreach ($img as $im) {
            if (strpos($im, "http://") === false) {
                $im = str_replace($root, "", $im);
                if (!file_exists(ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS . $im) || strpos($im, "images/") === 0)
                    $imglist[] = $im;
            }
        }
        $imglist = array_unique($imglist);
        sort($imglist, SORT_STRING);
        if (count($imglist) == 0) {
            $this->Session->setFlash(__('No missing images in this template', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('img', $imglist);
        $this->set('id', $id);
    }

}

?>
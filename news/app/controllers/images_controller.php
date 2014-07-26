<?php

class ImagesController extends AppController {

    var $name = 'Images';
    var $uses = array('Image');
    var $helpers = array(
        'Html',
        'Form',
        'Javascript',
        'Number' // Used to show readable filesizes
    );

    function index() {
        $this->set(
                'images',
                $this->Image->readFolder(APP . WEBROOT_DIR . DS . 'uploads')
        );
    }

    function upload() {
        // Upload an image
        if (!empty($this->data)) {
            // Validate and move the file
            if (!empty($this->data["Image"]["url"])) {
                if (in_array('curl', get_loaded_extensions())) {
                    $ch = curl_init($this->data["Image"]["url"]);

                    $img = NULL;
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

                    $data = curl_exec($ch);
                    curl_close($ch);

                    $ext = ".jpg";
                    if (strpos($data, "PNG") !== FALSE) {
                        $ext = ".png";
                    } else if (strpos($data, "GIF") !== FALSE) {
                        $ext = ".gif";
                    }
                    $na = substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12);
                    $pre = "";
                    if (file_exists(APP . WEBROOT_DIR . DS . 'uploads' . DS . $pre . $na . $ext)) {
                        $pre = 1;
                        while (file_exists(APP . WEBROOT_DIR . DS . 'uploads' . DS . $pre . $na . $ext)) {
                            $pre++;
                        }
                    }
                    file_put_contents(APP . WEBROOT_DIR . DS . 'uploads' . DS . $pre . $na . $ext, $data);
                    $this->Session->setFlash(__('The image was successfully uploaded.', true) . ' <a href="#" onclick="selectURL(\'' . $pre . $na . $ext . '\')">' . __('Use this Image', true) . '</a>');
                    $this->redirect(
                            array(
                                'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__('cURL is NOT installed', true));
                    $this->redirect(
                            array(
                                'action' => 'index'
                    ));
                }
            }
            /*
             * $url = 'http://example.com/image.php';
              $img = '/my/folder/flower.gif';
              file_put_contents($img, file_get_contents($url));

             */
            $up = $this->Image->upload($this->data, "");
            if ($up != false) {
                $this->Session->setFlash(__('The image was successfully uploaded.', true) . ' <a href="#" onclick="selectURL(\'' . substr($up, 8) . '\')">' . __('Use this Image', true) . '</a>');
            } else {
                $this->Session->setFlash(__('There was an error with the uploaded file.', true));
            }

            $this->redirect(
                    array(
                        'action' => 'index'
                    )
            );
        } else {
            $this->redirect(
                    array(
                        'action' => 'index'
                    )
            );
        }
    }

}

?>
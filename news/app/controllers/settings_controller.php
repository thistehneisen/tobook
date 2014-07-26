<?php

class SettingsController extends AppController {

    var $name = 'Settings';
    var $uses = array();

    function index() {
        $langs = array();
        $itemHandler = opendir(APP . "locale" . DS);
        if (!empty($this->data)) {

            file_put_contents(CONFIGS . 'settings.yml', json_encode($this->data["Settings"]));
            $this->Session->setFlash(__('Settings saved!!!', true));
            $this->redirect(array('action' => 'index'));
        } else {

            $this->data["Settings"] = Configure::read('Settings');
        }
        $this->lookup = json_decode(file_get_contents(APPLIBS . 'lang.json'), true);
        while (($item = readdir($itemHandler)) !== false) {

            if (is_file(APP . "locale" . DS . $item) != 1 && $item[0] != '.') {

                $langs[$item] = $this->lookup[$item];
            }
        }

	    $this->set('langs', $langs);
	    $tmz= json_decode(file_get_contents(APPLIBS . 'timezones.json'), true);

	    $this->set('timezones',$tmz);
    }

    function clean() {
        Cache::clear();
        $settings = array(
            'view_cache_path' => ROOT . DS . APP . 'tmp' . DS . 'cache' . DS . 'views',
            'std_cache_paths' => array(
                ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'cache' . DS . 'models',
                ROOT . DS . APP_DIR . DS . 'tmp' . DS . 'cache' . DS . 'persistent',
            )
        );
        $paths = $settings['std_cache_paths'];

        foreach ($paths as $path) {
            $folder = new Folder($path);
            $contents = $folder->read();

            foreach ($contents[1] as $file) {

                @unlink($path . DS . $file);
            }
        }
        $this->Session->setFlash(__('Cache cleaned!!!', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>
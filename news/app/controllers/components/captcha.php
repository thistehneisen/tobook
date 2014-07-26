<?php

/**
 * Securimage-Driven Captcha Component
 * @author debuggeddesigns.com
 * @license MIT
 * @version 0.1
 */
//cake's version of a require_once() call
//vendor('securimage'.DS.'securimage'); //use this with the 1.1 core
App::import('Vendor', 'Securimage', array('file' => 'securimage' . DS . 'securimage.php')); //use this with the 1.2 core
//the local directory of the vendor used to retrieve files
define('CAPTCHA_VENDOR_DIR', APP . 'vendors' . DS . 'securimage/');

class CaptchaComponent extends Object {

    var $controller;
    
    //filename and/or directory configuration
    var $_audio_path = 'audio/'; //the full path to wav files used
    var $_gd_font_file = 'gdfonts/bubblebath.gdf'; //the gd font to use
    var $_ttf_file = 'elephant.ttf'; //the path to the ttf font file to load
    var $_wordlist_file = 'words/words.txt'; //the wordlist to use

    function startup(&$controller) {

        //add local directory name to paths
        $this->_ttf_file = CAPTCHA_VENDOR_DIR . $this->_ttf_file;
        $this->_gd_font_file = CAPTCHA_VENDOR_DIR . $this->_gd_font_file;
        $this->_audio_path = CAPTCHA_VENDOR_DIR . $this->_audio_path;
        $this->_wordlist_file = CAPTCHA_VENDOR_DIR . $this->_wordlist_file;
        //CaptchaComponent instance of controller is replaced by a securimage instance
        $controller->captcha = & new securimage();
        //Change some settings
        $img->image_width = 250;
        $img->image_height = 80;
        $img->perturbation = 0.85;
        $img->image_bg_color = new Securimage_Color("#f6f6f6");
        $img->multi_text_color = array(new Securimage_Color("#3399ff"),
            new Securimage_Color("#3300cc"),
            new Securimage_Color("#3333cc"),
            new Securimage_Color("#6666ff"),
            new Securimage_Color("#99cccc")
        );
        $img->use_multi_text = true;
        $img->text_angle_minimum = -5;
        $img->text_angle_maximum = 5;
        $img->use_transparent_text = true;
        $img->text_transparency_percentage = 30; // 100 = completely transparent
        $img->num_lines = 7;
        $img->line_color = new Securimage_Color("#eaeaea");

        $img->signature_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
        $img->use_wordlist = true;


        $controller->set('captcha', $controller->captcha);
    }

}

?>
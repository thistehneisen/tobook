<?php
// +----------------------------------------------------------------------+
// | File name : Curl Wrapper  	                                          |
// |(generalised CURL functions) |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              		  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                    |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class Curlwrapper {

    private $ch;
    private $transfer_flag;

    public function __construct($url) {
        $this->url = $url;
        $this->ch = curl_init();
        $this->setOpt(CURLOPT_URL, $url);

        // Set the default options
        $this->setOpt(CURLOPT_HEADER, false);
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $this->setOpt(CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        }
        else {
            $this->setOpt(CURLOPT_USERAGENT, '');
        }
        $this->setOpt(CURLOPT_FOLLOWLOCATION, true);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->setOpt(CURLOPT_MAXREDIRS, 10);
        $this->setOpt(CURLOPT_TIMEOUT, 30);
        $this->setOpt(CURLOPT_COOKIEJAR, 'cookie.txt');
        $this->setOpt(CURLOPT_COOKIEFILE, 'cookie.txt');
        $this->setopt(CURLOPT_SSL_VERIFYPEER, false);
    }

    public function setOpt($name, $value) {
        $this->opts[$name] = $value;
        curl_setopt($this->ch, $name, $value);
    }

    public function get() {
        return $this->exec();
    }

    public function close() {
        curl_close($this->ch);
    }

    public function getEffectiveUrl() {
      $this->getHeaders(true);
      $effectiveUrl = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
      return (!$effectiveUrl || is_null($effectiveUrl) || empty($effectiveUrl)) ? $this->url : $effectiveUrl;
    }

    public function getHeaders($nobody = false) {
        $this->setOpt(CURLOPT_HEADER, true);
        if ($nobody == true) {
            $this->setOpt(CURLOPT_HTTPGET, false);
            $this->setOpt(CURLOPT_NOBODY, true);
        }
        $headers = curl_exec($this->ch);
        $this->setOpt(CURLOPT_HEADER, false);
        if ($nobody == true) {
            $this->setOpt(CURLOPT_NOBODY, false);
            $this->setOpt(CURLOPT_HTTPGET, true);
        }
        return $headers;
    }

    public function post($vars=array()) {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $vars);
        return $this->exec();
    }

    private function exec() {
        $ret = curl_exec($this->ch);
        if($ret === false) {
            throw new Exception(curl_error($this->ch));
        }
        $this->transfer_flag = true;
        return $ret;
    }

    public function copyToFile($file_or_fp) {
        if(!$file_or_fp) {
            throw new Exception("Expected file name or file pointer. Passed null.");
        }
        $fp = $file_or_fp;
        if(!is_resource($file_or_fp)) {
            $fp = fopen($file_or_fp, "w+");
            if(!$fp) {
                throw new Exception("Failed to open file [$file_or_fp]");
                return false;
            }
        }
        $this->setOpt(CURLOPT_RETURNTRANSFER, false);
        $this->setOpt(CURLOPT_FILE, $fp);
        $ret = $this->exec();
        if(!$ret){
            throw new Exception(curl_error($this->ch));
        }
        if(!is_resource($file_or_fp)) {
            fclose($fp);
        }
    }

    public function getInfo($opt) {
        if(!$this->transfer_flag) {
            throw new Exception("No transfer has yet taken place on this CURL handle");
        }
        return curl_getinfo($this->ch, $opt);
    }

    public function getContentType() {
        return $this->getInfo(CURLINFO_CONTENT_TYPE);
    }

    public function getContentSize() {
        return $this->getInfo(CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    }

}

?>

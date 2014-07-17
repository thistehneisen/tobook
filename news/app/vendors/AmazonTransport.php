<?php
$amazonerror="";
class Swift_Transport_AmazonTransport implements Swift_Transport {
    // -- Protected methods

    /** Get the params to initialize the buffer */
    protected function _getBufferParams() {
        return $this->_params;
    }

    protected static $instance;
    protected $region;
    var $skey;
	var $key;
	var $ses;

    public function __construct($k, $sk) {
        require_once 'sdk-1.2.6/sdk.class.php';
        $this->skey = $sk;
        $this->key = $k;
	    $this->ses = new AmazonSES($this->key, $this->skey);
    }

    public static function newInstance($key1, $skey1) {
        if (self::$instance == null) {
            self::$instance = new Swift_Transport_AmazonTransport($key1, $skey1);
        }
        return self::$instance;
    }

    public function isStarted() {
        return true;
    }

    public function registerPlugin(Swift_Events_EventListener $plugin) {
        
    }

    /**
     *
     * @param Swift_Message $message
     * @param reference $failedRecipients
     */
    public function send(Swift_Mime_Message $message, &$failedRecipients = null) {
        $from = $message->getFrom();
        if (count($from) == 0) {
            throw new Exception('The "from" message property is undefined', E_ERROR);
        }
        $source = $this->formatAddresses($from);

        $destination = array(
            'ToAddresses' => $this->formatAddresses($message->getTo()),
            'CcAddresses' => $this->formatAddresses($message->getCc()),
            'BccAddresses' => $this->formatAddresses($message->getBcc())
        );

        $msg = array(
            'Subject.Data' => $message->getSubject(),
            'Subject.Charset' => $message->getCharset(),
            'Body.Html.Data' => $message->toString(),
            'Body.Html.Charset' => $message->getCharset()
        );





        $data = array('Data' => base64_encode($message->toString()));
        $resp = $this->ses->send_raw_email($data);
        if (200 == $resp->status) {
            return count((array) $message->getTo());
        } else {
			throw new Exception($resp->body->Error->Message->to_string());
 
            return 0;
        }
    }

    public function start() {

    }

    public function stop() {

    }

    protected function formatAddresses($addresses) {
        $newAddresses = array();
        if (count($addresses) == 0) {
            return array();
        }
        foreach ($addresses as $address => $name) {
            if ($name != null) {
                $newAddresses[] = $name . ' <' . $address . '>';
            } else {
                $newAddresses[] = $address;
            }
        }


        return $newAddresses;
    }

}

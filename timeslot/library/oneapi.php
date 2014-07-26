<?php

define('USERNAME', 'varaa6');
define('PASSWORD', 'varaa12');

/**
 * Send message and check for delivery status untill it is delivered.
 *
 * Use ../examples.php to test this file
 */

require_once 'oneapi/client.php';

class pjSMS {
	
	public function __construct(){
		
	}
	
	public function sendSMS($send_address, $send_address, $message) {
		# example:initialize-sms-client
		$smsClient = new SmsClient(USERNAME, PASSWORD);
		# ----------------------------------------------------------------------------------------------------
		
		# example:login-sms-client
		$smsClient->login();
		
		# example:prepare-message-without-notify-url
		$smsMessage = new SMSRequest();
		$smsMessage->senderAddress = $send_address;
		$smsMessage->address = $send_address;
		$smsMessage->message = $message;
		# ----------------------------------------------------------------------------------------------------
		
		# example:send-message
		$smsMessageSendResult = $smsClient->sendSMS($smsMessage);
		# ----------------------------------------------------------------------------------------------------
		//
		# example:send-message-client-correlator
		// The client correlator is a unique identifier of this api call:
		$clientCorrelator = $smsMessageSendResult->clientCorrelator;
	}
}



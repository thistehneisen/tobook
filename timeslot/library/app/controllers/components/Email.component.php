<?php
/**
 * E-Mail component
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers.components
 */
class Email
{
/**
 * E-Mail regular expression
 *
 * @var string
 * @access private
 */
	var $emailRegExp = '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i';
/**
 * End of line
 *
 * @var string
 * @access private
 */
	var $eol = "\r\n";
/**
 * Content type
 *
 * @var string
 * @access private
 */
	var $contentType = "text/plain";
/**
 * Character set
 *
 * @var string
 * @access private
 */
	var $charset = "utf-8";
/**
 * Constructor
 */
	function Email()
	{
		//constructor
	}
/**
 * Send mail
 *
 * @param string $to Receiver of the mail
 * @param string $subject Subject of the email to be sent
 * @param string $message Message to be sent
 * @param string $sender Sender of the mail
 * @access public
 * @return bool Returns TRUE if the mail was successfully accepted for delivery, FALSE otherwise
 */
	function send($to, $subject, $message, $sender)
	{
		if (!preg_match($this->emailRegExp, $to))
		{
			return false;
		}
		
		if (!preg_match($this->emailRegExp, $sender))
		{
			return false;
		}
		
		$headers  = "MIME-Version: 1.0" . $this->eol;
		$headers .= "Content-type: ".$this->contentType."; charset=" . $this->charset . $this->eol;
		$headers .= "From: $sender" . $this->eol;
		$headers .= "Reply-To: $sender" . $this->eol;
		//$headers .= "Return-Path: $sender" . $this->eol;
		//$headers .= "X-Mailer: PHP/" . phpversion() . $this->eol;
		//$headers .= "Message-Id:" . md5(time()) . $this->eol;
		//$headers .= "X-Originating-IP:" . $_SERVER['REMOTE_ADDR'];
		
		$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
		return @mail($to, $subject, $message, $headers);
	}
/**
 * Set character set
 *
 * @param string $charset Character set
 * @access public
 */
	function setCharset($charset)
	{
		$this->charset = $charset;
	}
/**
 * Set content type
 *
 * @param string $contentType Content type
 * @access public
 * @return bool|void
 */
	function setContentType($contentType)
	{
		if (!in_array($contentType, array('text/plain', 'text/html')))
		{
			return false;
		}
		$this->contentType = $contentType;
	}
/**
 * Set end of line
 *
 * @param string $eol End of line (\r\n, \n...)
 * @access public
 */
	function setEol($eol)
	{
		$this->eol = $eol;
	}
}
?>
<?php
		$email = "jenistar90@gmail.com";
		$subject = "testing";
		$message = "apple of eye";
		
		$body = "<html>\n"; 
		$body .= "<body style=\"font-family:Verdana, Verdana, Geneva, sans-serif; font-size:20px; color:#FF0000;\">\n"; 
		$body .= $message; 
		$body .= "</body>\n"; 
		$body .= "</html>\n"; 		
		
		require_once dirname(__FILE__) . '/class.phpmailer.php';
		$mailer = new PHPMailer();
		$mailer->CharSet = 'utf-8';
		$mailer->IsMail();
		$mailer->Sender = "jenistar90";
		$mailer->From = "jenistar90@klikkaaja.com";
		$mailer->FromName = $subject;
		$mailer->AddAddress($email, "test");
		$mailer->WordWrap = 70;
		$mailer->IsHTML(TRUE);
		$mailer->Subject = $subject;
		$mailer->Body = $body;
		return $mailer->Send();
?>
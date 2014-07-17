<?php
	require_once dirname(__FILE__) . '/class.phpmailer.php';
    $result = "success";
    $error = "";
    $data = array();

    $email = $_POST['mail'];
    $subject = "testing";
    
    $html = $_POST['content'];
    $body = "<!DOCTYPE html>";
    $body.= "<html>";
    $body.= "<body>";
    $body.= "<div style='background:#FAFAFA; padding: 10px;'><div style='padding: 15px; border:1px solid #DDD;background:#FFF;'>";    
    $body.= "	<div style='width:100%; text-align:center;'>";
    $body.= "	<img src='http://klikkaaja.com/images/kikalogo.png'/>";
    $body.= "	</div>";
    $body.= $html;
    
    $body.= "</div></div>";
    $body.= "</body>";
    $body.= "</html>";
    
    // $body = $_POST['mail'];
    
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
    
    $mailer->Send();

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);    
?>

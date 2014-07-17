<?php
error_reporting(0);
extract($_GET);
$mailTo = $_GET['mailto'];
if($mailTo != '') {
    $mailBody = '<p>Hi Administrator,</p>
<p>A new contact request has been added your site. The details are as follows.</p>
<p>{CONTACT_DETAILS}</p>
	';
    foreach($_POST as $key=>$fields) {
        if($key != 'Submit') {
            $key = str_replace('_',' ', $key);
            $msgFields .= ucfirst($key).' : '.$fields.'<br>';
        }
    }

    $mailContent = str_replace('{CONTACT_DETAILS}',$msgFields,$mailBody);

    $subject = "Contact Request";
    $message = $mailContent;
    $from 	 = "admin@easycreate.com";
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: ". $from . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    mail($mailTo,$subject,$message,$headers);
    echo 'Successfully sent the request.';

}
else {
    echo "Some unexpected error occured.<br/>";
}
echo 'Click <a href="'.$_SERVER['HTTP_REFERER'].'">here<a> to continue.';

?>
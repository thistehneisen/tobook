<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");

$result = "success";
$error = "";
$data = array();

$campaignId = $_POST['campaignId'];
 
$sql = "select *
          from tbl_email_campaign
         where email_campaign = $campaignId";
$dataCampaign = $db->queryArray($sql);

$category = $dataCampaign[0]['category_code'];
$message = MT_mailImage(utf8_encode($dataCampaign[0]['content']));
$subject = $dataCampaign[0]['subject'];
$replyEmail = $dataCampaign[0]['reply_email'];
$replyName = $dataCampaign[0]['reply_name'];
$campaignId = $dataCampaign[0]['email_campaign'];

$recipients = array();
$sql = "select t2.destination
          from tbl_marketing_message t1, tbl_marketing_history t2
         where t1.content = $campaignId
           and t1.marketing_message = t2.marketing_message";
$dataCustomer = $db->queryArray($sql);
for ($j = 0; $j < count($dataCustomer); $j++) {
    $recipients[] = $dataCustomer[$j]['destination'];
}
MT_sendEmailBulk($recipients, $replyEmail, $replyName, $subject, $message, 1, $category);
$sql = "update tbl_email_campaign
           set status = 'SENT'
         where email_campaign = '$campaignId'";
$db->query($sql);
	
$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>
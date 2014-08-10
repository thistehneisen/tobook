<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");

$result = "success";
$error = "";
$data = array();

$replyName = mysql_escape_string($_POST['replyName']);
$replyEmail = mysql_escape_string($_POST['replyEmail']);
$ownerId = mysql_escape_string($_POST['ownerId']);
$subject = mysql_escape_string($_POST['subject']);
$content = mysql_escape_string($_POST['content']);
$campaignId = mysql_escape_string($_POST['campaignId']);
$categoryCode = time()."_".MT_generateRandom(32);
if ($campaignId == "") {
    $sql = "insert into tbl_email_campaign(owner, reply_email, reply_name, subject, content, status, category_code, created_time, updated_time)
             value ($ownerId, '$replyEmail', '$replyName', '$subject', '$content', 'DRAFT','$categoryCode', now(), now())";
    $db->queryInsert($sql);
} else {
    $sql = "update tbl_email_campaign
               set subject = '$subject'
                 , reply_email = '$replyEmail'
                 , reply_name = '$replyName'
                 , content = '$content'
                 , updated_time = now()
             where email_campaign = $campaignId";
    $db->query($sql);
}

$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>
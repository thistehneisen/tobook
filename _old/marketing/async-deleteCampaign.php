<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");

$result = "success";
$error = "";
$data = array();

$campaignIds = mysql_escape_string($_POST['campaignIds']);

$sql = "delete
          from tbl_email_campaign
         where email_campaign in ($campaignIds)";
$db->query($sql);

$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>
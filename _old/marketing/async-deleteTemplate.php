<?php
require_once("../DB_Connection.php");
require_once("common/functions.php");

$result = "success";
$error = "";
$data = array();

$templateIds = mysql_escape_string($_POST['templateIds']);

$sql = "delete
          from tbl_email_template
         where email_template in ($templateIds)";
$db->query($sql);

$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>
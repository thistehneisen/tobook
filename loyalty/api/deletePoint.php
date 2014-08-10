<?php
require_once("../../DB_Connection.php");
require_once("../common/functions.php");

$result = "success";
$error = "";
$msg = "";
$data = array();

$customerToken = mysql_escape_string( $_POST['customerToken'] );
$pointId = mysql_escape_string( $_POST['pointId'] );
$customerId = base64_decode( base64_decode( $customerToken ) );

$sql = "delete
          from tbl_loyalty_point
         where loyalty_point = '$pointId'";
$db->query($sql);

$data['msg'] = $msg;
$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>

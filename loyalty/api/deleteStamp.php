<?php
	require_once("../../marketing/common/DB_Connection.php");	
    require_once("../../marketing/common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $stampId = mysql_escape_string( $_POST['stampId'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
	$sql = "delete from tbl_loyalty_stamp where loyalty_stamp = '$stampId'";
	$db->query( $sql );
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

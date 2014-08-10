<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $consumerId = mysql_escape_string( $_POST['consumerId'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
    $sql = "delete from tbl_loyalty_consumer where loyalty_consumer = $consumerId";
    $db->query($sql);
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

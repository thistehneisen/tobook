<?php
	require_once("../../marketing/common/DB_Connection.php");	
    require_once("../../marketing/common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    $consumerId = mysql_escape_string( $_POST['consumerId'] );
    $stampId = mysql_escape_string( $_POST['stampId'] );
    
    $sql = "insert into tbl_loyalty_consumer_stamp( loyalty_consumer, loyalty_stamp, created_time, updated_time )
    		value( '$consumerId', '$stampId', now(), now() )";
    $db->queryInsert( $sql );
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

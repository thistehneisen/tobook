<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    $consumerId = mysql_escape_string( $_POST['consumerId'] );
    $stampId = mysql_escape_string( $_POST['stampId'] );

    $sql = "select * from tbl_loyalty_consumer_stamp where loyalty_consumer = $consumerId and loyalty_stamp = $stampId";

    $dataConsumerStamp = $db->queryArray($sql);
    $dataConsumerStamp = $dataConsumerStamp[0];
    
    if( $dataConsumerStamp['cnt_free'] != "0" ){
    	$sql = "update tbl_loyalty_consumer_stamp
    			   set cnt_free = cnt_free - 1
    			 where loyalty_consumer = $consumerId
    			   and loyalty_stamp = $stampId";
    	$db->query($sql);
    }else{
    	$msg = "You don't have enough free Stamp.";
    	$error = "LC002";
    	$result = "failed";
    }
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

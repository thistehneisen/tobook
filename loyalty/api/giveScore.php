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
    $score = mysql_escape_string( $_POST['score'] );
    
    $sql = "update tbl_loyalty_consumer
    		   set current_score = current_score + $score
    		 where loyalty_consumer = $consumerId";
    $db->query( $sql );
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

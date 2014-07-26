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
    $pointId = mysql_escape_string( $_POST['pointId'] );
    
    $sql = "select score_required as scoreRequired
    		  from tbl_loyalty_point
    		 where loyalty_point = $pointId";
    $dataPoint = $db->queryArray( $sql );
    $dataPoint = $dataPoint[0];
    $scoreRequired = $dataPoint['scoreRequired'];
    
    $sql = "insert into tbl_loyalty_consumer_point( loyalty_consumer, loyalty_point, created_time, updated_time )
    		value( '$consumerId', '$pointId', now(), now() )";
    $db->queryInsert( $sql );
    
    $sql = "update tbl_loyalty_consumer
    		   set current_score = current_score - $scoreRequired
    		 where loyalty_consumer = $consumerId";
    $db->query( $sql );
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

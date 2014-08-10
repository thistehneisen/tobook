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
    
    $sql = "select * 
    		  from tbl_loyalty_consumer_stamp
    		 where loyalty_consumer = $consumerId
    		   and loyalty_stamp = $stampId";
    $dataConsumerStamp = $db->queryArray($sql);
    if( $dataConsumerStamp == null ){
    	$sql = "insert into tbl_loyalty_consumer_stamp( loyalty_consumer, loyalty_stamp, cnt_used, cnt_free, created_time, updated_time )
    			values( $consumerId, $stampId, 1, 0, now(), now() )";
    	$db->query($sql);
    }else{
    	$dataConsumerStamp = $dataConsumerStamp[0];
    	$cntUsed = $dataConsumerStamp['cnt_used'];

    	$sql = "select * from tbl_loyalty_stamp where loyalty_stamp = $stampId";
    	$dataStamp = $db->queryArray($sql);
    	$dataStamp = $dataStamp[0];
    	
    	$cntStampRequired = $dataStamp['cnt_required'];
    	$cntStampFree = $dataStamp['cnt_free'];
    	
    	if( ( $cntUsed + 1 ) % $cntStampRequired != 0 ){
    		$cntStampFree = 0;
    		$subStr = "cnt_used + 1";
    	}else{
    		$subStr = "0";
    	}
    	
    	$sql = "update tbl_loyalty_consumer_stamp
    			   set cnt_used = $subStr
    				 , cnt_free = cnt_free + $cntStampFree
    			 where loyalty_consumer = $consumerId
    			   and loyalty_stamp = $stampId";
    	$db->query($sql);    	
    }
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

<?php
	require_once("../../marketing/common/DB_Connection.php");	
    require_once("../../marketing/common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $consumerId = mysql_escape_string( $_POST['consumerId'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
    $sql = "select first_name as firstName, last_name as lastName, email as email, phone as phone
    			 , address1 as address1, city as city, created_time as createdTime, updated_time as updatedTime, current_score as currentScore
    		  from tbl_loyalty_consumer
    		 where loyalty_consumer = $consumerId";
    $dataConsumer = $db->queryArray( $sql );
    $dataConsumer = $dataConsumer[0];   
    
    $data['firstName'] = $dataConsumer['firstName'];
    $data['lastName'] = $dataConsumer['lastName'];
    $data['email'] = $dataConsumer['email'];
    $data['phone'] = $dataConsumer['phone'];
    $data['address1'] = $dataConsumer['address1'];
    $data['city'] = $dataConsumer['city'];
    $data['createdTime'] = $dataConsumer['createdTime'];
    $data['updatedTime'] = $dataConsumer['updatedTime'];
    $data['currentScore'] = $dataConsumer['currentScore'];
    
    $sql = "select count(*) cntTotalUsed, loyalty_stamp
    		  from tbl_loyalty_consumer_stamp
    		 where loyalty_consumer = $consumerId
    		 group by loyalty_stamp";
    
    $sql = "select t1.loyalty_stamp as stampId, t1.stamp_name as stampName, t1.cnt_required as cntRequired, t1.cnt_free as cntFree
    			 , t1.created_time as createdTime, ( ifnull( t2.cntTotalUsed, 0 ) % t1.cnt_required ) as cntCurrentUsed
    		  from tbl_loyalty_stamp t1
    		  left join ( $sql ) t2 on t1.loyalty_stamp = t2.loyalty_stamp";
	$usedStampList = $db->queryArray( $sql );
	if( $usedStampList == null )
		$usedStampList = array( );
	
	$data['usedStampList'] = $usedStampList;
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

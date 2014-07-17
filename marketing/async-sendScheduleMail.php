<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $ownerId = mysql_escape_string( $_POST['ownerId'] );
    $campaignId = mysql_escape_string( $_POST['campaignId'] );
    $scheduleTime = mysql_escape_string( $_POST['scheduleTime'] );
    $customerList = $_POST['customerList'];
    $marketingType = 'M';
    
    $sql = "insert into tbl_marketing_message( title, content, type, created_time, updated_time)
    		values( '', '$campaignId', '$marketingType', now(), now())";
    $db->queryInsert( $sql );
    
    $marketingMessage = $db->getPrevInsertId();    
    
    $recipients = array( );
    for( $i = 0 ; $i < count( $customerList ); $i ++ ){
    	$customerId = $customerList[$i]['customerId'];
    	$planGroupCode = $customerList[$i]['planGroupCode'];
    	$destination =  $customerList[$i]['destination'];
    	$recipients[] = $destination;
    
    	$sql = "insert into tbl_marketing_history( owner, customer, plan_group_code, destination, messageId, status, marketing_type, marketing_message, created_time, updated_time)
    			values($ownerId, $customerId, '$planGroupCode', '$destination', '', 'success', '$marketingType', $marketingMessage, now(), now() )";
    	$db->queryInsert( $sql );
    }    

    $sql = "update tbl_email_campaign
    		   set status = 'SCHEDULED'
    		     , launched_time = '$scheduleTime'
    		 where email_campaign = '$campaignId'";
    $db->query( $sql );
    	    
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);    
?>

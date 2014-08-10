<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
    $sql = "select loyalty_consumer as consumerId, first_name as firstName, last_name as lastName, email as email
    			 , phone as phone, address1 as address1, city as city, created_time as createdTime, updated_time as updatedTime, current_score as currentScore
    		  from tbl_loyalty_consumer
    		 where owner = $customerId
    		 order by created_time desc";
    $consumerList = $db->queryArray($sql);
    if( $consumerList == null )
    	$consumerList = array( );
    
    $data['consumerList'] = $consumerList;
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

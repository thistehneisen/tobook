<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $consumerId = mysql_escape_string( $_POST['consumerId'] );
    $firstName = mysql_escape_string( $_POST['firstName'] );
    $lastName = mysql_escape_string( $_POST['lastName'] );
    $email = mysql_escape_string( $_POST['email'] );
    $phone = mysql_escape_string( $_POST['phone'] );
    $address1 = mysql_escape_string( $_POST['address1'] );
    $city = mysql_escape_string( $_POST['city'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
    if( $consumerId == "" ){
    	$sql = "insert into tbl_loyalty_consumer( owner, first_name, last_name, email, phone, address1, city, created_time, updated_time)
    			value( '$customerId', '$firstName', '$lastName', '$email', '$phone', '$address1', '$city', now(), now())";
    	$db->queryInsert( $sql );
    	$consumerId = $db->getPrevInsertId();
    	$data['consumerId'] = $consumerId;
    }else{
    	$sql = "update tbl_loyalty_consumer
    			   set owner = '$customerId'
    			     , first_name = '$firstName'
    			     , last_name = '$lastName'
    			     , email = '$email'
    			     , phone = '$phone'
    			     , address1 = '$address1'
    			     , city = '$city'
    			     , updated_time = now()
    			 where loyalty_consumer = $consumerId";
    	$db->query( $sql );
    	$data['consumerId'] = $consumerId;
    }
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

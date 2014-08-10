<?php
	require_once("../../DB_Connection.php");
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $ownerId = mysql_escape_string($_POST['ownerId']);
    $consumerId = mysql_escape_string($_POST['consumerId']);
    $firstName = mysql_escape_string($_POST['firstName']);
    $lastName = mysql_escape_string($_POST['lastName']);
    $email = mysql_escape_string($_POST['email']);
    $phone = mysql_escape_string($_POST['phone']);
    $address1 = mysql_escape_string($_POST['address1']);
    $city = mysql_escape_string($_POST['city']);
        
    if( $consumerId == "" ){
	    $sql = "insert into tbl_loyalty_consumer( owner, first_name, last_name, email, phone, address1, city, created_time, updated_time )
	    		value( $ownerId, '$firstName', '$lastName', '$email', '$phone', '$address1','$city', now(), now())";
	    $db->queryInsert($sql);
    }else{
    	$sql = "update tbl_loyalty_consumer
    			   set first_name = '$firstName'
    			     , last_name = '$lastName'
    			     , email = '$email'
    			     , phone = '$phone'
    			     , address1 = '$address1'
    			     , city = '$city'
    			     , updated_time = now()
    			 where loyalty_consumer = $consumerId";
    	$db->query($sql);
    }

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $stampId = mysql_escape_string( $_POST['stampId'] );
    $stampName = mysql_escape_string( $_POST['stampName'] );
    $cntRequired = mysql_escape_string( $_POST['cntRequired'] );
    $cntFree = mysql_escape_string( $_POST['cntFree'] );
    $validYn = mysql_escape_string( $_POST['validYn'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
    if( $stampId == "" ){
    	$sql = "insert into tbl_loyalty_stamp( owner, stamp_name, cnt_required, cnt_free, valid_yn, created_time, updated_time )
    			value( '$customerId', '$stampName', '$cntRequired', '$cntFree', '$validYn', now(), now())";
    	$db->query($sql);
    	$stampId = $db->getPrevInsertId();
    }else{
    	$sql = "update tbl_loyalty_stamp
    			   set owner = '$customerId'
    			     , stamp_name = '$stampName'
    			     , cnt_required = '$cntRequired'
    			     , cnt_free = '$cntFree'
    			     , valid_yn = '$validYn'
    			     , updated_time = now()
    			 where loyalty_stamp = $stampId";
    	$db->query($sql);
    }
    
    $data['stampId'] = $stampId;
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

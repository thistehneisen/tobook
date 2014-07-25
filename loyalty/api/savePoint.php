<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $pointId = mysql_escape_string( $_POST['pointId'] );
    $pointName = mysql_escape_string( $_POST['pointName'] );
    $scoreRequired = mysql_escape_string( $_POST['scoreRequired'] );
    $discount = mysql_escape_string( $_POST['discount'] );
    $validYn = mysql_escape_string( $_POST['validYn'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
    if( $pointId == "" ){
    	$sql = "insert into tbl_loyalty_point( owner, point_name, score_required, discount, valid_yn, created_time, updated_time )
    			value( '$customerId', '$pointName', '$scoreRequired', '$discount', '$validYn', now(), now())";
    	$db->query( $sql );
    	$pointId = $db->getPrevInsertId();
    }else{
    	$sql = "update tbl_loyalty_point
    			   set owner = '$customerId'
    			     , point_name = '$pointName'
    			     , score_required = '$scoreRequired'
    			     , discount = '$discount'
    			     , valid_yn = '$validYn'
    			     , updated_time = now()
    			 where loyalty_point = $pointId";
    	$db->query( $sql );
    }
    
    $data['pointId'] = $pointId;
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

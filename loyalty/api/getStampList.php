<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
	$sql = "select loyalty_stamp as stampId, stamp_name as stampName, cnt_required as cntRequired
				 , cnt_free as cntFree, created_time as createdTime, valid_yn as validYn
			  from tbl_loyalty_stamp
			 where owner = $customerId";
    $stampList = $db->queryArray( $sql );
    if( $stampList == null )
    	$stampList = array( );
    
    $data['stampList'] = $stampList;
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

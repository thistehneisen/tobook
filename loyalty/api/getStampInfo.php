<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $stampId = mysql_escape_string( $_POST['stampId'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
	$sql = "select loyalty_stamp as stampId, stamp_name as stampName, cnt_required as cntRequired, cnt_free as cntFree
				 , valid_yn as validYn, created_time as createdTime, updated_time as updatedTime
			  from tbl_loyalty_stamp
			 where loyalty_stamp = '$stampId'";
	$db->queryArray( $sql );
    $dataStamp = $db->queryArray( $sql );
    $dataStamp = $dataStamp[0];
    
    $data['stampName'] = $dataStamp['stampName'];
    $data['cntRequired'] = $dataStamp['cntRequired'];
    $data['cntFree'] = $dataStamp['cntFree'];
    $data['validYn'] = $dataStamp['validYn'];
    $data['createdTime'] = $dataStamp['createdTime'];
    $data['updatedTime'] = $dataStamp['updatedTime'];
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

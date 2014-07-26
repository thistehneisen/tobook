<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();

    $ownerId = mysql_escape_string($_POST['ownerId']);
    $stampId = mysql_escape_string($_POST['stampId']);
    $stampName = mysql_escape_string($_POST['stampName']);
    $cntRequired = mysql_escape_string($_POST['cntRequired']);
    $cntFree = mysql_escape_string($_POST['cntFree']);
    $validYn = mysql_escape_string($_POST['validYn']);
        
    if( $stampId == "" ){
	    $sql = "insert into tbl_loyalty_stamp( owner, stamp_name, cnt_required, cnt_free, valid_yn, created_time, updated_time )
	    		value( $ownerId, '$stampName', $cntRequired, $cntFree, '$validYn', now(), now())";
	    $db->queryInsert( $sql );
    }else{
    	$sql = "update tbl_loyalty_stamp
    			   set stamp_name = '$stampName'
    			     , cnt_required = '$cntRequired'
    			     , cnt_free = '$cntFree'
    			     , valid_yn = '$validYn'
    			     , updated_time = now()
    			 where loyalty_stamp = $stampId";
    	$db->query( $sql );
    }

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

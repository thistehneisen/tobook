<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
	$sql = "select loyalty_point as pointId, point_name as pointName, score_required as scoreRequired
				 , discount as discount, created_time as createdTime, valid_yn as validYn
			  from tbl_loyalty_point
			 where owner = $customerId";
    $pointList = $db->queryArray($sql);
    if( $pointList == null )
    	$pointList = array( );
    
    $data['pointList'] = $pointList;
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

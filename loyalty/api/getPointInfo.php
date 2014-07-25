<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $pointId = mysql_escape_string( $_POST['pointId'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
	$sql = "select point_name as pointName, score_required as scoreRequired, discount as discount
				 , valid_yn as validYn, created_time as createdTime, updated_time as updatedTime
			  from tbl_loyalty_point
			 where loyalty_point = '$pointId'";
	$db->queryArray( $sql );
    $dataPoint = $db->queryArray( $sql );
    $dataPoint = $dataPoint[0];
    
    $data['pointName'] = $dataPoint['pointName'];
    $data['scoreRequired'] = $dataPoint['scoreRequired'];
    $data['discount'] = $dataPoint['discount'];
    $data['validYn'] = $dataPoint['validYn'];
    $data['createdTime'] = $dataPoint['createdTime'];
    $data['updatedTime'] = $dataPoint['updatedTime'];
    
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

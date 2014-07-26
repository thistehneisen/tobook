<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $username = mysql_escape_string( $_POST['username'] );
    $password = mysql_escape_string( $_POST['password'] );
    
    $sql = "select *
    		  from tbl_user_mast
    		 where vuser_login = '$username'
    		   and vuser_password = md5('$password')";
    $dataUser = $db->queryArray( $sql );
    if( $dataUser != null ){
    	$dataUser = $dataUser[0];
    	$customerId = $dataUser['nuser_id'];
    	$customerToken = base64_encode(base64_encode($customerId));
    	$data['customerToken'] = $customerToken;
    }else{
    	$result = "failed";
    	$msg = "User authentication failed.";
    	$error = "LC001";
    }

    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

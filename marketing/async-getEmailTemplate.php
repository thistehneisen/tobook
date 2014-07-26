<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $emailId = mysql_escape_string($_POST['emailId']);
    $sql = "select * from tbl_email_template where email_template = $emailId";
    $dataEmail = $db->queryArray( $sql );
    
    $data['content'] = utf8_encode( $dataEmail['0']['content'] );
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

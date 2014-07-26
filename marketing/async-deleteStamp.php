<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $stampIds = mysql_escape_string($_POST['stampIds']);
    
    $sql = "delete from tbl_loyalty_stamp where loyalty_stamp in ($stampIds)";
    $db->query( $sql );

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

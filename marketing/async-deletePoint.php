<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $pointIds = mysql_escape_string($_POST['pointIds']);
    
    $sql = "delete from tbl_loyalty_point where loyalty_point in ($pointIds)";
    $db->query( $sql );

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>

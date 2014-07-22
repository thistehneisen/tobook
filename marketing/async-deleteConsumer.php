<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $consumerIds = mysql_escape_string($_POST['consumerIds']);
    
    
    $sql = "delete from tbl_loyalty_consumer where loyalty_consumer in ($consumerIds)";
    $db->query( $sql );

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
